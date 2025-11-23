<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user data.
     *
     * @var array
     */
    public $userData;

    /**
     * Create a new message instance.
     *
     * @param array $userData
     * @return void
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->userData['subject'])
                    ->markdown('emails.welcome', [
                        'name' => $this->userData['name'],
                        'username' => $this->userData['username'],
                        'password' => $this->userData['password'],
                        'settingsUrl' => $this->userData['settingsUrl'],
                        'helpCenterUrl' => $this->userData['helpCenterUrl'],
                        'termsUrl' => $this->userData['termsUrl'],
                        'privacyUrl' => $this->userData['privacyUrl'],
                        'cs' => $this->userData['cs'] ?? '',
                    ]);
    }
}