@extends('layouts.app')

@section('content')
<!-- <div class="container">
    <div class="text-center py-5">
        <img src="{{ asset('img/logo.png') }}" class="img-fluid align-middle" alt="..." style="width: 30%;">
    </div>
    <div class="row justify-content-center py-5"
        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;">
        <div class="col-md-8">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header text-center"
                    style="background-color: #0f3e79; border-top-left-radius: 20px; border-top-right-radius: 20px; color: white;">
                    <strong>WELCOME ROS!</strong></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                        old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #0f3e79; border-color: #0f3e79; font-size: 1rem;">
                                    Login
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->


<div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Columna izquierda -->
            <div class="col-md-6 text-white d-flex align-items-center justify-content-center"
                style="background-color: #54b18a;">
                <div class="text-center">
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="Placeholder Image" width="250">
                    <h1
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 3.25rem;">
                        <strong>
                            Welcome ROS</strong>
                    </h1>
                    <p
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 2.25rem;">
                        <strong>
                            Designed to help you manage your properties.</strong>
                    </p>
                </div>
            </div>
            <!-- Columna derecha -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="w-75">
                    <h2 class="text-center mb-4"
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 3.25rem;"><strong>
                        Sign In</strong></h2>
                    <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;">Email
                                Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="example@example.com"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="*********"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm"
                                style="background-color: #54b18a; color: #fff; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; width: 50%;">Login</button>
                            <br>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem;">Forgot
                                your password?</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection