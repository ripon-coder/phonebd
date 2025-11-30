<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'category_id',
        'image',
        'short_description',
        'status',
        'base_price',
        'raw_html',
        'is_raw_html',
        'is_featured',
        'is_published',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'is_sample',
        'sample_count_max',
        'is_review',
        'review_count_max',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
    ];


    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function specValues()
    {
        return $this->hasMany(ProductSpecValue::class, 'product_id');
    }

    public function variantPrices(): HasMany
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class);
    }

    public function cameraSamples(): HasMany
    {
        return $this->hasMany(CameraSample::class);
    }

    public function productPerformance()
    {
        return $this->hasOne(ProductPerformance::class);
    }

    public function antutuScore()
    {
        return $this->hasOne(AntutuScore::class);
    }

    public function productFaqs()
    {
        return $this->hasMany(ProductFaq::class);
    }

    public function getSpecGroupsAttribute()
    {
        $specValues = $this->specValues;

        return $specValues->groupBy(function ($value) {
            return $value->productSpecGroup ? $value->productSpecGroup->name : 'Other';
        })->map(function ($values, $groupName) {
            return (object) [
                'name' => $groupName,
                'items' => $values->map(function ($value) {
                    return (object) [
                        'key' => $value->productSpecItem ? $value->productSpecItem->label : '',
                        'value' => $value->value,
                    ];
                }),
            ];
        });
    }
}
