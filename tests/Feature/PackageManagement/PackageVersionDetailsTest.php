<?php

namespace Tests\Feature\PackageManagement;

use App\PackageVersion;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageVersionDetailsTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testGettingPackageVersionInformationAsADeveloperSucceeds()
    {
        $response = $this->actingAsDeveloper()
            ->get(route('api.package.version.show', [PackageVersion::first()->package, PackageVersion::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"version":"1"')
        ;
    }

    public function testGettingPackageVersionInformationAsAnAdminSucceeds()
    {
        $response = $this->actingAsAdmin()
            ->get(route('api.package.version.show', [PackageVersion::first()->package, PackageVersion::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"version":"1"')
        ;
    }

    public function testGettingPackageVersionInformationAsATechAdminSucceeds()
    {
        $response = $this->actingAsTechAdmin()
            ->get(route('api.package.version.show', [PackageVersion::first()->package, PackageVersion::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"version":"1"')
        ;
    }

    public function testGettingPackageVersionInformationAsAContentEditorSucceeds()
    {
        $response = $this->actingAsContentEditor()
            ->get(route('api.package.version.show', [PackageVersion::first()->package, PackageVersion::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"version":"1"')
        ;
    }

    public function testGettingPackageVersionInformationAsAContentAuthorSucceeds()
    {
        $response = $this->actingAsContentAuthor()
            ->get(route('api.package.version.show', [PackageVersion::first()->package, PackageVersion::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"version":"1"')
        ;
    }
}
