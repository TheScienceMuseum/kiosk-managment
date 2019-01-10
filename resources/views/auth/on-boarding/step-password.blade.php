@extends('layouts.onboarding')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-around">
            <span class="text-dark">
                Create Password
            </span>
            <span class="text-muted">
                Setup Authenticator
            </span>
        </div>

        <div class="card-body">
            <form action="{{ route('user.onboarding.password.process', [$token, encrypt($user->email)]) }}" method="post" class="registrationPasswordStep">
                @csrf

                <div class="alert alert-primary">
                    <p><strong>Welcome</strong></p>
                    <p>
                        You have had an account created for you on the kiosk management system.
                        Please enter the password you would like to use along with your email address to log into the system.
                    </p>
                </div>

                <div class="form-group row">
                    <label for="register-user-email" class="col-md-3 col-form-label text-md-right my-auto">{{ __('Email') }}</label>

                    <div class="col-md-8">
                        <input type="email" class="form-control" id="register-user-email" value="{{ $user->email }}" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-3 col-form-label text-md-right my-auto">{{ __('Password') }}</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-3 col-form-label text-md-right my-auto">{{ __('Confirm Password') }}</label>

                    <div class="col-md-8">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer text-right">
            <button type="button" class="btn btn-primary submitsForm" data-target="registrationPasswordStep">
                Continue to next step
            </button>
        </div>
    </div>
@endsection