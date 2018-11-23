<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;
use Tests\Browser\Components\LoginSecondFactorConfirmationComponent;

class UsersFilterTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    /**
     * @throws \Throwable
     */
    public function testUsersCanBeFilteredByName()
    {

        $this->browse(function (Browser $browser) {

            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
                ->pause(500)
                ->assertSee('Apply Filters')
                ->type('name', 'JP Developer')
                ->click('@users-filter-apply-button')
                ->on(new UsersIndexPage());

            $filteredUsersIndexPage->waitForText('View')
                ->assertSeeIn('ul.list-group', 'JP Developer')
                ->assertDontSeeIn('ul.list-group', 'developer')
                ->assertDontSeeIn('ul.list-group', 'admin')
                ->assertDontSeeIn('ul.list-group', 'tech admin')
                ->assertDontSeeIn('ul.list-group', 'content author')
                ->assertDontSeeIn('ul.list-group', 'content editor')
                ->logout();

        });
    }

    /**
     * @throws \Throwable
     */
    public function testUsersCanBeFilteredByEmail()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
                ->pause(500)
                ->assertSee('Apply Filters')
                ->type('email', 'joipolloi')
                ->click('@users-filter-apply-button')
                ->on(new UsersIndexPage());

            $filteredUsersIndexPage->waitForText('View')
                ->assertSeeIn('ul.list-group', 'JP Developer')
                ->assertDontSeeIn('ul.list-group', 'example');
        });
    }

    // NOT WORKING ATM - TODO FIX
//    /**
//     * @throws \Throwable
//     */
//    public function testUsersCanBeFilteredByRole()
//    {
//        $this->browse(function (Browser $browser) {
//            $usersIndexPage = $this->loginAs($browser, User::first())
//                ->resize(1920, 1080)
//                ->visit(new UsersIndexPage());
//
//            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
//                ->pause(500)
//                ->assertSee('Apply Filters')
//                ->select('role', 'Developer')
//                ->click('@users-filter-apply-button')
//                ->on(new UsersIndexPage());
//
//            $filteredUsersIndexPage->click('@users-filter-toggle-button')
//                ->assertQueryStringHas('role', 'Developer')
//                ->pause(10000)
//                ->assertSeeIn('ul.list-group', 'JP Developer');
//        });
//    }

    public function testResetUsersFilter()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->resize(1920, 1080)
                ->visit(new UsersIndexPage());

            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
                ->pause(500)
                ->assertSee('Apply Filters')
                ->type('name', 'admin')
                ->click('@users-filter-apply-button')
                ->on(new UsersIndexPage());

            $usersIndexPage = $filteredUsersIndexPage->waitForText('View')
                ->assertSeeIn('ul.list-group', 'admin')
                ->assertDontSeeIn('ul.list-group', 'JP Developer')
                ->click('@users-filter-toggle-button')
                ->pause(500)
                ->assertSee('Apply Filters')
                ->click('@users-filter-reset-button')
                ->on(new UsersIndexPage());

            $usersIndexPage->waitForText('View')
                ->assertSee('JP Developer');
        });
    }
}
