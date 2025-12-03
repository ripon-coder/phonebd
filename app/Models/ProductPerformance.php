<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPerformance extends Model
{
    use \App\Traits\ClearsResponseCache;

    protected $fillable = [
        'product_id',
        'gaming_fps',
        'battery_sot',
        'camera_score',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
