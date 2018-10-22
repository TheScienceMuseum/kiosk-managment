<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\UsesUsers;

class PackageAssignmentTest extends TestCase
{
    use ResetsDatabase, UsesUsers, WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->setUpUsers();
    }

    public function testAssigningAPackageToAKiosk()
    {
        $response = $this->actingAs($this->userDeveloper, 'api')
            ->put('/api/kiosk/1/assign/1')
        ;

        $response->assertStatus(200);
    }

    public function testGettingAListOfPackagesFilteredByName()
    {
        $response = $this->actingAs($this->userDeveloper, 'api')
            ->get('/api/package?filter[name]=default')
        ;

        $response->assertStatus(200);

        $packages = json_decode($response->getContent())->data;

        foreach ($packages as $package) {
            $this->assertEquals('default', $package->name);
        }
    }
}
