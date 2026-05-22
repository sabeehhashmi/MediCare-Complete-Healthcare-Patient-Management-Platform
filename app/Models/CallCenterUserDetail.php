<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CallCenterUserDetail extends Model
{
    use HasFactory ,SoftDeletes;
    protected $table = 'callcenter_user_details';
    protected $fillable = [
        'user_id', 
        'gender',
        'user_id',
        'country_id',
        'emirate_id',
        'area_id',
        'address',
        'location',
        'website',
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function emirate()
    {
        return $this->belongsTo(Emirate::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
   
    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }


}
