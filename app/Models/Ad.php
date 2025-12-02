<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, \App\Traits\HasStorageImage, \App\Traits\DeletesOldImages;

    protected $webpFields = ['image'];

    protected $fillable = [
        'title',
        'position',
        'image',
        'link',
        'script',
        'status',
        'start_date',
        'end_date',
        'storage_type',
    ];
}
