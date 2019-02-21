<?php

namespace App\Mail;

use App\PackageVersion;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageReadyForApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var PackageVersion
     */
    public $packageVersion;

    /**
     * Create a new message instance.
     *
     * @param PackageVersion $packageVersion
     */
    public function __construct(PackageVersion $packageVersion)
    {
        $this->packageVersion = $packageVersion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ': Invitation to approve package')
            ->markdown('mail.package-ready-for-approval', [
                'version' => $this->packageVersion,
            ]);
    }
}
