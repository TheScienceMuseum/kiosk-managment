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
            ->assertSee('Users');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@pagination-next-page' => '#pagination-next-page',
            '@pagination-prev-page' => '#pagination-prev-page',
            '@users-filter-toggle-button' => '.card-header .row .col .btn-dark',
            '@users-filter-apply-button' => 'form .float-right.row button',
            '@users-filter-reset-button' => 'form .float-right.row a.btn-outline-danger',
            '@create-user-button' => '.card-header .row .col button.btn-success',
            '@view-first-user-button' => '.list-group-item .row .col-12 a[href="/admin/users/1"] button',
            '@role-select-filter' => 'form div div div div div div div div input',
        ];
    }
}
