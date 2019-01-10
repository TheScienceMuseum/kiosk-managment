<?php

namespace Tests\Feature\KioskClient;

use App\Kiosk;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageDownloadTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testAssigningAPackageToARegisteredKioskShowsOnTheKiosksNextHealthCheck()
    {
        $kiosk = Kiosk::find(1);

        $response = $this->actingAsAdmin()
            ->put('/api/kiosk/1', [
                "name" => $kiosk->name,
                "asset_tag" => $kiosk->asset_tag,
                "location" => $kiosk->location,
                'assigned_package_version' => 1
            ])
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

    public function testDownloadingAPackageAssignedToARegisteredKioskGivesAValidTarArchive()
    {
        $kiosk = Kiosk::find(1);

        $response = $this->actingAsAdmin()
            ->put('/api/kiosk/1', [
                "name" => $kiosk->name,
                "asset_tag" => $kiosk->asset_tag,
                "location" => $kiosk->location,
                'assigned_package_version' => 1
            ])
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
                    'name' => $kiosk->name,
                    'location' => $kiosk->location,
                    'asset_tag' => $kiosk->asset_tag,
                    'identifier' => $this->kioskIdentifier,
                    'client_version' => '1.0.0',
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
                    'current_package_version' => null,
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'location',
                    'asset_tag',
                    'identifier',
                    'client_version',
                    'assigned_package_version',
                    'current_package_version',
                    'last_seen_at',
                ]
            ])
        ;

        $packageData = json_decode($response->getContent());

        $response = $this->actingAsRegisteredKiosk()
            ->postJson($packageData->data->assigned_package_version->path, [
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
