                        <!--=========================*
                              Home
                    *===========================-->
                        <li class="nav-item">
                            <a href="{{ url('/home') }}" class="nav-link"><i
                                    class="menu_icon fas fa-home"></i><span class="menu-title">Dashboard</span></a>

                        </li>
                        <!--=========================*
                              UI Features
                    *===========================-->
                        <li class="nav-item mega-menu">
                            @canany(['products.view', 'discount.products.view', 'categories.view', 'subcategories.view',
                                'brands.view', 'units.view', 'vendors.view', 'purchases.view', 'purchase_pos.create',
                                'warehouse.view', 'warehouse.stock.view', 'stock.transfer.view', 'stock.adjust.view', 'stock.adjust.create',
                                'sales.view', 'sales.create', 'customers.view', 'zones.view', 'sales.officers.view', 'receipts.voucher.view'])
                                <a href="#" class="nav-link">
                                     <i class="menu_icon fas fa-cogs"></i>
                                     <span class="menu-title">Management</span>
                                     <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <div class="col-group-wrapper row">
                                        <!-- Products & Categories -->
                                        @canany(['products.view', 'discount.products.view', 'categories.view',
                                            'subcategories.view', 'brands.view', 'units.view'])
                                            <div class="col-group col-md-3">
                                                <p class="category-heading">Products & Categories</p>
                                                <ul class="submenu-item">

                                                    @can('products.view')
                                                        <li><a href="{{ route('product') }}"><i class="fas fa-box"></i>
                                                                Products</a></li>
                                                        <li><a href="{{ route('opening_stock.index') }}"><i class="fas fa-cubes-stacked"></i>
                                                                Opening Stock & Pricing</a></li>
                                                    @endcan

                                                    @can('discount.products.view')
                                                        <li><a href="{{ route('discount.index') }}"><i class="fas fa-tags"></i>
                                                                Discount Products</a></li>
                                                    @endcan

                                                    @can('categories.view')
                                                        <li><a href="{{ route('Category.home') }}"><i class="fas fa-list"></i>
                                                                Category</a></li>
                                                    @endcan

                                                    @can('subcategories.view')
                                                        <li><a href="{{ route('subcategory.home') }}"><i
                                                                    class="fas fa-th-list"></i> Sub Category</a></li>
                                                    @endcan

                                                    @can('brands.view')
                                                        <li><a href="{{ route('Brand.home') }}"><i class="fas fa-trademark"></i>
                                                                Brands</a></li>
                                                    @endcan

                                                    @can('units.view')
                                                        <li><a href="{{ route('Unit.home') }}"><i
                                                                    class="fas fa-balance-scale"></i> Units</a></li>
                                                    @endcan

                                                    <li>
                                                        <a href="javascript:void(0)" class="open-pcode-modal-btn" onclick="window.openPCodeModal(event); return false;" data-bs-toggle="modal" data-bs-target="#pcodeModal" data-toggle="modal" data-target="#pcodeModal">
                                                            <i class="fas fa-barcode text-warning"></i> P-Code
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endcanany
                                        <!-- Purchase & Inventory -->
                                        @canany(['vendors.view', 'purchases.view', 'purchase_pos.create'])
                                            <div class="col-group col-md-3">
                                                <p class="category-heading">Purchase & Inventory</p>
                                                <ul class="submenu-item">
                                                    @can('vendors.view')
                                                        <li><a href="{{ url('vendor') }}"><i class="fas fa-truck"></i> Vendor</a>
                                                        </li>
                                                    @endcan
                                                    @can('purchases.view')
                                                        <li><a href="{{ route('Purchase.home') }}"><i
                                                                    class="fas fa-shopping-cart"></i> Purchase</a>
                                                        </li>
                                                    @endcan
                                                    @can('purchase_pos.create')
                                                        <li><a href="{{ route('purchase-pos.index') }}"><i
                                                                    class="fas fa-cash-register"></i> Purchase POS</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        @endcanany
                                        <!-- Accounts / Inventory -->
                                        @canany(['warehouse.view', 'warehouse.stock.view', 'stock.transfer.view', 'stock.adjust.view', 'stock.adjust.create'])
                                            <div class="col-group col-md-3">
                                                <p class="category-heading">Inventory Management</p>
                                                <ul class="submenu-item">
                                                    @can('warehouse.view')
                                                        <li><a href="{{ url('warehouse') }}"><i class="fas fa-warehouse"></i>
                                                                Warehouse</a></li>
                                                        <li><a href="{{ route('storage_locations.index') }}"><i class="fas fa-layer-group"></i>
                                                                Racks & Shelves</a></li>
                                                    @endcan
                                                    @can('warehouse.stock.view')
                                                        <li><a href="{{ url('warehouse_stocks') }}"><i class="fas fa-boxes"></i>
                                                                Stock</a></li>
                                                    @endcan
                                                    @can('stock.transfer.view')
                                                        <li><a href="{{ url('stock_transfers') }}"><i
                                                                    class="fas fa-exchange-alt"></i> Stock Transfer</a></li>
                                                    @endcan
                                                    @canany(['stock.adjust.view', 'stock.adjust.create'])
                                                        <li><a href="{{ route('stock_adjustments.index') }}"><i
                                                                    class="fas fa-sliders-h"></i> Stock Adjustment</a></li>
                                                    @endcanany
                                                    <li><a href="{{ route('opening_stock.index') }}"><i
                                                                class="fas fa-cubes-stacked text-primary"></i> Opening Stock</a></li>
                                                    <li><a href="{{ route('cycle_count.index') }}"><i
                                                                class="fas fa-clipboard-check text-success"></i> Cycle Count Audit</a></li>
                                                </ul>
                                            </div>
                                        @endcanany
                                        <!-- Customers & Sales -->
                                        @canany(['sales.view', 'sales.create', 'customers.view', 'sales.officers.view',
                                            'receipts.voucher.view', 'zones.view'])
                                            <div class="col-group col-md-3">
                                                <p class="category-heading">Sales & Customers</p>
                                                <ul class="submenu-item">
                                                    @can('sales.view')
                                                        <li><a href="{{ url('sale') }}"><i class="fas fa-receipt"></i>
                                                                Sales</a></li>
                                                    @endcan
                                                    @can('sales.create')
                                                        <li><a href="{{ route('pos.index') }}"><i class="fas fa-cash-register"></i>
                                                                POS System</a></li>
                                                    @endcan
                                                    @can('customers.view')
                                                        <li><a href="{{ url('customers') }}"><i class="fas fa-user"></i>
                                                                Customer</a></li>
                                                    @endcan
                                                    @can('customer_types.view')
                                                        <li><a href="{{ route('customer-types.index') }}"><i class="fas fa-tags"></i>
                                                                Customer Types</a></li>
                                                    @endcan
                                                    @can('zones.view')
                                                        <li><a href="{{ url('zone') }}"><i class="fas fa-map-marker-alt"></i>
                                                                Zone</a></li>
                                                    @endcan
                                                    @can('sales.officers.view')
                                                        <li><a href="{{ url('sales-officers') }}"><i class="fas fa-user-tie"></i>
                                                                Sales Officer</a></li>
                                                    @endcan
                                                    @can('receipts.voucher.view')
                                                        <li><a href="{{ route('all_recepit_vochers') }}"><i
                                                                    class="fas fa-file-invoice-dollar"></i>
                                                                Receipt Vouchers</a></li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            @endcanany
                        </li>


                        <!-- Vouchers Menu -->
                        <li class="nav-item">
                            @canany(['vouchers.create', 'vouchers.view', 'all.vouchers.view', 'chart.of.accounts.view', 'expense.voucher.view', 'receipts.voucher.view',
                                'journal.voucher.view', 'payment.voucher.view', 'income.voucher.view'])
                                <a href="#" class="nav-link">
                                    <i class="menu_icon fas fa-clipboard-list"></i>
                                    <span class="menu-title">Vouchers</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        @can('vouchers.create')
                                            <li><a href="{{ route('vouchers.create') }}"><i class="fa-solid fa-plus-circle"></i>
                                                    Create Voucher</a></li>
                                        @endcan
                                        @can('all.vouchers.view')
                                            <li><a href="{{ route('voucher.history') }}"><i class="fa-solid fa-list-check"></i>
                                                    All Vouchers</a></li>
                                        @endcan
                                        @can('chart.of.accounts.view')
                                            <li><a href="{{ route('view_all') }}"><i class="fa-solid fa-money-bill-wave"></i>
                                                    Char Of Accounts</a></li>
                                        @endcan
                                        @can('expense.voucher.view')
                                            <li><a href="{{ route('all_expense_vochers') }}"><i
                                                        class="fa-solid fa-money-bill-wave"></i> Expense Voucher</a></li>
                                            <li><a href="{{ route('expense_categories.index') }}"><i
                                                        class="fa-solid fa-list-check"></i> Expense Categories</a></li>
                                        @endcan
                                        @can('receipts.voucher.view')
                                            <li><a href="{{ route('all_recepit_vochers') }}"><i
                                                        class="fa-solid fa-wallet"></i> Receipts Voucher</a></li>
                                        @endcan
                                        @can('journal.voucher.view')
                                            <li><a href="{{ route('vouchers.index', 'journal voucher') }}"><i
                                                        class="fa-solid fa-wallet"></i> Journal Voucher</a></li>
                                        @endcan
                                        @can('payment.voucher.view')
                                            <li><a href="{{ route('all_Payment_vochers') }}"><i
                                                        class="fa-solid fa-wallet"></i> Payment Voucher</a></li>
                                        @endcan
                                        @can('income.voucher.view')
                                            <li><a href="{{ route('vouchers.index', 'income voucher') }}"><i
                                                        class="fa-solid fa-wallet"></i> Income Voucher</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            @endcanany
                        </li>
                        <li class="nav-item">
                            @canany(['item.stock.report.view', 'purchase.report.view', 'sale.report.view',
                                'customer.ledger.view', 'vendor.ledger.view', 'inventory.onhand.view', 'profit.loss.report.view',
                                'recovery.report.view', 'payable.report.view', 'parties.balance.report.view', 'aging.report.view', 'balance.sheet.report.view', 'executive.report.view'])
                                <a href="#" class="nav-link">
                                    <i class="menu_icon fas fa-clipboard-list"></i>
                                    <span class="menu-title">Reports</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        @can('executive.report.view')
                                            <li><a href="{{ route('report.executive') }}"><i class="fa-solid fa-crown text-warning"></i>
                                                    Executive Report</a></li>
                                        @endcan
                                        @can('item.stock.report.view')
                                            <li><a href="{{ route('report.item_stock') }}"><i class="fa-solid fa-users"></i>
                                                    Item Stock Report</a></li>
                                        @endcan
                                        @can('purchase.report.view')
                                            <li><a href="{{ route('report.purchase') }}"><i class="fa-solid fa-users"></i>
                                                    Purchase Report</a></li>
                                        @endcan
                                        @can('sale.report.view')
                                            <li><a href="{{ route('report.sale') }}"><i class="fa-solid fa-users"></i> Sale
                                                    Report</a></li>
                                            <li><a href="{{ route('report.product_sale_customer_wise') }}"><i class="fa-solid fa-users-between-lines"></i> Product Sale (Customer Wise)</a></li>
                                        @endcan
                                        @can('customer.ledger.view')
                                            <li><a href="{{ route('report.customer.ledger') }}"><i
                                                        class="fa-solid fa-book"></i> Customer Ledger</a></li>
                                        @endcan

                                        @can('vendor.ledger.view')
                                            <li><a href="{{ route('report.vendor.ledger') }}"><i
                                                        class="fa-solid fa-truck"></i> Vendor Ledger</a></li>
                                        @endcan


                                        @can('inventory.onhand.view')
                                            <li><a href="{{ route('reports.onhand') }}"><i class="fas fa-warehouse"></i>
                                                    Inventory On-Hand</a></li>
                                        @endcan
                                        
                                        @can('profit.loss.report.view')
                                            <li><a href="{{ route('report.profit_loss') }}"><i class="fa-solid fa-chart-line"></i>
                                                    Profit & Loss</a></li>
                                        @endcan
                                        
                                        @can('recovery.report.view')
                                            <li><a href="{{ route('report.recovery') }}"><i class="fa-solid fa-file-invoice-dollar"></i>
                                                    Recovery Report</a></li>
                                        @endcan

                                        @can('payable.report.view')
                                            <li><a href="{{ route('report.payable') }}"><i class="fa-solid fa-money-bill-wave"></i>
                                                    Payable Report</a></li>
                                        @endcan

                                        @can('parties.balance.report.view')
                                            <li><a href="{{ route('report.parties_balance') }}"><i class="fa-solid fa-users"></i>
                                                    Parties Balances</a></li>
                                        @endcan

                                        @can('aging.report.view')
                                            <li><a href="{{ route('report.aging') }}"><i class="fa-solid fa-hourglass-half"></i>
                                                    Aging Report</a></li>
                                        @endcan

                                        @can('balance.sheet.report.view')
                                            <li><a href="{{ route('report.balance_sheet') }}"><i class="fa-solid fa-scale-balanced"></i>
                                                    Balance Sheet</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            @endcanany
                        </li>
                        
                        <!-- Cashbook / Checkbook -->
                        @can('checkbook.view')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="menu_icon fas fa-wallet"></i>
                                <span class="menu-title">Cashbook</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="submenu">
                                <ul class="submenu-item">
                                    <li><a href="{{ route('checkbook.index') }}"><i class="fas fa-lock"></i> Day Closings</a></li>
                                    <li><a href="{{ route('checkbook.transactions') }}"><i class="fas fa-history"></i> Cashbook History</a></li>
                                </ul>
                            </div>
                        </li>
                        @endcan

                        <!-- HR Management Menu -->
                        <li class="nav-item">
                            @canany(['hr.departments.view', 'hr.designations.view', 'hr.employees.view',
                                'hr.attendance.view', 'hr.payroll.view', 'hr.leaves.view', 'hr.salary.structure.view',
                                'hr.shifts.view', 'hr.holidays.view', 'hr.loans.view', 'hr.biometric.devices.view'])
                                <a href="#" class="nav-link">
                                    <i class="menu_icon fas fa-users-cog"></i>
                                    <span class="menu-title">HR Management</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        @can('hr.departments.view')
                                            <li><a href="{{ route('hr.departments.index') }}"><i
                                                        class="fa-solid fa-building"></i> Departments</a></li>
                                        @endcan
                                        @can('hr.designations.view')
                                            <li><a href="{{ route('hr.designations.index') }}"><i
                                                        class="fa-solid fa-id-badge"></i> Designations</a></li>
                                        @endcan
                                        @can('hr.employees.view')
                                            <li><a href="{{ route('hr.employees.index') }}"><i
                                                        class="fa-solid fa-user-tie"></i> Employees</a></li>
                                        @endcan
                                        @can('hr.attendance.view')
                                            <li><a href="{{ route('hr.attendance.index') }}"><i
                                                        class="fa-solid fa-clock"></i> Attendance</a></li>
                                        @endcan
                                        @can('hr.payroll.view')
                                            <li><a href="{{ route('hr.payroll.index') }}"><i
                                                        class="fa-solid fa-money-check-alt"></i> Payroll</a></li>
                                        @endcan
                                        @can('hr.leaves.view')
                                            <li><a href="{{ route('hr.leaves.index') }}"><i
                                                        class="fa-solid fa-calendar-minus"></i> Leaves</a></li>
                                        @endcan
                                        @can('hr.salary.structure.view')
                                            <li><a href="{{ route('hr.salary-structure.index') }}"><i
                                                        class="fa-solid fa-coins"></i> Salary Structure</a></li>
                                        @endcan
                                        @can('hr.shifts.view')
                                            <li><a href="{{ route('hr.shifts.index') }}"><i class="fa-solid fa-clock"></i>
                                                    Shifts</a></li>
                                        @endcan
                                        @can('hr.holidays.view')
                                            <li><a href="{{ route('hr.holidays.index') }}"><i
                                                        class="fa-solid fa-calendar-alt"></i> Holidays</a></li>
                                        @endcan
                                        @can('hr.loans.view')
                                            <li><a href="{{ route('hr.loans.index') }}"><i
                                                        class="fa-solid fa-hand-holding-dollar"></i> Loans</a></li>
                                        @endcan
                                        @can('hr.biometric.devices.view')
                                            <li><a href="{{ route('hr.biometric-devices.index') }}"><i
                                                        class="fa-solid fa-fingerprint"></i> Biometric Devices</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            @endcanany
                        </li>
                        <!-- User Management Menu -->
                        <li class="nav-item">
                            @canany(['users.view', 'roles.view', 'permissions.view', 'branches.view'])
                                <a href="#" class="nav-link">
                                    <i class="menu_icon fas fa-clipboard-list"></i>
                                    <span class="menu-title">User Management</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        @can('users.view')
                                            <li><a href="{{ route('users.index') }}"><i class="fa-solid fa-users"></i>
                                                    Users</a></li>
                                        @endcan
                                        @can('roles.view')
                                            <li><a href="{{ route('roles.index') }}"><i class="fa-solid fa-user-lock"></i>
                                                    Roles</a></li>
                                        @endcan
                                        @can('permissions.view')
                                            <li><a href="{{ route('permissions.index') }}"><i
                                                        class="fa-solid fa-user-lock"></i> Permissions</a></li>
                                        @endcan
                                        @can('branches.view')
                                            <li><a href="{{ route('branch.index') }}"><i class="fa-solid fa-code-branch"></i>
                                                    Branches</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            @endcanany
                        </li>


                        <!-- Website Management -->
                        @canany(['website-settings.view', 'web_products.view', 'web_products.read', 'coupons.view', 'coupons.read', 'web_orders.view', 'web_orders.read', 'web_users.view', 'web_users.read'])
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="menu_icon fas fa-globe"></i>
                                    <span class="menu-title">Website Control</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="submenu">
                                    <ul class="submenu-item">
                                        @can('website-settings.view')
                                            <li><a href="{{ route('website_settings.index') }}"><i class="fa-solid fa-cogs"></i> Website Settings</a></li>
                                        @endcan
                                        @canany(['web_products.view', 'web_products.read'])
                                            <li><a href="{{ route('web_products.index') }}"><i class="fa-solid fa-box"></i> Web Products</a></li>
                                        @endcan
                                        @canany(['coupons.view', 'coupons.read'])
                                            <li><a href="{{ route('admin.coupons.index') }}"><i class="fas fa-tags"></i> Coupons</a></li>
                                        @endcan
                                        @canany(['web_orders.view', 'web_orders.read'])
                                            <li><a href="{{ route('web_orders.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Web Dashboard</a></li>
                                            <li><a href="{{ route('web_orders.index') }}"><i class="fa-solid fa-shopping-cart"></i> Web Orders</a></li>
                                        @endcan
                                        @canany(['web_users.view', 'web_users.read'])
                                            <li><a href="{{ route('web_users.index') }}"><i class="fas fa-users"></i> Web Users</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcanany

