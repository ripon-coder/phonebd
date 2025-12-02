<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait ConvertsImagesToWebp
{
    public $pendingWebpDeletions = [];

    protected static function bootConvertsImagesToWebp()
    {
        static::saving(function ($model) {
            $manager = new ImageManager(new Driver());
            
            foreach ($model->getWebpFields() as $field) {
                // 1. Handle deletion of the PREVIOUS image (if updating/replacing)
                if ($model->isDirty($field)) {
                    $diskName = $model->storage_type ?? 'backblaze';
                    $disk = Storage::disk($diskName);
                    $oldPath = $model->getOriginal($field);

                    if ($oldPath && $disk->exists($oldPath)) {
                        $model->pendingWebpDeletions[] = $oldPath;
                    }
                }

                // 2. Handle conversion of the NEW image
                if ($model->isDirty($field) && !empty($model->$field)) {
                    $path = $model->$field;
                    
                    // Skip if already webp
                    if (Str::endsWith(Str::lower($path), '.webp')) {
                        continue;
                    }

                    $diskName = $model->storage_type ?? 'backblaze';
                    $disk = Storage::disk($diskName);
                    
                    if ($disk->exists($path)) {
                        try {
                            $content = $disk->get($path);
                            $image = $manager->read($content);
                            
                            // Optimize: Resize if width is too large
                            if ($image->width() > 1200) {
                                $image->scale(width: 1200);
                            }
                            
                            $encoded = $image->toWebp(quality: 80);
                            $content = (string) $encoded;
                            
                            $newPath = preg_replace('/\.[^.]+$/', '.webp', $path);
                            
                            // Upload WebP with correct mime type
                            if (!$disk->put($newPath, $content, [
                                'visibility' => 'public',
                                'mimetype' => 'image/webp'
                            ])) {
                                throw new \Exception("Failed to upload WebP file to disk.");
                            }
                            
                            if (!$disk->exists($newPath)) {
                                throw new \Exception("Failed to verify uploaded WebP file: " . $newPath);
                            }
                            Log::info("Verified WebP upload: " . $newPath);
                            
                            // Mark original for deletion
                            if ($path !== $newPath) {
                                $model->pendingWebpDeletions[] = $path;
                            }
                            
                            // Update model to point to new WebP file
                            $model->$field = $newPath;
                            
                            // Free memory
                            unset($content, $image, $encoded);
                            
                        } catch (\Exception $e) {
                            Log::error("WebP conversion failed: " . $e->getMessage());
                        }
                    }
                }
            }
        });

        static::saved(function ($model) {
            if (!empty($model->pendingWebpDeletions)) {
                $diskName = $model->storage_type ?? 'backblaze';
                $disk = Storage::disk($diskName);

                foreach (array_unique($model->pendingWebpDeletions) as $path) {
                    try {
                        if ($disk->exists($path)) {
                            if ($disk->delete($path)) {
                                Log::info("Deleted original file (in saved): " . $path);
                            } else {
                                Log::error("Failed to delete original file (disk returned false): " . $path);
                            }
                        } else {
                            Log::warning("Original file not found for deletion (in saved): " . $path);
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to delete original file: " . $path . " Error: " . $e->getMessage());
                    }
                }
                $model->pendingWebpDeletions = [];
            }
        });
    }
    
    public function getWebpFields(): array
    {
        return property_exists($this, 'webpFields') ? $this->webpFields : [];
    }
}
