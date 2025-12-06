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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->enum('status', ['official', 'unofficial', 'upcoming', 'discontinued'])->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('raw_html')->nullable();
            $table->boolean('is_raw_html')->default(false);
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_published')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('storage_type')->default('backblaze')->nullable();
            $table->boolean('is_sample')->default(true);
            $table->tinyInteger('sample_count_max')->default(20);
            $table->boolean('is_review')->default(true);
            $table->tinyInteger('review_count_max')->default(20);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['title', 'status', 'is_featured', 'is_published', 'base_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
