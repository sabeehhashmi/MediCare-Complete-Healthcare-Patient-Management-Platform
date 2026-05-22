<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CometChatService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCometChatUsers extends Command
{
    protected $signature = 'cometchat:sync-users';
    protected $description = 'Sync existing users with CometChat';

    protected $cometChat;

    public function __construct(CometChatService $cometChat)
    {
        parent::__construct();
        $this->cometChat = $cometChat;
    }

    public function handle()
    {
        $this->info('Starting CometChat user sync...');
        
        $users = User::where('cometchat_user_created', false)
            ->whereIn('role', [1, 3, 4, 5, 6, 7, 8])
             ->where('deleted', 0)
            ->where('active', 1)
            ->get();

        if ($users->isEmpty()) {
            $this->info('No users to sync.');
            return 0;
        }

        $this->info("Syncing " . $users->count() . " users...");
        
        $synced = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                $uid = $this->cometChat->getOrCreateUser($user);
                
                if ($uid) {
                    $user->cometchat_uid = $uid;
                    $user->cometchat_user_created = true;
                    $user->cometchat_created_at = now();
                    $user->save();
                    $synced++;
                    $this->info("✓ Synced user: {$user->id} - {$user->email}");
                } else {
                    $failed++;
                    $this->error("✗ Failed to sync user: {$user->id}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error for user {$user->id}: " . $e->getMessage());
                Log::error('Sync user error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
            
            usleep(100000);
        }

        $this->info("\nSync completed!");
        $this->info("Synced: $synced, Failed: $failed");
        
        return 0;
    }
}