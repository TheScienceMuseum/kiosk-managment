<?php

namespace Tests\Feature\PackageManagement;

use App\Events\PackageVersionSubmittedForApproval;
use App\PackageVersion;
use Illuminate\Support\Facades\Event;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageVersionSubmitForApprovalTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testSubmittingAPackageVersionForApprovalAsADeveloperSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('draft')->firstOrFail();

        Event::fake();

        $response = $this->actingAsDeveloper()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'pending',
            ]);

        $response->assertStatus(200);

        Event::assertDispatched(PackageVersionSubmittedForApproval::class, function ($e) use ($packageVersion) {
            return $e->packageVersion->id === $packageVersion->id;
        });
    }

    public function testSubmittingAPackageVersionForApprovalAsAnAdminSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('draft')->firstOrFail();

        Event::fake();

        $response = $this->actingAsAdmin()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'pending',
            ]);

        $response->assertStatus(200);

        Event::assertDispatched(PackageVersionSubmittedForApproval::class, function ($e) use ($packageVersion) {
            return $e->packageVersion->id === $packageVersion->id;
        });
    }

    public function testSubmittingAPackageVersionForApprovalAsATechAdminFails()
    {
        $packageVersion = PackageVersion::whereStatus('draft')->firstOrFail();

        Event::fake();

        $response = $this->actingAsTechAdmin()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'pending',
            ]);

        $response->assertStatus(403);

        Event::assertNotDispatched(PackageVersionSubmittedForApproval::class);
    }

    public function testSubmittingAPackageVersionForApprovalAsAContentEditorSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('draft')->firstOrFail();

        Event::fake();

        $response = $this->actingAsContentEditor()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'pending',
            ]);

        $response->assertStatus(200);

        Event::assertDispatched(PackageVersionSubmittedForApproval::class, function ($e) use ($packageVersion) {
            return $e->packageVersion->id === $packageVersion->id;
        });
    }

    public function testSubmittingAPackageVersionForApprovalAsAContentAuthorSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('draft')->firstOrFail();

        Event::fake();

        $response = $this->actingAsContentAuthor()
            ->putJson(route('api.package.version.update', [$packageVersion->package, $packageVersion]), [
                'data' => $packageVersion->data,
                'status' => 'pending',
            ]);

        $response->assertStatus(200);

        Event::assertDispatched(PackageVersionSubmittedForApproval::class, function ($e) use ($packageVersion) {
            return $e->packageVersion->id === $packageVersion->id;
        });
    }
}
