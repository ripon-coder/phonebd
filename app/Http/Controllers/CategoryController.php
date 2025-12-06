<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('category.index', compact('categories'));
    }

    public function show(\App\Models\Category $category)
    {
        $productService = app(\App\Services\ProductService::class);

        // Prepare filters
        $filters = request()->only(['min_price', 'max_price', 'sort', 'status']);
        $filters['categories'] = [$category->id]; // Force this category

        $products = $productService->getAllPaginated($filters, 24);

        // Defensive: Pass empty arrays in case the view expects them (e.g. sidebar filters)
        $brands = []; 
        $categories = [];

        return view('category.show', compact('category', 'products', 'brands', 'categories'));
    }
}
