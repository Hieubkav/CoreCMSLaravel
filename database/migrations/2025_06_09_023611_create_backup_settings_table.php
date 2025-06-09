<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Backup type and scope
            $table->enum('type', ['full', 'database_only', 'files_only', 'custom'])->default('full');
            $table->json('included_tables')->nullable(); // For database backups
            $table->json('excluded_tables')->nullable(); // Tables to exclude
            $table->json('included_directories')->nullable(); // For file backups
            $table->json('excluded_directories')->nullable(); // Directories to exclude
            $table->json('file_patterns')->nullable(); // File patterns to include/exclude

            // Storage configuration
            $table->enum('storage_type', ['local', 's3', 'ftp', 'sftp', 'google_drive', 'dropbox'])->default('local');
            $table->string('storage_path')->default('backups'); // Local path or remote path
            $table->json('storage_config')->nullable(); // Storage credentials and settings

            // Schedule configuration
            $table->boolean('is_scheduled')->default(false);
            $table->enum('frequency', ['hourly', 'daily', 'weekly', 'monthly'])->default('daily');
            $table->string('cron_expression')->nullable(); // Custom cron schedule
            $table->time('preferred_time')->default('02:00'); // Preferred backup time
            $table->json('preferred_days')->nullable(); // For weekly/monthly backups

            // Retention policy
            $table->integer('keep_daily')->default(7); // Keep daily backups for X days
            $table->integer('keep_weekly')->default(4); // Keep weekly backups for X weeks
            $table->integer('keep_monthly')->default(6); // Keep monthly backups for X months
            $table->integer('keep_yearly')->default(1); // Keep yearly backups for X years
            $table->boolean('auto_cleanup')->default(true);

            // Compression and encryption
            $table->enum('compression', ['none', 'gzip', 'zip'])->default('gzip');
            $table->boolean('encrypt_backup')->default(false);
            $table->string('encryption_key')->nullable(); // Encrypted storage

            // Notification settings
            $table->boolean('notify_on_success')->default(false);
            $table->boolean('notify_on_failure')->default(true);
            $table->json('notification_emails')->nullable();
            $table->string('slack_webhook')->nullable();

            // Performance settings
            $table->integer('max_execution_time')->default(3600); // Seconds
            $table->integer('memory_limit')->default(512); // MB
            $table->integer('chunk_size')->default(1000); // Records per chunk for large tables
            $table->boolean('use_single_transaction')->default(true);

            // Backup verification
            $table->boolean('verify_backup')->default(true);
            $table->boolean('test_restore')->default(false); // Test restore after backup
            $table->string('checksum_algorithm')->default('md5');

            // Metadata and tracking
            $table->datetime('last_run_at')->nullable();
            $table->datetime('next_run_at')->nullable();
            $table->enum('last_status', ['success', 'failed', 'running', 'pending'])->nullable();
            $table->text('last_error')->nullable();
            $table->bigInteger('last_backup_size')->nullable(); // Bytes
            $table->integer('last_duration')->nullable(); // Seconds
            $table->integer('total_backups_count')->default(0);

            $table->enum('status', ['active', 'inactive', 'paused'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'is_scheduled']);
            $table->index('next_run_at');
            $table->index('slug');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_settings');
    }
};
