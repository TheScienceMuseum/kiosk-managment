<?php

namespace Tests\Feature\PackageManagement;

use App\Events\PackageVersionSubmittedForApproval;
use App\PackageVersion;
use Illuminate\Support\Facades\Event;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageVersionApproveForDeploymentTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testApprovingAPackageVersionAsADeveloperSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $response = $this->actingAsDeveloper()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'approved',
            ]);

        $response->assertStatus(200);
    }

    public function testApprovingAPackageVersionAsAnAdminSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $response = $this->actingAsAdmin()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'approved',
            ]);

        $response->assertStatus(200);
    }
    public function testApprovingAPackageVersionAsATechAdminFails()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $response = $this->actingAsTechAdmin()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'approved',
            ]);

        $response->assertStatus(403);
    }

    public function testApprovingAPackageVersionAsAContentEditorSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $response = $this->actingAsContentEditor()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'approved',
            ]);

        $response->assertStatus(200);
    }

    public function testApprovingAPackageVersionAsAContentAuthorFails()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $response = $this->actingAsContentAuthor()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'approved',
            ]);

        $response->assertStatus(403);
    }
}
