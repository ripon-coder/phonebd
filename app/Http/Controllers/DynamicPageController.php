<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DynamicPageController extends Controller
{
    public function show($slug)
    {
        $dynamicPage = \App\Models\DynamicPage::where('slug', $slug)->firstOrFail();

        if (!$dynamicPage->is_active) {
            abort(404);
        }

        $dynamicPage->load(['products' => function ($query) {
            $query->select('*')
                  ->selectSub(function ($q) {
                        $q->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4)')
                          ->from('reviews')
                          ->whereColumn('reviews.product_id', 'products.id')
                          ->where('is_approve', true);
                     }, 'avg_rating')
                  ->with(['brand', 'category'])
                  ->where('is_published', true)
                  ->where('status', '!=', 'discontinued')
                  ->when(request('sort') === 'price_asc', fn($q) => $q->orderBy('base_price', 'asc'))
                  ->when(request('sort') === 'price_desc', fn($q) => $q->orderBy('base_price', 'desc'))
                  ->when(request('sort') === 'rating', fn($q) => $q->orderBy('avg_rating', 'desc'))
                  ->when(request('sort') === 'popular', fn($q) => $q->withCount('reviews')->orderBy('reviews_count', 'desc'))
                  ->when(!request('sort') || request('sort') === 'latest', fn($q) => $q->latest('products.created_at'));
        }]);

        return view('dynamic_pages.show', compact('dynamicPage'));
    }
}
