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
