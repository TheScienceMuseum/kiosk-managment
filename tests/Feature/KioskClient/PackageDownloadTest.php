<?php

namespace Tests\Feature\KioskClient;

use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageDownloadTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testAssigningAPackageToARegisteredKioskShowsOnTheKiosksNextHealthCheck()
    {
        $response = $this->actingAsAdmin()
            ->put('/api/kiosk/1/assign/1')
        ;

        $response->assertStatus(200);

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

    public function testDownloadingAPackageAssignedToARegisteredKioskGivesAValidTarArchive()
    {
        $response = $this->actingAsAdmin()
            ->put('/api/kiosk/1/assign/1')
        ;

        $response->assertStatus(200);

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

        $packageData = json_decode($response->getContent());

        $response = $this->actingAsRegisteredKiosk()
            ->postJson($packageData->data->package->path, [
                'identifier' => $this->kioskIdentifier,
                'client' => [
                    'version' => '1.0.0',
                ],
            ])
        ;

        $response->assertStatus(200);

        $this->assertEquals('application/x-gzip', $response->headers->get('content-type'));
        $this->assertEquals('attachment; filename=default_1.package', $response->headers->get('content-disposition'));
    }
}
