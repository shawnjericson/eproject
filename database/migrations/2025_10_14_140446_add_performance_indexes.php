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
        // Add indexes for better performance
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['created_by', 'created_at']);
            $table->index(['status', 'created_by']);
        });

        Schema::table('monuments', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['created_by', 'created_at']);
            $table->index(['status', 'created_by']);
            $table->index(['zone', 'status']);
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->index(['created_at']);
            $table->index(['monument_id', 'created_at']);
        });

        Schema::table('post_translations', function (Blueprint $table) {
            $table->index(['post_id', 'language']);
        });

        Schema::table('monument_translations', function (Blueprint $table) {
            $table->index(['monument_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['created_by', 'created_at']);
            $table->dropIndex(['status', 'created_by']);
        });

        Schema::table('monuments', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['created_by', 'created_at']);
            $table->dropIndex(['status', 'created_by']);
            $table->dropIndex(['zone', 'status']);
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['monument_id', 'created_at']);
        });

        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'language']);
        });

        Schema::table('monument_translations', function (Blueprint $table) {
            $table->dropIndex(['monument_id', 'language']);
        });
    }
};