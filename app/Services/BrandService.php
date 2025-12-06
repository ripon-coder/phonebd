<?php

namespace App\Services;

use App\Models\Brand;

class BrandService
{
    public function getTopBrands($limit = 10)
    {
        return Brand::select('id', 'name', 'slug', 'image')->orderBy('sort_order')->take($limit)->get();
    }

    public function getAllBrands()
    {
        return Brand::orderBy('name')->get(['id', 'name', 'slug', 'image']);
    }

    public function getBrandBySlug($slug)
    {
        return Brand::where('slug', $slug)->firstOrFail();
    }

    public function getBrandsForFilter()
    {
        return Brand::orderBy('sort_order')->orderBy('name')->get(['id', 'name']);
    }
}
