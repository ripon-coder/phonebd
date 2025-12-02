<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasStorageImage;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes, HasStorageImage, \App\Traits\DeletesOldImages;
    
    protected $webpFields = ['featured_image'];

    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'featured_image',
        'storage_type',
        'content',
        'is_published',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
