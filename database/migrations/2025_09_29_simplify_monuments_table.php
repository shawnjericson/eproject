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
        Schema::table('monuments', function (Blueprint $table) {
            // Remove language field - we'll use translation table
            if (Schema::hasColumn('monuments', 'language')) {
                $table->dropColumn('language');
            }
            
            // Keep only essential fields in main table
            // title, description, history, content, location will be fallbacks
            // Real content will be in monument_translations table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            $table->enum('language', ['en', 'vi'])->default('en');
        });
    }
};
