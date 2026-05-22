<?php

namespace App\Services;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CometChatService
{
    public $appId;
    public $region;
    public $apiKey;
    public $baseUrl;
    private $checkedUsers = [];

    public function __construct()
    {
        $this->appId = config('cometchat.app_id');
        $this->region = config('cometchat.region');
        $this->apiKey = config('cometchat.api_key');
        // Correct base URL format for v3 API
        $this->baseUrl = "https://{$this->appId}.api-{$this->region}.cometchat.io/v3";
    }

    public function userExists($userIdentifier)
    {
        $user = $this->getUserModel($userIdentifier);
        if (!$user) return false;
        
        $uid = 'user_' . $user->id;
        
        if ($user->cometchat_user_created && $user->cometchat_uid === $uid) {
            return true;
        }
        
        if (isset($this->checkedUsers[$uid])) {
            return $this->checkedUsers[$uid];
        }
        
        try {
            // Correct endpoint: GET /users/:uid
            $response = $this->makeRequestWithTimeout('GET', "/users/{$uid}", [], 10);
            $exists = isset($response['data']['uid']);
            $this->checkedUsers[$uid] = $exists;
            
            if ($exists && !$user->cometchat_user_created) {
                $user->cometchat_uid = $uid;
                $user->cometchat_user_created = true;
                $user->cometchat_created_at = now();
                $user->save();
            }
            
            return $exists;
        } catch (Exception $e) {
            $this->checkedUsers[$uid] = false;
            return false;
        }
    }

