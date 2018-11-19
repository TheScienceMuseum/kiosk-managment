<?php

namespace App\Jobs;

use App\Events\PackageBuildCompleted;
use App\PackageVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BuildPackageFromVersion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int Only try to run the job once
     */
    public $tries = 1;

    /**
     * @var PackageVersion
     */
    protected $packageVersion;

    /**
     * @var string The build directory
     */
    protected $buildDirectory;

    /**
     * Create a new job instance.
     *
     * @param PackageVersion $packageVersion
     */
    public function __construct(PackageVersion $packageVersion)
    {
        $this->packageVersion = $packageVersion;
        $this->buildDirectory = 'builds/package-build-' . str_random();

        \Log::info('Queued a build of package version id: ' . $this->packageVersion->id . ' in directory ' . $this->buildDirectory);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Starting build of package version id: ' . $this->packageVersion->id . ' in directory ' . $this->buildDirectory);

        try {
            if ($this->getDisk()->exists($this->packageVersion->archive_path)) {
                $this->getDisk()->delete($this->packageVersion->archive_path);
            }

            $this->updateProgress($this->packageVersion, 1);

            // clone
            $this->updateProgress($this->packageVersion, 20);
            File::copyDirectory($this->getDisk()->path('kiosk-package-interface'), $this->getDisk()->path($this->buildDirectory));

            // import package data
            $this->updateProgress($this->packageVersion, 40);
            $packageData = array_merge(json_decode($this->packageVersion->data, true), [
                'name' => $this->packageVersion->package->name,
                'version' => (int)$this->packageVersion->version,
            ]);

            $this->getDisk()->put($this->buildDirectory . '/public/manifest.json', json_encode($packageData));

            // compress package
            $this->updateProgress($this->packageVersion, 60);
            $archiveFilename = $this->packageVersion->package->name . '_' . $this->packageVersion->version . '.package';
            $this->createProcess(['tar', '-czvf', $archiveFilename, '.'], $this->buildDirectory)->mustRun();

            // copy the package
            $this->updateProgress($this->packageVersion, 80);
            $this->getDisk()->delete('public/packages/' . $archiveFilename);
            $this->getDisk()->copy($this->buildDirectory . '/' . $archiveFilename, 'public/packages/' . $archiveFilename);

            // finish the process
            $this->updateProgress($this->packageVersion, 100);

            event(new PackageBuildCompleted($this->packageVersion));
        } catch (\Exception $e) {
            $this->packageVersion->update([
                'status' => 'failed',
                'progress' => 0,
            ]);

            \Log::error('Could not build package version id: ' . $this->packageVersion->id . ' due to error: ' . $e->getMessage());
        } finally {
            // we use a terminal shell here, laravel checks every file, performance impact is crazy
            $this->createProcess(['rm', '-rf', $this->buildDirectory])->mustRun();
        }
    }

    public function failed()
    {
        $this->packageVersion->update([
            'status' => 'failed',
            'progress' => 0,
        ]);
    }

    /**
     * @param array $command
     * @param string $cwd
     * @return Process
     */
    private function createProcess(array $command, string $cwd = '') : Process
    {
        $process = new Process($command);
        $process->setWorkingDirectory($this->getDisk()->path($cwd));
        $process->setTimeout(3600);
        $process->setIdleTimeout(3600);

        return $process;
    }

    /**
     * @param PackageVersion $packageVersion
     * @param $progress
     */
    private function updateProgress(PackageVersion $packageVersion, $progress)
    {
        $packageVersion->update([
            'progress' => (int) $progress,
        ]);
    }

    private function getDisk() : Filesystem
    {
        return Storage::disk('local');
    }
}
