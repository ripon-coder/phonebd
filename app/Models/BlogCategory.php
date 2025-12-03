<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasStorageImage; // Added this line

class BlogCategory extends Model
{
    use HasFactory, HasStorageImage, \App\Traits\DeletesOldImages, \App\Traits\ClearsResponseCache; // Modified this line

    protected $webpFields = ['image'];

    protected $fillable = [
        'name',
        'slug',
        'image',
        'storage_type',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
