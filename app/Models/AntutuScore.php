<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntutuScore extends Model
{
    use \App\Traits\ClearsResponseCache;

    protected $fillable = [
        'product_id',
        'total_score',
        'cpu_score',
        'gpu_score',
        'mem_score',
        'ux_score',
        'version',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
