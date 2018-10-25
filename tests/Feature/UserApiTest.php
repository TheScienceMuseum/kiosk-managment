<?php

namespace Tests\Feature;

use App\User;
use Tests\ResetsDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use ResetsDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->testUser = User::whereEmail('dev@joipolloi.com')->first();
    }

    public function testUserApiIndexUnfiltered()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => true,
            'links' => true,
            'meta' => true,
        ]);
    }

    public function testUserApiIndexFilteredByEmailDev()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user?filter[email]=dev@joipolloi.com');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => true,
            'links' => true,
            'meta' => true,
        ]);
    }

    public function testUserApiIndexFilteredByName()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user?filter[name]=Joi Polloi');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => true,
            'links' => true,
            'meta' => true,
        ]);
    }

    public function testUserApiIndexFilteredByRoleDeveloper()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user?filter[role]=developer');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => true,
            'links' => true,
            'meta' => true,
        ]);
    }

    public function testUserApiShow()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user/' . $this->testUser->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $this->testUser->name,
                'email' => $this->testUser->email,
                'path' => config('app.url') . '/api/user/' . $this->testUser->id,
            ],
        ]);
    }

    public function testUserApiStore()
    {
        $fakeUser = factory(User::class)->make();

        $response = $this->actingAs($this->testUser, 'api')
            ->postJson('/api/user', [
                'name' => $fakeUser->name,
                'email' => $fakeUser->email,
                'send_invite' => false,
                'roles' => ['developer'],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $fakeUser->name,
                    'email' => $fakeUser->email,
                    'roles' => [['name' => 'developer']],
                    'path' => true,
                ],
            ]);

        $createdUser = json_decode($response->getContent());

        $response = $this->actingAs($this->testUser, 'api')
            ->get($createdUser->data->path);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $fakeUser->name,
                    'email' => $fakeUser->email,
                ],
            ]);
    }

    public function testUserApiUpdate()
    {
        $updatingUser = User::find(2);

        $response = $this->actingAs($this->testUser, 'api')
            ->putJson('/api/user/' . $updatingUser->id, [
                'name' => 'test updating user',
                'email' => 'test-updating-user@example.com',
                'roles' => ['developer', 'content author'],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test updating user',
                    'email' => 'test-updating-user@example.com',
                    'roles' => [
                        ['name' => 'developer'],
                        ['name' => 'content author'],
                    ],
                ],
            ]);
    }

    public function testUserApiDestroy()
    {
        $destroyingUser = User::find(3);

        $response = $this->actingAs($this->testUser, 'api')
            ->delete('/api/user/' . $destroyingUser->id);

        $response->assertStatus(204);

        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user/' . $destroyingUser->id);

        $response->assertStatus(404);
    }

    public function testUserRolesApiIndex()
    {
        $response = $this->actingAs($this->testUser, 'api')
            ->get('/api/user/role');

        $response->assertStatus(200)
            ->assertExactJson([
                "data" => [
                    ["name" => "developer"],
                    ["name" => "admin"],
                    ["name" => "content author"],
                    ["name" => "content editor"],
                    ["name" => "tech admin"],
                ],
                "links" => [
                    "first" => "http://kiosk-manager.test/api/user/role?page%5Bnumber%5D=1",
                    "last" => "http://kiosk-manager.test/api/user/role?page%5Bnumber%5D=1",
                    "prev" => null,
                    "next" => null,
                ],
                "meta" => [
                    "current_page" => 1,
                    "from" => 1,
                    "last_page" => 1,
                    "path" => "http://kiosk-manager.test/api/user/role",
                    "per_page" => 10,
                    "to" => 5,
                    "total" => 5,
                ]
            ]);
    }
}
