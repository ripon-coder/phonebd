<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraSample extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'variant',
        'images',
        'is_approve',
        'ip_address',
        'finger_print',
        'storage_type',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approve' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
