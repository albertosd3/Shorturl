<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rotator_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotator_group_id')->constrained('rotator_groups')->onDelete('cascade');
            $table->text('url');
            $table->integer('weight')->default(1);
            $table->integer('clicks')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['rotator_group_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rotator_urls');
    }
};