<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\HasStorageImage, \Laravel\Scout\Searchable, \App\Traits\DeletesOldImages, \App\Traits\ClearsResponseCache;

    protected static function booted()
    {
        static::forceDeleted(function ($product) {
            // Handle Camera Samples Deletion including images
            if ($product->cameraSamples()->exists()) {
                foreach ($product->cameraSamples as $sample) {
                    if (!empty($sample->images) && is_array($sample->images)) {
                        $diskName = $sample->storage_type ?? 'backblaze';
                        foreach ($sample->images as $image) {
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk($diskName)->exists($image)) {
                                    \Illuminate\Support\Facades\Storage::disk($diskName)->delete($image);
                                }
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error("Failed to delete sample image: " . $e->getMessage());
                            }
                        }
                    }
                    $sample->delete();
                }
            }

             // Handle Reviews Deletion including images
            if ($product->reviews()->exists()) {
                foreach ($product->reviews as $review) {
                    if (!empty($review->images) && is_array($review->images)) {
                        $diskName = $review->storage_type ?? 'backblaze';
                        foreach ($review->images as $image) {
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk($diskName)->exists($image)) {
                                    \Illuminate\Support\Facades\Storage::disk($diskName)->delete($image);
                                }
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error("Failed to delete review image: " . $e->getMessage());
                            }
                        }
                    }
                    $review->delete();
                }
            }
        });
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_nospace' => str_replace(' ', '', $this->title),
            'brand' => $this->brand ? $this->brand->name : '',
            'brand_nospace' => $this->brand ? str_replace(' ', '', $this->brand->name) : '',
        ];
    }

    protected $webpFields = ['image', 'meta_image'];

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'category_id',
        'image',
        'short_description',
        'storage_type',
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
