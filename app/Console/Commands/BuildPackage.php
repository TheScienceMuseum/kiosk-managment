<?php

namespace App\Console\Commands;

use App\Jobs\BuildPackageFromVersion;
use App\PackageVersion;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BuildPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosk:package:build {packageVersion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds a .package file from a given package version';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $packageVersion = PackageVersion::find($this->argument('packageVersion'));

        if (!$packageVersion) {
            $this->error('Could not find a package version with id: ' . $this->argument('packageVersion'));
            return;
        }

        BuildPackageFromVersion::dispatch($packageVersion)->onQueue('long-running');

        return;
    }
}
