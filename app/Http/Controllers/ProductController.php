<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $reviewService;

    public function __construct(ProductService $productService, ReviewService $reviewService)
    {
        $this->productService = $productService;
        $this->reviewService = $reviewService;
    }

    public function show($category_slug, Product $product)
    {
        $product = $this->productService->getDetails($category_slug, $product);

        if (!$product) {
            abort(404);
        }

        $similarPriceProducts = $this->productService->getSimilarByPrice($product);
        $similarProducts = $this->productService->getSimilarByCategory($product, $similarPriceProducts->pluck('id'));

        $approvedReviews = $this->reviewService->getApprovedReviews($product);
        $totalReviews = $this->reviewService->getTotalApprovedReviews($product);

        return view('product.show', compact('product', 'similarPriceProducts', 'similarProducts', 'approvedReviews', 'totalReviews'));
    }

    public function storeReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating_design' => 'nullable|integer|min:1|max:5',
            'rating_performance' => 'nullable|integer|min:1|max:5',
            'rating_camera' => 'nullable|integer|min:1|max:5',
            'rating_battery' => 'nullable|integer|min:1|max:5',
            'pros' => 'nullable|array',
            'pros.*' => 'nullable|string',
            'cons' => 'nullable|array',
            'cons.*' => 'nullable|string',
            'variant' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:5120', // 5MB max
        ]);

        $this->reviewService->storeReview($validated, $product, $request->file('photos', []));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully! It will be visible after approval.',
            ]);
        }

        return back()->with('success', 'Review submitted successfully! It will be visible after approval.');
    }

    public function getReviews(Request $request, Product $product)
    {
        $perPage = 5;
        $page = $request->get('page', 1);

        $reviews = $this->reviewService->getPaginatedReviews($product, $page, $perPage);
        $totalReviews = $this->reviewService->getTotalApprovedReviews($product);
        $hasMore = ($page * $perPage) < $totalReviews;

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                $avgRating = collect([
                    $review->rating_design,
                    $review->rating_performance,
                    $review->rating_camera,
                    $review->rating_battery
                ])->filter()->avg();

                return [
                    'id' => $review->id,
                    'name' => $review->name,
                    'review' => $review->review,
                    'variant' => $review->variant,
                    'pros' => $review->pros,
                    'cons' => $review->cons,
                    'images' => $review->images,
                    'avg_rating' => $avgRating,
                    'created_at' => $review->created_at->diffForHumans(),
                ];
            }),
            'hasMore' => $hasMore,
            'nextPage' => $page + 1,
        ]);
    }
}
