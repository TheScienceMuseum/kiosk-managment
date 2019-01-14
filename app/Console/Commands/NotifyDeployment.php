<?php

namespace App\Console\Commands;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class NotifyDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ops:deployment:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify apis that the deployment has succeeded.';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        if($this->sendSlackNotification()) {
            $this->output->success('Sent notification to slack.');
        }
        if ($this->sendDetectifyNotification()) {
            $this->output->success('Triggered Scan in detectify.');
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendDetectifyNotification()
    {
        if (config('app.env') !== 'staging') {
            return false;
        }

        // Get the detectify api key
        $detectifyApiKey = env('DETECTIFY_API_KEY', false);

        if (! $detectifyApiKey) {
            $this->output->error("DETECTIFY_API_KEY is not set in the environment, cannot trigger scans on deploy");
        }

        // Get the detectify scanning profile id
        $detectifyScanProfile = env('DETECTIFY_SCAN_PROFILE', false);

        if (! $detectifyScanProfile) {
            $this->output->error("DETECTIFY_SCAN_PROFILE is not set in the environment, cannot trigger scans on deploy");
        }

        $url = "https://$detectifyApiKey@api.detectify.com/rest/v2/scans/$detectifyScanProfile/";
        (new GuzzleClient())->request('POST', $url);

        return true;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendSlackNotification()
    {
        if (!in_array(config('app.env'), ['staging', 'production'])) {
            return false;
        }

        // Get the notification template for the application
        $notificationTemplate = file_get_contents(base_path('deployment/aws/slack-notification.json'));

        if (! $notificationTemplate) {
            $this->output->error("Could not read the 'deployment/aws/slack-notification.json' file");
        }

        // Get the slack web hook uri
        $webHookUri = env('SLACK_WEB_HOOK_OPS', false);

        if (! $webHookUri) {
            $this->output->error("SLACK_WEB_HOOK_OPS is not set in the environment, cannot send deployment notifications");
        }

        // Parse the notification file and insert options
        $matches = [];
        preg_match_all('/(?:%([A-Z_]+)%)/', $notificationTemplate, $matches);
        $matches = array_unique($matches[1]);

        foreach ($matches as $match) {
            $value = env($match, false);

            if ($value) {
                $notificationTemplate = str_replace("%" . $match . "%", $value, $notificationTemplate);
                continue;
            }

            if ($match === 'COMMIT') {
                $process = new Process("sentry-cli releases propose-version", base_path());
                $process->run();
                $value = str_replace("\n", "", $process->getOutput());
                $notificationTemplate = str_replace("%" . $match . "%", $value, $notificationTemplate);
            }
        }

        (new GuzzleClient())->request('POST', $webHookUri, [
            'json' => json_decode($notificationTemplate, true),
        ]);

        return true;
    }
}
