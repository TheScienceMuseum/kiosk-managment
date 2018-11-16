<?php

namespace App\Listeners;

use App\Events\PackageVersionSubmittedForApproval;
use App\Jobs\BuildPackageFromVersion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuePackageBuildJob implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PackageVersionSubmittedForApproval  $event
     * @return void
     */
    public function handle(PackageVersionSubmittedForApproval $event)
    {
        BuildPackageFromVersion::dispatch($event->packageVersion)->onQueue('long-running');
    }
}
