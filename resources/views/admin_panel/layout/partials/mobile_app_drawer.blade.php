<!-- Mobile App Slide-in Offcanvas Drawer -->
<div class="app-mobile-drawer-backdrop d-lg-none" id="mobileAppDrawerBackdrop" style="display: none !important;"></div>

<aside class="app-mobile-drawer d-lg-none" id="mobileAppDrawer" aria-label="Mobile Navigation Menu" style="display: none !important;">
    <!-- Drawer Header: User Profile Card -->
    <div class="app-drawer-user-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <div class="app-drawer-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="ms-3 overflow-hidden">
                    <h6 class="app-drawer-user-name mb-0 text-truncate">{{ Auth::user()->name }}</h6>
                    <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-50 mt-1" style="font-size: 10px;">
                        {{ Auth::user()->email === 'admin@admin.com' ? 'Super Admin' : (Auth::user()->roles->first()->name ?? 'User') }}
                    </span>
                </div>
            </div>
            <button type="button" class="app-drawer-close-btn" id="mobileDrawerCloseBtn" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="text-white-50 small text-truncate" style="font-size: 11px;">
            <i class="far fa-envelope me-1"></i> {{ Auth::user()->email ?? '' }}
        </div>
    </div>

    <!-- Quick Touch Shortcuts Row -->
    <div class="app-drawer-quick-actions px-3 py-2 border-bottom">
        <div class="row g-2 text-center">
            @if(Auth::check() && Auth::user()->usertype !== 'admin')
                <div class="col-4">
                    <a href="{{ route('cycle_count.index') }}" class="app-drawer-quick-btn text-decoration-none">
                        <div class="quick-icon bg-indigo"><i class="fas fa-clipboard-check"></i></div>
                        <span>Counts</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('my-attendance') }}" class="app-drawer-quick-btn text-decoration-none">
                        <div class="quick-icon bg-emerald"><i class="fas fa-user-clock"></i></div>
                        <span>Attendance</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ url('/home') }}" class="app-drawer-quick-btn text-decoration-none">
                        <div class="quick-icon bg-sky"><i class="fas fa-home"></i></div>
                        <span>Home</span>
                    </a>
                </div>
            @else
                @canany(['sales.create', 'sales.view'])
                    <div class="col-3">
                        <a href="{{ route('pos.index') }}" class="app-drawer-quick-btn text-decoration-none">
                            <div class="quick-icon bg-indigo"><i class="fas fa-cash-register"></i></div>
                            <span>POS</span>
                        </a>
                    </div>
                @endcanany
                <div class="col-3">
                    <a href="{{ route('cycle_count.index') }}" class="app-drawer-quick-btn text-decoration-none">
                        <div class="quick-icon bg-emerald"><i class="fas fa-clipboard-check"></i></div>
                        <span>Counts</span>
                    </a>
                </div>
                @can('products.view')
                    <div class="col-3">
                        <a href="{{ route('product') }}" class="app-drawer-quick-btn text-decoration-none">
                            <div class="quick-icon bg-sky"><i class="fas fa-boxes-stacked"></i></div>
                            <span>Items</span>
                        </a>
                    </div>
                @endcan
                @canany(['item.stock.report.view', 'purchase.report.view', 'sale.report.view'])
                    <div class="col-3">
                        <a href="{{ route('report.sale') }}" class="app-drawer-quick-btn text-decoration-none">
                            <div class="quick-icon bg-amber"><i class="fas fa-chart-line"></i></div>
                            <span>Reports</span>
                        </a>
                    </div>
                @endcanany
            @endif
        </div>
    </div>

    <!-- Drawer Navigation Accordion Items -->
    <div class="app-drawer-nav-container">
        <ul class="app-drawer-menu-list">
            <!-- 1. Dashboard -->
            <li class="app-drawer-item {{ request()->is('home') || request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/home') }}" class="app-drawer-link">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-home app-menu-icon"></i>
                        <span class="app-menu-title">Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- 1.1 Physical Cycle Counts (Staff & Audit Portal) -->
            <li class="app-drawer-item {{ request()->is('cycle-count*') ? 'active' : '' }}">
                <a href="{{ route('cycle_count.index') }}" class="app-drawer-link">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-check app-menu-icon" style="color: #2563eb;"></i>
                        <span class="app-menu-title">Cycle Counts &amp; Audit</span>
                    </div>
                    <span class="badge badge-primary" style="font-size: 10px; padding: 2px 6px;">Portal</span>
                </a>
            </li>

            <!-- 1.2 My Attendance -->
            <li class="app-drawer-item {{ request()->is('my-attendance*') ? 'active' : '' }}">
                <a href="{{ route('my-attendance') }}" class="app-drawer-link">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-clock app-menu-icon" style="color: #10b981;"></i>
                        <span class="app-menu-title">My Attendance</span>
                    </div>
                </a>
            </li>

            <!-- 2. Management -->
            @canany(['products.view', 'discount.products.view', 'categories.view', 'subcategories.view',
                'brands.view', 'units.view', 'vendors.view', 'purchases.view', 'purchase_pos.create',
                'warehouse.view', 'warehouse.stock.view', 'stock.transfer.view', 'stock.adjust.view', 'stock.adjust.create',
                'sales.view', 'sales.create', 'customers.view', 'zones.view', 'sales.officers.view', 'receipts.voucher.view'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-cogs app-menu-icon"></i>
                        <span class="app-menu-title">Management</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    <!-- Products -->
                    @can('products.view')
                        <a href="{{ route('product') }}" class="app-drawer-sublink"><i class="fas fa-box"></i> Products</a>
                        <a href="{{ route('opening_stock.index') }}" class="app-drawer-sublink"><i class="fas fa-cubes-stacked"></i> Opening Stock & Pricing</a>
                    @endcan
                    @can('discount.products.view')
                        <a href="{{ route('discount.index') }}" class="app-drawer-sublink"><i class="fas fa-tags"></i> Discount Products</a>
                    @endcan
                    @can('categories.view')
                        <a href="{{ route('Category.home') }}" class="app-drawer-sublink"><i class="fas fa-list"></i> Category</a>
                    @endcan
                    @can('subcategories.view')
                        <a href="{{ route('subcategory.home') }}" class="app-drawer-sublink"><i class="fas fa-th-list"></i> Sub Category</a>
                    @endcan
                    @can('brands.view')
                        <a href="{{ route('Brand.home') }}" class="app-drawer-sublink"><i class="fas fa-trademark"></i> Brands</a>
                    @endcan
                    @can('units.view')
                        <a href="{{ route('Unit.home') }}" class="app-drawer-sublink"><i class="fas fa-balance-scale"></i> Units</a>
                    @endcan
                    <!-- Purchases & Vendors -->
                    @can('vendors.view')
                        <a href="{{ url('vendor') }}" class="app-drawer-sublink"><i class="fas fa-truck"></i> Vendor</a>
                    @endcan
                    @can('purchases.view')
                        <a href="{{ route('Purchase.home') }}" class="app-drawer-sublink"><i class="fas fa-shopping-cart"></i> Purchase</a>
                    @endcan
                    @can('purchase_pos.create')
                        <a href="{{ route('purchase-pos.index') }}" class="app-drawer-sublink"><i class="fas fa-cash-register"></i> Purchase POS</a>
                    @endcan
                    <!-- Warehouse & Stock -->
                    @can('warehouse.view')
                        <a href="{{ url('warehouse') }}" class="app-drawer-sublink"><i class="fas fa-warehouse"></i> Warehouse</a>
                        <a href="{{ route('storage_locations.index') }}" class="app-drawer-sublink"><i class="fas fa-layer-group"></i> Racks & Shelves</a>
                    @endcan
                    <a href="{{ route('cycle_count.index') }}" class="app-drawer-sublink"><i class="fas fa-clipboard-check text-primary"></i> Cycle Counts &amp; Audit</a>
                    @can('warehouse.stock.view')
                        <a href="{{ url('warehouse_stocks') }}" class="app-drawer-sublink"><i class="fas fa-boxes"></i> Warehouse Stocks</a>
                    @endcan
                    @can('stock.transfer.view')
                        <a href="{{ url('stock_transfers') }}" class="app-drawer-sublink"><i class="fas fa-exchange-alt"></i> Stock Transfer</a>
                    @endcan
                    @canany(['stock.adjust.view', 'stock.adjust.create'])
                        <a href="{{ route('stock_adjustments.index') }}" class="app-drawer-sublink"><i class="fas fa-sliders-h"></i> Stock Adjustment</a>
                    @endcanany
                    <!-- Sales & Customers -->
                    @can('sales.view')
                        <a href="{{ url('sale') }}" class="app-drawer-sublink"><i class="fas fa-receipt"></i> Sales</a>
                    @endcan
                    @can('sales.create')
                        <a href="{{ route('pos.index') }}" class="app-drawer-sublink"><i class="fas fa-cash-register"></i> POS System</a>
                    @endcan
                    @can('customers.view')
                        <a href="{{ url('customers') }}" class="app-drawer-sublink"><i class="fas fa-user"></i> Customer</a>
                    @endcan
                    @can('customer_types.view')
                        <a href="{{ route('customer-types.index') }}" class="app-drawer-sublink"><i class="fas fa-tags"></i> Customer Types</a>
                    @endcan
                    @can('zones.view')
                        <a href="{{ url('zone') }}" class="app-drawer-sublink"><i class="fas fa-map-marker-alt"></i> Zones</a>
                    @endcan
                    @can('sales.officers.view')
                        <a href="{{ url('sales-officers') }}" class="app-drawer-sublink"><i class="fas fa-user-tie"></i> Sales Officers</a>
                        <a href="{{ route('carpenters.index') }}" class="app-drawer-sublink"><i class="fas fa-hammer"></i> Carpenters</a>
                        <a href="{{ route('carpenter_commissions.index') }}" class="app-drawer-sublink"><i class="fas fa-hand-holding-usd"></i> Carpenter Commissions</a>
                    @endcan
                    @can('receipts.voucher.view')
                        <a href="{{ route('all_recepit_vochers') }}" class="app-drawer-sublink"><i class="fas fa-file-invoice-dollar"></i> Receipt Vouchers</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 3. Vouchers -->
            @canany(['vouchers.create', 'vouchers.view', 'all.vouchers.view', 'chart.of.accounts.view', 'expense.voucher.view', 'receipts.voucher.view',
                'journal.voucher.view', 'payment.voucher.view', 'income.voucher.view'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-list app-menu-icon"></i>
                        <span class="app-menu-title">Vouchers</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    @can('vouchers.create')
                        <a href="{{ route('vouchers.create') }}" class="app-drawer-sublink"><i class="fa-solid fa-plus-circle"></i> Create Voucher</a>
                    @endcan
                    @can('all.vouchers.view')
                        <a href="{{ route('voucher.history') }}" class="app-drawer-sublink"><i class="fa-solid fa-list-check"></i> All Vouchers</a>
                    @endcan
                    @can('chart.of.accounts.view')
                        <a href="{{ route('view_all') }}" class="app-drawer-sublink"><i class="fa-solid fa-money-bill-wave"></i> Chart Of Accounts</a>
                    @endcan
                    @can('expense.voucher.view')
                        <a href="{{ route('all_expense_vochers') }}" class="app-drawer-sublink"><i class="fa-solid fa-money-bill-wave"></i> Expense Voucher</a>
                        <a href="{{ route('expense_categories.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-list-check"></i> Expense Categories</a>
                    @endcan
                    @can('receipts.voucher.view')
                        <a href="{{ route('all_recepit_vochers') }}" class="app-drawer-sublink"><i class="fa-solid fa-wallet"></i> Receipts Voucher</a>
                    @endcan
                    @can('journal.voucher.view')
                        <a href="{{ route('vouchers.index', 'journal voucher') }}" class="app-drawer-sublink"><i class="fa-solid fa-wallet"></i> Journal Voucher</a>
                    @endcan
                    @can('payment.voucher.view')
                        <a href="{{ route('all_Payment_vochers') }}" class="app-drawer-sublink"><i class="fa-solid fa-wallet"></i> Payment Voucher</a>
                    @endcan
                    @can('income.voucher.view')
                        <a href="{{ route('vouchers.index', 'income voucher') }}" class="app-drawer-sublink"><i class="fa-solid fa-wallet"></i> Income Voucher</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 4. Reports -->
            @canany(['item.stock.report.view', 'purchase.report.view', 'sale.report.view',
                'customer.ledger.view', 'vendor.ledger.view', 'inventory.onhand.view', 'profit.loss.report.view',
                'recovery.report.view', 'payable.report.view', 'parties.balance.report.view', 'aging.report.view', 'balance.sheet.report.view', 'executive.report.view'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-pie app-menu-icon"></i>
                        <span class="app-menu-title">Reports</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    @can('executive.report.view')
                        <a href="{{ route('report.executive') }}" class="app-drawer-sublink"><i class="fa-solid fa-crown text-warning"></i> Executive Report</a>
                    @endcan
                    @can('sale.report.view')
                        <a href="{{ route('report.sale') }}" class="app-drawer-sublink"><i class="fa-solid fa-users"></i> Sale Report</a>
                        <a href="{{ route('report.product_sale_customer_wise') }}" class="app-drawer-sublink"><i class="fa-solid fa-users-between-lines"></i> Product Sale (Customer Wise)</a>
                    @endcan
                    @can('item.stock.report.view')
                        <a href="{{ route('report.item_stock') }}" class="app-drawer-sublink"><i class="fa-solid fa-boxes-stacked"></i> Item Stock Report</a>
                    @endcan
                    @can('purchase.report.view')
                        <a href="{{ route('report.purchase') }}" class="app-drawer-sublink"><i class="fa-solid fa-shopping-cart"></i> Purchase Report</a>
                    @endcan
                    @can('customer.ledger.view')
                        <a href="{{ route('report.customer.ledger') }}" class="app-drawer-sublink"><i class="fa-solid fa-book"></i> Customer Ledger</a>
                    @endcan
                    @can('vendor.ledger.view')
                        <a href="{{ route('report.vendor.ledger') }}" class="app-drawer-sublink"><i class="fa-solid fa-truck"></i> Vendor Ledger</a>
                    @endcan
                    @can('inventory.onhand.view')
                        <a href="{{ route('reports.onhand') }}" class="app-drawer-sublink"><i class="fas fa-warehouse"></i> Inventory On-Hand</a>
                    @endcan
                    @can('profit.loss.report.view')
                        <a href="{{ route('report.profit_loss') }}" class="app-drawer-sublink"><i class="fa-solid fa-chart-line"></i> Profit & Loss</a>
                    @endcan
                    @can('recovery.report.view')
                        <a href="{{ route('report.recovery') }}" class="app-drawer-sublink"><i class="fa-solid fa-file-invoice-dollar"></i> Recovery Report</a>
                    @endcan
                    @can('payable.report.view')
                        <a href="{{ route('report.payable') }}" class="app-drawer-sublink"><i class="fa-solid fa-money-bill-wave"></i> Payable Report</a>
                    @endcan
                    @can('parties.balance.report.view')
                        <a href="{{ route('report.parties_balance') }}" class="app-drawer-sublink"><i class="fa-solid fa-users"></i> Parties Balances</a>
                    @endcan
                    @can('aging.report.view')
                        <a href="{{ route('report.aging') }}" class="app-drawer-sublink"><i class="fa-solid fa-hourglass-half"></i> Aging Report</a>
                    @endcan
                    @can('balance.sheet.report.view')
                        <a href="{{ route('report.balance_sheet') }}" class="app-drawer-sublink"><i class="fa-solid fa-scale-balanced"></i> Balance Sheet</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 5. Cashbook -->
            @can('checkbook.view')
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-wallet app-menu-icon"></i>
                        <span class="app-menu-title">Cashbook</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    <a href="{{ route('checkbook.index') }}" class="app-drawer-sublink"><i class="fas fa-lock"></i> Day Closings</a>
                    <a href="{{ route('checkbook.transactions') }}" class="app-drawer-sublink"><i class="fas fa-history"></i> Cashbook History</a>
                </div>
            </li>
            @endcan

            <!-- 6. HR Management -->
            @canany(['hr.departments.view', 'hr.designations.view', 'hr.employees.view',
                'hr.attendance.view', 'hr.payroll.view', 'hr.leaves.view', 'hr.salary.structure.view',
                'hr.shifts.view', 'hr.holidays.view', 'hr.loans.view', 'hr.biometric.devices.view'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users-cog app-menu-icon"></i>
                        <span class="app-menu-title">HR Management</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    @can('hr.departments.view')
                        <a href="{{ route('hr.departments.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-building"></i> Departments</a>
                    @endcan
                    @can('hr.designations.view')
                        <a href="{{ route('hr.designations.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-id-badge"></i> Designations</a>
                    @endcan
                    @can('hr.employees.view')
                        <a href="{{ route('hr.employees.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-user-tie"></i> Employees</a>
                    @endcan
                    @can('hr.attendance.view')
                        <a href="{{ route('hr.attendance.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-clock"></i> Attendance</a>
                    @endcan
                    @can('hr.payroll.view')
                        <a href="{{ route('hr.payroll.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-money-check-alt"></i> Payroll</a>
                    @endcan
                    @can('hr.leaves.view')
                        <a href="{{ route('hr.leaves.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-calendar-minus"></i> Leaves</a>
                    @endcan
                    @can('hr.salary.structure.view')
                        <a href="{{ route('hr.salary-structure.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-coins"></i> Salary Structure</a>
                    @endcan
                    @can('hr.shifts.view')
                        <a href="{{ route('hr.shifts.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-clock"></i> Shifts</a>
                    @endcan
                    @can('hr.holidays.view')
                        <a href="{{ route('hr.holidays.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-calendar-alt"></i> Holidays</a>
                    @endcan
                    @can('hr.loans.view')
                        <a href="{{ route('hr.loans.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-hand-holding-dollar"></i> Loans</a>
                    @endcan
                    @can('hr.biometric.devices.view')
                        <a href="{{ route('hr.biometric-devices.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-fingerprint"></i> Biometric Devices</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 7. User Management -->
            @canany(['users.view', 'roles.view', 'permissions.view', 'branches.view'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-shield app-menu-icon"></i>
                        <span class="app-menu-title">User Management</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    @can('users.view')
                        <a href="{{ route('users.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-users"></i> Users</a>
                    @endcan
                    @can('roles.view')
                        <a href="{{ route('roles.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-user-lock"></i> Roles</a>
                    @endcan
                    @can('permissions.view')
                        <a href="{{ route('permissions.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-key"></i> Permissions</a>
                    @endcan
                    @can('branches.view')
                        <a href="{{ route('branch.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-code-branch"></i> Branches</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 8. Website Control -->
            @canany(['website-settings.view', 'web_products.view', 'web_products.read', 'coupons.view', 'coupons.read', 'web_orders.view', 'web_orders.read', 'web_users.view', 'web_users.read'])
            <li class="app-drawer-item has-submenu">
                <a href="javascript:void(0)" class="app-drawer-link drawer-accordion-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-globe app-menu-icon"></i>
                        <span class="app-menu-title">Website Control</span>
                    </div>
                    <i class="fas fa-chevron-right app-drawer-arrow"></i>
                </a>
                <div class="app-drawer-submenu">
                    @can('website-settings.view')
                        <a href="{{ route('website_settings.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-cogs"></i> Website Settings</a>
                    @endcan
                    @canany(['web_products.view', 'web_products.read'])
                        <a href="{{ route('web_products.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-box"></i> Web Products</a>
                    @endcan
                    @canany(['coupons.view', 'coupons.read'])
                        <a href="{{ route('admin.coupons.index') }}" class="app-drawer-sublink"><i class="fas fa-tags"></i> Coupons</a>
                    @endcan
                    @canany(['web_orders.view', 'web_orders.read'])
                        <a href="{{ route('web_orders.dashboard') }}" class="app-drawer-sublink"><i class="fa-solid fa-chart-line"></i> Web Dashboard</a>
                        <a href="{{ route('web_orders.index') }}" class="app-drawer-sublink"><i class="fa-solid fa-shopping-cart"></i> Web Orders</a>
                    @endcan
                    @canany(['web_users.view', 'web_users.read'])
                        <a href="{{ route('web_users.index') }}" class="app-drawer-sublink"><i class="fas fa-users"></i> Web Users</a>
                    @endcan
                </div>
            </li>
            @endcanany

            <!-- 9. Settings -->
            @canany(['settings.view', 'settings.read'])
            <li class="app-drawer-item {{ request()->is('settings*') ? 'active' : '' }}">
                <a href="{{ route('settings.index') }}" class="app-drawer-link">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-cog app-menu-icon"></i>
                        <span class="app-menu-title">Settings</span>
                    </div>
                </a>
            </li>
            @endcanany
        </ul>
    </div>

    <!-- Drawer Footer Actions -->
    <div class="app-drawer-footer p-3 border-top">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 fw-bold" style="border-radius: 10px; height: 42px;">
                <i class="fas fa-power-off"></i> Logout Account
            </button>
        </form>
        <div class="text-center text-muted mt-2" style="font-size: 10px;">
            Kent Hardware ERP &bull; v2.5
        </div>
    </div>
</aside>
