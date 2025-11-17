<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
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
