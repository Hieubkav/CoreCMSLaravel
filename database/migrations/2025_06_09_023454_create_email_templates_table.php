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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Template content
            $table->string('subject');
            $table->longText('html_content');
            $table->longText('text_content')->nullable(); // Plain text version

            // Template type and category
            $table->enum('type', [
                'order_confirmation', 'order_shipped', 'order_delivered', 'order_cancelled',
                'payment_received', 'payment_failed', 'refund_processed',
                'user_welcome', 'user_verification', 'password_reset',
                'newsletter', 'promotional', 'notification',
                'contact_form', 'support_ticket', 'custom'
            ])->default('custom');
            $table->string('category')->default('general'); // For grouping templates

            // Email settings
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->json('cc_emails')->nullable(); // CC recipients
            $table->json('bcc_emails')->nullable(); // BCC recipients

            // Template variables and placeholders
            $table->json('available_variables')->nullable(); // List of available placeholders
            $table->json('required_variables')->nullable(); // Required placeholders

            // Attachments and styling
            $table->json('attachments')->nullable(); // Default attachments
            $table->string('layout_template')->nullable(); // Base layout to use
            $table->json('styling_options')->nullable(); // Colors, fonts, etc.

            // Automation and triggers
            $table->boolean('is_automated')->default(false); // Auto-send or manual
            $table->json('trigger_conditions')->nullable(); // When to send automatically
            $table->integer('send_delay_minutes')->default(0); // Delay before sending

            // Localization
            $table->string('language', 5)->default('vi'); // Language code
            $table->unsignedBigInteger('parent_template_id')->nullable(); // For translations

            // Status and versioning
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->string('version', 10)->default('1.0');
            $table->boolean('is_default')->default(false); // Default template for type
            $table->integer('order')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('parent_template_id')->references('id')->on('email_templates')->onDelete('set null');

            // Indexes
            $table->index(['type', 'status']);
            $table->index(['category', 'status']);
            $table->index(['language', 'type']);
            $table->index('slug');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
