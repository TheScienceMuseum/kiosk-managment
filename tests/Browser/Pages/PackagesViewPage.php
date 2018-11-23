<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class PackagesViewPage extends Page
{
    private $packageId;

    public function __construct($packageId)
    {
        $this->packageId = $packageId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/packages/' . $this->packageId;
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
            ->assertSee('Editing Package:');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@create-version-button' => '.card .card-header form button[type="submit"]',
        ];
    }
}
