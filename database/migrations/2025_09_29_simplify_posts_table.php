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
        Schema::table('posts', function (Blueprint $table) {
            // Remove multilingual fields - they'll be in post_translations table
            $table->dropColumn([
                'title_vi',
                'description',
                'description_vi',
                'content_vi'
            ]);
            
            // Keep only basic post info
            // title, content will be the default/fallback content
            // Main language content will be in translations table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add back multilingual fields
            $table->string('title_vi')->nullable();
            $table->text('description')->nullable();
            $table->text('description_vi')->nullable();
            $table->text('content_vi')->nullable();
        });
    }
};
