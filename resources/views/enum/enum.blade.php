<link href="{{ asset('/css/enum.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <enum-component></enum-component>
        </div>
    </div>
@endsection
