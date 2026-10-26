@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* ==========================================================
           QUICKBOOKS / ODOO ERP "GENERAL LEDGER (STATEMENT)" THEME
           Pixel-Perfect High-Density Layout Matching Sales & Accounts
           ========================================================== */

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

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

        html, body,
        .odoo-page-wrapper,
        .qb-table,
        .qb-table thead th,
        .qb-table tbody td,
        .qb-win-header,
        .qb-filter-bar,
        .qb-table-card,
        input, button, select, textarea {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .odoo-page-wrapper {
            padding: 0 4px !important;
        }

        /* Bootstrap 4 Helper Polyfills */
        .fw-bold, .font-weight-bold { font-weight: 700 !important; }
        .fw-bolder { font-weight: 800 !important; }
        .fw-semibold { font-weight: 600 !important; }
        .font-monospace, .qb-mono { font-family: 'JetBrains Mono', Consolas, monospace !important; }
        .text-end, .text-right { text-align: right !important; }
        .text-start, .text-left { text-align: left !important; }
        .text-center { text-align: center !important; }

        /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS Replica) */
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

        /* QuickBooks Glossy Action Buttons */
        .qb-btn-blue {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 11px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
            white-space: nowrap;
        }
        .qb-btn-blue:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        .qb-btn-gray {
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
            border: 1px solid #94a3b8 !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 1px 2px rgba(0, 0, 0, 0.1) !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 11px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
            white-space: nowrap;
        }
        .qb-btn-gray:hover {
            background: #cbd5e1 !important;
            color: #0f172a !important;
        }

        /* 2. Sub-Header Ribbon: Financial KPIs & Totals */
        .qb-sub-ribbon {
            background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
            border: 1px solid #b2c5dc;
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 4px !important;
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
            gap: 5px;
            line-height: 1.25;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .qb-kpi-chip .chip-label {
            font-weight: 700;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .qb-kpi-chip .chip-val {
            font-weight: 800;
            font-family: 'JetBrains Mono', Consolas, monospace;
        }

        /* 3. Filter Bar (Single Line QuickBooks Strip) */
        .qb-filter-bar {
            background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
            border: 1px solid #b9cce2;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 5px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        .qb-filter-form {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            width: 100%;
            margin: 0;
        }
        .qb-filter-col {
            position: relative;
            display: flex;
            align-items: center;
        }
        .qb-input {
            height: 28px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            color: #0f2744 !important;
            padding: 2px 8px !important;
            background-color: #ffffff;
        }
        .qb-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
        }

        .qb-btn-search {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 3px !important;
            height: 28px !important;
            padding: 0 14px !important;
            font-size: 11.5px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
            cursor: pointer;
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
            height: 28px !important;
            padding: 0 10px !important;
            font-size: 11.5px !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none !important;
            cursor: pointer;
        }
        .qb-btn-reset:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }

        /* 4. Table Card (Exact QuickBooks / Odoo Replica) */
        .qb-table-card {
            background: #ffffff;
            border: 1px solid #94a3b8;
            border-radius: 4px;
            padding: 4px 6px 6px 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .table-responsive {
            border: 1px solid #94a3b8 !important;
            border-radius: 3px !important;
            background-color: #ffffff;
            overflow-x: auto;
            min-height: 0 !important;
        }
        .qb-table {
            margin-bottom: 0 !important;
            font-size: 12.5px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Table Headers (Soft Blue-Gray Gradient with Dividers) */
        .qb-table thead th {
            background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
            color: #0f2744 !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            font-size: 11px !important;
            letter-spacing: 0.4px;
            border-bottom: 2px solid #94a3b8 !important;
            border-right: 1px solid #cbd5e1 !important;
            border-top: none !important;
            padding: 7px 9px !important;
            white-space: nowrap !important;
            vertical-align: middle;
        }
        .qb-table thead th:last-child {
            border-right: none !important;
        }

        /* Table Body Cells */
        .qb-table tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #edf2f7 !important;
            border-top: none !important;
            padding: 6px 9px !important;
            font-size: 12.5px !important;
            color: #0f172a !important;
            vertical-align: middle;
        }
        .qb-table tbody td:last-child {
            border-right: none !important;
        }

        /* Row Cursor Hover: Pastel Mint Green */
        .qb-table tbody tr {
            cursor: pointer;
            transition: background-color 0.1s ease-in-out;
        }
        .qb-table tbody tr:hover td,
        .qb-table tbody tr:hover th {
            background-color: #d5eed7 !important;
            color: #0f172a !important;
        }
        .qb-table tbody tr:hover td small,
        .qb-table tbody tr:hover td .text-muted {
            color: #1e3a5f !important;
        }
        .qb-table tbody tr.qb-active-row td {
            background-color: #d5eed7 !important;
            color: #0f172a !important;
        }

        /* Retain Contrast on Hover */
        .qb-table tbody tr:hover td .acc-debit-val,
        .qb-table tbody tr.qb-active-row td .acc-debit-val {
            color: #044e32 !important;
            font-weight: 800 !important;
        }
        .qb-table tbody tr:hover td .acc-credit-val,
        .qb-table tbody tr.qb-active-row td .acc-credit-val {
            color: #7f1d1d !important;
            font-weight: 800 !important;
        }

        /* Table Footer Summary Row */
        .qb-table tfoot th,
        .qb-table tfoot td {
            background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
            color: #0f2744 !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            border-top: 2px solid #94a3b8 !important;
            border-right: 1px solid #cbd5e1 !important;
            padding: 8px 9px !important;
            vertical-align: middle;
        }
        .qb-table tfoot th:last-child,
        .qb-table tfoot td:last-child {
            border-right: none !important;
        }

        /* High-Contrast Tags & Badges */
        .qb-inv-tag {
            font-family: 'JetBrains Mono', Consolas, monospace;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #0f2744;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-decoration: none !important;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .qb-inv-tag:hover {
            background: #e2e8f0;
            color: #1d4ed8;
            border-color: #94a3b8;
        }

        .acc-bal-flag {
            font-size: 10px !important;
            font-weight: 800 !important;
            padding: 1px 5px !important;
            border-radius: 3px !important;
            margin-left: 3px !important;
            display: inline-block;
            vertical-align: middle;
        }
        .acc-bal-flag.dr {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            border: 1px solid #86efac !important;
        }
        .acc-bal-flag.cr {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
            border: 1px solid #fca5a5 !important;
        }

        .acc-debit-val {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 12.5px !important;
            font-weight: 800 !important;
            color: #047857 !important;
        }
        .acc-credit-val {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 12.5px !important;
            font-weight: 800 !important;
            color: #b91c1c !important;
        }
        .acc-balance-val {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 12.5px !important;
            font-weight: 800 !important;
            color: #0f172a !important;
        }

        .acc-badge-code {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            background-color: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px !important;
            padding: 2px 7px !important;
            display: inline-block;
            letter-spacing: 0.3px;
        }

        .acc-badge-party-customer {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            display: inline-block;
            text-transform: uppercase;
        }
        .acc-badge-party-vendor {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            display: inline-block;
            text-transform: uppercase;
        }
        .acc-badge-party-account {
            background: #f3e8ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            display: inline-block;
            text-transform: uppercase;
        }

        /* ==========================================================
           PRINT STYLESHEET (PROFESSIONAL A4 STATEMENT)
           ========================================================== */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 12px !important;
            }
            .rt_nav_header,
            .qb-win-header,
            .qb-filter-bar,
            .btn,
            footer,
            .no-print {
                display: none !important;
            }
            .odoo-page-wrapper {
                padding: 0 !important;
            }
            .qb-table-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .table-responsive {
                border: none !important;
                overflow: visible !important;
            }
            .qb-table {
                width: 100% !important;
                border: 1px solid #000 !important;
            }
            .qb-table thead th {
                background: #f1f5f9 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .qb-table tbody td {
                border: 1px solid #cbd5e1 !important;
                color: #000 !important;
            }
            .qb-table tfoot th,
            .qb-table tfoot td {
                background: #f1f5f9 !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-header {
                display: block !important;
                margin-bottom: 20px;
                border-bottom: 2px solid #0f2744;
                padding-bottom: 12px;
            }
        }
        @media screen {
            .print-header {
                display: none !important;
            }
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid odoo-page-wrapper">

                {{-- Row 1: Top Window Title Bar (Navy Blue Gradient - Exact POS / ERP Strip) --}}
                <div class="qb-win-header">
                    <div class="qb-win-title">
                        <i class="fas fa-book-open"></i>
                        <span>General Ledger (Account Statement)</span>
                        <span class="qb-win-sub d-none d-sm-inline">/ {{ $account->title }} ({{ $account->account_code }})</span>
                    </div>

                    {{-- Top Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        @if($entries->count() > 0)
                            <button type="button" class="qb-btn-blue" onclick="window.print()" title="Print Account Statement">
                                <i class="fas fa-print"></i> Print Statement
                            </button>
                        @endif
                        <a href="{{ route('view_all') }}" class="qb-btn-gray" title="Back to Chart of Accounts">
                            <i class="fas fa-arrow-left"></i> Back to Accounts
                        </a>
                    </div>
                </div>

                {{-- Print-Only Header (for Official Paperwork Statement) --}}
                <div class="print-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 style="margin: 0; font-size: 22px; font-weight: 900; color: #18385e;">
                                {{ \App\Models\Setting::get('company_name', 'KENT HARDWARE & INDUSTRIAL SOLUTIONS') }}
                            </h2>
                            <p style="margin: 2px 0; font-size: 11px; color: #475569;">
                                {{ \App\Models\Setting::get('company_address', 'Shop No. 91/1, Opp: FG Girls High School, Saddar Bazar Hyderabad Sindh') }}
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #475569;">
                                Phone: {{ \App\Models\Setting::get('company_phone', '+92-022-2781224') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <div style="font-size: 16px; font-weight: 800; color: #18385e; text-transform: uppercase;">General Ledger Statement</div>
                            <div style="font-size: 11px; color: #64748b;">
                                Statement Period: 
                                <strong>{{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : 'Beginning' }}</strong>
                                to 
                                <strong>{{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d/m/Y') : date('d/m/Y') }}</strong>
                            </div>
                            <div style="font-size: 11px; color: #64748b;">
                                Printed On: <strong>{{ date('d/m/Y h:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px;">
                        <div class="row g-2" style="font-size: 11.5px;">
                            <div class="col-4">
                                <strong>Account:</strong> {{ $account->title }} ({{ $account->account_code }})
                            </div>
                            <div class="col-4 text-center">
                                <strong>Head / Group:</strong> {{ $account->head->name ?? 'N/A' }}
                            </div>
                            <div class="col-4 text-end">
                                <strong>Type:</strong> {{ $account->type }} ({{ $account->type == 'Debit' ? 'Dr' : 'Cr' }})
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Sub-Header Ribbon: Financial KPIs & Totals --}}
                @php
                    $calcOB = $openingBalance ?? ($account->opening_balance ?? 0);
                    $periodDebit = $entries->sum('debit');
                    $periodCredit = $entries->sum('credit');
                    $finalBalance = $calcOB + $periodDebit - $periodCredit;
                @endphp
                <div class="qb-sub-ribbon">
                    {{-- Left side: Account Context & Badges --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="qb-kpi-chip">
                            <i class="fas fa-university text-primary"></i>
                            <span class="chip-label">Account:</span>
                            <span class="chip-val text-dark" style="font-size: 12px;">{{ $account->title }}</span>
                        </span>
                        <span class="acc-badge-code">{{ $account->account_code }}</span>
                        <span class="qb-kpi-chip">
                            <i class="fas fa-folder text-muted"></i>
                            <span class="chip-label">Head:</span>
                            <span class="chip-val text-dark">{{ $account->head->name ?? 'N/A' }}</span>
                        </span>
                        <span class="qb-kpi-chip">
                            <span class="chip-label">Type:</span>
                            <span class="chip-val {{ $account->type == 'Debit' ? 'text-primary' : 'text-warning' }}">{{ $account->type }}</span>
                        </span>
                        <span class="qb-kpi-chip">
                            <i class="fas fa-receipt text-muted"></i>
                            <span class="chip-val text-dark">{{ $entries->count() }} Entries</span>
                        </span>
                    </div>

                    {{-- Right side: Financial KPI Totals --}}
                    <div class="qb-kpi-group">
                        <div class="qb-kpi-chip">
                            <span class="chip-label">Opening:</span>
                            <span class="chip-val font-monospace text-dark">Rs {{ number_format($calcOB, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip">
                            <span class="chip-label text-success">Period Debit:</span>
                            <span class="chip-val font-monospace text-success">Rs {{ number_format($periodDebit, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip">
                            <span class="chip-label text-danger">Period Credit:</span>
                            <span class="chip-val font-monospace text-danger">Rs {{ number_format($periodCredit, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip" style="background: #ffffff; border: 1.5px solid #2563eb;">
                            <span class="chip-label" style="color: #18385e; font-weight: 800;">Closing Balance:</span>
                            <span class="chip-val font-monospace {{ $finalBalance >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 12.5px; font-weight: 900;">
                                Rs {{ number_format(abs($finalBalance), 2) }}
                                <span class="acc-bal-flag {{ $finalBalance >= 0 ? 'dr' : 'cr' }}">{{ $finalBalance >= 0 ? 'Dr' : 'Cr' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Filter Bar (Single Line QuickBooks Strip) --}}
                <div class="qb-filter-bar no-print">
                    <form method="GET" action="{{ route('accounts.ledger', $account->id) }}" id="ledgerFilterForm" class="qb-filter-form">
                        
                        {{-- Quick Presets Dropdown --}}
                        <div class="qb-filter-col" style="width: 140px;">
                            <select id="quick_period" class="form-control qb-input" style="font-size: 11.5px;">
                                <option value="">-- Quick Period --</option>
                                <option value="today">Today</option>
                                <option value="this_week">This Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                                <option value="all_time">All Time</option>
                            </select>
                        </div>

                        {{-- From Date --}}
                        <div class="qb-filter-col" style="width: 135px;">
                            <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                class="form-control qb-input datepicker-custom" placeholder="From Date">
                        </div>

                        {{-- To Date --}}
                        <div class="qb-filter-col" style="width: 135px;">
                            <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                class="form-control qb-input datepicker-custom" placeholder="To Date">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="qb-filter-col d-flex align-items-center gap-1">
                            <button type="submit" class="qb-btn-search">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('accounts.ledger', $account->id) }}" class="qb-btn-reset" title="Clear Filters">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Row 4: High-Density QuickBooks / Odoo Grid Table --}}
                <div class="qb-table-card">
                    <div class="table-responsive">
                        <table class="table qb-table" id="ledger-table">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th width="10%" class="text-center">Date</th>
                                    <th width="12%" class="text-center">Voucher / Ref</th>
                                    <th width="30%">Description / Particulars</th>
                                    <th width="15%">Party / Source</th>
                                    <th width="10%" class="text-end">Debit (Dr)</th>
                                    <th width="10%" class="text-end">Credit (Cr)</th>
                                    <th width="10%" class="text-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $runningBalance = $calcOB;
                                @endphp

                                {{-- Opening Balance Row --}}
                                <tr style="background-color: #fbfcfe;">
                                    <td class="text-center text-muted font-monospace"><i class="fas fa-history text-muted"></i></td>
                                    <td class="text-center font-monospace text-muted" style="font-size: 11.5px;">
                                        {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : 'Beginning' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10.5px;">OPENING</span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Opening Balance B/F
                                        <small class="text-muted font-weight-normal ms-1">(Balance brought forward prior to date range)</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">-</span>
                                    </td>
                                    <td class="text-end font-monospace text-muted">-</td>
                                    <td class="text-end font-monospace text-muted">-</td>
                                    <td class="text-end">
                                        <span class="acc-balance-val">
                                            {{ number_format(abs($runningBalance), 2) }}
                                            <span class="acc-bal-flag {{ $runningBalance >= 0 ? 'dr' : 'cr' }}">{{ $runningBalance >= 0 ? 'Dr' : 'Cr' }}</span>
                                        </span>
                                    </td>
                                </tr>

                                @forelse ($entries as $entry)
                                    @php
                                        $debit = (float) ($entry->debit ?? 0);
                                        $credit = (float) ($entry->credit ?? 0);
                                        $runningBalance = $runningBalance + $debit - $credit;

                                        $voucherNo = '-';
                                        if ($entry->source && !empty($entry->source->voucher_no)) {
                                            $voucherNo = $entry->source->voucher_no;
                                        } elseif ($entry->source && !empty($entry->source->invoice_no)) {
                                            $voucherNo = $entry->source->invoice_no;
                                        } elseif (!empty($entry->voucher_no)) {
                                            $voucherNo = $entry->voucher_no;
                                        }

                                        // Resolve Party Name and Tag
                                        $partyName = '';
                                        $partyType = '';
                                        $partyBadgeClass = 'acc-badge-party-account';
                                        if ($entry->party) {
                                            if ($entry->party_type === 'App\\Models\\Customer') {
                                                $partyName = $entry->party->customer_name ?? 'Unknown Customer';
                                                $partyType = 'Customer';
                                                $partyBadgeClass = 'acc-badge-party-customer';
                                            } elseif ($entry->party_type === 'App\\Models\\Vendor') {
                                                $partyName = $entry->party->name ?? 'Unknown Vendor';
                                                $partyType = 'Vendor';
                                                $partyBadgeClass = 'acc-badge-party-vendor';
                                            } else {
                                                $partyName = $entry->party->title ?? $entry->party->name ?? 'Account';
                                                $partyType = 'Account';
                                                $partyBadgeClass = 'acc-badge-party-account';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center font-monospace text-muted" style="font-size: 11.5px;">{{ $loop->iteration }}</td>
                                        <td class="text-center font-monospace" style="font-size: 11.5px; font-weight: 600;">
                                            {{ $entry->entry_date ? $entry->entry_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($voucherNo !== '-')
                                                <span class="qb-inv-tag">
                                                    <i class="fas fa-file-alt text-primary"></i> {{ $voucherNo }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark" style="font-size: 12.5px;">{{ $entry->description }}</span>
                                        </td>
                                        <td>
                                            @if ($partyName)
                                                <span class="{{ $partyBadgeClass }}">{{ $partyType }}</span>
                                                <span class="fw-bold d-block text-dark" style="font-size: 12px; line-height: 1.25;">{{ $partyName }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($debit > 0)
                                                <span class="acc-debit-val">{{ number_format($debit, 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($credit > 0)
                                                <span class="acc-credit-val">{{ number_format($credit, 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <span class="acc-balance-val">
                                                {{ number_format(abs($runningBalance), 2) }}
                                                <span class="acc-bal-flag {{ $runningBalance >= 0 ? 'dr' : 'cr' }}">{{ $runningBalance >= 0 ? 'Dr' : 'Cr' }}</span>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle mr-1"></i> No transactions found for the selected date range.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end text-uppercase" style="letter-spacing: 0.5px;">
                                        Total for Selected Period ({{ $entries->count() }} Transactions):
                                    </th>
                                    <th class="text-end">
                                        <span class="acc-debit-val" style="font-size: 13px;">{{ number_format($periodDebit, 2) }}</span>
                                    </th>
                                    <th class="text-end">
                                        <span class="acc-credit-val" style="font-size: 13px;">{{ number_format($periodCredit, 2) }}</span>
                                    </th>
                                    <th class="text-end">
                                        <span class="acc-balance-val" style="font-size: 13px;">
                                            {{ number_format(abs($finalBalance), 2) }}
                                            <span class="acc-bal-flag {{ $finalBalance >= 0 ? 'dr' : 'cr' }}">{{ $finalBalance >= 0 ? 'Dr' : 'Cr' }}</span>
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // 1. Initialize Flatpickr for clean date selection if available
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker-custom", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    allowInput: true
                });
            }

            // 2. Quick Period Shortcut Presets (Today, This Week, This Month, Last Month, This Year, All Time)
            $('#quick_period').on('change', function() {
                let val = $(this).val();
                let today = new Date();
                let start = null;
                let end = new Date();

                let formatDate = function(d) {
                    let year = d.getFullYear();
                    let month = String(d.getMonth() + 1).padStart(2, '0');
                    let day = String(d.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                if (val === 'today') {
                    start = new Date();
                    end = new Date();
                } else if (val === 'this_week') {
                    let day = today.getDay();
                    let diff = today.getDate() - day + (day === 0 ? -6 : 1); // Monday
                    start = new Date(today.setDate(diff));
                    end = new Date();
                } else if (val === 'this_month') {
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = new Date();
                } else if (val === 'last_month') {
                    start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    end = new Date(today.getFullYear(), today.getMonth(), 0);
                } else if (val === 'this_year') {
                    start = new Date(today.getFullYear(), 0, 1);
                    end = new Date();
                } else if (val === 'all_time') {
                    start = new Date(2000, 0, 1);
                    end = new Date();
                }

                if (start && end) {
                    let startStr = formatDate(start);
                    let endStr = formatDate(end);

                    let pickerFrom = document.getElementById('from_date') ? document.getElementById('from_date')._flatpickr : null;
                    let pickerTo = document.getElementById('to_date') ? document.getElementById('to_date')._flatpickr : null;

                    if (pickerFrom) pickerFrom.setDate(startStr, true);
                    else $("#from_date").val(startStr);

                    if (pickerTo) pickerTo.setDate(endStr, true);
                    else $("#to_date").val(endStr);
                }
            });

            // 3. Interactive Row Highlighting
            $('#ledger-table tbody tr').on('click', function() {
                $('#ledger-table tbody tr').removeClass('qb-active-row');
                $(this).addClass('qb-active-row');
            });
        });
    </script>
@endpush
