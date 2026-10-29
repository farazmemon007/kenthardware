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

        /* ==========================================================
           BOOTSTRAP 4 COMPATIBILITY & HIGH-CONTRAST TYPOGRAPHY POLYFILL
           ========================================================== */
        .fw-bold, .font-weight-bold { font-weight: 700 !important; }
        .fw-bolder { font-weight: 800 !important; }
        .fw-semibold { font-weight: 600 !important; }
        .font-monospace, .text-monospace { font-family: 'JetBrains Mono', Consolas, SFMono-Regular, Menlo, Monaco, monospace !important; }
        .text-end, .text-right { text-align: right !important; }
        .text-start, .text-left { text-align: left !important; }
        .text-center { text-align: center !important; }

        /* 3. Table Card */
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

        /* Table Body Cells - High Contrast & Visible */
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

        /* ==========================================================
           ROW CURSOR HOVER: PASTEL MINT GREEN
           Ensuring All Font Colors Remain 100% Crisp & Visible
           ========================================================== */
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

        /* Ensure Balances & Badges Stay High-Contrast on Hover/Selected Rows */
        .qb-table tbody tr:hover td .acc-balance-dr,
        .qb-table tbody tr.qb-active-row td .acc-balance-dr {
            color: #044e32 !important; /* Deep Forest Green for max contrast on #d5eed7 */
            font-weight: 800 !important;
        }
        .qb-table tbody tr:hover td .acc-balance-cr,
        .qb-table tbody tr.qb-active-row td .acc-balance-cr {
            color: #7f1d1d !important; /* Deep Crimson Red */
            font-weight: 800 !important;
        }
        .qb-table tbody tr:hover td .acc-badge-code,
        .qb-table tbody tr.qb-active-row td .acc-badge-code {
            background: #ffffff !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }
        .qb-table tbody tr:hover td .acc-badge-status-active,
        .qb-table tbody tr.qb-active-row td .acc-badge-status-active {
            background-color: #bbf7d0 !important;
            color: #064e3b !important;
            border-color: #4ade80 !important;
            font-weight: 800 !important;
        }

        .qb-table td.text-nowrap,
        .qb-table th.text-nowrap,
        .qb-table .font-monospace {
            white-space: nowrap !important;
        }

        /* Dedicated Custom High-Contrast Typography & Badges */
        .acc-col-num {
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #334155 !important;
        }
        .acc-badge-code {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            background-color: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px !important;
            padding: 2px 7px !important;
            display: inline-block;
            letter-spacing: 0.3px;
        }
        .acc-head-title {
            font-size: 12.5px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            line-height: 1.3;
        }
        .acc-head-parent {
            font-size: 10.5px !important;
            font-weight: 600 !important;
            color: #475569 !important;
            display: block;
            margin-top: 1px;
        }
        .acc-title {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            line-height: 1.3;
        }
        .acc-badge-type-debit {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            border: 1px solid #7dd3fc !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 2px 8px !important;
            border-radius: 12px;
            display: inline-block;
            text-transform: capitalize;
        }
        .acc-badge-type-credit {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fde68a !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 2px 8px !important;
            border-radius: 12px;
            display: inline-block;
            text-transform: capitalize;
        }
        .acc-opening-val {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            text-align: right !important;
        }
        .acc-balance-dr {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            color: #047857 !important; /* Rich Dark Green */
            letter-spacing: 0.2px;
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
        }
        .acc-balance-cr {
            font-family: 'JetBrains Mono', Consolas, monospace !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            color: #b91c1c !important; /* Rich Dark Red */
            letter-spacing: 0.2px;
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
        }
        .acc-bal-flag {
            font-size: 10px !important;
            font-weight: 800 !important;
            padding: 1px 5px !important;
            border-radius: 3px !important;
            display: inline-block;
            line-height: 1.2;
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
        .acc-badge-status-active {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            border: 1px solid #86efac !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 2px 9px !important;
            border-radius: 12px;
            display: inline-block;
        }
        .acc-badge-status-inactive {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
            border: 1px solid #fca5a5 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 2px 9px !important;
            border-radius: 12px;
            display: inline-block;
        }

        /* ==========================================================
           SMART CATEGORY / HEAD ROW GROUPING (QuickBooks & Odoo COA)
           ========================================================== */
        .qb-group-row {
            background: linear-gradient(180deg, #edf4fc 0%, #dbe7f5 100%) !important;
            border-top: 2px solid #8ca4c3 !important;
            border-bottom: 1.5px solid #b2c5dc !important;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s ease;
        }
        .qb-group-row:hover {
            background: linear-gradient(180deg, #e4effb 0%, #cee0f2 100%) !important;
        }
        .qb-group-cell {
            padding: 4px 10px !important;
            border-top: none !important;
            border-bottom: none !important;
            background: transparent !important;
        }
        .qb-group-icon-circle {
            width: 22px;
            height: 22px;
            background: #18385e;
            color: #ffffff;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }
        .qb-group-title {
            font-size: 13px !important;
            font-weight: 800 !important;
            color: #0f2744 !important;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .qb-group-pill {
            background: #ffffff !important;
            border: 1px solid #94a3b8 !important;
            color: #1e3a5f !important;
            font-size: 10.5px !important;
            font-weight: 700 !important;
            padding: 2px 7px !important;
            border-radius: 12px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .qb-group-subtotal-lbl {
            font-size: 10.5px !important;
            font-weight: 700 !important;
            color: #475569 !important;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .qb-group-subtotal-val {
            font-size: 12.5px !important;
            font-weight: 800 !important;
            letter-spacing: 0.2px;
        }
        .qb-group-chevron {
            transition: transform 0.2s ease;
        }
        .qb-group-chevron.fa-chevron-right {
            color: #2563eb !important;
        }

        /* Subtle indent for account rows under grouped heads */
        .qb-table tbody tr:not(.qb-group-row) td:first-child {
            padding-left: 10px !important;
        }

        /* Action View Buttons (Compact Toolbar Buttons) */
        .odoo-action-btn {
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
            border: 1px solid #8ca4c3 !important;
            color: #0f2744 !important;
            font-weight: 700 !important;
            border-radius: 3px !important;
            height: 23px !important;
            padding: 0 8px !important;
            font-size: 11px !important;
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
            font-size: 11.5px !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            margin-bottom: 4px !important;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 24px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11px !important;
            padding: 0 4px !important;
            margin: 0 4px !important;
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_filter {
            font-size: 11.5px !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            margin-bottom: 4px !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 24px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 11px !important;
            padding: 0 6px !important;
            margin-left: 4px !important;
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 11.5px !important;
            color: #1e293b !important;
            padding-top: 5px !important;
            font-weight: 700 !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 4px !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            color: #0f2744 !important;
            border: 1px solid #b2c5dc !important;
            padding: 3px 8px !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
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
                        <span class="font-weight-bold font-monospace" style="font-size: 11.5px; color: #0f172a !important;">
                            <i class="fas fa-list text-muted mr-1" style="margin-right: 4px;"></i> Accounts: <strong style="color: #0f172a;">{{ $accounts->count() }}</strong>
                        </span>
                        <span class="text-muted">|</span>
                        <span class="font-weight-bold font-monospace" style="font-size: 11.5px; color: #047857 !important;">
                            <i class="fas fa-check-circle mr-1" style="margin-right: 4px;"></i> Active: <strong>{{ $accounts->where('status', 1)->count() }}</strong>
                        </span>
                        <span class="text-muted">|</span>
                        <span class="font-weight-bold font-monospace" style="font-size: 11.5px; color: #334155 !important;">
                            <i class="fas fa-folder mr-1" style="margin-right: 4px;"></i> Categories: <strong style="color: #0f172a;">{{ $heads->count() }}</strong>
                        </span>
                    </div>
                    <div class="qb-kpi-group">
                        <div class="qb-kpi-chip">
                            <span class="chip-label" style="color: #047857; font-weight: 700;">Total Debit (Dr):</span>
                            <span class="chip-val" style="color: #047857; font-weight: 800;">Rs {{ number_format($totalDebit, 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip">
                            <span class="chip-label" style="color: #b91c1c; font-weight: 700;">Total Credit (Cr):</span>
                            <span class="chip-val" style="color: #b91c1c; font-weight: 800;">Rs {{ number_format($totalCredit, 2) }}</span>
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
                @php
                    $groupedAccounts = $accounts->groupBy(function($acc) {
                        return $acc->head ? $acc->head->name : 'General / Other Accounts';
                    });
                    $globalCounter = 1;
                @endphp
                <div class="qb-table-card d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table qb-table datanew" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 10%;">Code</th>
                                    <th>Head / Group</th>
                                    <th style="width: 25%;">Account Title</th>
                                    <th style="width: 8%; text-align: center;">Type</th>
                                    <th style="width: 12%; text-align: right;">Opening</th>
                                    <th style="width: 14%; text-align: right;">Current Balance</th>
                                    <th style="width: 8%; text-align: center;">Status</th>
                                    <th style="width: 20%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedAccounts as $headName => $accList)
                                    @php
                                        $groupTotal = $accList->sum('current_balance');
                                        $groupCount = $accList->count();
                                        $headIcon = (stripos($headName, 'bank') !== false) ? 'fa-university' : ((stripos($headName, 'cash') !== false) ? 'fa-wallet' : 'fa-folder-open');
                                        $isDr = $groupTotal >= 0;
                                        $formattedTotal = number_format(abs($groupTotal), 2) . ' ' . ($isDr ? 'Dr' : 'Cr');
                                    @endphp
                                    @foreach ($accList as $acc)
                                        <tr data-group-name="{{ $headName }}"
                                            data-group-count="{{ $groupCount }} {{ $groupCount == 1 ? 'Account' : 'Accounts' }}"
                                            data-group-total="{{ $formattedTotal }}"
                                            data-group-is-dr="{{ $isDr ? '1' : '0' }}"
                                            data-group-icon="{{ $headIcon }}">
                                            <td class="text-center font-monospace acc-col-num">{{ $globalCounter++ }}</td>
                                            <td>
                                                <span class="acc-badge-code">{{ $acc->account_code ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $headName }}</td>
                                            <td>
                                                <span class="acc-title">{{ $acc->title }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($acc->type == 'Debit')
                                                    <span class="acc-badge-type-debit">Debit</span>
                                                @else
                                                    <span class="acc-badge-type-credit">Credit</span>
                                                @endif
                                            </td>
                                            <td class="text-right acc-opening-val">
                                                {{ number_format($acc->opening_balance, 2) }}
                                            </td>
                                            <td class="text-right">
                                                <span class="{{ $acc->current_balance < 0 ? 'acc-balance-cr' : 'acc-balance-dr' }}">
                                                    {{ number_format(abs($acc->current_balance), 2) }}
                                                    <span class="acc-bal-flag {{ $acc->current_balance >= 0 ? 'dr' : 'cr' }}">{{ $acc->current_balance >= 0 ? 'Dr' : 'Cr' }}</span>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($acc->status)
                                                    <span class="acc-badge-status-active">Active</span>
                                                @else
                                                    <span class="acc-badge-status-inactive">Inactive</span>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════════
                     MOBILE CARDS VIEW (Clean 2x2 grid with balanced spacing)
                ══════════════════════════════════════════════════════════════ --}}
                <div class="d-block d-md-none">
                    @foreach ($groupedAccounts as $headName => $accList)
                        @php
                            $groupTotal = $accList->sum('current_balance');
                            $groupCount = $accList->count();
                            $headIcon = (stripos($headName, 'bank') !== false) ? 'fa-university' : ((stripos($headName, 'cash') !== false) ? 'fa-wallet' : 'fa-folder-open');
                            $isDr = $groupTotal >= 0;
                            $formattedTotal = number_format(abs($groupTotal), 2) . ' ' . ($isDr ? 'Dr' : 'Cr');
                        @endphp

                        {{-- Mobile Head Category Header Banner --}}
                        <div class="p-2 mb-2 mt-3 rounded d-flex justify-content-between align-items-center" style="background: linear-gradient(180deg, #edf4fc 0%, #dbe7f5 100%); border: 1.5px solid #8ca4c3;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="qb-group-icon-circle" style="width: 22px; height: 22px; font-size: 10px;"><i class="fas {{ $headIcon }}"></i></span>
                                <span class="fw-bold text-dark" style="font-size: 12.5px; text-transform: uppercase;">{{ $headName }}</span>
                                <span class="badge badge-light border text-dark font-weight-bold" style="font-size: 10px;">{{ $groupCount }} {{ $groupCount == 1 ? 'Account' : 'Accounts' }}</span>
                            </div>
                            <div>
                                <span class="font-monospace fw-bold {{ $isDr ? 'text-success' : 'text-danger' }}" style="font-size: 11.5px;">Rs {{ $formattedTotal }}</span>
                            </div>
                        </div>

                        @foreach ($accList as $acc)
                            <div class="card border shadow-sm rounded-3 mb-2" style="background: #ffffff; border-color: #cbd5e1 !important;">
                                <div class="card-body p-2">
                                    {{-- Code & Status Header --}}
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="acc-badge-code">{{ $acc->account_code ?? 'N/A' }}</span>
                                        @if ($acc->status)
                                            <span class="acc-badge-status-active">Active</span>
                                        @else
                                            <span class="acc-badge-status-inactive">Inactive</span>
                                        @endif
                                    </div>

                                    <div class="acc-title mb-2">{{ $acc->title }}</div>

                                    {{-- Type & Opening Grid --}}
                                    <div class="row g-1 mb-2 text-left" style="font-size: 11px;">
                                        <div class="col-6">
                                            <div class="text-muted font-weight-bold" style="font-size: 10px;">TYPE</div>
                                            <div class="font-weight-bold" style="color: #0f172a;">{{ $acc->type }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted font-weight-bold" style="font-size: 10px;">OPENING</div>
                                            <div class="font-weight-bold font-monospace" style="color: #0f172a;">{{ number_format($acc->opening_balance, 2) }}</div>
                                        </div>
                                    </div>

                                    {{-- Current Balance --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2 pt-1 border-top">
                                        <span class="font-weight-bold text-muted" style="font-size: 11px;">Current Balance:</span>
                                        <span class="{{ $acc->current_balance < 0 ? 'acc-balance-cr' : 'acc-balance-dr' }}">
                                            {{ number_format(abs($acc->current_balance), 2) }}
                                            <span class="acc-bal-flag {{ $acc->current_balance >= 0 ? 'dr' : 'cr' }}">{{ $acc->current_balance >= 0 ? 'Dr' : 'Cr' }}</span>
                                        </span>
                                    </div>

                                    {{-- Action Button Grid --}}
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <a href="{{ route('accounts.ledger', $acc->id) }}" class="btn btn-outline-primary btn-sm w-100 font-weight-bold d-flex align-items-center justify-content-center" style="height: 28px; font-size: 11px; border-radius: 4px;">
                                                <i class="fas fa-book mr-1" style="margin-right: 4px;"></i> Ledger
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 font-weight-bold d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#editAccountModal{{ $acc->id }}" style="height: 28px; font-size: 11px; border-radius: 4px;">
                                                <i class="fas fa-edit mr-1" style="margin-right: 4px;"></i> Edit
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 font-weight-bold d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#historyAccountModal{{ $acc->id }}" style="height: 28px; font-size: 11px; border-radius: 4px;">
                                                <i class="fas fa-history mr-1" style="margin-right: 4px;"></i> History ({{ $acc->histories->count() }})
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <form action="{{ route('accounts.toggleStatus', $acc->id) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="this.closest('form').submit()" class="btn {{ $acc->status ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm w-100 font-weight-bold d-flex align-items-center justify-content-center" style="height: 28px; font-size: 11px; border-radius: 4px;">
                                                    <i class="fas {{ $acc->status ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
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
                                        <div class="qb-modal-subtitle">Bank, Cash, or Ledger Account</div>
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
                "pageLength": 25,
                "aaSorting": [],
                "orderFixed": [[2, 'asc']],
                "columnDefs": [
                    { "visible": false, "targets": 2 }
                ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;

                    // Remove previously injected dynamic group rows before re-drawing
                    $(api.table().body()).find('.qb-group-row').remove();

                    api.column(2, { page: 'current' }).data().each(function (groupVal, i) {
                        var $row = $(rows).eq(i);
                        var groupName = $row.attr('data-group-name') || groupVal;

                        if (last !== groupName) {
                            var groupCount = $row.attr('data-group-count') || '1 Account';
                            var groupTotal = $row.attr('data-group-total') || '';
                            var isDr = $row.attr('data-group-is-dr') === '1';
                            var groupIcon = $row.attr('data-group-icon') || 'fa-folder-open';
                            var totalColor = isDr ? '#047857' : '#b91c1c';

                            $row.before(
                                '<tr class="qb-group-row" data-group-target="' + groupName + '" title="Click to collapse / expand accounts">' +
                                    '<td colspan="8" class="qb-group-cell">' +
                                        '<div class="d-flex align-items-center justify-content-between flex-wrap" style="padding: 3px 6px;">' +
                                            '<div class="d-flex align-items-center" style="gap: 8px;">' +
                                                '<i class="fas fa-chevron-down qb-group-chevron text-secondary" style="font-size: 10px;"></i>' +
                                                '<span class="qb-group-icon-circle"><i class="fas ' + groupIcon + '"></i></span>' +
                                                '<span class="qb-group-title">' + groupName + '</span>' +
                                                '<span class="badge qb-group-pill">' + groupCount + '</span>' +
                                            '</div>' +
                                            '<div class="d-flex align-items-center" style="gap: 6px;">' +
                                                '<span class="qb-group-subtotal-lbl">Total Head Balance:</span>' +
                                                '<span class="qb-group-subtotal-val font-monospace" style="color: ' + totalColor + ' !important;">Rs ' + groupTotal + '</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>'
                            );
                            last = groupName;
                        }
                    });
                },
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search accounts..."
                }
            });

            // Expand / Collapse group rows on clicking the group header
            $(document).on('click', '.qb-group-row', function() {
                var groupName = $(this).attr('data-group-target');
                var $targetRows = $(this).closest('tbody').find('tr[data-group-name="' + groupName + '"]');
                $targetRows.toggle();
                $(this).find('.qb-group-chevron').toggleClass('fa-chevron-down fa-chevron-right');
            });
        });
    </script>
@endsection
