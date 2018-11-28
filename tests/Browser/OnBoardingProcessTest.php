<?php

namespace Tests\Browser;

use App\Mail\UserOnBoardingInviteMailable;
use App\OnBoarding\OnBoardingService;
use App\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Dusk\Browser;
use Tests\ActsAs;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\OnBoardingStepMFA;
use Tests\Browser\Pages\OnBoardingStepPassword;
use Tests\DuskTestCase;
use Tests\ResetsDatabaseInDusk;

class OnBoardingProcessTest extends DuskTestCase
{
    use ActsAs, ResetsDatabaseInDusk;

    public function testCompletingTheOnBoardingProcessAsAnExistingUser()
    {
        Mail::fake();

        $email = OnBoardingService::startOnBoarding(User::first());

        Mail::assertQueued(UserOnBoardingInviteMailable::class);

        $this->browse(function (Browser $browser) use ($email) {
            $passwordStepUrl = route('user.onboarding.password', [$email->token, encrypt($email->user->email)]);

            $passwordStep = $browser->visit($passwordStepUrl)
                ->on(new OnBoardingStepPassword($email->token, $email->user->email));

            $mfaStep = $passwordStep->type('@password', '!@£qweASD')
                ->type('@password-confirm', '!@£qweASD')
                ->click('@password-form-submission')
                ->on(new OnBoardingStepMFA($email->token, $email->user->email));

            $mfaStep->click('@mfa-copied-confirmation')
                ->click('.card-header')
                ->pause(1000)
                ->click('@go-to-login')
                ->on(new LoginPage());
        });
    }
}
