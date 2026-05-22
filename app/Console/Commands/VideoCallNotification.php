<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Models\DoctorPatientAppointment;

class VideoCallNotification extends Command
{
    protected $signature = 'video:call {user_id} {channel}';
    protected $description = 'Send video call notification to doctor';

    protected $database;

    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->database = $firebaseService->getDatabase();
    }

    public function handle()
    {
        
        $doctor_id = $this->argument('user_id');
        $channel = $this->argument('channel');
        
        $order = DoctorPatientAppointment::with([ 'doctor','doctor.user'])
           ->where('doctor_patient_appointments.booking_id','=','#'.$channel)->get()->first();
        
         $doctor_id =$order->doctor->user->id;
         
         if($this->argument('user_id') == $doctor_id){
             return true;
         }
         
        $notification_id = time();

        $data["Doctor/".$doctor_id."/".$notification_id] = [
            "title" => "Incoming Video Call",
            "description" => "You have an incoming call",
            "notificationType" => "video_call",
            "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
            "channel_name" => $channel,
            "read" => "0",
            "seen" => "0",
            "type" => "call"
        ];

        $this->database->getReference()->update($data);

        $this->info("Video call notification sent");
    }
}