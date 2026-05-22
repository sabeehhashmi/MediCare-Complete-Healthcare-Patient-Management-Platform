<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TempUsers;

class SendLoginOtpMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-login-otp-mail {user_id} {type}';

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
        $type = $this->argument("type");

        if($type=='temp'){
            $user =  TempUsers::where(['id'=>$user_id])->get()->first();
        }else{
            $user =  User::where(['id'=>$user_id])->get()->first();
        }
        if($user){
            if($user->email != ''){
                $mailbody = view('mail.login_otp_mail', compact('user','type'));
                send_email($user->email,"Mednero Verification",$mailbody);
            }
        }
        //
    }
}
