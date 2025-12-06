<?php

namespace App\Http\Controllers;

use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $brandService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        BrandService $brandService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
    }

    public function index()
    {
        $categories = $this->categoryService->getTopCategories();
        $brands = $this->brandService->getTopBrands();
        $latestPhones = $this->productService->getLatest();
        $upcomingPhones = $this->productService->getUpcoming();
        $officialPhones = $this->productService->getOfficial();
        $unofficialPhones = $this->productService->getUnofficial();
        $dynamicPages = \App\Models\DynamicPage::query()
            ->select('id', 'title', 'slug', 'sort_order')
            ->whereNull('brand_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home.index', compact('categories', 'brands', 'latestPhones', 'upcomingPhones', 'officialPhones', 'unofficialPhones', 'dynamicPages'));
    }
}
