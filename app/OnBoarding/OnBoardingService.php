<?php

namespace App\OnBoarding;

use App\Mail\UserOnBoardingInviteMailable;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class OnBoardingService
{
    static public function startOnBoarding(User $user)
    {
        // Clear the users password and MFA Token
        $user->password = null;
        $user->mfa_secret = null;
        $user->save();

        // Create a password reset token to be used for the on boarding process
        $token = Password::broker()->createToken($user);

        // Email the user with a link to start their on boarding process
        $mailable = new UserOnBoardingInviteMailable($user, $token);
        Mail::to($user)->queue($mailable);

        return $mailable;
    }

    static public function getOnBoardingFromTokenAndEncryptedEmail(string $token, string $encryptedEmail)
    {
        $user = User::whereEmail(decrypt($encryptedEmail))->firstOrFail();

        if (! Password::broker()->tokenExists($user, $token)) {
            abort(401);
        }

        return (object) [
            'user' => $user,
            'token' => $token,
            'step' => $user->password === null ?
                'password' :
                (
                    $user->mfa_secret === null ?
                        'mfa' :
                        null
                ),
        ];
    }
}