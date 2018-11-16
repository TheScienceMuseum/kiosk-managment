<?php

namespace App\Listeners;

use App\Events\PackageBuildCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        //
    }
}
