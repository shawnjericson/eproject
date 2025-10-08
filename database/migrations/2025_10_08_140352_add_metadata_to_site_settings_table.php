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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('value');
            $table->string('category')->nullable()->after('description');
            $table->string('type')->default('text')->after('category');
            $table->string('min')->nullable()->after('type');
            $table->string('max')->nullable()->after('min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['description', 'category', 'type', 'min', 'max']);
        });
    }
};