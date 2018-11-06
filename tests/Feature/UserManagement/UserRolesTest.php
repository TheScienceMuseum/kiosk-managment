<?php

namespace Tests\Feature\UserManagement;

use App\User;
use Spatie\Permission\Models\Role;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UserRolesTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testGettingAListOfAllValidUserRoles()
    {
        $user = $this->createUserContentAuthor([]);

        $response = $this->actingAsDeveloper()
            ->get('/api/user/role')
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['name' => 'developer'],
                    ['name' => 'admin'],
                    ['name' => 'tech admin'],
                    ['name' => 'content author'],
                    ['name' => 'content editor'],
                ],
            ])
        ;
    }
}
