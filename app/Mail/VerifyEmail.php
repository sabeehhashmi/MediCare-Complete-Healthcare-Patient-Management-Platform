<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $module;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $module = 'users')
    {
        $this->user = $user;
        $this->module = $module;
    }

    public function build()
    {
        if($this->module == 'doctor'){
            return $this->view('mail.verify_doctor')
                        ->subject('Welcome to Mednero! Your Registration is Under Review')
                        ->with([
                            'verificationUrl' => url('/'.$this->module.'/verify-email/'.$this->user->email_verification_token),
                            'user'=>$this->user
                        ]);
        }else{
            return $this->view('mail.verify')
                        ->subject('Welcome to Mednero ! Your Clinic/ Hospital Registration is Under Review')
                        ->with([
                            'verificationUrl' => url('/'.$this->module.'/verify-email/'.$this->user->email_verification_token),
                        ]);
        }
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Verify Email',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
