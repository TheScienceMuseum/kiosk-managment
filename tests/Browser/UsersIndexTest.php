<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;

class UsersIndexTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    public function testPaginationButtonsFunctionCorrectly()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $usersIndexPage2 = $usersIndexPage->pause(1000)
                ->click('@pagination-next-page')
                ->on(new UsersIndexPage());

            $usersIndexPage2->pause(1000)
                ->assertQueryStringHas('page', '2')
                ->assertSee('Test User')
                ->click('@pagination-prev-page')
                ->on(new UsersIndexPage());
        });
    }
}
