<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'category_id',
        'short_description',
        'status',
        'base_price',
        'is_published',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function specGroups(): HasMany
    {
        return $this->hasMany(ProductSpecGroup::class);
    }

    public function variantPrices(): HasMany
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class);
    }
}
