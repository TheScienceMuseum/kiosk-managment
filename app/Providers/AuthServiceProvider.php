<?php

namespace App\Providers;

use App\Kiosk;
use App\Package;
use App\PackageVersion;
use App\Policies\KioskPolicy;
use App\Policies\PackagePolicy;
use App\Policies\PackageVersionPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Kiosk::class => KioskPolicy::class,
        Package::class => PackagePolicy::class,
        PackageVersion::class => PackageVersionPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
