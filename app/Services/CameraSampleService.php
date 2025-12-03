<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CameraSampleService
{
    public function getApprovedSamples(Product $product, $limit = 100)
    {
        return $product->cameraSamples()
            ->where('is_approve', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getTotalApprovedSamples(Product $product)
    {
        $samples = $product->cameraSamples()
            ->where('is_approve', true)
            ->get();

        $count = 0;
        foreach ($samples as $sample) {
            if (is_array($sample->images)) {
                $count += count($sample->images);
            }
        }

        return $count;
    }

    public function storeSample(array $data, Product $product, $photos = [])
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
                            $filename = uniqid('sample_') . '.webp';
                            $path = 'camera-samples/' . $filename;

                            // Optimize and convert to WebP
                            $manager = new ImageManager(new Driver());
                            $image = $manager->read($photo);
                            
                            // Resize if width is greater than 1600px (larger for camera samples)
                            if ($image->width() > 1600) {
                                $image->scale(width: 1600);
                            }
                            
                            // Higher quality for camera samples
                            $encoded = $image->toWebp(quality: 85);

                            // Store the optimized image
                            if (Storage::disk($disk)->put($path, (string) $encoded)) {
                                $imagePaths[] = Storage::disk('backblaze')->url($path);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to upload camera sample to Backblaze: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        return $product->cameraSamples()->create([
            'name' => $data['name'],
            'variant' => $data['variant'] ?? null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'storage_type' => $disk,
            'finger_print' => $data['finger_print'] ?? null,
            'ip_address' => request()->ip(),
            'is_approve' => false,
        ]);
    }
}
