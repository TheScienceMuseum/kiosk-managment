<?php

namespace Tests\Feature\PackageManagement;

use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CreateNewPackageVersionTest extends TestCase
{
    use ActsAs, ResetsDatabase, WithFaker;

    public function testCreatingANewVersionOfANonExistentPackageFails()
    {
        $response = $this->actingAsDeveloper()
            ->postJson('/api/package/99999/version', []);

        $response->assertStatus(404);
    }

    public function testCreatingANewVersionOfAnExistingPackage()
    {
        $response = $this->actingAsContentAuthor()
            ->postJson('/api/package/1/version', []);

        $response->assertStatus(201)
            ->assertJson([]);
    }
}
