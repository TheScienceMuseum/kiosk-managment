<?php

namespace Tests\Feature\KioskManagement;

use Tests\ActsAs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchForKiosksTest extends TestCase
{
    use ActsAs;

    public function testSearchingForARegisteredKiosk()
    {
        $response = $this->actingAsTechAdmin()
            ->get('/api/kiosk?' . http_build_query([
                'filter' => [
                    'registered' => true,
                ],
            ]))
        ;

        $response->assertStatus(200)
            ->assertDontSee('"name":"null"')
        ;
    }

    public function testSearchingForAnUnregisteredKiosk()
    {
        $response = $this->actingAsTechAdmin()
            ->get('/api/kiosk?' . http_build_query([
                    'filter' => [
                        'registered' => false,
                    ],
                ]))
        ;

        $response->assertStatus(200);
    }
}
