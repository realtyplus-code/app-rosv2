@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px">
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header text-center">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row mb-3 justify-content-center">
                                <label for="email" class="col-md-4 col-12 col-form-label text-md-end text-center">
                                    {{ __('Email Address') }}
                                </label>

                                <div class="col-md-6 col-10">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 justify-content-center">
                                <label for="password" class="col-md-4 col-12 col-form-label text-md-end text-center">
                                    {{ __('Password') }}
                                </label>

                                <div class="col-md-6 col-10">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 justify-content-center">
                                <label for="password-confirm"
                                    class="col-md-4 col-12 col-form-label text-md-end text-center">
                                    {{ __('Confirm Password') }}
                                </label>

                                <div class="col-md-6 col-10">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0 justify-content-center">
                                <div class="col-md-6 col-10 text-center">
                                    <button type="submit" class="btn btn-primary w-100"
                                        style="background-color: #F76F31;border-color: white;">
                                        {{ __('Reset Password') }}
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
