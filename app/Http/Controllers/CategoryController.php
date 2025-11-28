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

    public function show(Category $category)
    {
        $products = $this->categoryService->getCategoryProducts($category);
        return view('category.show', compact('category', 'products'));
    }
}
