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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index(); // Hỗ trợ IPv6
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->index();
            $table->unsignedBigInteger('content_id')->nullable(); // Generic content ID
            $table->string('session_id', 100)->index();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            // Index để tối ưu query
            $table->index(['ip_address', 'visited_at']);
            $table->index(['content_id', 'visited_at']);
            $table->index(['session_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
