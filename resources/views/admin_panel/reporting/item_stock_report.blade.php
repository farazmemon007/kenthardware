@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "ITEM STOCK & LEDGER REPORT" THEME
       Pixel-Perfect High-Density Layout Matching Sales & Accounts
       With Normal QuickBooks Fonts & Alternating Zebra Strips
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap');

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
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .odoo-page-wrapper {
        padding: 0 4px !important;
        max-width: 100% !important;
    }

    .font-monospace, .qb-mono, .tabular-nums {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
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
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
    }
    .qb-win-title i {
        color: #38bdf8;
        font-size: 16px;
    }
    .qb-win-sub {
        font-size: 12.5px;
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
        font-size: 13px;
        font-weight: 700;
        padding: 4px 13px;
        height: 28px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
        font-size: 13px !important;
        padding: 4px 13px !important;
        height: 29px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
        font-size: 13px !important;
        padding: 4px 13px !important;
        height: 29px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
        padding: 6px 12px;
        margin-bottom: 5px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }
    .qb-kpi-group {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .qb-kpi-chip {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 4px;
        padding: 6px 13px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        line-height: 1.25;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
    }
    .qb-kpi-chip .chip-label {
        font-weight: 800;
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .qb-kpi-chip .chip-val {
        font-weight: 800;
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-variant-numeric: tabular-nums;
        font-size: 16.5px;
    }

    /* 3. Filter Bar (Single-Line Compact Strip) */
    .qb-filter-bar {
        background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
        border: 1px solid #b9cce2;
        border-radius: 4px;
        padding: 6px 10px;
        margin-bottom: 5px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
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
        font-size: 12.5px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #1e3a5f !important;
        margin-bottom: 3px !important;
        white-space: nowrap;
    }
    .qb-input, select.qb-input {
        height: 35px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 4px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding: 3px 10px !important;
        background-color: #ffffff;
        outline: none;
    }
    .qb-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
    }

    /* Select2 High-Density ERP Styling */
    .qb-filter-col .select2-container .select2-selection--single {
        height: 35px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 4px !important;
        padding: 0 6px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 33px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding-left: 4px !important;
    }
    .qb-filter-col .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 33px !important;
        right: 6px !important;
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
        border-radius: 4px !important;
        height: 35px !important;
        padding: 0 15px !important;
        font-size: 13.5px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
        border-radius: 4px !important;
        height: 35px !important;
        padding: 0 13px !important;
        font-size: 13.5px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        position: relative !important;
    }
    .col-resize-guide {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #2563eb;
        box-shadow: 0 0 6px rgba(37, 99, 235, 0.8), 0 0 2px #1d4ed8;
        z-index: 100;
        display: none;
        pointer-events: none;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 15.5px !important;
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
    }

    /* Table Headers (Soft Blue-Gray Gradient with Sharp Dividers) */
    .qb-table thead th {
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 13.5px !important;
        letter-spacing: 0.35px;
        border-bottom: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-top: none !important;
        padding: 9px 12px !important;
        white-space: nowrap !important;
        vertical-align: middle;
        position: relative !important;
        box-sizing: border-box !important;
        overflow: hidden;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }

    /* Column Resizer Handle */
    .qb-table thead th .col-resizer {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 100%;
        cursor: col-resize !important;
        user-select: none !important;
        z-index: 20;
        display: block;
    }
    .qb-table thead th .col-resizer::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        width: 3px;
        background: transparent;
        transition: background 0.1s ease;
    }
    .qb-table thead th .col-resizer:hover::after,
    .qb-table thead th .col-resizer.is-resizing::after {
        background: #2563eb;
        box-shadow: 0 0 6px rgba(37, 99, 235, 0.9);
    }
    body.is-col-resizing {
        cursor: col-resize !important;
        user-select: none !important;
    }
    body.is-col-resizing * {
        user-select: none !important;
        cursor: col-resize !important;
    }
    body.is-col-resizing .qb-table tbody tr,
    body.is-col-resizing .qb-table thead th {
        pointer-events: none !important;
        transition: none !important;
    }
    body.is-col-resizing .col-resizer {
        pointer-events: auto !important;
    }

    /* Draggable Column Headers */
    .qb-table thead th.qb-col-draggable {
        cursor: grab !important;
        user-select: none !important;
        position: relative;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .qb-table thead th.qb-col-draggable:active {
        cursor: grabbing !important;
    }
    .qb-table thead th.qb-col-draggable:hover {
        background: linear-gradient(180deg, #edf4fc 0%, #d8e5f3 100%) !important;
    }
    .qb-table thead th.th-dragging {
        opacity: 0.45 !important;
        background: #dbeafe !important;
        outline: 2px dashed #2563eb !important;
    }
    .qb-table thead th.th-drag-over-left {
        border-left: 3px solid #2563eb !important;
        background: #eff6ff !important;
    }
    .qb-table thead th.th-drag-over-right {
        border-right: 3px solid #2563eb !important;
        background: #eff6ff !important;
    }
    .qb-col-grip {
        display: inline-block;
        color: #64748b;
        font-size: 11px;
        margin-right: 4px;
        opacity: 0.6;
        vertical-align: middle;
    }
    .qb-table thead th:hover .qb-col-grip {
        color: #0284c7;
        opacity: 1;
    }

    /* Table Body Cells */
    .qb-table tbody td {
        border-bottom: 1px solid #c8d7e6 !important;
        border-right: 1px solid #cbd8e7 !important;
        border-top: none !important;
        padding: 8px 10px !important;
        font-size: 15.5px !important;
        color: #0f172a !important;
        vertical-align: middle;
        line-height: 1.4;
        box-sizing: border-box !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .qb-table tbody td[data-col="name"],
    .qb-table tbody td[data-col="category"] {
        white-space: normal !important;
        word-break: break-word !important;
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
        font-weight: 800 !important;
        font-size: 16px !important;
        border-top: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        padding: 9px 10px !important;
        vertical-align: middle;
    }
    .qb-table tfoot th:last-child,
    .qb-table tfoot td:last-child {
        border-right: none !important;
    }

    /* High-Contrast Badges & Flags (Enhanced Legibility Styling) */
    .acc-badge-code {
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 800 !important;
        color: #0f2744 !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 3px 8px !important;
        display: inline-block;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
    .qb-unit-badge {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 13.5px;
        font-weight: 700;
        white-space: nowrap;
        display: inline-block;
    }
    .qb-status-badge {
        font-size: 13px !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 3px 8px !important;
        border-radius: 4px !important;
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
        border-radius: 4px;
        width: 30px;
        height: 28px;
        padding: 0;
        font-size: 13.5px;
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
        padding: 8px 14px;
        background: #f8fafc;
        border-top: 1px solid #cbd5e1;
    }
    .qb-page-btn {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        color: #0f2744;
        font-size: 14px;
        font-weight: 700;
        padding: 3px 12px;
        height: 30px;
        border-radius: 4px;
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

                    {{-- Reset Column Order Action --}}
                    <button type="button" id="btnResetColOrder" class="qb-btn-gray" title="Reset column positions and widths back to default">
                        <i class="fas fa-columns text-primary"></i> Reset Columns
                    </button>

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
                    <span class="acc-badge-code" style="font-size:13px; background:#eff6ff; color:#1e40af; border-color:#bfdbfe;" title="Drag header to reorder, or drag column right border to resize"><i class="fas fa-arrows-alt text-primary me-1"></i> Drag to Reorder &amp; Resize</span>
                    <span class="acc-badge-code" id="pageReportNotice" style="font-size:13px;">QuickBooks Grid View (50 Items/Page)</span>
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
                    <span class="ms-2 text-dark tabular-nums fw-bold" style="font-size:14px;">Loading stock &amp; movement records...</span>
                </div>

                <div class="table-responsive">
                    <div id="colResizeGuide" class="col-resize-guide"></div>
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
                    <div id="paginationInfo" class="text-dark tabular-nums fw-bold" style="font-size:13.5px;"></div>
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
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background:transparent; border:none; font-size:18px; line-height:1; opacity:.85;">
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
                <button type="button" class="qb-btn-gray" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {

    let currentReportData = [];
    let currentPage = 1;

    // Select2 Product Search (Local Select2 from app layout)
    if ($.fn.select2) {
        $('#product_id').select2({
            placeholder: "-- All Products --",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('products.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term || '' };
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
                            };
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
    }

    // Unselect / Clear handlers for Select2
    $('#product_id').on('select2:unselect select2:clear', function () {
        $(this).empty().append('<option value="all">-- All Products --</option>').val('all');
        $('#variant_key').empty().append('<option value="all">-- All Variants --</option>').val('all').prop('disabled', true);
        if ($.fn.select2) {
            $('#variant_key').trigger('change.select2');
        }
        currentPage = 1;
        fetchStockReport();
    });

    $('#variant_key').on('select2:unselect select2:clear', function () {
        $(this).val('all');
        currentPage = 1;
        fetchStockReport();
    });

    // When Product changes, fetch variants dynamically & update report
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
                    if ($.fn.select2) {
                        variantSelect.val('all').trigger('change.select2');
                    }
                },
                error: function () {
                    variantSelect.prop('disabled', true);
                }
            });
        } else {
            variantSelect.prop('disabled', true);
            if ($.fn.select2) {
                variantSelect.val('all').trigger('change.select2');
            }
        }

        currentPage = 1;
        fetchStockReport();
    });

    // When Variant changes, update report
    $('#variant_key').on('change', function () {
        currentPage = 1;
        fetchStockReport();
    });

    // Auto-filter when Category, Warehouse, or Unit type changes
    $('#category_id, #warehouse_id, #unit_type').on('change', function () {
        currentPage = 1;
        fetchStockReport();
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
        $('#product_id').empty().append('<option value="all">-- All Products --</option>').val('all');
        if ($.fn.select2) {
            $('#product_id').trigger('change.select2');
        }
        $('#variant_key').empty().append('<option value="all">-- All Variants --</option>').val('all').prop('disabled', true);
        if ($.fn.select2) {
            $('#variant_key').trigger('change.select2');
        }
        currentPage = 1;
        fetchStockReport();
    });

    // Reset Column Order & Widths Button
    $('#btnResetColOrder').on('click', function () {
        let mode = $('#report_mode').val() || 'summary';
        localStorage.removeItem('kent_stock_report_cols_' + mode);
        localStorage.removeItem('kent_stock_report_widths_' + mode);
        $('#stockTable').css({
            'width': '100%',
            'min-width': '100%'
        });
        renderTableHeader(mode);
        renderTableBody(currentReportData, mode);
    });

    // ==========================================================
    // COLUMN DEFINITIONS WITH REORDER & DRAG SUPPORT
    // ==========================================================

    const summaryColumns = {
        'index': {
            key: 'index',
            title: '#',
            thHtml: '<th data-col="index" style="width:42px;" class="text-center qb-col-draggable" title="Drag to reorder / resize column"><i class="fas fa-grip-vertical qb-col-grip"></i>#</th>',
            getCell: (row, i) => `<td data-col="index" class="text-center text-muted tabular-nums fw-semibold" style="font-size:14px; padding:8px 6px;">${((currentPage - 1) * 50) + (i + 1)}</td>`,
            getFooter: () => `<td data-col="index" class="text-center text-muted" style="font-size:14px;">—</td>`,
            getCsvValue: (r, i) => ((currentPage - 1) * 50) + (i + 1)
        },
        'code': {
            key: 'code',
            title: 'Item Code',
            thHtml: '<th data-col="code" style="width:105px;" class="qb-col-draggable" title="Drag to reorder / resize column"><i class="fas fa-grip-vertical qb-col-grip"></i>Item Code</th>',
            getCell: (row) => `<td data-col="code" style="padding:8px 8px;">${row.item_code ? `<span class="acc-badge-code">${escapeHtml(row.item_code)}</span>` : `<span class="text-muted tabular-nums">—</span>`}</td>`,
            getFooter: () => `<td data-col="code"></td>`,
            getCsvValue: (r) => r.item_code || ''
        },
        'unique': {
            key: 'unique',
            title: 'Unique #',
            thHtml: '<th data-col="unique" style="width:90px;" class="text-center qb-col-draggable" title="Unique Variant / Item No - Drag to reorder / resize"><i class="fas fa-grip-vertical qb-col-grip"></i>Unique #</th>',
            getCell: (row) => `<td data-col="unique" class="text-center" style="padding:8px 6px;">${(row.item_number && String(row.item_number).trim() !== '') ? `<span class="badge bg-primary text-white" style="font-family:'Inter',system-ui,sans-serif; font-size:13px; font-weight:700; padding:3.5px 8px; border-radius:4px;" title="Unique Variant / Item No">#${escapeHtml(String(row.item_number).replace(/^#/, ''))}</span>` : `<span class="text-muted tabular-nums">—</span>`}</td>`,
            getFooter: () => `<td data-col="unique"></td>`,
            getCsvValue: (r) => r.item_number || ''
        },
        'name': {
            key: 'name',
            title: 'Item / Variant Name',
            thHtml: '<th data-col="name" class="qb-col-draggable" style="width:230px;" title="Drag to reorder / resize column"><i class="fas fa-grip-vertical qb-col-grip"></i>Item / Variant Name</th>',
            getCell: (row) => `<td data-col="name" class="fw-bold text-dark" style="font-size:15.5px; line-height:1.35; word-break:break-word; padding:8px 10px;">${escapeHtml(row.item_name)}</td>`,
            getFooter: () => `<td data-col="name" class="fw-bold text-dark text-end" style="font-size:15px; letter-spacing:0.3px;">PAGE TOTAL:</td>`,
            getCsvValue: (r) => r.item_name || ''
        },
        'category': {
            key: 'category',
            title: 'Category',
            thHtml: '<th data-col="category" style="width:125px;" class="qb-col-draggable" title="Drag to reorder column"><i class="fas fa-grip-vertical qb-col-grip"></i>Category</th>',
            getCell: (row) => `<td data-col="category" class="text-secondary" style="font-size:14.5px; line-height:1.3; word-break:break-word; padding:8px 8px;">${escapeHtml(row.category_name || '—')}</td>`,
            getFooter: () => `<td data-col="category"></td>`,
            getCsvValue: (r) => r.category_name || ''
        },
        'unit': {
            key: 'unit',
            title: 'Unit',
            thHtml: '<th data-col="unit" class="text-center qb-col-draggable" style="width:60px;" title="Drag to reorder column"><i class="fas fa-grip-vertical qb-col-grip"></i>Unit</th>',
            getCell: (row) => `<td data-col="unit" class="text-center" style="padding:8px 6px;"><span class="qb-unit-badge">${escapeHtml(row.unit_name || 'Pcs')}</span></td>`,
            getFooter: () => `<td data-col="unit"></td>`,
            getCsvValue: (r) => r.unit_name || ''
        },
        'stock': {
            key: 'stock',
            title: 'Stock Bal',
            thHtml: '<th data-col="stock" class="text-center qb-col-draggable" style="width:105px;" title="Stock Balance - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Stock Bal</th>',
            getCell: (row) => {
                let s = parseFloat(row.balance) || 0;
                let c = s > 0 ? "color:#15803d;" : (s < 0 ? "color:#b91c1c;" : "color:#475569;");
                return `<td data-col="stock" class="text-center tabular-nums fw-bold" style="${c} font-size:15.5px; padding:8px 8px;">${escapeHtml(row.formatted_stock)}</td>`;
            },
            getFooter: () => `<td data-col="stock"></td>`,
            getCsvValue: (r) => r.balance || 0
        },
        'cartons': {
            key: 'cartons',
            title: 'Cartons',
            thHtml: '<th data-col="cartons" class="text-center qb-col-draggable" style="width:100px;" title="Cartons / Loose Pieces - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Cartons</th>',
            getCell: (row) => `<td data-col="cartons" class="text-center tabular-nums fw-semibold" style="color:#475569; font-size:14.5px; padding:8px 8px;">${row.carton_display || '—'}</td>`,
            getFooter: () => `<td data-col="cartons"></td>`,
            getCsvValue: (r) => r.carton_display || ''
        },
        'avgcost': {
            key: 'avgcost',
            title: 'Avg Cost (Rs)',
            thHtml: '<th data-col="avgcost" class="text-end qb-col-draggable" style="width:115px;" title="Average Cost in Rupees - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Avg Cost</th>',
            getCell: (row) => `<td data-col="avgcost" class="text-end tabular-nums fw-bold text-dark" style="font-size:15.5px; padding:8px 10px;">Rs ${(parseFloat(row.average_price) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>`,
            getFooter: () => `<td data-col="avgcost" class="text-end tabular-nums text-muted" style="font-size:14.5px;">—</td>`,
            getCsvValue: (r) => r.average_price || 0
        },
        'value': {
            key: 'value',
            title: 'Stock Value (Rs)',
            thHtml: '<th data-col="value" class="text-end qb-col-draggable" style="width:130px;" title="Total Stock Value in Rupees - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Stock Value</th>',
            getCell: (row) => `<td data-col="value" class="text-end tabular-nums fw-bold" style="color:#0f172a; font-size:15.5px; padding:8px 10px;">Rs ${(parseFloat(row.stock_value) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>`,
            getFooter: (totalVal) => `<td data-col="value" class="text-end tabular-nums fw-bold" style="color:#1d4ed8; font-size:16px; padding:9px 10px;">Rs ${(totalVal || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>`,
            getCsvValue: (r) => r.stock_value || 0
        },
        'status': {
            key: 'status',
            title: 'Status',
            thHtml: '<th data-col="status" class="text-center qb-col-draggable" style="width:105px;" title="Drag to reorder column"><i class="fas fa-grip-vertical qb-col-grip"></i>Status</th>',
            getCell: (row) => {
                let statusBadge = '<span class="qb-status-badge qb-status-healthy"><i class="fas fa-check-circle"></i> In Stock</span>';
                if (row.status === 'out_of_stock') {
                    statusBadge = '<span class="qb-status-badge qb-status-out"><i class="fas fa-times-circle"></i> Out of Stock</span>';
                } else if (row.status === 'low_stock') {
                    statusBadge = '<span class="qb-status-badge qb-status-low"><i class="fas fa-exclamation-triangle"></i> Low Stock</span>';
                }
                return `<td data-col="status" class="text-center" style="padding:8px 6px;">${statusBadge}</td>`;
            },
            getFooter: () => `<td data-col="status"></td>`,
            getCsvValue: (r) => r.status || ''
        },
        'history': {
            key: 'history',
            title: 'History',
            thHtml: '<th data-col="history" class="text-center qb-col-draggable" style="width:48px;" title="Movement History Timeline - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i><i class="fas fa-history"></i></th>',
            getCell: (row) => `<td data-col="history" class="text-center" style="padding:8px 6px;"><button type="button" class="qb-history-btn view-history-btn" data-id="${row.id}" data-name="${escapeHtml(row.item_name)}" title="View Movement History Timeline"><i class="fas fa-history"></i></button></td>`,
            getFooter: () => `<td data-col="history"></td>`,
            getCsvValue: () => ''
        }
    };

    const ledgerColumns = {
        'index': {
            key: 'index',
            title: '#',
            thHtml: '<th data-col="index" style="width:42px;" class="text-center qb-col-draggable" title="Drag to reorder column"><i class="fas fa-grip-vertical qb-col-grip"></i>#</th>',
            getCell: (row, i) => `<td data-col="index" class="text-center text-muted tabular-nums fw-semibold" style="font-size:14px; padding:8px 6px;">${((currentPage - 1) * 50) + (i + 1)}</td>`,
            getFooter: () => `<td data-col="index" class="text-center text-muted" style="font-size:14px;">—</td>`,
            getCsvValue: (r, i) => ((currentPage - 1) * 50) + (i + 1)
        },
        'code': {
            key: 'code',
            title: 'Item Code',
            thHtml: '<th data-col="code" style="width:105px;" class="qb-col-draggable" title="Drag to reorder column"><i class="fas fa-grip-vertical qb-col-grip"></i>Item Code</th>',
            getCell: (row) => `<td data-col="code" style="padding:8px 8px;">${row.item_code ? `<span class="acc-badge-code">${escapeHtml(row.item_code)}</span>` : `<span class="text-muted tabular-nums">—</span>`}</td>`,
            getFooter: () => `<td data-col="code"></td>`,
            getCsvValue: (r) => r.item_code || ''
        },
        'unique': {
            key: 'unique',
            title: 'Unique #',
            thHtml: '<th data-col="unique" style="width:90px;" class="text-center qb-col-draggable" title="Unique Variant / Item No - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Unique #</th>',
            getCell: (row) => `<td data-col="unique" class="text-center" style="padding:8px 6px;">${(row.item_number && String(row.item_number).trim() !== '') ? `<span class="badge bg-primary text-white" style="font-family:'Inter',system-ui,sans-serif; font-size:13px; font-weight:700; padding:3.5px 8px; border-radius:4px;" title="Unique Variant / Item No">#${escapeHtml(String(row.item_number).replace(/^#/, ''))}</span>` : `<span class="text-muted tabular-nums">—</span>`}</td>`,
            getFooter: () => `<td data-col="unique"></td>`,
            getCsvValue: (r) => r.item_number || ''
        },
        'name': {
            key: 'name',
            title: 'Item / Variant Name',
            thHtml: '<th data-col="name" class="qb-col-draggable" style="width:210px;" title="Drag to reorder / resize column"><i class="fas fa-grip-vertical qb-col-grip"></i>Item / Variant Name</th>',
            getCell: (row) => `<td data-col="name" class="fw-bold text-dark" style="font-size:15.5px; line-height:1.35; word-break:break-word; padding:8px 10px;">${escapeHtml(row.item_name)}</td>`,
            getFooter: () => `<td data-col="name" class="fw-bold text-dark text-end" style="font-size:15px; letter-spacing:0.3px;">PAGE TOTAL:</td>`,
            getCsvValue: (r) => r.item_name || ''
        },
        'opening': {
            key: 'opening',
            title: 'Open',
            thHtml: '<th data-col="opening" class="text-end qb-col-draggable" style="width:75px;" title="Opening Stock - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Open</th>',
            getCell: (row) => `<td data-col="opening" class="text-end tabular-nums text-muted fw-semibold" style="font-size:14.5px; padding:8px 8px;">${(parseFloat(row.initial_stock) || 0).toLocaleString()}</td>`,
            getFooter: () => `<td data-col="opening"></td>`,
            getCsvValue: (r) => r.initial_stock || 0
        },
        'purchased': {
            key: 'purchased',
            title: 'Purch (+)',
            thHtml: '<th data-col="purchased" class="text-end qb-col-draggable" style="width:85px;" title="Purchased - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Purch (+)</th>',
            getCell: (row) => `<td data-col="purchased" class="text-end tabular-nums text-success fw-bold" style="font-size:14.5px; padding:8px 8px;">+${(parseFloat(row.purchased) || 0).toLocaleString()}</td>`,
            getFooter: () => `<td data-col="purchased"></td>`,
            getCsvValue: (r) => r.purchased || 0
        },
        'sold': {
            key: 'sold',
            title: 'Sold (-)',
            thHtml: '<th data-col="sold" class="text-end qb-col-draggable" style="width:85px;" title="Sold - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Sold (-)</th>',
            getCell: (row) => `<td data-col="sold" class="text-end tabular-nums text-danger fw-bold" style="font-size:14.5px; padding:8px 8px;">-${(parseFloat(row.sold) || 0).toLocaleString()}</td>`,
            getFooter: () => `<td data-col="sold"></td>`,
            getCsvValue: (r) => r.sold || 0
        },
        'saleret': {
            key: 'saleret',
            title: 'Sal Ret (+)',
            thHtml: '<th data-col="saleret" class="text-end qb-col-draggable" style="width:85px;" title="Sale Return - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Sal Ret (+)</th>',
            getCell: (row) => `<td data-col="saleret" class="text-end tabular-nums text-success fw-bold" style="font-size:14.5px; padding:8px 8px;">+${(parseFloat(row.returned_qty) || 0).toLocaleString()}</td>`,
            getFooter: () => `<td data-col="saleret"></td>`,
            getCsvValue: (r) => r.returned_qty || 0
        },
        'purret': {
            key: 'purret',
            title: 'Pur Ret (-)',
            thHtml: '<th data-col="purret" class="text-end qb-col-draggable" style="width:85px;" title="Purchase Return - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Pur Ret (-)</th>',
            getCell: (row) => `<td data-col="purret" class="text-end tabular-nums text-danger fw-bold" style="font-size:14.5px; padding:8px 8px;">-${(parseFloat(row.purch_returned_qty) || 0).toLocaleString()}</td>`,
            getFooter: () => `<td data-col="purret"></td>`,
            getCsvValue: (r) => r.purch_returned_qty || 0
        },
        'adjust': {
            key: 'adjust',
            title: 'Adjust',
            thHtml: '<th data-col="adjust" class="text-center qb-col-draggable" style="background:#fffbeb !important; color:#92400e !important; width:85px;" title="Stock Adjustments - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Adjust</th>',
            getCell: (row) => {
                let adjVal = parseFloat(row.adjustments) || 0;
                let adjBadge = '<span class="text-muted tabular-nums">—</span>';
                if (adjVal > 0) adjBadge = `<span class="acc-bal-flag dr tabular-nums">+${adjVal}</span>`;
                else if (adjVal < 0) adjBadge = `<span class="acc-bal-flag cr tabular-nums">${adjVal}</span>`;
                return `<td data-col="adjust" class="text-center qb-col-adjust" style="padding:8px 6px;">${adjBadge}</td>`;
            },
            getFooter: () => `<td data-col="adjust"></td>`,
            getCsvValue: (r) => r.adjustments || 0
        },
        'closing': {
            key: 'closing',
            title: 'Closing',
            thHtml: '<th data-col="closing" class="text-end fw-bold qb-col-draggable" style="background:#eef2ff !important; color:#1d4ed8 !important; width:95px;" title="Closing Stock - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Closing</th>',
            getCell: (row) => {
                let stockNum = parseFloat(row.balance) || 0;
                let stockColor = stockNum > 0 ? 'color:#15803d;' : (stockNum < 0 ? 'color:#b91c1c;' : 'color:#475569;');
                return `<td data-col="closing" class="text-end tabular-nums fw-bold qb-col-closing" style="${stockColor} font-size:15.5px; padding:8px 8px;">${escapeHtml(row.formatted_stock)}</td>`;
            },
            getFooter: () => `<td data-col="closing"></td>`,
            getCsvValue: (r) => r.balance || 0
        },
        'value': {
            key: 'value',
            title: 'Stock Value (Rs)',
            thHtml: '<th data-col="value" class="text-end qb-col-draggable" style="width:130px;" title="Total Stock Value in Rupees - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i>Stock Value</th>',
            getCell: (row) => `<td data-col="value" class="text-end tabular-nums fw-bold" style="color:#0f172a; font-size:15.5px; padding:8px 10px;">Rs ${(parseFloat(row.stock_value) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>`,
            getFooter: (totalVal) => `<td data-col="value" class="text-end tabular-nums fw-bold" style="color:#1d4ed8; font-size:16px; padding:9px 10px;">Rs ${(totalVal || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>`,
            getCsvValue: (r) => r.stock_value || 0
        },
        'history': {
            key: 'history',
            title: 'History',
            thHtml: '<th data-col="history" class="text-center qb-col-draggable" style="width:48px;" title="Movement History Timeline - Drag to reorder"><i class="fas fa-grip-vertical qb-col-grip"></i><i class="fas fa-history"></i></th>',
            getCell: (row) => `<td data-col="history" class="text-center" style="padding:8px 6px;"><button type="button" class="qb-history-btn view-history-btn" data-id="${row.id}" data-name="${escapeHtml(row.item_name)}" title="View Movement History Timeline"><i class="fas fa-history"></i></button></td>`,
            getFooter: () => `<td data-col="history"></td>`,
            getCsvValue: () => ''
        }
    };

    const defaultSummaryOrder = ['index', 'code', 'unique', 'name', 'category', 'unit', 'stock', 'cartons', 'avgcost', 'value', 'status', 'history'];
    const defaultLedgerOrder  = ['index', 'code', 'unique', 'name', 'opening', 'purchased', 'sold', 'saleret', 'purret', 'adjust', 'closing', 'value', 'history'];

    function getColOrder(mode) {
        const storageKey = 'kent_stock_report_cols_' + mode;
        const defaultOrder = (mode === 'summary') ? defaultSummaryOrder : defaultLedgerOrder;
        try {
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                const parsed = JSON.parse(saved);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    const validKeys = parsed.filter(k => defaultOrder.includes(k));
                    defaultOrder.forEach(k => {
                        if (!validKeys.includes(k)) validKeys.push(k);
                    });
                    return validKeys;
                }
            }
        } catch(e) {}
        return [...defaultOrder];
    }

    function saveColOrder(mode, order) {
        const storageKey = 'kent_stock_report_cols_' + mode;
        try {
            localStorage.setItem(storageKey, JSON.stringify(order));
        } catch(e) {}
    }

    // Initial Load
    fetchStockReport();

    function fetchStockReport() {
        $('#loader').show();
        $('#reportBody').empty();

        let mode = $('#report_mode').val() || 'summary';
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
                $('#kpiGrandStockValue').text('Rs ' + (parseFloat(res.grand_total) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                
                let totalStock = parseFloat(res.total_current_stock) || 0;
                $('#kpiTotalStock').text(totalStock.toLocaleString() + ' Units');
                
                let netAdj = parseFloat(res.total_adjustments_qty) || 0;
                let netAdjStr = (netAdj >= 0 ? '+' : '') + netAdj.toLocaleString() + ' Units';
                $('#kpiNetAdjustments').text(netAdjStr);
                
                $('#kpiSoldAmount').text('Rs ' + (parseFloat(res.total_sold_amount) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                renderTableBody(res.data, mode);
                renderPagination(res.pagination);
            },
            error: function (xhr, status, error) {
                $('#loader').hide();
                console.error('Stock report error:', error, xhr.responseText);
                const colOrder = getColOrder(mode);
                $('#reportBody').html(`<tr><td colspan="${colOrder.length}" class="text-center py-4 text-danger fw-semibold" style="font-size:13.5px;"><i class="fas fa-exclamation-triangle me-1"></i> Failed to load stock records. Please try again.</td></tr>`);
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
            links += `<button type="button" class="qb-page-btn page-btn" data-page="${pag.current_page - 1}"><i class="fas fa-chevron-left me-1"></i> Prev</button>`;
        }

        let startPage = Math.max(1, pag.current_page - 2);
        let endPage = Math.min(pag.last_page, pag.current_page + 2);

        if (startPage > 1) {
            links += `<button type="button" class="qb-page-btn page-btn" data-page="1">1</button>`;
            if (startPage > 2) links += `<span class="px-1 text-muted">...</span>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === pag.current_page) {
                links += `<button type="button" class="qb-page-btn active page-btn" data-page="${i}">${i}</button>`;
            } else {
                links += `<button type="button" class="qb-page-btn page-btn" data-page="${i}">${i}</button>`;
            }
        }

        if (endPage < pag.last_page) {
            if (endPage < pag.last_page - 1) links += `<span class="px-1 text-muted">...</span>`;
            links += `<button type="button" class="qb-page-btn page-btn" data-page="${pag.last_page}">${pag.last_page}</button>`;
        }
        
        if (pag.current_page < pag.last_page) {
            links += `<button type="button" class="qb-page-btn page-btn" data-page="${pag.current_page + 1}">Next <i class="fas fa-chevron-right ms-1"></i></button>`;
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

        const colOrder = getColOrder(mode);
        const colDef = (mode === 'summary') ? summaryColumns : ledgerColumns;

        let tr = $('<tr></tr>');
        colOrder.forEach(key => {
            if (colDef[key]) {
                tr.append(colDef[key].thHtml);
            }
        });
        thead.append(tr);

        initDraggableColumns();
        initColumnResizing(mode);
    }

    function initDraggableColumns() {
        const thead = document.getElementById('tableHeader');
        if (!thead) return;
        const headerRow = thead.querySelector('tr');
        if (!headerRow) return;

        const ths = headerRow.querySelectorAll('th.qb-col-draggable');
        ths.forEach(th => {
            th.setAttribute('draggable', 'true');

            th.ondragstart = function (e) {
                // If user clicked on resizer handle or resizing is in progress, cancel drag
                if (e.target.classList.contains('col-resizer') || e.target.closest('.col-resizer') || $('body').hasClass('is-col-resizing')) {
                    e.preventDefault();
                    return false;
                }
                window.draggedStockTh = this;
                this.classList.add('th-dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', this.getAttribute('data-col'));
            };

            th.ondragend = function () {
                this.classList.remove('th-dragging');
                ths.forEach(t => t.classList.remove('th-drag-over-left', 'th-drag-over-right'));
                window.draggedStockTh = null;
            };

            th.ondragover = function (e) {
                if ($('body').hasClass('is-col-resizing')) return;
                e.preventDefault();
                if (!window.draggedStockTh || window.draggedStockTh === this) return;
                e.dataTransfer.dropEffect = 'move';

                const rect = this.getBoundingClientRect();
                const relX = e.clientX - rect.left;
                ths.forEach(t => t.classList.remove('th-drag-over-left', 'th-drag-over-right'));
                if (relX < rect.width / 2) {
                    this.classList.add('th-drag-over-left');
                } else {
                    this.classList.add('th-drag-over-right');
                }
            };

            th.ondragleave = function () {
                this.classList.remove('th-drag-over-left', 'th-drag-over-right');
            };

            th.ondrop = function (e) {
                e.preventDefault();
                ths.forEach(t => t.classList.remove('th-drag-over-left', 'th-drag-over-right', 'th-dragging'));
                const srcTh = window.draggedStockTh;
                if (!srcTh || srcTh === this) return;

                const rect = this.getBoundingClientRect();
                const relX = e.clientX - rect.left;
                const insertBefore = relX < rect.width / 2;

                if (insertBefore) {
                    headerRow.insertBefore(srcTh, this);
                } else {
                    headerRow.insertBefore(srcTh, this.nextSibling);
                }

                // Extract new column sequence from header
                const newColOrder = [];
                headerRow.querySelectorAll('th').forEach(h => {
                    const colKey = h.getAttribute('data-col');
                    if (colKey) newColOrder.push(colKey);
                });

                const mode = $('#report_mode').val() || 'summary';
                saveColOrder(mode, newColOrder);

                // Instantly reorder all tbody rows and footer
                syncTableBodyColumnOrder(newColOrder);
                syncTableFooterColumnOrder(newColOrder);
            };
        });
    }

    // ==========================================================
    // COLUMN RESIZING WITH DRAG HANDLE & LOCALSTORAGE CACHING
    // ==========================================================

    function getSavedColWidths(mode) {
        const key = 'kent_stock_report_widths_' + mode;
        try {
            const saved = localStorage.getItem(key);
            return saved ? JSON.parse(saved) : {};
        } catch (e) {
            return {};
        }
    }

    function saveColWidths(mode, widths) {
        const key = 'kent_stock_report_widths_' + mode;
        try {
            localStorage.setItem(key, JSON.stringify(widths));
        } catch (e) {}
    }

    function initColumnResizing(mode) {
        const $table = $('#stockTable');
        if (!$table.length) return;
        const tableEl = $table[0];
        const $container = $table.closest('.table-responsive');
        const containerEl = $container[0];

        let $guide = $container.find('#colResizeGuide');
        if (!$guide.length) {
            $guide = $('<div id="colResizeGuide" class="col-resize-guide"></div>');
            $container.prepend($guide);
        }
        const guideEl = $guide[0];

        const savedWidths = getSavedColWidths(mode);
        const $ths = $('#tableHeader tr th');
        let totalCalculatedWidth = 0;

        $ths.each(function(i) {
            const $th = $(this);
            const thEl = $th[0];
            const colKey = $th.attr('data-col') || ('col_' + i);

            // Apply saved width if exists
            if (savedWidths[colKey]) {
                const w = parseInt(savedWidths[colKey], 10);
                thEl.style.width = w + 'px';
                thEl.style.minWidth = w + 'px';
                totalCalculatedWidth += w;
            } else {
                const currentW = Math.round($th.outerWidth());
                if (currentW > 0) {
                    thEl.style.width = currentW + 'px';
                    thEl.style.minWidth = currentW + 'px';
                    totalCalculatedWidth += currentW;
                }
            }

            // Remove existing resizer if any to avoid duplication
            $th.find('.col-resizer').remove();

            // Create resizer handle
            const $resizer = $('<div class="col-resizer" title="Drag to adjust column width (Double-click to reset)"></div>');
            $th.append($resizer);

            $resizer.on('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const startX = e.pageX;
                const startW = Math.round($th.outerWidth());
                const startTableW = Math.round($table.outerWidth());
                const cachedParentW = Math.round(containerEl.clientWidth);
                const containerRect = containerEl.getBoundingClientRect();
                const containerScrollLeft = containerEl.scrollLeft;
                let hasMoved = false;

                $resizer.addClass('is-resizing');
                $('body').addClass('is-col-resizing');
                $th.attr('draggable', 'false');

                // Initialize guide position and display
                const initialGuideX = e.clientX - containerRect.left + containerScrollLeft;
                guideEl.style.left = initialGuideX + 'px';
                guideEl.style.height = tableEl.offsetHeight + 'px';
                guideEl.style.display = 'block';

                const minWidth = (colKey === 'index' || colKey === 'history') ? 38 : (colKey === 'unit' ? 50 : 65);

                let rafId = null;
                let targetWidth = startW;
                let targetTableWidth = startTableW;
                let targetGuideX = initialGuideX;

                function onMouseMove(moveEvent) {
                    const diff = moveEvent.pageX - startX;
                    if (Math.abs(diff) > 1) {
                        hasMoved = true;
                    }

                    const newW = Math.max(minWidth, Math.round(startW + diff));
                    if (newW === targetWidth) return;
                    targetWidth = newW;
                    const actualDiff = newW - startW;
                    targetTableWidth = Math.max(cachedParentW, startTableW + actualDiff);

                    targetGuideX = moveEvent.clientX - containerRect.left + containerEl.scrollLeft;

                    if (!rafId) {
                        rafId = requestAnimationFrame(function() {
                            rafId = null;
                            // Ultra-fast hardware-accelerated direct style updates (Zero layout thrashing!)
                            thEl.style.width = targetWidth + 'px';
                            thEl.style.minWidth = targetWidth + 'px';
                            tableEl.style.width = targetTableWidth + 'px';
                            tableEl.style.minWidth = targetTableWidth + 'px';
                            guideEl.style.left = targetGuideX + 'px';
                        });
                    }
                }

                function onMouseUp() {
                    $(document).off('mousemove', onMouseMove);
                    $(document).off('mouseup', onMouseUp);

                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }

                    guideEl.style.display = 'none';
                    $resizer.removeClass('is-resizing');
                    $('body').removeClass('is-col-resizing');
                    $th.attr('draggable', 'true');

                    if (hasMoved) {
                        const currentWidths = getSavedColWidths(mode);
                        currentWidths[colKey] = targetWidth;
                        saveColWidths(mode, currentWidths);
                    }
                }

                $(document).on('mousemove', onMouseMove);
                $(document).on('mouseup', onMouseUp);
            });

            // Double click resets column width
            $resizer.on('dblclick', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const currentWidths = getSavedColWidths(mode);
                delete currentWidths[colKey];
                saveColWidths(mode, currentWidths);

                thEl.style.width = '';
                thEl.style.minWidth = '';
                tableEl.style.width = '100%';
                tableEl.style.minWidth = '100%';

                setTimeout(function() {
                    initColumnResizing(mode);
                }, 40);
            });
        });

        if (totalCalculatedWidth > 0) {
            const parentWidth = Math.round(containerEl.clientWidth);
            const finalTableWidth = Math.max(parentWidth, totalCalculatedWidth);
            tableEl.style.width = finalTableWidth + 'px';
            tableEl.style.minWidth = finalTableWidth + 'px';
        }
    }

    function syncTableBodyColumnOrder(colOrder) {
        const tbody = document.getElementById('reportBody');
        if (!tbody) return;
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(tr => {
            const cellMap = {};
            Array.from(tr.children).forEach(td => {
                const k = td.getAttribute('data-col');
                if (k) cellMap[k] = td;
            });
            colOrder.forEach(k => {
                if (cellMap[k]) tr.appendChild(cellMap[k]);
            });
        });
    }

    function syncTableFooterColumnOrder(colOrder) {
        const tfoot = document.getElementById('tableFooter');
        if (!tfoot) return;
        const rows = tfoot.querySelectorAll('tr');
        rows.forEach(tr => {
            const cellMap = {};
            Array.from(tr.children).forEach(td => {
                const k = td.getAttribute('data-col');
                if (k) cellMap[k] = td;
            });
            colOrder.forEach(k => {
                if (cellMap[k]) tr.appendChild(cellMap[k]);
            });
        });
    }

    function renderTableBody(data, mode) {
        let tbody   = $('#reportBody');
        let tfooter = $('#tableFooter');
        tbody.empty();
        tfooter.empty();

        const colOrder = getColOrder(mode);
        const colDef = (mode === 'summary') ? summaryColumns : ledgerColumns;

        if (!data || data.length === 0) {
            tbody.append(`<tr><td colspan="${colOrder.length}" class="text-center py-4 text-muted fw-semibold" style="font-size:13.5px;">No stock data found matching the selected filters.</td></tr>`);
            return;
        }

        let totalValue = 0;

        $.each(data, function (i, row) {
            totalValue += (parseFloat(row.stock_value) || 0);

            let rowHtml = '<tr>';
            colOrder.forEach(key => {
                if (colDef[key]) {
                    rowHtml += colDef[key].getCell(row, i);
                }
            });
            rowHtml += '</tr>';
            tbody.append(rowHtml);
        });

        // Table Footer Summary
        let footerHtml = '<tr>';
        colOrder.forEach(key => {
            if (colDef[key]) {
                footerHtml += colDef[key].getFooter(totalValue);
            }
        });
        footerHtml += '</tr>';
        tfooter.append(footerHtml);
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
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
                            <td class="ps-3 text-dark tabular-nums fw-semibold" style="font-size:14px;">${m.date}</td>
                            <td><span class="badge bg-${m.type_badge} px-2 py-1" style="font-family:'Inter',system-ui,sans-serif; font-size:13px; font-weight:700;">${m.type}</span></td>
                            <td><span class="acc-badge-code" style="font-size:13px;">${m.ref_type}</span></td>
                            <td class="text-center tabular-nums fw-bold ${qtyColor}" style="font-size:14.5px;">${qtyVal > 0 ? '+' : ''}${m.qty}</td>
                            <td style="font-size:14px; color:#334155;">${m.note || '—'}</td>
                        </tr>`;
                        tbody.append(row);
                    });
                } else {
                    tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted fw-semibold" style="font-size:14px;">No stock movements recorded for this item yet.</td></tr>');
                }
            },
            error: function () {
                tbody.html('<tr><td colspan="5" class="text-center py-4 text-danger fw-semibold" style="font-size:14px;">Failed to fetch movement history.</td></tr>');
            }
        });
    });

    // Export CSV with Dynamic Column Arrangement
    $('#btnExportCsv, #btnExportCsvTop').on('click', function () {
        if (!currentReportData || currentReportData.length === 0) {
            alert('No report data to export.');
            return;
        }

        let mode = $('#report_mode').val() || 'summary';
        const colOrder = getColOrder(mode);
        const colDef = (mode === 'summary') ? summaryColumns : ledgerColumns;

        // Build CSV header from current column order
        let headerRow = colOrder
            .filter(k => k !== 'history')
            .map(k => '"' + (colDef[k].title || '').replace(/"/g, '""') + '"')
            .join(',');
        let csv = headerRow + '\n';

        $.each(currentReportData, function (i, r) {
            let rowVals = colOrder
                .filter(k => k !== 'history')
                .map(k => {
                    let v = colDef[k].getCsvValue(r, i);
                    return '"' + String(v === null || v === undefined ? '' : v).replace(/"/g, '""') + '"';
                });
            csv += rowVals.join(',') + '\n';
        });

        let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `Item_Stock_Report_${mode}_` + new Date().toISOString().slice(0,10) + ".csv";
        link.click();
    });

});
</script>
@endsection
