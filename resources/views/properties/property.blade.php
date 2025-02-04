<link href="{{ asset('/css/property.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <property-component :role="{{ Auth::user()->getRoleNames()}}" :permissions="{{ Auth::user()->getAllPermissions() }}"></property-component>
        </div>
    </div>
@endsection
