<link href="{{ asset('/css/incident_action.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <incident-action-component :role="{{ Auth::user()->getRoleNames()}}" :permissions="{{ Auth::user()->getAllPermissions() }}"></incident-action-component>
        </div>
    </div>
@endsection
