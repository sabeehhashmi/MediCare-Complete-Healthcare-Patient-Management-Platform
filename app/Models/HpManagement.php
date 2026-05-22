<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HpManagement extends Model
{

  // disable timestamp
  public $timestamps = false;

  protected $table = 'hp_managements';

    protected $fillable = [
      'meta_key','meta_value'
    ];
    use HasFactory;


    public static function getMetaValue($meta_key)
    {
        return self::where('meta_key', $meta_key)->first()->meta_value;
    }

    public static function getAllMeta()
    {
        return self::all()->pluck('meta_value', 'meta_key')->toArray();
    }

    // get attribute meta value and modify it based on the meta_key if it's img then use the get_uploaded_image_url($data[$image_key]) function to get the image url
    public function getMetaValueAttribute($value)
    {

      $meta_key_parts = explode('_', $this->meta_key);
      $lastPart = end($meta_key_parts);

      if ($lastPart == 'img') {

        //return Storage::disk(config('global.upload_bucket'))->url(config('global.homepage_image_upload_dir') . $value);

          return get_uploaded_image_url($value, 'homepage_image_upload_dir');
        }
        return $value;
    }
}


