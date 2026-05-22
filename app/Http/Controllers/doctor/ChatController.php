<?php
// app/Http/Controllers/doctor/ChatController.php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorPatientAppointment;
use App\Models\User;
use App\Models\Hospital;
use App\Models\AgentUserDetail;
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
        
        $doctor = Doctor::where('user_id', Auth::id())->first();
        $totalPatients = DoctorPatientAppointment::where('doctor_id', $doctor->id)->distinct('user_id')->count('user_id');
        $totalAppointments = DoctorPatientAppointment::where('doctor_id', $doctor->id)->count();
        
        return view('doctor.chat.index', compact('page_heading', 'module_heading', 'doctor', 'totalPatients', 'totalAppointments'));
    }

    private function triggerSyncIfNeeded()
    {
        $doctor = Auth::user();
        if (!$doctor) return;
        
        $doctorModel = Doctor::where('user_id', $doctor->id)->first();
        if (!$doctorModel) return;
        
        $patientIds = DoctorPatientAppointment::where('doctor_id', $doctorModel->id)
            ->where('booking_status', '!=', 'Cancelled')
            ->where('booking_status', '!=', 'cancelled')
            ->pluck('user_id')
            ->unique()
            ->toArray();
        
        if (empty($patientIds)) return;
        
        $unsyncedCount = User::whereIn('id', $patientIds)
            ->where('cometchat_user_created', false)
            ->where('deleted', 0)
            ->count();
        
        if ($unsyncedCount > 0 && !self::$syncTriggered) {
            self::$syncTriggered = true;
            exec("php " . base_path() . "/artisan cometchat:sync-users > /dev/null 2>&1 & ");
            Log::info('Auto-sync triggered for CometChat users from doctor chat', ['unsynced_count' => $unsyncedCount]);
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
                    'user_name' => 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                    'user_avatar' => $user->user_img_url ?? '',
                    'user_role' => $user->role
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Doctor Chat Init Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get all users doctor can chat with (NO API calls to CometChat here)
     * Frontend SDK handles conversations and last messages
     */
    public function getConversations()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            // $cacheKey = 'doctor_chat_users_' . $user->id;
            // $cachedData = Cache::get($cacheKey);
            
            // if ($cachedData !== null) {
            //     return response()->json(['status' => '1', 'data' => $cachedData]);
            // }
            
            $doctorModel = Doctor::where('user_id', $user->id)->first();
            if (!$doctorModel) {
                return response()->json(['status' => '0', 'data' => []]);
            }
            
            $userIds = [];
            
            // Get patients from active appointments
            $patients = DoctorPatientAppointment::where('doctor_id', $doctorModel->id)
                ->with('user')
                ->where('booking_status', '!=', 'Cancelled')
                ->where('booking_status', '!=', 'cancelled')
                ->select('user_id', DB::raw('MAX(created_at) as last_appointment_at'))
                ->groupBy('user_id')
                ->get();
                
            foreach ($patients as $appointment) {
                $patient = $appointment->user;
                if ($patient) {
                    $userIds[$patient->id] = [
                        'type' => 'patient',
                        'data' => $patient
                    ];
                }
            }
            
            // Get associated hospital
            if ($doctorModel->hospital_id) {
                $hospital = \App\Models\Hospital::with('user')->find($doctorModel->hospital_id);
                if ($hospital && $hospital->user) {
                    $userIds[$hospital->user_id] = [
                        'type' => 'hospital',
                        'data' => $hospital
                    ];
                }
            }
            
            // Add Admin, Agent, CallCenter
            $otherUsers = User::whereIn('role', [1, 3, 4])
                ->where('deleted', 0)
                ->where('cometchat_user_created', true)
                ->where('active', 1)
                ->get();
                
            foreach ($otherUsers as $otherUser) {
                $userIds[$otherUser->id] = [
                    'type' => 'other',
                    'data' => $otherUser
                ];
            }
            
            $result = [];
            foreach ($userIds as $userId => $info) {
                if ($info['type'] == 'patient') {
                    $patient = $info['data'];
                    $result[] = [
                        'id' => $patient->id,
                        'uid' => 'user_' . $patient->id,
                        'name' => trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')),
                        'avatar' => $patient->user_img_url ?? '',
                        'type' => 'Patient',
                        'specialty' => 'Patient',
                    ];
                } elseif ($info['type'] == 'hospital') {
                    $hospital = $info['data'];
                    $hospitalUser = $hospital->user;
                    $result[] = [
                        'id' => $hospitalUser->id,
                        'uid' => 'user_' . $hospitalUser->id,
                        'name' => $hospital->name_en ?? 'Hospital',
                        'avatar' => $hospitalUser->user_img_url ?? '',
                        'type' => 'Hospital',
                        'specialty' => 'Hospital',
                    ];
                } else {
                    $otherUser = $info['data'];
                    $result[] = [
                        'id' => $otherUser->id,
                        'uid' => 'user_' . $otherUser->id,
                        'name' => $this->getUserDisplayName($otherUser),
                        'avatar' => $otherUser->user_img_url ?? '',
                        'type' => $this->getUserTypeName($otherUser->role),
                        'specialty' => $this->getUserTypeName($otherUser->role),
                    ];
                }
            }
            
            //Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Doctor Get Conversations Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => []]);
        }
    }

    public function getMessages($receiverId)
    {
        // Handled by frontend SDK
        return response()->json(['status' => '1', 'data' => []]);
    }

    public function sendMessage(Request $request)
    {
        // Handled by frontend SDK
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

    private function getUserDisplayName($user): string
    {
        if (!$user) return 'Unknown';
        
        if ($user->role == 6) {
            return 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        }
        
        if ($user->role == 5 || $user->role == 8) {
            $hospital = \App\Models\Hospital::where('user_id', $user->id)->first();
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
        
        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        return $name ?: 'User';
    }

    private function getUserTypeName($role): string
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

    private function sendFirebaseNotification($receiverId, $senderId, $message)
    {
        try {
            $sender = User::find($senderId);
            $receiver = User::find($receiverId);
            
            if (!$sender || !$receiver) return;
            
            $timestamp = time();
            $createdAt = gmdate("d-m-Y H:i:s", $timestamp);
            
            exec("php " . base_path() . "/artisan update:empty_firebase_keys > /dev/null 2>&1 & ");
            
            $title = "New Message from Dr. " . trim(($sender->first_name ?? 'Doctor') . ' ' . ($sender->last_name ?? ''));
            $description = substr($message, 0, 100);
            
            $path = "";
            $url = "";
            
            if ($receiver->role == 7) {
                $key = $receiver->firebase_user_key ?? $receiver->id;
                $path = "Nottifications/" . $key . "/" . $timestamp;
                $url = url('patient/chat');
            } elseif ($receiver->role == 6) {
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
            } elseif ($receiver->role == 1) {
                $path = "Admin/" . $receiver->id . "/" . $timestamp;
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
            Log::error('Doctor Firebase Notification Error: ' . $e->getMessage());
        }
    }
}