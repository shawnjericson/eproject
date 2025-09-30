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
        // Create monument_translations table for multilingual content
        Schema::create('monument_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monument_id')->constrained('monuments')->onDelete('cascade');
            $table->enum('language', ['en', 'vi']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('history')->nullable();
            $table->longText('content')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            
            // Ensure one translation per language per monument
            $table->unique(['monument_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monument_translations');
    }
};
