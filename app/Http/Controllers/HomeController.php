<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_published', true)->latest()->take(12)->get();
        return view('home.index', compact('products'));
    }
}
