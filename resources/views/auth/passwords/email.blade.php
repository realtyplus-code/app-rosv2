@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px">
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header text-center">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success text-center" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row mb-3 justify-content-center">
                                <label for="email" class="col-md-4 col-12 col-form-label text-md-end text-center">
                                    {{ __('Email Address') }}
                                </label>

                                <div class="col-md-6 col-10">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0 justify-content-center">
                                <div class="col-md-6 col-10 text-center">
                                    <button type="submit" class="btn btn-primary w-100"
                                        style="background-color: #F76F31;border-color: white;">
                                        {{ __('Send Password Reset Link') }}
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