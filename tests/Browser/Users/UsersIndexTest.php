<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\Error403Page;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;

class UsersIndexTest extends DuskTestCase
{   use ResetsDatabaseInDusk, LoginWithMFA;

    public function testIndexUserPageRedirectsTo403WhenUnauthorised()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, User::find(13))
                ->resize(1920, 1080)
                ->visit('/admin/users/1')
                ->on(new Error403Page());
        });
    }

    public function testIndexUserPage()
    {
         $this->browse(function (Browser $browser) {
            $userIndexPage = $this->loginAs($browser, User::first())
                ->resize(1920, 1080)
                ->visit(new UsersIndexPage());
            $userIndexPage->waitForText('View')
                ->assertSee('developer')
                ->assertSee('admin')
                ->assertSee('tech admin');
        });
    }
}
