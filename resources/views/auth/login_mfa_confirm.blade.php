@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="mfa_confirmation_dialog">
                    <div class="card-header">{{ __('auth.mfa.header') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.login.mfa') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="one_time_password" class="col-sm-4 col-form-label text-md-right">{{ __('auth.mfa.opt_field_label') }}</label>

                                <div class="col-md-6">
                                    <input id="one_time_password" type="number" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="one_time_password" value="{{ old('one_time_password') }}" required autofocus>

                                    @if ($errors->has('message'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('auth.mfa.opt_field_help') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('auth.mfa.opt_submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
