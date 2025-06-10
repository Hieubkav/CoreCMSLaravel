<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BackupSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'included_tables',
        'excluded_tables',
        'included_directories',
        'excluded_directories',
        'file_patterns',
        'storage_type',
        'storage_path',
        'storage_config',
        'is_scheduled',
        'frequency',
        'cron_expression',
        'preferred_time',
        'preferred_days',
        'keep_daily',
        'keep_weekly',
        'keep_monthly',
        'keep_yearly',
        'auto_cleanup',
        'compression',
        'encrypt_backup',
        'encryption_key',
        'notify_on_success',
        'notify_on_failure',
        'notification_emails',
        'slack_webhook',
        'max_execution_time',
        'memory_limit',
        'chunk_size',
        'use_single_transaction',
        'verify_backup',
        'test_restore',
        'checksum_algorithm',
        'last_run_at',
        'next_run_at',
        'last_status',
        'last_error',
        'last_backup_size',
        'last_duration',
        'total_backups_count',
        'status',
        'order',
    ];

    protected $casts = [
        'included_tables' => 'array',
        'excluded_tables' => 'array',
        'included_directories' => 'array',
        'excluded_directories' => 'array',
        'file_patterns' => 'array',
        'storage_config' => 'array',
        'is_scheduled' => 'boolean',
        'preferred_time' => 'time',
        'preferred_days' => 'array',
        'keep_daily' => 'integer',
        'keep_weekly' => 'integer',
        'keep_monthly' => 'integer',
        'keep_yearly' => 'integer',
        'auto_cleanup' => 'boolean',
        'encrypt_backup' => 'boolean',
        'notify_on_success' => 'boolean',
        'notify_on_failure' => 'boolean',
        'notification_emails' => 'array',
        'max_execution_time' => 'integer',
        'memory_limit' => 'integer',
        'chunk_size' => 'integer',
        'use_single_transaction' => 'boolean',
        'verify_backup' => 'boolean',
        'test_restore' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'last_backup_size' => 'integer',
        'last_duration' => 'integer',
        'total_backups_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Scope cho backup settings active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope cho scheduled backups
     */
    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    /**
     * Scope cho backups cần chạy
     */
    public function scopeDue($query)
    {
        return $query->where('is_scheduled', true)
                    ->where('status', 'active')
                    ->where('next_run_at', '<=', now());
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Lấy danh sách backup types
     */
    public static function getBackupTypes(): array
    {
        return [
            'full' => 'Backup toàn bộ',
            'database_only' => 'Chỉ cơ sở dữ liệu',
            'files_only' => 'Chỉ files',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Lấy danh sách storage types
     */
    public static function getStorageTypes(): array
    {
        return [
            'local' => 'Lưu trữ cục bộ',
            's3' => 'Amazon S3',
            'ftp' => 'FTP',
            'sftp' => 'SFTP',
            'google_drive' => 'Google Drive',
            'dropbox' => 'Dropbox',
        ];
    }

    /**
     * Lấy danh sách frequencies
     */
    public static function getFrequencies(): array
    {
        return [
            'hourly' => 'Mỗi giờ',
            'daily' => 'Hàng ngày',
            'weekly' => 'Hàng tuần',
            'monthly' => 'Hàng tháng',
        ];
    }

    /**
     * Lấy danh sách compression types
     */
    public static function getCompressionTypes(): array
    {
        return [
            'none' => 'Không nén',
            'gzip' => 'Gzip',
            'zip' => 'Zip',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getBackupTypes()[$this->type] ?? $this->type;
    }

    /**
     * Lấy storage type label
     */
    public function getStorageTypeLabelAttribute(): string
    {
        return static::getStorageTypes()[$this->storage_type] ?? $this->storage_type;
    }

    /**
     * Lấy frequency label
     */
    public function getFrequencyLabelAttribute(): string
    {
        return static::getFrequencies()[$this->frequency] ?? $this->frequency;
    }

    /**
     * Lấy compression label
     */
    public function getCompressionLabelAttribute(): string
    {
        return static::getCompressionTypes()[$this->compression] ?? $this->compression;
    }

    /**
     * Tính next run time
     */
    public function calculateNextRun(): ?Carbon
    {
        if (!$this->is_scheduled || $this->status !== 'active') {
            return null;
        }

        $now = now();
        $preferredTime = Carbon::createFromFormat('H:i:s', $this->preferred_time);

        switch ($this->frequency) {
            case 'hourly':
                return $now->addHour();

            case 'daily':
                $next = $now->copy()->setTime($preferredTime->hour, $preferredTime->minute);
                if ($next <= $now) {
                    $next->addDay();
                }
                return $next;

            case 'weekly':
                $preferredDays = $this->preferred_days ?: [1]; // Default to Monday
                $next = $now->copy()->setTime($preferredTime->hour, $preferredTime->minute);
                
                while (!in_array($next->dayOfWeek, $preferredDays) || $next <= $now) {
                    $next->addDay();
                }
                return $next;

            case 'monthly':
                $next = $now->copy()->setTime($preferredTime->hour, $preferredTime->minute);
                if ($next <= $now) {
                    $next->addMonth();
                }
                return $next;

            default:
                if ($this->cron_expression) {
                    // Parse cron expression (would need a cron parser library)
                    return $now->addDay(); // Fallback
                }
                return null;
        }
    }

    /**
     * Update next run time
     */
    public function updateNextRun(): void
    {
        $this->next_run_at = $this->calculateNextRun();
        $this->save();
    }

    /**
     * Run backup
     */
    public function runBackup(): array
    {
        try {
            $this->update([
                'last_status' => 'running',
                'last_run_at' => now(),
            ]);

            $startTime = microtime(true);
            
            // Perform backup based on type
            $result = $this->performBackup();
            
            $duration = round((microtime(true) - $startTime));
            
            $this->update([
                'last_status' => 'success',
                'last_duration' => $duration,
                'last_backup_size' => $result['size'] ?? 0,
                'last_error' => null,
                'total_backups_count' => $this->total_backups_count + 1,
            ]);

            $this->updateNextRun();

            if ($this->notify_on_success) {
                $this->sendNotification('success', $result);
            }

            return [
                'success' => true,
                'message' => 'Backup completed successfully',
                'result' => $result,
            ];

        } catch (\Exception $e) {
            $this->update([
                'last_status' => 'failed',
                'last_error' => $e->getMessage(),
            ]);

            if ($this->notify_on_failure) {
                $this->sendNotification('failure', ['error' => $e->getMessage()]);
            }

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Perform actual backup
     */
    private function performBackup(): array
    {
        $filename = $this->generateBackupFilename();
        $tempPath = storage_path('app/temp/' . $filename);

        // Create backup based on type
        switch ($this->type) {
            case 'database_only':
                $size = $this->backupDatabase($tempPath);
                break;
                
            case 'files_only':
                $size = $this->backupFiles($tempPath);
                break;
                
            case 'full':
                $size = $this->backupFull($tempPath);
                break;
                
            case 'custom':
                $size = $this->backupCustom($tempPath);
                break;
                
            default:
                throw new \Exception('Unknown backup type: ' . $this->type);
        }

        // Compress if needed
        if ($this->compression !== 'none') {
            $compressedPath = $this->compressBackup($tempPath);
            unlink($tempPath);
            $tempPath = $compressedPath;
            $size = filesize($tempPath);
        }

        // Encrypt if needed
        if ($this->encrypt_backup) {
            $encryptedPath = $this->encryptBackup($tempPath);
            unlink($tempPath);
            $tempPath = $encryptedPath;
            $size = filesize($tempPath);
        }

        // Upload to storage
        $finalPath = $this->uploadToStorage($tempPath, $filename);
        
        // Cleanup temp file
        unlink($tempPath);

        // Verify backup if enabled
        if ($this->verify_backup) {
            $this->verifyBackup($finalPath);
        }

        // Cleanup old backups
        if ($this->auto_cleanup) {
            $this->cleanupOldBackups();
        }

        return [
            'filename' => $filename,
            'path' => $finalPath,
            'size' => $size,
        ];
    }

    /**
     * Generate backup filename
     */
    private function generateBackupFilename(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $this->compression === 'zip' ? 'zip' : 'sql';
        
        return "{$this->slug}_{$timestamp}.{$extension}";
    }

    /**
     * Backup database (placeholder)
     */
    private function backupDatabase(string $path): int
    {
        // Implement database backup logic
        // This would use mysqldump or similar
        file_put_contents($path, '-- Database backup placeholder');
        return filesize($path);
    }

    /**
     * Backup files (placeholder)
     */
    private function backupFiles(string $path): int
    {
        // Implement file backup logic
        file_put_contents($path, '-- Files backup placeholder');
        return filesize($path);
    }

    /**
     * Full backup (placeholder)
     */
    private function backupFull(string $path): int
    {
        // Implement full backup logic
        file_put_contents($path, '-- Full backup placeholder');
        return filesize($path);
    }

    /**
     * Custom backup (placeholder)
     */
    private function backupCustom(string $path): int
    {
        // Implement custom backup logic
        file_put_contents($path, '-- Custom backup placeholder');
        return filesize($path);
    }

    /**
     * Compress backup (placeholder)
     */
    private function compressBackup(string $path): string
    {
        // Implement compression logic
        return $path;
    }

    /**
     * Encrypt backup (placeholder)
     */
    private function encryptBackup(string $path): string
    {
        // Implement encryption logic
        return $path;
    }

    /**
     * Upload to storage (placeholder)
     */
    private function uploadToStorage(string $tempPath, string $filename): string
    {
        // Implement storage upload logic
        return $this->storage_path . '/' . $filename;
    }

    /**
     * Verify backup (placeholder)
     */
    private function verifyBackup(string $path): bool
    {
        // Implement backup verification
        return true;
    }

    /**
     * Cleanup old backups (placeholder)
     */
    private function cleanupOldBackups(): void
    {
        // Implement cleanup logic based on retention policy
    }

    /**
     * Send notification
     */
    private function sendNotification(string $type, array $data): void
    {
        // Implement notification sending
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
