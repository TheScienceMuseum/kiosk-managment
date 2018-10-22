<?php

namespace Tests\Feature;

use App\User;
use Tests\ResetsDatabase;
use Tests\TestCase;

class UserManagerPermissionsTest extends TestCase
{
    use ResetsDatabase;

    protected $userDeveloper;
    protected $userAdmin;
    protected $userTechAdmin;
    protected $userContentAuthor;
    protected $userContentEditor;

    protected function setUp()
    {
        parent::setUp();

        $this->userDeveloper = User::role('developer')->first();
        $this->userAdmin = User::role('admin')->first();
        $this->userTechAdmin = User::role('tech admin')->first();
        $this->userContentAuthor = User::role('content author')->first();
        $this->userContentEditor = User::role('content editor')->first();
    }

    public function testThatTechAdminUsersCannotUpdateTheirOwnRoles()
    {
        $response = $this->actingAs($this->userTechAdmin, 'api')
            ->putJson('/api/user/' . $this->userTechAdmin->id, [
                'name' => $this->userTechAdmin->name,
                'email' => $this->userTechAdmin->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(403);
    }

    public function testThatContentAuthorUsersCannotUpdateTheirOwnRoles()
    {
        $response = $this->actingAs($this->userContentAuthor, 'api')
            ->putJson('/api/user/' . $this->userContentAuthor->id, [
                'name' => $this->userContentAuthor->name,
                'email' => $this->userContentAuthor->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(403);
    }

    public function testThatContentEditorUsersCannotUpdateTheirOwnRoles()
    {
        $response = $this->actingAs($this->userContentEditor, 'api')
            ->putJson('/api/user/' . $this->userContentEditor->id, [
                'name' => $this->userContentEditor->name,
                'email' => $this->userContentEditor->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(403);
    }

    public function testThatAdminsCanUpdateTechAdminUserRoles()
    {
        $response = $this->actingAs($this->userAdmin, 'api')
            ->putJson('/api/user/' . $this->userTechAdmin->id, [
                'name' => $this->userTechAdmin->name,
                'email' => $this->userTechAdmin->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(200);
    }

    public function testThatAdminsCanUpdateContentAuthorUserRoles()
    {
        $response = $this->actingAs($this->userAdmin, 'api')
            ->putJson('/api/user/' . $this->userContentAuthor->id, [
                'name' => $this->userContentAuthor->name,
                'email' => $this->userContentAuthor->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(200);
    }

    public function testThatAdminsCanUpdateContentEditorUserRoles()
    {
        $response = $this->actingAs($this->userAdmin, 'api')
            ->putJson('/api/user/' . $this->userContentEditor->id, [
                'name' => $this->userContentEditor->name,
                'email' => $this->userContentEditor->email,
                'roles' => [
                    'developer',
                ],
            ])
        ;

        $response->assertStatus(200);
    }
}
