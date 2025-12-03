<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, \App\Traits\HasStorageImage, \App\Traits\DeletesOldImages, \App\Traits\ClearsResponseCache;

    protected $webpFields = ['featured_image'];
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Route Model Binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
