<nav class="profile-subnav sticky-subnav">
    <div class="container">
        <ul class="nav profile-tabs">

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/overview') ? 'active' : '' }}" href="/{{ $navbarProfileData["profileId"] }}/overview">
                    <i class="bi bi-book me-2"></i>{{ __('navbarProfile.overview') }}
                </a>
            </li>

            @if($user->isLecturer())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/papers') ? 'active' : '' }}" href="/{{ $navbarProfileData["profileId"] }}/papers">
                    <i class="bi bi-journal-code me-2"></i>{{ __('navbarProfile.papers') }}
                    <span class="badge-counter">{{ $navbarProfileData["papersCount"] }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/stars') ? 'active' : '' }}" href="/{{ $navbarProfileData["profileId"] }}/stars">
                    <i class="bi bi-star me-2"></i>{{ __('navbarProfile.stars') }}
                    <span class="badge-counter" id="navbarProfileStarsCount">{{ $navbarProfileData["starsCount"] }}</span>
                </a>
            </li>
            @endif

            @if($user->isUniversity())
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('*/papers') ? 'active' : '' }}" href="/{{ $navbarProfileData["profileId"] }}/papers">
                        <i class="bi bi-journal-text me-2"></i>{{ __('navbarProfile.publications') }}
                        <span class="badge-counter">{{ $navbarProfileData["papersCount"] ?? 0 }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('*/researchers') ? 'active' : '' }}" href="/{{ $navbarProfileData["profileId"] }}/researchers">
                        <i class="bi bi-people-fill me-2"></i>{{ __('navbarProfile.researchers') }}
                        <span class="badge-counter">{{ $navbarProfileData["researchersCount"] ?? 0 }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
