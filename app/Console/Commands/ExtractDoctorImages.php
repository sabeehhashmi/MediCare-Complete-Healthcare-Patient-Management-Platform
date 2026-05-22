<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use DB;

class ExtractDoctorImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-doctor-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $list = Doctor::where('temp_photo_file_name','!=','')->get();
        
        foreach($list as $k){
            $temp_name = $k->temp_photo_file_name;
            $temp_names = explode(".",$temp_name);
            $temp_names = array_reverse($temp_names);
            $file_name = time() . uniqid() . "." . $temp_names[0]??'jpg';
            //$file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $path = config('global.user_image_upload_dir').$file_name;
            if(file_exists(storage_path('app/uploads/extracted_doctor/'.$k->temp_photo_file_name))){
                $new_path = Storage::disk(config('global.upload_bucket'))->put($path, file_get_contents(storage_path('app/uploads/extracted_doctor/'.$k->temp_photo_file_name)));
                $user = User::find($k->user_id);
                $user->user_image = $file_name;
                $user->save();

                $hs = Doctor::find($k->id);
                $hs->temp_photo_file_name = '';
                $hs->save();

                unlink(storage_path('app/uploads/extracted_doctor/'.$k->temp_photo_file_name));
            }else{
                echo $k->temp_photo_file_name." image not found ";
            }
        }
    }
}
