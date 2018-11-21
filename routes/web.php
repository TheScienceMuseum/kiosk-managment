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
    'middleware' => ['auth', 'mfa'],
], function (\Illuminate\Routing\Router $router) {
    $router->post('/login/authorize', function () {
        return redirect()->back();
    })->name('auth.login.mfa');

    $router->get('/', 'HomeController@index')
        ->name('home');
    $router->get('/logout', function() {
        return redirect('home');
    });
    $router->group([
        'namespace' => 'Admin',
        'prefix' => 'admin',
    ], function (\Illuminate\Routing\Router $router) {
//        $router->get('users/{user}', 'UserController@show')
//            ->name('admin.users.show');

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

        $router->get('kiosks', 'KioskController@index')
            ->name('admin.kiosks');
        $router->get('kiosks/{kiosk}', 'KioskController@show')
            ->name('admin.kiosks.show');
        $router->put('kiosks/{kiosk}', 'KioskController@update')
            ->name('admin.kiosks.update');
    });

    $router->get('/{all}', 'HomeController@index')->where(['all' => '.*'])
        ->name('home');
});
