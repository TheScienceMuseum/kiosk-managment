<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\UsesUsers;

class ApiDocumentationRequestTest extends TestCase
{
    use ResetsDatabase, UsesUsers, WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->setUpUsers();
    }

    public function testDevelopersCanGetApiDocumentation()
    {
        $this->actingAs($this->userDeveloper, 'api')
            ->get('/api')
            ->assertStatus(200)
        ;
    }

    public function testAdminUsersCannotGetApiDocumentation()
    {
        $this->actingAs($this->userAdmin, 'api')
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testTechAdminUsersCannotGetApiDocumentation()
    {
        $this->actingAs($this->userTechAdmin, 'api')
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testContentEditorUsersCannotGetApiDocumentation()
    {
        $this->actingAs($this->userContentEditor, 'api')
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testContentAuthorUsersCannotGetApiDocumentation()
    {
        $this->actingAs($this->userContentAuthor, 'api')
            ->get('/api')
            ->assertStatus(403)
        ;
    }
}
