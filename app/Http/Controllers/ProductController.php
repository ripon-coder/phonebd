<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreReviewRequest;
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
        $averageRating = $this->reviewService->getAverageRating($product);

        return view('product.show', compact('product', 'similarPriceProducts', 'similarProducts', 'approvedReviews', 'totalReviews', 'averageRating'));
    }

    public function storeReview(StoreReviewRequest $request, Product $product)
    {
        $validated = $request->validated();

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
                    'no_spam_rating' => $review->no_spam_rating,
                ];
            }),
            'hasMore' => $hasMore,
            'nextPage' => $page + 1,
        ]);
    }
}
