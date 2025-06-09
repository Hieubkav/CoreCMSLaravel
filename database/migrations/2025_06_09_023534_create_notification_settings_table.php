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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Notification type and category
            $table->enum('type', [
                'order_status', 'payment_status', 'inventory_alert', 'user_activity',
                'system_alert', 'security_alert', 'marketing', 'newsletter',
                'review_submitted', 'contact_form', 'support_ticket', 'custom'
            ])->default('custom');
            $table->string('category')->default('general');

            // Notification channels
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('push_enabled')->default(false);
            $table->boolean('database_enabled')->default(true); // In-app notifications
            $table->boolean('slack_enabled')->default(false);
            $table->boolean('discord_enabled')->default(false);

            // Email settings
            $table->unsignedBigInteger('email_template_id')->nullable();
            $table->json('email_recipients')->nullable(); // Additional email recipients
            $table->boolean('email_to_admin')->default(false);
            $table->boolean('email_to_user')->default(true);

            // SMS settings
            $table->string('sms_template')->nullable();
            $table->json('sms_recipients')->nullable(); // Phone numbers

            // Push notification settings
            $table->string('push_title')->nullable();
            $table->string('push_message')->nullable();
            $table->string('push_icon')->nullable();
            $table->string('push_action_url')->nullable();

            // Slack/Discord settings
            $table->string('slack_webhook_url')->nullable();
            $table->string('slack_channel')->nullable();
            $table->string('discord_webhook_url')->nullable();

            // Trigger conditions
            $table->json('trigger_conditions')->nullable(); // When to send
            $table->json('trigger_data')->nullable(); // Data requirements
            $table->boolean('require_user_opt_in')->default(false);

            // Frequency and throttling
            $table->enum('frequency', ['immediate', 'hourly', 'daily', 'weekly'])->default('immediate');
            $table->integer('throttle_minutes')->default(0); // Min time between same notifications
            $table->integer('max_per_day')->nullable(); // Max notifications per day

            // User preferences
            $table->boolean('user_can_disable')->default(true);
            $table->boolean('user_can_customize')->default(false);
            $table->json('default_user_preferences')->nullable();

            // Priority and grouping
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('group_key')->nullable(); // For grouping similar notifications
            $table->boolean('is_persistent')->default(false); // Don't auto-dismiss

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('set null');

            // Indexes
            $table->index(['type', 'status']);
            $table->index(['category', 'status']);
            $table->index('slug');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
