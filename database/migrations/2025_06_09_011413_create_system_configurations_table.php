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
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();

            // Theme Configuration
            $table->enum('theme_mode', ['light_only', 'dark_only', 'both', 'none'])->default('light_only');
            $table->string('primary_color', 7)->default('#dc2626'); // red-600
            $table->string('secondary_color', 7)->default('#ffffff'); // white
            $table->string('accent_color', 7)->default('#f3f4f6'); // gray-100

            // Typography
            $table->enum('primary_font', ['Inter', 'Roboto', 'Open Sans', 'Poppins', 'Nunito'])->default('Inter');
            $table->enum('secondary_font', ['Inter', 'Roboto', 'Open Sans', 'Poppins', 'Nunito'])->default('Inter');
            $table->enum('tertiary_font', ['Inter', 'Roboto', 'Open Sans', 'Poppins', 'Nunito'])->default('Inter');

            // Design Style
            $table->enum('design_style', ['minimalism', 'glassmorphism', 'modern', 'classic'])->default('minimalism');

            // Error Pages
            $table->json('error_pages')->nullable(); // ['404', '500', 'maintenance', 'offline']

            // Icon System
            $table->enum('icon_system', ['heroicons', 'fontawesome'])->default('fontawesome');

            // Admin Panel Colors
            $table->string('admin_primary_color', 7)->default('#dc2626');
            $table->string('admin_secondary_color', 7)->default('#374151');

            // Analytics
            $table->boolean('visitor_analytics_enabled')->default(false);

            // Favicon
            $table->string('favicon_path')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
