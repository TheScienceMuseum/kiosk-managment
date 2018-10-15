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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'middleware' => 'auth',
], function (\Illuminate\Routing\Router $router) {
    $router->get('admin/users', 'AdminController@users')
        ->name('admin.users');

    $router->get('admin/kiosks', 'AdminController@kiosks')
        ->name('admin.kiosks');
});
