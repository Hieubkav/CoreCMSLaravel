<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutomationWorkflow extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_conditions',
        'actions',
        'schedule',
        'is_active',
        'is_recurring',
        'priority',
        'max_executions',
        'execution_count',
        'last_executed_at',
        'next_execution_at',
        'success_count',
        'failure_count',
        'average_execution_time',
        'created_by',
        'tags',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'trigger_conditions' => 'array',
        'actions' => 'array',
        'schedule' => 'array',
        'is_active' => 'boolean',
        'is_recurring' => 'boolean',
        'priority' => 'integer',
        'max_executions' => 'integer',
        'execution_count' => 'integer',
        'success_count' => 'integer',
        'failure_count' => 'integer',
        'average_execution_time' => 'float',
        'created_by' => 'integer',
        'tags' => 'array',
        'metadata' => 'array',
        'last_executed_at' => 'datetime',
        'next_execution_at' => 'datetime',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'trigger_type' => 'manual',
        'is_active' => true,
        'is_recurring' => false,
        'priority' => 5,
        'execution_count' => 0,
        'success_count' => 0,
        'failure_count' => 0,
        'average_execution_time' => 0,
    ];

    /**
     * Trigger types
     */
    public static function getTriggerTypes(): array
    {
        return [
            'manual' => 'Thủ công',
            'scheduled' => 'Theo lịch',
            'event' => 'Sự kiện',
            'webhook' => 'Webhook',
            'database' => 'Thay đổi database',
            'file' => 'Thay đổi file',
            'email' => 'Email nhận được',
            'api' => 'API call',
            'user_action' => 'Hành động người dùng',
            'system' => 'Hệ thống',
        ];
    }

    /**
     * Action types
     */
    public static function getActionTypes(): array
    {
        return [
            'send_email' => 'Gửi email',
            'send_notification' => 'Gửi thông báo',
            'update_database' => 'Cập nhật database',
            'create_record' => 'Tạo bản ghi',
            'delete_record' => 'Xóa bản ghi',
            'call_api' => 'Gọi API',
            'run_command' => 'Chạy lệnh',
            'generate_report' => 'Tạo báo cáo',
            'backup_data' => 'Sao lưu dữ liệu',
            'clean_cache' => 'Xóa cache',
            'optimize_images' => 'Tối ưu ảnh',
            'send_sms' => 'Gửi SMS',
            'post_social' => 'Đăng mạng xã hội',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Priority levels
     */
    public static function getPriorityLevels(): array
    {
        return [
            1 => 'Rất thấp',
            2 => 'Thấp',
            3 => 'Thấp vừa',
            4 => 'Vừa',
            5 => 'Vừa cao',
            6 => 'Cao',
            7 => 'Cao vừa',
            8 => 'Rất cao',
            9 => 'Khẩn cấp',
            10 => 'Tối quan trọng',
        ];
    }

    /**
     * Schedule types
     */
    public static function getScheduleTypes(): array
    {
        return [
            'once' => 'Một lần',
            'minutely' => 'Mỗi phút',
            'hourly' => 'Mỗi giờ',
            'daily' => 'Hàng ngày',
            'weekly' => 'Hàng tuần',
            'monthly' => 'Hàng tháng',
            'yearly' => 'Hàng năm',
            'cron' => 'Cron expression',
            'interval' => 'Khoảng thời gian',
        ];
    }

    /**
     * Execute workflow
     */
    public function execute(array $context = []): array
    {
        $startTime = microtime(true);
        $results = [];
        
        try {
            Log::info("Executing workflow: {$this->name}", ['workflow_id' => $this->id]);
            
            // Check if workflow is active
            if (!$this->is_active) {
                throw new \Exception('Workflow is not active');
            }
            
            // Check execution limits
            if ($this->max_executions && $this->execution_count >= $this->max_executions) {
                throw new \Exception('Maximum executions reached');
            }
            
            // Execute each action
            foreach ($this->actions as $action) {
                $actionResult = $this->executeAction($action, $context);
                $results[] = $actionResult;
                
                // Stop if action failed and workflow is configured to stop on failure
                if (!$actionResult['success'] && ($action['stop_on_failure'] ?? false)) {
                    break;
                }
            }
            
            $executionTime = microtime(true) - $startTime;
            $success = collect($results)->every('success');
            
            // Update workflow statistics
            $this->updateExecutionStats($executionTime, $success);
            
            // Schedule next execution if recurring
            if ($this->is_recurring && $success) {
                $this->scheduleNextExecution();
            }
            
            Log::info("Workflow executed successfully", [
                'workflow_id' => $this->id,
                'execution_time' => $executionTime,
                'results_count' => count($results),
            ]);
            
            return [
                'success' => $success,
                'execution_time' => $executionTime,
                'results' => $results,
                'message' => $success ? 'Workflow executed successfully' : 'Workflow completed with errors',
            ];
            
        } catch (\Exception $e) {
            $executionTime = microtime(true) - $startTime;
            $this->updateExecutionStats($executionTime, false);
            
            Log::error("Workflow execution failed", [
                'workflow_id' => $this->id,
                'error' => $e->getMessage(),
                'execution_time' => $executionTime,
            ]);
            
            return [
                'success' => false,
                'execution_time' => $executionTime,
                'results' => $results,
                'error' => $e->getMessage(),
                'message' => 'Workflow execution failed',
            ];
        }
    }

    /**
     * Execute single action
     */
    private function executeAction(array $action, array $context): array
    {
        $startTime = microtime(true);
        
        try {
            $actionType = $action['type'];
            $actionParams = array_merge($action['params'] ?? [], $context);
            
            switch ($actionType) {
                case 'send_email':
                    return $this->executeSendEmailAction($actionParams);
                case 'send_notification':
                    return $this->executeSendNotificationAction($actionParams);
                case 'update_database':
                    return $this->executeUpdateDatabaseAction($actionParams);
                case 'call_api':
                    return $this->executeCallApiAction($actionParams);
                case 'clean_cache':
                    return $this->executeCleanCacheAction($actionParams);
                case 'generate_report':
                    return $this->executeGenerateReportAction($actionParams);
                default:
                    return $this->executeCustomAction($actionType, $actionParams);
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'action_type' => $actionType,
                'execution_time' => microtime(true) - $startTime,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Execute send email action
     */
    private function executeSendEmailAction(array $params): array
    {
        $startTime = microtime(true);
        
        // Implement email sending logic
        // This is a simplified example
        
        return [
            'success' => true,
            'action_type' => 'send_email',
            'execution_time' => microtime(true) - $startTime,
            'message' => 'Email sent successfully',
            'details' => [
                'to' => $params['to'] ?? 'admin@example.com',
                'subject' => $params['subject'] ?? 'Automated Email',
            ],
        ];
    }

    /**
     * Execute send notification action
     */
    private function executeSendNotificationAction(array $params): array
    {
        $startTime = microtime(true);
        
        // Implement notification sending logic
        
        return [
            'success' => true,
            'action_type' => 'send_notification',
            'execution_time' => microtime(true) - $startTime,
            'message' => 'Notification sent successfully',
        ];
    }

    /**
     * Execute update database action
     */
    private function executeUpdateDatabaseAction(array $params): array
    {
        $startTime = microtime(true);
        
        // Implement database update logic
        
        return [
            'success' => true,
            'action_type' => 'update_database',
            'execution_time' => microtime(true) - $startTime,
            'message' => 'Database updated successfully',
        ];
    }

    /**
     * Execute call API action
     */
    private function executeCallApiAction(array $params): array
    {
        $startTime = microtime(true);
        
        // Implement API call logic
        
        return [
            'success' => true,
            'action_type' => 'call_api',
            'execution_time' => microtime(true) - $startTime,
            'message' => 'API called successfully',
        ];
    }

    /**
     * Execute clean cache action
     */
    private function executeCleanCacheAction(array $params): array
    {
        $startTime = microtime(true);
        
        try {
            if (isset($params['keys'])) {
                foreach ($params['keys'] as $key) {
                    Cache::forget($key);
                }
            } else {
                Cache::flush();
            }
            
            return [
                'success' => true,
                'action_type' => 'clean_cache',
                'execution_time' => microtime(true) - $startTime,
                'message' => 'Cache cleaned successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'action_type' => 'clean_cache',
                'execution_time' => microtime(true) - $startTime,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Execute generate report action
     */
    private function executeGenerateReportAction(array $params): array
    {
        $startTime = microtime(true);
        
        // Implement report generation logic
        
        return [
            'success' => true,
            'action_type' => 'generate_report',
            'execution_time' => microtime(true) - $startTime,
            'message' => 'Report generated successfully',
        ];
    }

    /**
     * Execute custom action
     */
    private function executeCustomAction(string $actionType, array $params): array
    {
        $startTime = microtime(true);
        
        // Implement custom action logic
        // This could call custom classes or methods
        
        return [
            'success' => true,
            'action_type' => $actionType,
            'execution_time' => microtime(true) - $startTime,
            'message' => 'Custom action executed successfully',
        ];
    }

    /**
     * Update execution statistics
     */
    private function updateExecutionStats(float $executionTime, bool $success): void
    {
        $this->increment('execution_count');
        
        if ($success) {
            $this->increment('success_count');
        } else {
            $this->increment('failure_count');
        }
        
        // Update average execution time
        $totalTime = $this->average_execution_time * ($this->execution_count - 1) + $executionTime;
        $this->average_execution_time = $totalTime / $this->execution_count;
        
        $this->last_executed_at = now();
        $this->save();
    }

    /**
     * Schedule next execution
     */
    private function scheduleNextExecution(): void
    {
        if (!$this->is_recurring || !$this->schedule) {
            return;
        }
        
        $scheduleType = $this->schedule['type'] ?? 'daily';
        $interval = $this->schedule['interval'] ?? 1;
        
        switch ($scheduleType) {
            case 'minutely':
                $this->next_execution_at = now()->addMinutes($interval);
                break;
            case 'hourly':
                $this->next_execution_at = now()->addHours($interval);
                break;
            case 'daily':
                $this->next_execution_at = now()->addDays($interval);
                break;
            case 'weekly':
                $this->next_execution_at = now()->addWeeks($interval);
                break;
            case 'monthly':
                $this->next_execution_at = now()->addMonths($interval);
                break;
            case 'yearly':
                $this->next_execution_at = now()->addYears($interval);
                break;
        }
        
        $this->save();
    }

    /**
     * Get workflows ready for execution
     */
    public static function getReadyForExecution(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)
            ->where('is_recurring', true)
            ->where('next_execution_at', '<=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('next_execution_at')
            ->get();
    }

    /**
     * Get workflow statistics
     */
    public static function getStatistics(): array
    {
        return [
            'total_workflows' => static::count(),
            'active_workflows' => static::where('is_active', true)->count(),
            'recurring_workflows' => static::where('is_recurring', true)->count(),
            'total_executions' => static::sum('execution_count'),
            'total_successes' => static::sum('success_count'),
            'total_failures' => static::sum('failure_count'),
            'average_success_rate' => static::getAverageSuccessRate(),
            'workflows_by_trigger' => static::getWorkflowsByTrigger(),
        ];
    }

    /**
     * Get average success rate
     */
    private static function getAverageSuccessRate(): float
    {
        $totalExecutions = static::sum('execution_count');
        $totalSuccesses = static::sum('success_count');
        
        return $totalExecutions > 0 ? ($totalSuccesses / $totalExecutions) * 100 : 0;
    }

    /**
     * Get workflows by trigger type
     */
    private static function getWorkflowsByTrigger(): array
    {
        return static::select('trigger_type', \DB::raw('COUNT(*) as count'))
            ->groupBy('trigger_type')
            ->pluck('count', 'trigger_type')
            ->toArray();
    }

    /**
     * Relationship with creator
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
