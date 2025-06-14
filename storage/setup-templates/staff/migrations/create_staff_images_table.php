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
        Schema::create('staff_images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable'); // imageable_id, imageable_type
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->enum('type', ['avatar', 'gallery', 'cover'])->default('gallery');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['imageable_id', 'imageable_type', 'type']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_images');
    }
};
