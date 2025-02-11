<link href="{{ asset('/css/user.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <user-component :role="{{ Auth::user()->getRoleNames()}}" :permissions="{{ Auth::user()->getAllPermissions() }}"></user-component>
        </div>
    </div>
@endsection
