<?php
// app/Console/Commands/SendPaymentEmail.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;

class SendPaymentEmail extends Command
{
    protected $signature = 'app:send-payment-email {appointment_id} {payment_url}';
    protected $description = 'Send payment email to patient';

    public function handle()
    {
        $appointment_id = $this->argument("appointment_id");
        $payment_url = base64_decode($this->argument("payment_url"));
        
        $appointment = DoctorPatientAppointment::with(['user', 'doctor.user'])->find($appointment_id);
        
        if ($appointment && $appointment->user) {
            $user = $appointment->user;
            $type = 'payment';
            
            $mailbody = view('mail.payment_mail', compact('appointment', 'payment_url', 'user', 'type'));
            send_email($appointment->user->email, "Appointment Payment Required - Mednero", $mailbody);
            
            if ($appointment->doctor && $appointment->doctor->user) {
                $doctorBody = view('mail.doctor_appointment_notification', compact('appointment'));
                send_email($appointment->doctor->user->email, "New Appointment Assigned - Mednero", $doctorBody);
            }
            
            if ($appointment->hospital && $appointment->hospital->user) {
                $hospitalBody = view('mail.hospital_appointment_notification', compact('appointment'));
                send_email($appointment->hospital->user->email, "New Appointment at Your Facility - Mednero", $hospitalBody);
            }
            
            // Update sent timestamp
            $appointment->payment_email_sent_at = now();
            $appointment->save();
            
            $this->info("Payment email sent to: " . $appointment->user->email);
        }
    }
}