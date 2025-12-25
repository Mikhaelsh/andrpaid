<nav class="profile-subnav sticky-subnav">
    <div class="container">
        <ul class="nav profile-tabs">

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/overview') ? 'active' : '' }}" href="#">
                    <i class="bi bi-book me-2"></i>Overview
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/papers') ? 'active' : '' }}" href="#">
                    <i class="bi bi-journal-code me-2"></i>Papers
                    <span class="badge-counter">12</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/stars') ? 'active' : '' }}" href="#">
                    <i class="bi bi-star me-2"></i>Stars
                    <span class="badge-counter">8</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/followers') ? 'active' : '' }}" href="#">
                    <i class="bi bi-people me-2"></i>Followers
                    <span class="badge-counter">1.2k</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
