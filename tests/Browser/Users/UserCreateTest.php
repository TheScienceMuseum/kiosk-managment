<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\Error401Page;
use Tests\Browser\Pages\UsersCreatePage;
use Tests\Browser\Pages\UsersIndexPage;
use Tests\Browser\Pages\UsersShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\LoginWithMFA;
use Tests\ResetsDatabaseInDusk;

class UserCreateTest extends DuskTestCase
{
    use ResetsDatabaseInDusk, LoginWithMFA;

    public function testCreateUserPageRedirectsTo403WhenUnauthorised()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAs($browser, User::find(13))
                ->visit('/admin/users/edit/create')
                ->on(new Error401Page());

        });
    }

    public function testCreatingAUser()
    {
        $this->browse(function (Browser $browser) {
            $usersIndexPage = $this->loginAs($browser, User::first())
                ->visit(new UsersIndexPage());

            $usersCreatePage = $usersIndexPage->click('@create-user-button')
                ->on(new UsersCreatePage());

            // Get highest user index - new user will have 1 greater
            $id = User::all()->last()->id;

            $newUserShowPage = $usersCreatePage->type('name', 'Test User')
                ->type('email', 'test@example.com')
                ->select('roles', 'admin')
                ->click('@add-role-button')
                ->waitFor('@role-badge')
                ->assertSeeIn('span.badge', 'Admin')
                ->click('@add-user-button')
                ->waitForLocation('/admin/users/' . ($id + 1))
                ->on(new UsersShowPage($id + 1));

            $newUserShowPage->waitForText('Name: Test User')
                ->assertSee('Email: test@example.com')
                ->assertSee('Admin');
        });
    }
}
