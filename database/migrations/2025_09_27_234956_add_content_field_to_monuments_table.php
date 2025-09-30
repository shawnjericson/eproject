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
            // Add content field for rich text content
            $table->longText('content')->nullable()->after('history');

            // Make description and history nullable since we're using content now
            $table->longText('description')->nullable()->change();
            $table->longText('history')->nullable()->change();

            // Add Central to zone enum
            $table->enum('zone', ['East', 'North', 'West', 'South', 'Central'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monuments', function (Blueprint $table) {
            // Remove content field
            $table->dropColumn('content');

            // Make description and history required again
            $table->longText('description')->nullable(false)->change();
            $table->longText('history')->nullable(false)->change();

            // Revert zone enum
            $table->enum('zone', ['East', 'North', 'West', 'South'])->change();
        });
    }
};
