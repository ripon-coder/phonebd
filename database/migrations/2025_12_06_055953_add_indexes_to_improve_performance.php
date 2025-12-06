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
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_published', 'is_featured', 'created_at'], 'idx_products_home_latest');
            $table->index(['status', 'is_published', 'is_featured', 'created_at'], 'idx_products_status_listing');
            $table->index(['category_id', 'is_published', 'base_price'], 'idx_products_similar_price');
            $table->index(['brand_id', 'is_published'], 'idx_products_brand_published');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approve'], 'idx_reviews_product_approve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_home_latest');
            $table->dropIndex('idx_products_status_listing');
            $table->dropIndex('idx_products_similar_price');
            $table->dropIndex('idx_products_brand_published');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_product_approve');
        });
    }
};
