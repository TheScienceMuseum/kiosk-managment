<?php

namespace Tests\Feature\PackageManagement;

use App\Package;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class DeletingPackagesTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testDeletingAPackageAsADeveloperSucceeds()
    {
        $response = $this->actingAsDeveloper()
            ->delete(route('api.package.destroy', [Package::first()]))
        ;

        $response->assertStatus(204);
    }

    public function testDeletingAPackageAsAnAdminSucceeds()
    {
        $response = $this->actingAsAdmin()
            ->delete(route('api.package.destroy', [Package::first()]))
        ;

        $response->assertStatus(204);
    }

    public function testDeletingAPackageAsATechAdminFails()
    {
        $response = $this->actingAsTechAdmin()
            ->delete(route('api.package.destroy', [Package::first()]))
        ;

        $response->assertStatus(403);
    }

    public function testDeletingAPackageAsAContentEditorSucceeds()
    {
        $response = $this->actingAsContentEditor()
            ->delete(route('api.package.destroy', [Package::first()]))
        ;

        $response->assertStatus(204);
    }

    public function testDeletingAPackageAsAContentAuthorFails()
    {
        $response = $this->actingAsContentAuthor()
            ->delete(route('api.package.destroy', [Package::first()]))
        ;

        $response->assertStatus(403);
    }
}
