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
        Schema::create('setup_modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name')->unique();
            $table->string('module_title');
            $table->text('module_description')->nullable();
            $table->boolean('is_installed')->default(false);
            $table->boolean('is_required')->default(false);
            $table->json('configuration')->nullable();
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_modules');
    }
};
