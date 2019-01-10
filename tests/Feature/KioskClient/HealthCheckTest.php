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
                    'last_seen_at' => true,
                    'path' => true,
                    'assigned_package_version' => [
                        "id" => 1,
                        "name" => "default",
                        "version" => 1,
                        "path" => "http://kiosk-manager.test/api/kiosk/package/1/version/1/download",
                        "package" => [
                            "id" => 1,
                            "name" => "default"
                        ],
                        "status" => "approved"
                    ],
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
                    'last_seen_at' => true,
                    'path' => true,
                    'assigned_package_version' => [
                        "id" => 1,
                        "name" => "default",
                        "version" => 1,
                        "path" => "http://kiosk-manager.test/api/kiosk/package/1/version/1/download",
                        "package" => [
                            "id" => 1,
                            "name" => "default"
                        ],
                        "status" => "approved"
                    ],
                ],
            ])
        ;

        $response = $this->actingAsTechAdmin()
            ->get('/api/kiosk/1/logs');

        $response->assertStatus(200)
            ->assertSee('test log entry');
    }
}
