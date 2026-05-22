<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'name_en',
        'name_ar',
        'country_id',
        'emirate_id',
        'active'
    ];

    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }

    public function emirate()
    {
        return $this->belongsTo(Emirate::class);
    }

    public function emirate_with_trashed()
    {
        return $this->belongsTo(Emirate::class)->withTrashed();
    }
}
