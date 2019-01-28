<?php

namespace App\Console\Commands;

use App\OnBoarding\OnBoardingService;
use App\User;
use Illuminate\Console\Command;

class OnboardUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ops:users:onboard {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger the onboarding process for the given user id.';

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
        $user = User::findOrFail($this->argument('user_id'));
        OnBoardingService::startOnBoarding($user);
    }
}
