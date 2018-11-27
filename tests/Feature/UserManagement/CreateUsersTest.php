<?php

namespace Tests\Feature\UserManagement;

use App\Mail\UserRegistrationInviteMailable;
use Illuminate\Support\Facades\Mail;
use Tests\ActsAs;
use Tests\CreatesUsers;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CreateUsersTest extends TestCase
{
    use ActsAs, CreatesUsers, ResetsDatabase, WithFaker;

    public function testCreatingANewUserWithoutAllFieldsFailsWithAppropriateMessages()
    {
        $response = $this->actingAsDeveloper()
            ->postJson('/api/user', [])
        ;

        $response->assertStatus(422)
            ->assertJson([
                'message' => true,
                'errors' => [
                    'name' => true,
                    'email' => true,
                    'send_invite' => true,
                    'roles' => true,
                ]
            ])
        ;
    }

    public function testCreatingANewUserWithoutAnInvitationEmailDoesNotSendAnEmail()
    {
        Mail::fake();

        $response = $this->actingAsDeveloper()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => false,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(201);

        Mail::assertNotQueued(UserRegistrationInviteMailable::class);
    }

    public function testCreatingANewUserWithAnInvitationEmailDoesSendAnEmailToThatUser()
    {
        Mail::fake();

        $response = $this->actingAsDeveloper()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(201);

        Mail::assertQueued(UserRegistrationInviteMailable::class);
    }

    public function testCreatingANewUserActingAsADeveloperSucceeds()
    {
        $response = $this->actingAsDeveloper()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(201);
    }

    public function testCreatingANewUserActingAsAnAdminSucceeds()
    {
        $response = $this->actingAsAdmin()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(201);
    }

    public function testCreatingANewUserActingAsATechAdminFails()
    {
        $response = $this->actingAsTechAdmin()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(403);
    }

    public function testCreatingANewUserActingAsAContentAuthorFails()
    {
        $response = $this->actingAsContentAuthor()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(403);
    }

    public function testCreatingANewUserActingAsAContentEditorFails()
    {
        $response = $this->actingAsContentEditor()
            ->postJson('/api/user', [
                'name' => $this->faker->unique()->name,
                'email' => $this->faker->unique()->email,
                'send_invite' => true,
                'roles' => ['content author'],
            ])
        ;

        $response->assertStatus(403);
    }
}
