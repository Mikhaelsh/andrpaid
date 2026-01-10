@php
    $inboxCount = \App\Models\Inbox::where("to_user_id", Auth::user()->id)->where("is_sent", true)->count();
    $sentCount = \App\Models\Inbox::where("from_user_id", Auth::user()->id)->where("is_sent", true)->count();
    $draftCount = \App\Models\Inbox::where("from_user_id", Auth::user()->id)->where("is_sent", false)->count();
@endphp

<div class="inbox-subnav border-bottom bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <nav class="nav nav-underline pb-0 gap-4">
                <a class="nav-link {{ request()->is('inboxes') ? 'active' : '' }}" href="/inboxes">
                    <i class="bi bi-inbox me-2"></i>{{ __('navbarInbox.inbox') }}
                    @if ($inboxCount != 0)
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $inboxCount }}</span>
                    @endif
                </a>

                <a class="nav-link {{ request()->is('inboxes/sent') ? 'active' : '' }}" href="/inboxes/sent">
                    <i class="bi bi-send me-2"></i>{{ __('navbarInbox.sent') }}
                    @if ($sentCount != 0)
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $sentCount }}</span>
                    @endif
                </a>

                <a class="nav-link {{ request()->is('inboxes/drafts') ? 'active' : '' }}" href="/inboxes/drafts">
                    <i class="bi bi-file-earmark me-2"></i>{{ __('navbarInbox.drafts') }}
                    @if ($draftCount != 0)
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $draftCount }}</span>
                    @endif
                </a>
            </nav>

            <div class="py-2">
                <a href="/inboxes/compose" class="btn btn-primary btn-sm fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-pencil-square me-2"></i>{{ __('navbarInbox.compose') }}
                </a>
            </div>

        </div>
    </div>
</div>
