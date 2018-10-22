<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\UsesUsers;

class KioskSearchTest extends TestCase
{
    use ResetsDatabase, UsesUsers, WithFaker;

    protected $kioskIdentifier;

    public function setUp()
    {
        parent::setUp();

        $this->setUpUsers();

        $this->kioskIdentifier = implode('-', $this->faker->words());

        $this->postJson('/api/kiosk/register', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);
    }

    public function testGettingAListOfRegisteredKiosks()
    {
        $response = $this->actingAs($this->userDeveloper, 'api')
            ->get('/api/kiosk?filter[registered]=true')
        ;

        $response->assertStatus(200);

        $kiosks = json_decode($response->getContent())->data;

        foreach ($kiosks as $kiosk) {
            $this->assertNotNull($kiosk->name);
        }
    }

    public function testGettingAListOfUnregisteredKiosks()
    {
        $response = $this->actingAs($this->userDeveloper, 'api')
            ->get('/api/kiosk?filter[registered]=false')
        ;

        $response->assertStatus(200);

        $kiosks = json_decode($response->getContent())->data;

        foreach ($kiosks as $kiosk) {
            $this->assertNull($kiosk->name);
        }
    }
}
