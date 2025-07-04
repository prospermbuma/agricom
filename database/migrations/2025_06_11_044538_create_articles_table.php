<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('category', ['pest_control', 'disease_management', 'farming_techniques', 'weather', 'market_prices', 'general']);
            $table->json('target_crops')->nullable(); // Array of crop IDs
            $table->string('featured_image')->nullable();
            $table->json('attachments')->nullable(); // Array of file paths
            $table->boolean('is_published')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
