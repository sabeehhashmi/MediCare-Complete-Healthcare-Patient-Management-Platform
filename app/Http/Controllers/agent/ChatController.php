<?php
// app/Http/Controllers/agent/ChatController.php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use App\Models\AgentUserDetail;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use App\Services\CometChatService;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        
        $totalHospitals = Hospital::where('type', TYPE_HOSPITAL)->count();
        $totalClinics = Hospital::where('type', TYPE_CLINIC)->count();
        $totalDoctors = Doctor::count();
        $totalPatients = User::where('role', 7)->where('deleted', 0)->where('active', 1)->count();
        
        return view('agent.chat.index', compact('page_heading', 'module_heading', 'totalHospitals', 'totalClinics', 'totalDoctors', 'totalPatients'));
    }

    private function triggerSyncIfNeeded()
    {
        $agent = Auth::user();
        if (!$agent) return;
        
        $unsyncedCount = User::where('cometchat_user_created', false)
            ->whereIn('role', [7, 6, 5, 8, 3, 4, 1])
            ->where('deleted', 0)
            ->where('active', 1)
            ->count();
        
        if ($unsyncedCount > 0 && !self::$syncTriggered) {
            self::$syncTriggered = true;
            exec("php " . base_path() . "/artisan cometchat:sync-users > /dev/null 2>&1 & ");
            Log::info('Auto-sync triggered for CometChat users from agent chat', ['unsynced_count' => $unsyncedCount]);
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
                    'user_name' => $user->name ?? 'Agent',
                    'user_avatar' => $user->user_img_url ?? '',
                    'user_role' => $user->role
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Agent Chat Init Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get all users agent can chat with (Admin, Hospitals, Clinics, Doctors, Patients, Call Center)
     */
    public function getConversations()
    {
        try {
            $agent = Auth::user();
            
            if (!$agent) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            // $cacheKey = 'agent_chat_users_' . $agent->id;
            // $cachedData = Cache::get($cacheKey);
            
            // if ($cachedData !== null) {
            //     return response()->json(['status' => '1', 'data' => $cachedData]);
            // }
            
            // Get ALL users (Admin, Hospitals, Clinics, Doctors, Patients, Call Center)
            $allUsers = User::whereIn('role', [1, 3, 4, 5, 6, 7, 8])
                ->where('deleted', 0)
                ->where('active', 1)
                ->get();
            
            $result = [];
            foreach ($allUsers as $userItem) {
                // Skip current user
                if ($userItem->id == $agent->id) continue;
                
                $result[] = [
                    'id' => $userItem->id,
                    'uid' => 'user_' . $userItem->id,
                    'name' => $this->getUserDisplayName($userItem),
                    'avatar' => $userItem->user_img_url ?? '',
                    'type' => $this->getRoleName($userItem->role),
                ];
            }
            
           // Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Agent Get Conversations Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => []]);
        }
    }

    private function getUserDisplayName($user): string
    {
        if (!$user) return 'Unknown';
        
        if ($user->role == 6) {
            return 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        }
        if ($user->role == 5 || $user->role == 8) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            return $hospital->name_en ?? ($user->name ?? ($user->role == 5 ? 'Hospital' : 'Clinic'));
        }
        if ($user->role == 3) {
            $agentDetails = AgentUserDetail::where('user_id', $user->id)->first();
            if ($agentDetails && $agentDetails->user) {
                return trim(($agentDetails->user->name ?? '')) ?: 'Agent';
            }
            return trim(($user->name ?? '')) ?: 'Agent';
        }
        if ($user->role == 4) {
            return $user->name ?? 'Call Center';
        }
        if ($user->role == 1) {
            return $user->name ?? 'Admin';
        }
        if ($user->role == 7) {
            return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Patient';
        }
        return $user->name ?? 'User';
    }

    private function getRoleName($role): string
    {
        $roles = [
            1 => 'Admin',
            3 => 'Agent',
            4 => 'Call Center',
            5 => 'Hospital',
            6 => 'Doctor',
            7 => 'Patient',
            8 => 'Clinic'
        ];
        return $roles[$role] ?? 'User';
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
            
            $title = "New Message from Agent: " . ($sender->name ?? 'Agent');
            $description = substr($message, 0, 100);
            
            $path = "";
            $url = "";
            
            if ($receiver->role == 6) {
                $path = "Doctor/" . $receiver->id . "/" . $timestamp;
                $url = url('doctor/chat');
            } elseif ($receiver->role == 5 || $receiver->role == 8) {
                $path = "Hospital/" . $receiver->id . "/" . $timestamp;
                $url = url('hospital/chat');
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
            Log::error('Agent Firebase Notification Error: ' . $e->getMessage());
        }
    }
}