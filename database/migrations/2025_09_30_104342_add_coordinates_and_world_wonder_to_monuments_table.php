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
            $table->decimal('latitude', 10, 8)->nullable()->after('zone');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('is_world_wonder')->default(false)->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'is_world_wonder']);
        });
    }
};
