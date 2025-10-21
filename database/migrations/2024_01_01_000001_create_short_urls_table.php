<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('short_code', 10)->unique();
            $table->text('original_url');
            $table->string('title')->nullable();
            $table->integer('clicks')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('short_code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};