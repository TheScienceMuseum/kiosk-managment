<?php

namespace Tests\Feature\PackageManagement;

use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class SearchForPackagesTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testSearchingForAPackageByName()
    {
        $response = $this->actingAsAdmin()
            ->get('/api/package?' . http_build_query([
                    'filter' => [
                        'name' => 'default',
                    ],
                ]))
        ;

        $response->assertStatus(200)
            ->assertSee('"name":"default"')
        ;
    }
}
