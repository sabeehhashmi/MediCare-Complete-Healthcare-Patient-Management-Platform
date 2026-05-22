<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emirate extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'name_en',
        'name_ar',
        'active',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }

    public function country_with_trashed()
    {
        return $this->belongsTo(CountryModel::class)->withTrashed();
    }

}
