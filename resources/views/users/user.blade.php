<link href="{{ asset('/css/user.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <user-component></user-component>
        </div>
    </div>
@endsection
