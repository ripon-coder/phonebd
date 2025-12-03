<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\DeletesOldImages, \App\Traits\ClearsResponseCache;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'image',
        'meta_title',
        'meta_description',
        'storage_type',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
