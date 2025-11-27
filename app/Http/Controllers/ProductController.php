<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function show($category_slug, Product $product)
    {
        // Eager load relationships for the main product with specific columns where possible
        $product->load([
            'brand:id,name,slug', 
            'category:id,name,slug', 
            'variantPrices', 
            'specValues.productSpecItem', 
            'specValues.productSpecGroup', 
            'faqs'
        ]);

        if ($product->category->slug !== $category_slug) {
            abort(404);
        }
        $priceRange = $product->base_price * 0.20; // 20% range
        $minPrice = $product->base_price - $priceRange;
        $maxPrice = $product->base_price + $priceRange;

        $similarPriceProducts = Product::select('id', 'title', 'slug', 'image', 'base_price', 'category_id')
            ->with('category:id,name,slug') // Eager load category with specific columns
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereBetween('base_price', [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->take(5)
            ->get();

        $similarProducts = Product::select('id', 'title', 'slug', 'image', 'base_price', 'category_id')
            ->with('category:id,name,slug') // Eager load category with specific columns
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereNotIn('id', $similarPriceProducts->pluck('id'))
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Fetch reviews logic moved from Blade with specific columns
        $approvedReviews = $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at')
            ->where('is_approve', true)
            ->latest()
            ->take(5)
            ->get();
        $totalReviews = $product->reviews()->where('is_approve', true)->count();

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



        $imagePaths = [];
        if ($request->hasFile('photos')) {
            // Check if Backblaze is configured
            $useBackblaze = config('filesystems.disks.backblaze.key') && 
                           config('filesystems.disks.backblaze.secret') &&
                           config('filesystems.disks.backblaze.bucket');
            
            $disk = $useBackblaze ? 'backblaze' : 'public';
            
            foreach ($request->file('photos') as $photo) {
                if ($photo && $photo->isValid()) {
                    try {
                        $path = $photo->store('reviews', $disk);
                        if ($path) {
                            if ($disk === 'backblaze') {
                                $imagePaths[] = Storage::disk('backblaze')->url($path);
                            } else {
                                $imagePaths[] = asset('storage/' . $path);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to upload review image: ' . $e->getMessage());
                        // Continue with other images, don't fail the entire review
                    }
                }
            }
        }

        // Filter out empty pros/cons
        $pros = isset($validated['pros']) ? array_filter($validated['pros'], fn($item) => !empty(trim($item))) : null;
        $cons = isset($validated['cons']) ? array_filter($validated['cons'], fn($item) => !empty(trim($item))) : null;

        $review = $product->reviews()->create([
            'name' => $validated['name'],
            'review' => $validated['review'],
            'rating_design' => $validated['rating_design'] ?? null,
            'rating_performance' => $validated['rating_performance'] ?? null,
            'rating_camera' => $validated['rating_camera'] ?? null,
            'rating_battery' => $validated['rating_battery'] ?? null,
            'pros' => !empty($pros) ? array_values($pros) : null,
            'cons' => !empty($cons) ? array_values($cons) : null,
            'variant' => $validated['variant'] ?? null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'is_approve' => false,
        ]);

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
        
        $reviews = $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at')
            ->where('is_approve', true)
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        $totalReviews = $product->reviews()->where('is_approve', true)->count();
        $hasMore = ($page * $perPage) < $totalReviews;
        
        return response()->json([
            'reviews' => $reviews->map(function($review) {
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
