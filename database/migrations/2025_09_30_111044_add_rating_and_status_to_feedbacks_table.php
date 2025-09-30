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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->tinyInteger('rating')->nullable()->after('message')->comment('Rating 1-5 stars for monument reviews');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['rating', 'status']);
        });
    }
};
