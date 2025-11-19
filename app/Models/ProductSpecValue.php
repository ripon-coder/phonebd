<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecValue extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id',
        'product_spec_group_id',
        'product_spec_item_id',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productSpecGroup()
    {
        return $this->belongsTo(ProductSpecGroup::class);
    }

    public function productSpecItem()
    {
        return $this->belongsTo(ProductSpecItem::class, 'product_spec_item_id');
    }
}