    public function getOrCreateUser($userIdentifier)
    {
        try {
            $user = $this->getUserModel($userIdentifier);
            if (!$user) return null;
            
            $uid = 'user_' . $user->id;
            
            if ($user->cometchat_user_created && $user->cometchat_uid === $uid) {
                return $uid;
            }
            
            if ($this->userExists($uid)) {
                return $uid;
            }
            
            Log::info('Creating new CometChat user', ['uid' => $uid, 'name' => $user->name]);
            
            $data = [
                'uid' => $uid,
                'name' => $this->getUserDisplayName($user),
                'avatar' => $this->getUserAvatar($user),
                'role' => 'default',
                'metadata' => [
                    '@private' => [
                        'user_id' => $user->id,
                        'role' => $user->role,
                        'email' => $user->email ?? ''
                    ]
                ]
            ];
            
            $response = $this->makeRequestWithTimeout('POST', '/users', $data, 30);
            
            if (isset($response['data']['uid'])) {
                $user->cometchat_uid = $uid;
                $user->cometchat_user_created = true;
                $user->cometchat_created_at = now();
                $user->save();
                return $uid;
            }
            
            if (isset($response['error']['code']) && $response['error']['code'] === 'ERR_UID_ALREADY_EXISTS') {
                $user->cometchat_uid = $uid;
                $user->cometchat_user_created = true;
                $user->cometchat_created_at = now();
                $user->save();
                return $uid;
            }
            
            return null;
            
        } catch (Exception $e) {
            Log::error('CometChat getOrCreateUser error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function getMessagesBetweenUsers($user1Id, $user2Id, $limit = 50)
    {
        try {
            $user1Uid = $this->getOrCreateUser($user1Id);
            $user2Uid = $this->getOrCreateUser($user2Id);
            
            if (!$user1Uid || !$user2Uid) return [];
            
            // Correct endpoint: GET /messages?uid=:uid&limit=:limit
            $response = $this->makeRequestWithTimeout('GET', "/messages?uid={$user2Uid}&limit={$limit}", [], 10);
            
            if (empty($response) || !isset($response['messages'])) {
                return [];
            }
            
            $messages = [];
            foreach ($response['messages'] as $message) {
                if (($message['sender'] === $user1Uid && $message['receiver'] === $user2Uid) ||
                    ($message['sender'] === $user2Uid && $message['receiver'] === $user1Uid)) {
                    
                    $messages[] = [
                        'id' => $message['id'] ?? null,
                        'data' => $message['data'] ?? [],
                        'sender' => $message['sender'] ?? '',
                        'receiver' => $message['receiver'] ?? '',
                        'sentAt' => $message['sentAt'] ?? (time() * 1000),
                        'type' => $message['type'] ?? 'text',
                    ];
                }
            }
            
            return $messages;
            
        } catch (Exception $e) {
            Log::error('CometChat getMessagesBetweenUsers error', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    public function getLastMessageBetweenUsers($user1Id, $user2Id)
    {
        $messages = $this->getMessagesBetweenUsers($user1Id, $user2Id, 1);
        return !empty($messages) ? $messages[0] : null;
    }
    
    public function generateAuthToken($userId)
    {
        try {
            $uid = $this->getOrCreateUser($userId);
            if (!$uid) return null;
            
            // Correct endpoint: POST /users/:uid/auth_tokens
            $response = $this->makeRequestWithTimeout('POST', "/users/{$uid}/auth_tokens", [], 15);
            return $response['data']['authToken'] ?? null;
        } catch (Exception $e) {
            Log::error('CometChat generateAuthToken error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function sendMessage($receiverId, $message, $senderId)
    {
        try {
            $senderUid = $this->getOrCreateUser($senderId);
            $receiverUid = $this->getOrCreateUser($receiverId);
            
            if (!$senderUid || !$receiverUid) return null;
            
            $data = [
                'receiver' => $receiverUid,
                'receiverType' => 'user',
                'type' => 'text',
                'data' => ['text' => $message]
            ];
            
            // Correct endpoint: POST /messages
            $response = $this->makeRequestWithTimeout('POST', '/messages', $data, 15);
            
            if (isset($response['data'])) {
                return $response['data'];
            }
            
            return null;
            
        } catch (Exception $e) {
            Log::error('CometChat sendMessage error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function sendMediaMessage($receiverUid, $file, $type, $senderUid = null)
    {
        try {
            // First upload the file to CometChat
            $uploadResult = $this->uploadFileToCometChat($file);
            
            if (!$uploadResult || !isset($uploadResult['attachments'][0])) {
                Log::error('File upload failed', ['result' => $uploadResult]);
                return null;
            }
            
            $attachment = $uploadResult['attachments'][0];
            
            $data = [
                'receiver' => $receiverUid,
                'receiverType' => 'user',
                'type' => $type,
                'data' => [
                    'attachments' => [$attachment],
                    'url' => $attachment['url'],
                    'name' => $file->getClientOriginalName()
                ]
            ];
            
            if ($type === 'image') {
                $data['data']['caption'] = $file->getClientOriginalName();
            }
            
            // Correct endpoint: POST /messages
            $response = $this->makeRequestWithTimeout('POST', '/messages', $data, 60);
            
            if (isset($response['data'])) {
                Log::info('Media message sent', ['message_id' => $response['data']['id']]);
                return $response['data'];
            }
            
            Log::error('Send media message failed', ['response' => $response]);
            return null;
            
        } catch (Exception $e) {
            Log::error('CometChat sendMediaMessage error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function uploadFileToCometChat($file)
    {
        // Correct endpoint: POST /media/upload
        $url = $this->baseUrl . "/media/upload";
        
        try {
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                Log::error('Cannot read file', ['path' => $file->getRealPath()]);
                return null;
            }
            
            $response = Http::timeout(60)->withHeaders([
                'apikey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->attach(
                'file', $fileContent, $file->getClientOriginalName(),
                ['Content-Type' => $file->getMimeType()]
            )->post($url);
            
            if ($response->successful()) {
                $result = $response->json();
                Log::info('File uploaded to CometChat', ['result' => $result]);
                return $result['data'] ?? $result;
            }
            
            Log::error('Upload failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
            
        } catch (Exception $e) {
            Log::error('CometChat uploadFile error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function getTotalUnreadCount($uid)
    {
        try {
            $userUid = $this->getOrCreateUser($uid);
            if (!$userUid) return 0;
            
            // Correct endpoint: GET /users/:uid/messages/unread
            $response = $this->makeRequestWithTimeout('GET', "/users/{$userUid}/messages/unread", [], 10);
            return $response['data']['totalUnreadCount'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function markAsRead($uid)
    {
        try {
            $userUid = $this->getOrCreateUser($uid);
            if (!$userUid) return false;
            
            // For v3 API, marking as read is automatic when fetching messages
            // Just fetch messages to mark them as read
            $this->makeRequestWithTimeout('GET', "/messages?uid={$userUid}&limit=1", [], 10);
            return true;
        } catch (Exception $e) {
            Log::error('Mark as read error', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function getUserModel($userIdentifier)
    {
        if ($userIdentifier instanceof User) {
            return $userIdentifier;
        }
        if (is_numeric($userIdentifier)) {
            return User::find($userIdentifier);
        }
        if (is_string($userIdentifier) && strpos($userIdentifier, 'user_') === 0) {
            $numericId = str_replace('user_', '', $userIdentifier);
            return User::find($numericId);
        }
        if (is_string($userIdentifier)) {
            return User::find($userIdentifier);
        }
        return null;
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
            return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Agent';
        }
        if ($user->role == 4) {
            return $user->name ?? 'Call Center';
        }
        if ($user->role == 1) {
            return $user->name ?? 'Admin';
        }
        return trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'User';
    }
    
    private function getUserAvatar($user): string
    {
        $default = $user->role == 6 ? asset('assets/img/doctor-avatar.jpg') : asset('admin-assets/assets/images/default-avatar.jpg');
        return $user->user_img_url ?? $default;
    }
    
    private function makeRequestWithTimeout($method, $endpoint, $data = [], $timeout = 30)
    {
        $url = $this->baseUrl . $endpoint;
        
        try {
            $request = Http::timeout($timeout)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apikey' => $this->apiKey
            ]);
            
            $response = $method === 'GET' ? $request->get($url) : $request->post($url, $data);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::warning('CometChat API error', [
                'status' => $response->status(),
                'url' => $url,
                'response' => $response->body()
            ]);
            
            return ['error' => $response->json(), 'status' => $response->status()];
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('CometChat timeout', ['url' => $url]);
            return [];
        } catch (Exception $e) {
            Log::error('CometChat request error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get all messages for a specific user (for monitoring)
     */
    public function getMessagesForUser($userUid, $limit = 100)
    {
        try {
            $url = $this->baseUrl . "/messages?uid={$userUid}&limit={$limit}";
            
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apikey' => $this->apiKey
            ])->get($url);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [];
        } catch (Exception $e) {
            Log::error('Get messages for user error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Async mark as read - Non-blocking
     */
    public function markAsReadAsync($uid)
    {
        try {
            $userUid = $this->getOrCreateUser($uid);
            if (!$userUid) return;
            
            // Fire and forget - use queue or just ignore
            if (function_exists('dispatch')) {
                dispatch(function() use ($userUid) {
                    try {
                        $this->makeRequestWithTimeout('GET', "/messages?uid={$userUid}&limit=1", [], 5);
                    } catch (Exception $e) {
                        // Silently fail
                    }
                })->onQueue('low');
            }
            
        } catch (Exception $e) {
            // Log but don't throw
            Log::warning('Async mark as read failed: ' . $e->getMessage());
        }
    }

    /**
     * Get last message between users with cache support
     */
    public function getLastMessageBetweenUsersCached($user1Id, $user2Id, $ttl = 60)
    {
        $cacheKey = "last_msg_{$user1Id}_{$user2Id}";
        
        // $cached = Cache::get($cacheKey);
        // if ($cached !== null) {
        //     return $cached;
        // }
        
        $message = $this->getLastMessageBetweenUsers($user1Id, $user2Id);
        
        // if ($message) {
        //     Cache::put($cacheKey, $message, $ttl);
        // }
        
        return $message;
    }
}