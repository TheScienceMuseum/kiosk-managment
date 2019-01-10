<?php

namespace Tests;


use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\LoginSecondFactorConfirmationComponent;

trait LoginWithMFA
{
    /**
     * @param Browser $browser
     * @param User $user
     * @return Browser
     */
    public function loginAs(Browser $browser, User $user)
    {
        return $browser->loginAs($user)
            ->visit('/')
            ->type('one_time_password', \MultiFactorAuth::getCurrentOtp($user->mfa_secret))
            ->click('button[type=submit]');
    }
}