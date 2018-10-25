<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use ActsAs, ResetsDatabase, WithFaker;

    public function testCreatingAUserWithMissingDataFails()
    {
        $response = $this->actingAsAdmin()
            ->postJson('/api/user', [])
        ;

        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email address field is required.'],
                    'send_invite' => ['The send invite field is required.'],
                ]
            ])
        ;
    }

    public function testCreatingAUserWithInvalidDataFails()
    {
        $response = $this->actingAsAdmin()
            ->postJson('/api/user', [
                'name' => 123,
                'email' => 'not-an-email-address',
                'send_invite' => 'not-a-boolean',
            ])
        ;

        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name must be a string.'],
                    'email' => ['The email address must be a valid email address.'],
                    'send_invite' => ['The send invite field must be true or false.'],
                ]
            ])
        ;
    }

    public function testCreatingAUserWithoutRoles()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $send_invite = false;

        $response = $this->actingAsAdmin()
            ->postJson('/api/user', [
                'name' => $name,
                'email' => $email,
                'send_invite' => $send_invite,
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $name,
                    'email' => $email,
                    'roles' => [],
                ]
            ])
        ;
    }

    public function testCreatingAUserWithRoles()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $send_invite = false;

        $response = $this->actingAsAdmin()
            ->postJson('/api/user', [
                'name' => $name,
                'email' => $email,
                'send_invite' => $send_invite,
                'roles' => [
                    'developer',
                    'admin',
                    'content author',
                    'content editor',
                    'tech admin',
                ],
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $name,
                    'email' => $email,
                    'roles' => [
                        ['name' => 'developer'],
                        ['name' => 'admin'],
                        ['name' => 'content author'],
                        ['name' => 'content editor'],
                        ['name' => 'tech admin'],
                    ],
                ]
            ])
        ;
    }
}
