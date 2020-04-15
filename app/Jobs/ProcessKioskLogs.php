<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Kiosk;
use App\KioskLog;

class ProcessKioskLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $kiosk;
    protected $logs;

    /**
     * Create a new job instance.
     *
     * @param Kiosk $kiosk
     * @param Logs $logs
     */
    public function __construct(Kiosk $kiosk, $logs)
    {
        $this->kiosk = $kiosk;
        $this->logs = $logs;

        \Log::info('Queued a log insertion for: ' . $kiosk->identifier);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logs = $this->logs;
        $kiosk = $this->kiosk;

        foreach ($logs as $logEntry) {
            if ($kiosk->logs()->whereTimestamp($logEntry['timestamp'])->get()->count() === 0) {
                $kiosk->logs()->create([
                    'level' => $logEntry['level'],
                    'message' => $logEntry['message'],
                    'timestamp' => $logEntry['timestamp'],
                ]);
            }
        }
    }
}
