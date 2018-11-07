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

    $router->group([
        'namespace' => 'Admin',
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('admin/users', 'UserController@index')
            ->name('admin.user');
        $router->get('admin/users/{user}', 'UserController@show')
            ->name('admin.user.show');

        $router->get('admin/packages', 'PackageController@index')
            ->name('admin.package');
        $router->get('admin/packages/{package}', 'PackageController@show')
            ->name('admin.package.show');

        $router->get('admin/kiosks', 'KioskController@index')
            ->name('admin.kiosk');
        $router->get('admin/kiosks/{kiosk}', 'KioskController@show')
            ->name('admin.kiosk.show');
        $router->put('admin/kiosks/{kiosk}', 'KioskController@update')
            ->name('admin.kiosk.update');
    });
});
