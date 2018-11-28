<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class OnBoardingStepMFA extends Page
{
    private $token;
    private $email;

    /**
     * OnBoardingStepPassword constructor.
     * @param $token
     * @param $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('user.onboarding.password', [$this->token, encrypt($this->email)]);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertSee('Authenticator Secret')
            ->assertSee('I confirm I have configured my authenticator app as required and that this is the only time I will see this information.');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@mfa-copied-confirmation' => '.custom-checkbox label',
            '@go-to-login' => '.submitsForm',
        ];
    }
}
