<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_spec_group_id',
        'key',
        'value',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductSpecGroup::class, 'product_spec_group_id');
    }
}
