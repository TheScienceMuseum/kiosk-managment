<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Support\Facades\Log;
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
//  TODO: Figure out why this doesn't work
//    /**
//     * @throws \Throwable
//     */
//    public function testUsersCanBeFilteredByRole()
//    {
//        $this->browse(function (Browser $browser) {
//            $usersIndexPage = $this->loginAs($browser, User::first())
//                ->visit(new UsersIndexPage());
//
//            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
//                ->pause(500)
//                ->assertSee('Apply Filters')
//                ->select('role', 'Developer')
//                ->click('@users-filter-apply-button')
//                ->on(new UsersIndexPage());
//
//            $filteredUsersIndexPage->assertQueryStringHas('role', 'Developer')
//                ->click('@users-filter-toggle-button')
//                ->waitForText('View')
//                ->assertSeeIn('ul.list-group', 'JP Developer');
//        });
//    }

    public function testResetUsersFilter()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
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
                ->assertSee('Reset Filters')
                ->click('@users-filter-reset-button')
                ->on(new UsersIndexPage());

            $usersIndexPage->waitForText('View')
                ->assertSee('JP Developer');
        });
    }
}
