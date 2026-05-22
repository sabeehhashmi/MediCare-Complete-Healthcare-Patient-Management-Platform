<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;

class SendNotiticationsPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notitications-patient {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $firebaseService;
    protected $notificationService;
    protected $database;

    public function __construct(FirebaseService $firebaseService,FirebasePushNotificationService $notificationService)
     {
         parent::__construct();
 
         $this->database = $firebaseService->getDatabase();
         $this->notificationService = $notificationService;
         
     }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $id = $this->argument("id");
        exec("php " . base_path() . "/artisan app:send-sms-notification-patient " . $id . " > /dev/null 2>&1 & ");
        exec("php " . base_path() . "/artisan app:send-email-notification-patient " . $id . " > /dev/null 2>&1 & ");
        exec("php " . base_path() . "/artisan app:admin-notifications " . $id . " > /dev/null 2>&1 & ");
        exec("php " . base_path() . "/artisan app:hospital-notification " . $id . " > /dev/null 2>&1 & ");
        exec("php " . base_path() . "/artisan app:doctor-notification " . $id . " > /dev/null 2>&1 & ");
        exec("php " . base_path() . "/artisan app:agent-notifications " . $id . " > /dev/null 2>&1 & ");
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
                $description = 'Hi '.$patient_name.', Your appointment request  with Dr. '.$order->doctor->user->name.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' at '.$order->hospital->name_en.' has been received. You will receive a confirmation call shortly.';
            }
            if($order->booking_status == BOOKING_STATUS_CONFIRMED){
                $ntype = 'confirmed_appointment';
                $title = "Appointment Confirmation";
                $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' is confirmed.';
                if($order->booking_status == BOOKING_STATUS_CONFIRMED && strtolower($order->payment_status) == 'paid')
                {
                 exec("php " . base_path() . "/artisan app:invoice-generated-notification " . $id ." > /dev/null 2>&1 & ");
                }
            }
            if($order->booking_status == BOOKING_STATUS_RESCHEDULED){
                $ntype = 'rescheduled_appointment';
                $title = "Rescheduled Appointment ";
                if($order->reason_reschedule){
                    $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been successfully rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' due to '.$order->reason_reschedule;
                }else{
                    $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been successfully rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' ';
                }
            }

            if($order->booking_status == BOOKING_STATUS_CANCELLED){
                $ntype = 'cancelled_appointment';
                $title = "Cancelled Appointment ";
                if($order->reason_cancel){
                    $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been cancelled due to '.$order->reason_cancel;
                }else{
                    $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been cancelled.';
                }
                
            }

            if($order->booking_status == BOOKING_STATUS_COMPLETED){
                $ntype = 'completed_appointment';
                $title = "Completed Appointment";
                $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' has been completed. You can access your appointment summary and any follow-up instructions in the appointments tab';
            }
            $customer = $order->user;

            if (!empty($customer->firebase_user_key)) {
                $notification_data["Nottifications/" . $customer->firebase_user_key . "/" . $notification_id] = [
                    "title" => $title,
                    "description" => $description,
                    "notificationType" => $ntype,
                    "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                    "order_id" => (string) $id,
                    "url" => "",
                    "imageURL" => '',
                    "read" => "0",
                    "seen" => "0",
                ];
                $this->database->getReference()->update($notification_data);
            }
            if (!empty($customer->user_device_token)) {
                 $result = $this->notificationService->sendNotification($customer->user_device_token,
                    [
                        "title" => $title,
                        "body" => $description
                    ],
                    [
                        "type" => $ntype,
                        "notificationID" => (string)$notification_id,
                        "order_id" => (string) $id,
                        "imageURL" => "",
                    ]);
                printr($result);
            }
        }
    }
}
