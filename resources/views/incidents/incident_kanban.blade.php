<link href="{{ asset('/css/incident.css') }}?v={{ time() }}" rel="stylesheet">
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <incident-kanban-component></incident-kanban-component>
        </div>
    </div>
@endsection
