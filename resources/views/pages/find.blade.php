@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalCSS')
    <link rel="stylesheet" href="styles/auth.css">
@endsection

@section('content')
    @foreach ($lecturers as $lecturer)
        <a href="/{{ $lecturer->user->profileId }}/overview">{{ $lecturer->user->name }}</a>
        <br><br><hr><br><br>
    @endforeach
@endsection
