<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

trait DeletesOldImages
{
    protected static function bootDeletesOldImages()
    {
        static::updating(function ($model) {
            foreach ($model->getImageFieldsToDelete() as $field) {
                if ($model->isDirty($field)) {
                    $oldPath = $model->getOriginal($field);
                    $newPath = $model->$field;
                    
                    if ($oldPath && $oldPath !== $newPath) {
                        $diskName = $model->storage_type ?? 'backblaze';
                        
                        try {
                            if (Storage::disk($diskName)->exists($oldPath)) {
                                Storage::disk($diskName)->delete($oldPath);
                                Log::info("DeletesOldImages: Successfully deleted old image: " . $oldPath);
                            }
                        } catch (\Exception $e) {
                            Log::error("DeletesOldImages: Failed to delete old image: " . $e->getMessage());
                        }
                    }
                }
            }
        });




        static::deleted(function ($model) {
            // Only handle force delete (where soft deletes are used, this event fires for soft delete too, 
            // but we check if it is force deleting if possible, or usually we only delete files on force delete).
            // However, SoftDeletes trait fires 'forceDeleted' event. 'deleted' is for soft delete.
            // If the model does NOT use SoftDeletes, 'deleted' is the end.
            // If the model USES SoftDeletes, 'forceDeleted' is the end.
            
            if (!in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($model))) {
                // Not using soft deletes, so this is permanent.
                foreach ($model->getImageFieldsToDelete() as $field) {
                    $path = $model->$field;
                    if ($path) {
                        $diskName = $model->storage_type ?? 'backblaze';
                        try {
                            if (Storage::disk($diskName)->exists($path)) {
                                Storage::disk($diskName)->delete($path);
                                Log::info("DeletesOldImages: Successfully deleted image on delete: " . $path);
                            }
                        } catch (\Exception $e) {
                            Log::error("DeletesOldImages: Failed to delete image on delete: " . $e->getMessage());
                        }
                    }
                }
            }
        });

        if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive(static::class))) {
            static::forceDeleted(function ($model) {
                foreach ($model->getImageFieldsToDelete() as $field) {
                    $path = $model->$field;
                    if ($path) {
                        $diskName = $model->storage_type ?? 'backblaze';
                        try {
                            if (Storage::disk($diskName)->exists($path)) {
                                Storage::disk($diskName)->delete($path);
                                Log::info("DeletesOldImages: Successfully deleted image on force delete: " . $path);
                            }
                        } catch (\Exception $e) {
                            Log::error("DeletesOldImages: Failed to delete image on force delete: " . $e->getMessage());
                        }
                    }
                }
            });
        }
    }
    public function getImageFieldsToDelete(): array
    {
        if (property_exists($this, 'webpFields')) {
            return $this->webpFields;
        }
        
        if (property_exists($this, 'imageFields')) {
            return $this->imageFields;
        }
        
        return ['image'];
    }
}
