<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\PackagesCreatePage;
use Tests\Browser\Pages\PackagesIndexPage;
use Tests\Browser\Pages\PackagesViewPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\ResetsDatabaseInDusk;
use Tests\LoginWithMFA;

class PackageCreateTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    public function testCreatingANewPackage()
    {
        $this->browse(function (Browser $browser) {
            $packagesIndexPage = $this->loginAs($browser, User::first())
                ->visit(new PackagesIndexPage());

            $packagesCreatePage = $packagesIndexPage->click('@create-package-button')
                ->on(new PackagesCreatePage());

            $packagesViewPage = $packagesCreatePage->type('@package-name-input', 'TestKioskPackage')
                ->click('@package-create-submit')
                ->on(new PackagesViewPage(2));
        });
    }
}
