<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;

class SendOrderPaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-order-payment-notification {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to user when appointment payment is successful';

    protected $firebaseService;
    protected $notificationService;
    protected $database;

    public function __construct(FirebaseService $firebaseService, FirebasePushNotificationService $notificationService)
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
        $id = $this->argument("id");
        
        $appointment = \App\Models\DoctorPatientAppointment::with(['user', 'member', 'doctor.user'])
            ->where('id', $id)
            ->first();

        if ($appointment) {
            $user = $appointment->user;
            if (!$user) {
                return;
            }

            // Check if user has disabled payment notifications
            if ($user->enable_payment_notification != 1) {
                return;
            }

            $patient_name = $user->name;
            if ($appointment->member_id > 0 && $appointment->member) {
                $patient_name = $appointment->member->full_name;
            }

            $notification_id = time();
            $ntype = 'appointment_payment_success';
            $title = "Payment Successful";
            $description = 'Hi ' . $patient_name . ', Your payment for the appointment with Dr. ' . ($appointment->doctor->user->name ?? 'Doctor') . ' on ' . date('d/m/Y', strtotime($appointment->booking_date)) . ' was successful. Your appointment is now confirmed.';

            // Update Firebase Realtime Database for in-app notifications
            if (!empty($user->firebase_user_key)) {
                $notification_data["Nottifications/" . $user->firebase_user_key . "/" . $notification_id] = [
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

            // Send Push Notification via FCM
            if (!empty($user->user_device_token)) {
                $this->notificationService->sendNotification($user->user_device_token,
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
            }
        }
    }
}
