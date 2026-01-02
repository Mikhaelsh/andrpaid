@extends('layouts.app')

@section('title', 'Admin Panel')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
@endsection

@section('content')
    @include('partials.navbarAdmin')


@endsection
