<?php

namespace App\Events;

use App\PackageVersion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PackageVersionSubmittedForApproval
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var PackageVersion
     */
    public $packageVersion;

    /**
     * Create a new event instance.
     *
     * @param PackageVersion $packageVersion
     */
    public function __construct(PackageVersion $packageVersion)
    {
        $this->packageVersion = $packageVersion;
    }
}
