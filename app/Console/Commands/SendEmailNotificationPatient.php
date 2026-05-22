<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;
class SendEmailNotificationPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-notification-patient {id}';

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
        $id = $this->argument("id");
        $order = DoctorPatientAppointment::with([ 'user','member','doctor','doctor.user',
           'hospital.location'])
           ->where('doctor_patient_appointments.id','=',$id)->get()->first();
        
        if($order){
            $patient_name = $order->user->name;
            if($order->member_id > 0){
                $patient_name = $order->member->full_name;
            }
            $customer = $order->user;
            $notification_id = time();
           
            if($order->booking_status == BOOKING_STATUS_PENDING || $order->booking_status=='pending'){
               
                $ntype = 'pending_appointment';
                $title = "Appointment Request Received";
                $description = 'Your appointment request  is Awaiting Confirmation . You will receive a confirmation call shortly.';
                if($customer->email != ''){
                    $mailbody = view('mail.appoitment_pending', compact('order','title','patient_name'));
                    $ret = send_email($customer->email,$title,$mailbody);
                }

                // =========================
    // 2. DOCTOR EMAIL
    // =========================
    $doctorUser = optional(optional($order->doctor)->user);

    $description = 'New Appointment Request Received and Awaiting For Confirmation .';

    if (!empty($doctorUser->email)) {

        $mailbody = view('mail.appoitment_pending', [
            'order' => $order,
            'title' => $title,
            'patient_name' => $doctorUser->name
        ])->render();

        send_email($doctorUser->email, $title, $mailbody);
    }

    // =========================
    // 3. HOSPITAL ADMIN EMAIL
    // =========================
    $hospitalUser = optional(optional(optional($order->doctor)->hospital)->user);

    if (!empty($hospitalUser->email)) {

        $mailbody = view('mail.appoitment_pending', [
            'order' => $order,
            'title' => $title,
            'patient_name' => $hospitalUser->name
        ])->render();

        send_email($hospitalUser->email, $title, $mailbody);
    }
    
            }
            if($order->booking_status == BOOKING_STATUS_CONFIRMED){
                $direction = 'https://www.google.com/maps/dir/?api=1&destination='.$order->hospital->location[0]->latitude.','.$order->hospital->location[0]->longitude.'&travelmode=driving,14z';
                $ntype = 'confirmed_appointment';
                $title = "Appointment Confirmed";
                $description='Your appointment is confirmed.';
                if($customer->email != ''){
                    $mailbody = view('mail.appoitment_confirmerd', compact('order','title','patient_name','direction'));
                    $ret = send_email($customer->email,$title,$mailbody);
                    printr($ret);
                }
            }
            if($order->booking_status == BOOKING_STATUS_RESCHEDULED){
                $direction = 'https://www.google.com/maps/dir/?api=1&destination='.$order->hospital->location[0]->latitude.','.$order->hospital->location[0]->longitude.'&travelmode=driving,14z';
                $ntype = 'rescheduled_appointment';
                $title = "Appointment Rescheduled";
                if($customer->email != ''){
                    $mailbody = view('mail.appoitment_rescheduled', compact('order','title','patient_name','direction'));
                    $ret = send_email($customer->email,$title,$mailbody);
                    printr($ret);
                }
            }

          if ($order->booking_status == BOOKING_STATUS_CANCELLED) {

    $ntype = 'cancelled_appointment';
    $title = "Appointment Cancelled";

    // =========================
    // 1. CUSTOMER EMAIL
    // =========================
    if (!empty($customer->email)) {

        $mailbody = view('mail.appoitment_cancelled', [
            'order' => $order,
            'title' => $title,
            'patient_name' => $patient_name ?? null
        ])->render();

        send_email($customer->email, $title, $mailbody);
    }

    // =========================
    // 2. DOCTOR EMAIL
    // =========================
    $doctorUser = optional(optional($order->doctor)->user);

    if (!empty($doctorUser->email)) {

        $mailbody = view('mail.appoitment_cancelled', [
            'order' => $order,
            'title' => $title,
            'patient_name' => $doctorUser->name
        ])->render();

        send_email($doctorUser->email, $title, $mailbody);
    }

    // =========================
    // 3. HOSPITAL ADMIN EMAIL
    // =========================
    $hospitalUser = optional(optional(optional($order->doctor)->hospital)->user);

    if (!empty($hospitalUser->email)) {

        $mailbody = view('mail.appoitment_cancelled', [
            'order' => $order,
            'title' => $title,
            'patient_name' => $hospitalUser->name
        ])->render();

        send_email($hospitalUser->email, $title, $mailbody);
    }
}

            if($order->booking_status == BOOKING_STATUS_COMPLETED){
                $ntype = 'completed_appointment';
                $title = "Appointment Completed";
                if($customer->email != ''){
                    $mailbody = view('mail.appoitment_completed', compact('order','title','patient_name'));
                    $ret = send_email($customer->email,$title,$mailbody);
                    printr($ret);
                }
            }
            
            
        }
    }
}
