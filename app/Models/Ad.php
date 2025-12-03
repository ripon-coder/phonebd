<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\HasStorageImage, \App\Traits\DeletesOldImages, \App\Traits\ClearsResponseCache;

    protected $webpFields = ['image'];

    protected $fillable = [
        'title',
        'type',
        'position',
        'image',
        'link',
        'script',
        'is_active',
        'views',
        'start_date',
        'storage_type',
    ];
}
