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
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        // Get the notification template for the application
        $notificationTemplate = file_get_contents(base_path('deployment/aws/slack-notification.json'));

        if (! $notificationTemplate) {
            throw new \Exception("Could not read the 'deployment/aws/slack-notification.json' file");
        }

        // Get the slack web hook uri
        $webHookUri = env('SLACK_WEB_HOOK_OPS', false);

        if (! $webHookUri) {
            throw new \Exception("SLACK_WEB_HOOK_OPS is not set in the environment, cannot send deployment notifications");
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

        $client = new GuzzleClient();

        $client->request('POST', $webHookUri, [
            'json' => json_decode($notificationTemplate, true),
        ]);
    }
}
