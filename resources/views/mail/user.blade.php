@extends('layouts.email')

@section('title', 'Welcome')

@section('content')
    <p>Hello, {{ $name }}!</p>
    <p>Your password is: {{ $password }}</p>
    <p>You can log in at: <a href="{{ $url }}">{{ $url }}</a></p>
    <p>Regards,</p>
@endsection