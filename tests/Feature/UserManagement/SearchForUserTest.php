<?php

namespace Tests\Feature\UserManagement;

use App\User;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SearchForUserTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testSearchingForAUserByName()
    {
        $response = $this->actingAsDeveloper()
            ->get('/api/user?' . http_build_query([
                    'filter' => [
                        'name' => 'tech admin',
                    ],
                ]))
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => true,
                'links' => true,
                'meta' => true,
            ])
            ->assertSee('tech admin')
        ;
    }

    public function testSearchingForAUserByEmail()
    {
        $user = factory(User::class)->create([
            'email' => 'testing@search.com',
        ]);
        $user->assignRole('content author');

        $response = $this->actingAsDeveloper()
            ->get('/api/user?' . http_build_query([
                    'filter' => [
                        'email' => 'testing@search.com',
                    ],
                ]))
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => true,
                'links' => true,
                'meta' => true,
            ])
            ->assertSee('testing@search.com')
        ;
    }

    public function testSearchingForAUserByRole()
    {
        $user = factory(User::class)->create([
            'email' => 'testing@search.com',
        ]);
        $user->assignRole('content author');

        $response = $this->actingAsDeveloper()
            ->get('/api/user?' . http_build_query([
                    'filter' => [
                        'roles' => ['content author'],
                    ],
                ]))
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => true,
                'links' => true,
                'meta' => true,
            ])
            ->assertSee('testing@search.com')
        ;
    }
}
