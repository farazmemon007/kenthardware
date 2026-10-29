@extends('admin_panel.layout.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/css/bootstrap-icons.min.css') }}">

<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "ALL VOUCHERS HISTORY" THEME
       Pixel-Perfect High-Density Layout Matching Accounts & Sales
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

    /* Universal Space Reset to Eliminate Unnecessary Scrolling */
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
    .qb-sub-ribbon,
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

    /* Helper Utilities */
    .fw-bold, .font-weight-bold { font-weight: 700 !important; }
    .fw-bolder { font-weight: 800 !important; }
    .fw-semibold { font-weight: 600 !important; }
    .font-monospace, .qb-mono { font-family: 'JetBrains Mono', Consolas, monospace !important; }
    .text-end, .text-right { text-align: right !important; }
    .text-start, .text-left { text-align: left !important; }
    .text-center { text-align: center !important; }

    /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS & ERP Strip) */
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
        white-space: nowrap;
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

    /* 2. Sub-Header Ribbon: Filter Status Tabs (Left) & Financial KPIs (Right) */
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

    /* QuickBooks Tab Pills */
    .qb-status-tabs {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }
    .qb-tab-pill {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid #a8bed8;
        border-radius: 4px;
        padding: 2px 9px;
        font-size: 11px;
        font-weight: 700;
        color: #193452;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        height: 24px;
        cursor: pointer;
        transition: all 0.12s ease;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        user-select: none;
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
    .qb-tab-pill.active-danger {
        background: #ffffff !important;
        border: 1.5px solid #b91c1c !important;
        color: #b91c1c !important;
        font-weight: 800;
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

    /* Financial KPI Chips */
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
        padding: 4px 8px;
        margin-bottom: 5px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    .qb-filter-form {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
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
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding: 1px 6px !important;
        background-color: #ffffff;
    }
    .qb-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
    }

    /* Datepicker Column Styling */
    .qb-filter-col.date-col {
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
        flex: 0 0 105px !important;
    }
    .qb-filter-col.date-col input {
        width: 105px !important;
        height: 26px !important;
        font-size: 11px !important;
        padding: 2px 6px !important;
    }

    /* Filter Action Buttons */
    .qb-btn-search {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        height: 26px !important;
        padding: 0 12px !important;
        font-size: 11px !important;
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
        height: 26px !important;
        padding: 0 10px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none !important;
        cursor: pointer;
        white-space: nowrap;
    }
    .qb-btn-reset:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
    }

    /* 4. Table Card (Exact QuickBooks / Odoo Grid Replica) */
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
        font-size: 12px !important;
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
        padding: 5px 8px !important;
        font-size: 12px !important;
        color: #0f172a !important;
        vertical-align: middle;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* Row Hover: Pastel Mint Green */
    .qb-table tbody tr {
        cursor: pointer;
        transition: background-color 0.1s ease-in-out;
    }
    .qb-table tbody tr:hover td,
    .qb-table tbody tr:hover th {
        background-color: #d5eed7 !important;
        color: #0f172a !important;
    }
    .qb-table tbody tr.qb-active-row td {
        background-color: #d5eed7 !important;
        color: #0f172a !important;
    }

    /* Badge & Tag Elements */
    .acc-badge-code {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #0f2744 !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 2px 7px !important;
        display: inline-block;
        letter-spacing: 0.3px;
    }

    .qb-badge {
        font-size: 10px !important;
        font-weight: 700 !important;
        padding: 2px 7px !important;
        border-radius: 3px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        letter-spacing: 0.2px !important;
        text-transform: uppercase !important;
    }
    .qb-badge-expense {
        background: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fca5a5 !important;
    }
    .qb-badge-in {
        background: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
    }
    .qb-badge-out {
        background: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fde68a !important;
    }

    .acc-badge-party-customer {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        display: inline-block;
        text-transform: uppercase;
    }
    .acc-badge-party-vendor {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        display: inline-block;
        text-transform: uppercase;
    }
    .acc-badge-party-account {
        background: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        display: inline-block;
        text-transform: uppercase;
    }

    /* Compact Actions Group */
    .qb-action-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        justify-content: center;
    }
    .qb-action-btn {
        width: 26px;
        height: 26px;
        min-width: 26px;
        min-height: 26px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        font-size: 11px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.12s ease;
        text-decoration: none !important;
    }
    .qb-action-print {
        background: #f0f7ff;
        border-color: #bae6fd;
        color: #0284c7;
    }
    .qb-action-print:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff !important;
    }
    .qb-action-delete {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }
    .qb-action-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #ffffff !important;
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
    .dataTables_wrapper .dataTables_paginate .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #193452 !important;
        border: 1px solid #b2c5dc !important;
        padding: 2px 7px !important;
        font-size: 11px !important;
        border-radius: 3px !important;
        margin: 0 1px !important;
        background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
        font-weight: 600 !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border-color: #15457a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
    }

    /* Remark Preview Badge */
    .qb-remark-preview {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #1e3a5f;
        cursor: pointer;
        transition: all 0.15s ease;
        max-width: 100%;
        user-select: none;
        outline: none;
        line-height: 1.4;
        text-align: left;
    }
    .qb-remark-preview:hover,
    .qb-remark-preview:focus {
        background: #e0f2fe;
        border-color: #38bdf8;
        color: #0284c7;
        box-shadow: 0 1px 3px rgba(2, 132, 199, 0.2);
    }

    /* Remark Hover Popover Message Box */
    .qb-remark-popover {
        position: fixed;
        z-index: 99999;
        width: 320px;
        max-width: calc(100vw - 30px);
        background: #ffffff;
        border: 1px solid #7dd3fc;
        border-radius: 6px;
        box-shadow: 0 10px 25px -4px rgba(15, 39, 68, 0.28), 0 4px 8px -2px rgba(15, 39, 68, 0.12);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-4px);
        transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s;
        pointer-events: none;
    }
    .qb-remark-popover.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
    .qb-popover-arrow {
        position: absolute;
        top: -6px;
        left: 20px;
        width: 10px;
        height: 10px;
        background: #18385e;
        border-top: 1px solid #7dd3fc;
        border-left: 1px solid #7dd3fc;
        transform: rotate(45deg);
        z-index: 3;
    }
    .qb-remark-popover.popover-above .qb-popover-arrow {
        top: auto;
        bottom: -6px;
        background: #ffffff;
        border-top: none;
        border-left: none;
        border-bottom: 1px solid #7dd3fc;
        border-right: 1px solid #7dd3fc;
    }
    .qb-popover-inner {
        position: relative;
        z-index: 2;
        background: #ffffff;
        border-radius: 5px;
        overflow: hidden;
    }
    .qb-popover-header {
        background: linear-gradient(180deg, #18385e 0%, #0f2744 100%);
        border-bottom: 2px solid #0077c5;
        padding: 5px 9px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
    }
    .qb-popover-title-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .qb-popover-voucher {
        font-size: 11px;
        font-weight: 800;
        color: #ffffff;
        font-family: 'JetBrains Mono', Consolas, monospace;
        letter-spacing: 0.3px;
    }
    .qb-popover-badge {
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        background: #e0f2fe;
        color: #0369a1;
    }
    .qb-popover-badge.type-expense {
        background: #fee2e2;
        color: #991b1b;
    }
    .qb-popover-badge.type-payment_in {
        background: #dcfce7;
        color: #166534;
    }
    .qb-popover-badge.type-payment_out {
        background: #fef3c7;
        color: #92400e;
    }
    .qb-popover-copy-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 600;
        padding: 1px 6px;
        cursor: pointer;
        line-height: 1.3;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        flex-shrink: 0;
    }
    .qb-popover-copy-btn:hover {
        background: #2563eb;
        border-color: #60a5fa;
        color: #ffffff;
    }
    .qb-popover-body {
        padding: 8px 10px;
        font-size: 11.5px;
        color: #0f2744;
        background: #ffffff;
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
        max-height: 220px;
        overflow-y: auto;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Print Stylesheet */
    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 12px !important;
        }
        .rt_nav_header,
        .qb-win-header,
        .qb-sub-ribbon,
        .qb-filter-bar,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_info,
        .qb-action-group,
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
        .print-header {
            display: block !important;
            margin-bottom: 16px;
            border-bottom: 2px solid #0f2744;
            padding-bottom: 10px;
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

            {{-- Row 1: Top Window Title Bar (Navy Blue Gradient - Exact POS & ERP Strip) --}}
            <div class="qb-win-header">
                <div class="qb-win-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>All Vouchers</span>
                    <span class="qb-win-sub d-none d-sm-inline">/ Unified History</span>
                </div>

                {{-- Top Action Buttons (Glossy QuickBooks 3D Buttons) --}}
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-blue d-none d-md-inline-flex" onclick="window.print()" title="Print List">
                        <i class="fas fa-print"></i> Print List
                    </button>
                    <button type="button" class="qb-btn-gray" id="btnRefreshTable" title="Reload Data">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>

                    <div class="dropdown">
                        <button class="qb-btn-green dropdown-toggle" type="button" id="newVoucherDropdown" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false" title="Create New Voucher">
                            <i class="fas fa-plus"></i> + New Voucher
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="newVoucherDropdown" style="border: 1px solid #aebfd4 !important; border-radius: 4px !important; box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important; padding: 4px 0 !important; font-size: 11.5px !important; min-width: 230px;">
                            <li><a class="dropdown-item py-1 text-primary fw-bold" href="{{ route('vouchers.create') }}"><i class="fas fa-plus-circle text-primary me-2"></i> Create Voucher (Unified)</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @can('expense.voucher.create')
                                <li><a class="dropdown-item py-1" href="{{ route('expense_vochers') }}"><i class="fas fa-file-invoice text-danger me-2"></i> Add Expense Voucher</a></li>
                            @endcan
                            @can('receipts.voucher.create')
                                <li><a class="dropdown-item py-1" href="{{ route('recepit_vochers') }}"><i class="fas fa-receipt text-success me-2"></i> Add Receipt Voucher (Payment In)</a></li>
                            @endcan
                            @can('payment.voucher.create')
                                <li><a class="dropdown-item py-1" href="{{ route('Payment_vochers') }}"><i class="fas fa-hand-holding-dollar text-warning me-2"></i> Add Payment Voucher (Payment Out)</a></li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Print Header (Paperwork Statement) --}}
            <div class="print-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 style="margin: 0; font-size: 20px; font-weight: 900; color: #18385e;">
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
                        <div style="font-size: 16px; font-weight: 800; color: #18385e; text-transform: uppercase;">All Vouchers History</div>
                        <div style="font-size: 11px; color: #64748b;">
                            Printed On: <strong>{{ date('d/m/Y h:i A') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row 2: Sub-Header Ribbon: Status Tabs (Left) & Financial KPIs (Right) --}}
            <div class="qb-sub-ribbon">
                {{-- Status Filter Tabs (QuickBooks Tab Style) --}}
                <div class="qb-status-tabs" id="typeFilterGroup">
                    <button type="button" class="qb-tab-pill active" data-type="all">
                        <i class="fas fa-list-ul"></i> All Vouchers
                    </button>
                    <button type="button" class="qb-tab-pill" data-type="expense">
                        <i class="fas fa-arrow-up text-danger"></i> Expense
                    </button>
                    <button type="button" class="qb-tab-pill" data-type="payment_in">
                        <i class="fas fa-arrow-right text-success"></i> Payment In (Receipts)
                    </button>
                    <button type="button" class="qb-tab-pill" data-type="payment_out">
                        <i class="fas fa-arrow-left text-warning"></i> Payment Out (Payments)
                    </button>
                </div>

                {{-- Financial KPI Chips (Exact Ledger/Sales Strip) --}}
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip" title="Total Vouchers Amount">
                        <span class="chip-label">Total:</span>
                        <span class="chip-val text-primary" id="sumTotal">0.00</span>
                    </div>
                    <div class="qb-kpi-chip" title="Total Expense Amount">
                        <span class="chip-label text-danger"><i class="fas fa-arrow-up text-danger me-1"></i>Expense:</span>
                        <span class="chip-val text-danger" id="sumExpense">0.00</span>
                    </div>
                    <div class="qb-kpi-chip" title="Total Payment In (Receipts)">
                        <span class="chip-label text-success"><i class="fas fa-arrow-right text-success me-1"></i>Payment In:</span>
                        <span class="chip-val text-success" id="sumPaymentIn">0.00</span>
                    </div>
                    <div class="qb-kpi-chip" title="Total Payment Out (Payments)">
                        <span class="chip-label text-warning"><i class="fas fa-arrow-left text-warning me-1"></i>Payment Out:</span>
                        <span class="chip-val text-warning" id="sumPaymentOut">0.00</span>
                    </div>
                </div>
            </div>

            {{-- Row 3: Filter Bar (Single Line QuickBooks Strip) --}}
            <div class="qb-filter-bar">
                <form id="voucherFilterForm" class="qb-filter-form" autocomplete="off" onsubmit="return false;">
                    <div class="qb-filter-col" style="width: 90px; flex: 0 0 90px;">
                        <select id="quick_filter" class="form-select qb-input" style="width: 100%;">
                            <option value="custom">Custom</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="qb-filter-col date-col">
                        <input type="text" class="form-control qb-input datepicker-custom" id="filterFromDate" placeholder="From Date">
                    </div>
                    <div class="qb-filter-col date-col">
                        <input type="text" class="form-control qb-input datepicker-custom" id="filterToDate" placeholder="To Date">
                    </div>
                    <div class="qb-filter-col" style="width: 110px; flex: 0 0 110px;">
                        <select class="form-select qb-input" id="filterPartyType" style="width: 100%;">
                            <option value="">All Parties</option>
                            <option value="customer">Customer</option>
                            <option value="vendor">Vendor</option>
                        </select>
                    </div>
                    <div class="qb-filter-col" style="flex: 1 1 180px; min-width: 150px; max-width: 250px;">
                        <select class="form-select qb-input" id="filterAccount" style="width: 100%;">
                            <option value="">All Accounts</option>
                            @if(isset($accounts))
                                @foreach($accounts as $ac)
                                    <option value="{{ $ac->id }}">{{ $ac->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="qb-filter-col" style="width: 85px; flex: 0 0 85px;">
                        <input type="number" step="0.01" class="form-control qb-input" id="filterMinAmount" placeholder="Min Amt">
                    </div>
                    <div class="qb-filter-col" style="width: 85px; flex: 0 0 85px;">
                        <input type="number" step="0.01" class="form-control qb-input" id="filterMaxAmount" placeholder="Max Amt">
                    </div>
                    <div class="qb-filter-col d-flex align-items-center gap-1" style="flex: 0 0 auto;">
                        <button type="button" class="btn qb-btn-search" id="applyFilters" title="Apply Filters">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" class="btn qb-btn-reset" id="resetFilters" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            {{-- Row 4: Table Card (Exact QuickBooks / Odoo Grid Replica) --}}
            <div class="qb-table-card">
                <div class="table-responsive">
                    <table id="voucherHistoryTable" class="table qb-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Voucher No</th>
                                <th class="text-center" style="width: 110px;">Type</th>
                                <th style="width: 105px;">Date</th>
                                <th style="min-width: 150px;">Party</th>
                                <th style="min-width: 130px;">Details</th>
                                <th class="text-end" style="width: 130px;">Amount</th>
                                <th style="min-width: 130px;">Remarks</th>
                                <th class="text-center" style="width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            {{-- Hover Message Box for Remarks --}}
            <div id="qbRemarkPopover" class="qb-remark-popover">
                <div class="qb-popover-arrow"></div>
                <div class="qb-popover-inner">
                    <div class="qb-popover-header">
                        <div class="qb-popover-title-wrap">
                            <i class="fas fa-comment-alt text-info" style="font-size: 11px;"></i>
                            <span class="qb-popover-voucher" id="qbPopoverVoucher">-</span>
                            <span class="qb-popover-badge" id="qbPopoverType"></span>
                        </div>
                        <button type="button" class="qb-popover-copy-btn" id="qbBtnCopyPopover" title="Copy Remarks">
                            <i class="far fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="qb-popover-body" id="qbPopoverText"></div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function() {

    let table = $('#voucherHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: "<'d-flex justify-content-between align-items-center mb-1'<'d-flex align-items-center gap-1 font-monospace'l><'d-flex align-items-center'f>>" +
             "<'row'<'col-12'tr>>" +
             "<'d-flex justify-content-between align-items-center mt-1'<'text-muted small'i><'d-flex justify-content-end'p>>",
        ajax: {
            url: '{{ route("voucher.history.data") }}',
            data: function(d) {
                d.type = $('#typeFilterGroup .qb-tab-pill.active').data('type') || 'all';
                d.from_date = $('#filterFromDate').val();
                d.to_date = $('#filterToDate').val();
                d.party_type = $('#filterPartyType').val();
                d.account_id = $('#filterAccount').val();
                d.min_amount = $('#filterMinAmount').val();
                d.max_amount = $('#filterMaxAmount').val();
            },
            dataSrc: function(json) {
                if (json.summary) {
                    $('#sumTotal').text(numberFormat(json.summary.total_amount));
                    $('#sumExpense').text(numberFormat(json.summary.total_expense));
                    $('#sumPaymentIn').text(numberFormat(json.summary.total_payment_in));
                    $('#sumPaymentOut').text(numberFormat(json.summary.total_payment_out));
                }
                return json.data;
            }
        },
        columns: [
            { 
                data: 'voucher_no',
                render: function(v, t, r) {
                    return '<span class="acc-badge-code">' + (v || '-') + '</span>';
                }
            },
            {
                data: 'type_label',
                className: 'text-center',
                render: function(v, t, r) {
                    if (r.source === 'expense') {
                        return '<span class="qb-badge qb-badge-expense"><i class="fas fa-arrow-up text-danger"></i> Expense</span>';
                    } else if (r.source === 'payment_in') {
                        return '<span class="qb-badge qb-badge-in"><i class="fas fa-arrow-right text-success"></i> Payment In</span>';
                    } else if (r.source === 'payment_out') {
                        return '<span class="qb-badge qb-badge-out"><i class="fas fa-arrow-left text-warning"></i> Payment Out</span>';
                    }
                    return '<span class="qb-badge bg-light text-dark">' + (v || '-') + '</span>';
                }
            },
            { 
                data: 'date',
                render: function(v) {
                    return '<span class="qb-mono text-muted" style="font-size:11.5px; font-weight:600;"><i class="far fa-calendar-alt me-1 text-secondary"></i>' + (v || '-') + '</span>';
                }
            },
            {
                data: null,
                render: function(r) {
                    let pName = r.party_name || '-';
                    let pBadge = '';
                    if (r.party_type_label === 'Customer') {
                        pBadge = '<span class="acc-badge-party-customer ms-1">Customer</span>';
                    } else if (r.party_type_label === 'Vendor') {
                        pBadge = '<span class="acc-badge-party-vendor ms-1">Vendor</span>';
                    } else if (r.party_type_label === 'Account') {
                        pBadge = '<span class="acc-badge-party-account ms-1">Account</span>';
                    }
                    return '<div class="d-flex align-items-center flex-wrap gap-1"><span class="fw-bold text-dark" style="font-size:12px;">' + pName + '</span>' + pBadge + '</div>';
                }
            },
            {
                data: 'detail',
                render: function(v) { 
                    return '<span class="text-secondary" style="font-size:11.5px; font-weight:500;">' + (v || '-') + '</span>'; 
                }
            },
            { 
                data: 'amount', 
                className: 'text-end',
                render: function(v) { 
                    return '<span class="qb-mono fw-bolder text-dark" style="font-size:12.5px;">Rs. ' + numberFormat(v) + '</span>'; 
                } 
            },
            { 
                data: 'remarks', 
                render: function(v, t, r) { 
                    let raw = (v || '').trim();
                    if (!raw || raw === '-') {
                        return '<span class="text-muted">-</span>';
                    }
                    let words = raw.split(/\s+/).filter(Boolean);
                    let isLong = words.length > 2;
                    let preview = isLong ? (words.slice(0, 2).join(' ') + '...') : raw;

                    let encRemark = encodeURIComponent(raw);
                    let safeVoucher = escapeHtml(r.voucher_no || '');
                    let safeType = escapeHtml(r.type_label || '');
                    let safeSource = escapeHtml(r.source || '');

                    return '<button type="button" class="qb-remark-preview" data-remark="' + encRemark + '" data-voucherno="' + safeVoucher + '" data-type="' + safeType + '" data-source="' + safeSource + '">' +
                           escapeHtml(preview) + ' <i class="fas fa-comment-dots text-primary ms-1" style="font-size:10px;"></i>' +
                           '</button>';
                } 
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(r) {
                    var btns = [];
                    if (r.print_url) {
                        btns.push({ 
                            icon: 'fas fa-print', 
                            cls: 'qb-action-print', 
                            url: r.print_url, 
                            title: 'Print Voucher', 
                            target: '_blank' 
                        });
                    }
                    if (r.delete_url) {
                        btns.push({ 
                            icon: 'fas fa-trash-alt', 
                            cls: 'qb-action-delete', 
                            url: r.delete_url, 
                            title: 'Delete Voucher', 
                            isDelete: true, 
                            deleteMethod: r.delete_method || 'DELETE' 
                        });
                    }
                    if (btns.length === 0) return '-';
                    var html = '<div class="qb-action-group">';
                    $.each(btns, function(i, b) {
                        if (b.isDelete) {
                            html += '<button type="button" class="qb-action-btn ' + b.cls + '" title="' + b.title + '" data-delete-url="' + b.url + '" data-delete-method="' + b.deleteMethod + '"><i class="' + b.icon + '"></i></button>';
                        } else {
                            var tgt = b.target ? ' target="' + b.target + '"' : '';
                            html += '<a href="' + b.url + '" class="qb-action-btn ' + b.cls + '" title="' + b.title + '"' + tgt + '><i class="' + b.icon + '"></i></a>';
                        }
                    });
                    html += '</div>';
                    return html;
                }
            }
        ],
        order: [[2, 'desc']],
        language: {
            searchPlaceholder: 'Search vouchers...',
            search: '',
            processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...'
        }
    });

    // Active row highlight on click (Odoo / QuickBooks signature)
    $(document).on('click', '#voucherHistoryTable tbody tr', function(e) {
        if ($(e.target).closest('.dropdown, a, button').length > 0) return;
        $('#voucherHistoryTable tbody tr').removeClass('qb-active-row');
        $(this).addClass('qb-active-row');
    });

    // Quick Filter Logic (Daily, Weekly, Monthly, Yearly)
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
            let diffToMonday = today.getDate() - day + (day === 0 ? -6 : 1);
            start = new Date(today.setDate(diffToMonday));
            end = new Date(start);
            end.setDate(start.getDate() + 6);
        } else if (val === 'monthly') {
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        } else if (val === 'yearly') {
            start = new Date(today.getFullYear(), 0, 1);
            end = new Date(today.getFullYear(), 11, 31);
        }

        let formatDate = function(d) {
            let year = d.getFullYear();
            let month = String(d.getMonth() + 1).padStart(2, '0');
            let day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        let startStr = formatDate(start);
        let endStr = formatDate(end);

        let pickerFrom = document.getElementById('filterFromDate') ? document.getElementById('filterFromDate')._flatpickr : null;
        let pickerTo = document.getElementById('filterToDate') ? document.getElementById('filterToDate')._flatpickr : null;

        if (pickerFrom) { pickerFrom.setDate(startStr, true); } else { $('#filterFromDate').val(startStr); }
        if (pickerTo) { pickerTo.setDate(endStr, true); } else { $('#filterToDate').val(endStr); }

        table.ajax.reload();
    });

    // Type filter tabs (All, Expense, Payment In, Payment Out)
    $('#typeFilterGroup').on('click', '.qb-tab-pill', function() {
        $('#typeFilterGroup .qb-tab-pill').removeClass('active active-danger active-success active-warning');
        let type = $(this).data('type');
        $(this).addClass('active');
        if (type === 'expense') $(this).addClass('active-danger');
        else if (type === 'payment_in') $(this).addClass('active-success');
        else if (type === 'payment_out') $(this).addClass('active-warning');
        table.ajax.reload();
    });

    // Apply & Reset Filters
    $('#applyFilters').on('click', function() { table.ajax.reload(); });
    $('#btnRefreshTable').on('click', function() { table.ajax.reload(null, false); });

    $('#resetFilters').on('click', function() {
        $('#quick_filter').val('custom');
        let fromElem = document.getElementById('filterFromDate');
        let toElem = document.getElementById('filterToDate');
        if (fromElem && fromElem._flatpickr) { fromElem._flatpickr.clear(); }
        if (toElem && toElem._flatpickr) { toElem._flatpickr.clear(); }
        $('#filterFromDate, #filterToDate, #filterMinAmount, #filterMaxAmount').val('');
        $('#filterPartyType, #filterAccount').val('');
        table.ajax.reload();
    });

    $('#filterFromDate, #filterToDate, #filterPartyType, #filterAccount, #filterMinAmount, #filterMaxAmount').on('keypress', function(e) {
        if (e.which === 13) { 
            e.preventDefault();
            $('#applyFilters').click(); 
        }
    });

    // Delete handler
    $('#voucherHistoryTable').on('click', '[data-delete-url]', function(e) {
        e.preventDefault();
        var url = $(this).data('delete-url');
        var method = $(this).data('delete-method') || 'DELETE';
        var row = table.row($(this).closest('tr'));
        var label = row.data() ? row.data().voucher_no + ' (' + row.data().type_label + ')' : 'this voucher';
        
        Swal.fire({
            title: 'Delete Voucher?',
            html: 'Are you sure you want to delete <strong>' + label + '</strong>?<br><span class="text-danger small">This will reverse related accounting entries.</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: url,
                type: 'POST',
                data: { _method: method, _token: '{{ csrf_token() }}' },
                success: function(resp) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: resp.message || 'Voucher deleted successfully.', timer: 2000, showConfirmButton: false });
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Failed to delete voucher.' });
                }
            });
        });
    });

    var popoverHideTimer = null;
    var $popover = $('#qbRemarkPopover');

    // Move popover to body so it never gets clipped by table overflow or z-index
    if ($popover.length && $popover.parent()[0] !== document.body) {
        $('body').append($popover);
    }

    function showRemarkPopover(targetEl) {
        clearTimeout(popoverHideTimer);
        var $btn = $(targetEl);
        if (!$btn.length) return;

        var rawRemark = '';
        try {
            var rawAttr = $btn.attr('data-remark') || '';
            rawRemark = decodeURIComponent(rawAttr);
        } catch (err) {
            rawRemark = $btn.attr('data-remark') || '';
        }

        var voucherNo = $btn.attr('data-voucherno') || '-';
        var typeLabel = $btn.attr('data-type') || '';
        var rawSource = ($btn.attr('data-source') || '').toLowerCase();

        $('#qbPopoverVoucher').text(voucherNo);
        if (typeLabel) {
            var $badge = $('#qbPopoverType').text(typeLabel).show();
            $badge.removeClass('type-expense type-payment_in type-payment_out');
            if (rawSource.indexOf('expense') !== -1 || typeLabel.toLowerCase().indexOf('expense') !== -1) {
                $badge.addClass('type-expense');
            } else if (rawSource.indexOf('in') !== -1 || typeLabel.toLowerCase().indexOf('in') !== -1) {
                $badge.addClass('type-payment_in');
            } else if (rawSource.indexOf('out') !== -1 || typeLabel.toLowerCase().indexOf('out') !== -1) {
                $badge.addClass('type-payment_out');
            }
        } else {
            $('#qbPopoverType').hide();
        }

        $('#qbPopoverText').text(rawRemark || 'No remarks available.');

        // Temporarily display offscreen/hidden to compute geometry
        $popover.css({ display: 'block', visibility: 'hidden' });
        var popWidth = $popover.outerWidth() || 320;
        var popHeight = $popover.outerHeight() || 100;

        var rect = targetEl.getBoundingClientRect();
        var top = rect.bottom + 6;
        var isAbove = false;

        // If bottom overflows window and room above
        if (top + popHeight > $(window).height() - 10 && rect.top - popHeight - 6 > 10) {
            top = rect.top - popHeight - 6;
            isAbove = true;
        }

        var left = rect.left;
        if (left + popWidth > $(window).width() - 15) {
            left = Math.max(10, $(window).width() - popWidth - 15);
        }
        if (left < 10) left = 10;

        // Arrow alignment with the center of the button
        var btnCenter = rect.left + (rect.width / 2);
        var arrowLeft = Math.max(12, Math.min(popWidth - 18, btnCenter - left));
        $popover.find('.qb-popover-arrow').css('left', arrowLeft + 'px');

        if (isAbove) {
            $popover.addClass('popover-above');
        } else {
            $popover.removeClass('popover-above');
        }

        $popover.css({
            top: top + 'px',
            left: left + 'px',
            visibility: ''
        });

        requestAnimationFrame(function() {
            $popover.addClass('show');
        });
    }

    function hideRemarkPopover(immediate) {
        clearTimeout(popoverHideTimer);
        if (immediate) {
            $popover.removeClass('show');
            setTimeout(function() {
                if (!$popover.hasClass('show')) {
                    $popover.css('display', 'none');
                }
            }, 150);
        } else {
            popoverHideTimer = setTimeout(function() {
                $popover.removeClass('show');
                setTimeout(function() {
                    if (!$popover.hasClass('show')) {
                        $popover.css('display', 'none');
                    }
                }, 150);
            }, 80);
        }
    }

    // Open message box on hover
    $(document).on('mouseenter', '.qb-remark-preview', function() {
        showRemarkPopover(this);
    });

    // Hide message box when cursor leaves
    $(document).on('mouseleave', '.qb-remark-preview', function() {
        hideRemarkPopover(false);
    });

    // Keep message box visible when hovering over the box itself
    $popover.on('mouseenter', function() {
        clearTimeout(popoverHideTimer);
    });

    $popover.on('mouseleave', function() {
        hideRemarkPopover(false);
    });

    // Hide immediately on scroll or table scroll
    $(window).on('scroll resize', function() {
        hideRemarkPopover(true);
    });
    $('.table-responsive').on('scroll', function() {
        hideRemarkPopover(true);
    });

    // Prevent click on remark preview button from navigating/jumping
    $(document).on('click', '.qb-remark-preview', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Copy remark text in popover
    $(document).on('click', '#qbBtnCopyPopover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var txt = $('#qbPopoverText').text().trim();
        if (!txt) return;

        var copyDone = function() {
            var $btn = $('#qbBtnCopyPopover');
            var origHtml = $btn.html();
            $btn.html('<i class="fas fa-check"></i> Copied!');
            setTimeout(function() {
                $btn.html(origHtml);
            }, 1500);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(txt).then(copyDone);
        } else {
            var temp = $('<textarea>');
            $('body').append(temp);
            temp.val(txt).select();
            document.execCommand('copy');
            temp.remove();
            copyDone();
        }
    });

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function numberFormat(v) {
        v = parseFloat(v) || 0;
        return v.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

});
</script>

@endsection
