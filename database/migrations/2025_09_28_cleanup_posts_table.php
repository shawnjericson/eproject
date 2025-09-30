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
            // Remove SEO fields that are not needed
            $table->dropColumn([
                'meta_title',
                'meta_title_vi', 
                'meta_description',
                'meta_description_vi',
                'slug',
                'slug_vi'
            ]);
            
            // Add description field for short summary (like monument)
            $table->text('description')->nullable()->after('title_vi');
            $table->text('description_vi')->nullable()->after('description');
            
            // Remove rejected status, keep it simple like monument
            $table->enum('status', ['draft', 'pending', 'approved'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add back SEO fields
            $table->string('meta_title')->nullable();
            $table->string('meta_title_vi')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_vi')->nullable();
            $table->string('slug')->nullable();
            $table->string('slug_vi')->nullable();
            
            // Remove description fields
            $table->dropColumn(['description', 'description_vi']);
            
            // Add back rejected status
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->change();
        });
    }
};
