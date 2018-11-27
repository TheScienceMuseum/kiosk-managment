<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class UsersShowPage extends Page
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/users/' . $this->userId;
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
            ->assertSee('Roles');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@view-first-user-button' => '.list-group-item .row .col a[href=/admin/users/1] button',
            '@delete-user-button' => '.card-header .row .col button.btn-danger',
        ];
    }
}
