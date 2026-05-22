<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
class UpdateUserFirebaseNode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:firebase_node {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

     protected $firebaseService;
     protected $notificationService;

     public function __construct(FirebaseService $firebaseService,FirebasePushNotificationService $notificationService)
     {
         parent::__construct();
 
         $this->database = $firebaseService->getDatabase();
        //  $this->notificationService = $notificationService;
        //  $result = $this->notificationService->sendNotification('frl3P1HXdUervps0nwS7UQ:APA91bHo64oCqRqrC26-qP5daNj2J0SaYpq7J4ijS_aI65955TTC2okKHusStD6XHCPYxfUxOduBEIsnNt96hQRtokHGRTai3BJuZvY6fwBq4QZ3sHabNCsLK7SzZR47bWXyLJeDUQu2',
        //     [
        //         "title" => "test",
        //         "body" => "test desc",
        //         "icon" => 'myicon',
        //         "sound" => 'default',
        //         "click_action" => "EcomNotification"
        //     ],
        //     [
        //         "type" => "test",
        //         "notificationID" => (string)time(),
        //         "history_id" => (string) "1",
        //         "imageURL" => "",
        //     ]);
     }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $user = \App\Models\User::where(['id'=>$user_id])->get();
        if($user->count() > 0){
          $user = $user->first();
          if($user->firebase_user_key != ''){
                $this->database->getReference('Users/' . $user->firebase_user_key . '/')->update([
                    'fcm_token' => (string)$user->user_device_token,
                    'user_name' => (string)$user->name,
                    'email'     => (string)$user->email,
                    'user_id'   => (string)$user->id,
                    'user_image'=> (string)$user->user_image,
                    'dial_code' => (string)$user->dial_code,
                    'phone'     => (string)$user->phone,
                    'privacy_id'=>(string)$user->privacy_id,
                    'send_notification'=>(string)$user->send_notification,
                    'mute_message'=>(string)$user->mute_message,
                    'last_login'=> (string)time()
                ]);
            }else{
                $user = \App\Models\User::find($user_id);
                $fb_user_refrence = $this->database->getReference('Users/')
                ->push([
                    'fcm_token' => (string)$user->user_device_token,
                    'user_name' => (string)$user->name,
                    'email'     => (string)$user->email,
                    'user_id'   => (string)$user->id,
                    'user_image'=> (string)$user->user_image,
                    'dial_code' => (string)$user->dial_code,
                    'phone'     => (string)$user->phone,
                    'privacy_id'=>(string)$user->privacy_id,
                    'send_notification'=>(string)$user->send_notification,
                    'mute_message'=>(string)$user->mute_message,
                    'last_login'=> (string)time()
                ]);
                $user->firebase_user_key = $fb_user_refrence->getKey();
                $user->save();
            }
        }
        return 0;
    }
}
