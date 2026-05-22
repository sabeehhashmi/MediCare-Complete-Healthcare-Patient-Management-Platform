<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendVerificationMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-verification-mail {id} {type}';

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
        //
        $id = $this->argument("id");
        $type = $this->argument("type");

        $user = User::find($id);

        if($type == 'doctor'){
            $view = 'mail.verify_doctor';
            $subject = 'Welcome to Mednero! Your Registration is Under Review';
        }else{
            $view = 'mail.verify';
            $subject = 'Welcome to Mednero ! Your Clinic/ Hospital Registration is Under Review';
        }

        $verificationUrl = url('/'.$type.'/verify-email/'.$user->email_verification_token);
        $mailbody = view($view, compact('user', 'verificationUrl'))->render();
        send_email($user->email, $subject, $mailbody);

        $mailbody_admin = view('mail.account_activation_mail_admin', compact('user','type'));
        $title_admin = "Account Activation Request:".$user->name;
        $ret = send_email('anilnavis@gmail.com',$title_admin,$mailbody_admin);
    }
}
