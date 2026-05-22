<?php
// app/Http/Controllers/admin/ChatMonitorController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\AgentUserDetail;
use App\Services\CometChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class ChatMonitorController extends Controller
{
    protected $cometChat;

    public function __construct(CometChatService $cometChat)
    {
        $this->cometChat = $cometChat;
    }

    public function index()
    {
        $page_heading = "Chat Monitor";
        return view('admin.chat.monitor', compact('page_heading'));
    }

    /**
     * Get ALL users for monitoring with CometChat validation
     */
    public function getUsers()
    {
        try {
            $cacheKey = 'chat_monitor_all_users_v2';
            $cachedData = Cache::get($cacheKey);
            
            if ($cachedData !== null) {
                return response()->json(['status' => '1', 'data' => $cachedData]);
            }
            
            // Get all users
            $users = User::whereIn('role', [1, 3, 4, 5, 6, 7, 8])
                ->where('deleted', 0)
                ->where('active', 1)
                ->select('id', 'role', 'first_name', 'last_name', 'name', 'email', 'user_image', 'cometchat_user_created')
                ->get();
            
            $result = [];
            foreach ($users as $user) {
                // Ensure user exists in CometChat
                $cometChatUid = null;
                if ($user->cometchat_user_created) {
                    $cometChatUid = 'user_' . $user->id;
                } else {
                    // Try to create user in CometChat
                    try {
                        $cometChatUid = $this->cometChat->getOrCreateUser($user);
                        if ($cometChatUid) {
                            $user->cometchat_user_created = true;
                            $user->save();
                        }
                    } catch (Exception $e) {
                        Log::warning('Could not create CometChat user: ' . $e->getMessage());
                        continue; // Skip this user if can't create
                    }
                }
                
                if ($cometChatUid) {
                    $result[] = [
                        'id' => $user->id,
                        'uid' => $cometChatUid,
                        'name' => $this->getUserDisplayName($user),
                        'role' => $this->getRoleName($user->role),
                        'role_id' => $user->role,
                        'avatar' => $user->user_img_url ?? '',
                        'email' => $user->email ?? '',
                        'cometchat_exists' => $user->cometchat_user_created
                    ];
                }
            }
            
            Cache::put($cacheKey, $result, 300);
            
            return response()->json(['status' => '1', 'data' => $result]);
            
        } catch (Exception $e) {
            Log::error('Monitor Get Users Error: ' . $e->getMessage());
            return response()->json(['status' => '0', 'data' => [], 'message' => $e->getMessage()]);
        }
    }

    private function getUserDisplayName($user): string
    {
        if (!$user) return 'Unknown';
        if ($user->role == 1) return $user->name ?? 'Admin';
        if ($user->role == 6) return 'Dr. ' . trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        if ($user->role == 5 || $user->role == 8) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            return $hospital->name_en ?? ($user->name ?? ($user->role == 5 ? 'Hospital' : 'Clinic'));
        }
        if ($user->role == 3) {
            $agentDetails = AgentUserDetail::where('user_id', $user->id)->first();
            return $agentDetails->user->name ?? ($user->name ?? 'Agent');
        }
        if ($user->role == 4) return $user->name ?? 'Call Center';
        if ($user->role == 7) return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Patient';
        return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'User';
    }

    private function getRoleName($role): string
    {
        $roles = [1 => 'Admin', 3 => 'Agent', 4 => 'Call Center', 5 => 'Hospital', 6 => 'Doctor', 7 => 'Patient', 8 => 'Clinic'];
        return $roles[$role] ?? 'User';
    }
}