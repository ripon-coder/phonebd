<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('published', true)->paginate(10);
        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        return view('blog.show', compact('post'));
    }

    public function category(BlogCategory $category)
    {
        $posts = $category->posts()->where('published', true)->paginate(10);
        return view('blog.category', compact('category', 'posts'));
    }
}
