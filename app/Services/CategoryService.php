<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getTopCategories($limit = 11)
    {
        return Category::orderBy('sort_order')->take($limit)->get();
    }

    public function getCategoryProducts(Category $category, $perPage = 12)
    {
        return $category->products()->where('is_published', true)->paginate($perPage);
    }
}
