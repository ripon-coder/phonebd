<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory, \App\Traits\DeletesOldImages;
    
    public $imageFields = ['site_logo', 'site_favicon'];
    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_logo',
        'site_favicon',

        'primary_color',
        'secondary_color',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'contact_email',
        'contact_phone',
        'contact_address',

        'facebook_link',
        'youtube_link',
        'instagram_link',
        'twitter_link',

        'is_maintenance_mode',
        'maintenance_message',
        'storage_type',
    ];

    protected $casts = [
        'is_maintenance_mode' => 'boolean',
    ];
}
