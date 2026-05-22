<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\Order;

class SendMedicineOrderPaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-medicine-order-payment-notification {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to user when medicine order payment is successful';

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
        $order = Order::with(['user'])
            ->where('id', $id)
            ->first();

        if ($order) {
            $user = $order->user;
            if (!$user) {
                return;
            }

            // Check if user has disabled payment notifications
            if ($user->enable_payment_notification != 1) {
                return;
            }

            $notification_id = time();
            $ntype = 'order_payment_success';
            $title = "Payment Successful";
            $description = 'Hi ' . $user->name . ', Your payment for order ' . $order->order_number . ' was successful. Thank you for your purchase!';

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
