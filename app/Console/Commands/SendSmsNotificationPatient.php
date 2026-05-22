<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;
use App\Services\FirebaseService;
use App\Services\FirebaseDynamicLinkService;

class SendSmsNotificationPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sms-notification-patient {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $firebaseService;
     protected $dynamicLinkService;

    public function __construct(FirebaseService $firebaseService,FirebaseDynamicLinkService $dynamicLinkService)
     {
         parent::__construct();
 
         $this->firebaseService = $firebaseService;
         $this->dynamicLinkService = $dynamicLinkService;
     }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // Generate a short link using Firebase
        $my_account_link = $this->dynamicLinkService->createShortLink("https://Mednero.com/website/patient-appointment");
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
                $description = 'Hi '.$patient_name.', Your appointment request  with Dr. '.$order->doctor->user->name.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' at '.$order->hospital->name_en.' is awaiting confirmation. You will receive a confirmation call shortly. ';
            }
            if($order->booking_status == BOOKING_STATUS_CONFIRMED){
                $ntype = 'confirmed_appointment';
                $title = "Appointment Confirmation";
                
                $direction_link = $this->dynamicLinkService->createShortLink("https://www.google.com/maps?q=".$order->hospital->location[0]->latitude.",".$order->hospital->location[0]->longitude);
                $description='Hi '.$patient_name.', Your appointment '.$order->booking_id.' with Dr. '.$order->doctor->user->name.' at'.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' is confirmed.To reschedule or manage your appointment, please click here '.$my_account_link.'. For directions, click here '.$direction_link.'  See you soon!';
                
            }
            if($order->booking_status == BOOKING_STATUS_RESCHEDULED){
                $ntype = 'rescheduled_appointment';
                $title = "Rescheduled Appointment ";
                $direction_link = $this->dynamicLinkService->createShortLink("https://www.google.com/maps?q=".$order->hospital->location[0]->latitude.",".$order->hospital->location[0]->longitude);
                if($order->reason_reschedule){
                    $description='Hi '.$patient_name.' , Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been successfully rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' due to '.$order->reason_reschedule.'.To Book or Manage other Appointments Click here '.$my_account_link.'  For directions, click here '.$direction_link;
                }else{
                    $description='Hi '.$patient_name.' , Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' has been successfully rescheduled to '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).'.To Book or Manage other Appointments Click here '.$my_account_link.'  For directions, click here '.$direction_link;
                }
            }

            if($order->booking_status == BOOKING_STATUS_CANCELLED){
                $ntype = 'cancelled_appointment';
                $title = "Cancelled Appointment ";
                if($order->reason_cancel){
                    $description = 'Hi '.$patient_name.', Your appointment with Dr '.$order->doctor->user->name.' at '.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' is cancelled due to '.$order->reason_cancel.'. You can conveniently book your next appointment online at Mednero App https://Mednero.com';
                }else{
                    $description = 'Hi '.$patient_name.', Your appointment with Dr '.$order->doctor->user->name.' at '.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' is cancelled. You can conveniently book your next appointment online at Mednero App https://Mednero.com';
                }
                
            }

            if($order->booking_status == BOOKING_STATUS_COMPLETED){ 
                $ntype = 'completed_appointment';
                $title = "Completed Appointment";
                $description = 'Hi '.$patient_name.', Your appointment with Dr. '.$order->doctor->user->name.' at '.$order->hospital->name_en.' on '.date('d/m/Y',strtotime($order->booking_date)).', at '.date('h:i a',strtotime($order->booking_time_slot)).' has been completed. You can access your appointment summary and any follow-upinstructions in the appointments tab within Mednero App';
            }
            $customer = $order->user;

            if($customer->dial_code != '' && $customer->phone != ''){
                $ret= send_normal_SMS($description,$customer->dial_code.$customer->phone);
                printr($ret);
            }
        }
    }
}
