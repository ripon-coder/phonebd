<?php

namespace App\Services;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogService
{
    public function getAllPosts($perPage = 10)
    {
        return BlogPost::where('is_published', true)->paginate($perPage);
    }

    public function getPostsByCategory(BlogCategory $category, $perPage = 10)
    {
        return $category->posts()->where('is_published', true)->paginate($perPage);
    }

    public function getAllCategories()
    {
        return BlogCategory::where('is_active', true)
            ->withCount(['posts' => function ($query) {
                $query->where('is_published', true);
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();
    }
}
