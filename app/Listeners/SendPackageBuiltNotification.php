<?php

namespace App\Listeners;

use App\Events\PackageBuildCompleted;
use App\Mail\PackageReadyForApproval;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPackageBuiltNotification implements ShouldQueue
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
     * @param  PackageBuildCompleted  $event
     * @return void
     */
    public function handle(PackageBuildCompleted $event)
    {
        if ($event->approvingUser) {
            $mailable = new PackageReadyForApproval($event->packageVersion);
            Mail::to($event->approvingUser)->queue($mailable);
        }
    }
}
