<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes, \Laravel\Scout\Searchable, \App\Traits\DeletesOldImages;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_nospace' => str_replace(' ', '', $this->name),
        ];
    }

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

    public function dynamicPages(): HasMany
    {
        return $this->hasMany(DynamicPage::class);
    }
}
