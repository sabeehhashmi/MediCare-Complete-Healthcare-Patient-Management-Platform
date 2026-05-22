<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;
use App\Models\User;
use Carbon\Carbon;
use App\Services\FirebaseService;

class AutoCancelUnpaidAppointments extends Command
{
    protected $signature = 'appointments:auto-cancel-unpaid';

    protected $description = 'Auto cancel unpaid appointments after 1 hour of appointment time (Dubai time)';

    protected $database;

    private string $timezone = 'Asia/Dubai';

    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->database = $firebaseService->getDatabase();
    }

  public function handle()
{
    
    
    $timezone = 'Asia/Dubai';

    $appointments = DoctorPatientAppointment::with([
        'user',
        'doctor.user',
        'hospital'
    ])
    ->where('payment_status', DoctorPatientAppointment::PAYMENT_STATUS_PENDING)
    ->where('payment_reminder_sent', 1)
    ->orderby('id','desc')
    ->limit(1)
    ->get();

    foreach ($appointments as $appointment) {

        try {
            
           

            if (!$appointment->booking_date || !$appointment->booking_time_slot) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Build appointment datetime (Dubai time safe)
            |--------------------------------------------------------------------------
            */
            $appointmentTime = Carbon::parse(
                $appointment->booking_date . ' ' . $appointment->booking_time_slot,
                $timezone
            );

            $now = Carbon::now($timezone);

            /*
            |--------------------------------------------------------------------------
            | CORE RULE:
            | Cancel if appointment is within 60 minutes OR already passed
            |--------------------------------------------------------------------------
            */

            $minutesLeft = $now->diffInMinutes($appointmentTime, false);
           
            // More than 60 minutes left → DO NOTHING
            if ($minutesLeft > 5) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Already cancelled check
            |--------------------------------------------------------------------------
            */

             if ($appointment->booking_status === BOOKING_STATUS_CANCELLED) {
                 continue;
             }
            

            /*
            |--------------------------------------------------------------------------
            | CANCEL APPOINTMENT
            |--------------------------------------------------------------------------
            */

            $appointment->booking_status = BOOKING_STATUS_CANCELLED;
            $appointment->reason_cancel =
                'Automatically cancelled due to non-payment (within 1 hour of appointment time)';
           
            $appointment->updated_at = now();
            $appointment->save();
             
             
            
            
            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            $this->addAppointmentHistory(
                $appointment->id,
                BOOKING_STATUS_CANCELLED
            );

            /*
            |--------------------------------------------------------------------------
            | PUSH NOTIFICATION
            |--------------------------------------------------------------------------
            */

            $this->sendPushNotification($appointment);

            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            $this->sendCancellationEmail($appointment);

            $this->info("Cancelled appointment ID: {$appointment->id}");

        } catch (\Exception $e) {
            
           
            dd($e->getMessage());
            \Log::error('Auto Cancel Error', [
                'appointment_id' => $appointment->id ?? null,
                'message' => $e->getMessage()
            ]);
        }
    }

    return Command::SUCCESS;
}
    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    protected function addAppointmentHistory($appointmentId, $status)
    {
        
        
        \App\Models\DoctorAppointmentsStatus::create([
            'appointment_id' => $appointmentId,
            'changed_by' => 1,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
            'changed_at' => now()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUSH NOTIFICATION
    |--------------------------------------------------------------------------
    */

    protected function sendPushNotification($appointment)
    {
        try {

            if (!$appointment->user?->firebase_user_key) {
                return;
            }

            $notificationId = time();
            $firebaseUserKey = $appointment->user->firebase_user_key;

            $data["Nottifications/".$firebaseUserKey."/".$notificationId] = [
                "title" => "Appointment Cancelled",
                "description" => "Your appointment has been automatically cancelled due to non-payment.",
                "notificationType" => "appointment_cancelled",
                "createdAt" => gmdate("d-m-Y H:i:s"),
                "booking_id" => $appointment->booking_id,
                "order_id" => (string)$appointment->id,
                "read" => "0",
                "seen" => "0",
                "type" => "appointment"
            ];

            $this->database->getReference()->update($data);

        } catch (\Exception $e) {
            \Log::error('Push notification failed: '.$e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL
    |--------------------------------------------------------------------------
    */

    protected function sendCancellationEmail($appointment)
    {
        try {

            $user = $appointment->user;

            if (!$user || !$user->email) {
                return;
            }

            $patient_name = $user->name ?? 'Patient';
            $order = $appointment;

            $mailbody = view(
                'mail.auto_cancel_appointment',
                compact('patient_name', 'order')
            )->render();

            send_email(
                $user->email,
                'Appointment Cancelled Due to Non-Payment (' . $appointment->booking_id . ')',
                $mailbody
            );

        } catch (\Exception $e) {
            \Log::error('Cancellation email failed: '.$e->getMessage());
        }
    }
}