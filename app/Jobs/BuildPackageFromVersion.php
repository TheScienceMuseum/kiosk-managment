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
use Spatie\Image\Image;
use Spatie\MediaLibrary\Models\Media;
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
        $this->buildDirectory = 'package-build-' . str_random();

        \Log::info('Queued a build of package version id: ' . $this->packageVersion->id . ' in directory ' . Storage::disk('build-temp')->path($this->buildDirectory));
    }

    public function handle()
    {
        \Log::info('Starting build of package version id: ' . $this->packageVersion->id . ' in directory ' . $this->buildDirectory);

        try {
            if ($this->packageVersion->archive_path_exists) {
                Storage::disk(config('filesystems.packages'))->delete($this->packageVersion->archive_path);
            }

            $this->updateProgress($this->packageVersion, 10);

            // download kiosk interface tarball
            $this->updateProgress($this->packageVersion, 15);
            Storage::disk('build-temp')
                ->put(
                    $this->buildDirectory . '/interface.tar.gz',
                    Storage::disk(config('filesystems.builds'))->get(config('kiosk.interface-file'))
                );

            // extract the kiosk interface tarball
            $this->updateProgress($this->packageVersion, 20);
            $this->createProcess(['tar', '-xzvf', 'interface.tar.gz'], $this->buildDirectory)->mustRun();

            // delete the tarball
            $this->updateProgress($this->packageVersion, 25);
            Storage::disk('build-temp')->delete($this->buildDirectory . '/interface.tar.gz');

            // import package data
            $this->updateProgress($this->packageVersion, 40);
            $packageData = $this->buildManifest($this->packageVersion);
            Storage::disk('build-temp')->put($this->buildDirectory . '/manifest.json', json_encode($packageData));

            // compress package
            $this->updateProgress($this->packageVersion, 60);
            $archiveFilename = $this->packageVersion->package->name . '_' . $this->packageVersion->version . '.package';
            $this->createProcess(['tar', '-czvf', '../' . $archiveFilename, '.'], $this->buildDirectory)->mustRun();

            // copy the package
            $this->updateProgress($this->packageVersion, 80);
            Storage::disk(config('filesystems.packages'))->put($this->packageVersion->archive_path, Storage::disk('build-temp')->get($archiveFilename));

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
            if (config('app.env') !== 'local') {
                $this->createProcess(['rm', '-rf', $this->buildDirectory])->mustRun();
            }
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
        $process->setWorkingDirectory(Storage::disk('build-temp')->path($cwd));
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

    private function buildManifest(PackageVersion $packageVersion)
    {
        $manifest = (object) json_decode(json_encode($packageVersion->data));

        $manifest->name = $packageVersion->package->name;
        $manifest->version = $packageVersion->version;

        $manifest->content->titles->image = $this->convertToManifestAsset($manifest->content->titles->image);
        $manifest->content->titles->attractorImage = $this->convertToManifestAsset($manifest->content->titles->attractorImage);

        foreach($manifest->content->contents as $contentIndex => $content) {
            $content->articleID = $packageVersion->version . '-' . $contentIndex;
            if (!empty($content->titleImage)) $content->titleImage = $this->convertToManifestAsset($content->titleImage);
            if (!empty($content->videoSrc)) $content->videoSrc = $this->convertToManifestAsset($content->videoSrc);

            if (!empty($content->subpages)) {
                foreach($content->subpages as $subpageIndex => $subpage) {
                    $subpage->pageID = $packageVersion->version . '-' . $contentIndex . '-' . $subpageIndex;
                    if (!empty($subpage->image)) $subpage->image = $this->convertToManifestAsset($subpage->image);
                    if (!empty($subpage->videoSrc)) $subpage->videoSrc = $this->convertToManifestAsset($subpage->videoSrc);
                }
            }
        }

        return $manifest;
    }

    private function convertToManifestAsset($assetEntry)
    {
        $titleAsset = Media::find($assetEntry->assetId);

        if ($assetEntry->assetType === 'image') {
            $assetEntry->imageSource = $this->copyAssetToBuildDir($titleAsset);
            unset($assetEntry->assetId);
            unset($assetEntry->assetMime);
            unset($assetEntry->assetType);

            $actualHeight = Image::load($this->getFullBuildPath().'/'.$assetEntry->imageSource)->getHeight();
            $assetEntry->boundingBox->y = round($assetEntry->boundingBox->y / $actualHeight, 2);
            $assetEntry->boundingBox->height = round($assetEntry->boundingBox->height / $actualHeight, 2);

            $actualWidth = Image::load($this->getFullBuildPath().'/'.$assetEntry->imageSource)->getWidth();
            $assetEntry->boundingBox->x = round($assetEntry->boundingBox->x / $actualWidth, 2);
            $assetEntry->boundingBox->width = round($assetEntry->boundingBox->width / $actualWidth, 2);

        } else if ($assetEntry->assetType === 'video') {
            $assetEntry = $this->copyAssetToBuildDir($titleAsset);
        }

        return $assetEntry;
    }

    private function copyAssetToBuildDir(Media $media)
    {
        $diskConfig = config("filesystems.disks.{$media->disk}");
        $disk = Storage::disk($media->disk);
        $path = $media->getPath();

        if (!empty($diskConfig['root'])) {
            $path = str_replace($diskConfig['root'] . '/', '', $path);
        }

        $newFilename = $media->id.'-'.$media->file_name;

        Storage::disk('build-temp')->put($this->buildDirectory.'/media/'.$newFilename, $disk->get($path));

        return './media/'.$newFilename;
    }

    private function getFullBuildPath()
    {
        $diskConfig = config("filesystems.disks.build-temp");
        return $diskConfig['root'].'/'.$this->buildDirectory;
    }
}
