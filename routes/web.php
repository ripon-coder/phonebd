<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::orderBy('sort_order')->get();
    $brands = Brand::orderBy('sort_order')->get();
    $latestPhones = Product::where('is_published', true)->latest()->take(8)->get();
    return view('home.index', compact('categories', 'brands', 'latestPhones'));
})->name('home');

Route::get('/blog', function () {
    return 'Blog Placeholder';
})->name('blog.index');
