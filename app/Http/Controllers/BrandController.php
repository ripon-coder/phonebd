<?php

namespace App\Http\Controllers;

use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {
        $brands = $this->brandService->getAllBrands();
        return view('brand.index', compact('brands'));
    }

    public function show($slug)
    {
        $brand = $this->brandService->getBrandBySlug($slug);
        
        // We can reuse the ProductService to get products, but we need to inject it or use the facade/helper if available.
        // Since ProductService is not injected here, let's inject it.
        $productService = app(\App\Services\ProductService::class);

        // Prepare filters
        $filters = request()->only(['min_price', 'max_price', 'sort', 'status']);
        $filters['brands'] = [$brand->id]; // Force this brand

        $products = $productService->getAllPaginated($filters, 20);

        return view('brand.show', compact('brand', 'products'));
    }
}
