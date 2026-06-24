<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('prompt');
            $table->string('negative_prompt')->nullable();

            // Status: pending | processing | succeeded | failed
            $table->string('status')->default('pending');

            // Diisi oleh Replicate setelah job diproses
            $table->string('prediction_id')->nullable()->unique();
            $table->text('image_url')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_generations');
    }
};
