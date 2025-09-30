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
        Schema::create('monuments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->longText('history');
            $table->enum('zone', ['East', 'North', 'West', 'South']);
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'pending', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monuments');
    }
};
