<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($category_slug, Product $product)
    {
        if ($product->category->slug !== $category_slug) {
            abort(404);
        }
        $priceRange = $product->base_price * 0.20; // 20% range
        $minPrice = $product->base_price - $priceRange;
        $maxPrice = $product->base_price + $priceRange;

        $similarPriceProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereBetween('base_price', [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->take(5)
            ->get();

        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereNotIn('id', $similarPriceProducts->pluck('id'))
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('product.show', compact('product', 'similarPriceProducts', 'similarProducts'));
    }
}
