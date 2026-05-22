<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicinCategory extends Model
{
    protected $table = 'medicine_categories';
    use HasFactory,SoftDeletes;
    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'medicin_category_id');
    }
}
