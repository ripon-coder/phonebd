<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'market_status',
        'variant_type',
        'is_expected',
        'ram',
        'storage',
        'amount',
        'currency',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
