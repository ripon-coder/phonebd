<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getTopCategories($limit = 11)
    {
        return Category::select('id', 'name', 'slug')->orderBy('sort_order')->take($limit)->get();
    }

    public function getAllCategories()
    {
        return Category::orderBy('sort_order')->get(['id', 'name', 'slug']);
    }

    public function getCategoryProducts(Category $category, $perPage = 12)
    {
        return $category->products()->where('is_published', true)->paginate($perPage);
    }

    public function getCategoriesForFilter()
    {
        return Category::orderBy('sort_order')->get(['id', 'name']);
    }
}
