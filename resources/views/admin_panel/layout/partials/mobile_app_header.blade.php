@php
    $dynCompName = \App\Models\Setting::get('company_name', 'Kent Hardware');
    $init1 = strtoupper(substr($dynCompName, 0, 1));
    $init2 = strlen($dynCompName) > 1 ? strtoupper(substr($dynCompName, 1, 1)) : '';
    $isCycleCountIndex = request()->routeIs('cycle_count.index') || request()->is('cycle-count');
    $isCountSheet = request()->routeIs('cycle_count.sheet');
@endphp

@if(!$isCycleCountIndex)
<!-- Mobile Native App Header (Visible only on < 992px) -->
<header class="app-mobile-topbar d-flex d-lg-none align-items-center justify-content-between px-3">
    <!-- Left: Navigation Icon + Screen Title (Flutter AppBar Style) -->
    <div class="d-flex align-items-center overflow-hidden">
        @if($isCountSheet)
            <a href="{{ route('cycle_count.index') }}" class="app-mobile-btn-hamburger me-2 text-decoration-none" aria-label="Back to counts" style="color: #ffffff;">
                <i class="fas fa-arrow-left" style="font-size: 17px;"></i>
            </a>
            <div class="d-flex flex-column ms-1 overflow-hidden">
                <span class="app-mobile-brand-title text-truncate" style="font-size: 16px; font-weight: 800; color: #ffffff;">Count Sheet</span>
                <span class="app-mobile-brand-sub" style="font-size: 11px; font-weight: 700; color: #93c5fd;">Blind Physical Inventory</span>
            </div>
        @else
            <button type="button" class="app-mobile-btn-hamburger me-2" id="mobileDrawerOpenBtn" aria-label="Open Navigation Menu">
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
            </button>
            <a href="{{ url('/home') }}" class="d-flex align-items-center text-decoration-none">
                <div class="app-mobile-logo-badge">
                    <span class="init-1">{{ $init1 }}</span><span class="init-2">{{ $init2 }}</span>
                </div>
                <div class="d-flex flex-column ms-2 overflow-hidden">
                    <span class="app-mobile-brand-title text-truncate">{{ $dynCompName }}</span>
                    <span class="app-mobile-brand-sub">
                        @if(Auth::check() && Auth::user()->usertype !== 'admin')
                            Staff Portal
                        @else
                            ERP System
                        @endif
                    </span>
                </div>
            </a>
        @endif
    </div>

    <!-- Right: Quick Actions & Profile -->
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
        @if(Auth::check() && Auth::user()->usertype !== 'admin')
            <a href="{{ route('cycle_count.index') }}" class="app-mobile-pos-pill text-decoration-none" style="background: rgba(37, 99, 235, 0.25); border-color: rgba(59, 130, 246, 0.5);" title="Cycle Counts">
                <i class="fas fa-clipboard-check text-primary"></i>
                <span>Counts</span>
            </a>
        @else
            @canany(['sales.create', 'sales.view'])
                <a href="{{ route('pos.index') }}" class="app-mobile-pos-pill text-decoration-none" title="Quick POS">
                    <i class="fas fa-bolt text-warning"></i>
                    <span>POS</span>
                </a>
            @endcanany
        @endif

        <!-- Mobile Notification Bell -->
        <div class="dropdown">
            <button class="app-mobile-icon-btn position-relative" type="button" data-toggle="dropdown" id="mobileNotificationBtn" aria-expanded="false" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger notification-badge" style="display: none; position: absolute; top: -3px; right: -3px; font-size: 8.5px; padding: 2px 4px; border-radius: 50%;">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-lg border-0 p-0"
                 aria-labelledby="mobileNotificationBtn"
                 style="width: 300px; border-radius: 14px; margin-top: 8px; overflow: hidden; z-index: 99999;">
                <div class="dropdown-header bg-white border-bottom py-3 px-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark small text-uppercase">Notifications</span>
                    <a href="{{ route('notifications.index') }}" class="text-primary small text-decoration-none">View All</a>
                </div>
                <div id="mobileNotificationList" style="max-height: 320px; overflow-y: auto;">
                    <div class="text-center p-3 text-muted small">Loading...</div>
                </div>
            </div>
        </div>

        <!-- Mobile Profile Dropdown -->
        <div class="dropdown">
            <button class="app-mobile-profile-avatar" type="button" data-toggle="dropdown" id="mobileProfileBtn" aria-expanded="false" title="Account">
                <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg p-2"
                 aria-labelledby="mobileProfileBtn"
                 style="border-radius: 14px; margin-top: 8px; min-width: 220px; z-index: 99999;">
                <div class="px-3 py-2 border-bottom mb-2 bg-light rounded-3">
                    <p class="mb-0 text-dark fw-bold small text-truncate">{{ Auth::user()->name }}</p>
                    <small class="text-muted text-break" style="font-size: 11px;">{{ Auth::user()->email ?? '' }}</small>
                </div>
                @canany(['settings.view', 'settings.read'])
                    <a class="dropdown-item d-flex align-items-center text-dark fw-semibold py-2 rounded-2" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog text-primary me-2"></i> Settings
                    </a>
                @endcanany
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger fw-semibold py-2 rounded-2">
                        <i class="fas fa-power-off text-danger me-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
@endif
