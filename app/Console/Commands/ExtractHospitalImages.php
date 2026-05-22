<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hospital;
use App\Models\User;
use App\Models\HospitalImage;
use Illuminate\Support\Facades\Storage;
use DB;

class ExtractHospitalImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-hospital-images';

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
    
        
        $list = Hospital::where('temp_logo','!=','')->get();
        
        foreach($list as $k){
            $temp_name = $k->temp_logo;
            $temp_names = explode(".",$temp_name);
            $temp_names = array_reverse($temp_names);
            $file_name = time() . uniqid() . "." . $temp_names[0]??'jpg';
            //$file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $path = config('global.user_image_upload_dir').$file_name;
            if(file_exists(storage_path('app/uploads/extracted/'.$k->temp_logo))){
                $new_path = Storage::disk(config('global.upload_bucket'))->put($path, file_get_contents(storage_path('app/uploads/extracted/'.$k->temp_logo)));
                $user = User::find($k->user_id);
                $user->user_image = $file_name;
                $user->save();

                $hs = Hospital::find($k->id);
                $hs->temp_logo = '';
                $hs->save();

                unlink(storage_path('app/uploads/extracted/'.$k->temp_logo));
            }else{
                echo $k->temp_logo." image not found ";
            }
        }


        $list = Hospital::where('temp_trade_licence','!=','')->get();
        echo $list->count();
        foreach($list as $k){
            $temp_name = $k->temp_trade_licence;
            $temp_names = explode(".",$temp_name);
            $temp_names = array_reverse($temp_names);
            $file_name = time() . uniqid() . "." . $temp_names[0]??'jpg';
            //$file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
            $path = config('global.trade_licenece_image_upload_dir').$file_name;
            if(file_exists(storage_path('app/uploads/extracted/'.$k->temp_trade_licence))){
                $new_path = Storage::disk(config('global.upload_bucket'))->put($path, file_get_contents(storage_path('app/uploads/extracted/'.$k->temp_trade_licence)));
                

                $hs = Hospital::find($k->id);
                $hs->trade_licenece = $file_name;
                $hs->temp_trade_licence = '';
                $hs->save();

                if(unlink(storage_path('app/uploads/extracted/'.$k->temp_trade_licence))){
                    echo "image removed";
                }else{
                    echo "faild to remove";
                }
            }else{
                echo $k->temp_trade_licence." trade licence not found ";
            }
        }


        $list = Hospital::where('temp_images','!=','')->get();
        echo $list->count();
        foreach($list as $k){
            $images = $k->temp_images;
            $images = explode(",",$images);
            if(!empty($images)){ 
                $full_image_names =[];
                foreach($images as $temp_name){
                    if($temp_name){
                        $temp_names = explode(".",$temp_name);
                        $temp_names = array_reverse($temp_names);
                        $file_name = time() . uniqid() . "." . $temp_names[0]??'jpg';
                        //$file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $path = config('global.hospital_image_upload_dir').$file_name;
                        if(file_exists(storage_path('app/uploads/extracted/'.$temp_name))){
                            $new_path = Storage::disk(config('global.upload_bucket'))->put($path, file_get_contents(storage_path('app/uploads/extracted/'.$temp_name)));
                            

                            $image = new HospitalImage();
                            $image->hospital_id = $k->id;
                            $image->image_name = $file_name;
                            $image->created_at = gmdate('Y-m-d H:i:s');
                            $image->updated_at = gmdate('Y-m-d H:i:s');
                            $image->save();

                            unlink(storage_path('app/uploads/extracted/'.$temp_name));
                            
                            
                        }else{
                            $full_image_names[]=$temp_name;
                            echo $temp_name." image not found ";
                        }
                    }
                }
                $full_image_names = array_filter($full_image_names);
                
                $hs = Hospital::find($k->id);
                if(empty($full_image_names)){
                    $hs->temp_images = '';
                }else{
                    $hs->temp_images = implode(",",$full_image_names);
                }
                
                $hs->save();
            }
        }
    }
}
