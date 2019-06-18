<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Components\LoginSecondFactorConfirmationComponent;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\LoginSecondFactorPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\ResetsDatabaseInDusk;

class LoginTest extends DuskTestCase
{
    use ResetsDatabaseInDusk;

    protected $mfaSecret = 'QGXDUURSP6V7G6PG';

    public function testLoginOfARegisteredUserShowsMultiFactorAuth()
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->logout()->visit(new LoginPage());

            $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new LoginSecondFactorPage());
        });
    }

    public function testLoginOfARegisteredUserWithIncorrectSecondFactorFails()
    {
        $user = User::first();
        $user->mfa_secret = $this->mfaSecret;
        $user->save();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->logout()->visit(new LoginPage());

            $loginSecondFactorPage = $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new LoginSecondFactorPage())
            ;

            $loginSecondFactorPage->within(new LoginSecondFactorConfirmationComponent(), function (Browser $browser) {
                $browser->submitOneTimePassword('000000');
            });

            $browser->assertSee('The \'One Time Password\' typed was wrong.');
        });
    }

    public function testLoginOfARegisteredUserWithCorrectSecondFactorSucceeds()
    {
        $user = User::first();
        $user->mfa_secret = $this->mfaSecret;
        $user->save();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->logout()->visit(new LoginPage());

            $loginSecondFactorPage = $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new LoginSecondFactorPage())
            ;

            $loginSecondFactorPage->within(new LoginSecondFactorConfirmationComponent(), function (Browser $browser) {
                $browser->submitOneTimePassword(\MultiFactorAuth::getCurrentOtp($this->mfaSecret));
            });

            $browser->assertDontSee('The \'One Time Password\' typed was wrong.');
        });
    }
}
