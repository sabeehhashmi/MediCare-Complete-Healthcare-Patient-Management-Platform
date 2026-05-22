<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateEmptyFirebaseUserKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:empty_firebase_keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger UpdateUserFirebaseNode for users with empty firebase_user_key';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersQuery = User::where(function($query) {
            $query->whereNull('firebase_user_key')
                  ->orWhere('firebase_user_key', '');
        });

        $count = $usersQuery->count();

        if ($count === 0) {
            $this->info('No users found with empty firebase_user_key.');
            return 0;
        }

        $this->info("Found $count users. Starting update...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $usersQuery->chunk(100, function ($users) use ($bar) {
            foreach ($users as $user) {
                // Trigger the existing command for each user
                $this->call('update:firebase_node', [
                    'user_id' => $user->id
                ]);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('Successfully triggered updates for all users.');

        return 0;
    }
}
