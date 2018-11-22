<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class PackagesIndexPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/packages';
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
            ->assertSee('Running on 1 kiosks');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@show-filters-button' => '.card .card-header .btn-group .btn[data-target="#collapsible-filters"]',
            '@apply-filters-button' => '#collapsible-filters form .btn[type="submit"]',
            '@reset-filters-button' => '#collapsible-filters form .btn[type="reset"]',
            '@create-package-button' => '.card .card-header .btn-group a.btn',
        ];
    }
}
