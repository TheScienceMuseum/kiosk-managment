<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\UsersShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;
use Tests\Browser\Pages\UsersIndexPage;

class UsersShowTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    /**
     * @throws \Throwable
     */
    public function testShowUserInformationPage()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $userShowPage = $usersIndexPage->waitForText('View')
                ->click('@view-user-button')
                ->on(new UsersShowPage());

            $userShowPage->pause(200)
                ->assertSee('JP Developer')
                ->assertSee('dev@joipolloi.com');
        });
    }
}
