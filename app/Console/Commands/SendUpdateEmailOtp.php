<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
class SendUpdateEmailOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-update-email-otp {user_id} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->argument("user_id");
        $type = $this->argument("email");
        $mail = base64_decode($type);

        
            $user =  User::where(['id'=>$user_id])->get()->first();
        $type = 'noraml';
        if($user){
            
                $mailbody = view('mail.login_otp_mail', compact('user','type'));
                send_email($mail,"Mednero Verification",$mailbody);
            
        }
        //
    }
}
