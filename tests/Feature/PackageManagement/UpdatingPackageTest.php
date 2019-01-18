<?php

namespace Tests\Feature\PackageManagement;

use App\Package;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class UpdatingPackageTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testUpdatingAPackageNameAsADeveloperFails()
    {
        $randomName = str_random();

        $response = $this->actingAsDeveloper()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(422);
    }

    public function testUpdatingAPackageNameAsAnAdminFails()
    {
        $randomName = str_random();

        $response = $this->actingAsAdmin()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(422);
    }

    public function testUpdatingAPackageNameAsATechAdminFails()
    {
        $randomName = str_random();

        $response = $this->actingAsTechAdmin()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(403);
    }

    public function testUpdatingAPackageNameAsAContentEditorFails()
    {
        $randomName = str_random();

        $response = $this->actingAsContentEditor()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(422);
    }

    public function testUpdatingAPackageNameAsAContentAuthorFails()
    {
        $randomName = str_random();

        $response = $this->actingAsContentAuthor()
            ->putJson(route('api.package.update', [Package::first()]), [
                'name' => $randomName,
            ])
        ;

        $response->assertStatus(422);
    }

}
