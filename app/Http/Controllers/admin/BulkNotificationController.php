<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NotificationList;
use Illuminate\Support\Facades\DB;

class BulkNotificationController extends Controller
{
    public function index()
    {
        $page_heading = 'Bulk Notification History';
        $notifications = NotificationList::orderBy('id', 'desc')->paginate(10);
        
        $roles = [
            7 => 'Patients',
            5 => 'Hospital/Clinic',
            3 => 'Agents',
            4 => 'SERVICE CENTER',
            6 => 'Doctors',
        ];
        
        return view('admin.bulk_notifications.index', compact('page_heading', 'notifications', 'roles'));
    }

    public function create()
    {
        $page_heading = 'Send Bulk Notification';
        
        // Defining roles based on config/global.php
        $roles = [
            7 => 'Patients',
            5 => 'Hospital/Clinic',
            3 => 'Agents',
            4 => 'SERVICE CENTER',
            6 => 'Doctors',
        ];
        
        return view('admin.bulk_notifications.create', compact('page_heading', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $user_types = [$request->user_type];
        if ($request->user_type == 5) {
            $user_types[] = 8;
        }
        $notification = NotificationList::create([
            'user_types' => $user_types, // Stored as array for compatibility with SendBulkNotifications command
            'user_ids' => $request->user_ids, 
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Trigger the background command via exec
        $artisanPath = base_path() . "/artisan";
        $command = "php " . $artisanPath . " app:send-bulk-notifications " . $notification->id . " > /dev/null 2>&1 & ";
        exec($command);

        return response()->json([
            'status' => 1,
            'message' => 'Notification added to queue and will be sent shortly.',
            'oData' => ['redirect' => route('admin.bulk_notifications.index')]
        ]);
    }

    public function getUsersByType(Request $request)
    {
        $type = $request->type;
        $term = $request->q;
        $page = $request->page ?? 1;

        if (empty($type)) {
            return response()->json(['results' => [], 'pagination' => ['more' => false]]);
        }

        $roles = [$type];
        if ($type == 5) {
            $roles[] = 8;
        }
        $usersQuery = User::whereIn('role', $roles)
            ->where('active', 1);

        if (!empty($term)) {
            $usersQuery->where(function($q) use ($term) {
                $q->where('name', 'LIKE', "%$term%")
                  ->orWhere('email', 'LIKE', "%$term%")
                  ->orWhere('patient_id', 'LIKE', "%$term%");
            });
        }

        $users = $usersQuery->paginate(15);

        $results = [];
        foreach ($users->items() as $user) {
            $results[] = [
                'id' => $user->id,
                'text' => $user->name . " (" . ($user->patient_id ?? $user->email) . ")"
            ];
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $users->hasMorePages()
            ]
        ]);
    }
}
