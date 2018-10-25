<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\ActsAs;

class KioskRegistrationTest extends TestCase
{
    use ResetsDatabase, ActsAs, WithFaker;

    protected $kioskIdentifier;

    public function setUp()
    {
        parent::setUp();

        $this->kioskIdentifier = implode('-', $this->faker->words());
    }

    public function testUnregisteredKioskGetsNotFoundResponseOnHealthCheck()
    {
        $response = $this->postJson('/api/kiosk/health-check', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $response->assertStatus(404);
    }

    public function testRegisteringANewKiosk()
    {
        $response = $this->postJson('/api/kiosk/register', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $response->assertStatus(201);

        $response = $this->postJson('/api/kiosk/health-check', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $response->assertStatus(200);

        $kiosk_api_path = json_decode($response->getContent())->data->path;

        $response = $this->actingAsTechAdmin()
            ->get($kiosk_api_path);

        $response->assertStatus(200);
    }
}
