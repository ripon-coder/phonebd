<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use \App\Traits\ClearsResponseCache;

    public $clearResponseCacheOnCreate = false;

    protected $fillable = [
        
        'product_id',
        'name',
        'review',
        'rating_design',
        'rating_performance',
        'rating_camera',
        'rating_battery',
        'pros',
        'cons',
        'variant',
        'images',
        'storage_type',
        'finger_print',
        'ip_address',
        'no_spam_rating',
        'is_approve',
        'is_ip_banned',
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'images' => 'array',
        'is_approve' => 'boolean',
        'is_ip_banned' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
