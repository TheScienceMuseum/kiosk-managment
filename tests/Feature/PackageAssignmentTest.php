<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\ActsAs;

class PackageAssignmentTest extends TestCase
{
    use ResetsDatabase, ActsAs, WithFaker;

    public function testAssigningAPackageToAKiosk()
    {
        $response = $this->actingAsDeveloper()
            ->put('/api/kiosk/1/assign/1')
        ;

        $response->assertStatus(200);
    }
}
