<?php
// app/Console/Commands/AdminUrgentNotification.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Models\DoctorPatientAppointment;

class AdminUrgentNotification extends Command
{
    protected $signature = 'app:admin-urgent-notification {id}';
    protected $description = 'Send admin notification for urgent appointment';

    protected $database;

    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->database = $firebaseService->getDatabase();
    }

    public function handle()
    {
        $id = $this->argument("id");
        
        $appointment = DoctorPatientAppointment::with(['user', 'member', 'doctor', 'doctor.user', 'hospital'])
            ->where('id', $id)->first();
           
        if ($appointment && $appointment->is_urgent) {
            $patient_name = $appointment->user->name;
            if ($appointment->member_id > 0) {
                $patient_name = $appointment->member->full_name;
            }
            
            $notification_id = time();
            $title = "🚨 URGENT APPOINTMENT";
            $description = 'Urgent: ' . $patient_name . ' with Dr. ' . $appointment->doctor->user->name . ' on ' . date('d/m/Y', strtotime($appointment->booking_date)) . ' at ' . $appointment->booking_time_slot . ' | Fee: ' . $appointment->formatted_consultation_fee;
            
            // Send to Admin
            $notification_data["Admin/" . $notification_id] = [
                "title" => $title,
                "description" => $description,
                "notificationType" => "urgent_appointment",
                "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                "appointment_id" => (string) $id,
                "booking_id" => $appointment->booking_id,
                "consultation_fee" => (string) $appointment->consultation_fee,
                "order_id" => (string) $id,
                "url" => route('admin.appointments.urgent'),
                "imageURL" => (string)$appointment->user->user_img_url,
                "read" => "0",
                "seen" => "0",
                "type" => 'urgent_appointment'
            ];
            $this->database->getReference()->update($notification_data);
            
            $this->info("Urgent notification sent to Admin for appointment: " . $appointment->booking_id);
        }
    }
}