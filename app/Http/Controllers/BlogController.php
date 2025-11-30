<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {
        $posts = $this->blogService->getAllPosts();
        $categories = $this->blogService->getAllCategories();
        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(BlogPost $post)
    {
        $categories = $this->blogService->getAllCategories();
        return view('blog.show', compact('post', 'categories'));
    }

    public function category(BlogCategory $category)
    {
        $posts = $this->blogService->getPostsByCategory($category);
        $categories = $this->blogService->getAllCategories();
        return view('blog.category', compact('category', 'posts', 'categories'));
    }
}
