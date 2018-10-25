<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\ActsAs;

class ApiDocumentationTest extends TestCase
{
    use ResetsDatabase, ActsAs, WithFaker;

    public function testDevelopersCanGetApiDocumentation()
    {
        $this->actingAsDeveloper()
            ->get('/api')
            ->assertStatus(200)
        ;
    }

    public function testAdminUsersCannotGetApiDocumentation()
    {
        $this->actingAsAdmin()
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testTechAdminUsersCannotGetApiDocumentation()
    {
        $this->actingAsTechAdmin()
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testContentEditorUsersCannotGetApiDocumentation()
    {
        $this->actingAsContentEditor()
            ->get('/api')
            ->assertStatus(403)
        ;
    }

    public function testContentAuthorUsersCannotGetApiDocumentation()
    {
        $this->actingAsContentAuthor()
            ->get('/api')
            ->assertStatus(403)
        ;
    }
}
