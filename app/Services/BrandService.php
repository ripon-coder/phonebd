<?php

namespace App\Services;

use App\Models\Brand;

class BrandService
{
    public function getTopBrands($limit = 10)
    {
        return Brand::orderBy('sort_order')->take($limit)->get();
    }
}
