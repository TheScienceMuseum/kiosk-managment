<?php

namespace Tests\Feature\UserManagement;

use App\User;
use Spatie\Permission\Models\Role;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SuspendUsersTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testSuspendingAUserActingAsADeveloperSucceeds()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsDeveloper()
            ->delete('/api/user/' . $user->id)
        ;

        $response->assertStatus(204);
    }

    public function testSuspendingAUserActingAsAnAdminSucceeds()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsAdmin()
            ->delete('/api/user/' . $user->id)
        ;

        $response->assertStatus(204);
    }

    public function testSuspendingAUserActingAsATechAdminFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsTechAdmin()
            ->delete('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }

    public function testSuspendingAUserActingAsAContentEditorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentEditor()
            ->delete('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }

    public function testSuspendingAUserActingAsAContentAuthorFails()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsContentAuthor()
            ->delete('/api/user/' . $user->id)
        ;

        $response->assertStatus(403);
    }
}
