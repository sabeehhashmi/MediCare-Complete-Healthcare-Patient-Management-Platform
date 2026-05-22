<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteService extends Model
{
    protected $table = 'website_services';

    protected $fillable = [
        'title',
        'desc',
        'status',
        'icon',      // Add this
        'icon_type'  // Add this (image or fontawesome)
    ];

    protected $appends = ['icon_url'];

    public function getIconUrlAttribute()
    {
        if ($this->icon && $this->icon_type == 'image') {
            return get_uploaded_image_url($this->icon, 'website_services_dir');
        }
        return null;
    }
}
