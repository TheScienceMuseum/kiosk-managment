<?php

namespace App\Jobs;

use App\Events\PackageBuildCompleted;
use App\Gallery;
use App\PackageVersion;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     * @var User The user that will be approving the
     */
    protected $approvingUser;

    /**
     * Create a new job instance.
     *
     * @param PackageVersion $packageVersion
     * @param User|null $approvingUser
     */
    public function __construct(PackageVersion $packageVersion, User $approvingUser = null)
    {
        $this->packageVersion = $packageVersion;
        $this->approvingUser = $approvingUser;
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

            // delete the current media
            $this->createProcess(['rm', '-rf', $this->buildDirectory.'/media/*'])->mustRun();

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
            Storage::disk(config('filesystems.packages'))
                ->put($this->packageVersion->archive_path, Storage::disk('build-temp')->get($archiveFilename));

            // finish the process
            $this->updateProgress($this->packageVersion, 100);

            event(new PackageBuildCompleted($this->packageVersion, $this->approvingUser));
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

    /**
     * Runs when a job fails
     */
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

    /**
     * @param PackageVersion $packageVersion
     * @return object
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function buildManifest(PackageVersion $packageVersion)
    {
        $manifest = (object) json_decode(json_encode($packageVersion->data));

        $manifest->name = Str::kebab($packageVersion->package->name);
        $manifest->label = $packageVersion->package->name;
        $manifest->version = $packageVersion->version;

        if (!empty($manifest->content->titles->image)) $manifest->content->titles->image = $this->convertToManifestAsset($manifest->content->titles->image);
        if (!empty($manifest->content->titles->attractor)) $manifest->content->titles->attractor = $this->convertToManifestAsset($manifest->content->titles->attractor);

        foreach($manifest->content->contents as $contentIndex => $content) {
            $content->articleID = $packageVersion->version . '-' . $contentIndex;
            if (!empty($content->titleImage)) $content->titleImage = $this->convertToManifestAsset($content->titleImage);
            if (!empty($content->asset)) $content->asset = $this->convertToManifestAsset($content->asset);

            if (!empty($content->subpages)) {
                foreach($content->subpages as $subpageIndex => $subpage) {
                    $subpage->pageID = $packageVersion->version . '-' . $contentIndex . '-' . $subpageIndex;
                    if (!empty($subpage->asset)) $subpage->asset = $this->convertToManifestAsset($subpage->asset);
                    if (!empty($subpage->audio)) $subpage->audio = $this->convertToManifestAsset($subpage->audio);
                }
            }
        }

        // insert the customised style based on gallery chosen
        $galleryID = empty($manifest->gallery) ? 1 : $manifest->gallery;
        $gallery = Gallery::find($galleryID);
        $indexFile = Storage::disk('build-temp')->get($this->buildDirectory . '/index.html');

        $indexFile = str_replace(
            '<html lang="en">',
            '<html lang="en" class="'.$gallery->classes.'">',
            $indexFile
        );

        Storage::disk('build-temp')->put($this->buildDirectory . '/index.html', $indexFile);

        return $manifest;
    }

    /**
     * @param $assetEntry
     * @return \stdClass|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function convertToManifestAsset($assetEntry)
    {
        if (empty($assetEntry)) return null;

        $assetMedia = Media::find($assetEntry->assetId);

        $assetEntry->assetSource = $this->copyAssetToBuildDir($assetMedia);
        unset($assetEntry->assetId);
        unset($assetEntry->assetMime);
        unset($assetEntry->assetFilename);

        if ($assetEntry->assetType === 'video') {
            if (!empty($assetEntry->bslAssetId)) {
                $bslMedia = Media::find($assetEntry->bslAssetId);
                $assetEntry->bslSource = $this->copyAssetToBuildDir($bslMedia);
                unset($assetEntry->bslAssetId);
                unset($assetEntry->bslAssetMime);
                unset($assetEntry->bslAssetFilename);
            }
        }

        if ($assetEntry->assetType === 'model') {
            // extract the zip to a folder with random name
            $folder = './media/'.uniqid().'/';
            $extractionPath = Storage::disk('build-temp')->path($this->buildDirectory.'/'.$folder);
            $zipPath = Storage::disk('build-temp')->path($this->buildDirectory.'/'.$assetEntry->assetSource);

            $archive = new \ZipArchive();
            $archive->open($zipPath);
            $archive->extractTo($extractionPath);

            unlink($zipPath);

            unset($assetEntry->assetSource);

            $assetEntry->assetDirectory = $folder;
        }

        if ($assetEntry->assetType === 'image' && !empty($assetEntry->boundingBox)) {
            $imageSize = getimagesize($this->getFullBuildPath().'/'.$assetEntry->assetSource);
            $assetEntry->boundingBox->y = round($assetEntry->boundingBox->y / $imageSize[1], 2);
            $assetEntry->boundingBox->height = round($assetEntry->boundingBox->height / $imageSize[1], 2);

            $assetEntry->boundingBox->x = round($assetEntry->boundingBox->x / $imageSize[0], 2);
            $assetEntry->boundingBox->width = round($assetEntry->boundingBox->width / $imageSize[0], 2);
        }

        return $assetEntry;
    }

    /**
     * @param Media $media
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
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

    /**
     * @return string
     */
    private function getFullBuildPath()
    {
        $diskConfig = config("filesystems.disks.build-temp");
        return $diskConfig['root'].'/'.$this->buildDirectory;
    }
}
