<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rotator_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_code', 10)->unique();
            $table->text('description')->nullable();
            $table->enum('rotation_type', ['sequential', 'random', 'weighted'])->default('random');
            $table->boolean('is_active')->default(true);
            $table->integer('clicks')->default(0);
            $table->timestamps();
            
            $table->index('short_code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rotator_groups');
    }
};