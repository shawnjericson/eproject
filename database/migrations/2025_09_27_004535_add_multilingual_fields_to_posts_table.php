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
            // Add language field to specify primary language
            $table->enum('language', ['en', 'vi'])->default('en')->after('status');

            // Add Vietnamese versions of existing fields
            $table->string('title_vi')->nullable()->after('language');
            $table->text('content_vi')->nullable()->after('title_vi');

            // Add meta fields for better SEO
            $table->string('meta_title')->nullable()->after('content_vi');
            $table->string('meta_title_vi')->nullable()->after('meta_title');
            $table->text('meta_description')->nullable()->after('meta_title_vi');
            $table->text('meta_description_vi')->nullable()->after('meta_description');
            $table->string('slug')->nullable()->after('meta_description_vi');
            $table->string('slug_vi')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'language',
                'title_vi',
                'content_vi',
                'meta_title',
                'meta_title_vi',
                'meta_description',
                'meta_description_vi',
                'slug',
                'slug_vi'
            ]);
        });
    }
};
