<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnBoardingProcessStepMFARegistrationRequest;
use App\Http\Requests\OnBoardingProcessStepPasswordRequest;
use App\Http\Requests\OnBoardingShowStepMFARegistrationRequest;
use App\Http\Requests\OnBoardingShowStepPasswordRequest;
use App\OnBoarding\OnBoardingService;
use Illuminate\Support\Facades\Hash;

class UserOnBoardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showStepPassword(OnBoardingShowStepPasswordRequest $request, string $token, string $encryptedEmail)
    {
        $onboarding = OnBoardingService::getOnBoardingFromTokenAndEncryptedEmail($token, $encryptedEmail);

        if ($onboarding->step !== 'password') {
            abort(404);
        }

        return view('auth.on-boarding.step-password', [
            'user' => $onboarding->user,
            'token' => $onboarding->token,
        ]);
    }

    public function processStepPassword(OnBoardingProcessStepPasswordRequest $request, string $token, string $encryptedEmail)
    {
        $onboarding = OnBoardingService::getOnBoardingFromTokenAndEncryptedEmail($token, $encryptedEmail);

        if ($onboarding->step !== 'password') {
            abort(404);
        }

        $onboarding->user->password = Hash::make($request->input('password'));
        $onboarding->user->save();

        return redirect(route('user.onboarding.mfa', [$onboarding->token, encrypt($onboarding->user->email)]));
    }

    public function showStepMFARegistration(OnBoardingShowStepMFARegistrationRequest $request, string $token, string $encryptedEmail)
    {
        $onboarding = OnBoardingService::getOnBoardingFromTokenAndEncryptedEmail($token, $encryptedEmail);

        if ($onboarding->step !== 'mfa') {
            abort(404);
        }

        $mfaSecret = \MultiFactorAuth::generateSecretKey();

        $qrCode = \MultiFactorAuth::getQRCodeUrl(
            config('app.name'),
            $onboarding->user->email,
            $mfaSecret
        );

        $onboarding->user->mfa_secret = $mfaSecret;
        $onboarding->user->save();

        return view('auth.on-boarding.step-mfa-registration', [
            'user' => $onboarding->user,
            'token' => $onboarding->token,
            'qr' => $qrCode,
            'mfaSecret' => $mfaSecret,
        ]);
    }

    public function processStepMFARegistration(OnBoardingProcessStepMFARegistrationRequest $request, string $token, string $encryptedEmail)
    {
        $onboarding = OnBoardingService::getOnBoardingFromTokenAndEncryptedEmail($token, $encryptedEmail);

        if ($onboarding->step !== null) {
            abort(404);
        }

        return redirect(route('login'));
    }
}
