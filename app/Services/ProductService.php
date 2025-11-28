<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getDetails($slug, Product $product)
    {
        $product->load([
            'brand:id,name,slug', 
            'category:id,name,slug', 
            'variantPrices', 
            'specValues.productSpecItem', 
            'specValues.productSpecGroup', 
            'faqs'
        ]);

        if ($product->category->slug !== $slug) {
            return null;
        }

        return $product;
    }

    public function getSimilarByPrice(Product $product)
    {
        $priceRange = $product->base_price * 0.20; // 20% range
        $minPrice = $product->base_price - $priceRange;
        $maxPrice = $product->base_price + $priceRange;

        return Product::select('id', 'title', 'slug', 'image', 'base_price', 'category_id')
            ->with('category:id,name,slug')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereBetween('base_price', [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->take(5)
            ->get();
    }

    public function getSimilarByCategory(Product $product, $excludeIds = [])
    {
        return Product::select('id', 'title', 'slug', 'image', 'base_price', 'category_id')
            ->with('category:id,name,slug')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereNotIn('id', $excludeIds)
            ->inRandomOrder()
            ->take(5)
            ->get();
    }

    public function getLatest($limit = 10)
    {
        return Product::with('category')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getUpcoming($limit = 10)
    {
        return Product::with('category')
            ->where('status', 'upcoming')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getOfficial($limit = 10)
    {
        return Product::with('category')
            ->where('status', 'official')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getUnofficial($limit = 10)
    {
        return Product::with('category')
            ->where('status', 'unofficial')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }
}
