<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;

class SendLabResultNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-lab-result-notification {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to patient when lab results are uploaded';

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
        $appointment = DoctorPatientAppointment::with(['user', 'member', 'doctor', 'doctor.user', 'hospital'])
            ->where('id', $id)
            ->first();

        if ($appointment) {
            $customer = $appointment->user;

            // Check if user has disabled lab result notifications
            if ($customer && $customer->enable_lab_result_notification != 1) {
                return;
            }

            $patient_name = $customer->name;
            if ($appointment->member_id > 0 && $appointment->member) {
                $patient_name = $appointment->member->full_name;
            }

            $notification_id = time();
            $ntype = 'lab_result_uploaded';
            $title = "Medical Reports Uploaded";
            $description = 'Hi ' . $patient_name . ', Your medical reports (Lab/X-ray) for the appointment with Dr. ' . $appointment->doctor->user->name . ' at ' . $appointment->hospital->name_en . ' on ' . date('d/m/Y', strtotime($appointment->booking_date)) . ' have been uploaded. You can view them in the appointments tab.';

            $customer = $appointment->user;

            // Update Firebase Realtime Database for in-app notifications
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

            // Send Push Notification via FCM
            if (!empty($customer->user_device_token)) {
                $this->notificationService->sendNotification($customer->user_device_token,
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
