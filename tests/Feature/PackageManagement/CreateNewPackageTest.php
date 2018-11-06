<?php

namespace Tests\Feature\PackageManagement;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class CreateNewPackageTest extends TestCase
{
    use ActsAs, ResetsDatabase, WithFaker;

    public function testCreatingAPackageWithoutInformationFailsWithAppropriateMessages()
    {
        $response = $this->actingAsContentAuthor()
            ->postJson('/api/package')
        ;

        $response->assertStatus(422)
            ->assertJson([
                'message' => true,
                'errors' => [
                    'name' => true,
                ]
            ])
        ;
    }

    public function testCreatingAPackageWithAnExistingNameFailsWithAppropriateMessages()
    {
        $response = $this->actingAsContentAuthor()
            ->postJson('/api/package', [
                'name' => 'default',
            ])
        ;

        $response->assertStatus(422)
            ->assertJson([
                'message' => true,
                'errors' => [
                    'name' => true,
                ]
            ])
        ;
    }

    public function testCreatingAPackageAsADeveloperSucceeds()
    {
        $response = $this->actingAsDeveloper()
            ->postJson('/api/package', [
                'name' => $this->faker->unique()->word,
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => true,
                ],
            ])
        ;
    }

    public function testCreatingAPackageAsAnAdminSucceeds()
    {
        $response = $this->actingAsAdmin()
            ->postJson('/api/package', [
                'name' => $this->faker->unique()->word,
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => true,
                ],
            ])
        ;
    }

    public function testCreatingAPackageAsATechAdminFails()
    {
        $response = $this->actingAsTechAdmin()
            ->postJson('/api/package', [
                'name' => $this->faker->unique()->word,
            ])
        ;

        $response->assertStatus(403);
    }

    public function testCreatingAPackageAsAContentAuthorSucceeds()
    {
        $response = $this->actingAsContentAuthor()
            ->postJson('/api/package', [
                'name' => $this->faker->unique()->word,
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => true,
                ],
            ])
        ;
    }

    public function testCreatingAPackageAsAContentEditorSucceeds()
    {
        $response = $this->actingAsContentEditor()
            ->postJson('/api/package', [
                'name' => $this->faker->unique()->word,
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => true,
                ],
            ])
        ;
    }
}
