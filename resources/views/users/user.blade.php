<link href="{{ asset('/css/user.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @php
                $role = Auth::user()->getRoleNames()->first();
            @endphp
            @if(in_array($role, ['owner', 'tenant', 'ros_client']))
                <user-only-component :role="{{ Auth::user()->getRoleNames() }}"
                    :permissions="{{ Auth::user()->getAllPermissions() }}"></user-only-component>
            @else
                <user-component :role="{{ Auth::user()->getRoleNames() }}"
                    :permissions="{{ Auth::user()->getAllPermissions() }}"></user-component>
            @endif
        </div>
    </div>
@endsection
