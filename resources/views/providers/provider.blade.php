<link href="{{ asset('/css/provider.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <provider-component></provider-component>
        </div>
    </div>
@endsection