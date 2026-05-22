<?php
// app/Http/Controllers/hospital/ChatController.php

namespace App\Http\Controllers\hospital;

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
        
        $hospital = Hospital::where('user_id', Auth::id())->first();
        
        if ($hospital) {
            $totalDoctors = Doctor::where('hospital_id', $hospital->id)->count();
            $totalAppointments = DoctorPatientAppointment::where('hospital_id', $hospital->id)->count();
            $totalDepartments = $hospital->departments()->count();
        } else {
            $totalDoctors = 0;
            $totalAppointments = 0;
            $totalDepartments = 0;
        }
        
        return view('hospital.chat.index', compact('page_heading', 'module_heading', 'hospital', 'totalDoctors', 'totalAppointments', 'totalDepartments'));
    }

    private function triggerSyncIfNeeded()
    {
        $hospitalUser = Auth::user();
        if (!$hospitalUser) return;
        
        $hospital = Hospital::where('user_id', $hospitalUser->id)->first();
        if (!$hospital) return;
        
        $userIds = [$hospitalUser->id];
        
        if ($hospital->agent_id) {
            $agentDetail = AgentUserDetail::find($hospital->agent_id);
            if ($agentDetail && $agentDetail->user_id) {
                $userIds[] = $agentDetail->user_id;
            }
        }
        
        $doctors = Doctor::where('hospital_id', $hospital->id)->with('user')->get();
        foreach ($doctors as $doctor) {
            if ($doctor->user_id) {
                $userIds[] = $doctor->user_id;
            }
        }
        
        $patientUserIds = DoctorPatientAppointment::where('hospital_id', $hospital->id)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        $userIds = array_merge($userIds, $patientUserIds);
        
        $unsyncedCount = User::whereIn('id', array_unique($userIds))
            ->where('cometchat_user_created', false)
            ->where('deleted', 0)
            ->count();
        
        if ($unsyncedCount > 0 && !self::$syncTriggered) {
            self::$syncTriggered = true;
            exec("php " . base_path() . "/artisan cometchat:sync-users > /dev/null 2>&1 & ");
            Log::info('Auto-sync triggered for CometChat users from hospital chat', ['unsynced_count' => $unsyncedCount]);
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
                    'user_name' => $user->name ?? 'Hospital',
                    'user_avatar' => $user->user_img_url ?? '',
                    'user_role' => $user->role
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Hospital Chat Init Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get all users hospital can chat with - FIXED with correct image field
     */
    public function getConversations()
    {
        try {
            $hospitalUser = Auth::user();
            $hospital = Hospital::where('user_id', $hospitalUser->id)->first();
            
            if (!$hospital) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            // $cacheKey = 'hospital_chat_users_' . $hospitalUser->id;
            // $cachedData = Cache::get($cacheKey);
            
            // if ($cachedData !== null) {
            //     return response()->json(['status' => '1', 'data' => $cachedData]);
            // }
            
            $userIds = [];
            
            // 1. Admin
            $admin = User::find(1);
            if ($admin) {
                $userIds[$admin->id] = ['type' => 'Admin', 'data' => $admin];
            }
            
            // 2. Assigned Agent
            if ($hospital->agent_id) {
                $agentDetail = AgentUserDetail::find($hospital->agent_id);
                if ($agentDetail && $agentDetail->user) {
                    $userIds[$agentDetail->user_id] = ['type' => 'Agent', 'data' => $agentDetail->user];
                }
            }
            
            // 3. Doctors
            $doctors = Doctor::where('hospital_id', $hospital->id)->with('user')->get();
            foreach ($doctors as $doctor) {
                if ($doctor->user) {
                    $userIds[$doctor->user_id] = ['type' => 'Doctor', 'data' => $doctor->user];
                }
            }
            
            // 4. Patients with appointments
            $patients = User::whereIn('users.id', function($query) use ($hospital) {
                    $query->select('doctor_patient_appointments.user_id')
                        ->from('doctor_patient_appointments')
                        ->where('doctor_patient_appointments.hospital_id', $hospital->id)
                        ->whereNotNull('doctor_patient_appointments.user_id');
                })
                ->where('users.role', 7)
                ->where('users.deleted', 0)
                ->where('users.active', 1)
                ->get();
            
            foreach ($patients as $patient) {
                $userIds[$patient->id] = ['type' => 'Patient', 'data' => $patient];
            }
            
            // 5. Call Center
            if ($hospital->callcenter_id) {
                $callCenterDetail = \App\Models\CallCenterUserDetail::find($hospital->callcenter_id);
                if ($callCenterDetail && $callCenterDetail->user) {
                    $userIds[$callCenterDetail->user_id] = ['type' => 'Call Center', 'data' => $callCenterDetail->user];
                }
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
            
            //Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Hospital Get Conversations Error: ' . $e->getMessage());
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
            
            $title = "New Message from Hospital: " . ($sender->name ?? 'Hospital');
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
            } elseif ($receiver->role == 4) {
                $path = "Callcenter/" . $timestamp;
                $url = url('callcenter/chat');
            } elseif ($receiver->role == 1) {
                $path = "Admin/" . $timestamp;
                $url = url('admin/chat');
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
            Log::error('Hospital Firebase Notification Error: ' . $e->getMessage());
        }
    }
}