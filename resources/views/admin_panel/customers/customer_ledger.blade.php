@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* ==========================================================
           QUICKBOOKS / ODOO ERP "CUSTOMER LEDGER (STATEMENT)" THEME
           Pixel-Perfect High-Density Layout Matching Sales & Accounts
           ========================================================== */

        /* Native QuickBooks Font Stack - Crystal Clear on Windows with ClearType Subpixel Rendering */
        html, body,
        .odoo-page-wrapper,
        .qb-table,
        .qb-table thead th,
        .qb-table tbody td,
        .qb-win-header,
        .qb-filter-bar,
        .qb-table-card,
        input, button, select, textarea {
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Tahoma, Roboto, "Helvetica Neue", Arial, sans-serif !important;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: subpixel-antialiased !important;
            -moz-osx-font-smoothing: auto !important;
        }

        .odoo-page-wrapper {
            padding: 0 4px !important;
        }

        .font-monospace,
        .qb-mono,
        .qb-table td.font-monospace,
        .qb-table span.font-monospace {
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Tahoma, Roboto, Arial, sans-serif !important;
            font-variant-numeric: tabular-nums !important;
            font-feature-settings: "tnum" !important;
        }

        /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS & Chart of Accounts Replica) */
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

        .qb-btn-green {
            background: linear-gradient(180deg, #53af41 0%, #35832a 100%) !important;
            border: 1px solid #286c1f !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
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
        .qb-btn-green:hover {
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
            font-family: 'JetBrains Mono', 'Consolas', monospace;
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
            font-weight: 500 !important;
            color: #0f2744 !important;
            padding: 2px 8px !important;
            background-color: #ffffff;
        }
        .qb-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
        }

        /* Select2 Style */
        .qb-filter-col .select2-container .select2-selection--single {
            height: 28px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            padding: 0 4px !important;
            background: #ffffff;
        }
        .qb-filter-col .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 26px !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            color: #0f2744 !important;
            padding-left: 4px !important;
        }
        .qb-filter-col .select2-container .select2-selection--single .select2-selection__arrow {
            height: 26px !important;
            right: 4px !important;
        }

        /* Date input constraints */
        .qb-filter-col.date-col {
            width: 115px !important;
            flex: 0 0 115px !important;
        }
        .qb-filter-col.date-col input {
            width: 100% !important;
            height: 28px !important;
            font-size: 11.5px !important;
            padding: 2px 8px !important;
            text-align: center;
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

        /* 4. Table Card (Exact QuickBooks Replica) */
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
            font-size: 12px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Table Headers (Dark Navy Blue - 100% High Contrast Against White Data Rows) */
        .qb-table thead,
        .qb-table thead tr,
        .qb-table thead th,
        table.dataTable thead th {
            background: linear-gradient(180deg, #18385e 0%, #0f2238 100%) !important;
            background-color: #18385e !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            font-size: 11.5px !important;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #081422 !important;
            border-right: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-top: none !important;
            padding: 8px 10px !important;
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
            font-size: 12px !important;
            color: #1a202c !important;
            vertical-align: middle;
            font-variant-numeric: tabular-nums;
        }
        .qb-table tbody td:last-child {
            border-right: none !important;
        }

        /* ==========================================================
           ROW CURSOR HOVER: PASTEL MINT GREEN (Exact Replica)
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

        /* Table Footer Summary Row */
        .qb-table tfoot th {
            background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
            color: #193452 !important;
            font-weight: 800 !important;
            font-size: 11.5px !important;
            border-top: 2px solid #aebfd4 !important;
            border-right: 1px solid #c9d7e5 !important;
            padding: 8px 9px !important;
            vertical-align: middle;
        }
        .qb-table tfoot th:last-child {
            border-right: none !important;
        }

        /* Badges for Transaction Types */
        .qb-badge-type {
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            text-transform: uppercase;
        }
        .qb-badge-inv {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .qb-badge-pay {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .qb-badge-ret {
            background-color: #ffedd5;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }
        .qb-badge-ob {
            background-color: #f3e8ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }
        .qb-badge-gen {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        /* Balance Tag */
        .qb-bal-pill {
            display: inline-block;
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 3px;
            margin-left: 4px;
            vertical-align: middle;
        }
        .qb-bal-pill.dr {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .qb-bal-pill.cr {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* Invoice Link Pill */
        .qb-inv-tag {
            font-family: 'JetBrains Mono', 'Consolas', monospace;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #0f2744;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 3px;
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

        /* Customer Initials Avatar */
        .qb-avatar-circle {
            width: 22px;
            height: 22px;
            background: #18385e;
            color: #ffffff;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
            flex-shrink: 0;
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
            .qb-table tfoot th {
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
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Customer Ledger (Statement)</span>
                        <span class="qb-win-sub d-none d-sm-inline">/ Account Statement &amp; History</span>
                    </div>

                    {{-- Top Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        @if(request('customer_id') && $CustomerLedgers->count() > 0)
                            <button type="button" class="qb-btn-blue" onclick="window.print()" title="Print Customer Statement">
                                <i class="fas fa-print"></i> Print Statement
                            </button>
                        @endif
                        <a href="{{ route('view_all') }}" class="qb-btn-gray" title="Back to Chart of Accounts">
                            <i class="fas fa-arrow-left"></i> Back to Accounts
                        </a>
                    </div>
                </div>

                {{-- Print-Only Header (for Official Customer Statement Paperwork) --}}
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
                            <div style="font-size: 16px; font-weight: 800; color: #18385e; text-transform: uppercase;">Customer Statement</div>
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
                    @if($selected_customer)
                        <div class="mt-3 p-2" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px;">
                            <div class="row g-2" style="font-size: 11.5px;">
                                <div class="col-6">
                                    <strong>Customer:</strong> {{ $selected_customer->customer_name }}
                                </div>
                                <div class="col-6 text-end">
                                    <strong>Contact / Mobile:</strong> {{ $selected_customer->mobile ?? 'N/A' }}
                                </div>
                                @if(!empty($selected_customer->address))
                                <div class="col-12">
                                    <strong>Address:</strong> {{ $selected_customer->address }}
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Row 2: Sub-Header Ribbon: Financial KPIs & Totals --}}
                @php
                    $totalDebit = $CustomerLedgers->sum('debit');
                    $totalCredit = $CustomerLedgers->sum('credit');
                    $cb = (float) ($closing_balance ?? 0);
                    $ob = (float) ($opening_balance ?? 0);
                    $netChange = $totalDebit - $totalCredit;
                @endphp
                <div class="qb-sub-ribbon">
                    {{-- Left side: Customer Context & Entries Count --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @if($selected_customer)
                            <div class="qb-kpi-chip">
                                <span class="qb-avatar-circle">{{ strtoupper(substr($selected_customer->customer_name, 0, 1)) }}</span>
                                <span class="chip-label text-secondary">Customer:</span>
                                <span class="chip-val text-dark">{{ $selected_customer->customer_name }}</span>
                            </div>

                            @if(!empty($selected_customer->mobile))
                                <div class="qb-kpi-chip d-none d-md-inline-flex" title="Customer Phone">
                                    <i class="fas fa-phone-alt text-muted"></i>
                                    <span class="chip-val text-muted">{{ $selected_customer->mobile }}</span>
                                </div>
                            @endif

                            <div class="qb-kpi-chip" title="Total Transactions in Selected Range">
                                <span class="chip-label text-secondary">Entries:</span>
                                <span class="chip-val text-primary">{{ $CustomerLedgers->count() }}</span>
                            </div>
                        @else
                            <div class="text-secondary fw-bold d-flex align-items-center gap-2" style="font-size: 11px;">
                                <i class="fas fa-info-circle text-primary"></i>
                                <span>Select a customer from the dropdown below to load ledger statement.</span>
                            </div>
                        @endif
                    </div>

                    {{-- Right side: QuickBooks Financial Summary Chips --}}
                    @if(request('customer_id'))
                        <div class="qb-kpi-group">
                            {{-- Opening Balance --}}
                            <div class="qb-kpi-chip" title="Opening Balance prior to selected period">
                                <span class="chip-label">Opening:</span>
                                <span class="chip-val text-dark">Rs. {{ number_format(abs($ob), 2) }}</span>
                                <span class="qb-bal-pill {{ $ob >= 0 ? 'dr' : 'cr' }}">{{ $ob >= 0 ? 'Dr' : 'Cr' }}</span>
                            </div>

                            {{-- Total Debits (Invoices / Charges) --}}
                            <div class="qb-kpi-chip" title="Total Invoices &amp; Debit Entries in period">
                                <span class="chip-label text-success">Period Debit (Dr):</span>
                                <span class="chip-val text-success">Rs. {{ number_format($totalDebit, 2) }}</span>
                            </div>

                            {{-- Total Credits (Payments &amp; Receipts) --}}
                            <div class="qb-kpi-chip" title="Total Payments &amp; Credit Entries in period">
                                <span class="chip-label text-danger">Period Credit (Cr):</span>
                                <span class="chip-val text-danger">Rs. {{ number_format($totalCredit, 2) }}</span>
                            </div>

                            {{-- Closing Balance Pill --}}
                            <div class="qb-kpi-chip" style="background: {{ $cb > 0 ? '#fff5f5' : ($cb < 0 ? '#f0fdf4' : '#ffffff') }}; border-color: {{ $cb > 0 ? '#f87171' : ($cb < 0 ? '#4ade80' : '#8ca4c3') }};" title="Current Net Balance">
                                <span class="chip-label" style="color: {{ $cb > 0 ? '#b91c1c' : ($cb < 0 ? '#15803d' : '#475569') }};">Closing Balance:</span>
                                <span class="chip-val" style="color: {{ $cb > 0 ? '#b91c1c' : ($cb < 0 ? '#15803d' : '#0f172a') }};">
                                    Rs. {{ number_format(abs($cb), 2) }}
                                </span>
                                <span class="qb-bal-pill {{ $cb >= 0 ? 'dr' : 'cr' }}">{{ $cb >= 0 ? 'Dr' : 'Cr' }}</span>
                                <small class="fw-bold d-none d-lg-inline" style="font-size: 10px; color: {{ $cb > 0 ? '#b91c1c' : ($cb < 0 ? '#15803d' : '#64748b') }};">
                                    ({{ $cb > 0 ? 'Receivable' : ($cb < 0 ? 'Advance' : 'Settled') }})
                                </small>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Row 3: Filter Bar (Single Line QuickBooks Horizontal Strip) --}}
                <div class="qb-filter-bar no-print">
                    <form method="GET" action="{{ route('customers.ledger') }}" class="qb-filter-form" id="ledgerFilterForm">
                        
                        {{-- Customer Selector --}}
                        <div class="qb-filter-col" style="flex: 1 1 260px; min-width: 200px; max-width: 380px;">
                            <select name="customer_id" id="customer_id" class="form-select qb-input select2-customer" style="width: 100%;" required>
                                <option value="">-- Search &amp; Select Customer --</option>
                                @foreach ($customers as $cust)
                                    <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->customer_name }} @if(!empty($cust->mobile)) ({{ $cust->mobile }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Quick Date Range Shortcuts --}}
                        <div class="qb-filter-col" style="width: 110px; flex: 0 0 110px;">
                            <select id="quick_period" class="form-select qb-input" style="width: 100%;">
                                <option value="custom" selected>Custom</option>
                                <option value="today">Today</option>
                                <option value="this_week">This Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                                <option value="all_time">All Time</option>
                            </select>
                        </div>

                        {{-- From Date --}}
                        <div class="qb-filter-col date-col">
                            <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                class="form-control qb-input datepicker-custom" placeholder="From Date">
                        </div>

                        {{-- To Date --}}
                        <div class="qb-filter-col date-col">
                            <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                class="form-control qb-input datepicker-custom" placeholder="To Date">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="qb-filter-col d-flex align-items-center gap-1">
                            <button type="submit" class="qb-btn-search">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('customers.ledger') }}" class="qb-btn-reset" title="Clear Filters">
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
                                    <th width="4%" class="text-center">#</th>
                                    <th width="10%" class="text-center">Date</th>
                                    <th width="10%" class="text-center">Type</th>
                                    <th width="18%">Customer</th>
                                    <th width="30%">Description / Particulars</th>
                                    <th width="12%" class="text-end">Debit (Dr)</th>
                                    <th width="12%" class="text-end">Credit (Cr)</th>
                                    <th width="14%" class="text-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(request('customer_id') && ($ob != 0 || $CustomerLedgers->count() > 0))
                                    {{-- Opening Balance Row --}}
                                    <tr style="background-color: #fbfcfe;">
                                        <td class="text-center text-muted font-monospace"><i class="fas fa-history text-muted"></i></td>
                                        <td class="text-center font-monospace text-muted">
                                            {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : 'Beginning' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="qb-badge-type qb-badge-ob"><i class="fas fa-lock"></i> Opening</span>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ $selected_customer->customer_name ?? 'Customer' }}
                                        </td>
                                        <td class="fw-semibold text-secondary">
                                            Opening Balance B/F
                                            <small class="text-muted">(Prior to selected date range)</small>
                                        </td>
                                        <td class="text-end font-monospace">
                                            {{ $ob > 0 ? number_format($ob, 2) : '-' }}
                                        </td>
                                        <td class="text-end font-monospace">
                                            {{ $ob < 0 ? number_format(abs($ob), 2) : '-' }}
                                        </td>
                                        <td class="text-end font-monospace fw-bold">
                                            {{ number_format(abs($ob), 2) }}
                                            <span class="qb-bal-pill {{ $ob >= 0 ? 'dr' : 'cr' }}">{{ $ob >= 0 ? 'Dr' : 'Cr' }}</span>
                                        </td>
                                    </tr>
                                @endif

                                @forelse ($CustomerLedgers as $key => $ledger)
                                    @php
                                        $debit = (float) ($ledger->debit ?? 0);
                                        $credit = (float) ($ledger->credit ?? 0);
                                        $balance = (float) $ledger->closing_balance;
                                        $suffix = $balance >= 0 ? 'Dr' : 'Cr';

                                        // Determine transaction type
                                        $desc = $ledger->description ?? '';
                                        $isInvoice = stripos($desc, 'Invoice') !== false || stripos($desc, 'Sale') !== false;
                                        $isPayment = stripos($desc, 'Payment') !== false || stripos($desc, 'Receipt') !== false;
                                        $isReturn = stripos($desc, 'Return') !== false;
                                        $isOpening = stripos($desc, 'Opening') !== false;

                                        // Extract invoice number if present (e.g., #INV-0005)
                                        $invNo = null;
                                        if (preg_match('/#?INV-[0-9A-Za-z-]+/i', $desc, $matches)) {
                                            $invNo = ltrim($matches[0], '#');
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted font-monospace">{{ $loop->iteration }}</td>
                                        <td class="text-center font-monospace fw-semibold">{{ $ledger->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @if($isReturn)
                                                <span class="qb-badge-type qb-badge-ret"><i class="fas fa-undo"></i> Return</span>
                                            @elseif($isPayment)
                                                <span class="qb-badge-type qb-badge-pay"><i class="fas fa-receipt"></i> Payment</span>
                                            @elseif($isInvoice)
                                                <span class="qb-badge-type qb-badge-inv"><i class="fas fa-file-invoice"></i> Invoice</span>
                                            @elseif($isOpening)
                                                <span class="qb-badge-type qb-badge-ob"><i class="fas fa-lock"></i> Opening</span>
                                            @else
                                                <span class="qb-badge-type qb-badge-gen"><i class="fas fa-book"></i> Journal</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ $ledger->customer->customer_name ?? ($selected_customer->customer_name ?? 'N/A') }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                                <span class="fw-semibold">{{ $ledger->description }}</span>

                                                @if($ledger->source_type === 'App\Models\Sale' && $ledger->source_id)
                                                    <a href="{{ route('sales.receipt', $ledger->source_id) }}" target="_blank" class="qb-inv-tag no-print" title="View Sale Receipt">
                                                        <i class="fas fa-external-link-alt"></i> Receipt
                                                    </a>
                                                @elseif($invNo)
                                                    <span class="qb-inv-tag" title="Invoice Number">
                                                        <i class="fas fa-hashtag"></i> {{ $invNo }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end font-monospace fw-bold" style="color: #059669;">
                                            {{ $debit > 0 ? number_format($debit, 2) : '-' }}
                                        </td>
                                        <td class="text-end font-monospace fw-bold" style="color: #dc2626;">
                                            {{ $credit > 0 ? number_format($credit, 2) : '-' }}
                                        </td>
                                        <td class="text-end font-monospace fw-bold">
                                            {{ number_format(abs($balance), 2) }}
                                            <span class="qb-bal-pill {{ $suffix == 'Dr' ? 'dr' : 'cr' }}">{{ $suffix }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    @if(request('customer_id'))
                                        <tr>
                                            <td colspan="8" class="text-center py-4 bg-white">
                                                <div class="py-3">
                                                    <i class="fas fa-inbox text-secondary fs-1 d-block mb-2" style="opacity: 0.4;"></i>
                                                    <div class="fw-bold text-dark fs-6">No Transactions in Selected Period</div>
                                                    <div class="text-muted small">
                                                        Opening Balance as of {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d/m/Y') : 'Beginning' }} is 
                                                        <strong>Rs. {{ number_format(abs($ob), 2) }} {{ $ob >= 0 ? 'Dr' : 'Cr' }}</strong>.
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center py-5 bg-white">
                                                <div class="py-4">
                                                    <i class="fas fa-user-check text-primary fs-1 d-block mb-3" style="opacity: 0.6;"></i>
                                                    <div class="fw-bold text-dark fs-5 mb-1">Select a Customer to View Ledger</div>
                                                    <div class="text-muted small mb-3">Choose a customer from the dropdown above to load their complete transaction history and statement.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                            @if(request('customer_id') && ($CustomerLedgers->count() > 0 || $ob != 0))
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end text-uppercase">
                                            Total for Period ({{ $CustomerLedgers->count() }} Transactions):
                                        </th>
                                        <th class="text-end font-monospace text-success fw-bold">
                                            Rs. {{ number_format($totalDebit, 2) }}
                                        </th>
                                        <th class="text-end font-monospace text-danger fw-bold">
                                            Rs. {{ number_format($totalCredit, 2) }}
                                        </th>
                                        <th class="text-end font-monospace fw-bold" style="color: {{ $cb > 0 ? '#b91c1c' : ($cb < 0 ? '#15803d' : '#0f172a') }};">
                                            Rs. {{ number_format(abs($cb), 2) }}
                                            <span class="qb-bal-pill {{ $cb >= 0 ? 'dr' : 'cr' }}">{{ $cb >= 0 ? 'Dr' : 'Cr' }}</span>
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Print Signatures Footer (Visible only in print) --}}
                <div class="print-header mt-5 pt-4">
                    <div class="row pt-4" style="border-top: 1px dashed #94a3b8;">
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px; font-weight: 700; font-size: 11px;">
                                Prepared By
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px; font-weight: 700; font-size: 11px;">
                                Checked &amp; Verified By
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px; font-weight: 700; font-size: 11px;">
                                Customer Signature &amp; Stamp
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-muted mt-3" style="font-size: 10px;">
                        This statement was automatically generated by {{ \App\Models\Setting::get('company_name', 'KENT HARDWARE ERP') }} on {{ date('d/m/Y h:i A') }}.
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // 1. Initialize Select2 for Customer Dropdown
            if ($.fn.select2 && $('.select2-customer').length > 0) {
                $('.select2-customer').select2({
                    placeholder: "-- Search & Select Customer --",
                    allowClear: true,
                    width: '100%'
                });

                // Auto-submit on customer change for rapid navigation (standard QuickBooks behavior)
                $('.select2-customer').on('change', function() {
                    let val = $(this).val();
                    if (val) {
                        $('#ledgerFilterForm').submit();
                    }
                });
            }

            // 2. Initialize Flatpickr for clean date selection if available
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker-custom", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    allowInput: true
                });
            }

            // 3. Quick Period Shortcut Presets (Today, This Week, This Month, Last Month, This Year, All Time)
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

            // 4. Interactive Row Highlighting
            $('#ledger-table tbody tr').on('click', function() {
                $('#ledger-table tbody tr').removeClass('qb-active-row');
                $(this).addClass('qb-active-row');
            });
        });
    </script>
@endpush
