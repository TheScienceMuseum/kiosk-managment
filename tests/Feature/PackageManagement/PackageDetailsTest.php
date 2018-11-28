<?php

namespace Tests\Feature\PackageManagement;

use App\Package;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageDetailsTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testGettingPackageInformationAsADeveloperSucceeds()
    {
        $response = $this->actingAsDeveloper()
            ->get(route('api.package.show', [Package::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }

    public function testGettingPackageInformationAsAnAdminSucceeds()
    {
        $response = $this->actingAsAdmin()
            ->get(route('api.package.show', [Package::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }

    public function testGettingPackageInformationAsATechAdminSucceeds()
    {
        $response = $this->actingAsTechAdmin()
            ->get(route('api.package.show', [Package::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }

    public function testGettingPackageInformationAsAContentEditorSucceeds()
    {
        $response = $this->actingAsContentEditor()
            ->get(route('api.package.show', [Package::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }

    public function testGettingPackageInformationAsAContentAuthorSucceeds()
    {
        $response = $this->actingAsContentAuthor()
            ->get(route('api.package.show', [Package::first()]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }
}
