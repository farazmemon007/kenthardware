@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* ==========================================================
           QUICKBOOKS POS "SALES MANAGEMENT" PIXEL-PERFECT THEME
           Matching add_sale222.blade.php Color Palette & Styling
           ========================================================== */

        /* Universal Space Reset to Eliminate Scrolling */
        .app-page-body {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            min-height: auto !important;
        }
        .main-content {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            min-height: auto !important;
        }
        .main-content-inner {
            padding: 0 !important;
        }
        footer {
            display: none !important;
        }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

        html, body,
        .odoo-page-wrapper,
        .qb-table,
        .qb-table thead th,
        .qb-table tbody td,
        .qb-win-header,
        .qb-filter-bar,
        .qb-table-card,
        .dataTables_wrapper,
        input, button, select, textarea {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .odoo-page-wrapper {
            padding: 0 4px !important;
        }

        /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS Title Bar) */
        .qb-win-header {
            height: 36px !important;
            min-height: 36px !important;
            background: linear-gradient(180deg, #18385e 0%, #102641 100%) !important;
            color: #ffffff !important;
            border-radius: 5px 5px 0 0;
            border: 1px solid #0a192b;
            border-bottom: 1px solid #06111f;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
            margin-bottom: 4px !important;
        }
        .qb-win-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #ffffff;
        }
        .qb-win-title i {
            color: #38bdf8;
            font-size: 14px;
        }
        .qb-win-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* QuickBooks Glossy Action Buttons (Like Sale Screen) */
        .qb-btn-green {
            background: linear-gradient(180deg, #53af41 0%, #35832a 100%) !important;
            border: 1px solid #286c1f !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 12px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
        }
        .qb-btn-green:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        .qb-btn-blue {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 10px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
        }
        .qb-btn-blue:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        .qb-btn-red {
            background: linear-gradient(180deg, #e05252 0%, #c33838 100%) !important;
            border: 1px solid #942222 !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 10px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
        }
        .qb-btn-red:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        /* 2. Sub-Header Strip (Steel Blue Gradient - Exact POS Order Ribbon) */
        .qb-sub-ribbon {
            background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
            border: 1px solid #b2c5dc;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 4px !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        /* Status Tabs (Like QuickBooks Order Tabs in Image 1) */
        .qb-status-tabs {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .qb-tab-pill {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid #a8bed8;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            color: #193452;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            height: 24px;
            transition: all 0.12s ease;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .qb-tab-pill:hover {
            background: #ffffff;
            color: #0f2744;
            border-color: #8ca4c3;
        }
        .qb-tab-pill.active {
            background: #ffffff !important;
            border: 1.5px solid #18385e !important;
            color: #102641 !important;
            font-weight: 800;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) !important;
        }
        .qb-tab-pill.active-success {
            background: #ffffff !important;
            border: 1.5px solid #15803d !important;
            color: #15803d !important;
            font-weight: 800;
        }
        .qb-tab-pill.active-warning {
            background: #ffffff !important;
            border: 1.5px solid #b45309 !important;
            color: #b45309 !important;
            font-weight: 800;
        }
        .qb-tab-pill.active-info {
            background: #ffffff !important;
            border: 1.5px solid #0369a1 !important;
            color: #0369a1 !important;
            font-weight: 800;
        }
        .qb-tab-pill.active-danger {
            background: #ffffff !important;
            border: 1.5px solid #b91c1c !important;
            color: #b91c1c !important;
            font-weight: 800;
        }
        .qb-tab-pill .badge {
            font-size: 9.5px;
            padding: 1px 4px;
            border-radius: 6px;
            font-family: Consolas, monospace;
        }

        /* Financial Summary Chips (Like Bottom Strip in Image 1) */
        .qb-kpi-group {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }
        .qb-kpi-chip {
            background: #ffffff;
            border: 1px solid #8ca4c3;
            border-radius: 4px;
            padding: 2px 7px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            line-height: 1.2;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .qb-kpi-chip .chip-label {
            font-weight: 700;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
        }
        .qb-kpi-chip .chip-val {
            font-weight: 800;
            font-family: Consolas, monospace;
        }

        /* 3. Filter Bar (Light Slate Blue Gradient Strip) */
        .qb-filter-bar {
            background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
            border: 1px solid #b9cce2;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 5px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        .qb-filter-form {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: nowrap;
            width: 100%;
            margin: 0;
            overflow-x: auto;
        }
        .qb-filter-col {
            position: relative;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }
        .qb-input {
            height: 26px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            color: #0f2744 !important;
            padding: 1px 6px !important;
            background-color: #ffffff;
        }
        .qb-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
        }

        /* Datepicker Container - Strictly Constrained */
        .qb-filter-col.date-col {
            width: 100px !important;
            min-width: 100px !important;
            max-width: 100px !important;
            flex: 0 0 100px !important;
        }
        .qb-filter-col.date-col input,
        .qb-filter-col.date-col .flatpickr-wrapper,
        .qb-filter-col.date-col .input,
        .qb-filter-col.date-col .form-control {
            width: 100px !important;
            min-width: 100px !important;
            max-width: 100px !important;
            height: 26px !important;
            font-size: 11px !important;
            padding: 2px 6px !important;
            display: inline-block !important;
            box-sizing: border-box !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            color: #0f2744 !important;
        }

        /* Select2 in Filter Bar */
        .qb-filter-col .select2-container .select2-selection--single {
            height: 26px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            padding: 0 4px !important;
            background: #ffffff;
        }
        .qb-filter-col .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 24px !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            color: #0f2744 !important;
            padding-left: 0 !important;
        }
        .qb-filter-col .select2-container .select2-selection--single .select2-selection__arrow {
            height: 24px !important;
            right: 2px !important;
        }

        /* Filter Buttons */
        .qb-btn-search {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 3px !important;
            height: 26px !important;
            padding: 0 10px !important;
            font-size: 11px !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
        }
        .qb-btn-search:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }
        .qb-btn-reset {
            background: linear-gradient(180deg, #ffffff 0%, #e8eff6 100%) !important;
            border: 1px solid #8ca4c3 !important;
            color: #334155 !important;
            font-weight: 600 !important;
            border-radius: 3px !important;
            height: 26px !important;
            padding: 0 8px !important;
            font-size: 11px !important;
            display: inline-flex;
            align-items: center;
        }
        .qb-btn-reset:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }

        /* 4. Table Card (Exact Replica of QuickBooks Grid Table) */
        .qb-table-card {
            background: #ffffff;
            border: 1px solid #aebfd4;
            border-radius: 4px;
            padding: 4px 6px 6px 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .table-responsive {
            border: 1px solid #aebfd4 !important;
            border-radius: 3px !important;
            background-color: #ffffff;
            overflow-x: auto;
            min-height: 0 !important;
        }
        .qb-table {
            margin-bottom: 0 !important;
            font-size: 13px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Table Headers (Exact QuickBooks Replica: Light Blue-Gray Gradient with Dividers) */
        .qb-table thead th {
            background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
            color: #193452 !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 11px !important;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #aebfd4 !important;
            border-right: 1px solid #c9d7e5 !important;
            border-top: none !important;
            padding: 7px 10px !important;
            white-space: nowrap !important;
        }
        .qb-table thead th:last-child {
            border-right: none !important;
        }

        /* Remove Broken DataTables Sort Glyphs [][ ] */
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:before,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc_disabled:after,
        .dataTables_wrapper .dataTable thead .sorting:before,
        .dataTables_wrapper .dataTable thead .sorting:after,
        .dataTables_wrapper .dataTable thead .sorting_asc:before,
        .dataTables_wrapper .dataTable thead .sorting_asc:after,
        .dataTables_wrapper .dataTable thead .sorting_desc:before,
        .dataTables_wrapper .dataTable thead .sorting_desc:after,
        .qb-table thead th:before,
        .qb-table thead th:after {
            display: none !important;
            content: "" !important;
        }
        table.dataTable thead th,
        table.dataTable thead td {
            background-image: none !important;
        }

        /* Table Body Cells */
        .qb-table tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #edf2f7 !important;
            border-top: none !important;
            padding: 7px 10px !important;
            font-size: 13px !important;
            color: #1a202c !important;
            vertical-align: middle !important;
            font-variant-numeric: tabular-nums;
        }
        .qb-table tbody td:last-child {
            border-right: none !important;
        }

        /* ==========================================================
           ROW CURSOR HOVER: PASTEL MINT GREEN (Exact Sale Screen Replica)
           ========================================================== */
        .qb-table tbody tr {
            cursor: pointer;
            transition: background-color 0.1s ease-in-out;
        }
        .qb-table tbody tr:hover td,
        .qb-table tbody tr:hover th {
            background-color: #d5eed7 !important;
            color: #111827 !important;
        }
        .qb-table tbody tr:hover td small,
        .qb-table tbody tr:hover td .text-muted {
            color: #2e4a32 !important;
        }
        .qb-table tbody tr.qb-active-row td {
            background-color: #d5eed7 !important;
            color: #111827 !important;
        }

        .qb-table td.text-nowrap,
        .qb-table th.text-nowrap {
            white-space: nowrap !important;
        }

        .font-monospace,
        .qb-mono {
            font-family: 'JetBrains Mono', 'Consolas', 'SFMono-Regular', Menlo, monospace !important;
        }

        /* Actions Button (Glossy QuickBooks Tool Button) */
        .odoo-action-btn, .btn-premium-action {
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
            border: 1.5px solid #64748b !important;
            color: #0f172a !important;
            font-weight: 700 !important;
            border-radius: 4px !important;
            height: 26px !important;
            padding: 0 8px !important;
            font-size: 11.5px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            transition: all 0.12s ease;
        }
        .odoo-action-btn:hover,
        .odoo-action-btn[aria-expanded="true"],
        .btn-premium-action:hover,
        .btn-premium-action[aria-expanded="true"] {
            background: #dbeafe !important;
            border-color: #2563eb !important;
            color: #1d4ed8 !important;
        }

        /* Customer Initials Avatar */
        .avatar-circle {
            width: 26px !important;
            height: 26px !important;
            min-width: 26px !important;
            min-height: 26px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            border-radius: 50% !important;
            margin-right: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        /* Dropdown Menu in Grid */
        .dropdown-menu {
            border: 1px solid #aebfd4 !important;
            border-radius: 4px !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
            padding: 3px 0 !important;
            z-index: 1060 !important;
            font-size: 11.5px !important;
        }
        .dropdown-item {
            font-size: 11.5px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            padding: 4px 12px !important;
            transition: all 0.12s ease !important;
        }
        .dropdown-item:hover {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        .dropdown-divider {
            border-top: 1px solid #e2e8f0 !important;
            margin: 3px 0 !important;
        }

        /* DataTables Controls (Clean QuickBooks Style) */
        .dataTables_wrapper .dataTables_length {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 24px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11px !important;
            padding: 0 4px !important;
            margin: 0 4px !important;
            background: #ffffff;
        }
        .dataTables_wrapper .dataTables_filter {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 24px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11.5px !important;
            padding: 0 6px !important;
            margin-left: 4px !important;
            background: #ffffff;
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 11px !important;
            color: #475569 !important;
            padding-top: 4px !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 4px !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            color: #193452 !important;
            border: 1px solid #b2c5dc !important;
            padding: 2px 7px !important;
            font-size: 11px !important;
            border-radius: 3px !important;
            margin: 0 1px !important;
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%);
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border-color: #15457a !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid odoo-page-wrapper">

                {{-- Row 1: Top Window Title Bar (Navy Blue POS Replica) --}}
                <div class="qb-win-header">
                    <div class="qb-win-title">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Sales Management</span>
                        <span class="qb-win-sub d-none d-sm-inline">/ Invoices</span>
                    </div>

                    {{-- Top Action Buttons (Glossy QuickBooks 3D Buttons) --}}
                    <div class="d-flex align-items-center gap-1">
                        <a class="qb-btn-red" href="{{ route('sale.return.index') }}" title="Returns">
                            <i class="fas fa-undo-alt"></i> Returns
                        </a>
                        <a class="qb-btn-blue" href="{{ url('bookings') }}" title="Bookings">
                            <i class="fas fa-bookmark"></i> Bookings
                        </a>
                        @can('sales.create')
                            <a class="qb-btn-green" href="{{ route('sale.add') }}" title="Add Sale">
                                <i class="fas fa-plus"></i> + New Sale (F1)
                            </a>
                        @endcan
                    </div>
                </div>

                {{-- Row 2: Status Ribbon (Left) & Financial KPIs (Right) --}}
                <div class="qb-sub-ribbon">
                    {{-- Status Filter Pills (QuickBooks Order Tab Style) --}}
                    <div class="qb-status-tabs">
                        <a href="{{ route('sale.index', ['status' => 'all']) }}"
                            class="qb-tab-pill {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">
                            All <span class="badge {{ request('status') == 'all' || !request('status') ? 'bg-primary text-white' : 'bg-secondary text-white' }}">{{ $stats['total_count'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('sale.index', ['status' => 'posted']) }}"
                            class="qb-tab-pill {{ request('status') == 'posted' ? 'active-success' : '' }}">
                            <i class="fas fa-check-circle text-success"></i> Posted <span class="badge {{ request('status') == 'posted' ? 'bg-success text-white' : 'bg-success-subtle text-success' }}">{{ $stats['posted_count'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('sale.index', ['status' => 'draft']) }}"
                            class="qb-tab-pill {{ request('status') == 'draft' ? 'active-warning' : '' }}">
                            <i class="fas fa-pencil-alt text-warning"></i> Draft <span class="badge {{ request('status') == 'draft' ? 'bg-warning text-dark' : 'bg-warning-subtle text-dark' }}">{{ $stats['draft_count'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('sale.index', ['status' => 'booked']) }}"
                            class="qb-tab-pill {{ request('status') == 'booked' ? 'active-info' : '' }}">
                            <i class="fas fa-bookmark text-info"></i> Booked <span class="badge {{ request('status') == 'booked' ? 'bg-info text-white' : 'bg-info-subtle text-info' }}">{{ $stats['booked_count'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('sale.index', ['status' => 'returned']) }}"
                            class="qb-tab-pill {{ request('status') == 'returned' ? 'active-danger' : '' }}">
                            <i class="fas fa-undo-alt text-danger"></i> Returned <span class="badge {{ request('status') == 'returned' ? 'bg-danger text-white' : 'bg-danger-subtle text-danger' }}">{{ $stats['returned_count'] ?? 0 }}</span>
                        </a>
                    </div>

                    {{-- Inline Financial KPI Chips --}}
                    <div class="qb-kpi-group">
                        <div class="qb-kpi-chip" title="Total Invoices">
                            <span class="chip-label">Total:</span>
                            <span class="chip-val text-primary" id="statTotalCount">{{ number_format($stats['total_count'] ?? 0) }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Total Net Revenue">
                            <span class="chip-label">Net:</span>
                            <span class="chip-val text-success" id="statTotalNet">Rs. {{ number_format($stats['total_net'] ?? 0, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Discounts Given">
                            <span class="chip-label">Disc:</span>
                            <span class="chip-val text-warning" id="statTotalDiscount">Rs. {{ number_format($stats['total_discount'] ?? 0, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Posted / Booked">
                            <span class="chip-label">Posted/Booked:</span>
                            <span class="chip-val text-info" id="statStatusCounts">
                                {{ $stats['posted_count'] ?? 0 }} <span class="text-muted fw-normal">/ {{ $stats['booked_count'] ?? 0 }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Filter Bar (Single Line, Never Wraps) --}}
                <div class="qb-filter-bar" id="filterPanelContainer">
                    <form id="filterForm" class="qb-filter-form" autocomplete="off" onsubmit="return false;">
                        <div class="qb-filter-col" style="width: 100px; flex: 0 0 100px;">
                            <select id="quick_filter" class="form-select qb-input" style="width: 100%;">
                                <option value="custom">Custom</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="qb-filter-col date-col">
                            <input type="text" class="form-control qb-input datepicker-custom" name="from_date" id="filter_from_date" placeholder="From Date">
                        </div>
                        <div class="qb-filter-col date-col">
                            <input type="text" class="form-control qb-input datepicker-custom" name="to_date" id="filter_to_date" placeholder="To Date">
                        </div>
                        <div class="qb-filter-col" style="width: 95px; flex: 0 0 95px;">
                            <input type="text" class="form-control qb-input" name="bill_no" id="filter_bill_no" value="{{ request('bill_no') ?? request('invoice_no') }}" placeholder="Inv/Bill#">
                        </div>
                        <div class="qb-filter-col" style="width: 85px; flex: 0 0 85px;">
                            <input type="text" class="form-control qb-input" name="reference" id="filter_reference" placeholder="Ref#">
                        </div>
                        <div class="qb-filter-col" style="flex: 1 1 180px; min-width: 150px; max-width: 280px;">
                            <select class="form-select select2-customer qb-input" name="customer_id" id="filter_customer_id" style="width: 100%;">
                                <option value="">All Customers</option>
                                @if(isset($walkinCustomers) && count($walkinCustomers) > 0)
                                    <optgroup label="Walk-in Customers">
                                        @foreach ($walkinCustomers as $wName)
                                            <option value="walkin:{{ $wName }}" {{ request('customer_id') == 'walkin:'.$wName ? 'selected' : '' }}>
                                                {{ $wName }} (Walk-in)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                <optgroup label="Registered Customers">
                                    @foreach ($customers as $c)
                                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->customer_name }} {{ $c->mobile ? '('.$c->mobile.')' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="qb-filter-col d-flex align-items-center gap-1" style="flex: 0 0 auto;">
                            <button type="button" class="btn qb-btn-search" id="btnSearch" title="Search">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                            <button type="button" class="btn qb-btn-reset" id="btnReset" title="Reset Filters">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Session Flash Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 rounded py-1 px-2 mb-1" style="font-size: 11px;">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close ms-auto p-1" data-bs-dismiss="alert" aria-label="Close" style="font-size: 8px;"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 rounded py-1 px-2 mb-1" style="font-size: 11px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close ms-auto p-1" data-bs-dismiss="alert" aria-label="Close" style="font-size: 8px;"></button>
                    </div>
                @endif

                {{-- Row 4: Table Card (Exact Replica of POS Grid Table) --}}
                <div class="qb-table-card">
                    <div class="table-responsive">
                        <table id="sales-table" class="table table-hover align-middle datanew qb-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="ps-2">Invoice / Bill#</th>
                                    <th>Customer</th>
                                    <th>M.Bill</th>
                                    <th>Products</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Gross</th>
                                    <th class="text-end">Inline Disc</th>
                                    <th class="text-end">Add. Disc</th>
                                    <th class="text-end">Net Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-center pe-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                @include('admin_panel.sale.partials.sales_table_body')
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize Select2 with search for customer dropdown
            if ($('.select2-customer').length > 0) {
                $('.select2-customer').select2({
                    placeholder: "Customer (Name or select)...",
                    allowClear: true,
                    tags: true,
                    width: '100%'
                });
            }

            // Function to initialize DataTable safely
            function initDataTable() {
                try {
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#sales-table')) {
                        $('#sales-table').DataTable().destroy();
                    }
                    if ($.fn.DataTable) {
                        $('#sales-table').DataTable({
                            "pageLength": 10,
                            "order": [],
                            "columnDefs": [
                                { "orderable": false, "targets": [11] } // Action column not sortable
                            ],
                            "language": {
                                "search": "",
                                "searchPlaceholder": "Search sales...",
                                "lengthMenu": "Show _MENU_"
                            },
                            "dom": "<'d-flex justify-content-between align-items-center mb-1'<'d-flex align-items-center gap-1 font-monospace'l><'d-flex align-items-center'f>>" +
                                "<'row'<'col-12'tr>>" +
                                "<'d-flex justify-content-between align-items-center mt-1'<'text-muted small'i><'d-flex justify-content-end'p>>",
                        });
                    }
                } catch(e) {
                    console.error("DataTable initialization error: ", e);
                }
            }

            // Initial call
            initDataTable();

            // Active row highlight on click (like POS / Sale screen)
            $(document).on('click', '#sales-table tbody tr', function(e) {
                if ($(e.target).closest('.dropdown, a, button').length > 0) return;
                $('#sales-table tbody tr').removeClass('qb-active-row');
                $(this).addClass('qb-active-row');
            });

            // Mobile Filter Panel Toggle
            $('#toggleFilterPanel').on('click', function() {
                $('#filterPanelContainer').slideToggle(200);
            });

            // Core AJAX Filter Function
            function applySalesFilter() {
                const $btn = $('#btnSearch');
                const origHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                let formData = $('#filterForm').serialize();
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('status')) {
                    formData += '&status=' + encodeURIComponent(urlParams.get('status'));
                }

                $.ajax({
                    url: '{{ route("sale.index") }}',
                    method: 'GET',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html(origHtml);
                        
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#sales-table')) {
                            $('#sales-table').DataTable().destroy();
                        }
                        
                        $('#salesTableBody').html(response.html);
                        
                        // Update Stat Cards dynamically if present
                        if (response.stats) {
                            $('#statTotalCount').text(Number(response.stats.total_count || 0).toLocaleString());
                            $('#statTotalNet').text('Rs. ' + Number(response.stats.total_net || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('#statTotalDiscount').text('Rs. ' + Number(response.stats.total_discount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('#statStatusCounts').html((response.stats.posted_count || 0) + ' <span class="text-muted fw-normal">/ ' + (response.stats.booked_count || 0) + '</span>');
                        }

                        initDataTable();
                    },
                    error: function(err) {
                        $btn.prop('disabled', false).html(origHtml);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', 'Failed to retrieve filtered list.', 'error');
                        } else {
                            alert('Failed to retrieve filtered list.');
                        }
                    }
                });
            }

            // Quick Filter Logic
            $(document).on('change', '#quick_filter', function() {
                let val = $(this).val();
                if (val === 'custom') return;

                let today = new Date();
                let start = new Date();
                let end = new Date();

                if (val === 'daily') {
                    start = new Date();
                    end = new Date();
                } else if (val === 'weekly') {
                    let day = today.getDay();
                    let diff = today.getDate() - day + (day === 0 ? -6 : 1);
                    start = new Date(today.setDate(diff));
                    end = new Date();
                } else if (val === 'monthly') {
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = new Date();
                } else if (val === 'yearly') {
                    start = new Date(today.getFullYear(), 0, 1);
                    end = new Date();
                }

                let formatDate = function(d) {
                    let year = d.getFullYear();
                    let month = String(d.getMonth() + 1).padStart(2, '0');
                    let day = String(d.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                let startStr = formatDate(start);
                let endStr = formatDate(end);

                let pickerFrom = document.getElementById('filter_from_date') ? document.getElementById('filter_from_date')._flatpickr : null;
                let pickerTo = document.getElementById('filter_to_date') ? document.getElementById('filter_to_date')._flatpickr : null;

                if (pickerFrom) pickerFrom.setDate(startStr, true);
                else $("#filter_from_date").val(startStr);

                if (pickerTo) pickerTo.setDate(endStr, true);
                else $("#filter_to_date").val(endStr);

                applySalesFilter();
            });

            // Trigger search on button click & enter key
            $('#btnSearch').on('click', function(e) {
                e.preventDefault();
                applySalesFilter();
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                applySalesFilter();
                return false;
            });

            $(document).on('keypress', '#filterForm input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    applySalesFilter();
                    return false;
                }
            });

            // Reset form completely and fetch unfiltered list via AJAX
            $('#btnReset').on('click', function(e) {
                e.preventDefault();

                // 1. Explicitly clear all filter inputs
                $('#filter_from_date').val('');
                $('#filter_to_date').val('');
                $('#filter_bill_no').val('');
                $('#filter_reference').val('');
                $('#quick_filter').val('custom');
                
                // 2. Clear Select2 Customer Dropdown properly
                if ($('.select2-customer').length > 0) {
                    $('.select2-customer').val('').trigger('change');
                }
                
                // 3. Clear Flatpickr instances
                let fromElem = document.getElementById('filter_from_date');
                let toElem = document.getElementById('filter_to_date');
                if (fromElem && fromElem._flatpickr) {
                    fromElem._flatpickr.clear();
                }
                if (toElem && toElem._flatpickr) {
                    toElem._flatpickr.clear();
                }

                // Clear any Flatpickr visible alt-inputs inside filter container
                $('#filterPanelContainer .datepicker-custom').val('');
                $('#filterPanelContainer input.input').val('');

                // 4. Clear DataTables client search if any
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#sales-table')) {
                    $('#sales-table').DataTable().search('');
                }

                // 5. Clean browser URL query parameters (revert back to clean /sale or preserve status tab)
                let urlParams = new URLSearchParams(window.location.search);
                let newUrl = window.location.pathname;
                if (urlParams.has('status')) {
                    newUrl += '?status=' + encodeURIComponent(urlParams.get('status'));
                }
                if (window.history.replaceState) {
                    window.history.replaceState({}, '', newUrl);
                }

                // 6. Trigger AJAX to fetch complete unfiltered data
                applySalesFilter();
            });

            // Confirm Booking Action
            $(document).on('click', '.confirm-booking-btn', function(e) {
                e.preventDefault();
                let form = $(this).closest("form");

                Swal.fire({
                    title: "Confirm Booking?",
                    text: "Are you sure you want to convert this booking to a posted sale? This will update stocks and post ledgers.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Confirm it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Keyboard Shortcut F1 -> New Sale
            $(document).on('keydown', function(e) {
                if (e.key === 'F1') {
                    e.preventDefault();
                    window.location.href = "{{ route('sale.add') }}";
                }
            });
        });
    </script>
@endsection
