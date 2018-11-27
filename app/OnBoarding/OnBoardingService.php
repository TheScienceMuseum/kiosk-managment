<?php

namespace App\OnBoarding;

use App\Mail\UserOnBoardingInviteMailable;
use Illuminate\Foundation\Auth\User;
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
        Mail::to($user)
            ->queue(new UserOnBoardingInviteMailable($user, $token));
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