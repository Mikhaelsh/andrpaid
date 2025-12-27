@extends('layouts.app')

{{-- 1. CHANGED: Title --}}
@section('title', 'Profile')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/profile.css') }}">
@endsection

@section('content')
    @include('partials.navbarProfile')


@endsection

