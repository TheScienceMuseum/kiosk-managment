@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card text-center">
                    <div class="card-header">Multi-Factor Authenticator</div>

                    <div class="card-body">
                        <p>Set up your two factor authentication by scanning the barcode below.</p>
                        <div>
                            <img src="{{ $qr }}">
                        </div>
                        <p>Alternatively, you can use the code: <span id="mfa_secret">{{ $secret }}</span></p>
                        <p>You must set up your Authenticator app before continuing. You will be unable to login otherwise.</p>
                        <div>
                            <a href="/login" class="btn btn-primary" id="login">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection