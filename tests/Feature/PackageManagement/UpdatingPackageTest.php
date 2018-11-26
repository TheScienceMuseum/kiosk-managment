<?php

namespace Tests\Feature\PackageManagement;

use App\Package;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class UpdatingPackageTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testUpdatingAPackageNameAsADeveloperSucceeds()
    {
        $randomName = str_random();

        $response = $this->actingAsDeveloper()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(200)
            ->assertSee($randomName);
    }

    public function testUpdatingAPackageNameAsAnAdminSucceeds()
    {
        $randomName = str_random();

        $response = $this->actingAsAdmin()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(200)
            ->assertSee($randomName);
    }

    public function testUpdatingAPackageNameAsATechAdminFails()
    {
        $randomName = str_random();

        $response = $this->actingAsTechAdmin()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(403)
            ->assertDontSee($randomName);
    }

    public function testUpdatingAPackageNameAsAContentEditorSucceeds()
    {
        $randomName = str_random();

        $response = $this->actingAsContentEditor()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(200)
            ->assertSee($randomName);
    }

    public function testUpdatingAPackageNameAsAContentAuthorSucceeds()
    {
        $randomName = str_random();

        $response = $this->actingAsContentAuthor()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(200)
            ->assertSee($randomName);
    }

}
