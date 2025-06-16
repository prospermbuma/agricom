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
        Schema::create('farmer_crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->decimal('area_planted_acres', 8, 2)->nullable();
            $table->date('planting_date')->nullable();
            $table->date('expected_harvest_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_crops');
    }
};
