@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "ITEM STOCK & LEDGER REPORT" THEME
       Pixel-Perfect High-Density Layout Matching Sales & Accounts
       With Normal QuickBooks Fonts & Alternating Zebra Strips
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap');

    /* Universal Space Reset to Eliminate Scrolling & Extra Spaces */
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
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .odoo-page-wrapper {
        padding: 0 4px !important;
        max-width: 100% !important;
    }

    .font-monospace, .qb-mono, .tabular-nums {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-variant-numeric: tabular-nums !important;
        font-feature-settings: "tnum" !important;
    }

    /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS Replica) */
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

    /* Odoo/QuickBooks Mode Switcher Segmented Pills */
    .qb-mode-group {
        display: inline-flex;
        align-items: center;
        background: rgba(0, 0, 0, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 4px;
        padding: 2px;
        gap: 2px;
    }
    .qb-mode-btn {
        border: none;
        background: transparent;
        color: #cbd5e1;
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 11px;
        height: 26px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.12s ease;
        white-space: nowrap;
        text-decoration: none !important;
    }
    .qb-mode-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.12);
    }
    .qb-mode-btn.active {
        background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%) !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.35);
    }

    /* QuickBooks Glossy Action Buttons */
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
    .qb-btn-gray:hover {
        background: #cbd5e1 !important;
        color: #0f172a !important;
    }

    /* 2. Sub-Header Ribbon: Financial & Inventory KPIs */
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
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-variant-numeric: tabular-nums;
        font-size: 13px;
    }

    /* 3. Filter Bar (Single-Line Compact Strip) */
    .qb-filter-bar {
        background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
        border: 1px solid #b9cce2;
        border-radius: 4px;
        padding: 5px 8px;
        margin-bottom: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    .qb-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
    }
    .qb-filter-col {
        display: flex;
        flex-direction: column;
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

    /* Select2 High-Density ERP Styling */
    .qb-filter-col .select2-container .select2-selection--single {
        height: 30px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        padding: 0 4px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding-left: 4px !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 28px !important;
        right: 4px !important;
    }
    .qb-filter-col .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #f1f5f9 !important;
        opacity: 0.75 !important;
        cursor: not-allowed;
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
        height: 30px !important;
        padding: 0 11px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
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
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .table-responsive {
        border: none !important;
        overflow-x: auto;
        min-height: 0 !important;
        width: 100% !important;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 12px !important;
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto;
    }

    /* Table Headers (Soft Blue-Gray Gradient with Sharp Dividers) */
    .qb-table thead th {
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #0f2744 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 10.5px !important;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-top: none !important;
        padding: 5px 6px !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }

    /* Table Body Cells */
    .qb-table tbody td {
        border-bottom: 1px solid #c8d7e6 !important;
        border-right: 1px solid #cbd8e7 !important;
        border-top: none !important;
        padding: 4px 6px !important;
        font-size: 12px !important;
        color: #0f172a !important;
        vertical-align: middle;
        line-height: 1.25;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* ==========================================================
       AUTHENTIC QUICKBOOKS ALTERNATING STRIPES ("STRIP WALE")
       ========================================================== */
    .qb-table tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    .qb-table tbody tr:nth-child(even) td {
        background-color: #e2ecf7 !important; /* Distinct Darker QuickBooks Cool Blue Stripe */
    }

    /* Tinted Column Overrides for Movement Ledger */
    .qb-table tbody tr:nth-child(odd) td.qb-col-adjust {
        background-color: #fffbeb !important;
    }
    .qb-table tbody tr:nth-child(even) td.qb-col-adjust {
        background-color: #fee9b3 !important;
    }
    .qb-table tbody tr:nth-child(odd) td.qb-col-closing {
        background-color: #f0f4ff !important;
    }
    .qb-table tbody tr:nth-child(even) td.qb-col-closing {
        background-color: #dce7fc !important;
    }

    /* Row Hover: QuickBooks Signature Sky-Blue Highlight */
    .qb-table tbody tr {
        cursor: pointer;
        transition: background-color 0.1s ease-in-out;
    }
    .qb-table tbody tr:hover td {
        background-color: #bedaff !important; /* Prominent Active Selection Blue */
        color: #0f172a !important;
    }
    .qb-table tbody tr:hover td:first-child {
        box-shadow: inset 5px 0 0 #1d4ed8 !important; /* Royal blue marker strip */
    }
    .qb-table tbody tr:hover td .acc-badge-code {
        background-color: #d5e2f1 !important;
        border-color: #7b9bbd !important;
    }

    /* Table Footer Summary Row */
    .qb-table tfoot th,
    .qb-table tfoot td {
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
        color: #0f2744 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        border-top: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        padding: 6px 8px !important;
        vertical-align: middle;
    }
    .qb-table tfoot th:last-child,
    .qb-table tfoot td:last-child {
        border-right: none !important;
    }

    /* High-Contrast Badges & Flags (Compact High-Density Styling) */
    .acc-badge-code {
        font-family: 'SFMono-Regular', Consolas, 'Segoe UI Mono', monospace !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 3px !important;
        padding: 1px 5px !important;
        display: inline-block;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
    .qb-unit-badge {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        border-radius: 3px;
        padding: 1px 5px;
        font-size: 10.5px;
        font-weight: 600;
        white-space: nowrap;
        display: inline-block;
    }
    .qb-status-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 5px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }
    .qb-status-healthy {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .qb-status-out {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }
    .qb-status-low {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .acc-bal-flag {
        font-size: 10.5px !important;
        font-weight: 700 !important;
        padding: 1px 5px !important;
        border-radius: 3px !important;
        display: inline-block;
        vertical-align: middle;
        white-space: nowrap;
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

    .qb-history-btn {
        background: #f8fafc;
        border: 1px solid #8ca4c3;
        color: #1e3a5f;
        border-radius: 3px;
        width: 24px;
        height: 22px;
        padding: 0;
        font-size: 11px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.12s ease;
    }
    .qb-history-btn:hover {
        background: #1e3a5f;
        color: #ffffff;
        border-color: #0f2744;
    }

    /* 5. Pagination Bar */
    .qb-pagination-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 10px;
        background: #f8fafc;
        border-top: 1px solid #cbd5e1;
    }
    .qb-page-btn {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        color: #0f2744;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 9px;
        height: 26px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .qb-page-btn:hover {
        background: #e2e8f0;
    }
    .qb-page-btn.active {
        background: #2563eb;
        border-color: #1d4ed8;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .qb-win-header { height: auto !important; min-height: 38px; padding: 4px 8px; flex-wrap: wrap; gap: 4px; }
        .qb-filter-form { flex-direction: column; align-items: stretch; }
        .qb-filter-col { width: 100% !important; }
        .qb-sub-ribbon { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- 1. Top Window Title Bar (Navy Blue Gradient - Exact POS & Chart of Accounts Replica) --}}
            <div class="qb-win-header">
                <div class="qb-win-title">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Item Stock &amp; Ledger Report</span>
                    <span class="qb-win-sub d-none d-md-inline">/ Valuation &amp; Movement Audit</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    {{-- Mode Switcher Segmented Pills --}}
                    <div class="qb-mode-group">
                        <button type="button" class="qb-mode-btn active" data-mode="summary">
                            <i class="fas fa-chart-pie"></i> Valuation Summary
                        </button>
                        <button type="button" class="qb-mode-btn" data-mode="ledger">
                            <i class="fas fa-file-invoice-dollar"></i> Detailed Movement Ledger
                        </button>
                    </div>

                    {{-- Export CSV Action --}}
                    <button type="button" id="btnExportCsvTop" class="qb-btn-blue" title="Export current report data to CSV">
                        <i class="fas fa-file-excel"></i> Export CSV
                    </button>
                </div>
            </div>

            {{-- 2. Sub-Header Ribbon: Financial KPIs & Totals --}}
            <div class="qb-sub-ribbon">
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip">
                        <i class="fas fa-coins text-primary"></i>
                        <span class="chip-label">Grand Stock Value:</span>
                        <span class="chip-val font-monospace text-primary" id="kpiGrandStockValue">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-cubes text-success"></i>
                        <span class="chip-label">Current Total Stock:</span>
                        <span class="chip-val font-monospace text-success" id="kpiTotalStock">0 Units</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-sliders-h text-warning"></i>
                        <span class="chip-label">Net Adjustments:</span>
                        <span class="chip-val font-monospace text-warning" id="kpiNetAdjustments">+0 Units</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-shopping-cart text-info"></i>
                        <span class="chip-label">Total Sold Amount:</span>
                        <span class="chip-val font-monospace text-info" id="kpiSoldAmount">Rs 0.00</span>
                    </div>
                </div>

                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="acc-badge-code" id="pageReportNotice">QuickBooks Grid View (50 Items/Page)</span>
                </div>
            </div>

            {{-- 3. Filter Bar (Single-Line Compact QuickBooks Strip) --}}
            <div class="qb-filter-bar">
                <form id="stockFilterForm" class="qb-filter-form">
                    <input type="hidden" name="report_mode" id="report_mode" value="summary">

                    {{-- Category --}}
                    <div class="qb-filter-col" style="min-width: 160px; flex: 1;">
                        <label class="qb-flabel">Category</label>
                        <select name="category_id" id="category_id" class="qb-input">
                            <option value="all">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Warehouse --}}
                    <div class="qb-filter-col" style="min-width: 150px; flex: 1;">
                        <label class="qb-flabel">Warehouse</label>
                        <select name="warehouse_id" id="warehouse_id" class="qb-input">
                            @if(isset($warehouses) && count($warehouses) > 0)
                                <option value="all">-- All Warehouses --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name ?? $wh->warehouse_name }}</option>
                                @endforeach
                            @else
                                <option value="all">-- Main Stock --</option>
                            @endif
                        </select>
                    </div>

                    {{-- Unit Mode Filter --}}
                    <div class="qb-filter-col" style="min-width: 160px; flex: 1;">
                        <label class="qb-flabel">Unit / Size Type</label>
                        <select name="unit_type" id="unit_type" class="qb-input">
                            <option value="all">All Units (Pcs / Kg / M²)</option>
                            <option value="cartons_pcs">Pieces &amp; Cartons</option>
                            <option value="weight_kg">Weight Units (Kg / Gm)</option>
                            <option value="area_m2">Area Units (M² / Feet)</option>
                        </select>
                    </div>

                    {{-- Unique Variant / Item No Filter --}}
                    <div class="qb-filter-col" style="min-width: 135px; flex: 1;">
                        <label class="qb-flabel">Unique Item #</label>
                        <input type="text" name="item_number" id="filter_item_number" class="qb-input font-monospace" placeholder="e.g. 2954, 1781" autocomplete="off">
                    </div>

                    {{-- Product (Select2) --}}
                    <div class="qb-filter-col" style="min-width: 230px; flex: 1.5;">
                        <label class="qb-flabel">Search Product</label>
                        <select name="product_id" id="product_id" class="form-control select2">
                            <option value="all">-- All Products --</option>
                        </select>
                    </div>

                    {{-- Product Variant (Select2) --}}
                    <div class="qb-filter-col" style="min-width: 170px; flex: 1.2;">
                        <label class="qb-flabel">Product Variant</label>
                        <select name="variant_key" id="variant_key" class="form-control select2" disabled>
                            <option value="all">-- All Variants --</option>
                        </select>
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="d-flex align-items-center gap-1" style="margin-bottom: 0;">
                        <button type="button" id="btnSearch" class="qb-btn-search" title="Filter Records">
                            <i class="fas fa-search"></i> Apply
                        </button>
                        <button type="button" id="btnReset" class="qb-btn-reset" title="Reset all filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" id="btnExportCsv" class="qb-btn-gray" title="Export to CSV">
                            <i class="fas fa-file-excel text-success"></i> Export
                        </button>
                    </div>
                </form>
            </div>

            {{-- 4. Table Card (QuickBooks / Odoo High-Density Table with Stripes) --}}
            <div class="qb-table-card">
                <div id="loader" style="display:none; text-align:center; padding:18px; background:#f8fafc;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 text-dark font-monospace fw-bold" style="font-size:12px;">Loading stock &amp; movement records...</span>
                </div>

                <div class="table-responsive">
                    <table id="stockTable" class="qb-table" style="width:100%;">
                        <thead id="tableHeader">
                            {{-- Rendered dynamically based on Summary vs Ledger mode --}}
                        </thead>
                        <tbody id="reportBody">
                            {{-- Filled by AJAX --}}
                        </tbody>
                        <tfoot id="tableFooter">
                            {{-- Grand Totals --}}
                        </tfoot>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div id="paginationContainer" class="qb-pagination-bar">
                    <div id="paginationInfo" class="text-dark font-monospace fw-bold" style="font-size:11.5px;"></div>
                    <div id="paginationLinks" class="d-flex gap-1"></div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     PRODUCT MOVEMENT HISTORY TIMELINE MODAL (ODOO / QB STYLED)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="productHistoryModal" tabindex="-1" aria-labelledby="productHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:6px; overflow:hidden;">
            
            <div class="qb-win-header" style="border-radius:0; height:36px !important; min-height:36px !important;">
                <div class="qb-win-title" style="font-size:13px;">
                    <i class="fas fa-history text-info"></i>
                    <span id="productHistoryModalLabel">Product Movement Timeline</span>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="background:transparent; border:none; font-size:18px; line-height:1; opacity:.85;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="p-2 border-bottom bg-light">
                <small class="text-muted fw-semibold" id="historyModalSub">Chronological audit log of all inward, sales, returns &amp; stock adjustments</small>
            </div>

            <div class="modal-body p-0" style="max-height:420px; overflow-y:auto; background:#ffffff;">
                <table class="qb-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width:150px;">Date &amp; Time</th>
                            <th style="width:140px;">Movement Type</th>
                            <th style="width:150px;">Reference</th>
                            <th class="text-center" style="width:110px;">Quantity</th>
                            <th>Note / Reason</th>
                        </tr>
                    </thead>
                    <tbody id="historyModalBody">
                        {{-- Dynamically populated --}}
                    </tbody>
                </table>
            </div>

            <div class="modal-footer py-2 px-3 border-top bg-light d-flex justify-content-end">
                <button type="button" class="qb-btn-gray" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {

    let currentReportData = [];
    let currentPage = 1;

    // Select2 Product Search
    $('#product_id').select2({
        placeholder: "-- All Products --",
        allowClear: true,
        width: '100%',
        ajax: {
            url: "{{ route('products.search') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        let text = '';
                        if (item.item_number) {
                            text += '[#' + item.item_number + '] ';
                        }
                        if (item.item_code) {
                            text += item.item_code + ' - ';
                        }
                        text += item.item_name;
                        return {
                            id: item.id,
                            text: text
                        }
                    })
                };
            },
            cache: true
        }
    });

    // Select2 Variant Filter
    $('#variant_key').select2({
        placeholder: "-- All Variants --",
        allowClear: true,
        width: '100%'
    });

    $('#product_id').on('select2:unselect', function () {
        $(this).val('all').trigger('change');
    });

    $('#variant_key').on('select2:unselect', function () {
        $(this).val('all').trigger('change');
    });

    // When Product changes, fetch variants dynamically
    $('#product_id').on('change', function () {
        let productId = $(this).val();
        let variantSelect = $('#variant_key');

        variantSelect.empty();
        variantSelect.append('<option value="all">-- All Variants --</option>');

        if (productId && productId !== 'all') {
            variantSelect.prop('disabled', true);
            $.ajax({
                url: "/report/product-variants/" + productId,
                type: "GET",
                success: function (res) {
                    if (res.success && res.has_variants && res.variants && res.variants.length > 0) {
                        $.each(res.variants, function (i, v) {
                            variantSelect.append(new Option(v.text, v.key, false, false));
                        });
                        variantSelect.prop('disabled', false);
                    } else {
                        variantSelect.empty();
                        variantSelect.append('<option value="all">-- No Variants (Standard Item) --</option>');
                        variantSelect.prop('disabled', true);
                    }
                    variantSelect.val('all').trigger('change.select2');
                },
                error: function () {
                    variantSelect.prop('disabled', true);
                }
            });
        } else {
            variantSelect.prop('disabled', true);
            variantSelect.val('all').trigger('change.select2');
        }
    });

    // Mode Toggle Buttons
    $('.qb-mode-btn').on('click', function () {
        $('.qb-mode-btn').removeClass('active');
        $(this).addClass('active');
        $('#report_mode').val($(this).data('mode'));
        currentPage = 1;
        fetchStockReport();
    });

    // Form submit & Enter key
    $('#stockFilterForm').on('submit', function (e) {
        e.preventDefault();
        currentPage = 1;
        fetchStockReport();
    });

    $('#filter_item_number').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            currentPage = 1;
            fetchStockReport();
        }
    });

    // Search Click
    $('#btnSearch').on('click', function () {
        currentPage = 1;
        fetchStockReport();
    });

    // Reset Filters
    $('#btnReset').on('click', function () {
        $('#category_id').val('all');
        $('#warehouse_id').val('all');
        $('#unit_type').val('all');
        $('#filter_item_number').val('');
        $('#product_id').val('all').trigger('change.select2');
        $('#variant_key').empty().append('<option value="all">-- All Variants --</option>').val('all').prop('disabled', true).trigger('change.select2');
        currentPage = 1;
        fetchStockReport();
    });

    // Initial Load
    fetchStockReport();

    function fetchStockReport() {
        $('#loader').show();
        $('#reportBody').empty();

        let mode = $('#report_mode').val();
        renderTableHeader(mode);

        $.ajax({
            url: "{{ route('report.item_stock.fetch') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                category_id: $('#category_id').val(),
                product_id:  $('#product_id').val(),
                variant_key: $('#variant_key').val(),
                warehouse_id: $('#warehouse_id').val(),
                unit_type:   $('#unit_type').val(),
                item_number: $('#filter_item_number').val(),
                report_mode: mode,
                page: currentPage,
                per_page: 50
            },
            success: function (res) {
                $('#loader').hide();
                currentReportData = res.data || [];

                // Update KPIs
                $('#kpiGrandStockValue').text('Rs ' + (res.grand_total || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                
                let totalStock = res.total_current_stock || 0;
                $('#kpiTotalStock').text(totalStock.toLocaleString() + ' Units');
                
                let netAdj = res.total_adjustments_qty || 0;
                let netAdjStr = (netAdj >= 0 ? '+' : '') + netAdj.toLocaleString() + ' Units';
                $('#kpiNetAdjustments').text(netAdjStr);
                
                $('#kpiSoldAmount').text('Rs ' + (res.total_sold_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                renderTableBody(res.data, mode);
                renderPagination(res.pagination);
            },
            error: function () {
                $('#loader').hide();
                alert('Error loading stock report data.');
            }
        });
    }

    function renderPagination(pag) {
        if (!pag || pag.total === 0) {
            $('#paginationInfo').text('Showing 0 of 0 items');
            $('#paginationLinks').empty();
            return;
        }

        let start = ((pag.current_page - 1) * 50) + 1;
        let end = Math.min(pag.current_page * 50, pag.total);
        $('#paginationInfo').text(`Showing ${start} to ${end} of ${pag.total.toLocaleString()} items (50 per page)`);

        let links = '';
        if (pag.current_page > 1) {
            links += `<button class="qb-page-btn page-btn" data-page="${pag.current_page - 1}"><i class="fas fa-chevron-left me-1"></i> Prev</button>`;
        }

        let startPage = Math.max(1, pag.current_page - 2);
        let endPage = Math.min(pag.last_page, pag.current_page + 2);

        if (startPage > 1) {
            links += `<button class="qb-page-btn page-btn" data-page="1">1</button>`;
            if (startPage > 2) links += `<span class="px-1 text-muted">...</span>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === pag.current_page) {
                links += `<button class="qb-page-btn active page-btn" data-page="${i}">${i}</button>`;
            } else {
                links += `<button class="qb-page-btn page-btn" data-page="${i}">${i}</button>`;
            }
        }

        if (endPage < pag.last_page) {
            if (endPage < pag.last_page - 1) links += `<span class="px-1 text-muted">...</span>`;
            links += `<button class="qb-page-btn page-btn" data-page="${pag.last_page}">${pag.last_page}</button>`;
        }
        
        if (pag.current_page < pag.last_page) {
            links += `<button class="qb-page-btn page-btn" data-page="${pag.current_page + 1}">Next <i class="fas fa-chevron-right ms-1"></i></button>`;
        }
        
        $('#paginationLinks').html(links);
    }

    $(document).on('click', '.page-btn', function() {
        currentPage = $(this).data('page');
        fetchStockReport();
    });

    function renderTableHeader(mode) {
        let thead = $('#tableHeader');
        thead.empty();

        if (mode === 'summary') {
            thead.append(`
                <tr>
                    <th style="width:28px;" class="text-center">#</th>
                    <th style="width:78px;">Item Code</th>
                    <th style="width:62px;" class="text-center" title="Unique Variant / Item No">Unique #</th>
                    <th>Item / Variant Name</th>
                    <th style="width:92px;">Category</th>
                    <th class="text-center" style="width:42px;">Unit</th>
                    <th class="text-center" style="width:78px;" title="Stock Balance">Stock Bal</th>
                    <th class="text-center" style="width:80px;" title="Cartons / Loose Pieces">Cartons</th>
                    <th class="text-end" style="width:88px;" title="Average Cost in Rupees">Avg Cost</th>
                    <th class="text-end" style="width:96px;" title="Total Stock Value in Rupees">Stock Value</th>
                    <th class="text-center" style="width:82px;">Status</th>
                    <th class="text-center" style="width:36px;" title="Movement History Timeline"><i class="fas fa-history"></i></th>
                </tr>
            `);
        } else {
            thead.append(`
                <tr>
                    <th style="width:28px;" class="text-center">#</th>
                    <th style="width:78px;">Item Code</th>
                    <th style="width:62px;" class="text-center" title="Unique Variant / Item No">Unique #</th>
                    <th>Item / Variant Name</th>
                    <th class="text-end" style="width:56px;" title="Opening Stock">Open</th>
                    <th class="text-end" style="width:58px;" title="Purchased">Purch (+)</th>
                    <th class="text-end" style="width:58px;" title="Sold">Sold (-)</th>
                    <th class="text-end" style="width:60px;" title="Sale Return">Sal Ret (+)</th>
                    <th class="text-end" style="width:60px;" title="Purchase Return">Pur Ret (-)</th>
                    <th class="text-center" style="background:#fffbeb !important; color:#92400e !important; width:66px;" title="Stock Adjustments">Adjust</th>
                    <th class="text-end fw-bold" style="background:#eef2ff !important; color:#1d4ed8 !important; width:72px;" title="Closing Stock">Closing</th>
                    <th class="text-end" style="width:92px;" title="Total Stock Value in Rupees">Stock Value</th>
                    <th class="text-center" style="width:36px;" title="Movement History Timeline"><i class="fas fa-history"></i></th>
                </tr>
            `);
        }
    }

    function renderTableBody(data, mode) {
        let tbody  = $('#reportBody');
        let tfooter= $('#tableFooter');
        tbody.empty();
        tfooter.empty();

        if (!data || data.length === 0) {
            tbody.append(`<tr><td colspan="13" class="text-center py-4 text-muted fw-semibold" style="font-size:13px;">No stock data found matching the selected filters.</td></tr>`);
            return;
        }

        let totalValue = 0;

        $.each(data, function (i, row) {
            totalValue += parseFloat(row.stock_value) || 0;

            let historyBtn = `
                <button type="button" class="qb-history-btn view-history-btn" data-id="${row.id}" data-name="${escapeHtml(row.item_name)}" title="View Movement History Timeline">
                    <i class="fas fa-history"></i>
                </button>
            `;

            let itemCodeBadge = row.item_code 
                ? `<span class="acc-badge-code">${row.item_code}</span>` 
                : `<span class="text-muted tabular-nums">—</span>`;

            let itemNumberBadge = (row.item_number && String(row.item_number).trim() !== '') 
                ? `<span class="badge bg-primary text-white" style="font-family:'SFMono-Regular',Consolas,'Segoe UI',monospace; font-size:10.5px; font-weight:600; padding:2px 5px; border-radius:3px;" title="Unique Variant / Item No">#${escapeHtml(String(row.item_number).replace(/^#/, ''))}</span>` 
                : `<span class="text-muted tabular-nums">—</span>`;

            if (mode === 'summary') {
                let statusBadge = '<span class="qb-status-badge qb-status-healthy"><i class="fas fa-check-circle"></i> In Stock</span>';
                if (row.status === 'out_of_stock') {
                    statusBadge = '<span class="qb-status-badge qb-status-out"><i class="fas fa-times-circle"></i> Out of Stock</span>';
                } else if (row.status === 'low_stock') {
                    statusBadge = '<span class="qb-status-badge qb-status-low"><i class="fas fa-exclamation-triangle"></i> Low Stock</span>';
                }

                let stockNum = parseFloat(row.balance) || 0;
                let stockColor = stockNum > 0 ? 'color:#15803d;' : (stockNum < 0 ? 'color:#b91c1c;' : 'color:#475569;');

                let rowHtml = `
                <tr>
                    <td class="text-center text-muted tabular-nums fw-semibold" style="font-size:11.5px; padding:4px 2px;">${i + 1}</td>
                    <td style="padding:4px 5px;">${itemCodeBadge}</td>
                    <td class="text-center" style="padding:4px 3px;">${itemNumberBadge}</td>
                    <td class="fw-semibold text-dark" style="font-size:12px; line-height:1.25; word-break:break-word; padding:4px 6px;">${row.item_name}</td>
                    <td class="text-secondary" style="font-size:11.5px; line-height:1.2; word-break:break-word; padding:4px 5px;">${row.category_name || '—'}</td>
                    <td class="text-center" style="padding:4px 3px;"><span class="qb-unit-badge">${row.unit_name || 'Pcs'}</span></td>
                    <td class="text-center tabular-nums fw-bold" style="${stockColor} font-size:12px; padding:4px 5px;">${row.formatted_stock}</td>
                    <td class="text-center tabular-nums fw-semibold" style="color:#475569; font-size:11.5px; padding:4px 5px;">${row.carton_display || '—'}</td>
                    <td class="text-end tabular-nums fw-bold text-dark" style="font-size:12px; padding:4px 5px;">Rs ${parseFloat(row.average_price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-end tabular-nums fw-bold" style="color:#0f172a; font-size:12px; padding:4px 5px;">Rs ${parseFloat(row.stock_value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-center" style="padding:4px 3px;">${statusBadge}</td>
                    <td class="text-center" style="padding:4px 2px;">${historyBtn}</td>
                </tr>`;
                tbody.append(rowHtml);

            } else {
                // Detailed Movement Ledger Row
                let adjVal = parseFloat(row.adjustments) || 0;
                let adjBadge = '<span class="text-muted tabular-nums">—</span>';
                if (adjVal > 0) {
                    adjBadge = `<span class="acc-bal-flag dr tabular-nums">+${adjVal}</span>`;
                } else if (adjVal < 0) {
                    adjBadge = `<span class="acc-bal-flag cr tabular-nums">${adjVal}</span>`;
                }

                let stockNum = parseFloat(row.balance) || 0;
                let stockColor = stockNum > 0 ? 'color:#15803d;' : (stockNum < 0 ? 'color:#b91c1c;' : 'color:#475569;');

                let rowHtml = `
                <tr>
                    <td class="text-center text-muted tabular-nums fw-semibold" style="font-size:11.5px; padding:4px 2px;">${i + 1}</td>
                    <td style="padding:4px 5px;">${itemCodeBadge}</td>
                    <td class="text-center" style="padding:4px 3px;">${itemNumberBadge}</td>
                    <td class="fw-semibold text-dark" style="font-size:12px; line-height:1.25; word-break:break-word; padding:4px 6px;">${row.item_name}</td>
                    <td class="text-end tabular-nums text-muted" style="font-size:11.5px; padding:4px 4px;">${parseFloat(row.initial_stock).toLocaleString()}</td>
                    <td class="text-end tabular-nums text-success fw-bold" style="font-size:11.5px; padding:4px 4px;">+${parseFloat(row.purchased).toLocaleString()}</td>
                    <td class="text-end tabular-nums text-danger fw-bold" style="font-size:11.5px; padding:4px 4px;">-${parseFloat(row.sold).toLocaleString()}</td>
                    <td class="text-end tabular-nums text-success fw-bold" style="font-size:11.5px; padding:4px 4px;">+${parseFloat(row.returned_qty).toLocaleString()}</td>
                    <td class="text-end tabular-nums text-danger fw-bold" style="font-size:11.5px; padding:4px 4px;">-${parseFloat(row.purch_returned_qty).toLocaleString()}</td>
                    <td class="text-center qb-col-adjust" style="padding:4px 3px;">${adjBadge}</td>
                    <td class="text-end tabular-nums fw-bold qb-col-closing" style="${stockColor} font-size:12px; padding:4px 5px;">${row.formatted_stock}</td>
                    <td class="text-end tabular-nums fw-bold text-dark" style="font-size:12px; padding:4px 5px;">Rs ${parseFloat(row.stock_value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-center" style="padding:4px 2px;">${historyBtn}</td>
                </tr>`;
                tbody.append(rowHtml);
            }
        });

        // Table Footer Summary
        if (mode === 'summary') {
            tfooter.append(`
                <tr>
                    <td colspan="8" class="text-end fw-bold" style="color:#0f2744; letter-spacing:0.3px; font-size:11.5px;">PAGE STOCK VALUATION TOTAL:</td>
                    <td class="text-end tabular-nums fw-bold" style="color:#1e3a5f; font-size:12px;">—</td>
                    <td class="text-end tabular-nums fw-bold" style="color:#1d4ed8; font-size:12.5px;">Rs ${totalValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td colspan="2"></td>
                </tr>
            `);
        } else {
            tfooter.append(`
                <tr>
                    <td colspan="11" class="text-end fw-bold" style="color:#0f2744; letter-spacing:0.3px; font-size:11.5px;">PAGE STOCK VALUATION TOTAL:</td>
                    <td class="text-end tabular-nums fw-bold" style="color:#1d4ed8; font-size:12.5px;">Rs ${totalValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td></td>
                </tr>
            `);
        }
    }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // View Product History Timeline Modal
    $(document).on('click', '.view-history-btn', function () {
        let productId = $(this).data('id');
        let productName = $(this).data('name');

        $('#productHistoryModalLabel').html('<i class="fas fa-history text-info me-2"></i>Movement Timeline: ' + productName);
        let tbody = $('#historyModalBody');
        tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading movement history...</td></tr>');
        $('#productHistoryModal').modal('show');

        $.ajax({
            url: "/report/item-stock-history/" + productId,
            type: "GET",
            success: function (res) {
                tbody.empty();
                if (res.success && res.history && res.history.length > 0) {
                    $.each(res.history, function (i, m) {
                        let qtyVal = parseFloat(m.qty) || 0;
                        let qtyColor = qtyVal > 0 ? 'text-success' : (qtyVal < 0 ? 'text-danger' : 'text-dark');
                        let row = `
                        <tr>
                            <td class="ps-3 text-dark font-monospace fw-semibold" style="font-size:12px;">${m.date}</td>
                            <td><span class="badge bg-${m.type_badge} px-2 py-1 font-monospace" style="font-size:11.5px;">${m.type}</span></td>
                            <td><span class="acc-badge-code">${m.ref_type}</span></td>
                            <td class="text-center font-monospace fw-bold ${qtyColor}" style="font-size:12.5px;">${qtyVal > 0 ? '+' : ''}${m.qty}</td>
                            <td style="font-size:12px; color:#334155;">${m.note || '—'}</td>
                        </tr>`;
                        tbody.append(row);
                    });
                } else {
                    tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted fw-semibold" style="font-size:12px;">No stock movements recorded for this item yet.</td></tr>');
                }
            },
            error: function () {
                tbody.html('<tr><td colspan="5" class="text-center py-4 text-danger fw-semibold" style="font-size:12px;">Failed to fetch movement history.</td></tr>');
            }
        });
    });

    // Export CSV
    $('#btnExportCsv, #btnExportCsvTop').on('click', function () {
        if (!currentReportData || currentReportData.length === 0) {
            alert('No report data to export.');
            return;
        }

        let mode = $('#report_mode').val();
        let csv = '';

        if (mode === 'summary') {
            csv = 'Item Code,Unique Item #,Item Name,Category,Unit,Stock Balance,Cartons / Loose,Avg Price (Rs),Stock Value (Rs),Status\n';
            $.each(currentReportData, function (i, r) {
                let code = (r.item_code || '').replace(/"/g, '""');
                let itemNum = (r.item_number || '').replace(/"/g, '""');
                let name = (r.item_name || '').replace(/"/g, '""');
                let cat  = (r.category_name || '').replace(/"/g, '""');
                let unit = (r.unit_name || '').replace(/"/g, '""');
                let bal  = (r.balance || 0);
                let cart = (r.carton_display || '').replace(/"/g, '""');
                let avg  = (r.average_price || 0);
                let val  = (r.stock_value || 0);
                let st   = (r.status || '');
                csv += `"${code}","${itemNum}","${name}","${cat}","${unit}","${bal}","${cart}","${avg}","${val}","${st}"\n`;
            });
        } else {
            csv = 'Item Code,Unique Item #,Item Name,Opening,Purchased (+),Sold (-),Sale Return (+),Purch Return (-),Adjustments (+/-),Closing Stock,Stock Value (Rs)\n';
            $.each(currentReportData, function (i, r) {
                let code = (r.item_code || '').replace(/"/g, '""');
                let itemNum = (r.item_number || '').replace(/"/g, '""');
                let name = (r.item_name || '').replace(/"/g, '""');
                let op   = (r.initial_stock || 0);
                let pu   = (r.purchased || 0);
                let so   = (r.sold || 0);
                let sr   = (r.returned_qty || 0);
                let pr   = (r.purch_returned_qty || 0);
                let adj  = (r.adjustments || 0);
                let cl   = (r.balance || 0);
                let val  = (r.stock_value || 0);
                csv += `"${code}","${itemNum}","${name}","${op}","${pu}","${so}","${sr}","${pr}","${adj}","${cl}","${val}"\n`;
            });
        }

        let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `Item_Stock_Report_${mode}_` + new Date().toISOString().slice(0,10) + ".csv";
        link.click();
    });

});
</script>
@endsection
