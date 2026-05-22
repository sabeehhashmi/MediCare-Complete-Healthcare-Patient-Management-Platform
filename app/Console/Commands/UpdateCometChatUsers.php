<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateCometChatUsers extends Command
{
    protected $signature = 'cometchat:update-users';
    protected $description = 'Clear CometChat user columns (make them empty)';

    public function handle()
    {
        $this->info("Clearing CometChat user columns...");

        // Clear CometChat columns for all users
        $affected = User::whereIn('role', [1, 3, 4, 5, 6, 7, 8])
            ->where('deleted', 0)
            ->update([
                'cometchat_uid' => null,
                'cometchat_user_created' => false,
                'cometchat_created_at' => null,
            ]);

        $this->info("✓ Cleared CometChat data for {$affected} users");

        return 0;
    }
}