<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReviewService
{
    public function getAverageRating(Product $product)
    {
        $result = $product->reviews()
            ->where('is_approve', true)
            ->selectRaw('AVG((COALESCE(rating_design,0) + COALESCE(rating_performance,0) + COALESCE(rating_camera,0) + COALESCE(rating_battery,0)) / 4) as avg_rating')
            ->first();

        return $result ? round($result->avg_rating, 1) : 0;
    }

    public function getApprovedReviews(Product $product, $limit = 5)
    {
        return $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at', 'no_spam_rating')
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
        $disk = null;
        if (!empty($photos)) {
            // Check if Backblaze is configured
            $useBackblaze = config('filesystems.disks.backblaze.key') && 
                           config('filesystems.disks.backblaze.secret') &&
                           config('filesystems.disks.backblaze.bucket');
            
            if ($useBackblaze) {
                $disk = 'backblaze';
                foreach ($photos as $photo) {
                    if ($photo && $photo->isValid()) {
                        try {
                            // Create unique filename
                            $filename = uniqid('review_') . '.webp';
                            $path = 'reviews/' . $filename;

                            // Optimize and convert to WebP
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($photo);
                        
                        // Resize if width is greater than 1200px
                        if ($image->width() > 1200) {
                            $image->scale(width: 1200);
                        }
                        
                        $encoded = $image->toWebp(quality: 70);

                            // Store the optimized image
                            if (Storage::disk($disk)->put($path, (string) $encoded)) {
                                $imagePaths[] = Storage::disk('backblaze')->url($path);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to upload review image to Backblaze: ' . $e->getMessage());
                            // Continue with other images, don't fail the entire review
                        }
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
            'storage_type' => $disk,
            'finger_print' => $data['finger_print'] ?? null,
            'ip_address' => request()->ip(),
            'no_spam_rating' => $this->calculateSpamScore($data),
            'is_approve' => false,
        ]);
    }

    public function getPaginatedReviews(Product $product, $page = 1, $perPage = 5)
    {
        return $product->reviews()
            ->select('id', 'name', 'review', 'rating_design', 'rating_performance', 'rating_camera', 'rating_battery', 'pros', 'cons', 'variant', 'images', 'created_at', 'no_spam_rating')
            ->where('is_approve', true)
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    }

    private function calculateSpamScore(array $data): int
    {
        $score = 10; // Start with perfect score (No Spam)

        // 1. Review Length Penalty
        $reviewLength = strlen(trim($data['review']));
        if ($reviewLength < 15) {
            $score -= 4; // Very short review
        } elseif ($reviewLength < 50) {
            $score -= 2; // Short review
        }

        // 2. Detailed Ratings Penalty
        $hasDetailedRatings = !empty($data['rating_design']) || 
                              !empty($data['rating_performance']) || 
                              !empty($data['rating_camera']) || 
                              !empty($data['rating_battery']);
        
        if (!$hasDetailedRatings) {
            $score -= 2; // Lazy reviewer, didn't rate specific aspects
        }

        // 3. Pros/Cons Penalty
        $hasPros = !empty($data['pros']) && is_array($data['pros']) && count(array_filter($data['pros'])) > 0;
        $hasCons = !empty($data['cons']) && is_array($data['cons']) && count(array_filter($data['cons'])) > 0;

        if (!$hasPros && !$hasCons) {
            $score -= 1; // Didn't provide any pros or cons
        }

        // 4. Name Check (Basic)
        if (preg_match('/http|www|\.com/i', $data['name'])) {
            $score -= 5; // URL in name is highly suspicious
        }

        // 5. Suspicious Keywords Check
        $suspiciousKeywords = [
            'casino', 'gambling', 'betting', 'crypto', 'bitcoin', 'forex', 
            'investment', 'loan', 'viagra', 'cialis', 'sex', 'porn', 
            'dating', 'click here', 'buy now', 'free money', 'prize', 
            'winner', 'lottery', 'pharmacy', 'medication','motherchod',
            // Bangla Bad Words
            'চুদ', 'মাগি', 'খানকি', 'বেশ্যা', 'কুত্তা', 'হারামি', 'শালা', 'শুয়োর', 'বাল', 'নটির পোলা',
            'bal','মাদারচোদ','chudanir pola', 'vodai', 'voda', 'shala', 'sala', 'kutta', 'kuttar baccha', 
            'khanki', 'khankir pola', 'magi', 'magir pola', 'bessha', 'shuor', 'suor', 
            'harami', 'chud', 'marani', 'putki', 'bara', 'nj'
        ];
        
        $reviewText = strtolower($data['review']);
        foreach ($suspiciousKeywords as $keyword) {
            if (str_contains($reviewText, $keyword)) {
                $score -= 8; // Heavy penalty for spam keywords
                break; // One hit is enough to flag
            }
        }

        // Ensure score stays within 0-10 range
        return max(0, min(10, $score));
    }
}
