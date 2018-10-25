<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class LoginSecondFactorConfirmationComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#mfa_confirmation_dialog';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector())
            ->assertVisible('@input-opt')
            ->assertVisible('@submit');
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@input-opt' => '#one_time_password',
            '@submit' => 'button[type="submit"]',
        ];
    }

    public function submitOneTimePassword($browser, $oneTimePassword)
    {
        $browser->type('@input-opt', $oneTimePassword)
            ->click('@submit')
        ;
    }
}
