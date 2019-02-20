<?php

namespace Tests\Feature\PackageManagement;

use App\Jobs\BuildPackageFromVersion;
use App\PackageVersion;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\ActsAs;
use Tests\ResetsDatabase;
use Tests\TestCase;

class PackageVersionBuildTest extends TestCase
{
    use ActsAs, ResetsDatabase;

    public function testTriggeringAPackageBuildFromTheCommandLineSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        Queue::fake();

        Artisan::call('kiosk:package:build', [
            'packageVersion' => $packageVersion->id,
        ]);

        Queue::assertPushedOn('long-running', BuildPackageFromVersion::class);
    }

    public function testTriggeringAPackageBuildFromTheCommandLineWithAnInvalidPackageVersionFails()
    {
        Queue::fake();

        Artisan::call('kiosk:package:build', [
            'packageVersion' => 9999,
        ]);

        Queue::assertNotPushed(BuildPackageFromVersion::class);
    }

    public function testBuildingAValidPackageVersionSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $job = new BuildPackageFromVersion($packageVersion);
        $job->handle();

        $this->assertTrue($packageVersion->archive_path_exists);
    }

    public function testRebuildingAValidPackageVersionSucceeds()
    {
        $packageVersion = PackageVersion::whereStatus('pending')->firstOrFail();

        $job = new BuildPackageFromVersion($packageVersion);
        $job->handle();

        $this->assertTrue($packageVersion->archive_path_exists);
    }
}
