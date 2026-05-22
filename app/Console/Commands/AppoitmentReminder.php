<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;
use Carbon\Carbon;
use Exception;

class AppoitmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appoitment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders 30 minutes before the slot time (Dubai Time)';

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
        exec("php " . base_path() . "/artisan update:empty_firebase_keys > /dev/null 2>&1 & ");
        
        // Get current time in Dubai
        $nowInDubai = Carbon::now('Asia/Dubai');
        $todayStr = $nowInDubai->format('Y-m-d');
        
        // We look for appointments 30 minutes from now
        $targetSlotTime = $nowInDubai->copy()->addMinutes(30)->format('H:i');

        $this->info("Checking reminders for $todayStr at slot $targetSlotTime (Dubai Time)");

        $list = DoctorPatientAppointment::with(['user', 'member', 'doctor', 'doctor.user', 'hospital'])
            ->where('booking_date', $todayStr)
            ->where('booking_time_slot', $targetSlotTime)
            ->where('booking_status', BOOKING_STATUS_CONFIRMED)
            ->whereNull('reminder_30m_sent_at')
            ->get();

        if ($list->isEmpty()) {
            $this->info("No appointments found for this slot.");
            return;
        }

        foreach ($list as $order) {
            try {
                $notification_id = time();
                $patient_name = $order->user->name;
                if ($order->member_id > 0 && $order->member) {
                    $patient_name = $order->member->full_name;
                }

                $slotTimeFormatted = date('h:i A', strtotime($order->booking_time_slot));
                $hospitalName = $order->hospital->name_en ?? 'the hospital';
                $doctorName = $order->doctor->user->name ?? 'Doctor';

                $title = "Appointment Reminder";
                $patientDescription = "Hi $patient_name, this is a reminder of your upcoming appointment with Dr. $doctorName at $hospitalName today at $slotTimeFormatted.";
                $doctorDescription = "Reminder: Your appointment with $patient_name is scheduled for $slotTimeFormatted today.";

                // 1. Notify Patient
                $this->notifyPatient($order, $title, $patientDescription, $notification_id, $patient_name);

                // 2. Notify Doctor
                $this->notifyDoctor($order, $title, $doctorDescription, $notification_id);

                // 3. Mark as sent
                $order->update(['reminder_30m_sent_at' => Carbon::now()]);

                $this->info("Reminder sent for Appointment ID: {$order->id}");
            } catch (Exception $e) {
                $this->error("Failed to send reminder for Appointment ID: {$order->id}. Error: " . $e->getMessage());
            }
        }
    }

    protected function notifyPatient($order, $title, $description, $notification_id, $patient_name)
    {
        $customer = $order->user;
        if (!$customer || $customer->enable_reminder_notification != 1) return;

        // Firebase Realtime DB
        if (!empty($customer->firebase_user_key)) {
            $notification_data["Nottifications/" . $customer->firebase_user_key . "/" . $notification_id] = [
                "title" => $title,
                "description" => $description,
                "notificationType" => 'appointment_reminder',
                "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                "order_id" => (string) $order->id,
                "url" => "",
                "imageURL" => '',
                "read" => "0",
                "seen" => "0",
            ];
            $this->database->getReference()->update($notification_data);
        }

        // Push Notification
        // if (!empty($customer->user_device_token)) {
        //     $this->notificationService->sendNotification($customer->user_device_token, [
        //         "title" => $title,
        //         "body" => $description
        //     ], [
        //         "type" => 'appointment_reminder',
        //         "notificationID" => (string)$notification_id,
        //         "order_id" => (string) $order->id,
        //         "imageURL" => "",
        //     ]);
        // }

        // SMS Notification
        // try {
        //     $smsContent = 'Hi '.$patient_name.', This is to remind you of your upcoming appointment with Dr. '.($order->doctor->user->name ?? '').' at '.($order->hospital->name_en ?? '').' scheduled for today at '.$date('h:i a',strtotime($order->booking_time_slot)).'. To manage your appointment, Click here '.url("/appointments");
        //     if ($customer->dial_code != '' && $customer->phone != '') {
        //         send_normal_SMS($smsContent, $customer->dial_code . $customer->phone);
        //     }
        // } catch (Exception $e) {
        //     // Log or ignore SMS errors to not block other notifications
        // }
    }

    protected function notifyDoctor($order, $title, $description, $notification_id)
    {
        $doctorUser = $order->doctor->user ?? null;
        if (!$doctorUser || $doctorUser->enable_reminder_notification != 1) return;

        // Firebase Realtime DB
        $notification_data["Doctor/" . $doctorUser->id . "/" . $notification_id] = [
            "title" => $title,
            "description" => $description,
            "notificationType" => 'appointment_reminder',
            "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
            "order_id" => (string) $order->id,
            "url" => "",
            "imageURL" => (string)$order->user->user_img_url,
            "read" => "0",
            "seen" => "0",
            "type" => 'appoitment'
        ];
        $this->database->getReference()->update($notification_data);

        // Doctor Push Notification
        if (!empty($doctorUser->user_device_token)) {
            $this->notificationService->sendNotification($doctorUser->user_device_token, [
                "title" => $title,
                "body" => $description
            ], [
                "type" => 'appointment_reminder',
                "notificationID" => (string)$notification_id,
                "order_id" => (string) $order->id,
                "imageURL" => "",
            ]);
        }
    }
}
