<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasStorageImage
{
    public function getImageUrl(string $field = 'image'): ?string
    {
        if (empty($this->$field)) {
            return null;
        }

        $disk = $this->storage_type ?? 'backblaze';
        
        // If it's a full URL, return it as is
        if (filter_var($this->$field, FILTER_VALIDATE_URL)) {
            return $this->$field;
        }

        return Storage::disk($disk)->url($this->$field);
    }
}
