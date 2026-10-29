@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       MODERN ENTERPRISE "CUSTOMER LEDGER REPORT" THEME
       Pixel-Perfect High-Density ERP Layout
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

    .app-page-body, .main-content {
        padding-top: 6px !important;
        padding-bottom: 6px !important;
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
    input, button, select, textarea {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    .odoo-page-wrapper {
        padding: 0 8px !important;
        max-width: 100% !important;
    }

    .font-monospace, .qb-mono, .tabular-nums {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-variant-numeric: tabular-nums !important;
    }

    /* 1. Window Header */
    .qb-win-header {
        height: 42px !important;
        min-height: 42px !important;
        background: linear-gradient(135deg, #0f2744 0%, #1e3a5f 100%) !important;
        color: #ffffff !important;
        border-radius: 6px 6px 0 0;
        border: 1px solid #0a192b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        margin-bottom: 6px !important;
    }
    .qb-win-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
    }
    .qb-win-title i {
        color: #38bdf8;
        font-size: 16px;
    }
    .qb-win-sub {
        font-size: 11px;
        font-weight: 600;
        color: #93c5fd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 3px;
    }

    .qb-header-btn {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 4px;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 11.5px;
        padding: 4px 10px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .qb-header-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.4);
    }
    .qb-header-btn.btn-excel {
        background: #107c41;
        border-color: #0b592e;
    }
    .qb-header-btn.btn-excel:hover {
        background: #159c53;
        border-color: #107c41;
    }

    /* 2. Executive KPI Cards */
    .qb-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 6px !important;
    }
    .qb-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .qb-kpi-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    .qb-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    .qb-kpi-card.opening::before { background: #64748b; }
    .qb-kpi-card.debit::before { background: #ef4444; }
    .qb-kpi-card.credit::before { background: #10b981; }
    .qb-kpi-card.closing::before { background: #2563eb; }

    .qb-kpi-info {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }
    .qb-kpi-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    .qb-kpi-val {
        font-size: 15px;
        font-weight: 800;
        font-family: 'JetBrains Mono', Consolas, monospace;
        line-height: 1.25;
    }
    .qb-kpi-sub {
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
    }
    .qb-kpi-icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .qb-kpi-card.opening .qb-kpi-icon-wrap { background: #f1f5f9; color: #475569; }
    .qb-kpi-card.debit .qb-kpi-icon-wrap { background: #fef2f2; color: #ef4444; }
    .qb-kpi-card.credit .qb-kpi-icon-wrap { background: #ecfdf5; color: #10b981; }
    .qb-kpi-card.closing .qb-kpi-icon-wrap { background: #eff6ff; color: #2563eb; }

    /* 3. Filter Bar (Clean Responsive Flex Strip) */
    .qb-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 6px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    .qb-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
    }
    .qb-filter-col {
        display: flex;
        flex-direction: column;
    }
    .qb-flabel {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #334155 !important;
        margin-bottom: 3px !important;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .qb-flabel i {
        color: #64748b;
        font-size: 10.5px;
    }
    .qb-input, select.qb-input {
        height: 32px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        padding: 3px 8px !important;
        background-color: #ffffff;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .qb-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    /* Select2 High-Density ERP Styling */
    .qb-filter-col .select2-container .select2-selection--single {
        height: 32px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 0 4px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .qb-filter-col .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        padding-left: 6px !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px !important;
        right: 6px !important;
    }

    .qb-btn-search {
        background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%) !important;
        border: 1px solid #1e40af !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 32px !important;
        padding: 0 14px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        white-space: nowrap;
        cursor: pointer;
        transition: filter 0.12s ease;
    }
    .qb-btn-search:hover { filter: brightness(1.1); color: #ffffff !important; }

    .qb-btn-reset {
        background: #f8fafc !important;
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
        font-weight: 600 !important;
        border-radius: 4px !important;
        height: 32px !important;
        padding: 0 12px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.12s ease;
    }
    .qb-btn-reset:hover { background: #e2e8f0 !important; color: #0f172a !important; }

    /* 4. Table Card & Ledger Table */
    .qb-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .qb-table-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 7px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .qb-meta-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .qb-meta-name {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .qb-meta-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .sale-table-wrap {
        border: none !important;
        overflow-x: auto;
        max-height: calc(100vh - 240px);
        min-height: 380px;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .qb-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #1e293b !important;
        color: #f8fafc !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 11px !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #0f172a !important;
        border-right: 1px solid #334155 !important;
        padding: 8px 10px !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }

    .qb-table tbody td {
        border-bottom: 1px solid #edf2f7 !important;
        border-right: 1px solid #edf2f7 !important;
        padding: 6px 10px !important;
        font-size: 12.5px !important;
        color: #1e293b !important;
        vertical-align: middle;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* Soft Zebra Rows */
    .qb-table tbody tr:nth-child(even) td {
        background-color: #f8fafc !important;
    }
    .qb-table tbody tr:hover td {
        background-color: #eff6ff !important;
        color: #0f172a !important;
    }
    .qb-table tbody tr:hover td:first-child {
        box-shadow: inset 3px 0 0 #2563eb !important;
    }

    /* Opening Balance Row Styling */
    .qb-table tbody tr.row-opening td {
        background: #f0f7ff !important;
        border-bottom: 2px solid #bfdbfe !important;
        font-weight: 700 !important;
    }

    .acc-badge-code {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: #1e40af !important;
        background-color: #eff6ff !important;
        border: 1px solid #bfdbfe !important;
        border-radius: 4px !important;
        padding: 1px 7px !important;
        display: inline-block;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .acc-bal-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 1px 5px;
        border-radius: 3px;
        margin-left: 4px;
        vertical-align: middle;
        text-transform: uppercase;
    }
    .acc-bal-badge.dr {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .acc-bal-badge.cr {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .acc-ac-badge {
        font-size: 11px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 3px;
        padding: 1px 5px;
        margin-left: 4px;
        display: inline-block;
    }

    .qb-table tfoot tr td {
        position: sticky;
        bottom: 0;
        z-index: 9;
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 12.5px !important;
        border-top: 2px solid #334155 !important;
        border-right: 1px solid #1e293b !important;
        padding: 8px 10px !important;
    }

    /* Print View Customization */
    @media print {
        body { background: #ffffff !important; font-size: 10.5px !important; }
        .no-print, header, .sidebar, .navbar, footer, .qb-filter-card, .qb-win-header, .qb-table-toolbar .btn, .qb-table-toolbar input { display: none !important; }
        .odoo-page-wrapper { padding: 0 !important; background: #fff !important; }
        .qb-table-card { border: none !important; box-shadow: none !important; }
        .sale-table-wrap { max-height: none !important; overflow: visible !important; }
        .qb-table th { background: #e2e8f0 !important; color: #000000 !important; border: 1px solid #000 !important; }
        .qb-table td { border: 1px solid #999 !important; }
        .qb-table tfoot td { background: #f1f5f9 !important; color: #000000 !important; border: 1px solid #000 !important; }
        .qb-print-header { display: block !important; }
    }
    .qb-print-header { display: none; }

    @media (max-width: 991px) {
        .qb-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .qb-kpi-grid { grid-template-columns: 1fr; }
        .qb-filter-form { flex-direction: column; align-items: stretch; }
        .qb-filter-col { width: 100% !important; min-width: 100% !important; }
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- Printable Header (Shows Only in Print Mode) --}}
            <div class="qb-print-header mb-3">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">KENT HARDWARE</h4>
                        <div class="text-muted small">Customer Statement of Accounts / Ledger</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-dark" id="printCustomerName">All Customers</div>
                        <div class="small text-muted" id="printPeriod">Generated: {{ date('d-M-Y H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- 1. Top Window Title Bar --}}
            <div class="qb-win-header no-print">
                <div class="qb-win-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Customer Ledger Report</span>
                    <span class="qb-win-sub d-none d-md-inline">Statement of Accounts</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="qb-header-btn btn-excel" id="btnExportCsv" title="Export to CSV / Excel">
                        <i class="fas fa-file-excel"></i> Export CSV
                    </button>
                    <button type="button" class="qb-header-btn btnPrintReport" title="Print Ledger Statement">
                        <i class="fas fa-print"></i> Print Statement
                    </button>
                    <button type="button" class="qb-header-btn btnSearchTrigger" title="Refresh Report Data">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            {{-- 2. Executive Financial KPI Cards --}}
            <div class="qb-kpi-grid no-print">
                {{-- Opening Balance --}}
                <div class="qb-kpi-card opening">
                    <div class="qb-kpi-info">
                        <span class="qb-kpi-label">Opening Balance</span>
                        <span class="qb-kpi-val text-dark" id="pillOpeningBalance">Rs 0.00</span>
                        <span class="qb-kpi-sub">Brought Forward (B/F)</span>
                    </div>
                    <div class="qb-kpi-icon-wrap">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>

                {{-- Total Debit --}}
                <div class="qb-kpi-card debit">
                    <div class="qb-kpi-info">
                        <span class="qb-kpi-label text-danger">Total Debit (Dr)</span>
                        <span class="qb-kpi-val text-danger" id="pillTotalDebit">Rs 0.00</span>
                        <span class="qb-kpi-sub">Invoices & Charges</span>
                    </div>
                    <div class="qb-kpi-icon-wrap">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                {{-- Total Credit --}}
                <div class="qb-kpi-card credit">
                    <div class="qb-kpi-info">
                        <span class="qb-kpi-label text-success">Total Credit (Cr)</span>
                        <span class="qb-kpi-val text-success" id="pillTotalCredit">Rs 0.00</span>
                        <span class="qb-kpi-sub">Payments & Returns</span>
                    </div>
                    <div class="qb-kpi-icon-wrap">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

                {{-- Closing Balance --}}
                <div class="qb-kpi-card closing">
                    <div class="qb-kpi-info">
                        <span class="qb-kpi-label text-primary">Closing Balance</span>
                        <span class="qb-kpi-val text-primary" id="pillClosingBalance">Rs 0.00</span>
                        <span class="qb-kpi-sub" id="pillBalanceSub">Net Position</span>
                    </div>
                    <div class="qb-kpi-icon-wrap">
                        <i class="fas fa-scale-balanced"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Filter Bar (Clean Responsive Strip) --}}
            <div class="qb-filter-card no-print">
                <form id="ledgerFormDesk" class="qb-filter-form" onsubmit="return false;">
                    
                    {{-- Zone --}}
                    <div class="qb-filter-col" style="min-width: 160px; flex: 1;">
                        <label class="qb-flabel"><i class="fas fa-map-marker-alt"></i> Zone</label>
                        <select name="zone_id" id="zone_id_desk" class="form-select select2 zoneSelect">
                            <option value="">-- All Zones --</option>
                            @foreach ($zones as $z)
                                <option value="{{ $z->id }}">{{ $z->zone }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Customer --}}
                    <div class="qb-filter-col" style="min-width: 230px; flex: 1.6;">
                        <label class="qb-flabel"><i class="fas fa-user"></i> Customer</label>
                        <select name="customer_id" id="customer_id_desk" class="form-select select2 customerSelect">
                            <option value="all" data-zone="">-- All Customers --</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}" data-zone="{{ $c->zone }}">{{ $c->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quick Filter --}}
                    <div class="qb-filter-col" style="min-width: 140px; flex: 0.8;">
                        <label class="qb-flabel"><i class="fas fa-calendar-alt"></i> Quick Filter</label>
                        <select id="quick_filter_desk" class="qb-input quickFilterSelect">
                            <option value="custom">Custom Range</option>
                            <option value="all_time">All Time</option>
                            <option value="daily">Daily (Today)</option>
                            <option value="weekly">Weekly (This Week)</option>
                            <option value="monthly">Monthly (This Month)</option>
                            <option value="yearly">Yearly (This Year)</option>
                        </select>
                    </div>

                    {{-- Start Date --}}
                    <div class="qb-filter-col" style="min-width: 135px; flex: 0.8;">
                        <label class="qb-flabel"><i class="fas fa-calendar-day"></i> Start Date</label>
                        <input type="date" name="start_date" id="start_date_desk" class="qb-input startDateInput" value="2000-01-01">
                    </div>

                    {{-- End Date --}}
                    <div class="qb-filter-col" style="min-width: 135px; flex: 0.8;">
                        <label class="qb-flabel"><i class="fas fa-calendar-check"></i> End Date</label>
                        <input type="date" name="end_date" id="end_date_desk" class="qb-input endDateInput" value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-1 pb-1">
                        <button type="button" class="qb-btn-search btnSearchTrigger" title="Generate Ledger">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" class="qb-btn-reset btnResetTrigger" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            {{-- Loader --}}
            <div id="loader" style="display:none; text-align:center; padding: 25px; background:#ffffff;" class="qb-table-card mb-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="ms-2 text-dark font-monospace fw-bold" style="font-size:12.5px;">Fetching Customer Ledger Data…</span>
            </div>

            {{-- 4. Table Card (Desktop) --}}
            <div id="ledgerBox" style="display:none;">
                <div class="qb-table-card">
                    {{-- Toolbar / Meta Header --}}
                    <div class="qb-table-toolbar">
                        <div class="qb-meta-left" id="ledgerHeader">
                            <div class="qb-meta-name">
                                <i class="fas fa-user-circle text-primary"></i>
                                <span id="lblCustomerName">All Customers</span>
                            </div>
                            <span class="qb-meta-badge">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <span id="lblPeriodRange">01-Jan-2000 to 29-Oct-2026</span>
                            </span>
                            <span class="qb-meta-badge" id="lblTxnCount">
                                <i class="fas fa-list-ol text-muted"></i>
                                <span>0 Entries</span>
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 no-print">
                            <div class="input-group input-group-sm" style="width: 220px;">
                                <span class="input-group-text bg-white border-end-0 py-0 text-muted" style="height:28px;">
                                    <i class="fas fa-search" style="font-size:11px;"></i>
                                </span>
                                <input type="text" id="tableQuickSearch" class="form-control form-control-sm border-start-0 ps-1" placeholder="Search table..." style="height:28px; font-size:11.5px;">
                            </div>
                        </div>
                    </div>

                    <div class="sale-table-wrap">
                        <table class="qb-table nowrap" style="width:100%;" id="mainLedgerTable">
                            <thead>
                                <tr>
                                    <th style="width: 110px;">Date</th>
                                    <th style="width: 140px;">Ref / Invoice</th>
                                    <th style="width: 190px;">Customer</th>
                                    <th>Description</th>
                                    <th class="text-end" style="width: 130px;">Debit (Dr)</th>
                                    <th class="text-end" style="width: 130px;">Credit (Cr)</th>
                                    <th class="text-end" style="width: 145px;">Balance</th>
                                </tr>
                            </thead>
                            <tbody id="ledgerBody"></tbody>
                            <tfoot id="ledgerFooter"></tfoot>
                        </table>
                    </div>
                </div>

                {{-- Mobile Cards Container (d-md-none) --}}
                <div class="d-md-none no-print mt-2" id="ledgerMobileContainer"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($('.select2').length > 0) {
                $('.select2').select2({ width: '100%' });
            }

            let allCustomers = $('.customerSelect option').clone();
            let currentTransactions = [];
            let currentOpeningBalance = 0;
            let currentCustomerName = 'All Customers';
            let currentStart = '2000-01-01';
            let currentEnd = '{{ date("Y-m-d") }}';

            // Sync Inputs
            $('.startDateInput').on('change', function() { $('.startDateInput').val($(this).val()); });
            $('.endDateInput').on('change', function() { $('.endDateInput').val($(this).val()); });

            $('.zoneSelect').on('change', function() {
                let selectedZone = $(this).val();
                $('.zoneSelect').val(selectedZone);

                $('.customerSelect').empty();
                allCustomers.each(function() {
                    let optionZone = $(this).attr('data-zone');
                    if ($(this).val() === 'all' || selectedZone === "" || optionZone == selectedZone) {
                        $('.customerSelect').append($(this).clone());
                    }
                });
                $('.customerSelect').val('all').trigger('change.select2');
            });

            $('.customerSelect').on('change', function() { $('.customerSelect').val($(this).val()); });

            $('.quickFilterSelect').on('change', function() {
                let val = $(this).val();
                $('.quickFilterSelect').val(val);
                let today = new Date();
                let start = new Date();
                let end = new Date();

                let formatDate = (d) => {
                    let m = '' + (d.getMonth() + 1), day = '' + d.getDate(), y = d.getFullYear();
                    if (m.length < 2) m = '0' + m;
                    if (day.length < 2) day = '0' + day;
                    return [y, m, day].join('-');
                };

                if (val === 'all_time') {
                    $('.startDateInput').val('2000-01-01');
                    $('.endDateInput').val(formatDate(end));
                    loadLedger();
                    return;
                } else if (val === 'daily') {
                    // today
                } else if (val === 'weekly') {
                    let day = today.getDay();
                    let diff = today.getDate() - day + (day === 0 ? -6 : 1);
                    start.setDate(diff);
                } else if (val === 'monthly') {
                    start.setDate(1);
                } else if (val === 'yearly') {
                    start.setMonth(0, 1);
                } else if (val === 'custom') {
                    return;
                }

                $('.startDateInput').val(formatDate(start));
                $('.endDateInput').val(formatDate(end));
                loadLedger();
            });

            // Initial load
            loadLedger();

            $(document).on('click', '.btnSearchTrigger', function() {
                loadLedger();
            });

            $('.btnResetTrigger').on('click', function() {
                $('.startDateInput').val('2000-01-01');
                $('.endDateInput').val('{{ date("Y-m-d") }}');
                $('.zoneSelect').val('').trigger('change');
                $('.customerSelect').val('all').trigger('change');
                $('.quickFilterSelect').val('custom');
                $('#tableQuickSearch').val('');
                loadLedger();
            });

            $('.btnPrintReport').on('click', () => window.print());

            // Live Table Quick Search Filter
            $('#tableQuickSearch').on('keyup', function() {
                let query = $(this).val().toLowerCase().trim();
                if (!query) {
                    renderLedgerRows(currentTransactions);
                    return;
                }

                let filtered = currentTransactions.filter(function(t) {
                    let ref = (t.invoice || t.ref_no || '').toLowerCase();
                    let cust = (t.customer_name || '').toLowerCase();
                    let desc = (t.description || '').toLowerCase();
                    let date = (t.date || '').toLowerCase();
                    let debit = (t.debit || '').toString();
                    let credit = (t.credit || '').toString();
                    return ref.includes(query) || cust.includes(query) || desc.includes(query) || date.includes(query) || debit.includes(query) || credit.includes(query);
                });

                renderLedgerRows(filtered, true);
            });

            // CSV Export
            $('#btnExportCsv').on('click', function() {
                if (!currentTransactions || currentTransactions.length === 0) {
                    alert('No transactions available to export.');
                    return;
                }

                let csvRows = [];
                csvRows.push(['KENT HARDWARE - CUSTOMER LEDGER STATEMENT']);
                csvRows.push(['Customer:', currentCustomerName]);
                csvRows.push(['Period:', `${formatDisplayDate(currentStart)} to ${formatDisplayDate(currentEnd)}`]);
                csvRows.push(['Exported On:', new Date().toLocaleString()]);
                csvRows.push([]);
                csvRows.push(['Date', 'Ref / Invoice', 'Customer', 'Description', 'Debit (Dr)', 'Credit (Cr)', 'Balance']);

                // Opening balance
                csvRows.push(['-', '-', currentCustomerName, 'Opening Balance (B/F)', '0.00', '0.00', currentOpeningBalance.toFixed(2)]);

                let running = currentOpeningBalance;
                let totDr = 0;
                let totCr = 0;

                currentTransactions.forEach(function(t) {
                    let dr = parseFloat(t.debit) || 0;
                    let cr = parseFloat(t.credit) || 0;
                    totDr += dr;
                    totCr += cr;
                    running = parseFloat(t.balance) || (running + dr - cr);

                    csvRows.push([
                        t.date || '',
                        t.invoice || t.ref_no || '-',
                        t.customer_name || currentCustomerName,
                        (t.description || '').replace(/"/g, '""'),
                        dr.toFixed(2),
                        cr.toFixed(2),
                        running.toFixed(2)
                    ]);
                });

                csvRows.push(['TOTALS', '', '', '', totDr.toFixed(2), totCr.toFixed(2), running.toFixed(2)]);

                let csvContent = '\uFEFF' + csvRows.map(e => e.map(item => `"${item}"`).join(',')).join('\n');
                let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                let url = URL.createObjectURL(blob);
                let link = document.createElement('a');
                link.setAttribute('href', url);
                let cleanName = currentCustomerName.replace(/[^a-zA-Z0-9]/g, '_');
                link.setAttribute('download', `Customer_Ledger_${cleanName}_${currentEnd}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            function loadLedger() {
                let zid = $(".zoneSelect").val();
                let cid = $(".customerSelect").val();
                let start = $(".startDateInput").val() || '2000-01-01';
                let end = $(".endDateInput").val() || '{{ date("Y-m-d") }}';

                currentStart = start;
                currentEnd = end;

                $("#loader").show();
                $("#ledgerBox").hide();

                $.get("{{ route('report.customer.ledger.fetch') }}", {
                    zone_id: zid,
                    customer_id: cid || 'all',
                    start_date: start,
                    end_date: end
                }, function(res) {
                    $("#loader").hide();
                    $("#ledgerBox").show();

                    let displayStart = formatDisplayDate(start);
                    let displayEnd = formatDisplayDate(end);

                    currentCustomerName = (res.customer && res.customer.customer_name) ? res.customer.customer_name : 'All Customers';
                    currentOpeningBalance = parseFloat(res.opening_balance) || 0;
                    currentTransactions = res.transactions || [];

                    // Update Top Meta Bar & Print Header
                    $('#lblCustomerName, #printCustomerName').text(currentCustomerName);
                    $('#lblPeriodRange').text(`${displayStart} to ${displayEnd}`);
                    $('#printPeriod').text(`Period: ${displayStart} to ${displayEnd} | Printed: {{ date('d-M-Y H:i') }}`);
                    $('#lblTxnCount').html(`<i class="fas fa-list-ol text-muted"></i> <span>${currentTransactions.length} Entries</span>`);

                    // Render table and KPIs
                    renderLedgerRows(currentTransactions);
                }).fail(function() {
                    $("#loader").hide();
                    alert("Failed to load customer ledger. Please try again.");
                });
            }

            function renderLedgerRows(transactions, isFilter) {
                let totalDebit = 0;
                let totalCredit = 0;
                let lastBalance = currentOpeningBalance;

                let formattedOpening = 'Rs ' + currentOpeningBalance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                $('#pillOpeningBalance').text(formattedOpening);

                let html = '';
                let mobHtml = '';

                // Only show Opening Balance row if not filtering via search box
                if (!isFilter) {
                    html += `
                        <tr class="row-opening">
                            <td class="text-center font-monospace text-muted">—</td>
                            <td class="text-center"><span class="acc-badge-code" style="background:#e0f2fe; color:#0369a1; border-color:#bae6fd;">OP-BAL</span></td>
                            <td class="fw-bold text-dark">${currentCustomerName}</td>
                            <td class="fw-bold text-dark">
                                <i class="fas fa-history text-muted me-1"></i> Opening Balance (B/F)
                            </td>
                            <td class="text-end font-monospace text-muted">—</td>
                            <td class="text-end font-monospace text-muted">—</td>
                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:13px;">
                                Rs ${currentOpeningBalance.toLocaleString(undefined, {minimumFractionDigits: 2})}
                                ${currentOpeningBalance > 0 ? '<span class="acc-bal-badge dr">Dr</span>' : (currentOpeningBalance < 0 ? '<span class="acc-bal-badge cr">Cr</span>' : '')}
                            </td>
                        </tr>
                    `;

                    mobHtml += `
                        <div class="card p-2 mb-2 bg-light border font-monospace">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark">Opening Balance (B/F)</strong>
                                <strong class="text-dark">Rs ${currentOpeningBalance.toLocaleString(undefined, {minimumFractionDigits: 2})}</strong>
                            </div>
                        </div>
                    `;
                }

                if (transactions.length === 0) {
                    html += `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-receipt fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                <span class="fw-bold">No ledger transactions found for this period.</span>
                            </td>
                        </tr>
                    `;
                } else {
                    transactions.forEach((t) => {
                        let debit = t.debit && t.debit > 0 ? parseFloat(t.debit) : 0;
                        let credit = t.credit && t.credit > 0 ? parseFloat(t.credit) : 0;
                        totalDebit += debit;
                        totalCredit += credit;

                        let currentBal = parseFloat(t.balance);
                        lastBalance = currentBal;

                        let refCode = t.invoice || t.ref_no || '—';
                        let custName = t.customer_name || currentCustomerName;

                        // Highlight payment accounts if present
                        let descFormatted = t.description || '';
                        if (descFormatted.indexOf('[A/C:') !== -1) {
                            descFormatted = descFormatted.replace(/\[A\/C:\s*([^\]]+)\]/g, '<span class="acc-ac-badge"><i class="fas fa-university text-muted me-1"></i>$1</span>');
                        }

                        let balBadge = currentBal > 0 ? '<span class="acc-bal-badge dr">Dr</span>' : (currentBal < 0 ? '<span class="acc-bal-badge cr">Cr</span>' : '');

                        html += `
                            <tr>
                                <td class="font-monospace text-nowrap" style="font-size:12px;">${formatDisplayDate(t.date)}</td>
                                <td><span class="acc-badge-code">${refCode}</span></td>
                                <td class="fw-semibold text-dark text-nowrap" style="font-size:12px;">${custName}</td>
                                <td class="text-dark" style="font-size:12px;">${descFormatted}</td>
                                <td class="text-end font-monospace text-danger fw-bold" style="font-size:12.5px;">
                                    ${debit > 0 ? 'Rs ' + debit.toLocaleString(undefined, {minimumFractionDigits: 2}) : '—'}
                                </td>
                                <td class="text-end font-monospace text-success fw-bold" style="font-size:12.5px;">
                                    ${credit > 0 ? 'Rs ' + credit.toLocaleString(undefined, {minimumFractionDigits: 2}) : '—'}
                                </td>
                                <td class="text-end font-monospace fw-bold ${currentBal >= 0 ? 'text-dark' : 'text-danger'}" style="font-size:12.5px;">
                                    Rs ${currentBal.toLocaleString(undefined, {minimumFractionDigits: 2})}
                                    ${balBadge}
                                </td>
                            </tr>
                        `;

                        mobHtml += `
                            <div class="card p-2 mb-2 border shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="acc-badge-code">${refCode}</span>
                                    <span class="text-muted font-monospace" style="font-size: 11px;">${formatDisplayDate(t.date)}</span>
                                </div>
                                <div class="mb-1" style="font-size: 12px;">
                                    <strong class="text-dark">${custName}</strong>
                                    <div class="text-secondary small">${t.description}</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-top pt-1 font-monospace" style="font-size: 11px;">
                                    <span class="text-danger fw-bold">Dr: ${debit > 0 ? 'Rs ' + debit.toLocaleString() : '0'}</span>
                                    <span class="text-success fw-bold">Cr: ${credit > 0 ? 'Rs ' + credit.toLocaleString() : '0'}</span>
                                    <span class="fw-bold ${currentBal >= 0 ? 'text-dark' : 'text-danger'}">Bal: Rs ${currentBal.toLocaleString()} ${balBadge}</span>
                                </div>
                            </div>
                        `;
                    });
                }

                // Table Footer
                let closingBalBadge = lastBalance > 0 ? '<span class="acc-bal-badge dr">Dr</span>' : (lastBalance < 0 ? '<span class="acc-bal-badge cr">Cr</span>' : '');

                let footerHtml = `
                    <tr>
                        <td colspan="4" class="text-end text-white fw-bold" style="letter-spacing:0.5px;">GRAND TOTALS:</td>
                        <td class="text-end font-monospace text-danger" style="font-size:13px; color:#fca5a5 !important;">Rs ${totalDebit.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-end font-monospace text-success" style="font-size:13px; color:#86efac !important;">Rs ${totalCredit.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-end font-monospace text-primary" style="font-size:13.5px; color:#93c5fd !important;">
                            Rs ${lastBalance.toLocaleString(undefined, {minimumFractionDigits: 2})}
                            ${closingBalBadge}
                        </td>
                    </tr>
                `;

                $("#ledgerBody").html(html);
                $("#ledgerFooter").html(footerHtml);
                $("#ledgerMobileContainer").html(mobHtml);

                if (!isFilter) {
                    let formattedDebit = 'Rs ' + totalDebit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    let formattedCredit = 'Rs ' + totalCredit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    let formattedClosing = 'Rs ' + lastBalance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

                    $('#pillTotalDebit').text(formattedDebit);
                    $('#pillTotalCredit').text(formattedCredit);
                    $('#pillClosingBalance').text(formattedClosing);

                    if (lastBalance > 0) {
                        $('#pillBalanceSub').html('<span class="text-danger fw-bold">Receivable (Dr)</span>');
                    } else if (lastBalance < 0) {
                        $('#pillBalanceSub').html('<span class="text-success fw-bold">Advance / Payable (Cr)</span>');
                    } else {
                        $('#pillBalanceSub').html('<span class="text-muted fw-bold">Settled (Nil)</span>');
                    }
                }
            }

            function formatDisplayDate(dateStr) {
                if (!dateStr) return '-';
                // If format YYYY-MM-DD
                let parts = dateStr.split('-');
                if (parts.length === 3 && parts[0].length === 4) {
                    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    let mIdx = parseInt(parts[1], 10) - 1;
                    return parts[2] + '-' + (months[mIdx] || parts[1]) + '-' + parts[0];
                }
                return dateStr;
            }
        });
    </script>
@endsection
