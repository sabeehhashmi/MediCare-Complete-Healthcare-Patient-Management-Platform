<?php
// app/Http/Controllers/clinic/ChatController.php

namespace App\Http\Controllers\clinic;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorPatientAppointment;
use App\Models\Hospital;
use App\Models\AgentUserDetail;
use App\Models\User;
use App\Services\CometChatService;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use DateTime;
use DateTimeZone;

class ChatController extends Controller
{
    protected $cometChat;
    protected $database;
    private static $syncTriggered = false;

    public function __construct(CometChatService $cometChat, FirebaseService $firebaseService)
    {
        $this->cometChat = $cometChat;
        $this->database = $firebaseService->getDatabase();
    }

    public function index()
    {
        $page_heading = "Messages";
        $module_heading = "Chat";
        
        $this->triggerSyncIfNeeded();
        
        $clinic = Hospital::where('user_id', Auth::id())->first();
        
        if ($clinic) {
            $totalDoctors = Doctor::where('hospital_id', $clinic->id)->count();
            $totalAppointments = DoctorPatientAppointment::where('hospital_id', $clinic->id)->count();
        } else {
            $totalDoctors = 0;
            $totalAppointments = 0;
        }
        
        return view('clinic.chat.index', compact('page_heading', 'module_heading', 'clinic', 'totalDoctors', 'totalAppointments'));
    }

    private function triggerSyncIfNeeded()
    {
        $clinicUser = Auth::user();
        if (!$clinicUser) return;
        
        $clinic = Hospital::where('user_id', $clinicUser->id)->first();
        if (!$clinic) return;
        
        $userIds = [$clinicUser->id];
        
        if ($clinic->agent_id) {
            $agentDetail = AgentUserDetail::find($clinic->agent_id);
            if ($agentDetail && $agentDetail->user_id) {
                $userIds[] = $agentDetail->user_id;
            }
        }
        
        $doctors = Doctor::where('hospital_id', $clinic->id)->with('user')->get();
        foreach ($doctors as $doctor) {
            if ($doctor->user_id) {
                $userIds[] = $doctor->user_id;
            }
        }
        
        // Get patients who have appointments with this clinic's doctors
        $patientIds = DoctorPatientAppointment::where('hospital_id', $clinic->id)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        $userIds = array_merge($userIds, $patientIds);
        
        // Get Admin (role 1) and Call Center (role 4)
        $adminAndCallCenter = User::whereIn('role', [1, 4])
            ->where('deleted', 0)
            ->pluck('id')
            ->toArray();
        $userIds = array_merge($userIds, $adminAndCallCenter);
        
        $unsyncedCount = User::whereIn('id', array_unique($userIds))
            ->where('cometchat_user_created', false)
            ->where('deleted', 0)
            ->count();
        
        if ($unsyncedCount > 0 && !self::$syncTriggered) {
            self::$syncTriggered = true;
            exec("php " . base_path() . "/artisan cometchat:sync-users > /dev/null 2>&1 & ");
            Log::info('Auto-sync triggered for CometChat users from clinic chat', ['unsynced_count' => $unsyncedCount]);
        }
    }

