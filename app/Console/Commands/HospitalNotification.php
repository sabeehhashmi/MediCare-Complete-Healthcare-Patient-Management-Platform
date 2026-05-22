<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;

class HospitalNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hospital-notification {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $firebaseService;
    protected $notificationService;
    protected $database;
    /**
     * Execute the console command.
     */
    public function __construct(FirebaseService $firebaseService,FirebasePushNotificationService $notificationService)
     {
         parent::__construct();
 
         $this->database = $firebaseService->getDatabase();
         $this->notificationService = $notificationService;
         
     }

    public function handle()
    {
        //
        $id = $this->argument("id");
        
        $order = DoctorPatientAppointment::with([ 'user','member','doctor','doctor.user',
           'hospital.location'])
           ->where('doctor_patient_appointments.id','=',$id)->get()->first();
           
        if($order){
            $patient_name = $order->user->name;
            if($order->member_id > 0){
                $patient_name = $order->member->full_name;
            }
            $notification_id = time();
            if($order->booking_status == BOOKING_STATUS_PENDING){
                $ntype = 'pending_appointment';
                $title = "Pending Appointment";
                $description = 'Dr. '.$order->doctor->user->name.' has received a pending appointment request from '.$patient_name.' for '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot));
                
            }
            if($order->booking_status == BOOKING_STATUS_CONFIRMED){
                $ntype = 'confirmed_appointment';
                $title = "Appointment Confirmation";
                $description='Dr. '.$order->doctor->user->name.' appointment with '.$patient_name.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' has been confirmed.';
            }
            if($order->booking_status == BOOKING_STATUS_RESCHEDULED){
                $ntype = 'rescheduled_appointment';
                $title = "Rescheduled Appointment ";
                if($order->reason_reschedule){
                    $description='Dr. '.$order->doctor->user->name.' appointment with '.$patient_name.' has been rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' due to '.$order->reason_reschedule;
                    
                }else{
                    $description='Dr. '.$order->doctor->user->name.' appointment with '.$patient_name.' has been rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' ';
                }
            }

            if($order->booking_status == BOOKING_STATUS_CANCELLED){
                $ntype = 'cancelled_appointment';
                $title = "Cancelled Appointment ";
                if($order->reason_cancel){
                    $description='Dr. '.$order->doctor->user->name.' appointment with '.$patient_name.' has been cancelled due to '.$order->reason_cancel;
                }else{
                    $description='Dr. '.$order->doctor->user->name.' appointment with '.$patient_name.' has been cancelled.';
                }
                
            }

            if($order->booking_status == BOOKING_STATUS_COMPLETED){
                $ntype = 'completed_appointment';
                $title = "Completed Appointment";
                $description='We are pleased to inform you that '.$order->doctor->user->name.' appointment with '.$patient_name.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' has been completed.';
            }
            
                $notification_data["Hospital/".$order->hospital->user_id."/" . $notification_id] = [
                    "title" => $title,
                    "description" => $description,
                    "notificationType" => $ntype,
                    "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                    "order_id" => (string) $id,
                    "url" => "",
                    "imageURL" => (string)$order->user->user_img_url,
                    "read" => "0",
                    "seen" => "0",
                    "type"=>'appoitment'
                ];
                $this->database->getReference()->update($notification_data);
                //printr($notification_data);
            
            if (!empty($customer->user_device_token)) {
                //  $result = $this->notificationService->sendNotification($customer->user_device_token,
                //     [
                //         "title" => $title,
                //         "body" => $description
                //     ],
                //     [
                //         "type" => $ntype,
                //         "notificationID" => (string)$notification_id,
                //         "order_id" => (string) $id,
                //         "imageURL" => "",
                //     ]);
                // printr($result);
            }
        }
    }
}
