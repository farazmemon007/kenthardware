@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "PROFIT & LOSS ANALYSIS" THEME
       Pixel-Perfect High-Density Layout Matching Sales & Accounts
       With Normal QuickBooks Fonts & Alternating Zebra Strips
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

    /* Universal Space Reset */
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

    .font-monospace, .qb-mono {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
    }

    /* 1. Top Window Title Bar */
    .qb-win-header {
        height: 38px !important;
        min-height: 38px !important;
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
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
    }
    .qb-win-title i {
        color: #38bdf8;
        font-size: 15px;
    }
    .qb-win-sub {
        font-size: 11px;
        font-weight: 600;
        color: #93c5fd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .qb-btn-blue {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        padding: 3px 12px !important;
        height: 27px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        cursor: pointer;
        transition: filter 0.1s ease;
        white-space: nowrap;
    }
    .qb-btn-blue:hover { filter: brightness(1.1); color: #ffffff !important; }

    .qb-btn-gray {
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
        border: 1px solid #94a3b8 !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        padding: 3px 12px !important;
        height: 27px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        cursor: pointer;
        transition: filter 0.1s ease;
        white-space: nowrap;
    }
    .qb-btn-gray:hover { background: #cbd5e1 !important; color: #0f172a !important; }

    /* 2. Sub-Header Ribbon: Financial Totals */
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
        gap: 8px;
        flex-wrap: wrap;
    }
    .qb-kpi-chip {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1.25;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
    }
    .qb-kpi-chip .chip-label {
        font-weight: 800;
        color: #334155;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .qb-kpi-chip .chip-val {
        font-weight: 800;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 13px;
    }

    /* 3. Filter Bar */
    .qb-filter-bar {
        background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
        border: 1px solid #b9cce2;
        border-radius: 4px;
        padding: 6px 10px;
        margin-bottom: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    .qb-flabel {
        font-size: 10.5px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #1e3a5f !important;
        margin-bottom: 2px !important;
        white-space: nowrap;
    }
    .qb-input, select.qb-input {
        height: 30px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding: 2px 8px !important;
        background-color: #ffffff;
        outline: none;
    }
    .qb-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
    }

    .select2-container .select2-selection--single {
        height: 30px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding-left: 6px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 28px !important;
    }

    .qb-btn-search {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        height: 30px !important;
        padding: 0 13px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        cursor: pointer;
    }
    .qb-btn-search:hover { filter: brightness(1.1); color: #ffffff !important; }

    .qb-btn-reset {
        background: linear-gradient(180deg, #ffffff 0%, #e8eff6 100%) !important;
        border: 1px solid #8ca4c3 !important;
        color: #334155 !important;
        font-weight: 600 !important;
        border-radius: 3px !important;
        height: 30px !important;
        padding: 0 11px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        cursor: pointer;
    }
    .qb-btn-reset:hover { background: #e2e8f0 !important; color: #0f172a !important; }

    /* 4. Table Card & High-Density QuickBooks Table */
    .qb-table-card {
        background: #ffffff;
        border: 1px solid #94a3b8;
        border-radius: 4px;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .sale-table-wrap {
        border: none !important;
        overflow-x: auto;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 13px !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }
    .qb-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 11.5px !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-top: none !important;
        padding: 7px 10px !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }
    .qb-table tbody td {
        border-bottom: 1px solid #c8d7e6 !important;
        border-right: 1px solid #cbd8e7 !important;
        border-top: none !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        color: #0f2744 !important;
        vertical-align: middle;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* QuickBooks Distinct Alternating Darker Stripes */
    .qb-table tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    .qb-table tbody tr:nth-child(even) td {
        background-color: #e2ecf7 !important; /* Distinct Darker Cool Blue Stripe */
    }
    .qb-table tbody tr:hover td {
        background-color: #bedaff !important; /* Deep Sky-Blue Hover Highlight */
        color: #0f172a !important;
    }
    .qb-table tbody tr:hover td:first-child {
        box-shadow: inset 5px 0 0 #1d4ed8 !important;
    }
    .qb-table tbody tr:hover td .acc-badge-code {
        background-color: #d5e2f1 !important;
        border-color: #7b9bbd !important;
    }

    .acc-badge-code {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #0f2744 !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 3px !important;
        padding: 2px 7px !important;
        display: inline-block;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    /* DataTables in QuickBooks style */
    .dataTables_wrapper .dataTables_filter {
        padding: 6px 10px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 28px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        font-size: 12px !important;
        padding: 2px 8px !important;
    }
    .dataTables_wrapper .dataTables_length {
        padding: 6px 10px !important;
        font-size: 12px !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 6px 10px !important;
        font-size: 12px !important;
    }

    /* Mobile Cards */
    .mob-card {
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .mob-metric-card {
        border-radius: 6px;
        border: 1px solid #b2c5dc;
        background: #ffffff;
        padding: 6px 8px;
        text-align: center;
        height: 100%;
    }
    .mob-metric-label {
        font-size: 10px;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
    }
    .mob-metric-val {
        font-size: 13px;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
    }

    @media print {
        body { background: #ffffff !important; font-size: 11px; }
        .no-print, header, .sidebar, .navbar, footer, .qb-win-header, .qb-filter-bar { display: none !important; }
        .odoo-page-wrapper { padding: 0 !important; background: #fff !important; }
        .qb-table-card { border: none !important; box-shadow: none !important; }
        .sale-table-wrap { max-height: none !important; overflow: visible !important; }
        .qb-table thead th { background: #e2eaf3 !important; color: #000 !important; }
        .qb-table td, .qb-table th { border: 1px solid #94a3b8 !important; }
    }
</style>

<div class="odoo-page-wrapper">

    {{-- 1. Top Window Title Bar --}}
    <div class="qb-win-header no-print">
        <div class="qb-win-title">
            <i class="fas fa-chart-pie"></i>
            <span>Profit & Loss Analysis</span>
            <span class="qb-win-sub d-none d-sm-inline">| Income Statement & Product Margins</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Financial KPIs (Desktop) --}}
    <div class="qb-sub-ribbon no-print d-none d-md-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-success"><i class="fas fa-arrow-up me-1"></i>Gross Profit:</span>
                <span class="chip-val text-success" id="cardGrossProfit">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fas fa-arrow-down me-1"></i>Total Expenses:</span>
                <span class="chip-val text-danger" id="cardExpenses">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-primary"><i class="fas fa-balance-scale me-1"></i>Net Profit / Loss:</span>
                <span class="chip-val text-primary" id="cardNetProfit">Rs 0.00</span>
            </div>
        </div>
        <div class="text-muted small fw-bold">
            <i class="fa-solid fa-calculator me-1 text-primary"></i> Period Profitability
        </div>
    </div>

    {{-- Mobile Summary Cards --}}
    <div class="row g-2 mb-1 d-md-none no-print px-1">
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-success">Gross Profit</span>
                <div class="mob-metric-val text-success mt-1" id="mobCardGrossProfit">Rs 0</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-danger">Expenses</span>
                <div class="mob-metric-val text-danger mt-1" id="mobCardExpenses">Rs 0</div>
            </div>
        </div>
        <div class="col-12 mt-1">
            <div class="mob-metric-card" style="background: #f0f9ff; border-color: #bae6fd;">
                <span class="mob-metric-label text-primary">Net Profit / Loss</span>
                <div class="mob-metric-val text-primary mt-1" id="mobCardNetProfit">Rs 0</div>
            </div>
        </div>
    </div>

    {{-- 3. Filter Bar (Desktop) --}}
    <div class="qb-filter-bar no-print d-none d-md-block">
        <form id="profitLossFormDesk">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">From:</span>
                        <input type="datetime-local" id="start_date_desk" class="qb-input fw-bold startDateInput" value="{{ date('Y-m-d\T00:00') }}" style="width: 180px;">
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">To:</span>
                        <input type="datetime-local" id="end_date_desk" class="qb-input fw-bold endDateInput" value="{{ date('Y-m-d\T23:59') }}" style="width: 180px;">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-search btnSearchTrigger">
                        <i class="fa-solid fa-filter"></i> Search
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-4">
                    <span class="qb-flabel">Category:</span>
                    <select id="category_id_desk" class="form-select form-select-sm select2 categorySelect">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <span class="qb-flabel">Customer:</span>
                    <select id="customer_id_desk" class="form-select form-select-sm select2 customerSelect">
                        <option value="all">All Customers</option>
                        @foreach($customers as $cus)
                            <option value="{{ $cus->id }}">{{ $cus->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <span class="qb-flabel">Product:</span>
                    <select id="product_id_desk" class="form-select form-select-sm select2 productSelect">
                        <option value="all">All Products</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->item_code }} - {{ $prod->item_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Filter Bar (Mobile) --}}
    <div class="card border-0 shadow-sm mb-2 no-print d-md-none p-2" style="border-radius: 6px; background: #e8f0f8;">
        <form id="profitLossFormMob">
            <div class="row g-2">
                <div class="col-6">
                    <span class="qb-flabel">From</span>
                    <input type="datetime-local" id="start_date_mob" class="form-control form-control-sm startDateInput" value="{{ date('Y-m-d\T00:00') }}" style="font-size: 11px;">
                </div>
                <div class="col-6">
                    <span class="qb-flabel">To</span>
                    <input type="datetime-local" id="end_date_mob" class="form-control form-control-sm endDateInput" value="{{ date('Y-m-d\T23:59') }}" style="font-size: 11px;">
                </div>
                <div class="col-12">
                    <span class="qb-flabel">Category</span>
                    <select class="form-select form-select-sm select2 categorySelect">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <span class="qb-flabel">Customer</span>
                    <select class="form-select form-select-sm select2 customerSelect">
                        <option value="all">All Customers</option>
                        @foreach($customers as $cus)
                            <option value="{{ $cus->id }}">{{ $cus->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <span class="qb-flabel">Product</span>
                    <select class="form-select form-select-sm select2 productSelect">
                        <option value="all">All Products</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->item_code }} - {{ $prod->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-1 mt-1">
                    <button type="button" class="qb-btn-search flex-fill justify-content-center btnSearchTrigger">
                        <i class="fa-solid fa-filter me-1"></i> Search
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Loader --}}
    <div id="loader" style="display:none; text-align:center; padding: 25px;">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="small fw-bold text-muted mt-2">Calculating Profit & Loss Data…</div>
    </div>

    {{-- 4. Content Layout --}}
    <div class="row g-2 mb-2">
        {{-- Item Profitability Table --}}
        <div class="col-lg-8">
            <div class="qb-table-card">
                <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-table me-2 text-primary"></i>Item Profitability
                    </span>
                </div>
                
                {{-- DESKTOP TABLE VIEW (d-none d-md-block) --}}
                <div class="d-none d-md-block">
                    <div class="sale-table-wrap" style="max-height: 480px; min-height: 350px;">
                        <table id="profitTable" class="qb-table table-bordered align-middle" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">CODE</th>
                                    <th>PRODUCT</th>
                                    <th class="text-center" style="width: 80px;">QTY</th>
                                    <th class="text-center" style="width: 80px;">RET</th>
                                    <th class="text-end" style="width: 120px;">REVENUE</th>
                                    <th class="text-end" style="width: 120px;">COGS</th>
                                    <th class="text-end" style="width: 130px;">PROFIT</th>
                                </tr>
                            </thead>
                            <tbody id="profitBody"></tbody>
                        </table>
                    </div>
                </div>

                {{-- MOBILE CARDS CONTAINER (d-md-none) --}}
                <div class="d-md-none p-2" id="mobProfitContainer"></div>
            </div>
        </div>

        {{-- Top 5 Profit Share Chart --}}
        <div class="col-lg-4">
            <div class="qb-table-card h-100">
                <div class="bg-light py-2 px-3 border-bottom">
                    <span class="fw-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-chart-pie me-2 text-purple" style="color: #8b5cf6;"></i>Top 5 Profit Share
                    </span>
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center p-3" style="min-height: 350px;">
                    <div id="noChartData" class="text-muted small py-4 fw-semibold">Fetch data to see chart</div>
                    <canvas id="profitChart" style="max-height: 280px; width:100%; display:none;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 10 Customers --}}
    <div class="qb-table-card mb-3">
        <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-trophy me-2 text-warning"></i>Top 10 Customers by Profit
            </span>
            <span class="badge bg-secondary text-white small" id="customerCount">0 customers</span>
        </div>
        
        {{-- DESKTOP TABLE VIEW (d-none d-md-block) --}}
        <div class="d-none d-md-block">
            <div class="sale-table-wrap" style="max-height: 350px;">
                <table class="qb-table table-bordered align-middle" id="topCustomersTableContent">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">#</th>
                            <th>CUSTOMER NAME</th>
                            <th class="text-end" style="width: 160px;">REVENUE</th>
                            <th class="text-end" style="width: 160px;">LEDGER BALANCE</th>
                            <th class="text-end" style="width: 160px;">NET PROFIT</th>
                        </tr>
                    </thead>
                    <tbody id="topCustomersBody">
                        <tr><td colspan="5" class="text-center text-muted py-4 small">Fetch data to see top customers</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE CARDS CONTAINER (d-md-none) --}}
        <div class="d-md-none p-2" id="mobTopCustomersContainer"></div>
    </div>

</div>
@endsection

@section('js')
<script src="{{ asset('assets/vendors/chartjs/chart.umd.min.js') }}"></script>
<script>
$(document).ready(function() {
    if ($('.select2').length > 0) {
        $('.select2').select2({ width: '100%' });
    }

    // Sync Inputs between Desktop & Mobile
    $('.startDateInput').on('change', function() { $('.startDateInput').val($(this).val()); });
    $('.endDateInput').on('change', function() { $('.endDateInput').val($(this).val()); });
    $('.categorySelect').on('change', function() { $('.categorySelect').val($(this).val()); });
    $('.customerSelect').on('change', function() { $('.customerSelect').val($(this).val()); });
    $('.productSelect').on('change', function() { $('.productSelect').val($(this).val()); });

    var profitTable = $('#profitTable').DataTable({
        paging: true,
        pageLength: 15,
        searching: true,
        ordering: true,
        order: [[6, 'desc']],
        language: { search: '', searchPlaceholder: 'Search items...' },
        columns: [
            { data: 'item_code' },
            { data: 'item_name' },
            { data: 'sold_qty', className: 'text-center' },
            { data: 'returned_qty', className: 'text-center' },
            { data: 'revenue', className: 'text-end' },
            { data: 'cogs', className: 'text-end' },
            { data: 'profit', className: 'text-end' }
        ]
    });

    var myChart = null;

    function fmt(n) {
        return Math.round(parseFloat(n)).toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0});
    }

    $('.btnSearchTrigger').click(fetchReport);

    $('.btnResetTrigger').on('click', function() {
        $('.startDateInput').val('{{ date("Y-m-d\T00:00") }}');
        $('.endDateInput').val('{{ date("Y-m-d\T23:59") }}');
        $('.categorySelect, .customerSelect, .productSelect').val('all').trigger('change');
        fetchReport();
    });

    $('.btnPrintReport').on('click', () => window.print());

    function fetchReport() {
        $('#loader').show();

        let startDate = $('.startDateInput').val();
        let endDate   = $('.endDateInput').val();
        let catId     = $('.categorySelect').val();
        let custId    = $('.customerSelect').val();
        let prodId    = $('.productSelect').val();

        $.ajax({
            url: "{{ route('report.profit_loss.fetch') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                start_date: startDate,
                end_date: endDate,
                product_id: prodId,
                category_id: catId,
                customer_id: custId
            },
            success: function(response) {
                $('#loader').hide();
                renderReport(response);
            },
            error: function() {
                $('#loader').hide();
                alert('Could not fetch report data');
            }
        });
    }

    function renderReport(data) {
        // Table
        profitTable.clear();
        let chartLabels = [];
        let chartData = [];
        let mobHtml = '';

        data.products.forEach(function(r, idx) {
            let profitColor = parseFloat(r.profit) >= 0 ? '#15803d' : '#b91c1c';
            
            let itemNameHtml = `
                <div>
                    <div class="fw-bold text-dark" style="font-size:13px;">${r.item_name}</div>
                    ${r.unit_badge ? `<span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; font-size:10px; font-weight:600;"><i class="fas fa-tag me-1 text-primary"></i>${r.unit_badge}</span>` : ''}
                </div>
            `;

            profitTable.row.add({
                item_code: '<span class="acc-badge-code">' + r.item_code + '</span>',
                item_name: itemNameHtml,
                sold_qty: '<span class="fw-bold text-dark qb-mono">' + r.sold_qty + '</span>',
                returned_qty: '<span class="text-danger fw-bold qb-mono">' + (r.returned_qty || 0) + '</span>',
                revenue: '<span class="qb-mono fw-semibold">Rs ' + fmt(r.revenue) + '</span>',
                cogs: '<span class="qb-mono text-muted fw-semibold">Rs ' + fmt(r.cogs) + '</span>',
                profit: '<span class="qb-mono" style="color:' + profitColor + '; font-weight:800;">Rs ' + fmt(r.profit) + '</span>'
            });

            if (chartLabels.length < 5 && parseFloat(r.profit) > 0) {
                chartLabels.push(r.item_name.length > 18 ? r.item_name.substring(0, 18) + '…' : r.item_name);
                chartData.push(r.profit);
            }

            // Mobile Card
            mobHtml += `
                <div class="mob-card p-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-muted border" style="font-size: 10px;">#${idx+1}</span>
                            <span class="acc-badge-code" style="font-size: 11px;">${r.item_code}</span>
                            ${r.unit_badge ? `<span class="badge bg-light text-secondary border px-1" style="font-size: 9.5px;">${r.unit_badge}</span>` : ''}
                        </div>
                        <strong class="qb-mono" style="color:${profitColor}; font-size:12.5px;">Profit: Rs ${fmt(r.profit)}</strong>
                    </div>
                    <div class="fw-bold text-dark mb-1" style="font-size:13px;">${r.item_name}</div>
                    <div class="border-top pt-2 mt-1">
                        <div class="row g-1 text-center" style="font-size: 11px;">
                            <div class="col-3 border-end"><span class="text-muted d-block" style="font-size:10px;">Qty</span><strong class="qb-mono">${r.sold_qty}</strong></div>
                            <div class="col-3 border-end"><span class="text-muted d-block" style="font-size:10px;">Ret</span><strong class="text-danger qb-mono">${r.returned_qty||0}</strong></div>
                            <div class="col-3 border-end"><span class="text-muted d-block" style="font-size:10px;">Revenue</span><strong class="qb-mono">Rs ${fmt(r.revenue)}</strong></div>
                            <div class="col-3"><span class="text-muted d-block" style="font-size:10px;">COGS</span><strong class="qb-mono">Rs ${fmt(r.cogs)}</strong></div>
                        </div>
                    </div>
                </div>`;
        });
        profitTable.draw();
        $('#mobProfitContainer').html(mobHtml || '<div class="text-center text-muted py-3 small">No item profitability data</div>');

        // Cards (Desktop & Mobile)
        let formattedGross = 'Rs ' + fmt(data.total_gross_profit);
        let formattedExp   = 'Rs ' + fmt(data.total_expenses);
        let formattedNet   = 'Rs ' + fmt(data.net_profit);

        $('#cardGrossProfit, #mobCardGrossProfit').text(formattedGross);
        $('#cardExpenses, #mobCardExpenses').text(formattedExp);
        $('#cardNetProfit, #mobCardNetProfit').text(formattedNet);

        // Chart
        updateChart(chartLabels, chartData);

        // Top 10 Customers
        renderTopCustomers(data.top_customers || []);
    }

    function renderTopCustomers(customers) {
        var html = '';
        var mobCustHtml = '';
        $('#customerCount').text(customers.length + ' customers');

        if (customers.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted py-4 small">No customer data for this period</td></tr>';
            $('#topCustomersBody').html(html);
            $('#mobTopCustomersContainer').html('<div class="text-center text-muted py-3 small">No customer data</div>');
            return;
        }

        customers.forEach(function(c, idx) {
            var profitColor = c.profit >= 0 ? '#15803d' : '#b91c1c';
            var profitSign = c.profit < 0 ? '-' : '';

            html += '<tr>' +
                '<td class="text-center fw-bold text-muted" style="font-size:12px;">' + (idx + 1) + '</td>' +
                '<td>' +
                    '<div class="fw-bold text-dark" style="font-size:13px;">' + c.name + '</div>' +
                    '<div class="text-muted small" style="font-size:10.5px;">ID: CUST-' + c.id.toString().padStart(4, '0') + '</div>' +
                '</td>' +
                '<td class="text-end fw-semibold qb-mono" style="font-size:13px;">Rs ' + fmt(c.revenue) + '</td>' +
                '<td class="text-end text-danger fw-semibold qb-mono" style="font-size:13px;">Rs ' + fmt(c.balance) + '</td>' +
                '<td class="text-end">' +
                    '<span class="badge p-1.5 px-2 qb-mono" style="background: ' + (c.profit >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)') + '; color: ' + profitColor + '; font-size:12.5px; font-weight:800;">' + 
                        profitSign + 'Rs ' + fmt(Math.abs(c.profit)) + 
                    '</span>' +
                '</td>' +
            '</tr>';

            mobCustHtml += `
                <div class="mob-card p-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-light text-muted border" style="font-size:10px;">#${idx+1}</span>
                            <strong class="text-dark" style="font-size:13px;">${c.name}</strong>
                        </div>
                        <span class="badge p-1 px-2 qb-mono" style="background:${c.profit >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'}; color:${profitColor}; font-size:11.5px; font-weight:700;">
                            ${profitSign}Rs ${fmt(Math.abs(c.profit))}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between text-muted" style="font-size:11px;">
                        <span>Revenue: <strong class="qb-mono text-dark">Rs ${fmt(c.revenue)}</strong></span>
                        <span class="text-danger">Balance: <strong class="qb-mono text-danger">Rs ${fmt(c.balance)}</strong></span>
                    </div>
                </div>`;
        });

        $('#topCustomersBody').html(html);
        $('#mobTopCustomersContainer').html(mobCustHtml);
    }

    function updateChart(labels, data) {
        if (myChart) myChart.destroy();

        if (labels.length === 0) {
            $('#profitChart').hide();
            $('#noChartData').show().text('No profit data available');
            return;
        }

        $('#noChartData').hide();
        $('#profitChart').show();

        var ctx = document.getElementById('profitChart');
        myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#059669', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                cutout: '55%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 10 }, padding: 12 }
                    }
                }
            }
        });
    }

    // Auto load report
    fetchReport();
});
</script>
@endsection
