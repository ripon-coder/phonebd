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
        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        return view('blog.show', compact('post'));
    }

    public function category(BlogCategory $category)
    {
        $posts = $this->blogService->getPostsByCategory($category);
        return view('blog.category', compact('category', 'posts'));
    }
}
