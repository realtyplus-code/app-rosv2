<link href="{{ asset('/css/home.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
           {{--  <home-component :rol="{{ Auth::user()->getRoleNames() }}"></home-component> --}}
           <indicator-component :rol="{{ Auth::user()->getRoleNames() }}"></indicator-component>
        </div>
    </div>
@endsection
