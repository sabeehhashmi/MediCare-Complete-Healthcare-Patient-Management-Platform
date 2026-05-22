<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubInsurencePolicy extends Model
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

    public function insurence(){
        return $this->belongsTo('App\Models\InsurencePolicy','insurence_id','id');
    }
    public function insurence_with_trashed(){
        return $this->belongsTo('App\Models\InsurencePolicy','insurence_id','id')->withTrashed();
    }
}
