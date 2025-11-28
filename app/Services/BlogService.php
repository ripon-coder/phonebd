<?php

namespace App\Services;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogService
{
    public function getAllPosts($perPage = 10)
    {
        return BlogPost::where('published', true)->paginate($perPage);
    }

    public function getPostsByCategory(BlogCategory $category, $perPage = 10)
    {
        return $category->posts()->where('published', true)->paginate($perPage);
    }
}
