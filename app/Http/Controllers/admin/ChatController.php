<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\AgentUserDetail; // ADD THIS for agent details
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
        $page_heading = "Admin Chat";
        $this->triggerSyncIfNeeded();
        return view('admin.chat.index', compact('page_heading'));
    }

    private function triggerSyncIfNeeded()
    {
        $unsyncedCount = User::where('cometchat_user_created', false)
            ->whereIn('role', [7, 6, 5, 8, 3, 4])
            ->where('deleted', 0)
            ->count();
        
        if ($unsyncedCount > 0 && !self::$syncTriggered) {
            self::$syncTriggered = true;
            exec("php " . base_path() . "/artisan cometchat:sync-users > /dev/null 2>&1 & ");
            Log::info('Auto-sync triggered for CometChat users', ['unsynced_count' => $unsyncedCount]);
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
                return response()->json(['status' => '0', 'message' => 'Failed to create user']);
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
                    'user_name' => $user->name ?? 'Admin',
                    'user_avatar' => $user->user_img_url ?? '',
                    'user_role' => $user->role
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Admin Chat Init Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get conversations - returns user list with proper names
     */
    public function getConversations()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            // $cacheKey = 'admin_conversations_' . $user->id;
            // $cachedData = Cache::get($cacheKey);
            
            // if ($cachedData !== null) {
            //     return response()->json(['status' => '1', 'data' => $cachedData]);
            // }
            
            // Get all users with their details - FIXED for agent names
            $users = User::whereIn('role', [7, 6, 5, 8, 3, 4])
                ->where('deleted', 0)
                ->where('cometchat_user_created', true)
                ->select('id', 'role', 'first_name', 'last_name', 'name', 'email', 'user_image')
                ->get();
            
            $result = [];
            foreach ($users as $userItem) {
                $result[] = [
                    'id' => $userItem->id,
                    'uid' => 'user_' . $userItem->id,
                    'name' => $this->getUserDisplayName($userItem),
                    'avatar' => $userItem->user_img_url ?? '',
                    'role' => $userItem->role,
                    'role_name' => $this->getRoleName($userItem->role),
                ];
            }
            
            //Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Admin Get Conversations Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => []]);
        }
    }

    /**
     * Get users list with proper names - FIXED for agents
     */
    public function getUsers(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $users = User::whereIn('role', [7, 6, 5, 8, 3, 4])
                ->where('deleted', 0)
                ->where('cometchat_user_created', true)
                ->when($search, function($q) use ($search) {
                    $q->where(function($query) use ($search) {
                        $query->where('first_name', 'LIKE', "%{$search}%")
                              ->orWhere('last_name', 'LIKE', "%{$search}%")
                              ->orWhere('name', 'LIKE', "%{$search}%")
                              ->orWhere('email', 'LIKE', "%{$search}%");
                    });
                })
                ->orderBy('id', 'asc')
                ->get();
            
            $result = [];
            foreach ($users as $userItem) {
                $result[] = [
                    'id' => $userItem->id,
                    'name' => $this->getUserDisplayName($userItem),
                    'avatar' => $userItem->user_img_url ?? '',
                    'uid' => 'user_' . $userItem->id,
                    'type' => $this->getRoleName($userItem->role),
                    'email' => $userItem->email ?? '',
                ];
            }
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Get users error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => []]);
        }
    }

    /**
     * Get messages - pass-through
     */
    public function getMessages($receiverId)
    {
        return response()->json(['status' => '1', 'data' => []]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|string',
            'message' => 'required|string|max:5000'
        ]);
        
        try {
            $senderId = Auth::id();
            if (!$senderId) {
                return response()->json(['status' => '0', 'message' => 'User not authenticated']);
            }
            
            $receiverId = str_replace('user_', '', $request->receiver_id);
            $message = trim($request->message);
            
            if (empty($message)) {
                return response()->json(['status' => '0', 'message' => 'Message cannot be empty']);
            }
            
            $sentMessage = $this->cometChat->sendMessage($receiverId, $message, $senderId);
            
            if (!$sentMessage) {
                return response()->json(['status' => '0', 'message' => 'Failed to send message']);
            }
            
            $this->sendFirebaseNotification($receiverId, $senderId, $message);
            
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone(config('app.timezone', 'Asia/Dubai')));
            
            return response()->json([
                'status' => '1',
                'data' => [
                    'id' => $sentMessage['id'] ?? null,
                    'text' => $message,
                    'sender' => 'user_' . $senderId,
                    'receiver' => 'user_' . $receiverId,
                    'sent_at' => now()->timestamp * 1000,
                    'sent_time' => $dateTime->format('h:i A'),
                    'sent_date' => $dateTime->format('d M Y'),
                    'is_sent_by_me' => true,
                    'type' => 'text'
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Admin Send Message Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    public function uploadAttachment(Request $request)
    {
        try {
            $senderId = Auth::id();
            if (!$senderId) {
                return response()->json(['status' => '0', 'message' => 'User not authenticated']);
            }
            
            $receiverUid = $request->receiver_id;
            $type = $request->type;
            $file = $request->file('attachment');
            
            if (!$file) {
                return response()->json(['status' => '0', 'message' => 'No file uploaded']);
            }
            
            if ($file->getSize() > 10 * 1024 * 1024) {
                return response()->json(['status' => '0', 'message' => 'File too large. Max 10MB']);
            }
            
            $cometChatType = 'file';
            if ($type === 'image') $cometChatType = 'image';
            elseif ($type === 'audio') $cometChatType = 'audio';
            
            $sentMessage = $this->cometChat->sendMediaMessage($receiverUid, $file, $cometChatType);
            
            if (!$sentMessage) {
                return response()->json(['status' => '0', 'message' => 'Failed to send media message']);
            }
            
            return response()->json([
                'status' => '1',
                'data' => [
                    'id' => $sentMessage['id'] ?? null,
                    'type' => $type,
                    'url' => $sentMessage['data']['url'] ?? '',
                    'name' => $file->getClientOriginalName(),
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    public function markAsRead(Request $request)
    {
        $request->validate(['receiver_id' => 'required|string']);
        return response()->json(['status' => '1']);
    }

    public function getUnreadCount(Request $request)
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

    /**
     * FIXED: Get user display name - Now properly handles Agent names
     */
    private function getUserDisplayName($user): string
    {
        if (!$user) return 'Unknown';
        
        // Doctor (role 6)
        if ($user->role == 6) {
            return 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        }
        
        // Hospital (role 5) or Clinic (role 8)
        if ($user->role == 5 || $user->role == 8) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            return $hospital->name_en ?? ($user->name ?? ($user->role == 5 ? 'Hospital' : 'Clinic'));
        }
        
        // Agent (role 3) - FIXED: Get agent details properly
        if ($user->role == 3) {
            // Try to get agent details
            $agentDetails = AgentUserDetail::where('user_id', $user->id)->first();
            if ($agentDetails && $agentDetails->user) {
                return trim(($agentDetails->user->name ?? '')) ?: 'Agent';
            }
            // Fallback to user name
            return trim(($user->name ?? '')) ?: 'Agent';
        }
        
        // Call Center (role 4)
        if ($user->role == 4) {
            return $user->name ?? 'Call Center';
        }
        
        // Patient (role 7) or others
        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        return $name ?: 'User';
    }

    private function getRoleName($role): string
    {
        $roles = [
            7 => 'Patient', 6 => 'Doctor', 5 => 'Hospital', 8 => 'Clinic',
            3 => 'Agent', 4 => 'Call Center', 1 => 'Admin'
        ];
        return $roles[$role] ?? 'User';
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
            
            $title = "New Message from Admin: " . ($sender->name ?? 'Admin');
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
            } elseif ($receiver->role == 4) {
                $path = "Callcenter/" . $timestamp;
                $url = url('callcenter/chat');
            } elseif ($receiver->role == 7) {
                $key = $receiver->firebase_user_key ?? $receiver->id;
                $path = "Nottifications/" . $key . "/" . $timestamp;
                $url = url('patient/chat');
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
                    "imageURL" => $sender->user_image_url ?? '',
                    "read" => "0",
                    "seen" => "0",
                    "type" => "chat"
                ]
            ];
            
            $this->database->getReference()->update($updates);
            
        } catch (Exception $e) {
            Log::error('Firebase Notification Error: ' . $e->getMessage());
        }
    }
}