<!-- Fixed Native App Bottom Navigation Bar (Visible only on < 992px) -->
<nav class="app-mobile-bottom-bar d-flex d-lg-none" aria-label="Mobile Bottom Navigation">
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
            <div class="bottom-tab-icon pos-badge-icon">
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

    <!-- Tab 4: Reports -->
    @canany(['item.stock.report.view', 'purchase.report.view', 'sale.report.view'])
        <a href="{{ route('report.sale') }}" class="bottom-tab-item {{ request()->is('report*') ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="bottom-tab-label">Reports</span>
        </a>
    @else
        <a href="{{ url('customers') }}" class="bottom-tab-item {{ request()->is('customers*') ? 'active' : '' }}">
            <div class="bottom-tab-icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <span class="bottom-tab-label">Customers</span>
        </a>
    @endcanany

    <!-- Tab 5: App Menu Drawer Trigger -->
    <button type="button" class="bottom-tab-item border-0 bg-transparent" id="bottomDrawerTriggerBtn">
        <div class="bottom-tab-icon">
            <i class="fas fa-bars"></i>
        </div>
        <span class="bottom-tab-label">Menu</span>
    </button>
</nav>
