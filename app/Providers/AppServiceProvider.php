<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Force the application to only create urls for it's app.url
         * and force all urls to have https if app.url is https
         */
        URL::forceRootUrl(\Config::get('app.url'));
        if (str_contains(\Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Horizon::auth(function ($request) {
            return $request->user() && $request->user()->can('view queue dashboard');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
