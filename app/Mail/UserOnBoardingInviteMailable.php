<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserOnBoardingInviteMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $registeredUser;

    /**
     * @var string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param User $registeredUser
     * @param string $token
     */
    public function __construct(User $registeredUser, string $token)
    {
        $this->registeredUser = $registeredUser;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ': Invitation to set up account')
            ->markdown('mail.user-registration-invite', [
                'user' => $this->registeredUser,
                'token' => $this->token,
            ]);
    }
}
