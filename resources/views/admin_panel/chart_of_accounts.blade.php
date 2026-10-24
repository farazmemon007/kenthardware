@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* ==========================================================
           QUICKBOOKS / ODOO POS "CHART OF ACCOUNTS" THEME
           High-Density Zero-Scroll Layout Matching Sales Screen
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
        .odoo-page-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            padding: 0 4px !important;
        }

        /* 1. Top Window Title Bar (Navy Blue POS Replica) */
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
            color: #60a5fa;
            font-size: 14px;
        }
        .qb-win-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* QuickBooks Glossy Action Buttons */
        .qb-btn-blue {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
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
        .qb-btn-blue:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

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

        /* 2. Sub-Header Ribbon: Financial KPIs & Totals */
        .qb-sub-ribbon {
            background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
            border: 1px solid #b2c5dc;
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 5px !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .qb-kpi-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .qb-kpi-chip {
            background: #ffffff;
            border: 1px solid #8ca4c3;
            border-radius: 4px;
            padding: 2px 8px;
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

        /* 3. Table Card */
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
            font-size: 11.5px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Table Headers (Soft Blue-Gray Gradient with Dividers) */
        .qb-table thead th {
            background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
            color: #193452 !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 10.5px !important;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #aebfd4 !important;
            border-right: 1px solid #c9d7e5 !important;
            border-top: none !important;
            padding: 6px 8px !important;
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
            padding: 5px 8px !important;
            font-size: 11.5px !important;
            color: #1a202c !important;
            vertical-align: middle;
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
        .qb-table th.text-nowrap,
        .qb-table .font-monospace {
            white-space: nowrap !important;
        }

        /* Action View Buttons (Compact Toolbar Buttons) */
        .odoo-action-btn {
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
            border: 1px solid #8ca4c3 !important;
            color: #18385e !important;
            font-weight: 700 !important;
            border-radius: 3px !important;
            height: 22px !important;
            padding: 0 7px !important;
            font-size: 10.5px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
            transition: all 0.12s ease;
            text-decoration: none !important;
            white-space: nowrap !important;
        }
        .odoo-action-btn:hover {
            background: #dbeafe !important;
            border-color: #2563eb !important;
            color: #1d4ed8 !important;
        }
        .odoo-btn-edit:hover {
            background: #e0e7ff !important;
            border-color: #4f46e5 !important;
            color: #4338ca !important;
        }
        .odoo-btn-hist:hover {
            background: #f1f5f9 !important;
            border-color: #64748b !important;
            color: #334155 !important;
        }

        /* DataTables Controls (Clean QuickBooks Style) */
        .dataTables_wrapper .dataTables_length {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
            margin-bottom: 4px !important;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 22px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 10.5px !important;
            padding: 0 4px !important;
            margin: 0 4px !important;
        }
        .dataTables_wrapper .dataTables_filter {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
            margin-bottom: 4px !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 22px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 10.5px !important;
            padding: 0 6px !important;
            margin-left: 4px !important;
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
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border-color: #15457a !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        /* ==============================================================
           QUICKBOOKS & ODOO POS MODAL DESIGN SYSTEM (Anti-Rogue CSS)
           ============================================================== */
        .qb-modal-content {
            border-radius: 8px !important;
            overflow: hidden !important;
            border: 1px solid #0f2744 !important;
            box-shadow: 0 20px 45px -10px rgba(15, 39, 68, 0.45) !important;
            background: #ffffff !important;
        }

        .qb-modal-header {
            background: linear-gradient(180deg, #18385e 0%, #0f2744 100%) !important;
            border-bottom: 2px solid #0077c5 !important;
            padding: 9px 14px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            color: #ffffff !important;
        }
        .qb-modal-icon-badge {
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 13px;
            flex-shrink: 0;
        }
        .qb-modal-title {
            font-size: 13.5px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            letter-spacing: 0.3px !important;
            line-height: 1.2 !important;
        }
        .qb-modal-subtitle {
            font-size: 10.5px !important;
            font-weight: 600 !important;
            color: #93c5fd !important;
            line-height: 1.2 !important;
        }
        .qb-modal-close {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 16px !important;
            line-height: 1 !important;
            opacity: 0.8 !important;
            cursor: pointer !important;
            padding: 4px 6px !important;
            border-radius: 4px !important;
            transition: all 0.15s ease !important;
        }
        .qb-modal-close:hover {
            opacity: 1 !important;
            background: rgba(255, 255, 255, 0.18) !important;
            color: #ffffff !important;
        }

        .qb-modal-info-bar {
            background: #edf3fa !important;
            border: 1px solid #b2c5dc !important;
            border-radius: 4px !important;
            padding: 5px 9px !important;
            font-size: 11px !important;
            color: #1e3a5f !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            margin-bottom: 9px !important;
        }

        /* Modal Form Fields & Labels: STRICT OVERRIDE FOR #3C3ACC */
        .qb-modal-content label,
        .qb-modal-content .text-secondary,
        .qb-field-label {
            color: #1e293b !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 0.2px !important;
            margin-bottom: 3px !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
        }
        .qb-field-label i {
            color: #195293 !important;
            font-size: 11px !important;
        }

        /* Addon Icons */
        .qb-input-addon {
            background: #edf3fa !important;
            border: 1px solid #8ca4c3 !important;
            border-right: none !important;
            color: #1e3a5f !important;
            font-size: 11px !important;
            padding: 0 9px !important;
            border-radius: 4px 0 0 4px !important;
            height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Modal Inputs & Selects */
        .qb-modal-input,
        .qb-modal-select {
            height: 30px !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            background-color: #ffffff !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 0 4px 4px 0 !important;
            padding: 3px 8px !important;
            transition: all 0.15s ease !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        }
        .qb-modal-input:focus,
        .qb-modal-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
            outline: none !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
        }

        /* Switch Card */
        .qb-switch-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .qb-custom-switch {
            width: 32px !important;
            height: 18px !important;
            cursor: pointer !important;
        }
        .qb-custom-switch:checked {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
        }

        /* Modal Footer Buttons */
        .qb-modal-footer {
            background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
            border-top: 1px solid #b2c5dc !important;
            padding: 7px 12px !important;
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            gap: 8px !important;
        }
        .qb-modal-btn-cancel {
            background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%) !important;
            border: 1px solid #8ca4c3 !important;
            color: #334155 !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            height: 27px !important;
            padding: 0 14px !important;
            border-radius: 4px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            text-decoration: none !important;
            transition: all 0.12s ease !important;
        }
        .qb-modal-btn-cancel:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
            border-color: #64748b !important;
        }

        .qb-modal-btn-save {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            font-size: 11.5px !important;
            height: 27px !important;
            padding: 0 16px !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            text-decoration: none !important;
            transition: filter 0.1s ease !important;
        }
        .qb-modal-btn-save:hover {
            filter: brightness(1.1) !important;
            color: #ffffff !important;
        }

        .qb-modal-btn-green {
            background: linear-gradient(180deg, #53af41 0%, #35832a 100%) !important;
            border: 1px solid #286c1f !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            font-size: 11.5px !important;
            height: 27px !important;
            padding: 0 16px !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            text-decoration: none !important;
            transition: filter 0.1s ease !important;
        }
        .qb-modal-btn-green:hover {
            filter: brightness(1.1) !important;
            color: #ffffff !important;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid odoo-page-wrapper">

                {{-- Row 1: Top Window Title Bar (Navy Blue POS Replica) --}}
                <div class="qb-win-header">
                    <div class="qb-win-title">
                        <i class="fas fa-university"></i>
                        <span>Chart of Accounts</span>
                        <span class="qb-win-sub d-none d-sm-inline">/ Cash, Bank &amp; Financial Ledgers</span>
                    </div>

                    {{-- Top Action Buttons --}}
                    @can('chart.of.accounts.create')
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="qb-btn-blue" data-toggle="modal" data-target="#addAccountModal">
                                <i class="fas fa-plus"></i> + Add Account
                            </button>
                            <button type="button" class="qb-btn-green" data-toggle="modal" data-target="#addHeadModal">
                                <i class="fas fa-folder-plus"></i> + Add Category
                            </button>
                        </div>
                    @endcan
                </div>

                {{-- Row 2: Financial KPI Summary Ribbon --}}
                @php
                    $totalDebit = $accounts->where('current_balance', '>', 0)->sum('current_balance');
                    $totalCredit = abs($accounts->where('current_balance', '<', 0)->sum('current_balance'));
                @endphp
                <div class="qb-sub-ribbon">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold text-dark font-monospace" style="font-size: 11px;">
                            <i class="fas fa-list text-muted me-1"></i> Accounts: <strong>{{ $accounts->count() }}</strong>
                        </span>
                        <span class="text-muted">|</span>
                        <span class="fw-bold text-success font-monospace" style="font-size: 11px;">
                            <i class="fas fa-check-circle me-1"></i> Active: <strong>{{ $accounts->where('status', 1)->count() }}</strong>
                        </span>
                        <span class="text-muted">|</span>
                        <span class="fw-bold text-secondary font-monospace" style="font-size: 11px;">
                            <i class="fas fa-folder me-1"></i> Categories: <strong>{{ $heads->count() }}</strong>
                        </span>
                    </div>
                    <div class="qb-kpi-group">
                        <div class="qb-kpi-chip">
                            <span class="chip-label text-success">Total Debit (Dr):</span>
                            <span class="chip-val text-success">Rs {{ number_format($totalDebit, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip">
                            <span class="chip-label text-danger">Total Credit (Cr):</span>
                            <span class="chip-val text-danger">Rs {{ number_format($totalCredit, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-1 px-3 mb-1" style="font-size: 11.5px; border-radius: 4px;">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger py-1 px-3 mb-1" style="font-size: 11.5px; border-radius: 4px;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ══════════════════════════════════════════════════════════════
                     DESKTOP TABLE VIEW (Compact Odoo/QuickBooks POS High Density)
                ══════════════════════════════════════════════════════════════ --}}
                <div class="qb-table-card d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table qb-table datanew" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 9%;">Code</th>
                                    <th style="width: 15%;">Head / Group</th>
                                    <th style="width: 22%;">Account Title</th>
                                    <th style="width: 7%; text-align: center;">Type</th>
                                    <th style="width: 11%; text-align: right;">Opening</th>
                                    <th style="width: 13%; text-align: right;">Current Balance</th>
                                    <th style="width: 6%; text-align: center;">Status</th>
                                    <th style="width: 14%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $acc)
                                    <tr>
                                        <td class="text-center font-monospace text-muted fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace px-2 py-0" style="font-size: 10.5px; font-weight: 700;">{{ $acc->account_code ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $acc->head->name ?? '-' }}</span>
                                            @if ($acc->head && $acc->head->parent_id)
                                                <small class="text-muted d-block" style="font-size: 10px;">({{ $acc->head->parent->name ?? '' }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark" style="font-size: 12px;">{{ $acc->title }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($acc->type == 'Debit')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2" style="font-size: 10px; font-weight: 700;">Debit</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2" style="font-size: 10px; font-weight: 700;">Credit</span>
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace text-secondary" style="font-size: 11.5px;">
                                            {{ number_format($acc->opening_balance, 2) }}
                                        </td>
                                        <td class="text-end">
                                            <span class="font-monospace fw-bold {{ $acc->current_balance < 0 ? 'text-danger' : 'text-success' }}" style="font-size: 12px;">
                                                {{ number_format(abs($acc->current_balance), 2) }}
                                                <small class="fw-bold ms-1" style="font-size: 10px;">{{ $acc->current_balance >= 0 ? 'Dr' : 'Cr' }}</small>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($acc->status)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2" style="font-size: 10px;">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2" style="font-size: 10px;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center" style="gap: 3px;">
                                                {{-- Ledger Button --}}
                                                <a href="{{ route('accounts.ledger', $acc->id) }}" class="odoo-action-btn" title="View Ledger">
                                                    <i class="fas fa-book" style="margin-right: 3px;"></i> Ledger
                                                </a>

                                                {{-- Edit Account Button --}}
                                                <button type="button" class="odoo-action-btn odoo-btn-edit" data-toggle="modal" data-target="#editAccountModal{{ $acc->id }}" title="Edit Account / Ledger Balance">
                                                    <i class="fas fa-edit" style="margin-right: 3px;"></i> Edit
                                                </button>

                                                {{-- Audit History Button --}}
                                                <button type="button" class="odoo-action-btn odoo-btn-hist" data-toggle="modal" data-target="#historyAccountModal{{ $acc->id }}" title="View Edit History">
                                                    <i class="fas fa-history" style="margin-right: 3px;"></i> {{ $acc->histories->count() }}
                                                </button>

                                                {{-- Toggle Status Form --}}
                                                <form action="{{ route('accounts.toggleStatus', $acc->id) }}" method="POST" style="display:inline-block; margin: 0;">
                                                    @csrf
                                                    <button type="button" onclick="this.closest('form').submit()" class="odoo-action-btn {{ $acc->status ? 'text-danger' : 'text-success' }}" style="width: 22px; padding: 0;" title="{{ $acc->status ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $acc->status ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════════
                     MOBILE CARDS VIEW (Clean 2x2 grid with balanced spacing)
                ══════════════════════════════════════════════════════════════ --}}
                <div class="d-block d-md-none">
                    @foreach ($accounts as $acc)
                        <div class="card border shadow-sm rounded-3 mb-2" style="background: #ffffff; border-color: #cbd5e1 !important;">
                            <div class="card-body p-2">
                                
                                {{-- Code & Status Header --}}
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark font-monospace" style="font-size: 11.5px;">{{ $acc->account_code ?? 'N/A' }}</span>
                                    @if ($acc->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0" style="font-size: .70rem;">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0" style="font-size: .70rem;">Inactive</span>
                                    @endif
                                </div>

                                {{-- Head Category & Account Title --}}
                                <div class="text-muted small mb-0" style="font-size: .78rem;">{{ $acc->head->name ?? '-' }}</div>
                                <div class="fw-bold text-dark mb-2" style="font-size: 13px;">{{ $acc->title }}</div>

                                {{-- Type & Opening Grid --}}
                                <div class="row g-1 mb-2 text-start" style="font-size: .80rem;">
                                    <div class="col-6">
                                        <div class="text-muted small">Type</div>
                                        <div class="fw-bold text-dark">{{ $acc->type }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">Opening</div>
                                        <div class="fw-bold text-dark font-monospace">{{ number_format($acc->opening_balance, 2) }}</div>
                                    </div>
                                </div>

                                {{-- Current Balance --}}
                                <div class="d-flex justify-content-between align-items-center mb-2 pt-1 border-top">
                                    <span class="text-muted small">Current Balance</span>
                                    <span class="fw-bold font-monospace {{ $acc->current_balance < 0 ? 'text-danger' : 'text-success' }}" style="font-size: 13px;">
                                        {{ number_format(abs($acc->current_balance), 2) }}
                                        <small class="fw-normal text-secondary ms-1">{{ $acc->current_balance >= 0 ? 'Dr' : 'Cr' }}</small>
                                    </span>
                                </div>

                                {{-- Action Button Grid --}}
                                <div class="row g-1">
                                    <div class="col-6">
                                        <a href="{{ route('accounts.ledger', $acc->id) }}" class="btn btn-outline-primary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" style="height: 28px; font-size: .75rem; border-radius: 4px;">
                                            <i class="fas fa-book me-1"></i> Ledger
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#editAccountModal{{ $acc->id }}" style="height: 28px; font-size: .75rem; border-radius: 4px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#historyAccountModal{{ $acc->id }}" style="height: 28px; font-size: .75rem; border-radius: 4px;">
                                            <i class="fas fa-history me-1"></i> History ({{ $acc->histories->count() }})
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <form action="{{ route('accounts.toggleStatus', $acc->id) }}" method="POST">
                                            @csrf
                                            <button type="button" onclick="this.closest('form').submit()" class="btn {{ $acc->status ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" style="height: 28px; font-size: .75rem; border-radius: 4px;">
                                                <i class="fas {{ $acc->status ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ══════════════════════════════════════════════════════════════
                     ALL MODALS (QUICKBOOKS & ODOO EXECUTIVE DESIGN SYSTEM)
                ══════════════════════════════════════════════════════════════ --}}
                @foreach ($accounts as $acc)

                    {{-- EDIT ACCOUNT & LEDGER BALANCE MODAL --}}
                    <div class="modal fade" id="editAccountModal{{ $acc->id }}" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel{{ $acc->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
                            <form class="modal-content qb-modal-content border-0 shadow-lg" action="{{ route('accounts.update', $acc->id) }}" method="POST">
                                @csrf
                                <div class="qb-modal-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="qb-modal-icon-badge">
                                            <i class="fas fa-edit text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="qb-modal-title">Edit Account &amp; Ledger - {{ $acc->title }}</div>
                                            <div class="qb-modal-subtitle">Code: {{ $acc->account_code ?? 'N/A' }} | Direct Balance Edit &amp; Audit Trail</div>
                                        </div>
                                    </div>
                                    <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="modal-body p-3 text-start bg-white">
                                    <div class="qb-modal-info-bar">
                                        <i class="fas fa-info-circle me-1 text-primary"></i> Aap <strong>Current Ledger Balance</strong> ko direct edit karke naya amount daal sakte hain. Audit history me entry record ho jayegi.
                                    </div>

                                    <div class="mb-2">
                                        <label class="qb-field-label">
                                            <i class="fas fa-sitemap"></i> Select Head (Category) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text qb-input-addon"><i class="fas fa-folder"></i></span>
                                            <select class="form-select qb-modal-select" name="head_id" required>
                                                @foreach ($heads as $head)
                                                    <option value="{{ $head->id }}" {{ $acc->head_id == $head->id ? 'selected' : '' }}>{{ $head->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="qb-field-label">
                                            <i class="fas fa-university"></i> Account Title <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text qb-input-addon"><i class="fas fa-pencil-alt"></i></span>
                                            <input type="text" name="title" class="form-control qb-modal-input" value="{{ $acc->title }}" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4 col-12">
                                            <label class="qb-field-label">
                                                <i class="fas fa-exchange-alt"></i> Type
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text qb-input-addon"><i class="fas fa-tags"></i></span>
                                                <select class="form-select qb-modal-select" name="type">
                                                    <option value="Debit" {{ $acc->type == 'Debit' ? 'selected' : '' }}>Debit (Dr)</option>
                                                    <option value="Credit" {{ $acc->type == 'Credit' ? 'selected' : '' }}>Credit (Cr)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <label class="qb-field-label">
                                                <i class="fas fa-clock"></i> Opening Bal
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text qb-input-addon font-monospace fw-bold">Rs</span>
                                                <input type="number" step="0.01" name="opening_balance" class="form-control qb-modal-input font-monospace text-end" value="{{ $acc->opening_balance }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <label class="qb-field-label text-success">
                                                <i class="fas fa-wallet text-success"></i> Current Bal <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text qb-input-addon font-monospace fw-bold text-success">Rs</span>
                                                <input type="number" step="0.01" name="current_balance" class="form-control qb-modal-input font-monospace fw-bold text-success text-end border-success" value="{{ $acc->current_balance }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="qb-field-label">
                                            <i class="fas fa-comment-alt"></i> Reason / Note for Ledger Edit (Optional)
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text qb-input-addon"><i class="fas fa-sticky-note"></i></span>
                                            <input type="text" name="note" class="form-control qb-modal-input" placeholder="e.g. Manual payment addition / Balance correction">
                                        </div>
                                    </div>

                                    <div class="qb-switch-box mb-1">
                                        <div>
                                            <label class="qb-field-label mb-0" for="statusCheck{{ $acc->id }}" style="cursor: pointer;">
                                                <i class="fas fa-toggle-on text-success"></i> Active Account Status
                                            </label>
                                            <span class="text-muted d-block" style="font-size: 10px;">Enable or disable account from POS &amp; ledgers</span>
                                        </div>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input qb-custom-switch" type="checkbox" id="statusCheck{{ $acc->id }}" name="status" value="1" {{ $acc->status ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <div class="qb-modal-footer">
                                    <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="qb-modal-btn-save">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ACCOUNT AUDIT HISTORY MODAL --}}
                    <div class="modal fade" id="historyAccountModal{{ $acc->id }}" tabindex="-1" role="dialog" aria-labelledby="historyAccountModalLabel{{ $acc->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content qb-modal-content border-0 shadow-lg">
                                <div class="qb-modal-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="qb-modal-icon-badge">
                                            <i class="fas fa-history text-info"></i>
                                        </div>
                                        <div>
                                            <div class="qb-modal-title">Audit History - {{ $acc->title }}</div>
                                            <div class="qb-modal-subtitle">Record of all balance edits and modifications</div>
                                        </div>
                                    </div>
                                    <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="modal-body p-0" style="max-height: 60vh; overflow-y: auto;">
                                    @if($acc->histories->count() > 0)
                                        <div class="table-responsive m-0 border-0">
                                            <table class="table qb-table align-middle mb-0 text-nowrap" style="font-size: 11px;">
                                                <thead class="sticky-top">
                                                    <tr>
                                                        <th class="ps-3 py-2 text-start">Date &amp; Time</th>
                                                        <th class="py-2 text-start">User</th>
                                                        <th class="text-end py-2">Old Balance</th>
                                                        <th class="text-end py-2">New Balance</th>
                                                        <th class="ps-3 py-2 text-start">Remarks / Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($acc->histories as $h)
                                                        <tr>
                                                            <td class="ps-3 font-monospace text-muted">{{ $h->created_at->format('d-M-Y h:i:s A') }}</td>
                                                            <td>
                                                                <span class="badge bg-light text-dark border font-monospace">{{ $h->user_name ?? ($h->user->name ?? 'User') }}</span>
                                                            </td>
                                                            <td class="text-end text-muted font-monospace">Rs {{ number_format($h->old_balance, 2) }}</td>
                                                            <td class="text-end fw-bold text-primary font-monospace">Rs {{ number_format($h->new_balance, 2) }}</td>
                                                            <td class="ps-3 text-muted">{{ $h->note ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-3x mb-3" style="color:#cbd5e1;"></i>
                                            <p class="fw-bold mb-0">No edit history recorded yet</p>
                                            <small>History entries will automatically appear here whenever opening balance or account details are updated.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="qb-modal-footer">
                                    <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                <!-- Add New Account Modal -->
                <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 490px;">
                        <form class="modal-content qb-modal-content border-0 shadow-lg" action="{{ route('accounts.store') }}" method="POST">
                            @csrf
                            <div class="qb-modal-header">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="qb-modal-icon-badge">
                                        <i class="fas fa-plus-circle text-info"></i>
                                    </div>
                                    <div>
                                        <div class="qb-modal-title">Add New Financial Account</div>
                                        <div class="qb-modal-subtitle">Bank, Cash, or Khata Ledger Account</div>
                                    </div>
                                </div>
                                <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="modal-body p-3 bg-white">
                                <div class="qb-modal-info-bar">
                                    <i class="fas fa-info-circle me-1 text-primary"></i> Create a new account for general ledger &amp; POS billing allocation.
                                </div>

                                <div class="mb-2">
                                    <label class="qb-field-label">
                                        <i class="fas fa-sitemap"></i> Select Head (Category) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text qb-input-addon"><i class="fas fa-folder"></i></span>
                                        <select class="form-select qb-modal-select" name="head_id" required>
                                            <option value="">-- Select Head / Category --</option>
                                            @foreach ($heads as $head)
                                                <option value="{{ $head->id }}">{{ $head->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="qb-field-label">
                                        <i class="fas fa-university"></i> Account Title <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text qb-input-addon"><i class="fas fa-pencil-alt"></i></span>
                                        <input type="text" name="title" class="form-control qb-modal-input" placeholder="e.g., UBL Current, Shop Cash, Meezan Bank" required>
                                    </div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="qb-field-label">
                                            <i class="fas fa-exchange-alt"></i> Account Type
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text qb-input-addon"><i class="fas fa-tags"></i></span>
                                            <select class="form-select qb-modal-select" name="type">
                                                <option value="Debit">Debit (Dr)</option>
                                                <option value="Credit">Credit (Cr)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="qb-field-label">
                                            <i class="fas fa-coins"></i> Opening Balance
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text qb-input-addon font-monospace fw-bold">Rs</span>
                                            <input type="number" step="0.01" name="opening_balance" class="form-control qb-modal-input font-monospace text-end" value="0.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="qb-switch-box mb-1">
                                    <div>
                                        <label class="qb-field-label mb-0" for="statusCheck" style="cursor: pointer;">
                                            <i class="fas fa-toggle-on text-success"></i> Active Account Status
                                        </label>
                                        <span class="text-muted d-block" style="font-size: 10px;">Enable this account for POS billing and ledgers</span>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input qb-custom-switch" type="checkbox" id="statusCheck" name="status" value="1" checked>
                                    </div>
                                </div>
                            </div>

                            <div class="qb-modal-footer">
                                <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="qb-modal-btn-save">
                                    <i class="fas fa-check-circle"></i> Save Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Head Modal -->
                <div class="modal fade" id="addHeadModal" tabindex="-1" role="dialog" aria-labelledby="addHeadLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
                        <form class="modal-content qb-modal-content border-0 shadow-lg" action="{{ route('account-heads.store') }}" method="POST">
                            @csrf
                            <div class="qb-modal-header">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="qb-modal-icon-badge">
                                        <i class="fas fa-folder-plus text-success"></i>
                                    </div>
                                    <div>
                                        <div class="qb-modal-title">Add Account Category / Head</div>
                                        <div class="qb-modal-subtitle">Group financial ledgers (e.g. Bank, Cash, Assets)</div>
                                    </div>
                                </div>
                                <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="modal-body p-3 bg-white">
                                <div class="qb-modal-info-bar">
                                    <i class="fas fa-info-circle me-1 text-success"></i> Create a new account category / head to group accounts.
                                </div>

                                <div class="mb-2">
                                    <label class="qb-field-label">
                                        <i class="fas fa-folder-open"></i> Category / Head Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text qb-input-addon"><i class="fas fa-tag"></i></span>
                                        <input type="text" name="name" class="form-control qb-modal-input" placeholder="e.g., Current Assets, Bank Accounts" required>
                                    </div>
                                </div>
                            </div>

                            <div class="qb-modal-footer">
                                <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="qb-modal-btn-green">
                                    <i class="fas fa-check-circle"></i> Save Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }
            $('.datanew').DataTable({
                "pageLength": 10,
                "aaSorting": [],
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search accounts..."
                }
            });
        });
    </script>
@endsection
