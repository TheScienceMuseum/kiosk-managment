<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class UsersIndexPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/users';
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
            ->assertSee('JP Developer');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@pagination-next-page' => 'nav ul li a[href="/admin/users?page=2"]',
            '@pagination-prev-page' => 'nav ul li a[href="/admin/users?page=1"]',
        ];
    }
}
