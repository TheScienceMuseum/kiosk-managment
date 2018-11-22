<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class PackagesCreatePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/packages/create';
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
            ->assertSee(__('packages.create'));
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@package-name-input' => 'input[name="name"]',
            '@package-create-submit' => 'form button[type="submit"]',
        ];
    }
}
