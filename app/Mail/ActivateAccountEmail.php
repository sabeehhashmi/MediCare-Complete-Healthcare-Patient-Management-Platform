<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivateAccountEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $login_route;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $login_route)
    {
        $this->user = $user;
        $this->login_route = $login_route;
    }

    public function build()
    {
        $loginUrl = url('/'.$this->login_route ?? 'website');
        return $this->view('mail.account-active')
                    ->subject('Account Activation')
                    ->with([
                        'user' => $this->user, 'loginUrl' => $loginUrl
                    ]);
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
