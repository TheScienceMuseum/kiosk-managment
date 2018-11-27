<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class UsersCreatePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/users/edit/create';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Create a new user');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@add-role-button' => '.card-body form .row .col .form-group button',
            '@add-user-button' => '.card-body form button.btn-block',
            '@role-badge' => '.card-body form .row .col span.badge',
        ];
    }
}
