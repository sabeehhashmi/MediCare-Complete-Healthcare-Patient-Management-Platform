<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalInstruction extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'title_ar', 'description', 'created_by', 'updated_by', 'type'];

    public static function get_instructions_list($where=[],$params=[]){
        $faq = HospitalInstruction::where($where)->orderBy('created_at','desc');  
        if( !empty($params) ){
            if(isset($params['search_key']) && $params['search_key'] != ''){
                $faq->Where('title','ilike','%'.$params['search_key'].'%');
            }
        }
        return $faq;
    } 
}
