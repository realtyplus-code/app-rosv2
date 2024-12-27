<link href="{{ asset('/css/home.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <home-component></home-component>
        </div>
    </div>
@endsection
