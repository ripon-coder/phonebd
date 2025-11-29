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
            $table->string('storage_type')->nullable();
            $table->string('finger_print')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('no_spam_rating')->default(0)->comment('0-2 = high spam, 3-5 = low spam, 6-10 = no spam');
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
