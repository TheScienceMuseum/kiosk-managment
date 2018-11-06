<?php

namespace Tests\Feature\KioskClient;

use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testHealthCheckFromUnregisteredKioskReturnsEntityNotFound()
    {
        $response = $this->actingAsUnregisteredKiosk()
            ->postJson('/api/kiosk/health-check', [
                'identifier' => $this->kioskIdentifier,
                'client' => [
                    'version' => '1.0.0',
                ],
            ])
        ;

        $response->assertStatus(404);
    }

    public function testHealthCheckFromRegisteredKioskReturnsRequiredInformation()
    {
        $response = $this->actingAsRegisteredKiosk()
            ->postJson('/api/kiosk/health-check', [
                'identifier' => $this->kioskIdentifier,
                'client' => [
                    'version' => '1.0.0',
                ],
            ])
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => true,
                    'location' => true,
                    'asset_tag' => true,
                    'identifier' => $this->kioskIdentifier,
                    'client_version' => '1.0.0',
                    'current_package' => null,
                    'last_seen_at' => true,
                    'package' => [
                        'name' => 'default',
                        'version' => 1,
                        'path' => true,
                    ],
                    'path' => true,
                ],
            ])
        ;
    }

    public function testHealthCheckFromARegisteredKioskWithLogsAttachesLogsToTheKiosk()
    {
        $response = $this->actingAsRegisteredKiosk()
            ->postJson('/api/kiosk/health-check', [
                'identifier' => $this->kioskIdentifier,
                'client' => [
                    'version' => '1.0.0',
                ],
                'logs' => [
                    ['message' => 'test log entry', 'level' => 'error', 'timestamp' => now()->toIso8601ZuluString()],
                ],
            ])
        ;

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => true,
                    'location' => true,
                    'asset_tag' => true,
                    'identifier' => $this->kioskIdentifier,
                    'client_version' => '1.0.0',
                    'current_package' => null,
                    'last_seen_at' => true,
                    'package' => [
                        'name' => 'default',
                        'version' => 1,
                        'path' => true,
                    ],
                    'path' => true,
                ],
            ])
        ;

        $response = $this->actingAsTechAdmin()
            ->get('/api/kiosk/1/logs');

        $response->assertStatus(200)
            ->assertSee('test log entry');
    }
}
