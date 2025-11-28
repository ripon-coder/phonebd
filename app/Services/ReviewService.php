<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ReviewService
{
    public function getApprovedReviews(Product $product, $limit = 5)
    {
        return $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at')
            ->where('is_approve', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getTotalApprovedReviews(Product $product)
    {
        return $product->reviews()->where('is_approve', true)->count();
    }

    public function storeReview(array $data, Product $product, $photos = [])
    {
        $imagePaths = [];
        if (!empty($photos)) {
            // Check if Backblaze is configured
            $useBackblaze = config('filesystems.disks.backblaze.key') && 
                           config('filesystems.disks.backblaze.secret') &&
                           config('filesystems.disks.backblaze.bucket');
            
            $disk = $useBackblaze ? 'backblaze' : 'public';
            
            foreach ($photos as $photo) {
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
        $pros = isset($data['pros']) ? array_filter($data['pros'], fn($item) => !empty(trim($item))) : null;
        $cons = isset($data['cons']) ? array_filter($data['cons'], fn($item) => !empty(trim($item))) : null;

        return $product->reviews()->create([
            'name' => $data['name'],
            'review' => $data['review'],
            'rating_design' => $data['rating_design'] ?? null,
            'rating_performance' => $data['rating_performance'] ?? null,
            'rating_camera' => $data['rating_camera'] ?? null,
            'rating_battery' => $data['rating_battery'] ?? null,
            'pros' => !empty($pros) ? array_values($pros) : null,
            'cons' => !empty($cons) ? array_values($cons) : null,
            'variant' => $data['variant'] ?? null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'is_approve' => false,
        ]);
    }

    public function getPaginatedReviews(Product $product, $page = 1, $perPage = 5)
    {
        return $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at')
            ->where('is_approve', true)
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    }
}
