<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        login as baseLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::whereEmail($request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->getAuthPassword())) {
            return $this->sendFailedLoginResponse($request);
        }

        if (! $user->mfa_secret) {
            return $this->registerMultiFactor($request, $user);
        } else {
            return $this->baseLogin($request);
        }
    }

    protected function registerMultiFactor(Request $request, Authenticatable $user)
    {
        $mfaSecret = \MultiFactorAuth::generateSecretKey();

        $qrCode = \MultiFactorAuth::getQRCodeInline(
            config('app.name'),
            $user->{$this->username()},
            $mfaSecret
        );

        $user->mfa_secret = $mfaSecret;
        $user->save();

        return view('auth.login_mfa_register', [
            'qr' => $qrCode,
            'secret' => $mfaSecret,
        ]);
    }
}
