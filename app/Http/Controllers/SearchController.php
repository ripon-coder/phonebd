<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('home');
        }

        $products = \App\Models\Product::search($query)
            ->query(fn ($q) => $q->select(['id', 'title', 'slug', 'image', 'base_price', 'category_id', 'storage_type'])
                                 ->selectSub(function ($sq) {
                                     $sq->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                                       ->from('reviews')
                                       ->whereColumn('reviews.product_id', 'products.id')
                                       ->where('is_approve', true);
                                  }, 'avg_rating')
                                 ->with('category:id,slug,name'))
            ->paginate(20);

        $brands = \App\Models\Brand::search($query)
            ->query(fn ($q) => $q->select(['id', 'name', 'slug', 'image', 'storage_type']))
            ->get();

        return view('search.index', compact('products', 'brands', 'query'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Search products with standard query
        $products = \App\Models\Product::search($query)
            ->query(fn ($query) => $query->select(['id', 'title', 'slug', 'image', 'base_price', 'category_id', 'storage_type'])->with('category:id,slug'))
            ->take(5)
            ->get();

        // If no products found, try searching without spaces
        if ($products->isEmpty()) {
            $queryNoSpace = str_replace(' ', '', $query);
            $products = \App\Models\Product::search($queryNoSpace)
                ->query(fn ($query) => $query->select(['id', 'title', 'slug', 'image', 'base_price', 'category_id', 'storage_type'])->with('category:id,slug'))
                ->take(5)
                ->get();
        }

        // Search brands
        $brands = \App\Models\Brand::search($query)
            ->query(fn ($query) => $query->select(['id', 'name', 'slug', 'image', 'storage_type']))
            ->take(3)
            ->get();

        // If no brands found, try searching without spaces
        if ($brands->isEmpty()) {
            $queryNoSpace = str_replace(' ', '', $query);
            $brands = \App\Models\Brand::search($queryNoSpace)
                ->query(fn ($query) => $query->select(['id', 'name', 'slug', 'image', 'storage_type']))
                ->take(3)
                ->get();
        }

        $results = [];

        foreach ($brands as $brand) {
            $results[] = [
                'type' => 'brand',
                'title' => $brand->name,
                'url' => route('brands.show', $brand->slug),
                'image' => $brand->image ? $brand->getImageUrl('image') : null,
            ];
        }

        foreach ($products as $product) {
            $results[] = [
                'type' => 'product',
                'title' => $product->title,
                'url' => route('product.show', ['category_slug' => $product->category->slug, 'product' => $product->slug]),
                'image' => $product->image ? $product->getImageUrl('image') : null,
                'price' => $product->base_price ? '৳' . number_format($product->base_price) : 'Expected',
            ];
        }

        return response()->json($results);
    }
}
