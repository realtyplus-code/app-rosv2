<link href="{{ asset('/css/insurance.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <insurance-component></insurance-component>
        </div>
    </div>
@endsection
