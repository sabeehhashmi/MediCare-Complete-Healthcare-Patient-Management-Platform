<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class InsurencePolicy extends Model
{
    use HasFactory,SoftDeletes;

    public $hidden=['deleted_at'];

    public function getTitleAttribute($value){
        $language = $_POST['language']??1;
        if($language == 2){
            return $this->title_ar;
        }
        return $value;
    }
    
    public function subInsurance()
    {
        return $this->belongsTo(SubInsurencePolicy::class);
    }

    public function sub_insurence_policy()
{
    return $this->hasMany(SubInsurencePolicy::class, 'insurence_id', 'id');
}

// Accessor for the count
public function getSubInsurencePolicyCountAttribute()
{
    // Use the relationship to calculate the count
    $count = $this->sub_insurence_policy()->count();

    // Return 1 if count is 0
    return $count > 0 ? $count : 1;
}

}
