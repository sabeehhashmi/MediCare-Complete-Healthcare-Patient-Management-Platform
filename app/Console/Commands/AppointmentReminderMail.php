<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;

class AppointmentReminderMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appointment-reminder-mail';

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

        $list = DoctorPatientAppointment::with([ 'user','member','doctor','doctor.user',
        'hospital.location'])
        ->whereHas('user',function($q){
            $q->where('email','!=','');
        })
        ->whereDate('booking_date','=',date('Y-m-d'))->where(['booking_status'=>BOOKING_STATUS_CONFIRMED])->get();
        
        foreach($list as $order){
            $notification_id = time();
            $patient_name = $order->user->name;
            if($order->member_id > 0){
                $patient_name = $order->member->full_name;
            }
            
                $ntype = 'appointment_reminder';
                $title = "Appointment Reminder";
                
            
            $customer = $order->user;
            $direction = 'https://www.google.com/maps/dir/?api=1&destination='.$order->hospital->location[0]->latitude.','.$order->hospital->location[0]->longitude.'&travelmode=driving,14z';
            $mailbody = view('mail.appoitment_reminder', compact('order','title','patient_name','direction'));
            send_email($customer->email,$title,$mailbody);
        }
    }
}
