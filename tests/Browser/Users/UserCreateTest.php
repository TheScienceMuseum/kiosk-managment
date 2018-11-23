<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\UsersCreatePage;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;

class UserCreateTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    public function testCreateUserButtonNotVisibleWhenUnauthorised()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::find(13))
                ->resize(1920, 1080)
                ->visit(new UsersIndexPage());

            $usersIndexPage->assertMissing('@create-user-button');
        });
    }

    public function testCreatingAUser()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->resize(1920, 1080)
                ->visit(new UsersIndexPage());

            $usersCreatePage = $usersIndexPage->click('@create-user-button')
                ->on(new UsersCreatePage());
        });
    }
}