    public function init()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['status' => '0', 'message' => 'User not authenticated']);
            }
            
            $this->triggerSyncIfNeeded();
            
            $cometChatUid = $this->cometChat->getOrCreateUser($user);
            
            if (!$cometChatUid) {
                return response()->json(['status' => '0', 'message' => 'Failed to initialize chat user']);
            }
            
            $authToken = $this->cometChat->generateAuthToken($user->id);
            
            if (!$authToken) {
                return response()->json(['status' => '0', 'message' => 'Failed to generate auth token']);
            }
            
            return response()->json([
                'status' => '1',
                'data' => [
                    'uid' => $cometChatUid,
                    'auth_token' => $authToken,
                    'app_id' => config('cometchat.app_id'),
                    'region' => config('cometchat.region'),
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Clinic',
                    'user_avatar' => $user->user_img_url ?? '',
                    'user_role' => $user->role
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Clinic Chat Init Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get all users clinic can chat with (Admin, Agent, Doctors, Patients, Call Center)
     * This now includes Admin properly
     */
    public function getConversations()
    {
        try {
            $clinicUser = Auth::user();
            $clinic = Hospital::where('user_id', $clinicUser->id)->first();
            
            if (!$clinic) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            // $cacheKey = 'clinic_chat_users_' . $clinicUser->id;
            // $cachedData = Cache::get($cacheKey);
            
            // if ($cachedData !== null) {
            //     return response()->json(['status' => '1', 'data' => $cachedData]);
            // }
            
            $userIds = [];
            
            // 1. Admin (role 1) - ALWAYS include admin
            $admin = User::find(1);
            if ($admin) {
                $userIds[$admin->id] = ['type' => 'Admin', 'data' => $admin];
            }
            
            // 2. Assigned Agent (role 3)
            if ($clinic->agent_id) {
                $agentDetail = AgentUserDetail::find($clinic->agent_id);
                if ($agentDetail && $agentDetail->user) {
                    $userIds[$agentDetail->user_id] = ['type' => 'Agent', 'data' => $agentDetail->user];
                }
            }
            
            // 3. Call Center (role 4) - All call center users
            $callCenter = User::where('role', 4)
                ->where('deleted', 0)
                ->where('active', 1)
                ->get();
            foreach ($callCenter as $cc) {
                $userIds[$cc->id] = ['type' => 'Call Center', 'data' => $cc];
            }
            
            // 4. Doctors under this clinic (role 6)
            $doctors = Doctor::where('hospital_id', $clinic->id)->with('user')->get();
            foreach ($doctors as $doctor) {
                if ($doctor->user) {
                    $userIds[$doctor->user_id] = ['type' => 'Doctor', 'data' => $doctor->user];
                }
            }
            
            // 5. Patients with appointments (role 7)
            $patients = User::whereIn('users.id', function($query) use ($clinic) {
                    $query->select('doctor_patient_appointments.user_id')
                        ->from('doctor_patient_appointments')
                        ->where('doctor_patient_appointments.hospital_id', $clinic->id)
                        ->whereNotNull('doctor_patient_appointments.user_id');
                })
                ->where('users.role', 7)
                ->where('users.deleted', 0)
                ->where('users.active', 1)
                ->get();
            foreach ($patients as $patient) {
                $userIds[$patient->id] = ['type' => 'Patient', 'data' => $patient];
            }
            
            $result = [];
            foreach ($userIds as $userId => $info) {
                $userData = $info['data'];
                $result[] = [
                    'id' => $userId,
                    'uid' => 'user_' . $userId,
                    'name' => $this->getUserDisplayName($userData, $info['type']),
                    'avatar' => $userData->user_img_url ?? '',
                    'type' => $info['type'],
                ];
            }
            
           // Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Clinic Get Conversations Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => []]);
        }
    }

    private function getUserDisplayName($user, $type)
    {
        if (!$user) return 'Unknown';
        
        if ($type == 'Doctor') {
            return 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        }
        if ($type == 'Agent') {
            $agentDetails = AgentUserDetail::where('user_id', $user->id)->first();
            if ($agentDetails && $agentDetails->user) {
                $name = trim(($agentDetails->user->name ?? ''));
                if (!empty($name)) return $name;
            }
            return trim(($user->name ?? '')) ?: 'Agent';
        }
        if ($type == 'Patient') {
            return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Patient';
        }
        if ($type == 'Admin') {
            return $user->name ?? 'Admin';
        }
        if ($type == 'Call Center') {
            return $user->name ?? 'Call Center';
        }
        return $user->name ?? $type;
    }

    public function getMessages($receiverId)
    {
        return response()->json(['status' => '1', 'data' => []]);
    }

    public function sendMessage(Request $request)
    {
        return response()->json(['status' => '1']);
    }

    public function markAsRead(Request $request)
    {
        return response()->json(['status' => '1']);
    }

    public function getUnreadCount()
    {
        return response()->json(['status' => '1', 'unread_count' => 0]);
    }

    public function checkNewMessages(Request $request)
    {
        return response()->json(['status' => '1', 'has_new' => false]);
    }

    public function notifyMessage(Request $request)
    {
        try {
            $senderId = Auth::id();
            $receiverId = str_replace('user_', '', $request->receiver_id);
            $message = $request->message;
            $this->sendFirebaseNotification($receiverId, $senderId, $message);
            return response()->json(['status' => '1']);
        } catch (Exception $e) {
            return response()->json(['status' => '0']);
        }
    }

    private function sendFirebaseNotification($receiverId, $senderId, $message)
    {
        try {
            $sender = User::find($senderId);
            $receiver = User::find($receiverId);
            
            if (!$sender || !$receiver) return;
            
            $timestamp = time();
            $createdAt = gmdate("d-m-Y H:i:s", $timestamp);
            
            exec("php " . base_path() . "/artisan update:empty_firebase_keys > /dev/null 2>&1 & ");
            
            $title = "New Message from Clinic: " . ($sender->name ?? 'Clinic');
            $description = substr($message, 0, 100);
            
            $path = "";
            $url = "";
            
            if ($receiver->role == 6) {
                $path = "Doctor/" . $receiver->id . "/" . $timestamp;
                $url = url('doctor/chat');
            } elseif ($receiver->role == 3) {
                $path = "Agent/" . $receiver->id . "/" . $timestamp;
                $url = url('agent/chat');
            } elseif ($receiver->role == 7) {
                $key = $receiver->firebase_user_key ?? $receiver->id;
                $path = "Nottifications/" . $key . "/" . $timestamp;
                $url = url('patient/chat');
            } elseif ($receiver->role == 1) {
                $path = "Admin/" . $timestamp;
                $url = url('admin/chat');
            } elseif ($receiver->role == 4) {
                $path = "Callcenter/" . $timestamp;
                $url = url('callcenter/chat');
            }
            
            if (empty($path)) return;
            
            $updates = [
                $path => [
                    "title" => $title,
                    "description" => $description,
                    "notificationType" => "chat_message",
                    "createdAt" => $createdAt,
                    "order_id" => "",
                    "url" => $url,
                    "imageURL" => $sender->user_img_url ?? '',
                    "read" => "0",
                    "seen" => "0",
                    "type" => "chat"
                ]
            ];
            
            $this->database->getReference()->update($updates);
            
        } catch (Exception $e) {
            Log::error('Clinic Firebase Notification Error: ' . $e->getMessage());
        }
    }
}