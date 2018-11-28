<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group([
    'middleware' => [],
], function (\Illuminate\Routing\Router $router) {
    $router->get('download/client/{os}', 'DownloadController@downloadKioskClient');
});

Route::group([
    'middleware' => [],
], function (\Illuminate\Routing\Router $router) {
    $router->get('registration/step/password/{token}/{encryptedEmail}', 'Auth\UserOnBoardingController@showStepPassword')
        ->name('user.onboarding.password');
    $router->post('registration/step/password/{token}/{encryptedEmail}', 'Auth\UserOnBoardingController@processStepPassword')
        ->name('user.onboarding.password.process');

    $router->get('registration/step/mfa/{token}/{encryptedEmail}', 'Auth\UserOnBoardingController@showStepMFARegistration')
        ->name('user.onboarding.mfa');
    $router->post('registration/step/mfa/{token}/{encryptedEmail}', 'Auth\UserOnBoardingController@processStepMFARegistration')
        ->name('user.onboarding.mfa.process');
});


Route::group([
    'middleware' => ['auth', 'mfa'],
], function (\Illuminate\Routing\Router $router) {
    $router->post('/login/authorize', function () {
        return redirect()->back();
    })->name('auth.login.mfa');


    $router->get('/logout', function() {
        return redirect('home');
    });
    $router->group([
        'namespace' => 'Admin',
        'prefix' => 'admin',
    ], function (\Illuminate\Routing\Router $router) {
        $router->post('users/{user}/on-board', 'UserController@onboard')
            ->name('admin.users.on-board');

        $router->get('packages', 'PackageController@index')
            ->name('admin.packages');
        $router->get('packages/create', 'PackageController@create')
            ->name('admin.packages.create');
        $router->post('packages', 'PackageController@store')
            ->name('admin.packages.store');
        $router->get('packages/{package}', 'PackageController@show')
            ->name('admin.packages.show');

        $router->post('packages/{package}/version', 'PackageVersionController@store')
            ->name('admin.packages.versions.store');
        $router->get('packages/{package}/version/{packageVersion}', 'PackageVersionController@show')
            ->name('admin.packages.versions.show');
        $router->put('packages/{package}/version/{packageVersion}', 'PackageVersionController@update')
            ->name('admin.packages.versions.update');
        $router->get('packages/{package}/version/{packageVersion}/download', 'PackageVersionController@download')
            ->name('admin.packages.versions.download');
        $router->post('packages/{package}/version/{packageVersion}/approve', 'PackageVersionController@approve')
            ->name('admin.packages.versions.approve');

        $router->put('kiosks/{kiosk}', 'KioskController@update')
            ->name('admin.kiosks.update');
    });

    $router->get('/{all}', 'HomeController@index')->where(['all' => '.*'])
        ->name('home');
});
