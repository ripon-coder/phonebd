<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecValue extends Model
{
    protected $fillable = [
        'product_id',
        'product_spec_item_id',
        'key',
        'value',
    ];
}
