<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraSample extends Model
{
    use \App\Traits\ClearsResponseCache;

    public $clearResponseCacheOnCreate = false;

    protected $fillable = [
        'product_id',
        'name',
        'variant',
        'images',
        'is_approve',
        'ip_address',
        'finger_print',
        'storage_type',
        'is_ip_banned',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approve' => 'boolean',
        'is_ip_banned' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
