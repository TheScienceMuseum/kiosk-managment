<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\ActsAs;

class PackageSearchTest extends TestCase
{
    use ResetsDatabase, ActsAs, WithFaker;

    public function testGettingAListOfPackages()
    {
        $response = $this->actingAsDeveloper()
            ->get('/api/package')
        ;

        $response->assertStatus(200);
    }

    public function testGettingAListOfPackagesFilteredByName()
    {
        $response = $this->actingAsDeveloper()
            ->get('/api/package?filter[name]=default')
        ;

        $response->assertStatus(200);

        $packages = json_decode($response->getContent())->data;

        foreach ($packages as $package) {
            $this->assertEquals('default', $package->name);
        }
    }
}
