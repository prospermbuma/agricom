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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->text('description')->dullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->enum('properties', ['jembe', 'shoka', 'panga'])->default('jembe');
            $table->unique('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
