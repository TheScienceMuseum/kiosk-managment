<?php

namespace Tests\Feature\UserManagement;

use App\User;
use Spatie\Permission\Models\Role;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UpdateUsersTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testUpdatingAUserWithoutAllFieldsFailsWithAppropriateMessages()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsDeveloper()
            ->putJson('/api/user/' . $user->id, [])
        ;

        $response->assertStatus(422)
            ->assertJson([
                'message' => true,
                'errors' => [
                    'name' => true,
                    'email' => true,
                    'roles' => true,
                ]
            ])
        ;
    }

    public function testUpdatingAUserActingAsADeveloperSucceeds()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsDeveloper()
            ->putJson('/api/user/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function (Role $role) {
                    return $role->name;
                }),
            ])
        ;

        $response->assertStatus(200);
    }

    public function testUpdatingAUserActingAsAnAdminSucceeds()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsAdmin()
            ->putJson('/api/user/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function (Role $role) {
                    return $role->name;
                }),
            ])
        ;

        $response->assertStatus(200);
    }

    public function testUpdatingAUserActingAsATechAdminFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsTechAdmin()
            ->putJson('/api/user/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function (Role $role) {
                    return $role->name;
                }),
            ])
        ;

        $response->assertStatus(403);
    }

    public function testUpdatingAUserActingAsAContentEditorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentEditor()
            ->putJson('/api/user/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function (Role $role) {
                    return $role->name;
                }),
            ])
        ;

        $response->assertStatus(403);
    }

    public function testUpdatingAUserActingAsAContentAuthorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentAuthor()
            ->putJson('/api/user/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function (Role $role) {
                    return $role->name;
                }),
            ])
        ;

        $response->assertStatus(403);
    }
}
