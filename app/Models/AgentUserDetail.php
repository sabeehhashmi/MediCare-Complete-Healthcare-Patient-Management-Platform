<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentUserDetail extends Model
{

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'status',
        'user_id',
        'country_id',
        'emirate_id',
        'area_id',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function user() {
    //     return $this->belongsTo(User::class);
    // }

    public function emirate()
    {
        return $this->belongsTo(Emirate::class, 'emirate_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }
    public function call_center(){
        return $this->belongsTo(CallCenterUserDetail::class,'callcenter_id');
    }

}
