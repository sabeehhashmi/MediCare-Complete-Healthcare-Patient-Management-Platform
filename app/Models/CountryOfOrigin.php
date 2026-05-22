<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryOfOrigin extends Model
{

    public $fillable = [
        'name',
        'status',
        'name_ar'
    ];
    use HasFactory,SoftDeletes;
    public $appends = ['prefix','dial_code'];
    public function getPrefixAttribute(){
        return '';
    }
    public function getDialCodeAttribute(){
        return '';
    }
}
