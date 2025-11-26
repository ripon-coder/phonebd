<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->take(11)->get();
        $brands = Brand::orderBy('sort_order')->take(10)->get();
        $latestPhones = Product::where('is_published', true)->latest()->take(10)->get();
        $upcomingPhones = Product::where('status', 'upcoming')->where('is_published', true)->latest()->take(10)->get();
        $officialPhones = Product::where('status', 'official')->where('is_published', true)->latest()->take(10)->get();
        $unofficialPhones = Product::where('status', 'unofficial')->where('is_published', true)->latest()->take(10)->get();
        
        return view('home.index', compact('categories', 'brands', 'latestPhones', 'upcomingPhones', 'officialPhones', 'unofficialPhones'));
    }
}
