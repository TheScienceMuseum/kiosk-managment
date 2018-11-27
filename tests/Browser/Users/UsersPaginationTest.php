<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;

class UsersPaginationTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    /**
     * @throws \Throwable
     */
    public function testPaginationButtonsFunctionCorrectlyWithNoFilter()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $usersIndexPage2 = $usersIndexPage->waitForText('View')
                ->click('@pagination-next-page')
                ->on(new UsersIndexPage());

            $usersIndexPage2->waitForText('View')
                ->assertQueryStringHas('page', '2')
                ->assertSee('Test User')
                ->click('@pagination-prev-page')
                ->on(new UsersIndexPage());
        });
    }

    public function testPaginationButtonsFunctionCorrectlyWithFilterApplied()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $filteredUsersIndexPage = $usersIndexPage->click('@users-filter-toggle-button')
                ->waitForText('Apply Filters')
                ->type('email', 'example')
                ->click('@users-filter-apply-button')
                ->on(new UsersIndexPage());

            $filteredUsersIndexPage2 = $filteredUsersIndexPage->waitForText('View')
                ->assertQueryStringHas('email', 'example')
                ->assertSeeIn('ul.list-group', 'admin')
                ->click('@pagination-next-page')
                ->on(new UsersIndexPage());

            $filteredUsersIndexPage2->waitForText('View')
                ->assertQueryStringHas('page', '2')
                ->assertQueryStringHas('email', 'example')
                ->assertSeeIn('ul.list-group', 'content editor')
                ->click('@pagination-prev-page')
                ->on(new UsersIndexPage());
        });
    }
}
