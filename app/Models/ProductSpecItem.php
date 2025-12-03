<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecItem extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\ClearsResponseCache;

    protected $fillable = [
        'product_spec_group_id',
        'slug',
        'label',
        'input_type',
        'options',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductSpecGroup::class, 'product_spec_group_id');
    }

    public function values()
    {
        return $this->hasMany(ProductSpecValue::class);
    }
    public function items()
    {
        return $this->hasMany(ProductSpecItem::class);
    }
}
