@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Columna izquierda -->
            <div class="col-md-6 text-white d-flex align-items-center justify-content-center"
                style="background-color: #E5E5E5;">
                <div class="text-center">
                    <img src="{{ asset('img/rentalcolora.svg') }}" class="img-fluid" alt="Placeholder Image" width="550" style="margin: 50px 0">
                    <h1
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 3.25rem; color: #333333;">
                        <strong>
                            Welcome ROS</strong>
                    </h1>
                    <p
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 2.25rem; color: #666666;">
                        <strong>
                            Designed to help you manage your properties.</strong>
                    </p>
                </div>
            </div>
            <!-- Columna derecha -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="w-75">
                    <h2 class="text-center mb-4"
                        style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 3.25rem; color: #333333;"><strong>
                        Sign In</strong></h2>
                    <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; color: #666666;">Email
                                Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="example@example.com"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; color: #666666;" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; color: #666666;">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="*********"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; color: #666666;" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm"
                                style="background-color: #F76F31; color: #fff; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; width: 50%;">Login</button>
                            <br>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none"
                                style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 1.25rem; color: #F76F31;">Forgot
                                your password?</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
