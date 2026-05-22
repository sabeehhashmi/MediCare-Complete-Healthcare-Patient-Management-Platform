<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,softDeletes;
    public $appends = ['file_url'];
    public function getFileUrlAttribute(){
        return get_uploaded_image_url($this->file_name,'product_image_upload_dir');
    }
    public function getStatusText()
    {
        if ($this->product_status == 1) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }
    public function getPriceAttribute($value){
        return rtrim(sprintf('%.20f', $value), '0');
    }
}
