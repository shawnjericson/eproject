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
            // Remove unnecessary multilingual fields since we're using single language for now
            $table->dropColumn([
                'title_vi',
                'description_vi',
                'history_vi',
                'location_vi',
                'meta_title',
                'meta_title_vi',
                'meta_description',
                'meta_description_vi',
                'slug',
                'slug_vi'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            // Add back the dropped columns
            $table->string('title_vi')->nullable()->after('title');
            $table->text('description_vi')->nullable()->after('description');
            $table->text('history_vi')->nullable()->after('history');
            $table->string('location_vi')->nullable()->after('location');
            $table->string('meta_title')->nullable()->after('location_vi');
            $table->string('meta_title_vi')->nullable()->after('meta_title');
            $table->text('meta_description')->nullable()->after('meta_title_vi');
            $table->text('meta_description_vi')->nullable()->after('meta_description');
            $table->string('slug')->nullable()->after('meta_description_vi');
            $table->string('slug_vi')->nullable()->after('slug');
        });
    }
};
