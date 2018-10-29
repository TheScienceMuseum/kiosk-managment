<?php

namespace Tests\Feature\UserManagement;

use App\User;
use Spatie\Permission\Models\Role;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ShowUserInformationTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testShowingInformationAboutAUserAsADeveloperSucceeds()
    {
        $user = $this->createUserContentAuthor([
            'name' => 'test show',
            'email' => 'test@show.com',
        ]);

        $response = $this->actingAsDeveloper()
            ->get('/api/user/' . $user->id)
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test show',
                    'email' => 'test@show.com',
                    'roles' => [
                        ['name' => 'content author'],
                    ],
                ],
            ])
        ;
    }

    public function testShowingInformationAboutAUserAsAnAdminSucceeds()
    {
        $user = $this->createUserContentAuthor([
            'name' => 'test show',
            'email' => 'test@show.com',
        ]);

        $response = $this->actingAsAdmin()
            ->get('/api/user/' . $user->id)
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test show',
                    'email' => 'test@show.com',
                    'roles' => [
                        ['name' => 'content author'],
                    ],
                ],
            ])
        ;
    }

    public function testShowingInformationAboutAUserAsATechAdminFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsTechAdmin()
            ->get('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }

    public function testShowingInformationAboutAUserAsAContentEditorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentEditor()
            ->get('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }

    public function testShowingInformationAboutAUserAsAContentAuthorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentAuthor()
            ->get('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }
}
