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
            ->select('*')
            ->selectSub(function ($q) {
                $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                  ->from('reviews')
                  ->whereColumn('reviews.product_id', 'products.id')
                  ->where('is_approve', true);
             }, 'avg_rating')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getUpcoming($limit = 10)
    {
        return Product::with('category')
            ->select('*')
            ->selectSub(function ($q) {
                $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                  ->from('reviews')
                  ->whereColumn('reviews.product_id', 'products.id')
                  ->where('is_approve', true);
             }, 'avg_rating')
            ->where('status', 'upcoming')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getOfficial($limit = 10)
    {
        return Product::with('category')
            ->select('*')
            ->selectSub(function ($q) {
                $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                  ->from('reviews')
                  ->whereColumn('reviews.product_id', 'products.id')
                  ->where('is_approve', true);
             }, 'avg_rating')
            ->where('status', 'official')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getUnofficial($limit = 10)
    {
        return Product::with('category')
            ->select('*')
            ->selectSub(function ($q) {
                $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                  ->from('reviews')
                  ->whereColumn('reviews.product_id', 'products.id')
                  ->where('is_approve', true);
             }, 'avg_rating')
            ->where('status', 'unofficial')
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getAllPaginated($filters = [], $perPage = 20)
    {
        $query = Product::with('category:id,name,slug')
            ->select('id', 'title', 'slug', 'base_price', 'image', 'category_id', 'created_at')
            ->selectSub(function ($q) {
                $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                  ->from('reviews')
                  ->whereColumn('reviews.product_id', 'products.id')
                  ->where('is_approve', true);
             }, 'avg_rating')
            ->where('is_published', true);

        // Filter by Brands
        if (!empty($filters['brands'])) {
            $query->whereIn('brand_id', $filters['brands']);
        }

        // Filter by Categories
        if (!empty($filters['categories'])) {
            $query->whereIn('category_id', $filters['categories']);
        }

        // Filter by Status
        if (!empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        }

        // Filter by Price Range
        if (!empty($filters['min_price'])) {
            $query->where('base_price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('base_price', '<=', $filters['max_price']);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'popular':
                $query->withCount('reviews')
                      ->orderBy('reviews_count', 'desc');
                break;
            case 'rating':
                 $query->orderBy('avg_rating', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
