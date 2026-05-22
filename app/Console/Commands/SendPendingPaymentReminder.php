<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoctorPatientAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendPendingPaymentReminder extends Command
{
    protected $signature = 'appointments:pending-payment-reminder';
    protected $description = 'Send pending payment reminders for offline appointments';

    protected $database;

    public function __construct(\App\Services\FirebaseService $firebaseService)
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
        ->orderBy('id', 'desc')
        ->get();

        foreach ($appointments as $appointment) {

            try {

                // skip if no user
                if (!$appointment->user) {
                    continue;
                }

                // =========================
                // TIME CHECK (Dubai)
                // =========================
                $appointmentDateTime = Carbon::parse(
                    $appointment->booking_date . ' ' . $appointment->booking_time_slot,
                    $timezone
                );

                $now = Carbon::now($timezone);

                $minutes = $now->diffInMinutes($appointmentDateTime, false);

                // only next 3 hours window
                if ($minutes > 15 || $minutes < 0) {
                    continue;
                }

                // skip if already sent
                if ($appointment->payment_reminder_sent == 1) {
                    continue;
                }

                // =========================
                // FIREBASE NOTIFICATION
                // =========================
                try {

                    if (!empty($appointment->user->firebase_user_key)) {

                        $notificationId = time();
                        $firebaseUserId = $appointment->user->firebase_user_key;

                        $data = [
                            "Nottifications/" . $firebaseUserId . "/" . $notificationId => [
                                "title" => "Pending Payment Reminder",
                                "description" => "Your appointment payment is still pending. Please complete payment before appointment time.",
                                "notificationType" => "payment_pending",
                                "createdAt" => gmdate("d-m-Y H:i:s", $notificationId),
                                "appointment_id" => (string) $appointment->id,
                                "booking_id" => (string) $appointment->booking_id,
                                "order_id" => (string) $appointment->id,
                                "read" => "0",
                                "seen" => "0",
                                "type" => "payment"
                            ]
                        ];

                        $this->database->getReference()->update($data);
                    }

                } catch (\Exception $e) {
                    Log::error('Firebase reminder failed', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage()
                    ]);
                }

                // =========================
                // EMAIL
                // =========================
                try {

                    if (!empty($appointment->user->email)) {

                        $patient_name = $appointment->user->name ?? 'Patient';
                        $order = $appointment;

                        // safe URL (CLI safe)
                        $payment_url = url('/payment?appointment_id=' . $appointment->id);

                        $mailbody = view(
                            'mail.pending-payment-reminder',
                            compact('order', 'patient_name', 'payment_url')
                        )->render();

                        send_email(
                            $appointment->user->email,
                            "Pending Appointment Payment Reminder",
                            $mailbody
                        );
                    }

                } catch (\Exception $e) {
                    Log::error('Email reminder failed', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage()
                    ]);
                }

                // =========================
                // MARK SENT
                // =========================
                $appointment->payment_reminder_sent = 1;
                $appointment->save();

                $this->info("Reminder sent for appointment ID: " . $appointment->id);

            } catch (\Exception $e) {

                Log::error('Pending Payment Reminder MAIN ERROR', [
                    'appointment_id' => $appointment->id ?? null,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return Command::SUCCESS;
    }
}