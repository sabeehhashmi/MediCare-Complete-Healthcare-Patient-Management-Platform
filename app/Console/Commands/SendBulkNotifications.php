<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationList;
use App\Models\User;
use App\Services\FirebaseService;

class SendBulkNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-bulk-notifications {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send bulk notifications to users based on types and selection';

    protected $database;

    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->database = $firebaseService->getDatabase();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        exec("php " . base_path() . "/artisan update:empty_firebase_keys > /dev/null 2>&1 & ");
        $id = $this->argument('id');
        $notification = NotificationList::find($id);

        if (!$notification) {
            $this->error("Notification with ID $id not found.");
            return 1;
        }

        $notification->update(['status' => 'processing']);

        $user_types = $notification->user_types; // array of role IDs
        $user_ids = $notification->user_ids; // array of user IDs or null

        $query = User::whereIn('role', $user_types)
            ->where('active', 1)
            ->where('enable_public_notification', 1);

        if (!empty($user_ids)) {
            $query->whereIn('id', $user_ids);
        }

        $notification_time = time();
        $created_at_fmt = gmdate("d-m-Y H:i:s", $notification_time);

        $query->chunk(100, function ($users) use ($notification, $notification_time, $created_at_fmt) {
            $updates = [];
            foreach ($users as $user) {
                $path = "";
                if ($user->role == 7) { // Patient
                    if (!empty($user->firebase_user_key)) {
                        $path = "Nottifications/" . $user->firebase_user_key . "/" . $notification_time;
                    }
                } elseif ($user->role == 6) { // Doctor
                    $path = "Doctor/" . $user->id . "/" . $notification_time;
                } elseif ($user->role == 5 || $user->role == 8) { // Hospital/Clinic
                    $path = "Hospital/" . $user->id . "/" . $notification_time;
                } elseif ($user->role == 3) { // Agent
                    $path = "Agent/" . $notification_time;
                } elseif ($user->role == 4 ) { // SERVICE CENTER
                    $path = "Callcenter/" . $notification_time;
                }

                if (!empty($path)) {
                    $updates[$path] = [
                        "title" => $notification->title,
                        "description" => $notification->description,
                        "notificationType" => 'bulk_broadcast',
                        "createdAt" => $created_at_fmt,
                        "order_id" => "",
                        "url" => "",
                        "imageURL" => '',
                        "read" => "0",
                        "seen" => "0",
                    ];
                }
            }

            if (!empty($updates)) {
                $this->database->getReference()->update($updates);
            }
        });

        $notification->update(['status' => 'completed']);
        $this->info("Bulk notification sent successfully.");

        return 0;
    }
}
