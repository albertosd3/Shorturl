<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->string('short_code', 10);
            $table->string('click_type')->default('short_url'); // 'short_url' or 'rotator'
            $table->foreignId('short_url_id')->nullable()->constrained('short_urls')->onDelete('cascade');
            $table->foreignId('rotator_group_id')->nullable()->constrained('rotator_groups')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('country', 5)->nullable();
            $table->string('city')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->text('referer')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->json('stopbot_data')->nullable();
            $table->timestamps();
            
            $table->index(['short_code', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['country', 'created_at']);
            $table->index(['is_bot', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};