<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class VerifyOldUsersSeeder extends Seeder
{
    public function run()
    {
        // Seeder logic as provided previously
        $currentTimestamp = Carbon::now();
        User::whereNull('email_verified_at')
            ->update(['email_verified_at' => $currentTimestamp]);

        $updatedUsersCount = User::where('email_verified_at', $currentTimestamp)->count();
        $this->command->info("Updated email_verified_at for $updatedUsersCount users.");
    }
}
