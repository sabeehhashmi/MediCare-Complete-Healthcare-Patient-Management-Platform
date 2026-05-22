<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Services\FirebasePushNotificationService;
use App\Models\DoctorPatientAppointment;

class InvoiceGeneratedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:invoice-generated-notification {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice generated notification to patient';

    protected $database;
    protected $notificationService;

    public function __construct(
        FirebaseService $firebaseService,
        FirebasePushNotificationService $notificationService
    ) {
        parent::__construct();

        $this->database = $firebaseService->getDatabase();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        $order = DoctorPatientAppointment::with([
            'user',
            'member',
            'doctor',
            'doctor.user',
            'hospital'
        ])->find($id);

        if (!$order) {
            $this->error('Appointment not found.');
            return;
        }

        // Only send if confirmed + paid
        if (
            $order->booking_status != BOOKING_STATUS_CONFIRMED ||
            strtolower($order->payment_status) != 'paid'
        ) {
            $this->error('Appointment is not confirmed or payment not paid.');
            return;
        }

        $customer = $order->user;

        if (!$customer) {
            $this->error('Customer not found.');
            return;
        }

        $patient_name = $customer->name;

        if ($order->member_id > 0 && $order->member) {
            $patient_name = $order->member->full_name;
        }

        $notification_id = time();

        $title = "Invoice Generated";

        $description =
            'Hi '.$patient_name.',
            Your payment has been received successfully for your appointment with Dr. '
            .$order->doctor->user->name.
            ' at '.$order->hospital->name_en.
            '. Your invoice has been generated successfully.';

        $ntype = 'invoice_generated';

        // Redirect URL
        $redirect_url = route('front.invoices.index');

        /**
         * Firebase Database Notification
         */
        if (!empty($customer->firebase_user_key)) {

            $notification_data[
                "Nottifications/" .
                $customer->firebase_user_key .
                "/" .
                $notification_id
            ] = [
                "title" => $title,
                "description" => $description,
                "notificationType" => $ntype,
                "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                "order_id" => (string)$id,
                "url" => $redirect_url,
                "imageURL" => "",
                "read" => "0",
                "seen" => "0",
            ];

            $this->database->getReference()->update($notification_data);
        }

        /**
         * Push Notification
         */
        if (!empty($customer->user_device_token)) {

            $result = $this->notificationService->sendNotification(
                $customer->user_device_token,
                [
                    "title" => $title,
                    "body" => $description
                ],
                [
                    "type" => $ntype,
                    "notificationID" => (string)$notification_id,
                    "order_id" => (string)$id,
                    "url" => $redirect_url,
                    "imageURL" => "",
                ]
            );

            print_r($result);
        }

        $this->info('Invoice notification sent successfully.');
    }
}