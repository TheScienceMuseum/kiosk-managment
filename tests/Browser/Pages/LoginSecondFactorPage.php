<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class LoginSecondFactorPage extends Page
{
    use DatabaseMigrations;

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login/authorize';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertSee('Multi-Factor Authentication')
            ->assertSee('Auth Code')
            ->assertSee('Enter an authentication code from your app.');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@mfa-secret' => '#mfa_secret',
            '@login' => '#login',
        ];
    }
}
