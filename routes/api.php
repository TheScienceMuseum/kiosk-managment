<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')
    ->middleware(['auth:api'])
    ->namespace('Api')
    ->group(function (Router $router) {
        $router->name('user.')
            ->prefix('user')
            ->group(function (Router $router) {
                $router->get('role', 'UserController@roleIndex')->name('role.index');

                $router->get('', 'UserController@index')->name('index');
                $router->post('', 'UserController@store')->name('store');
                $router->get('{user}', 'UserController@show')->name('show');
                $router->put('{user}', 'UserController@update')->name('update');
                $router->delete('{user}', 'UserController@destroy')->name('destroy');
            });

        $router->name('kiosk.')
            ->prefix('kiosk')
            ->group(function (Router $router) {
                $router->get('', 'KioskController@index')->name('index');
                $router->get('{kiosk}', 'KioskController@show')->name('show');
                $router->get('{kiosk}/logs', 'KioskController@showLogs')->name('show.logs');
                $router->put('{kiosk}', 'KioskController@update')->name('update');
                $router->delete('{kiosk}', 'KioskController@destroy')->name('destroy');
                $router->put('{kiosk}/assign/{packageVersion}', 'KioskController@assignPackage')->name('assign-package');
            });

        $router->name('package.')
            ->prefix('package')
            ->group(function (Router $router) {
                $router->get('', 'PackageController@index')->name('index');
                $router->post('', 'PackageController@store')->name('store');
                $router->get('{package}', 'PackageController@show')->name('show');
                $router->put('{package}', 'PackageController@update')->name('update');
                $router->delete('{package}', 'PackageController@destroy')->name('destroy');

                $router->post('{package}/version', 'PackageVersionController@store')->name('version.store');
                $router->get('{package}/version/{packageVersion}', 'PackageVersionController@show')->name('version.show');
                $router->put('{package}/version/{packageVersion}', 'PackageVersionController@update')->name('version.update');
            });
    });

Route::name('api.information')
    ->middleware(['auth:api'])
    ->namespace('Api')
    ->group(function (Router $router) {
        $router->get('', 'ApiInformationController@resources')->name('resources');
    });

Route::name('api.')
    ->namespace('Api')
    ->prefix('kiosk')
    ->middleware(['api'])
    ->group(function (Router $router) {
        $router->post('health-check', 'KioskController@healthCheck')->name('kiosk.health-check');
        $router->post('register', 'KioskController@register')->name('kiosk.register');
        $router->post('package/{package}/version/{packageVersion}/download', 'KioskController@download')->name('kiosk.package.download');
    });
