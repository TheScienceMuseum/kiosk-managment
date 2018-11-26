<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\Error401Page;
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
                ->visit('/admin/users/')
                ->on(new Error401Page());
        });
    }

    public function testIndexUserPage()
    {
         $this->browse(function (Browser $browser) {
            $userIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());
            $userIndexPage->waitForText('View')
                ->assertSee('developer')
                ->assertSee('admin')
                ->assertSee('tech admin');
        });
    }
}
