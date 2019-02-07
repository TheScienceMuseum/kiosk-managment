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
    $router->post('login/authorize', function () {
        return str_replace(url('/'), '', url()->previous()) === '/login/authorize' ?
            redirect('/') :
            redirect()->back()
            ;
    })->name('auth.login.mfa');

    $router->get('/asset/{media}/{type?}', function(\Spatie\MediaLibrary\Models\Media $media, $type = '') {
        return response()->file($media->getPath($type));
    })->name('asset');

    $router->get('/{all}', 'HomeController@spa')
        ->where(['all' => '.*'])
        ->name('spa');

});
