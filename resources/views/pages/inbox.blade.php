@extends('layouts.app')

@section('title', __('inbox.title_read'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/inboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/inbox.css') }}">
@endsection

@section('content')
    @include('partials.navbarInbox')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="message-card">
                    <div class="message-toolbar">
                        <div>
                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-muted border">
                                <i class="bi bi-arrow-left me-1"></i> {{ __('inbox.btn_back') }}
                            </a>
                        </div>
                    </div>

                    <div class="message-header">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h4 class="fw-bold mb-0 text-dark">{{ $inbox->subject ?? __('inbox.no_subject') }}</h4>
                            <span class="badge bg-light text-secondary border">
                                {{ $inbox->created_at->format('M d, Y, h:i A') }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ $inbox->fromUser->name }}&background=random&color=fff"
                                 class="sender-avatar shadow-sm">

                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark fs-5">
                                    {{ $inbox->fromUser->name }}
                                </div>

                                <div class="message-meta d-flex flex-column flex-md-row gap-md-2">
                                    <span>
                                        <span class="text-muted">{{ __('inbox.label_from') }}</span>
                                        &lt;{{ $inbox->fromUser->email ?? 'no-reply' }}&gt;
                                    </span>

                                    <span class="d-none d-md-inline text-muted">•</span>

                                    <span>
                                        <span class="text-muted">{{ __('inbox.label_to') }}</span>
                                        <span class="text-dark fw-medium">{{ $inbox->toUser->name }} &lt;{{ $inbox->fromUser->email ?? 'no-reply' }}&gt; {{ $inbox->toUser->id === Auth::user()->id ? __('inbox.label_me') : '' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="message-body">
                        @if(!empty($inbox->body))
                            {!! nl2br(e($inbox->body)) !!}
                        @else
                            <div class="text-muted fst-italic p-3 bg-light rounded border border-light">
                                <i class="bi bi-info-circle me-2"></i> {{ __('inbox.empty_content') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
