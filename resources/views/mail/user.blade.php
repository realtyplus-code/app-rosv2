@extends('layouts.email')

@section('title', 'Welcome')

@section('content')
    <p>Hi, {{ $name }}!</p>
    <p>Saludos,</p>
@endsection
