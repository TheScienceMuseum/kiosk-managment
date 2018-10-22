<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\ResetsDatabase;
use Tests\TestCase;

class KioskHealthCheckTest extends TestCase
{
    use ResetsDatabase, WithFaker;

    protected $kioskIdentifier;

    public function setUp()
    {
        parent::setUp();

        $this->kioskIdentifier = implode('-', $this->faker->words());

        $this->postJson('/api/kiosk/register', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);
    }

    public function testNewlyRegisteredKioskDoesNotHaveAPackageAssigned()
    {
        $response = $this->postJson('/api/kiosk/health-check', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'current_package' => null,
                ]
            ])
        ;
    }

    public function testHealthCheckUpdatesTheLastSeenTimestamp()
    {
        $response = $this->postJson('/api/kiosk/health-check', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $last_seen_1 = json_decode($response->getContent())->data->last_seen_at;

        sleep(1);

        $response = $this->postJson('/api/kiosk/health-check', [
            'identifier' => $this->kioskIdentifier,
            'client' => [
                'version' => '1.0.0',
            ]
        ]);

        $last_seen_2 = json_decode($response->getContent())->data->last_seen_at;

        $this->assertGreaterThan($last_seen_1, $last_seen_2);
    }
}
