<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\Error401Page;
use Tests\Browser\Pages\UsersShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;
use Tests\Browser\Pages\UsersIndexPage;

class UsersShowPageTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    public function testShowUserPageRedirectsTo403WhenUnauthorised()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, User::find(13))
                ->visit('/admin/users/1')
                ->on(new Error401Page());

        });
    }
    /**
     * @throws \Throwable
     */
    public function testShowUserInformationPage()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $userShowPage = $usersIndexPage->waitForText('View')
                ->click('@view-first-user-button')
                ->on(new UsersShowPage(1));

            $userShowPage->waitForText('JP Developer')
                ->assertSee('Email: dev@joipolloi.com');
        });
    }

    public function testDeleteUserButton()
    {
        $this->browse(function (Browser $browser) {
            $usersShowPage = $this->loginAs($browser, User::first())
                ->visit(new UsersShowPage(10));

            $usersIndexPage = $usersShowPage->click('@delete-user-button')
                ->waitForLocation('/admin/users')
                ->on(new UsersIndexPage());

            $usersIndexPage->waitForText('View')
                ->assertSeeIn('ul.list-group', 'content author');
        });
    }

//    public function testDeleteUserButtonDoesntWorkForLoggedInUser()
//    {
//
//    }
}
