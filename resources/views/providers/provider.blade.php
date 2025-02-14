<link href="{{ asset('/css/provider.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @php
                $role = Auth::user()->getRoleNames()->first();
            @endphp
            @if (in_array($role, ['provider']))
                <provider-only-component :permissions="{{ Auth::user()->getAllPermissions() }}"></provider-only-component>
            @else
                <provider-component :permissions="{{ Auth::user()->getAllPermissions() }}"></provider-component>
            @endif

        </div>
    </div>
@endsection
