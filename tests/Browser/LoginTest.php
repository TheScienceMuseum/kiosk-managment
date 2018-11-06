<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Components\LoginSecondFactorConfirmationComponent;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\LoginSecondFactorSetupPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\ResetsDatabaseInDusk;

class LoginTest extends DuskTestCase
{
    use ResetsDatabaseInDusk;

    protected $mfaSecret = 'QGXDUURSP6V7G6PG';

    public function testFirstLoginOfARegisteredUserShowsMultiFactorAuthRegistration()
    {
        $user = User::first();
        $user->mfa_secret = null;
        $user->save();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->visit(new LoginPage);

            $loginSecondFactorSetupPage = $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new LoginSecondFactorSetupPage());

            $this->mfaSecret = $loginSecondFactorSetupPage->text('@mfa-secret');

            $loginSecondFactorSetupPage->click('@login')
                ->on(new LoginPage());
        });
    }

    public function testSecondLoginOfARegisteredUserWithIncorrectSecondFactorFails()
    {
        $user = User::first();
        $user->mfa_secret = $this->mfaSecret;
        $user->save();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->logout()->visit(new LoginPage());

            $homePage = $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new HomePage())
            ;

            $homePage->within(new LoginSecondFactorConfirmationComponent(), function (Browser $browser) {
                $browser->submitOneTimePassword('000000');
            });

            $browser->assertSee('The \'One Time Password\' typed was wrong.');
        });
    }

    public function testSecondLoginOfARegisteredUserWithCorrectSecondFactorSucceeds()
    {
        $user = User::first();
        $user->mfa_secret = $this->mfaSecret;
        $user->save();

        $this->browse(function (Browser $browser) use ($user) {
            $loginPage = $browser->logout()->visit(new LoginPage());

            $homePage = $loginPage->type('@input-email', $user->email)
                ->type('@input-password', '123qweasd')
                ->check('@input-remember', true)
                ->click('@submit-login')
                ->on(new HomePage())
            ;

            $homePage->within(new LoginSecondFactorConfirmationComponent(), function (Browser $browser) {
                $browser->submitOneTimePassword(\MultiFactorAuth::getCurrentOtp($this->mfaSecret));
            });

            $browser->assertDontSee('The \'One Time Password\' typed was wrong.');
        });
    }
}
