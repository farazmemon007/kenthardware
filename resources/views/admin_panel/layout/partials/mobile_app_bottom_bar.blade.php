@php
    $isEmployee = Auth::check() && Auth::user()->usertype !== 'admin';
    $isCycleCountRoute = request()->is('cycle-count*');
@endphp

<!-- Fixed Native App Bottom Navigation Bar (Visible only on < 992px) -->
<nav class="app-mobile-bottom-bar d-flex d-lg-none" aria-label="Mobile Bottom Navigation">
    @if($isEmployee || $isCycleCountRoute)
        {{-- ===============================================
             EMPLOYEE & STAFF PORTAL BOTTOM NAVIGATION (Android App)
             =============================================== --}}
        <!-- Tab 1: Home / Dashboard -->
        <a href="{{ url('/home') }}" class="bottom-tab-item {{ (request()->is('home') || request()->is('dashboard')) && !$isCycleCountRoute ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-home"></i>
            </div>
            <span class="bottom-tab-label">Home</span>
        </a>

        <!-- Tab 2: Cycle Counts / My Tasks -->
        <a href="{{ route('cycle_count.index') }}" class="bottom-tab-item {{ request()->is('cycle-count') ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span class="bottom-tab-label">Counts</span>
        </a>

        <!-- Tab 3: Current Sheet / Scanner (Only if on sheet, or quick tasks) -->
        @if(request()->routeIs('cycle_count.sheet'))
            <a href="javascript:void(0)" class="bottom-tab-item active" onclick="document.getElementById('mQuickScannerInput')?.focus();">
                <div class="bottom-tab-icon">
                    <i class="fas fa-barcode"></i>
                </div>
                <span class="bottom-tab-label">Scanner</span>
            </a>
        @else
            <!-- Tab 3: My Attendance -->
            <a href="{{ route('my-attendance') }}" class="bottom-tab-item {{ request()->is('my-attendance*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <span class="bottom-tab-label">Attendance</span>
            </a>
        @endif

        @if(request()->routeIs('cycle_count.sheet'))
            <!-- Tab 4: My Attendance -->
            <a href="{{ route('my-attendance') }}" class="bottom-tab-item {{ request()->is('my-attendance*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <span class="bottom-tab-label">Attendance</span>
            </a>
        @endif

        <!-- Tab 5: App Menu Drawer Trigger -->
        <button type="button" class="bottom-tab-item" id="bottomDrawerTriggerBtn" aria-label="Open Navigation Menu">
            <div class="bottom-tab-icon">
                <i class="fas fa-bars"></i>
            </div>
            <span class="bottom-tab-label">Menu</span>
        </button>
    @else
        {{-- ===============================================
             ADMIN & MANAGER BOTTOM NAVIGATION
             =============================================== --}}
        <!-- Tab 1: Home / Dashboard -->
        <a href="{{ url('/home') }}" class="bottom-tab-item {{ request()->is('home') || request()->is('dashboard') ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-home"></i>
            </div>
            <span class="bottom-tab-label">Home</span>
        </a>

        <!-- Tab 2: POS Quick Sale -->
        @canany(['sales.create', 'sales.view'])
            <a href="{{ route('pos.index') }}" class="bottom-tab-item {{ request()->is('pos*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <span class="bottom-tab-label">POS</span>
            </a>
        @else
            <a href="{{ url('sale') }}" class="bottom-tab-item {{ request()->is('sale*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="bottom-tab-label">Sales</span>
            </a>
        @endcanany

        <!-- Tab 3: Products / Stock -->
        @can('products.view')
            <a href="{{ route('product') }}" class="bottom-tab-item {{ request()->is('product*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <span class="bottom-tab-label">Products</span>
            </a>
        @else
            <a href="{{ route('checkbook.index') }}" class="bottom-tab-item {{ request()->is('checkbook*') ? 'active' : '' }}">
                <div class="bottom-tab-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="bottom-tab-label">Cashbook</span>
            </a>
        @endcan

        <!-- Tab 4: Physical Cycle Counts & Audit -->
        <a href="{{ route('cycle_count.index') }}" class="bottom-tab-item {{ request()->is('cycle-count*') ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span class="bottom-tab-label">Audit</span>
        </a>

        <!-- Tab 5: App Menu Drawer Trigger -->
        <button type="button" class="bottom-tab-item" id="bottomDrawerTriggerBtn" aria-label="Open Navigation Menu">
            <div class="bottom-tab-icon">
                <i class="fas fa-bars"></i>
            </div>
            <span class="bottom-tab-label">Menu</span>
        </button>
    @endif
</nav>
