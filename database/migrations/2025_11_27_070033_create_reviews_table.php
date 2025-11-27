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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('review');
            $table->integer('rating_design')->nullable();
            $table->integer('rating_performance')->nullable();
            $table->integer('rating_camera')->nullable();
            $table->integer('rating_battery')->nullable();
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->string('variant')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_approve')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
