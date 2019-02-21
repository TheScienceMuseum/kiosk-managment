<?php

namespace App\Events;

use App\PackageVersion;
use App\User;
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
     * @var User
     */
    public $approvingUser;

    /**
     * Create a new event instance.
     *
     * @param PackageVersion $packageVersion
     * @param User|null $approvingUser
     */
    public function __construct(PackageVersion $packageVersion, User $approvingUser = null)
    {
        $this->packageVersion = $packageVersion;
        $this->approvingUser = $approvingUser;
    }
}
