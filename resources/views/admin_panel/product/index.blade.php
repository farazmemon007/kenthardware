@extends('admin_panel.layout.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&family=Caveat:wght@600;700&display=swap');
    /* ── LAYOUT RESET: make sure page uses full width cleanly ── */
    .erp-page { background: #f8fafc; min-height: calc(100vh - 80px); padding: 20px 0; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    .erp-page .container-fluid { max-width: 100%; box-sizing: border-box; }

    /* ── ERP Product Page – Premium Design System ── */
    :root {
        --erp-primary:    #6366f1;
        --erp-primary-lt: #eef2ff;
        --erp-success:    #10b981;
        --erp-success-lt: #ecfdf5;
        --erp-warning:    #f59e0b;
        --erp-warning-lt: #fffbeb;
        --erp-danger:     #ef4444;
        --erp-danger-lt:  #fef2f2;
        --erp-info:       #3b82f6;
        --erp-info-lt:    #eff6ff;
        --erp-border:     #e2e8f0;
        --erp-bg:         #f8fafc;
        --erp-card-bg:    #ffffff;
        --erp-text:       #0f172a;
        --erp-muted:      #64748b;
        --erp-radius:     14px;
        --erp-shadow:     0 2px 8px rgba(15,23,42,0.04), 0 1px 3px rgba(15,23,42,0.02);
        --erp-shadow-md:  0 8px 30px rgba(15,23,42,0.08);
    }

    /* ── Stats Cards ── */
    .stat-card {
        background: var(--erp-card-bg);
        border-radius: var(--erp-radius);
        border: 1px solid var(--erp-border);
        box-shadow: var(--erp-shadow);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--erp-shadow-md); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .stat-card .stat-label { font-size: .73rem; font-weight: 700; color: var(--erp-muted); text-transform: uppercase; letter-spacing: .5px; }
    .stat-card .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--erp-text); line-height: 1.2; }
    .stat-card .stat-sub   { font-size: .72rem; color: var(--erp-muted); margin-top: 2px; }

    /* ── Main Card ── */
    .erp-card {
        background: var(--erp-card-bg);
        border-radius: var(--erp-radius);
        border: 1px solid var(--erp-border);
        box-shadow: var(--erp-shadow);
        overflow: hidden;
    }
    .erp-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--erp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .erp-card-header .page-title { font-size: 1.05rem; font-weight: 800; color: var(--erp-text); margin: 0; display: flex; align-items: center; gap: 8px; }
    .erp-card-header .page-sub   { font-size: .78rem; color: var(--erp-muted); margin: 2px 0 0 0; }
    
    /* ── Header action buttons group ── */
    .erp-hdr-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* ── Filter Panel ── */
    .filter-panel {
        background: #f8fafc;
        border-bottom: 1px solid var(--erp-border);
        padding: 16px 20px;
    }
    .filter-panel .filter-heading {
        font-size: .74rem; font-weight: 800; text-transform: uppercase; letter-spacing: .6px;
        color: var(--erp-muted); margin-bottom: 12px; display: flex; align-items: center; gap: 6px;
    }
    .filter-panel label.form-label {
        font-size: .72rem; font-weight: 700; color: var(--erp-muted);
        text-transform: uppercase; letter-spacing: .4px; margin-bottom: 5px;
    }

    .erp-filter-row {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
    }
    .erp-filter-field {
        display: flex;
        flex-direction: column;
        flex: 1 1 150px;
        min-width: 130px;
        max-width: 240px;
        box-sizing: border-box;
    }
    .erp-filter-search { flex: 1 1 220px; max-width: 320px; }
    .erp-filter-btns   { flex: 0 0 auto; max-width: none; min-width: 0; }

    .erp-flabel {
        display: block;
        font-size: .69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--erp-muted);
        margin-bottom: 5px;
        white-space: nowrap;
    }
    .erp-finput {
        display: block;
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        font-size: .83rem;
        font-weight: 500;
        color: var(--erp-text);
        background: #fff;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        box-sizing: border-box;
    }
    .erp-finput:focus {
        border-color: var(--erp-primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }
    .search-wrap { position: relative; width: 100%; }
    .search-wrap .search-icon {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--erp-muted); font-size: 13px; pointer-events: none;
        z-index: 1;
    }
    .search-wrap .erp-finput { padding-left: 34px; }

    /* Filter action buttons */
    .btn-erp-filter {
        background: var(--erp-primary); color: #fff; border: none;
        border-radius: 8px; height: 38px; padding: 0 16px;
        font-size: .83rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;
        transition: background .15s, transform .1s; cursor: pointer;
    }
    .btn-erp-filter:hover { background: #4f46e5; color: #fff; transform: translateY(-1px); }
    .btn-erp-clear {
        background: #fff; color: var(--erp-muted); border: 1px solid var(--erp-border);
        border-radius: 8px; height: 38px; padding: 0 14px;
        font-size: .83rem; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;
        transition: all .15s; text-decoration: none; cursor: pointer;
    }
    .btn-erp-clear:hover { border-color: var(--erp-danger); color: var(--erp-danger); background: var(--erp-danger-lt); }

    /* Active filter badges */
    .active-filters { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
    .filter-chip {
        background: var(--erp-primary-lt); color: var(--erp-primary);
        border: 1px solid #c7d2fe; border-radius: 20px;
        padding: 3px 10px; font-size: .72rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 4px;
    }

    /* ── Header action buttons ── */
    .btn-hdr {
        border-radius: 8px; padding: 8px 14px; font-size: .82rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px; transition: all .15s;
        border: 1px solid transparent; text-decoration: none; cursor: pointer;
    }
    .btn-hdr-outline { background: #fff; color: var(--erp-muted); border-color: var(--erp-border); }
    .btn-hdr-outline:hover { border-color: #94a3b8; color: var(--erp-text); background: var(--erp-bg); }
    .btn-hdr-success { background: #10b981; color: #fff; border-color: #10b981; }
    .btn-hdr-success:hover { background: #059669; border-color: #059669; color: #fff; transform: translateY(-1px); }
    .btn-hdr-warning { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .btn-hdr-warning:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    .btn-hdr-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: #6366f1; box-shadow: 0 2px 6px rgba(99,102,241,0.25); }
    .btn-hdr-primary:hover { background: #4338ca; border-color: #4338ca; color: #fff; transform: translateY(-1px); }

    /* ── QuickBooks POS Desktop Item List (Striped Grid, Resizable Columns) ── */
    .erp-table-wrap {
        padding: 0;
        overflow-x: auto;
        width: 100%;
        position: relative;
    }
    #productTable {
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        font-size: 12px;
        table-layout: fixed;
    }
    #modalVariantTable {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        font-size: 12px;
        table-layout: auto;
    }
    #productTable thead th, #modalVariantTable thead th {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.35px;
        padding: 7px 8px !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom: 2px solid #cbd5e1 !important;
        border-right: 1px solid #e2e8f0 !important;
        white-space: nowrap;
        position: sticky; top: 0; z-index: 2;
        box-sizing: border-box;
    }
    #productTable thead th, #qbPiTable thead th {
        user-select: none;
        position: relative;
    }
    #productTable thead th .col-resizer, #qbPiTable thead th .col-resizer {
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 100%;
        cursor: col-resize;
        user-select: none;
        z-index: 10;
        display: block;
    }
    #productTable thead th .col-resizer::after, #qbPiTable thead th .col-resizer::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        width: 2px;
        background: transparent;
        transition: background 0.15s ease;
    }
    #productTable thead th .col-resizer:hover::after,
    #productTable thead th .col-resizer.is-resizing::after,
    #qbPiTable thead th .col-resizer:hover::after,
    #qbPiTable thead th .col-resizer.is-resizing::after {
        background: #2563eb;
        box-shadow: 0 0 5px rgba(37, 99, 235, 0.9);
    }
    .qb-pi-modal-backdrop {
        z-index: 1070 !important;
    }
    #customizePiColumnsModal {
        z-index: 1075 !important;
    }
    body.is-col-resizing {
        cursor: col-resize !important;
        user-select: none !important;
    }
    body.is-col-resizing * {
        user-select: none !important;
    }
    #productTable thead th:last-child, #modalVariantTable thead th:last-child {
        border-right: none !important;
    }
    #productTable tbody td, #modalVariantTable tbody td {
        padding: 5px 8px !important;
        border: none !important;
        border-bottom: 1px solid #c8d7e6 !important;
        border-right: 1px solid #cbd8e7 !important;
        color: #1e293b;
        vertical-align: middle;
        font-size: 12px;
        line-height: 1.25;
        white-space: nowrap;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #productTable tbody td:last-child, #modalVariantTable tbody td:last-child {
        border-right: none !important;
    }
    #productTable tbody td.td-item-details {
        white-space: normal;
        min-width: 120px;
        max-width: none;
        overflow: visible;
        word-break: break-word;
    }
    /* Alternating Zebra Striping ("strip wale") - Distinct Darker QuickBooks Cool Blue */
    #productTable tbody tr:nth-child(even) td, #modalVariantTable tbody tr:nth-child(even) td {
        background-color: #dbe7f4 !important; /* Distinct Darker QuickBooks Cool Blue Stripe */
    }
    #productTable tbody tr:nth-child(odd) td, #modalVariantTable tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    #productTable tbody tr:hover td, #modalVariantTable tbody tr:hover td {
        background-color: #bfdbfe !important; /* Prominent dark blue hover */
    }
    #productTable tbody tr:hover td:first-child, #modalVariantTable tbody tr:hover td:first-child {
        box-shadow: inset 6px 0 0 #1d4ed8; /* Dark royal blue prominent indicator strip */
    }
    /* Selected Table Row in QuickBooks Desktop blue */
    #productTable tbody tr.row-qb-selected td {
        background-color: #93c5fd !important;
        color: #1e3a8a !important;
        font-weight: 700;
    }
    #productTable tbody tr.row-qb-selected td:first-child {
        box-shadow: inset 6px 0 0 #1e40af;
    }
    #productTable tbody tr.row-inactive td {
        opacity: .65;
    }

    /* Image cell */
    .product-img {
        width: 26px; height: 26px; object-fit: cover;
        border-radius: 4px; border: 1px solid var(--erp-border);
        transition: transform .2s ease; cursor: pointer; display: block; margin: 0 auto;
    }
    .product-img:hover { transform: scale(1.6); z-index: 10; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .no-img-badge {
        width: 26px; height: 26px; border-radius: 4px;
        background: #f1f5f9; border: 1px dashed #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; color: #94a3b8; margin: 0 auto;
    }

    /* Item details cell */
    .item-name { font-weight: 700; color: #0f172a; margin-bottom: 1px; font-size: 12px; line-height: 1.25; }
    .item-meta { font-size: 10.5px; color: var(--erp-muted); display: flex; flex-wrap: wrap; gap: 3px; align-items: center; margin-top: 1px; }
    .item-meta .meta-chip {
        background: #f1f5f9; border-radius: 3px; padding: 1px 5px;
        font-size: 10px; font-weight: 600; color: #475569;
        display: inline-flex; align-items: center; gap: 2px;
    }
    .item-code { font-family: 'Courier New', monospace; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 3px; padding: 0 4px; font-size: 10.5px; font-weight: 700; color: #334155; }

    /* Stock badge */
    .stock-badge {
        display: inline-flex; align-items: center; gap: 3px;
        background: var(--erp-success-lt); color: var(--erp-success);
        border: 1px solid #a7f3d0; border-radius: 4px;
        padding: 1px 5px; font-size: 11px; font-weight: 700;
        white-space: nowrap;
    }
    .stock-badge.low  { background: var(--erp-danger-lt); color: var(--erp-danger); border-color: #fecaca; }
    .stock-badge.zero { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .stock-unit { font-weight: 500; font-size: 10px; opacity: .85; }

    /* Price cells */
    .price-purchase { color: #64748b; font-weight: 600; font-size: 11.5px; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .price-sale     { color: #059669; font-weight: 800; font-size: 12px; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .price-wholesale { color: #0284c7; font-weight: 700; font-size: 11.5px; white-space: nowrap; font-variant-numeric: tabular-nums; }

    /* Status badge */
    .status-active {
        background: #ecfdf5; color: #059669;
        border: 1px solid #a7f3d0; border-radius: 4px;
        padding: 1px 6px; font-size: 10.5px; font-weight: 700;
        white-space: nowrap;
    }
    .status-inactive {
        background: #f1f5f9; color: #64748b;
        border: 1px solid #cbd5e1; border-radius: 4px;
        padding: 1px 6px; font-size: 10.5px; font-weight: 700;
        white-space: nowrap;
    }

    /* Action buttons – desktop compact icon row */
    .action-group {
        display: flex; align-items: center; justify-content: center; gap: 3px;
        flex-wrap: nowrap;
    }
    .btn-act {
        width: 24px; height: 24px; padding: 0;
        border-radius: 4px; font-size: 10.5px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid transparent; transition: all .12s; cursor: pointer;
        line-height: 1; white-space: nowrap; flex-shrink: 0;
    }
    .btn-act-view    { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
    .btn-act-view:hover    { background: #0284c7; color: #fff; }
    .btn-act-edit    { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
    .btn-act-edit:hover    { background: #4f46e5; color: #fff; }
    .btn-act-barcode { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .btn-act-barcode:hover { background: #059669; color: #fff; }
    .btn-act-deact   { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .btn-act-deact:hover   { background: #dc2626; color: #fff; }
    .btn-act-act     { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .btn-act-act:hover     { background: #059669; color: #fff; }
    .btn-act-delete  { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .btn-act-delete:hover  { background: #dc2626; color: #fff; }

    /* ── Pagination ── */
    .erp-pagination { padding: 10px 16px; border-top: 1px solid var(--erp-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
    .erp-pagination .showing { font-size: .75rem; color: var(--erp-muted); }
    .erp-pagination .page-link { border-radius: 5px !important; border-color: var(--erp-border) !important; color: var(--erp-text) !important; font-size: .78rem; padding: 4px 10px; }
    .erp-pagination .page-item.active .page-link { background: #2563eb !important; border-color: #2563eb !important; color: #fff !important; }

    /* ── Actions column – compact width ── */
    #productTable th:last-child,
    #productTable td:last-child { width: 130px; min-width: 110px; text-align: center; }

    /* ── Select checkbox ── */
    input[type="checkbox"].row-check { width: 14px; height: 14px; accent-color: #2563eb; cursor: pointer; margin: 0; }

    /* ── DataTable override ── */
    div.dataTables_wrapper div.dataTables_length select { width: 75px !important; }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate { display: none !important; }
    .dataTables_wrapper { overflow-x: visible !important; }

    /* ── Odoo Enterprise Matrix View: Attribute Pills & Radios (Kent Theme) ── */
    .matrix-radio-pill {
        display: inline-flex !important;
        align-items: center !important;
        padding: 6px 14px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        cursor: pointer !important;
        margin: 0 !important;
        transition: all 0.15s ease !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        user-select: none !important;
    }
    .matrix-radio-pill:hover {
        border-color: #2563eb !important;
        background: #f8fafc !important;
    }
    .matrix-radio-pill.selected-pill {
        border-color: #2563eb !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
    }
    .matrix-radio-pill.selected-pill .matrix-attr-val {
        color: #1e40af !important;
        font-weight: 700 !important;
    }
    .matrix-radio-pill.disabled-pill {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    .matrix-radio-pill.disabled-pill .matrix-attr-val {
        color: #94a3b8 !important;
    }

    /* Odoo Matrix Top KPI Ribbon */
    .matrix-kpi-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 9px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 14px;
        gap: 12px;
    }
    .matrix-kpi-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
    .matrix-kpi-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 12px;
    }
    .matrix-kpi-item:first-child {
        padding-left: 0;
    }
    .matrix-kpi-divider {
        width: 1px;
        height: 28px;
        background: #cbd5e1;
        margin: 0 4px;
        flex-shrink: 0;
    }
    .matrix-kpi-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        margin-bottom: 2px;
        white-space: nowrap;
    }
    .matrix-kpi-value {
        font-size: 17px;
        font-weight: 800;
        line-height: 1.2;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }

    /* ════════════════════════════════════════════════════
       MOBILE RESPONSIVE  ≤ 768px
    ════════════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .erp-page { padding: 12px 0; }
        .container-fluid { padding-left: 10px !important; padding-right: 10px !important; }

        /* Stats: 2-col grid */
        .stat-grid { grid-template-columns: 1fr 1fr !important; gap: 10px !important; }
        .stat-card { padding: 12px; gap: 10px; }
        .stat-icon { width: 36px; height: 36px; font-size: 15px; border-radius: 10px; }
        .stat-card .stat-value { font-size: 1.15rem; }
        .stat-card .stat-label { font-size: .65rem; }
        .stat-card .stat-sub   { display: none; }

        /* Card header: title + buttons */
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 12px; padding: 14px; }
        .erp-hdr-actions {
            width: 100% !important;
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px !important;
        }
        .btn-hdr {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
            font-size: .78rem !important;
            padding: 9px 10px !important;
            box-sizing: border-box !important;
            border-radius: 10px !important;
            height: 38px !important;
        }

        /* Filter panel: stack fields */
        .filter-panel { padding: 12px 14px; }
        .erp-filter-row { gap: 10px; }
        .erp-filter-field,
        .erp-filter-search { flex: 1 1 100%; max-width: 100%; }
        .erp-filter-btns { flex: 1 1 100%; width: 100%; }
        .erp-filter-btns > div { width: 100%; display: flex; gap: 8px; }
        .btn-erp-filter,
        .btn-erp-clear { flex: 1; text-align: center; justify-content: center; height: 40px; }

        /* Table wrap */
        .erp-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0;
        }
        #productTable {
            min-width: 720px !important;
            font-size: .8rem;
        }
        #productTable thead th { padding: 10px 10px; font-size: .64rem; }
        #productTable tbody td { padding: 8px 10px; }

        /* Pagination */
        .erp-pagination {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
        }
        .erp-pagination nav { width: 100%; }
    }

    /* ── Mobile Product Cards ── */
    .mobile-product-cards { display: none; padding: 14px; }

    @media (max-width: 768px) {
        .erp-table-wrap { display: none !important; }
        .mobile-product-cards { display: flex; flex-direction: column; gap: 12px; }
    }

    .prod-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(15,23,42,0.03);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .prod-mcard.row-inactive { opacity: 0.65; background: #f8fafc; }
    .prod-mcard-hd { display: flex; align-items: flex-start; gap: 12px; }
    .prod-mcard-body {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .prod-mcard-price { font-size: 1.05rem; font-weight: 800; color: #10b981; }
    .prod-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        margin-top: 12px;
        width: 100%;
    }
    .prod-mcard-actions .btn-act {
        width: 100% !important;
        justify-content: center !important;
        height: 38px !important;
        font-size: .78rem !important;
        border-radius: 8px !important;
    }

    @media (max-width: 480px) {
        .stat-grid { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }
        .stat-card { padding: 10px; }
        .stat-card .stat-value { font-size: 1.05rem; }
        #productTable { min-width: 660px !important; }
        .btn-act { padding: 3px 6px; font-size: .68rem; }
    }

    /* QuickBooks POS Desktop Style Context Menu */
    .qb-custom-context-menu {
        position: fixed !important;
        margin: 0 !important;
        padding: 6px !important;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.18), 0 4px 6px -2px rgba(0, 0, 0, 0.08) !important;
        z-index: 999999999 !important;
        min-width: 200px !important;
        font-family: inherit !important;
        font-size: 13px !important;
        display: none;
        transform: none !important;
    }

    .qb-custom-context-menu .qb-ctx-header {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #64748b !important;
        padding: 4px 8px 6px 8px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        border-bottom: 1px solid #f1f5f9 !important;
        margin-bottom: 4px !important;
    }

    .qb-custom-context-menu .qb-ctx-item {
        display: flex !important;
        align-items: center !important;
        gap: 9px !important;
        width: 100% !important;
        padding: 7px 10px !important;
        border: none !important;
        background: transparent !important;
        color: #1e293b !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        text-align: left !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
        text-decoration: none !important;
        line-height: 1.4 !important;
    }

    .qb-custom-context-menu .qb-ctx-item:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }

    .qb-custom-context-menu .qb-ctx-item.text-primary:hover {
        background-color: #eef2ff !important;
        color: #4338ca !important;
    }

    .qb-custom-context-menu .qb-ctx-item i {
        font-size: 13px !important;
        width: 16px !important;
        text-align: center !important;
        flex-shrink: 0 !important;
    }

    .qb-custom-context-menu .qb-ctx-divider {
        height: 1px !important;
        background-color: #e2e8f0 !important;
        margin: 4px 0 !important;
    }

    #productTable thead th {
        cursor: context-menu;
    }

    /* ══════════════════════════════════════════════════════════
       38mm x 26mm Label Live Preview inside Modal (Photo Match)
       ══════════════════════════════════════════════════════════ */
    .label-sticker-preview {
        width: 195px;
        height: 133px;
        background: #ffffff;
        border: 1px solid #000000;
        border-radius: 6px;
        padding: 12px 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        text-align: center;
        overflow: hidden;
        box-sizing: border-box;
        user-select: none;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    /* Line 1: Unique Variant Serial No */
    .label-sticker-preview .sticker-line-serial {
        font-size: 18px;
        font-weight: 900;
        color: #000000;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        line-height: 1.2;
        letter-spacing: 0.5px;
    }

    /* Line 2: P-CODE */
    .label-sticker-preview .sticker-line-2 {
        font-size: 15px;
        font-weight: 800;
        color: #000000;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        line-height: 1.2;
        letter-spacing: 0.3px;
    }

    /* Line 3: LOCATION */
    .label-sticker-preview .sticker-line-3 {
        font-size: 13px;
        font-weight: 700;
        color: #000000;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        line-height: 1.2;
    }

    .lbl-var-row {
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .lbl-var-row:hover {
        background-color: #f8fafc !important;
    }
    .lbl-var-row.table-active {
        background-color: #eff6ff !important;
        border-left: 3px solid #2563eb !important;
    }

    /* ══════════════════════════════════════════════════════════
       QUICKBOOKS POINT OF SALE DESKTOP 12.0 STYLE SYSTEM
       ══════════════════════════════════════════════════════════ */
    .qb-main-toolbar {
        background: #ffffff;
        border-bottom: 2px solid #e2e8f0;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .qb-btn-iwantto {
        background: #16a34a !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        border: none !important;
        border-radius: 6px;
        padding: 7px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        cursor: pointer;
        transition: background 0.15s;
    }
    .qb-btn-iwantto:hover { background: #15803d !important; color: #ffffff !important; }
    
    .qb-iwantto-menu {
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border: 1px solid #cbd5e1;
        padding: 6px 0;
        min-width: 220px;
        z-index: 1050;
    }
    .qb-iwantto-menu .dropdown-item {
        font-size: 12.5px;
        padding: 7px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1e293b;
        font-weight: 500;
    }
    .qb-iwantto-menu .dropdown-item:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .qb-iwantto-menu .dropdown-item i {
        width: 16px;
        text-align: center;
        color: #475569;
    }
    .qb-iwantto-menu .dropdown-divider { margin: 4px 0; }

    .qb-search-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex: 1 1 320px;
        max-width: 520px;
    }
    .qb-search-input-wrap {
        position: relative;
        flex: 1;
    }
    .qb-search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }
    .qb-search-input {
        width: 100%;
        height: 36px;
        padding: 0 12px 0 34px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 13px;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        transition: all 0.15s;
    }
    .qb-search-input:focus {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
    .qb-filter-select {
        height: 36px;
        padding: 0 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 12.5px;
        color: #334155;
        background: #ffffff;
        outline: none;
    }

    .qb-toolbar-btn {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 13px;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none !important;
        white-space: nowrap;
    }
    .qb-toolbar-btn:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .qb-toolbar-btn.active {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #1d4ed8;
    }
    .qb-toolbar-btn-primary {
        background: #2563eb;
        color: #ffffff !important;
        border-color: #2563eb;
    }
    .qb-toolbar-btn-primary:hover {
        background: #1d4ed8;
        color: #ffffff !important;
    }

    /* ── Split Screen: Item List Table + Show Details Drawer ── */
    .qb-workspace-wrap {
        display: flex;
        align-items: stretch;
        width: 100%;
        min-height: 580px;
        position: relative;
        overflow: hidden;
    }
    .qb-table-pane {
        flex: 1 1 auto;
        min-width: 0;
        transition: all 0.25s ease-in-out;
        overflow-x: auto;
    }
    .qb-details-pane {
        width: 420px;
        min-width: 380px;
        max-width: 450px;
        background: #f8fafc;
        border-left: 1px solid #e2e8f0;
        display: none; /* toggled via js */
        flex-direction: column;
        transition: all 0.25s ease-in-out;
        z-index: 20;
        box-shadow: -4px 0 20px rgba(15, 23, 42, 0.06);
    }
    .qb-details-pane.open {
        display: flex;
    }

    /* Selected Table Row (QuickBooks Desktop Sky Blue) */
    #productTable tbody tr.row-qb-selected td {
        background-color: #93c5fd !important;
        color: #1e3a8a !important;
        font-weight: 700;
    }

    /* ── Details Drawer Interior ── */
    .qb-drawer-header {
        padding: 12px 16px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .qb-badge-code {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        font-size: 11px;
        padding: 2.5px 7px;
        border-radius: 6px;
        letter-spacing: 0.3px;
        flex-shrink: 0;
    }
    .qb-drawer-close-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .qb-drawer-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* Modern Segmented Control Tab Bar */
    .qb-drawer-tabs-wrapper {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 14px;
    }
    .qb-drawer-segmented-tabs {
        display: flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 8px;
        gap: 4px;
        border: 1px solid #e2e8f0;
    }
    .qb-drawer-tab-btn {
        flex: 1;
        padding: 6px 12px;
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .qb-drawer-tab-btn:hover {
        color: #1e293b;
    }
    .qb-drawer-tab-btn.active {
        background: #ffffff;
        color: #0f172a;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    /* Drawer Scrollable Body */
    .qb-drawer-body {
        padding: 14px;
        overflow-y: auto;
        flex: 1 1 auto;
        max-height: calc(100vh - 190px);
    }
    .qb-drawer-body::-webkit-scrollbar {
        width: 5px;
    }
    .qb-drawer-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .qb-drawer-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .qb-drawer-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Product Hero Mini Card */
    .qb-product-hero-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .qb-hero-img-wrap {
        width: 56px;
        height: 56px;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .qb-hero-info {
        flex: 1;
        min-width: 0;
    }
    .qb-hero-name {
        font-weight: 700;
        font-size: 13.5px;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .qb-hero-meta {
        font-size: 11.5px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .qb-hero-kpi-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .qb-hero-kpi-chip small {
        font-size: 10px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .qb-kpi-price {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .qb-kpi-stock {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* Section Accordion Cards in Details */
    .qb-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: border-color 0.15s ease;
    }
    .qb-section-card:hover {
        border-color: #cbd5e1;
    }
    .qb-section-head {
        padding: 9px 12px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        font-size: 11.5px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: background 0.15s ease;
    }
    .qb-section-head:hover {
        background: #f8fafc;
    }
    .qb-section-head .head-title {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .qb-icon-bubble {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10.5px;
    }
    .qb-bubble-blue { background: #eff6ff; color: #2563eb; }
    .qb-bubble-purple { background: #f5f3ff; color: #7c3aed; }
    .qb-bubble-indigo { background: #eef2ff; color: #4f46e5; }
    .qb-bubble-amber { background: #fffbeb; color: #d97706; }
    .qb-bubble-emerald { background: #ecfdf5; color: #059669; }
    .qb-bubble-cyan { background: #ecfeff; color: #0891b2; }

    .qb-section-head i.chevron {
        transition: transform 0.2s ease;
        font-size: 10px;
        color: #94a3b8;
    }
    .qb-section-card.collapsed .qb-section-body { display: none; }
    .qb-section-card.collapsed .qb-section-head { border-bottom: none; }
    .qb-section-card.collapsed .qb-section-head i.chevron { transform: rotate(-90deg); }

    .qb-section-body {
        padding: 6px 12px 10px 12px;
        font-size: 12px;
    }
    .qb-data-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
        gap: 12px;
    }
    .qb-data-row:last-child {
        border-bottom: none;
    }
    .qb-data-label {
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
        font-size: 11.5px;
    }
    .qb-data-val {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
        font-size: 12px;
    }
    .qb-code-pill {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 1.5px 6px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }
    .qb-price-regular {
        color: #059669 !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
    }
    .qb-price-wholesale {
        color: #0284c7 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
    }
    .qb-stock-pill {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 1.5px 8px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }

    /* ── Slide ON (Green) / Slide OFF (Red) Switch ── */
    .qb-price-slider-wrap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        user-select: none;
        padding: 2px 4px;
        border-radius: 20px;
        transition: all 0.2s ease;
    }
    .qb-price-slider-wrap:hover {
        background: rgba(0, 0, 0, 0.03);
    }
    .qb-price-slider-label {
        font-size: 11px;
        font-weight: 700;
        color: #ef4444; /* Red when OFF */
        letter-spacing: 0.2px;
        transition: color 0.25s ease;
    }
    .qb-price-slider-wrap.active .qb-price-slider-label {
        color: #16a34a; /* Green when ON */
    }
    .qb-price-switch {
        width: 36px;
        height: 19px;
        background-color: #ef4444; /* RED when OFF */
        border-radius: 20px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25);
        display: inline-block;
        flex-shrink: 0;
    }
    .qb-price-switch .qb-price-switch-knob {
        width: 15px;
        height: 15px;
        background-color: #ffffff;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
    }
    /* When active: Green background and knob slides to right */
    .qb-price-switch.active {
        background-color: #16a34a !important; /* GREEN when ON */
    }
    .qb-price-switch.active .qb-price-switch-knob {
        transform: translateX(17px); /* Slides to right */
    }

    /* ── Upgraded Professional Matrix Table ── */
    .qb-matrix-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 11.5px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-top: 4px;
    }
    .qb-matrix-table th, .qb-matrix-table td {
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 7px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .qb-matrix-table th:last-child, .qb-matrix-table td:last-child {
        border-right: none;
    }
    .qb-matrix-table tbody tr:last-child td {
        border-bottom: 1.5px solid #cbd5e1;
    }
    .qb-matrix-table thead th {
        background: #f8fafc;
        font-weight: 700;
        color: #334155;
        padding: 8px 10px;
    }
    .qb-matrix-table th.qb-corner-cell {
        background: #f1f5f9;
        text-align: left;
        width: 32%;
        padding: 8px 10px;
    }
    .matrix-corner-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 10px;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.5px;
    }
    .matrix-corner-title .matrix-slash {
        color: #94a3b8;
        font-weight: 400;
    }
    .matrix-attr-chip {
        display: inline-block;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-weight: 700;
        font-size: 11px;
        padding: 2px 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        letter-spacing: 0.3px;
    }
    .matrix-total-hdr {
        font-size: 10.5px;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.6px;
    }
    .qb-matrix-table td.qb-row-label {
        background: #f8fafc;
        text-align: left;
        padding: 7px 10px;
    }
    .matrix-size-badge {
        font-weight: 700;
        color: #0f172a;
        font-size: 11.5px;
    }
    .matrix-stock-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
        transition: all 0.15s ease;
    }
    .matrix-stock-chip.in-stock {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.06);
    }
    .matrix-stock-chip.in-stock:hover {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
        transform: translateY(-1px);
    }
    .matrix-stock-chip.zero-stock {
        background: #f8fafc;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        font-size: 11px;
    }
    .qb-matrix-table .qb-total-cell {
        background: #f8fafc;
        font-weight: 800;
        color: #0f172a;
    }
    .matrix-row-total {
        font-weight: 800;
        color: #0f172a;
        font-size: 12px;
    }
    .qb-matrix-table tfoot tr {
        background: #f1f5f9;
    }
    .qb-matrix-table td.qb-total-label {
        background: #f1f5f9;
        font-weight: 800;
        color: #1e293b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: left;
        padding: 8px 10px;
        border-bottom: none;
    }
    .qb-matrix-table td.qb-col-total {
        background: #f1f5f9;
        font-weight: 800;
        color: #0f172a;
        border-bottom: none;
    }
    .matrix-col-total {
        font-weight: 800;
        color: #0f172a;
        font-size: 12px;
    }
    .qb-matrix-table td.qb-grand-total {
        background: #e0e7ff;
        border-bottom: none;
        padding: 6px 8px;
    }
    .matrix-grand-chip {
        display: inline-block;
        background: #3730a3;
        color: #ffffff;
        font-weight: 800;
        font-size: 12px;
        padding: 3px 12px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(55, 48, 163, 0.25);
    }

    /* Modern Edit Matrix Button */
    .qb-btn-edit-matrix {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        border: none !important;
        color: #ffffff !important;
        padding: 5px 14px !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        border-radius: 20px !important;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2) !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease !important;
    }
    .qb-btn-edit-matrix:hover:not(:disabled) {
        background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.28) !important;
    }
    .matrix-indicator-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3b82f6;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    /* Memo notes pad */
    .qb-notes-memo {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 8px;
        padding: 10px;
        font-family: inherit;
        font-size: 12px;
        color: #78350f;
        min-height: 85px;
        width: 100%;
        resize: vertical;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .qb-notes-memo:focus {
        border-color: #f59e0b;
        background: #ffffff;
    }

    /* Interactive Edit Style Matrix inputs */
    .qb-edit-matrix-input {
        width: 60px;
        height: 28px;
        text-align: center;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-weight: 700;
        font-size: 12px;
        padding: 0 4px;
        outline: none;
    }

    /* ══════════════════════════════════════════════════════════════
       Physical Inventory Executive ERP Audit Document Styles (Clean Software Theme)
       ══════════════════════════════════════════════════════════════ */
    .doc-modal-content {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        background: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .doc-modal-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 20px;
        color: #0f172a;
    }
    .doc-hdr-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 16px;
        flex-shrink: 0;
    }
    .doc-title-main {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.25px;
        line-height: 1.25;
        margin: 0;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .doc-header-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 2.5px 9px;
        font-size: 11px;
        color: #475569;
        font-weight: 600;
        letter-spacing: 0.15px;
    }
    .doc-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3.5px 10px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .doc-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }
    .doc-btn-close {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .doc-btn-close:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }
    /* ── Executive Dual Dock Cards (Clean Modern SaaS & ERP Toolbar) ── */
    .pi-dock-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .pi-dock-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 3px 8px -1px rgba(15, 23, 42, 0.06);
    }
    .pi-dock-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 7px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 40px;
    }
    .pi-dock-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        flex-shrink: 0;
    }
    .pi-dock-icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }
    .pi-dock-icon-emerald {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .pi-dock-title {
        font-family: 'Inter', sans-serif;
        font-size: 11.5px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.35px;
        text-transform: uppercase;
    }
    .pi-dock-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
        margin-left: 3px;
    }
    .pi-dock-body {
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: #ffffff;
        flex: 1;
    }
    .pi-btn-due-pill {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1.3;
    }
    .pi-btn-due-pill:hover {
        background: #fef3c7;
        color: #78350f;
        border-color: #fcd34d;
    }
    .pi-status-banner {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.05);
    }
    .doc-badge-count {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 2.5px 8px;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .doc-badge-count.has-selection {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }
    .doc-btn-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2.5px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 5px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1.3;
        outline: none !important;
    }
    .doc-btn-chip:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .doc-btn-chip-primary {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }
    .doc-btn-chip-primary:hover {
        background: #dbeafe;
        border-color: #93c5fd;
        color: #1e40af;
    }

    /* ── Bulletproof Modern ERP Control Inputs (Zero Bootstrap dependencies) ── */
    .pi-control-item {
        display: inline-flex;
        align-items: stretch;
        height: 34px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .pi-control-item:focus-within {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }
    .pi-control-label {
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        color: #475569;
        font-family: 'Inter', sans-serif;
        font-size: 11.5px;
        font-weight: 600;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        user-select: none;
    }
    .pi-control-select {
        flex: 1 1 auto;
        border: none !important;
        background: #ffffff;
        color: #0f172a;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        padding: 0 28px 0 10px;
        outline: none !important;
        box-shadow: none !important;
        cursor: pointer;
        height: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 10px 10px;
    }
    .pi-control-input {
        flex: 1 1 auto;
        border: none !important;
        background: #ffffff;
        color: #0f172a;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        padding: 0 10px;
        outline: none !important;
        box-shadow: none !important;
        height: 100%;
    }
    .pi-control-btn {
        border: none;
        border-left: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s ease;
        font-size: 11px;
    }
    .pi-control-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .pi-btn-primary-action {
        height: 34px;
        padding: 0 10px;
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #1d4ed8;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 11.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        outline: none !important;
    }
    .pi-btn-primary-action:hover {
        background: #1d4ed8;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    }

    /* Segmented Mode Pill Toggle (Top Right) */
    .qb-mode-segmented-control {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 3px;
        gap: 3px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    .qb-mode-pill-btn {
        border: none;
        background: transparent;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        padding: 5px 15px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none !important;
        line-height: 1.25;
        user-select: none;
    }
    .qb-mode-pill-btn:hover:not(.active) {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.8);
    }
    .qb-mode-pill-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.32);
    }

    /* Modern Select2 Multi-Select Box Styling */
    /* Modern Compact Auto-Expanding Select2 Multi-Select */
    .doc-select2-box-wrap {
        position: relative;
        width: 440px;
        max-width: 100%;
    }
    #qbPhysicalInventoryModal .select2-container {
        width: 100% !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple {
        min-height: 30px !important;
        height: auto !important;
        max-height: 90px !important;
        overflow-y: auto !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        padding: 1px 22px 1px 5px !important;
        position: relative !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
        cursor: text !important;
        display: block !important;
    }
    #qbPhysicalInventoryModal .select2-container--default.select2-container--focus .select2-selection--multiple,
    #qbPhysicalInventoryModal .select2-container--default.select2-container--open .select2-selection--multiple {
        border-color: #2563eb !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }
    #qbPhysicalInventoryModal ul.select2-selection__rendered,
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        margin: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding: 0 !important;
        padding-left: 0 !important;
        list-style: none !important;
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 2px 4px !important;
        width: 100% !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #eff6ff !important;
        border: 1px solid #bfdbfe !important;
        border-radius: 4px !important;
        color: #1d4ed8 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        padding: 1px 6px 1px 4px !important;
        margin: 1px 0 !important;
        height: 22px !important;
        line-height: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.04);
        box-sizing: border-box !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
        background: #dbeafe !important;
        border-color: #93c5fd !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ef4444 !important;
        font-weight: 700 !important;
        margin: 0 2px 0 0 !important;
        border: none !important;
        background: rgba(239, 68, 68, 0.12) !important;
        border-radius: 50% !important;
        width: 13px !important;
        height: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 9.5px !important;
        line-height: 13px !important;
        cursor: pointer !important;
        padding: 0 !important;
        transition: all 0.15s ease;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: #ef4444 !important;
        color: #ffffff !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-search--inline {
        margin: 0 !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        flex: 1 1 auto !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-search--inline .select2-search__field {
        margin: 1px 0 !important;
        padding: 0 3px !important;
        font-size: 11.5px !important;
        color: #1e293b !important;
        font-family: inherit !important;
        height: 20px !important;
        line-height: 20px !important;
        border: none !important;
        outline: none !important;
        background: transparent !important;
        min-width: 60px !important;
        box-shadow: none !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-search--inline .select2-search__field::placeholder {
        color: #94a3b8 !important;
        font-size: 11.5px !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__clear {
        position: absolute !important;
        right: 6px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 12px !important;
        color: #94a3b8 !important;
        line-height: 1 !important;
        cursor: pointer !important;
        z-index: 2;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
        color: #ef4444 !important;
    }
    #qbPhysicalInventoryModal .select2-dropdown {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.18) !important;
        z-index: 999999 !important;
        overflow: hidden;
        background: #ffffff;
        margin-top: 4px;
    }
    .qb-pi-dropdown-search-wrap {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 6px 8px;
    }
    .qb-pi-dropdown-search-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
        outline: none !important;
    }
    #qbPhysicalInventoryModal .select2-results__options {
        max-height: 260px;
        padding: 4px;
    }
    #qbPhysicalInventoryModal .select2-results__option {
        font-size: 12.5px;
        padding: 8px 12px;
        border-radius: 6px;
        margin-bottom: 2px;
        transition: background-color 0.15s ease;
        color: #1e293b;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }
    #qbPhysicalInventoryModal .select2-container--default .select2-results__option--selected {
        background-color: #f1f5f9;
        color: #64748b;
    }

    /* Barcode tools dock */
    .doc-tools-dock {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .doc-input-prefix-wrap {
        position: relative;
        flex: 1 1 260px;
        min-width: 220px;
    }
    .doc-prefix-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 5;
        font-size: 13px;
    }
    .doc-btn-action-tool {
        height: 36px;
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-weight: 600;
        font-size: 11.5px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        outline: none !important;
    }
    .doc-btn-action-tool:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .doc-btn-action-tool:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
    }
    .doc-status-summary-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 11.5px;
        font-weight: 600;
        height: 36px;
    }
    .doc-btn-primary {
        height: 36px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: 1px solid #1d4ed8;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12.5px;
        letter-spacing: 0.3px;
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .doc-btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        color: #ffffff;
    }
    .doc-btn-secondary {
        height: 36px;
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .doc-btn-secondary:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .doc-table-sheet-wrap {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.03);
        max-height: 500px;
        overflow-y: auto;
        overflow-x: auto;
    }
    #qbPiTable {
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        font-size: 12px;
        margin-bottom: 0;
        table-layout: auto;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    #qbPiTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 9px 10px !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-right: 1px solid #f1f5f9 !important;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
        box-sizing: border-box;
        user-select: none;
    }
    #qbPiTable thead th:last-child {
        border-right: none !important;
    }
    #qbPiTable tbody td {
        padding: 7px 10px !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-right: 1px solid #f8fafc !important;
        color: #1e293b;
        vertical-align: middle;
        font-size: 12px;
        line-height: 1.35;
        white-space: nowrap;
        box-sizing: border-box;
        transition: background-color 0.12s ease;
    }
    #qbPiTable tbody td:last-child {
        border-right: none !important;
    }
    #qbPiTable tbody tr:nth-child(even) td {
        background-color: #dbe7f4 !important; /* Distinct Darker QuickBooks Cool Blue Stripe */
    }
    #qbPiTable tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    #qbPiTable tbody tr:hover td {
        background-color: #eff6ff !important;
    }
    #qbPiTable tbody tr:hover td:first-child {
        box-shadow: inset 3.5px 0 0 #2563eb;
    }
    #qbPiTable tbody tr.pi-row-flash td {
        background-color: #dbeafe !important;
        animation: piFlash 1.5s ease-out;
    }
    @keyframes piFlash {
        0% { background-color: #93c5fd; }
        100% { background-color: #fafbfd; }
    }
    .doc-sku-pill {
        font-family: 'JetBrains Mono', SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        background: #f1f5f9;
        color: #334155;
        padding: 2.5px 7px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        font-size: 11px;
        display: inline-block;
        letter-spacing: -0.2px;
    }
    /* Modern Software Badges */
    .pi-cat-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        background: #f5f3ff;
        color: #5b21b6;
        border: 1px solid #ede9fe;
        padding: 2.5px 8px;
        border-radius: 5px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .pi-barcode-badge {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 500;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 2.5px 7px;
        border-radius: 4px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .pi-attr-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        background: #f8fafc;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        display: inline-block;
    }
    .pi-size-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        display: inline-block;
    }
    .pi-location-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #dcfce7;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .pi-velocity-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 10.5px;
        padding: 2.5px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.1px;
    }
    .pi-velocity-a {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fee2e2;
    }
    .pi-velocity-b {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
    }
    .pi-velocity-c {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .pi-stock-badge {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        background: #f8fafc;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        padding: 2.5px 8px;
        border-radius: 4px;
        font-size: 11.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .pi-stock-badge.zero {
        color: #94a3b8;
        background: #f8fafc;
    }
    .qb-pi-stepper {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: all 0.15s ease;
    }
    .qb-pi-stepper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }
    .qb-pi-stepper-btn {
        width: 25px;
        height: 27px;
        border: none;
        background: #f8fafc;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s ease;
        user-select: none;
    }
    .qb-pi-stepper-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .qb-pi-count-input {
        width: 54px;
        height: 27px;
        border: none;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        text-align: center;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        font-size: 12.5px;
        color: #0f172a;
        outline: none;
        background: #ffffff;
    }
    .qb-pi-count-input:focus {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .variance-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        min-width: 68px;
        justify-content: center;
        padding: 3px 8px;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        font-size: 11px;
        border-radius: 5px;
        line-height: 1.25;
        letter-spacing: -0.2px;
    }
    .variance-zero {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .variance-surplus {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .variance-shortage {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .doc-summary-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-top: 12px;
    }
    .doc-summary-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .doc-summary-kpi-card:hover {
        box-shadow: 0 3px 8px -1px rgba(15, 23, 42, 0.06);
    }
    .doc-kpi-icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .doc-summary-kpi-label {
        font-family: 'Inter', sans-serif;
        font-size: 10.5px;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .doc-summary-kpi-val {
        font-family: 'JetBrains Mono', monospace;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.15;
        color: #0f172a;
    }
    .doc-btn-apply {
        height: 38px;
        padding: 0 20px;
        background: #0f172a;
        color: #ffffff;
        border: 1px solid #0f172a;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.2px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.1);
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .doc-btn-apply:hover {
        background: #1e293b;
        border-color: #1e293b;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        transform: translateY(-0.5px);
    }
    .doc-btn-discard {
        height: 38px;
        padding: 0 16px;
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .doc-btn-discard:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* ── Fixed Bottom Status Bar (QuickBooks POS Desktop Style) ── */
    .qb-fixed-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        min-height: 40px;
        background: #ffffff;
        border-top: 1px solid #cbd5e1;
        box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.08);
        z-index: 1040;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 20px;
        font-size: 12.5px;
        color: #334155;
        user-select: none;
    }
    .qb-status-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        white-space: nowrap;
    }
    .qb-status-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }
    .qb-status-title {
        font-weight: 600;
        color: #64748b;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .qb-status-num {
        font-size: 13.5px;
        font-weight: 800;
    }
    .qb-status-sub {
        font-size: 11px;
        color: #64748b;
    }
    .qb-status-divider {
        width: 1px;
        height: 18px;
        background: #e2e8f0;
        margin: 0 10px;
        flex-shrink: 0;
    }
    /* Clearance so fixed footer doesn't overlap pagination or content */
    .main-content {
        padding-top: 6px !important;
        margin-top: 0 !important;
        padding-bottom: 50px !important;
    }
    .main-content-inner {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .container-fluid {
        padding-top: 0 !important;
    }
    .erp-card {
        margin-top: 0 !important;
    }
    @media (max-width: 991px) {
        .qb-fixed-bottom-bar {
            padding: 6px 12px;
            overflow-x: auto;
            white-space: nowrap;
        }
        .qb-status-items-wrap {
            display: flex;
            align-items: center;
            gap: 4px;
        }
    }

    /* ==============================================================
       QUICKBOOKS & ODOO ERP PRODUCT IMPORT MODAL DESIGN SYSTEM (V2)
       ============================================================== */
    #importModal .qb-modal-content {
        border-radius: 10px !important;
        overflow: hidden !important;
        border: 1px solid #0f2744 !important;
        box-shadow: 0 20px 45px -10px rgba(15, 39, 68, 0.45) !important;
        background: #ffffff !important;
        max-height: calc(100vh - 40px) !important;
        display: flex !important;
        flex-direction: column !important;
    }

    #importModal .qb-modal-header {
        background: linear-gradient(180deg, #18385e 0%, #0f2744 100%) !important;
        border-bottom: 2px solid #0077c5 !important;
        padding: 12px 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        color: #ffffff !important;
        flex-shrink: 0 !important;
    }

    #importModal .qb-modal-icon-badge {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        font-size: 16px;
        flex-shrink: 0;
    }

    #importModal .qb-modal-title {
        font-size: 15px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        letter-spacing: 0.2px !important;
        line-height: 1.3 !important;
        margin: 0 !important;
    }

    #importModal .qb-modal-subtitle {
        font-size: 11.5px !important;
        font-weight: 500 !important;
        color: #93c5fd !important;
        line-height: 1.3 !important;
        margin-top: 2px !important;
        display: block !important;
    }

    #importModal .qb-modal-close {
        background: transparent !important;
        border: none !important;
        color: #ffffff !important;
        font-size: 18px !important;
        line-height: 1 !important;
        opacity: 0.8 !important;
        cursor: pointer !important;
        padding: 6px 10px !important;
        border-radius: 4px !important;
        transition: all 0.15s ease !important;
    }
    #importModal .qb-modal-close:hover {
        opacity: 1 !important;
        background: rgba(255, 255, 255, 0.18) !important;
        color: #ffffff !important;
    }

    /* Template Action Cards (Structured & Clean) */
    .qb-template-card-box {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 12px 14px;
        transition: all 0.18s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .qb-template-card-box:hover {
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37,99,235,0.12);
        transform: translateY(-1px);
    }
    .qb-template-card-box.qb-card-excel:hover {
        border-color: #16a34a;
        box-shadow: 0 4px 12px rgba(22,163,74,0.14);
    }

    .qb-template-icon-square {
        width: 36px;
        height: 36px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .qb-template-icon-excel {
        background: #ecfdf5;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .qb-template-icon-csv {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .qb-template-card-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin: 0;
    }
    .qb-template-card-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
        margin: 6px 0 10px 0;
        display: block;
    }

    /* Engine Safeguard Pills */
    .qb-feat-pill {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 10px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        height: 100%;
        font-size: 11px;
        line-height: 1.35;
    }
    .qb-feat-pill i {
        font-size: 14px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .qb-feat-pill strong {
        display: block;
        color: #1e293b;
        font-size: 11px;
        margin-bottom: 1px;
    }
    .qb-feat-pill span {
        color: #64748b;
        font-size: 10.5px;
    }

    /* Modern Dropzone */
    .qb-dropzone {
        border: 2px dashed #93c5fd;
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        padding: 14px 16px;
    }
    .qb-dropzone:hover, .qb-dropzone.dragover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .qb-dropzone-icon {
        font-size: 28px;
        color: #2563eb;
        line-height: 1;
        margin-bottom: 6px;
    }
    .qb-dropzone-title {
        font-size: 12.5px;
        color: #1e293b;
        font-weight: 500;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    .qb-dropzone-preview {
        background: #f8fafc;
        border-radius: 6px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
    }
    .qb-file-badge-icon {
        width: 34px;
        height: 34px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    /* Modal Footer */
    #importModal .qb-modal-footer {
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
        border-top: 1px solid #b2c5dc !important;
        padding: 10px 20px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 8px !important;
        flex-shrink: 0 !important;
    }
    #importModal .qb-modal-btn-cancel {
        background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%) !important;
        border: 1px solid #8ca4c3 !important;
        color: #334155 !important;
        font-weight: 700 !important;
        font-size: 11.5px !important;
        height: 32px !important;
        padding: 0 16px !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        text-decoration: none !important;
        transition: all 0.12s ease !important;
    }
    #importModal .qb-modal-btn-cancel:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
        border-color: #64748b !important;
    }
    #importModal .qb-modal-btn-save {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 12px !important;
        height: 32px !important;
        padding: 0 18px !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
        transition: filter 0.1s ease !important;
    }
    #importModal .qb-modal-btn-save:hover {
        filter: brightness(1.1) !important;
        color: #ffffff !important;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid px-3 pt-1 pb-3">

    {{-- ── Main Card ── --}}
    <div class="erp-card">

        {{-- ── QuickBooks Point of Sale Desktop 12.0 Main Toolbar ── --}}
        <div class="qb-main-toolbar">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Green "I Want to..." Button with dropdown --}}
                <div class="dropdown">
                    <button class="qb-btn-iwantto dropdown-toggle" type="button" id="qbIWantToDropdownBtn" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars"></i> I Want to...
                    </button>
                    <ul class="dropdown-menu qb-iwantto-menu" aria-labelledby="qbIWantToDropdownBtn">
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuToggleDetails"><i class="fas fa-columns text-primary"></i> <span id="qbMenuDetailsText">Show Details</span> <span class="badge bg-light text-muted border ms-auto font-monospace" style="font-size:10px;">F8</span></a></li>
                        <li><a class="dropdown-item" href="{{ url('create_prodcut') }}"><i class="fas fa-plus text-success"></i> Add Item</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuPrintTags"><i class="fas fa-barcode text-info"></i> Print Tags <span class="badge bg-light text-muted border ms-auto font-monospace" style="font-size:10px;">Ctrl+F10</span></a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuEditItem"><i class="fas fa-pencil-alt text-warning"></i> Edit Item</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuEditStyle"><i class="fas fa-th text-primary"></i> Edit Style Matrix</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuPhysicalInventory"><i class="fas fa-clipboard-check" style="color:#7c3aed;"></i> Physical Inventory</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.item_stock') }}"><i class="fas fa-chart-line text-secondary"></i> Inventory Reports</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="qbMenuCustomizeColumns"><i class="fas fa-table-columns text-dark"></i> Customize Columns...</a></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0)" id="qbMenuDeleteItem"><i class="fas fa-trash-alt text-danger"></i> Delete Item</a></li>
                    </ul>
                </div>

                {{-- Search Inventory input + filter dropdown --}}
                <div class="qb-search-group">
                    <div class="qb-search-input-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="qbSearchInventoryInput" class="qb-search-input" placeholder="Search Inventory (Item #, Name, Code, Attribute...)" value="{{ request('search') }}">
                    </div>
                    <select id="qbQuickFilterSelect" class="qb-filter-select">
                        <option value="all">All Items</option>
                        <option value="custom" {{ request()->hasAny(['category_id','brand_id']) ? 'selected' : '' }}>&lt;Custom Filter&gt;</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Items</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Items</option>
                    </select>
                </div>
            </div>

            {{-- Quick action buttons matching QuickBooks POS --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="qb-toolbar-btn" id="qbToggleDetailsBtn" title="Toggle Item Details Panel (F8)">
                    <i class="fas fa-columns text-primary"></i> <span id="qbToggleDetailsLabel">Show Details</span> <span class="text-muted" style="font-size:10.5px;">(F8)</span>
                </button>
                <a href="{{ url('create_prodcut') }}" class="qb-toolbar-btn qb-toolbar-btn-primary" title="Add New Inventory Item">
                    <i class="fas fa-plus"></i> Add Item
                </a>
                <button type="button" class="qb-toolbar-btn" id="qbEditSelectedBtn" title="Edit Currently Selected Item">
                    <i class="fas fa-pencil-alt text-primary"></i> Edit
                </button>
                <button type="button" class="qb-toolbar-btn position-relative" id="qbPhysicalInventoryBtn" title="Start Physical Inventory Cycle Count">
                    <i class="fas fa-clipboard-check" style="color:#7c3aed;"></i> Physical Inventory
                    @if(isset($dueCycleCount) && $dueCycleCount > 0)
                        <span class="badge rounded-pill bg-danger ms-1 text-white qb-due-counter-badge" style="font-size:10px; font-weight:700;" title="{{ $dueCycleCount }} items due for cycle count today!">
                            {{ $dueCycleCount }} Due
                        </span>
                    @endif
                </button>
                <button type="button" class="qb-toolbar-btn" id="customizeIndexColumnsBtn" data-toggle="modal" data-target="#customizeIndexColumnsModal" data-bs-toggle="modal" data-bs-target="#customizeIndexColumnsModal" title="Customize Columns (or right-click header)">
                    <i class="fas fa-columns text-secondary"></i> Columns
                </button>
                <div class="dropdown">
                    <button class="qb-toolbar-btn dropdown-toggle" type="button" id="qbMoreActionsDropdown" data-bs-toggle="dropdown" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i> More
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:8px; font-size:12.5px;">
                        <li><a class="dropdown-item" href="{{ route('opening_stock.index') }}"><i class="fas fa-cubes text-primary me-2"></i> Opening Stock</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.template.quickbooks') }}"><i class="fas fa-file-excel text-success me-2"></i> Client Template (QuickBooks / Item #)</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.template') }}"><i class="fas fa-file-csv text-secondary me-2"></i> Full ERP Template CSV</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.export') }}"><i class="fas fa-file-download text-success me-2"></i> Export to CSV</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="openImportModalBtn"><i class="fas fa-file-upload text-warning me-2"></i> Import Excel / CSV</a></li>
                    </ul>
                </div>
            </div>
        </div>


        {{-- ── Hidden Filter Form for Toolbar Search & Status Select ── --}}
        <form method="GET" action="{{ route('product') }}" id="filterForm" style="display: none;">
            <input type="hidden" name="search" id="hiddenSearchInput" value="{{ request('search') }}">
            <input type="hidden" name="status" id="hiddenStatusInput" value="{{ request('status') }}">
            <input type="hidden" name="category_id" id="hiddenCategoryInput" value="{{ request('category_id') }}">
        </form>

        {{-- ── Due Cycle Count Alert Banner ── --}}
        @if(isset($dueCycleCount) && $dueCycleCount > 0)
        <div class="mx-3 mt-2 mb-1 p-2 px-3 alert alert-warning d-flex align-items-center justify-content-between flex-wrap gap-2 border shadow-sm" id="qbMainPageDueBanner" style="border-radius: 8px; background: #fffbeb; border-color: #fde68a; color: #92400e;">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
                <span style="font-size: 13px;">
                    <strong>Cycle Count Due:</strong> <span class="badge bg-danger text-white qb-due-counter">{{ $dueCycleCount }}</span> product(s) are due for physical inventory counting today based on sales velocity!
                </span>
            </div>
            <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1" id="qbMainBannerCountDueBtn" style="font-size: 12px; border-radius: 6px;">
                <i class="fas fa-clipboard-check me-1"></i> Count Due Products Now
            </button>
        </div>
        @endif

        {{-- ── Success & Error Alerts ── --}}
        @if (session()->has('success'))
        <div class="mx-4 mt-3 alert d-flex align-items-center gap-2" style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; color:#065f46; font-size:.85rem; padding:10px 14px;">
            <i class="fas fa-check-circle" style="color:#059669; font-size:16px;"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mx-4 mt-3 alert alert-danger d-flex align-items-center gap-2" style="border-radius:8px; font-size:.85rem; padding:10px 14px;">
            <i class="fas fa-exclamation-circle text-danger" style="font-size:16px;"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="mx-4 mt-3 alert alert-danger" style="border-radius:8px; font-size:.85rem; padding:10px 14px;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size:16px;"></i>
                <strong>Import / Validation Errors:</strong>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        {{-- ── QuickBooks POS Workspace: Table + Show Details Drawer (F8) ── --}}
        <div class="qb-workspace-wrap">
            <div class="qb-table-pane">
                <div class="erp-table-wrap table-responsive">

                <table id="productTable" class="table table-hover align-middle nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:30px;" class="text-center" data-col-key="select"><input type="checkbox" id="selectAll" class="row-check"></th>
                            <th style="width:38px;" class="text-center" data-col-key="serial_num">Sr #</th>
                            <th style="width:125px;" class="text-center" data-col-key="item_unique_code">Item Unique Code</th>
                            <th style="width:36px;" class="text-center" data-col-key="image">Image</th>
                            <th data-col-key="item_details">Item Details</th>
                            <th style="width:75px;" class="text-center" data-col-key="stock">Stock</th>
                            <th style="width:82px;" class="text-end" data-col-key="cost_price">Cost</th>
                            <th style="width:82px;" class="text-end" data-col-key="sky_price">Price</th>
                            <th style="width:65px;" class="text-center" data-col-key="sky_pcode">Sky Code</th>
                            <th style="width:82px;" class="text-end" data-col-key="rot_price">Rot Price</th>
                            <th style="width:65px;" class="text-center" data-col-key="rot_pcode">Rot Code</th>
                            <th style="width:65px;" class="text-center" data-col-key="status">Status</th>
                            <th class="text-center" style="width:130px;" data-col-key="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            @php
                                $stockPieces = (float) ($product->warehouse_stocks_sum_total_pieces ?? 0);
                                $ppb = $product->pieces_per_box > 0 ? $product->pieces_per_box : 1;
                                if (($product->size_mode === 'by_cartons' || $product->size_mode === 'by_size') && $ppb > 1) {
                                    $boxes = floor($stockPieces / $ppb);
                                    $loose = $stockPieces % $ppb;
                                    $stockDisplay = $loose > 0 ? "{$boxes}.{$loose}" : "{$boxes}";
                                    $stockUnit    = $loose > 0 ? 'Box.Loose' : 'Boxes';
                                } else {
                                    $stockDisplay = $stockPieces;
                                    $stockUnit    = 'Pcs';
                                }
                                $stockClass = $stockPieces == 0 ? 'zero' : (($product->alert_carton_quantity && $stockPieces <= $product->alert_carton_quantity) ? 'low' : '');

                                if ($product->size_mode === 'by_size') {
                                    $m2 = ($product->height * $product->width) / 10000;
                                    $tradePrice  = $m2 * (float)$product->purchase_price_per_m2;
                                    $retailPrice = $m2 * (float)$product->price_per_m2;
                                } else {
                                    $tradePrice  = (float)$product->purchase_price_per_piece;
                                    $retailPrice = (float)$product->sale_price_per_piece ?: (float)$product->sale_price_per_box;
                                }
                            @endphp
                            <tr id="product-row-{{ $product->id }}" class="{{ $product->is_active ? '' : 'row-inactive' }}">
                                <td class="text-center"><input type="checkbox" class="selectProduct row-check" value="{{ $product->id }}"></td>
                                <td class="text-center text-muted" style="font-size:11px;">{{ $products->firstItem() + $key }}</td>
                                <td class="text-center">
                                    @if(!empty($product->item_number))
                                        <div class="mb-1"><span class="badge bg-primary text-white font-monospace" style="font-size:10px;">#{{ $product->item_number }}</span></div>
                                    @endif
                                    <span class="item-code">{{ $product->item_code }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($product->image)
                                        <img src="{{ asset('uploads/products/' . $product->image) }}"
                                            alt="{{ $product->item_name }}" class="product-img">
                                    @else
                                        <div class="no-img-badge"><i class="fas fa-image"></i></div>
                                    @endif
                                </td>
                                <td class="td-item-details">
                                    <div class="item-name">{{ $product->item_name }}</div>
                                    <div class="item-meta">
                                        @if($product->category_relation)
                                            <span class="meta-chip"><i class="fas fa-list" style="font-size:.6rem;"></i> {{ $product->category_relation->name }}</span>
                                        @endif
                                        @if($product->brand)
                                            <span class="meta-chip"><i class="fas fa-trademark" style="font-size:.6rem;"></i> {{ $product->brand->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="stock-badge {{ $stockClass }}">
                                        <i class="fas fa-cubes"></i>
                                        {{ $stockDisplay }}
                                        <span class="stock-unit">{{ $stockUnit }}</span>
                                    </span>
                                </td>
                                <td class="price-purchase text-end">Rs. {{ number_format($tradePrice, 2) }}</td>
                                <td class="price-sale text-end">Rs. {{ number_format($retailPrice, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size: 11px;" title="Sky P-Code">
                                        {{ \App\Services\PCodeService::encode($retailPrice) }}
                                    </span>
                                </td>
                                <td class="price-wholesale text-end">Rs. {{ number_format((float)($product->wholesale_price ?? 0), 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size: 11px;" title="Rot P-Code">
                                        {{ \App\Services\PCodeService::encode((float)($product->wholesale_price ?? 0)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($product->is_active)
                                        <span class="status-active" id="status-badge-{{ $product->id }}">Active</span>
                                    @else
                                        <span class="status-inactive" id="status-badge-{{ $product->id }}">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group align-items-center justify-content-center">
                                        <input type="radio" class="form-check-input row-matrix-radio d-none" name="row_matrix_radio" id="rowMatrixRadio_{{ $product->id }}" data-id="{{ $product->id }}">
                                        <button type="button" class="btn-act btn-act-view viewProductBtn"
                                            data-id="{{ $product->id }}" title="View Matrix & Details (F8)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn-act btn-act-edit" title="Edit Product">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endif
                                        <button type="button"
                                            class="btn-act btn-act-barcode openLabelPrintModalBtn"
                                            data-id="{{ $product->id }}"
                                            title="Print 38x26mm Labels (Ctrl+F10)">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                                            <button type="button"
                                                class="btn-act {{ $product->is_active ? 'btn-act-deact' : 'btn-act-act' }} toggle-active-btn"
                                                data-id="{{ $product->id }}"
                                                data-active="{{ $product->is_active ? '1' : '0' }}"
                                                data-name="{{ $product->item_name }}"
                                                title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        @endif
                                        @if (auth()->user()->can('products.delete') || auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                                            <button type="button"
                                                class="btn-act btn-act-delete delete-product-btn"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->item_name }}"
                                                title="Delete Product">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>{{-- /erp-table-wrap --}}
        </div>{{-- /qb-table-pane --}}

        {{-- ── QuickBooks POS Side Details Drawer ("Show Details - F8") ── --}}
        <div class="qb-details-pane" id="qbDetailsDrawer">
            <div class="qb-drawer-header">
                <div class="d-flex align-items-center gap-2" style="min-width:0;">
                    <span class="qb-badge-code" id="qbDrawerItemNum">#---</span>
                    <h6 class="mb-0 fw-bold text-dark text-truncate" id="qbDrawerItemTitle" style="font-size:13.5px;" title="Select item to view">Select an item</h6>
                </div>
                <button type="button" class="qb-drawer-close-btn" id="qbCloseDetailsDrawer" title="Hide Details (F8)">
                    <span>Hide (F8)</span> <i class="fas fa-times ms-1"></i>
                </button>
            </div>

            <div class="qb-drawer-tabs-wrapper">
                <div class="qb-drawer-segmented-tabs">
                    <button type="button" class="qb-drawer-tab-btn active" id="qbTabBtnStyle" data-tab="style">
                        <i class="fas fa-layer-group"></i> Style Detail
                    </button>
                    <button type="button" class="qb-drawer-tab-btn" id="qbTabBtnInventory" data-tab="inventory">
                        <i class="fas fa-info-circle"></i> Inventory Detail
                    </button>
                </div>
            </div>

            <div class="qb-drawer-body">
                <!-- TAB 1: Style Detail -->
                <div id="qbTabContentStyle">
                    <!-- Product Hero Card -->
                    <div class="qb-product-hero-card mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div id="qbDrawerImgWrap" class="qb-hero-img-wrap">
                                <i class="fas fa-image text-muted fs-4"></i>
                            </div>
                            <div class="qb-hero-info">
                                <div class="qb-hero-name" id="qbDrawerStyleName">No item selected</div>
                                <div class="qb-hero-meta" id="qbDrawerStyleMeta">Click any table row to inspect</div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="qb-hero-kpi-chip qb-kpi-price qb-price-hero" style="display:none;">
                                        <small class="text-muted">Price:</small> <span id="qbHeroPrice">Rs. 0.00</span>
                                    </span>
                                    <span class="qb-hero-kpi-chip qb-kpi-stock">
                                        <small class="text-muted">Stock:</small> <span id="qbHeroStock">0 Pcs</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Specifications Accordion -->
                    <div class="qb-section-card" id="qbCardItemInfo">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-blue"><i class="fas fa-sliders-h"></i></span> Item Specifications</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="qb-price-slider-wrap" id="qbPriceSliderWrap" onclick="toggleQbPriceSlider(event);" title="Click to Slide ON (Green) or Slide OFF (Red)">
                                    <span class="qb-price-slider-label" id="qbPriceSliderStatus">PRICES: OFF</span>
                                    <div class="qb-price-switch" id="qbPriceSwitchBtn">
                                        <div class="qb-price-switch-knob"></div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                        </div>
                        <div class="qb-section-body">
                            <div class="qb-data-row"><span class="qb-data-label">Type</span><span class="qb-data-val" id="qbValType">Inventory Part</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Department</span><span class="qb-data-val" id="qbValDepartment">—</span></div>
                            <div class="qb-data-row qb-price-row" style="display:none;"><span class="qb-data-label">Regular Price</span><span class="qb-data-val qb-price-regular" id="qbValRegularPrice">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">P-Code</span><span class="qb-data-val"><span class="qb-code-pill fw-bold" id="qbValCombinedPCode" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">—</span></span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Sky P-Code</span><span class="qb-data-val"><span class="qb-code-pill" id="qbValSkyPCode">—</span></span></div>
                            <div class="qb-data-row qb-price-row" style="display:none;"><span class="qb-data-label">Wholesale Price</span><span class="qb-data-val qb-price-wholesale" id="qbValWholesalePrice">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Rot P-Code</span><span class="qb-data-val"><span class="qb-code-pill" id="qbValRotPCode">—</span></span></div>
                            <div class="qb-data-row qb-price-row" style="display:none;"><span class="qb-data-label">Avg Cost</span><span class="qb-data-val" id="qbValAvgCost">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">On Hand Qty</span><span class="qb-data-val"><span class="qb-stock-pill" id="qbValOnHand">0 Pieces</span></span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Available Qty</span><span class="qb-data-val"><span class="qb-stock-pill" id="qbValAvailable">0 Pieces</span></span></div>
                        </div>
                    </div>

                    <!-- More Info Accordion -->
                    <div class="qb-section-card" id="qbCardMoreInfo">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-purple"><i class="fas fa-warehouse"></i></span> Warehouse &amp; Vendor</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="qb-section-body">
                            <div class="qb-data-row"><span class="qb-data-label">Vendor / Brand</span><span class="qb-data-val" id="qbValVendor">—</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">ALU / Barcode</span><span class="qb-data-val"><span class="qb-code-pill" id="qbValAlu">—</span></span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Location (Rack/Shelf)</span><span class="qb-data-val" id="qbValLocation">—</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Reorder Alert</span><span class="qb-data-val" id="qbValReorder">—</span></div>
                        </div>
                    </div>

                    <!-- Style Matrix Grid (Sizes vs Attributes) -->
                    <div class="qb-section-card" id="qbCardMatrix">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-indigo"><i class="fas fa-th"></i></span> Style Matrix (Sizes &times; Attributes)</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="qb-section-body p-2">
                            <div id="qbDrawerMatrixWrap" class="table-responsive" style="max-height:260px;">
                                <div class="text-center py-4 text-muted small fst-italic">
                                    Click any product row to display its Sizes &times; Attributes on-hand stock matrix.
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2.5 pt-2 border-top">
                                <div class="d-flex align-items-center gap-2 text-muted" style="font-size:11px;">
                                    <span class="matrix-indicator-dot"></span>
                                    <span>Stock per size &amp; attribute</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary qb-btn-edit-matrix" id="qbBtnEditStyle" disabled>
                                    <i class="fas fa-pencil-alt me-1"></i> Edit Style
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Inventory Item Detail -->
                <div id="qbTabContentInventory" style="display:none;">
                    <!-- Memo Notes Box -->
                    <div class="qb-section-card">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-amber"><i class="fas fa-sticky-note"></i></span> Item Notes &amp; Memo</span>
                        </div>
                        <div class="qb-section-body">
                            <textarea class="qb-notes-memo" id="qbDrawerNotesMemo" placeholder="Item notes, supplier details, or instructions..."></textarea>
                            <div class="text-end mt-1">
                                <span class="text-muted small" style="font-size:10.5px;" id="qbMemoSaveHint">Auto-synced with Remarks</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Box -->
                    <div class="qb-section-card">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-emerald"><i class="fas fa-chart-pie"></i></span> Statistics &amp; Performance</span>
                        </div>
                        <div class="qb-section-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted" style="font-size:11px;">Rating</span>
                                <div class="text-warning" style="font-size:12px;">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                            <div class="qb-data-row"><span class="qb-data-label">Net Sales</span><span class="qb-data-val text-success fw-bold" id="qbValNetSales">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Cost of Goods</span><span class="qb-data-val text-muted" id="qbValCostGoods">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Gross Profit</span><span class="qb-data-val text-primary fw-bold" id="qbValGrossProfit">Rs. 0.00</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Profit Margin</span><span class="qb-data-val" id="qbValProfitMargin"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">0.0%</span></span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Qty Received</span><span class="qb-data-val" id="qbValQtyReceived">0</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Qty Sold</span><span class="qb-data-val" id="qbValQtySold">0</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Adjustments</span><span class="qb-data-val" id="qbValQtyAdjusted">0</span></div>
                        </div>
                    </div>

                    <!-- Misc & Shipping -->
                    <div class="qb-section-card">
                        <div class="qb-section-head">
                            <span class="head-title"><span class="qb-icon-bubble qb-bubble-cyan"><i class="fas fa-truck"></i></span> Misc &amp; Packaging</span>
                        </div>
                        <div class="qb-section-body">
                            <div class="qb-data-row"><span class="qb-data-label">Weight per Piece</span><span class="qb-data-val" id="qbValWeight">0 kg</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Pieces per Box</span><span class="qb-data-val" id="qbValPiecesPerBox">1</span></div>
                            <div class="qb-data-row"><span class="qb-data-label">Dimensions (H &times; W)</span><span class="qb-data-val" id="qbValDimensions">—</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- /qb-workspace-wrap --}}

        {{-- ── Mobile Product Cards (Shown only on mobile < 768px for 100% user-friendly view) ── --}}
        <div class="mobile-product-cards">
            @foreach ($products as $product)
                @php
                    $stockPieces = (float) ($product->warehouse_stocks_sum_total_pieces ?? 0);
                    $ppb = $product->pieces_per_box > 0 ? $product->pieces_per_box : 1;
                    if (($product->size_mode === 'by_cartons' || $product->size_mode === 'by_size') && $ppb > 1) {
                        $boxes = floor($stockPieces / $ppb);
                        $loose = $stockPieces % $ppb;
                        $stockDisplay = $loose > 0 ? "{$boxes}.{$loose}" : "{$boxes}";
                        $stockUnit    = $loose > 0 ? 'Box.Loose' : 'Boxes';
                    } else {
                        $stockDisplay = $stockPieces;
                        $stockUnit    = 'Pcs';
                    }
                    $stockClass = $stockPieces == 0 ? 'zero' : (($product->alert_carton_quantity && $stockPieces <= $product->alert_carton_quantity) ? 'low' : '');

                    if ($product->size_mode === 'by_size') {
                        $m2 = ($product->height * $product->width) / 10000;
                        $tradePrice  = $m2 * (float)$product->purchase_price_per_m2;
                        $retailPrice = $m2 * (float)$product->price_per_m2;
                    } else {
                        $tradePrice  = (float)$product->purchase_price_per_piece;
                        $retailPrice = (float)$product->sale_price_per_piece ?: (float)$product->sale_price_per_box;
                    }
                @endphp
                <div class="prod-mcard {{ $product->is_active ? '' : 'row-inactive' }}" id="pmcard-{{ $product->id }}">
                    <div class="prod-mcard-hd">
                        <input type="checkbox" class="selectProduct row-check mt-1" value="{{ $product->id }}">
                        @if ($product->image)
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->item_name }}" class="product-img">
                        @else
                            <div class="no-img-badge"><i class="fas fa-image"></i></div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <div class="item-name mb-0 text-truncate">{{ $product->item_name }}</div>
                                @if($product->is_active)
                                    <span class="status-active" id="mstatus-badge-{{ $product->id }}">Active</span>
                                @else
                                    <span class="status-inactive" id="mstatus-badge-{{ $product->id }}">Inactive</span>
                                @endif
                            </div>
                            <div class="item-meta mt-1">
                                @if(!empty($product->item_number))
                                    <span class="badge bg-primary text-white font-monospace me-1" style="font-size:10px;">#{{ $product->item_number }}</span>
                                @endif
                                <span class="item-code">{{ $product->item_code }}</span>
                                @if($product->category_relation)
                                    <span class="meta-chip"><i class="fas fa-list"></i> {{ $product->category_relation->name }}</span>
                                @endif
                                @if($product->brand)
                                    <span class="meta-chip"><i class="fas fa-trademark"></i> {{ $product->brand->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="prod-mcard-body">
                        <div>
                            <div style="font-size:.68rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Sale Price</div>
                            <div class="prod-mcard-price">Rs. {{ number_format($retailPrice, 2) }}</div>
                            <div style="font-size:.7rem; color:var(--erp-muted);">Cost: Rs. {{ number_format($tradePrice, 2) }}</div>
                        </div>
                        <div class="text-end">
                            <div style="font-size:.68rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase; margin-bottom:2px;">Stock</div>
                            <span class="stock-badge {{ $stockClass }}">
                                <i class="fas fa-cubes"></i> {{ $stockDisplay }} <span class="stock-unit">{{ $stockUnit }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="prod-mcard-actions">
                        <div class="d-flex flex-column align-items-center w-100">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <input type="radio" class="form-check-input row-matrix-radio" name="row_matrix_radio" id="mRowMatrixRadio_{{ $product->id }}" data-id="{{ $product->id }}" style="cursor: pointer; width: 13px; height: 13px; accent-color: #2563eb; margin: 0;">
                                <label for="mRowMatrixRadio_{{ $product->id }}" class="form-check-label m-0 fw-bold" style="font-size: 9px; color: #2563eb; cursor: pointer;">Matrix View</label>
                            </div>
                            <button type="button" class="btn-act btn-act-view viewProductBtn w-100" data-id="{{ $product->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-act btn-act-edit">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                        @endif
                        <button type="button" class="btn-act btn-act-barcode openLabelPrintModalBtn" data-id="{{ $product->id }}" title="Print 38x26mm Labels">
                            <i class="fas fa-barcode"></i> Label
                        </button>
                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                            <button type="button"
                                class="btn-act {{ $product->is_active ? 'btn-act-deact' : 'btn-act-act' }} toggle-active-btn"
                                data-id="{{ $product->id }}"
                                data-active="{{ $product->is_active ? '1' : '0' }}"
                                data-name="{{ $product->item_name }}">
                                <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                        @endif
                        @if (auth()->user()->can('products.delete') || auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                            <button type="button"
                                class="btn-act btn-act-delete delete-product-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->item_name }}"
                                title="Delete Product">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>{{-- /mobile-product-cards --}}


        {{-- ── Pagination ── --}}
        <div class="erp-pagination">
            <span class="showing">
                Showing <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong>
                of <strong>{{ $products->total() }}</strong> products
            </span>
            {{ $products->appends(request()->query())->links() }}
        </div>

    </div>{{-- /erp-card --}}

    {{-- ── Fixed Bottom Status Bar (QuickBooks POS Desktop Style) ── --}}
    <div class="qb-fixed-bottom-bar" id="qbFixedBottomBar">
        <div class="d-flex align-items-center flex-nowrap qb-status-items-wrap">
            {{-- Total Products --}}
            <div class="qb-status-item">
                <span class="qb-status-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fas fa-box-open"></i></span>
                <span class="qb-status-title">Total Products:</span>
                <strong class="qb-status-num text-dark">{{ $products->total() }}</strong>
                <span class="qb-status-sub">(in catalog)</span>
            </div>

            <div class="qb-status-divider"></div>

            {{-- Active --}}
            <div class="qb-status-item">
                <span class="qb-status-icon" style="background:#ecfdf5; color:#059669;"><i class="fas fa-check-circle"></i></span>
                <span class="qb-status-title">Active:</span>
                <strong class="qb-status-num text-success">{{ $products->getCollection()->where('is_active',1)->count() }}</strong>
                <span class="qb-status-sub">(on this page)</span>
            </div>

            <div class="qb-status-divider"></div>

            {{-- Inactive --}}
            <div class="qb-status-item">
                <span class="qb-status-icon" style="background:#fef2f2; color:#dc2626;"><i class="fas fa-times-circle"></i></span>
                <span class="qb-status-title">Inactive:</span>
                <strong class="qb-status-num text-danger">{{ $products->getCollection()->where('is_active',0)->count() }}</strong>
                <span class="qb-status-sub">(on this page)</span>
            </div>

            <div class="qb-status-divider"></div>

            {{-- Filtered Results --}}
            <div class="qb-status-item">
                <span class="qb-status-icon" style="background:#fffbeb; color:#d97706;"><i class="fas fa-filter"></i></span>
                <span class="qb-status-title">Filtered Results:</span>
                <strong class="qb-status-num text-dark">{{ $products->total() }}</strong>
                <span class="qb-status-sub">
                    @if(request()->hasAny(['search','category_id','brand_id','status']))
                        <span class="badge" style="background:#fef3c7; color:#b45309; font-weight:700; font-size:10px; border:1px solid #fde68a;">Filters Active</span>
                    @else
                        (all products)
                    @endif
                </span>
            </div>
        </div>

        {{-- Right-hand quick info / shortcuts helper --}}
        <div class="d-none d-md-flex align-items-center gap-3" style="font-size: 11.5px; color: #64748b;">
            <span><i class="fas fa-columns text-primary me-1"></i> Press <kbd style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-weight:600; padding:2px 5px; border-radius:4px; font-size:10.5px;">F8</kbd> to toggle Item Details</span>
            <span class="text-muted">|</span>
            <span>Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong></span>
        </div>
    </div>
        </div>{{-- /container-fluid --}}
    </div>{{-- /main-content-inner --}}
</div>{{-- /main-content --}}


{{-- ══════════════════════════════════════════════════════════════
     IMPORT MODAL
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 820px; margin: 1.5rem auto;" role="document">
        <div class="modal-content qb-modal-content border-0 shadow-lg" style="max-height: calc(100vh - 40px); display: flex; flex-direction: column; overflow: hidden; border-radius: 10px;">
            <form action="{{ route('products.import.validate') }}" method="POST" enctype="multipart/form-data" id="qbImportForm" style="display: flex; flex-direction: column; flex-grow: 1; min-height: 0; margin: 0;">
                @csrf

                {{-- QuickBooks & Odoo Modal Header --}}
                <div class="qb-modal-header flex-shrink-0" style="padding: 12px 18px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="qb-modal-icon-badge">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="qb-modal-title mb-0" id="importModalLabel">Import Products (Excel / CSV)</h5>
                                <span class="badge" style="background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.45); color: #86efac; font-size: 9.5px; font-weight: 700; letter-spacing: 0.4px;">
                                    <i class="fas fa-check-circle me-1"></i> SMART PARSER
                                </span>
                            </div>
                            <span class="qb-modal-subtitle">QuickBooks Desktop POS &amp; Odoo ERP Data Migration Engine</span>
                        </div>
                    </div>
                    <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" title="Close dialog">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Modal Body (Scrollable if screen is compact) --}}
                <div class="modal-body flex-grow-1" style="overflow-y: auto; padding: 16px 20px; background: #f8fafc;">
                    @if(session('error'))
                        <div class="alert alert-danger py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 12px; border-radius: 6px;">
                            <i class="fas fa-exclamation-circle fs-5 flex-shrink-0"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger py-2.5 px-3 mb-3" style="font-size: 12px; border-radius: 6px;">
                            <div class="fw-bold mb-1 d-flex align-items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Please resolve the following before proceeding:</span>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ── STEP 1: Download Templates ── --}}
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark" style="font-size: 12px; letter-spacing: 0.3px;">
                                <i class="fas fa-cloud-download-alt text-primary me-1.5"></i> STEP 1: Download Official Template
                            </span>
                            <span class="text-muted" style="font-size: 11px;">Recommended for seamless mapping</span>
                        </div>

                        <div class="row g-2.5">
                            <!-- QuickBooks POS Template -->
                            <div class="col-md-6">
                                <div class="qb-template-card-box qb-card-excel">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="qb-template-icon-square qb-template-icon-excel">
                                                    <i class="fas fa-file-excel"></i>
                                                </div>
                                                <div>
                                                    <h6 class="qb-template-card-title">QuickBooks POS Template</h6>
                                                    <span class="text-muted" style="font-size: 10.5px;">Best for POS Item # inventory</span>
                                                </div>
                                            </div>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5" style="font-size: 9.5px; font-weight: 700;">.XLSX</span>
                                        </div>
                                        <span class="qb-template-card-desc">
                                            Includes: <strong>Item #, Style, Attribute (Size), Cost, Price, Location &amp; Barcode</strong>.
                                        </span>
                                    </div>
                                    <a href="{{ route('products.template.quickbooks') }}" class="btn btn-sm btn-outline-success fw-bold d-flex align-items-center justify-content-center gap-1.5 w-100" style="height: 30px; font-size: 11.5px; border-radius: 5px; text-decoration: none;">
                                        <i class="fas fa-download"></i> Download Excel (.xlsx)
                                    </a>
                                </div>
                            </div>

                            <!-- Standard Full ERP Template -->
                            <div class="col-md-6">
                                <div class="qb-template-card-box qb-card-csv">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="qb-template-icon-square qb-template-icon-csv">
                                                    <i class="fas fa-file-csv"></i>
                                                </div>
                                                <div>
                                                    <h6 class="qb-template-card-title">Standard Full ERP Template</h6>
                                                    <span class="text-muted" style="font-size: 10.5px;">All columns &amp; multi-warehouses</span>
                                                </div>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1.5 py-0.5" style="font-size: 9.5px; font-weight: 700;">.CSV</span>
                                        </div>
                                        <span class="qb-template-card-desc">
                                            Includes: <strong>Categories, Brands, Suppliers, Multi-Units, Tax Rates &amp; Variations</strong>.
                                        </span>
                                    </div>
                                    <a href="{{ route('products.template') }}" class="btn btn-sm btn-outline-primary fw-bold d-flex align-items-center justify-content-center gap-1.5 w-100" style="height: 30px; font-size: 11.5px; border-radius: 5px; text-decoration: none;">
                                        <i class="fas fa-download"></i> Download CSV (.csv)
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- QuickBooks & Odoo Smart Engine Safeguards --}}
                        <div class="p-2.5 mt-2.5" style="background: #edf3fa; border: 1px solid #b2c5dc; border-radius: 6px;">
                            <div class="d-flex align-items-center gap-1.5 mb-2" style="font-size: 11px; font-weight: 700; color: #1e3a5f;">
                                <i class="fas fa-wand-magic-sparkles text-primary"></i>
                                <span>QuickBooks &amp; Odoo Engine Safeguards (Active):</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="qb-feat-pill">
                                        <i class="fas fa-object-group text-primary"></i>
                                        <div>
                                            <strong>Auto-Group Styles</strong>
                                            <span>Variants grouped by Item Name</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="qb-feat-pill">
                                        <i class="fas fa-wrench text-warning"></i>
                                        <div>
                                            <strong>Auto-Fix Dates</strong>
                                            <span>Restores <code>04-Mar</code> &rarr; <code>3/4"</code></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="qb-feat-pill">
                                        <i class="fas fa-barcode text-success"></i>
                                        <div>
                                            <strong>Preserve Item #</strong>
                                            <span>Saves QB POS 1-4 digit codes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── STEP 2: Drag & Drop File Upload ── --}}
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label fw-bold mb-0 text-dark" style="font-size: 12px; letter-spacing: 0.3px;">
                                <i class="fas fa-cloud-arrow-up text-primary me-1.5"></i> STEP 2: Upload Excel or CSV File <span class="text-danger">*</span>
                            </label>
                            <span class="text-muted" style="font-size: 11px;">Supported: .xlsx, .xls, .csv (Max 20 MB)</span>
                        </div>

                        <div class="qb-dropzone position-relative" id="qbImportDropzone" style="min-height: 96px;">
                            {{-- Transparent Full-Cover File Input (Native clicks & drag-and-drop directly work) --}}
                            <input type="file" name="csv_file" id="qbImportFileInput" accept=".xlsx,.xls,.csv,.txt" required
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 5;">

                            {{-- Empty State --}}
                            <div id="qbDropzonePrompt" class="qb-dropzone-prompt text-center py-2 px-2" style="pointer-events: none;">
                                <div class="qb-dropzone-icon">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                </div>
                                <div class="qb-dropzone-title">
                                    <span class="text-primary fw-bold text-decoration-underline">Click to browse</span> or drag and drop spreadsheet here
                                </div>
                                <div class="text-muted" style="font-size: 11px;">
                                    Supports: <strong>Excel (.xlsx, .xls)</strong> or <strong>CSV (.csv)</strong> &bull; Max size: <strong>20 MB</strong>
                                </div>
                            </div>

                            {{-- File Selected State (Hidden initially) --}}
                            <div id="qbDropzoneFilePreview" class="qb-dropzone-preview d-none align-items-center justify-content-between position-relative" style="z-index: 6;">
                                <div class="d-flex align-items-center gap-2.5 min-w-0">
                                    <div class="qb-file-badge-icon" id="qbFileIcon">
                                        <i class="fas fa-file-excel text-success"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark text-truncate" id="qbFileName" style="font-size: 12.5px; font-family: monospace;">filename.xlsx</div>
                                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 11px;">
                                            <span id="qbFileSize">0 KB</span>
                                            &bull;
                                            <span class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i> Ready for Validation</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 position-relative" id="qbFileRemoveBtn" style="font-size: 11px; z-index: 10;" title="Remove & select another file">
                                    <i class="fas fa-times me-1"></i> Change File
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ── STEP 3: Configuration & Mode ── --}}
                    <div class="mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <span class="fw-bold text-dark" style="font-size: 12px; letter-spacing: 0.3px;">
                                <i class="fas fa-sliders-h text-primary me-1.5"></i> STEP 3: Import Settings
                            </span>
                        </div>

                        <div class="row g-2">
                            <!-- Import Mode Select -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size: 11.5px; color: #1e293b;">
                                    Import Action Mode
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-primary border-end-0" style="border-color: #cbd5e1; height: 34px;"><i class="fas fa-layer-group"></i></span>
                                    <select name="import_mode" class="form-select form-select-sm" style="height: 34px; font-size: 11.5px; border-color: #cbd5e1; font-weight: 600;" required>
                                        <option value="create" selected>Create &amp; Update (Add new + update existing)</option>
                                        <option value="update_only">Update Only (Existing variants only)</option>
                                    </select>
                                </div>
                                <span class="text-muted d-block mt-1" style="font-size: 10px;">
                                    Auto-matches existing products by Barcode or Item Code.
                                </span>
                            </div>

                            <!-- Auto-Create Missing Category & Brand -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size: 11.5px; color: #1e293b;">
                                    Master Records
                                </label>
                                <div class="p-2 d-flex align-items-center justify-content-between" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; height: 34px;">
                                    <div class="d-flex align-items-center min-w-0">
                                        <label for="autoCreate" class="form-check-label fw-semibold text-dark mb-0 text-truncate" style="font-size: 11.5px; cursor: pointer;">
                                            Auto-create Categories &amp; Brands
                                        </label>
                                    </div>
                                    <div class="form-check form-switch m-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" id="autoCreate" name="auto_create" value="1" checked style="cursor: pointer; width: 34px; height: 18px;">
                                    </div>
                                </div>
                                <span class="text-muted d-block mt-1" style="font-size: 10px;">
                                    Creates missing categories or brands found in the uploaded file.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Modal Footer (Pinned firmly at bottom, never cut off) ── --}}
                <div class="qb-modal-footer flex-shrink-0">
                    <div class="d-flex align-items-center gap-1.5 text-secondary d-none d-sm-flex" style="font-size: 11px;">
                        <span class="badge rounded-pill bg-success p-1" style="width: 8px; height: 8px; display: inline-block;"></span>
                        <span class="fw-semibold text-dark">QuickBooks &amp; Odoo Engine Active</span>
                        <span class="text-muted">&bull; Pre-Import Preview &amp; Verification Step next</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="qb-modal-btn-save" id="qbImportSubmitBtn">
                            <i class="fas fa-arrow-right me-1"></i> Next: Validate &amp; Preview
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- QuickBooks POS Style: Customize Item List Columns Modal --}}
<div class="modal fade" id="customizeIndexColumnsModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-columns text-primary fs-6"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-dark" style="font-size: 14px;">Customize Item List Columns</h6>
                        <span class="text-muted" style="font-size: 11px;">Show or hide columns in the Product Catalog table</span>
                    </div>
                </div>
                <button type="button" class="close text-secondary qb-idx-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size:24px;border:none;background:transparent;cursor:pointer;line-height:1;padding:0 4px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="fw-semibold text-secondary" style="font-size: 12px;">Available Columns:</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 11px;" id="idxColSelectAll">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;" id="idxColResetDefault">Reset Default</button>
                    </div>
                </div>
                <div class="row g-2" id="indexColumnsChecklist">
                    {{-- Injected dynamically by JavaScript --}}
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fst-italic" style="font-size: 11px;"><i class="fas fa-info-circle me-1 text-primary"></i> Right-click table header anytime to open</span>
                <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill qb-idx-modal-close" data-dismiss="modal" data-bs-dismiss="modal">Apply &amp; Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     QUICKBOOKS POS STYLE: EDIT STYLE MODAL (Interactive Matrix)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="qbEditStyleModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 840px;" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-th text-primary fs-6"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-dark" style="font-size: 14px;">Edit Style: <span id="qbEditStyleModalTitle">—</span></h6>
                        <span class="text-muted" style="font-size: 11px;">Update variant stock, on-hand counts and attributes</span>
                    </div>
                </div>
                <button type="button" class="close text-secondary" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size:24px;border:none;background:transparent;cursor:pointer;line-height:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="alert alert-info py-2 px-3 mb-2 d-flex align-items-center gap-2" style="font-size:12px; border-radius:6px;">
                    <i class="fas fa-info-circle"></i>
                    <span>Edit the on-hand stock quantity in each cell. Totals will update automatically.</span>
                </div>
                <div class="table-responsive" id="qbEditStyleMatrixContainer" style="max-height: 440px;">
                    <!-- Interactive Matrix Table injected here -->
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
                <div id="qbEditStyleStatus" style="font-size:12px;"></div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary px-4 fw-bold" id="qbBtnSaveStyleChanges">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     ACCOUNTING / ERP DOCUMENT STYLE: PHYSICAL INVENTORY AUDIT SHEET
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="qbPhysicalInventoryModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl" style="max-width: 1560px; width: 98%; margin: 15px auto;" role="document">
        <div class="modal-content doc-modal-content">
            <!-- Document Header -->
            <div class="doc-modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="doc-hdr-icon-box">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="doc-title-main">PHYSICAL INVENTORY AUDIT &amp; CYCLE COUNT RECONCILIATION</h6>
                            <span class="badge bg-primary text-uppercase" style="font-size:10px; font-weight:700; letter-spacing:0.5px; padding:3px 7px;">FORM PI-101</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="doc-header-pill"><i class="fas fa-barcode text-warning"></i> AUDIT REF: #PI-{{ date('Ymd') }}</span>
                            <span class="doc-header-pill"><i class="far fa-calendar-alt text-info"></i> DATE: {{ date('d-M-Y') }}</span>
                            <span class="doc-header-pill" id="qbPiHeaderLocationPill"><i class="fas fa-warehouse text-success"></i> LOCATION: <span id="qbPiActiveWarehouseName">BY SELECTED PRODUCTS</span></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('cycle_count.index') }}" class="btn btn-sm btn-outline-primary fw-semibold d-inline-flex align-items-center gap-1.5" style="height:32px; font-size:12px; border-radius:4px; text-decoration:none;">
                        <i class="fas fa-calendar-alt"></i> Schedule
                    </a>
                    <a href="{{ route('cycle_count.create') }}" class="btn btn-sm btn-primary fw-semibold d-inline-flex align-items-center gap-1.5" style="height:32px; font-size:12px; border-radius:4px; text-decoration:none;">
                        <i class="fas fa-plus"></i> Assign Count
                    </a>
                    <div class="doc-status-badge">
                        <span class="doc-status-dot"></span>
                        <span>IN RECONCILIATION</span>
                    </div>
                    <button type="button" class="doc-btn-close ms-2" id="qbPiTopCloseBtn" title="Close / Discard">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body p-3 bg-light">
                <!-- Product Selection & Reconciliation Dock (Software ERP Ribbon Style) -->
                <!-- Enterprise Dual Dock: Left = Product & Item Scan, Right = Location & Audit Controls -->
                <div class="doc-ribbon-card mb-2.5 p-2.5">
                    <div class="row g-2.5 align-items-stretch">
                        <!-- ══════════════════════════════════════════════════════════
                             LEFT SIDE: PRODUCT SELECTION & BARCODE SCANNER
                        ══════════════════════════════════════════════════════════ -->
                        <div class="col-xl-6 col-lg-6 col-12 d-flex flex-column">
                            <div class="pi-dock-card">
                                <!-- Dock Header -->
                                <div class="pi-dock-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="pi-dock-icon pi-dock-icon-blue">
                                            <i class="fas fa-boxes-stacked"></i>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <span class="pi-dock-title">Product Scope</span>
                                            <span class="pi-dock-subtitle d-none d-sm-inline">&bull; Search &amp; Scan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <button type="button" class="doc-btn-chip doc-btn-chip-primary" id="qbPiSelectAllProductsBtn" title="Select all catalog products">
                                            <i class="fas fa-check-double"></i> All
                                        </button>
                                        <button type="button" class="doc-btn-chip" id="qbPiClearAllProductsBtn" title="Clear selection">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                        <span class="doc-badge-count ms-1" id="qbPiSelectedCountBadge">
                                            <strong>0</strong> Selected
                                        </span>
                                    </div>
                                </div>

                                <!-- Dock Body -->
                                <div class="pi-dock-body">
                                    <!-- Product Search Box -->
                                    <div class="doc-select2-box-wrap w-100">
                                        <select id="qbPiProductSelect" class="doc-ribbon-select2" multiple="multiple" data-placeholder="Search product name or code..." style="width: 100%;">
                                            @if(isset($allProductsList))
                                                @foreach($allProductsList as $pItem)
                                                    <option value="{{ $pItem->id }}">{{ $pItem->item_name }} &bull; [{{ $pItem->item_code }}]</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Barcode Scanner & Step Bar -->
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="pi-control-item flex-grow-1">
                                            <span class="pi-control-label"><i class="fas fa-barcode text-muted"></i></span>
                                            <input type="text" id="qbPiScanItemInput" class="pi-control-input" placeholder="Scan Barcode / SKU to count...">
                                        </div>
                                        <div class="pi-control-item" style="width: 105px;">
                                            <span class="pi-control-label">Step</span>
                                            <input type="number" id="qbPiScanQtyInput" class="pi-control-input text-center fw-bold px-1" value="1" min="1" step="1" style="font-family: 'JetBrains Mono', monospace;">
                                        </div>
                                        <button type="button" class="pi-btn-primary-action" id="qbPiAddCountBtn">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                        <!-- Hidden legacy aliases for event compatibility -->
                                        <input type="hidden" id="qbPiScanItemInputLoc">
                                        <input type="hidden" id="qbPiScanQtyInputLoc" value="1">
                                        <span id="qbPiAddCountBtnLoc" style="display:none;"></span>
                                    </div>

                                    <!-- Feedback status banner (shown when products are active/loaded) -->
                                    <div id="qbPiCurrentProductBadge" class="pi-status-banner" style="display:none;">
                                        <i class="fas fa-check-circle text-success me-1.5 flex-shrink-0"></i>
                                        <span id="qbPiCurrentProductName" class="text-truncate">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════════════
                             RIGHT SIDE: LOCATION & AUDIT CONTROLS
                        ══════════════════════════════════════════════════════════ -->
                        <div class="col-xl-6 col-lg-6 col-12 d-flex flex-column">
                            <div class="pi-dock-card">
                                <!-- Dock Header -->
                                <div class="pi-dock-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="pi-dock-icon pi-dock-icon-emerald">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <span class="pi-dock-title">Location &amp; Controls</span>
                                            <span class="pi-dock-subtitle d-none d-sm-inline">&bull; Audit Setup</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <button type="button" class="pi-btn-due-pill" id="qbPiLoadDueBtn" title="Load products due for cycle count today">
                                            <i class="fas fa-bell text-warning"></i> Due Today (<span id="qbPiModalDueCount">{{ $dueCycleCount ?? 0 }}</span>)
                                        </button>
                                        <span class="doc-badge-count" id="qbPiLocationLoadedBadge" style="display: none;">
                                            <strong>0</strong> Loaded
                                        </span>
                                        <span id="qbPiModalDueCountLoc" style="display:none;">{{ $dueCycleCount ?? 0 }}</span>
                                        <span id="qbPiLoadDueBtnLoc" style="display:none;"></span>
                                    </div>
                                </div>

                                <!-- Dock Body -->
                                <div class="pi-dock-body">
                                    <!-- Location & Rack Selectors Row -->
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Location Select -->
                                        <div class="pi-control-item flex-grow-1" style="min-width: 170px;">
                                            <span class="pi-control-label"><i class="fas fa-store text-muted"></i> Loc</span>
                                            <select id="qbPiWarehouseSelect" class="pi-control-select">
                                                <option value="all">🌐 All Locations</option>
                                                @if(isset($shopsList) && $shopsList->count() > 0)
                                                    <optgroup label="🏪 Shops / Stores">
                                                        @foreach($shopsList as $shop)
                                                            <option value="branch_{{ $shop->id }}">🏪 {{ $shop->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                                @if(isset($warehousesList) && $warehousesList->count() > 0)
                                                    <optgroup label="🏢 Warehouses">
                                                        @foreach($warehousesList as $wh)
                                                            <option value="wh_{{ $wh->id }}">🏢 {{ $wh->warehouse_name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Rack Select with reset button -->
                                        <div class="pi-control-item" id="qbPiRackShelfRow" style="min-width: 145px; flex: 1;">
                                            <span class="pi-control-label" id="qbPiRackShelfLabel"><i class="fas fa-boxes-stacked text-muted"></i> W. Rack</span>
                                            <select id="qbPiRackShelfSelect" class="pi-control-select">
                                                <option value="all">📦 All Racks</option>
                                            </select>
                                            <button type="button" class="pi-control-btn" id="qbPiResetRackShelfBtn" title="Reset rack filter">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </div>

                                        <!-- Load Location Button (Compact Width) -->
                                        <button type="button" class="pi-btn-primary-action px-2.5" id="qbPiLoadWarehouseItemsBtn" title="Load products for selected location">
                                            <i class="fas fa-sync-alt"></i> Load
                                        </button>
                                    </div>

                                    <!-- Frequency Schedule & Action Buttons Toolbar -->
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                        <div class="pi-control-item" style="width: 220px;">
                                            <span class="pi-control-label"><i class="far fa-clock text-muted"></i> Frequency</span>
                                            <select id="qbPiFrequencySelect" class="pi-control-select">
                                                <option value="auto">⚡ Auto (Velocity)</option>
                                                <option value="1">📅 Daily (1d)</option>
                                                <option value="7">📅 Weekly (7d)</option>
                                                <option value="30">📅 Monthly (30d)</option>
                                                <option value="90">📅 Quarterly (90d)</option>
                                            </select>
                                            <button type="button" class="pi-control-btn text-primary fw-bold" id="qbPiApplyScheduleBtn" title="Apply frequency to worksheet items">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>

                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary fw-semibold d-inline-flex align-items-center gap-1.5" id="customizePiColumnsBtn" style="height: 34px; font-size: 11.5px; border-color: #cbd5e1; color: #334155;" title="Customize Columns (or right-click table header)">
                                                <i class="fas fa-columns text-primary"></i> Columns
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary fw-semibold d-inline-flex align-items-center gap-1.5" id="qbPiSetUncountedZeroBtn" style="height: 34px; font-size: 11.5px; border-color: #cbd5e1; color: #334155;" title="Set counted quantity to 0 for all items">
                                                <i class="fas fa-eraser text-danger"></i> Set 0
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary fw-semibold d-inline-flex align-items-center gap-1.5" id="qbPiResetExpectedBtn" style="height: 34px; font-size: 11.5px; border-color: #cbd5e1; color: #334155;" title="Reset all counted quantities back to book stock">
                                                <i class="fas fa-undo text-secondary"></i> Reset Book
                                            </button>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-primary fw-semibold d-inline-flex align-items-center gap-1.5 ms-1" id="qbPiAssignStaffBtn" style="height: 34px; font-size: 11.5px;" title="Assign this location to staff as a formal Cycle Count">
                                            <i class="fas fa-user-check"></i> Assign to Staff
                                        </button>

                                        <!-- Hidden legacy aliases -->
                                        <span id="qbPiSetUncountedZeroBtnLoc" style="display:none;"></span>
                                        <span id="qbPiResetExpectedBtnLoc" style="display:none;"></span>
                                        <select id="qbPiFrequencySelectLoc" style="display:none;"><option value="auto">Auto</option></select>
                                        <span id="qbPiApplyScheduleBtnLoc" style="display:none;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger Worksheet Table (Styled like Product Table in Image 2) -->
                <div class="doc-table-sheet-wrap">
                    <table class="table table-hover align-middle nowrap" id="qbPiTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th data-col-key="sr_no" style="width: 38px;" class="text-center">Sr #</th>
                                <th data-col-key="item_unique" style="width: 110px;" class="text-center">Item Unique</th>
                                <th data-col-key="image" style="width: 40px;" class="text-center">Image</th>
                                <th data-col-key="product_name" style="min-width: 170px;">Product Name</th>
                                <th data-col-key="category" style="width: 100px;" class="text-center">Category</th>
                                <th data-col-key="barcode" style="width: 120px;" class="text-center">Barcode</th>
                                <th data-col-key="attribute" style="width: 90px;" class="text-center">Attribute</th>
                                <th data-col-key="size" style="width: 80px;" class="text-center">Size</th>
                                <th data-col-key="location" style="width: 150px;">Location</th>
                                <th data-col-key="velocity" style="width: 110px;" class="text-center">Velocity</th>
                                <th data-col-key="stock" style="width: 85px;" class="text-center">Stock</th>
                                <th data-col-key="physical_count" style="width: 120px;" class="text-center">Physical Count</th>
                                <th data-col-key="variance" style="width: 85px;" class="text-center">Variance</th>
                                <th data-col-key="actions" class="text-center" style="width: 50px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="qbPiTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Audit KPI Summary Cards Strip -->
                <div class="doc-summary-strip">
                    <div class="doc-summary-kpi-card">
                        <div class="doc-kpi-icon-wrap" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="doc-kpi-body">
                            <span class="doc-summary-kpi-label">Total SKUs in Count</span>
                            <span class="doc-summary-kpi-val" id="qbPiTotalCountItems">0</span>
                        </div>
                    </div>
                    <div class="doc-summary-kpi-card">
                        <div class="doc-kpi-icon-wrap" style="background: rgba(16,185,129,0.1); color: #059669;">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="doc-kpi-body">
                            <span class="doc-summary-kpi-label">Stock Matched (0 Diff)</span>
                            <span class="doc-summary-kpi-val text-success" id="qbPiMatchedItemsCount">0</span>
                        </div>
                    </div>
                    <div class="doc-summary-kpi-card">
                        <div class="doc-kpi-icon-wrap" style="background: rgba(239,68,68,0.1); color: #dc2626;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="doc-kpi-body">
                            <span class="doc-summary-kpi-label">Discrepancies</span>
                            <span class="doc-summary-kpi-val text-danger" id="qbPiDiffItemsCount">0</span>
                        </div>
                    </div>
                    <div class="doc-summary-kpi-card">
                        <div class="doc-kpi-icon-wrap" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="doc-kpi-body">
                            <span class="doc-summary-kpi-label">Net Variance Units</span>
                            <span class="doc-summary-kpi-val" id="qbPiNetVarianceCount">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Footer Actions -->
            <div class="modal-footer py-2.5 px-3 bg-white border-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2 text-muted" style="font-size:12px;">
                    <i class="fas fa-shield-alt text-primary"></i>
                    <span>Reconciliation writes directly to warehouse inventory ledger with audit timestamp.</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="doc-btn-discard" id="qbPiCancelBtn">
                        <i class="fas fa-times"></i> Discard / Close
                    </button>
                    <button type="button" class="doc-btn-apply" id="qbPiApplyChangesBtn">
                        <i class="fas fa-check-double"></i> Post &amp; Apply Stock Adjustments
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- QuickBooks POS Style: Right-click Header Context Menu for Product Table --}}
<div id="qbIndexContextMenu" class="qb-custom-context-menu">
    <div class="qb-ctx-header">
        <i class="fas fa-sliders-h text-primary"></i>
        <span>Catalog Columns</span>
    </div>
    <button type="button" class="qb-ctx-item text-primary" id="qbCtxCustomizeIndexBtn">
        <i class="fas fa-columns"></i>
        <span><strong>Customize Columns...</strong></span>
    </button>
    <div class="qb-ctx-divider"></div>
    <button type="button" class="qb-ctx-item text-muted" id="qbCtxResetWidthsBtn">
        <i class="fas fa-arrows-alt-h"></i>
        <span>Reset Column Widths</span>
    </button>
    <button type="button" class="qb-ctx-item text-muted" id="qbCtxResetIndexBtn">
        <i class="fas fa-undo"></i>
        <span>Reset Visibility</span>
    </button>
</div>

{{-- QuickBooks POS Style: Right-click Header Context Menu for Physical Inventory Table --}}
<div id="qbPiContextMenu" class="qb-custom-context-menu">
    <div class="qb-ctx-header">
        <i class="fas fa-sliders-h text-primary"></i>
        <span>Audit Worksheet</span>
    </div>
    <button type="button" class="qb-ctx-item text-primary" id="qbPiCtxCustomizeBtn">
        <i class="fas fa-columns"></i>
        <span><strong>Customize Columns...</strong></span>
    </button>
    <div class="qb-ctx-divider"></div>
    <button type="button" class="qb-ctx-item text-muted" id="qbPiCtxResetWidthsBtn">
        <i class="fas fa-arrows-alt-h"></i>
        <span>Reset Column Widths</span>
    </button>
    <button type="button" class="qb-ctx-item text-muted" id="qbPiCtxResetBtn">
        <i class="fas fa-undo"></i>
        <span>Reset Visibility</span>
    </button>
</div>

{{-- QuickBooks POS Style: Customize Physical Inventory Columns Modal --}}
<div class="modal fade" id="customizePiColumnsModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-columns text-primary fs-6"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-dark" style="font-size: 14px;">Customize Inventory Columns</h6>
                        <span class="text-muted" style="font-size: 11px;">Show or hide columns in the Physical Inventory table</span>
                    </div>
                </div>
                <button type="button" class="close text-secondary qb-pi-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size:24px;border:none;background:transparent;cursor:pointer;line-height:1;padding:0 4px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="fw-semibold text-secondary" style="font-size: 12px;">Available Columns:</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 11px;" id="piColSelectAll">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;" id="piColResetDefault">Reset Default</button>
                    </div>
                </div>
                <div class="row g-2" id="piColumnsChecklist">
                    {{-- Injected dynamically by JavaScript --}}
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fst-italic" style="font-size: 11px;"><i class="fas fa-info-circle me-1 text-primary"></i> Right-click table header anytime to open</span>
                <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill qb-pi-modal-close" data-dismiss="modal" data-bs-dismiss="modal">Apply &amp; Close</button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     PRODUCT VIEW MODAL (Compact Kent ERP Theme)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="productViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 740px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            {{-- Header --}}
            <div class="modal-header border-bottom bg-white px-4 py-2.5 d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; border: 1px solid #bfdbfe;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark text-capitalize mb-0" id="productViewModalLabel" style="font-size: 1.15rem; font-weight: 800; color: #0f172a; letter-spacing: -0.2px;">
                            <span id="view_item_name">Product</span>
                        </h5>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 6px; margin-top: 3px;" id="view_item_subtext">
                            <span class="badge" style="background: #0f172a; color: #ffffff; font-size: 10.5px;">CODE</span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <!-- View Mode Switcher: Table List vs Video Matrix -->
                    <div class="btn-group btn-group-sm p-0.5 bg-light rounded-pill border" role="group" aria-label="View Mode" style="border-color: #cbd5e1 !important;">
                        <button type="button" class="btn btn-sm px-3 fw-bold active rounded-pill" id="btnModeMatrix" style="font-size: 11px; transition: all 0.2s; background: #2563eb; color: #ffffff; padding: 4px 14px; border: none;">
                            <i class="fas fa-th-large me-1"></i> Matrix
                        </button>
                        <button type="button" class="btn btn-sm px-3 fw-bold rounded-pill btn-light text-muted" id="btnModeTable" style="font-size: 11px; transition: all 0.2s; padding: 4px 14px; border: none;">
                            <i class="fas fa-table me-1"></i> Table
                        </button>
                    </div>

                    <button type="button" class="close text-secondary ms-1" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 20px; border: none; background: transparent; line-height: 1; cursor: pointer; padding: 0 4px; outline: none; opacity: 0.6;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body p-0">
                <div id="modalLoadingSpinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Fetching product details…</p>
                </div>

                <!-- Video / Odoo POS Style Matrix View -->
                <div id="modalMatrixContainer" class="p-3 px-4" style="background: #ffffff; min-height: 260px;">
                    <!-- Top Product Quick Info Bar (Odoo Ribbon Style) -->
                    <div class="matrix-kpi-bar" id="matrixKpiBar">
                        <div class="matrix-kpi-group">
                            <!-- Numeric Sky Price (Hidden by default, toggleable) -->
                            <div class="matrix-kpi-item matrix-numeric-price d-none">
                                <span class="matrix-kpi-label">Sky Price</span>
                                <div class="matrix-kpi-value text-success" id="matrix_item_price" style="color: #059669 !important;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-kpi-divider matrix-numeric-divider d-none"></div>

                            <!-- Secret Sky P-Code -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Sky P-Code</span>
                                <div class="matrix-kpi-value text-primary" id="matrix_item_pcode" style="color: #2563eb !important;">---</div>
                            </div>
                            <div class="matrix-kpi-divider"></div>

                            <!-- Numeric Rot Price (Hidden by default, toggleable) -->
                            <div class="matrix-kpi-item matrix-numeric-price d-none">
                                <span class="matrix-kpi-label">Rot Price</span>
                                <div class="matrix-kpi-value text-info" id="matrix_item_rot_price" style="color: #0284c7 !important;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-kpi-divider matrix-numeric-divider d-none"></div>

                            <!-- Secret Rot P-Code -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Rot P-Code</span>
                                <div class="matrix-kpi-value text-primary" id="matrix_item_rot_pcode" style="color: #2563eb !important;">---</div>
                            </div>
                            <div class="matrix-kpi-divider"></div>

                            <!-- Free To Use -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Free To Use</span>
                                <div class="matrix-kpi-value text-dark" id="matrix_stock_display">0 Units</div>
                            </div>

                            <!-- Toggle Eye Button to reveal/hide numeric prices -->
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3 border rounded-pill ms-2 d-inline-flex align-items-center shadow-sm" id="btnToggleMatrixPriceDigits" title="Show/Hide Numeric Prices (Sky Price & Rot Price)" style="background: #ffffff; color: #475569; font-size: 11.5px; font-weight: 600; cursor: pointer; height: 32px; border-color: #cbd5e1;">
                                <i class="fas fa-eye-slash" id="iconMatrixPriceEye" style="margin-right: 6px;"></i>
                                <span id="textMatrixPriceToggle">Show Prices</span>
                            </button>
                        </div>
                        <div id="matrix_serial_badge_container" class="d-none text-end d-flex flex-column align-items-end justify-content-center">
                            <span class="badge font-monospace px-2.5 py-1" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 12px; font-weight: 700; border-radius: 6px;" id="matrix_serial_badge"></span>
                            <div id="matrix_location_badge_wrap" class="mt-1 d-none">
                                <span class="badge font-monospace px-2 py-0.5 shadow-sm d-inline-flex align-items-center" id="matrix_location_badge" style="background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; font-size: 11px; border-radius: 4px;" title="Variant Location">
                                    <i class="fas fa-map-marker-alt text-danger" style="font-size: 10px; margin-right: 4px;"></i>
                                    <span id="matrix_location_text">---</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Attribute Groups with Radio Options (e.g. Gauge, Length) -->
                    <div id="matrixAttributeGroups" class="d-flex flex-column gap-2.5 mb-1">
                        <!-- Dynamic attribute rows injected via JS -->
                    </div>
                </div>

                <!-- Standard Table View -->
                <div id="modalContentRow" class="table-responsive d-none p-0" style="max-height: 480px; overflow-y: auto;">
                    <table id="modalVariantTable" class="table align-middle mb-0" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-start ps-3" style="min-width: 160px;">Variant Name</th>
                                <th class="text-center" style="width: 65px;">Size</th>
                                <th class="text-center" style="width: 65px;">Color</th>
                                <th class="text-center" style="width: 75px;">Stock</th>
                                <th class="text-end" style="width: 85px;">Price</th>
                                <th class="text-center" style="width: 65px;">Sky Code</th>
                                <th class="text-end" style="width: 85px;">Cost</th>
                                <th class="text-end" style="width: 85px;">Rot Price</th>
                                <th class="text-center" style="width: 65px;">Rot Code</th>
                                <th class="text-end pe-3" style="width: 110px;">Barcode</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Single Unified Footer --}}
            <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                <div id="matrixSelectedVariantInfo" class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center" id="btnMatrixPrintLabel" title="Print 38x26mm Label for Selected Variant" style="font-size: 12px; height: 34px;">
                        <i class="fas fa-barcode" style="margin-right: 6px;"></i> Print Label
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-semibold" data-dismiss="modal" data-bs-dismiss="modal" id="btnMatrixDiscard" style="font-size: 12px; height: 34px; background: #fff; border-color: #cbd5e1;">Close</button>
                    <button type="button" class="btn btn-sm px-4 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center" id="btnMatrixAdd" style="background: #2563eb; color: #ffffff; border: none; font-size: 12.5px; min-width: 120px; height: 34px;" disabled>
                        <i class="fas fa-check" style="margin-right: 6px;"></i> Add Variant
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     PRODUCT & VARIANT LABEL PRINT MODAL (38mm x 26mm)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="productLabelModal" tabindex="-1" aria-labelledby="productLabelModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 840px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1 !important;">
            {{-- Header --}}
            <div class="modal-header border-bottom bg-white px-4 py-2.5 d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; border: 1px solid #bfdbfe; margin-right: 12px;">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="productLabelModalLabel" style="font-size: 1.15rem; font-weight: 800; color: #0f172a; letter-spacing: -0.2px;">
                            Print Labels (38mm &times; 26mm)
                        </h5>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 6px; margin-top: 3px;" id="lbl_modal_product_subtitle">
                            <span class="badge" style="background: #0f172a; color: #ffffff; font-size: 10.5px;">CODE</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="close text-secondary" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 20px; border: none; background: transparent; line-height: 1; cursor: pointer; padding: 0 4px; outline: none; opacity: 0.6;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3.5" style="background: #f8fafc;">
                <div id="lblModalLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Loading variants &amp; codes...</p>
                </div>

                <div id="lblModalContent" class="row g-3">
                    {{-- Left column: Variant selection list & copies --}}
                    <div class="col-md-7">
                        <div class="card shadow-none h-100" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; overflow: hidden;">
                            <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                                <span class="fw-bold text-dark small d-inline-flex align-items-center" style="font-size: 12px; font-weight: 700;">
                                    <i class="fas fa-list text-primary" style="margin-right: 6px;"></i> Select Items to Print
                                </span>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-semibold" id="lblSelectAllBtn" style="font-size: 11px; height: 24px; border-radius: 4px;">Select All</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 fw-semibold" id="lblDeselectAllBtn" style="font-size: 11px; height: 24px; border-radius: 4px;">Clear</button>
                                </div>
                            </div>
                            <div class="card-body p-0" style="max-height: 310px; overflow-y: auto;">
                                <table class="table table-hover table-sm align-middle mb-0" style="font-size: 12px;">
                                    <thead class="sticky-top" style="background: #f8fafc; font-size: 10.5px; font-weight: 700; color: #475569; letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="width: 34px; padding: 7px;" class="text-center">
                                                <input type="checkbox" id="lblMasterCheck" checked style="accent-color: #2563eb; cursor: pointer;">
                                            </th>
                                            <th style="padding: 7px 8px;">Item / Variant</th>
                                            <th class="text-center" style="padding: 7px 8px;">P-Codes</th>
                                            <th class="text-center" style="padding: 7px 8px;">Loc</th>
                                            <th class="text-center" style="width: 75px; padding: 7px 8px;">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lblVariantsTableBody">
                                        <!-- Injected via JS -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light py-2 px-3 border-top d-flex justify-content-between align-items-center" style="font-size: 11.5px; border-color: #e2e8f0;">
                                <span class="text-muted">Selected: <strong id="lblSelectedCountText" class="text-primary font-monospace" style="font-size: 12.5px;">0</strong> item(s)</span>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <span class="text-muted small">Default Qty:</span>
                                    <input type="number" id="lblDefaultCopiesInput" value="1" min="1" max="100" class="form-control form-control-sm text-center py-0 fw-bold" style="width: 48px; height: 26px; font-size: 12px; border-radius: 5px; border-color: #cbd5e1; color: #2563eb;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 fw-semibold" id="lblApplyAllCopiesBtn" title="Apply to all items" style="height: 26px; font-size: 11.5px; border-radius: 5px;">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right column: Live 38mm x 26mm Preview Card --}}
                    <div class="col-md-5">
                        <div class="card shadow-none h-100 d-flex flex-column justify-content-between" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; overflow: hidden;">
                            <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                                <span class="fw-bold text-dark small d-inline-flex align-items-center" style="font-size: 12px; font-weight: 700;">
                                    <i class="fas fa-eye text-primary" style="margin-right: 6px;"></i> 38mm &times; 26mm Live Preview
                                </span>
                                <span class="badge font-monospace" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 10px; font-weight: 700; padding: 2.5px 7px; border-radius: 4px;">1:1 Scale</span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center" style="min-height: 200px; background: #f8fafc;">
                                <!-- Realistic Sticker Card with subtle drop shadow -->
                                <div id="lblLivePreviewBox" class="label-sticker-preview shadow-sm">
                                    <!-- Line 1: Unique Item Number (#20226) -->
                                    <div class="sticker-line-serial" id="lblPrevSerial">
                                        #20226
                                    </div>

                                    <!-- Line 2: SKY-ROOT CODE -->
                                    <div class="sticker-line-2" id="lblPrevLine2">
                                        A.DB-A.DB
                                    </div>

                                    <!-- Line 3: LOCATION -->
                                    <div class="sticker-line-3" id="lblPrevLine3">
                                        LOCATION
                                    </div>
                                </div>
                                <small class="text-muted mt-2 text-center" style="font-size: 10.5px;">Click any row on the left to preview its sticker</small>
                            </div>
                            <div class="card-footer bg-white p-2.5 border-top text-center" style="border-color: #e2e8f0;">
                                <span class="badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px; padding: 4px 10px; border-radius: 5px;">
                                    <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Formatted specifically for standard 38mm &times; 26mm thermal label rolls.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between align-items-center flex-wrap" style="border-color: #e2e8f0; gap: 10px;">
                <!-- Controls: Print Mode & Grouping Style -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                    <!-- Print Mode -->
                    <div class="d-flex align-items-center" style="gap: 6px;">
                        <span class="small fw-bold text-muted text-uppercase d-inline-flex align-items-center" style="font-size: 10.5px; letter-spacing: 0.5px; margin-right: 2px;">
                            <i class="fas fa-sliders-h text-primary" style="margin-right: 4px;"></i>Mode:
                        </span>
                        <div class="loc-segment-switch" style="height: 30px; padding: 2px 3px;">
                            <input type="radio" name="lblPrintLayout" id="layoutThermal" value="thermal" checked autocomplete="off">
                            <label for="layoutThermal" title="Thermal Roll (38x26mm)" style="padding: 3px 9px !important; font-size: 11px !important;">
                                <i class="fas fa-receipt me-1"></i> Thermal (38&times;26)
                            </label>

                            <input type="radio" name="lblPrintLayout" id="layoutA4" value="a4" autocomplete="off">
                            <label for="layoutA4" title="A4 Sheet Grid" style="padding: 3px 9px !important; font-size: 11px !important;">
                                <i class="fas fa-file-alt me-1"></i> A4 Grid Sheet
                            </label>
                        </div>
                    </div>

                    <!-- Label Grouping Option -->
                    <div class="d-flex align-items-center" style="gap: 6px;">
                        <span class="small fw-bold text-muted text-uppercase d-inline-flex align-items-center" style="font-size: 10.5px; letter-spacing: 0.5px; margin-right: 2px;">
                            <i class="fas fa-boxes text-primary" style="margin-right: 4px;"></i>Style:
                        </span>
                        <div class="loc-segment-switch" style="height: 30px; padding: 2px 3px;">
                            <input type="radio" name="lblPrintGrouping" id="groupIndividual" value="individual" checked autocomplete="off">
                            <label for="groupIndividual" title="Each variant prints its own label" style="padding: 3px 9px !important; font-size: 11px !important;">
                                <i class="fas fa-tag me-1"></i> Individual
                            </label>

                            <input type="radio" name="lblPrintGrouping" id="groupLocation" value="location" autocomplete="off">
                            <label for="groupLocation" title="Combine variants on the same rack onto 1 label" style="padding: 3px 9px !important; font-size: 11px !important;">
                                <i class="fas fa-warehouse me-1"></i> By Rack/Shelf
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-semibold" data-dismiss="modal" data-bs-dismiss="modal" style="font-size: 12px; height: 34px; background: #fff; border-color: #cbd5e1;">Close</button>
                    <a href="#" target="_blank" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center" id="lblBtnOpenPrintPage" style="font-size: 12px; height: 34px;">
                        <i class="fas fa-external-link-alt" style="margin-right: 6px;"></i> Open Print Sheet
                    </a>
                    <button type="button" class="btn btn-sm px-4 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center" id="lblBtnPrintNow" style="background: #2563eb; color: #ffffff; border: none; font-size: 12.5px; height: 34px;">
                        <i class="fas fa-print" style="margin-right: 6px;"></i> Print Selected Labels
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>
<script>
if (typeof XLSX === 'undefined') {
    document.write('<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"><\/script>');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
window.escapeHtml = escapeHtml;

$(document).ready(function () {

    // ── Open Import Modal ──
    $('#openImportModalBtn').on('click', function () {
        $('#importModal').modal('show');
    });

    @if($errors->any() || session('error'))
        $('#importModal').modal('show');
    @endif

    // ── QuickBooks & Odoo Import Modal Dropzone & File Handling ──
    (function () {
        var dropzone   = document.getElementById('qbImportDropzone');
        var fileInput  = document.getElementById('qbImportFileInput');
        var promptEl   = document.getElementById('qbDropzonePrompt');
        var previewEl  = document.getElementById('qbDropzoneFilePreview');
        var fileNameEl = document.getElementById('qbFileName');
        var fileSizeEl = document.getElementById('qbFileSize');
        var fileIconEl = document.getElementById('qbFileIcon');
        var removeBtn  = document.getElementById('qbFileRemoveBtn');
        var form       = document.getElementById('qbImportForm');
        var submitBtn  = document.getElementById('qbImportSubmitBtn');

        if (!dropzone || !fileInput) return;

        // Visual Drag & Drop indicators
        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'dragend', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            }, false);
        });

        // Drop handling (native input also catches drop, but this ensures DataTransfer files map properly)
        dropzone.addEventListener('drop', function (e) {
            var dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length) {
                try {
                    fileInput.files = dt.files;
                } catch (err) {}
                handleFile(dt.files[0]);
            }
        }, false);

        // Native Change event on file input
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length) {
                handleFile(this.files[0]);
            }
        });

        function handleFile(file) {
            if (!file) return;
            var ext = file.name.split('.').pop().toLowerCase();

            // If an Excel spreadsheet (.xlsx, .xls) is selected, automatically convert to CSV in the browser!
            if ((ext === 'xlsx' || ext === 'xls') && typeof XLSX !== 'undefined') {
                if (promptEl) promptEl.classList.add('d-none');
                if (previewEl) {
                    previewEl.classList.remove('d-none');
                    previewEl.classList.add('d-flex');
                }
                if (fileIconEl) fileIconEl.innerHTML = '<i class="fas fa-spinner fa-spin text-primary fs-5"></i>';
                if (fileNameEl) {
                    fileNameEl.innerHTML = '<span class="text-muted">' + escapeHtml(file.name) + '</span> ' +
                        '<span class="badge bg-warning text-dark ms-1" style="font-size:10px;"><i class="fas fa-magic"></i> Converting to CSV...</span>';
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-cog fa-spin me-1.5"></i> Converting Excel to CSV...';
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, {type: 'array', cellDates: true});
                        var firstSheetName = workbook.SheetNames[0];
                        var worksheet = workbook.Sheets[firstSheetName];
                        var csvString = XLSX.utils.sheet_to_csv(worksheet, {FS: ',', RS: '\n'});

                        // Clean out trailing empty rows / pure commas if any
                        csvString = csvString.replace(/(?:[\r\n]+(?:\s*,)*\s*)+$/, '');

                        var newFileName = file.name.replace(/\.(xlsx|xls)$/i, '') + '.csv';
                        var csvBlob = new Blob([csvString], {type: 'text/csv;charset=utf-8;'});
                        var csvFile = new File([csvBlob], newFileName, {type: 'text/csv'});

                        // Replace file input's files with the newly converted CSV File
                        if (window.DataTransfer) {
                            var dt = new DataTransfer();
                            dt.items.add(csvFile);
                            fileInput.files = dt.files;
                        }

                        renderFileState(csvFile, true, file.name);
                    } catch (err) {
                        console.warn('Browser XLSX-to-CSV conversion failed, falling back to original file:', err);
                        renderFileState(file, false);
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-check-double me-1.5"></i> Validate &amp; Preview Import';
                        }
                    }
                };
                reader.readAsArrayBuffer(file);
                return;
            }

            renderFileState(file, false);
        }

        function renderFileState(file, wasConverted, originalName) {
            var ext = file.name.split('.').pop().toLowerCase();
            if (fileNameEl) {
                if (wasConverted) {
                    fileNameEl.innerHTML = '<span class="fw-bold text-dark">' + escapeHtml(file.name) + '</span> ' +
                        '<span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size:10px;"><i class="fas fa-check-circle me-1"></i> Auto-Converted to CSV</span>';
                } else {
                    fileNameEl.textContent = file.name;
                }
            }

            var sizeKb = (file.size / 1024).toFixed(1);
            if (fileSizeEl) {
                if (file.size > 1024 * 1024) {
                    fileSizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                } else {
                    fileSizeEl.textContent = sizeKb + ' KB';
                }
            }

            if (fileIconEl) {
                if (wasConverted || ext === 'csv') {
                    fileIconEl.innerHTML = '<i class="fas fa-file-csv text-success fs-5"></i>';
                } else if (ext === 'xlsx' || ext === 'xls') {
                    fileIconEl.innerHTML = '<i class="fas fa-file-excel text-success fs-5"></i>';
                } else {
                    fileIconEl.innerHTML = '<i class="fas fa-file-alt text-secondary fs-5"></i>';
                }
            }

            if (promptEl) promptEl.classList.add('d-none');
            if (previewEl) {
                previewEl.classList.remove('d-none');
                previewEl.classList.add('d-flex');
            }

            fileInput.style.zIndex = '1';
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.value = '';
                if (promptEl) promptEl.classList.remove('d-none');
                if (previewEl) {
                    previewEl.classList.add('d-none');
                    previewEl.classList.remove('d-flex');
                }
                fileInput.style.zIndex = '5';
            });
        }

        // Form Submit Validation & Loading Indicator
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!fileInput.files || !fileInput.files.length) {
                    e.preventDefault();
                    alert('Please select an Excel (.xlsx, .xls) or CSV (.csv) file first.');
                    return false;
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1.5"></i> Uploading &amp; Validating...';
                }
                return true;
            });
        }
    })();

    // ── Select All ──
    $('#selectAll').click(function() {
        $('.selectProduct').prop('checked', this.checked);
    });

    // ── DataTable init ── (responsive:false – we use CSS horizontal scroll instead)
    let table = $('#productTable').DataTable({
        responsive: false,
        paging:     false,
        ordering:   true,
        info:       false,
        order:      [[4, 'asc']],
        dom:        'rt',
        scrollX:    false,
        autoWidth:  false,
        columnDefs: [{ targets: [0, 12], orderable: false, searchable: false }]
    });   // DataTable closes here

    // ══════════════════════════════════════════════════════════════
    // QUICKBOOKS POS STYLE: CUSTOMIZE PRODUCT TABLE COLUMNS
    // ══════════════════════════════════════════════════════════════
    const INDEX_COLUMNS = [
        { index: 1,  key: 'serial_num',        label: 'Serial #',           default: true },
        { index: 2,  key: 'item_unique_code',  label: 'Item Unique Code',   default: true },
        { index: 3,  key: 'image',             label: 'Image',              default: true },
        { index: 4,  key: 'item_details',      label: 'Item Details',       default: true },
        { index: 5,  key: 'stock',             label: 'Stock',              default: true },
        { index: 6,  key: 'cost_price',        label: 'Purchase Price',     default: true },
        { index: 7,  key: 'sky_price',         label: 'Sky Price',          default: true },
        { index: 8,  key: 'sky_pcode',         label: 'Sky P-Code',         default: true },
        { index: 9,  key: 'rot_price',         label: 'Rot Price',          default: true },
        { index: 10, key: 'rot_pcode',         label: 'Rot P-Code',         default: true },
        { index: 11, key: 'status',            label: 'Status',             default: true },
        { index: 12, key: 'actions',           label: 'Actions',            default: true },
    ];

    const IDX_STORAGE_KEY = 'kent_product_table_columns_v3';

    function getIndexColPrefs() {
        try {
            const saved = localStorage.getItem(IDX_STORAGE_KEY);
            if (saved) return JSON.parse(saved);
        } catch(e) {}
        const defaults = {};
        INDEX_COLUMNS.forEach(c => defaults[c.key] = c.default);
        return defaults;
    }

    function saveIndexColPrefs(prefs) {
        try {
            localStorage.setItem(IDX_STORAGE_KEY, JSON.stringify(prefs));
        } catch(e) {}
    }

    function applyIndexColVisibility(prefs) {
        INDEX_COLUMNS.forEach(col => {
            const isVisible = prefs[col.key] !== false;
            table.column(col.index).visible(isVisible, false);
        });
        table.columns.adjust().draw(false);
        if (typeof initTableColumnResizing === 'function') {
            setTimeout(initTableColumnResizing, 50);
        }
    }

    function renderIndexColumnsChecklist() {
        const checklistContainer = document.getElementById('indexColumnsChecklist');
        if (!checklistContainer) return;

        const prefs = getIndexColPrefs();
        checklistContainer.innerHTML = '';

        INDEX_COLUMNS.forEach(col => {
            const isChecked = prefs[col.key] !== false;
            const colDiv = document.createElement('div');
            colDiv.className = 'col-6';
            colDiv.innerHTML = `
                <div class="form-check p-2 rounded border bg-light d-flex align-items-center gap-2" style="cursor: pointer; transition: background 0.15s;">
                    <input class="form-check-input ms-0 mt-0 idx-col-chk" type="checkbox" id="chk_idx_${col.key}" data-key="${col.key}" data-index="${col.index}" ${isChecked ? 'checked' : ''} style="cursor: pointer; width: 16px; height: 16px;">
                    <label class="form-check-label small fw-semibold text-dark mb-0 text-truncate" for="chk_idx_${col.key}" style="cursor: pointer; user-select: none;">
                        ${col.label}
                    </label>
                </div>
            `;
            checklistContainer.appendChild(colDiv);
        });

        checklistContainer.querySelectorAll('.idx-col-chk').forEach(chk => {
            chk.addEventListener('change', function() {
                const colKey = this.dataset.key;
                const colIdx = parseInt(this.dataset.index, 10);
                const currentPrefs = getIndexColPrefs();
                currentPrefs[colKey] = this.checked;
                saveIndexColPrefs(currentPrefs);
                table.column(colIdx).visible(this.checked);
                table.columns.adjust().draw(false);
            });
        });
    }

    const customizeIndexModalEl = document.getElementById('customizeIndexColumnsModal');

    function showCustomizeIndexModal() {
        renderIndexColumnsChecklist();
        if (!customizeIndexModalEl) return;
        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery(customizeIndexModalEl).modal === 'function') {
                jQuery(customizeIndexModalEl).modal('show');
                return;
            }
        } catch(e) {}
        try {
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(customizeIndexModalEl) : null;
                (inst || new bootstrap.Modal(customizeIndexModalEl)).show();
                return;
            }
        } catch(e) {}
        // Fallback display
        customizeIndexModalEl.classList.add('show');
        customizeIndexModalEl.style.display = 'block';
        customizeIndexModalEl.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
        let bd = document.querySelector('.qb-idx-backdrop');
        if (!bd) {
            bd = document.createElement('div');
            bd.className = 'modal-backdrop fade show qb-idx-backdrop';
            document.body.appendChild(bd);
            bd.onclick = hideCustomizeIndexModal;
        }
    }

    function hideCustomizeIndexModal() {
        if (!customizeIndexModalEl) return;
        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery(customizeIndexModalEl).modal === 'function') {
                jQuery(customizeIndexModalEl).modal('hide');
            }
        } catch(e) {}
        try {
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(customizeIndexModalEl) : null;
                if (inst) inst.hide();
            }
        } catch(e) {}
        customizeIndexModalEl.classList.remove('show');
        customizeIndexModalEl.style.display = 'none';
        customizeIndexModalEl.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        const bd = document.querySelector('.qb-idx-backdrop');
        if (bd) bd.remove();
    }

    $('.qb-idx-modal-close').on('click', function(e) {
        e.preventDefault();
        hideCustomizeIndexModal();
    });

    $('#customizeIndexColumnsBtn').on('click', function(e) {
        e.preventDefault();
        showCustomizeIndexModal();
    });

    if (customizeIndexModalEl && typeof jQuery !== 'undefined') {
        jQuery(customizeIndexModalEl).on('show.bs.modal', function() {
            renderIndexColumnsChecklist();
        });
    }

    $('#idxColSelectAll').on('click', function() {
        const prefs = {};
        INDEX_COLUMNS.forEach(c => {
            prefs[c.key] = true;
            table.column(c.index).visible(true, false);
        });
        table.columns.adjust().draw(false);
        saveIndexColPrefs(prefs);
        renderIndexColumnsChecklist();
    });

    $('#idxColResetDefault').on('click', function() {
        const prefs = {};
        INDEX_COLUMNS.forEach(c => {
            prefs[c.key] = c.default;
            table.column(c.index).visible(c.default, false);
        });
        table.columns.adjust().draw(false);
        saveIndexColPrefs(prefs);
        renderIndexColumnsChecklist();
    });

    // Right click on table header (QuickBooks POS Desktop style)
    const qbIndexContextMenu = document.getElementById('qbIndexContextMenu');

    if (qbIndexContextMenu && qbIndexContextMenu.parentElement !== document.body) {
        document.body.appendChild(qbIndexContextMenu);
    }

    const hideIndexContextMenu = function() {
        if (qbIndexContextMenu) {
            qbIndexContextMenu.style.display = 'none';
        }
    };

    const positionIndexContextMenu = function(e) {
        if (!qbIndexContextMenu) return;
        if (qbIndexContextMenu.parentElement !== document.body) {
            document.body.appendChild(qbIndexContextMenu);
        }

        qbIndexContextMenu.style.visibility = 'hidden';
        qbIndexContextMenu.style.display = 'block';

        const menuWidth = qbIndexContextMenu.offsetWidth || 210;
        const menuHeight = qbIndexContextMenu.offsetHeight || 105;

        let posX = e.clientX;
        let posY = e.clientY;

        if (posX + menuWidth > window.innerWidth - 8) {
            posX = window.innerWidth - menuWidth - 8;
        }
        if (posY + menuHeight > window.innerHeight - 8) {
            posY = window.innerHeight - menuHeight - 8;
        }

        posX = Math.max(8, posX);
        posY = Math.max(8, posY);

        qbIndexContextMenu.style.left = posX + 'px';
        qbIndexContextMenu.style.top = posY + 'px';
        qbIndexContextMenu.style.visibility = 'visible';
    };

    $('#productTable thead').on('contextmenu', function(e) {
        e.preventDefault();
        e.stopPropagation();
        positionIndexContextMenu(e.originalEvent || e);
    });

    // Dismiss on any scroll or resize
    window.addEventListener('scroll', hideIndexContextMenu, { passive: true });
    window.addEventListener('resize', hideIndexContextMenu, { passive: true });
    document.addEventListener('scroll', hideIndexContextMenu, { passive: true, capture: true });

    $(document).on('click', function(e) {
        if (qbIndexContextMenu && !qbIndexContextMenu.contains(e.target)) {
            hideIndexContextMenu();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            hideIndexContextMenu();
        }
    });

    $('#qbCtxCustomizeIndexBtn').on('click', function(e) {
        e.preventDefault();
        hideIndexContextMenu();
        showCustomizeIndexModal();
    });

    $('#qbCtxResetIndexBtn').on('click', function(e) {
        e.preventDefault();
        hideIndexContextMenu();
        $('#idxColResetDefault').click();
    });

    // CRITICAL: Render checklist and apply column visibility immediately on page load!
    renderIndexColumnsChecklist();
    applyIndexColVisibility(getIndexColPrefs());

    // ══════════════════════════════════════════════════════════════
    // DRAGGABLE COLUMN WIDTH RESIZING (Excel / QuickBooks POS Desktop Style)
    // ══════════════════════════════════════════════════════════════
    const COL_WIDTH_STORAGE_KEY = 'kent_product_col_widths_v3';

    function getSavedColWidths() {
        try {
            const raw = localStorage.getItem(COL_WIDTH_STORAGE_KEY);
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        return {};
    }

    function saveColWidths(widths) {
        try {
            localStorage.setItem(COL_WIDTH_STORAGE_KEY, JSON.stringify(widths));
        } catch(e) {}
    }

    function initTableColumnResizing() {
        const $table = $('#productTable');
        if (!$table.length) return;

        const savedWidths = getSavedColWidths();
        const $visibleThs = $table.find('thead th:visible');

        let totalCalculatedWidth = 0;

        $visibleThs.each(function(i) {
            const $th = $(this);
            const colKey = $th.attr('data-col-key') || ('col_' + i);

            // Apply saved width if exists
            if (savedWidths[colKey]) {
                const w = parseInt(savedWidths[colKey], 10);
                $th.css({
                    'width': w + 'px',
                    'min-width': w + 'px',
                    'max-width': w + 'px'
                });
                totalCalculatedWidth += w;
            } else {
                const currentW = Math.round($th.outerWidth());
                $th.css({
                    'width': currentW + 'px',
                    'min-width': currentW + 'px',
                    'max-width': currentW + 'px'
                });
                totalCalculatedWidth += currentW;
            }

            // Remove existing resizer if any to avoid duplication
            $th.find('.col-resizer').remove();

            // Create resizer handle
            const $resizer = $('<div class="col-resizer" title="Drag to adjust width (Double-click to reset)"></div>');
            $th.append($resizer);

            $resizer.on('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const startX = e.pageX;
                const startW = $th.outerWidth();
                const startTableW = $table.outerWidth();
                let hasMoved = false;

                $resizer.addClass('is-resizing');
                $('body').addClass('is-col-resizing');

                function onMouseMove(moveEvent) {
                    const diff = moveEvent.pageX - startX;
                    if (Math.abs(diff) > 2) {
                        hasMoved = true;
                    }

                    const minWidth = (colKey === 'select') ? 28 : (colKey === 'serial_num' ? 30 : (colKey === 'item_unique_code' ? 70 : 45));
                    const newW = Math.max(minWidth, Math.round(startW + diff));
                    const actualDiff = newW - startW;

                    $th.css({
                        'width': newW + 'px',
                        'min-width': newW + 'px',
                        'max-width': newW + 'px'
                    });

                    const parentW = $table.parent().width();
                    const newTableW = Math.max(parentW, startTableW + actualDiff);
                    $table.css({
                        'width': newTableW + 'px',
                        'min-width': newTableW + 'px'
                    });
                }

                function onMouseUp(upEvent) {
                    $(document).off('mousemove', onMouseMove);
                    $(document).off('mouseup', onMouseUp);

                    $resizer.removeClass('is-resizing');
                    $('body').removeClass('is-col-resizing');

                    if (hasMoved) {
                        // Suppress DataTables sorting on this th
                        $th.one('click', function(clickEvent) {
                            clickEvent.stopImmediatePropagation();
                            clickEvent.preventDefault();
                        });

                        const currentWidths = getSavedColWidths();
                        currentWidths[colKey] = Math.round($th.outerWidth());
                        saveColWidths(currentWidths);
                    }
                }

                $(document).on('mousemove', onMouseMove);
                $(document).on('mouseup', onMouseUp);
            });

            // Double click on resizer resets column to default
            $resizer.on('dblclick', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const currentWidths = getSavedColWidths();
                delete currentWidths[colKey];
                saveColWidths(currentWidths);

                $th.css({
                    'width': '',
                    'min-width': '',
                    'max-width': ''
                });
                $table.css({
                    'width': '100%',
                    'min-width': '100%'
                });
                setTimeout(initTableColumnResizing, 60);
            });
        });

        if (totalCalculatedWidth > 0) {
            const parentWidth = $table.parent().width();
            const finalTableWidth = Math.max(parentWidth, totalCalculatedWidth);
            $table.css({
                'width': finalTableWidth + 'px',
                'min-width': finalTableWidth + 'px'
            });
        }
    }

    // Initialize resizing on load
    setTimeout(initTableColumnResizing, 120);

    // Context menu: Reset Column Widths
    $('#qbCtxResetWidthsBtn').on('click', function(e) {
        e.preventDefault();
        hideIndexContextMenu();
        localStorage.removeItem(COL_WIDTH_STORAGE_KEY);
        $('#productTable thead th').css({ 'width': '', 'min-width': '', 'max-width': '' });
        $('#productTable').css({ 'width': '100%', 'min-width': '100%' });
        setTimeout(initTableColumnResizing, 80);
    });

    // ── Select All ──
    $('#selectAll').click(function() {
        $('.selectProduct').prop('checked', this.checked);
    });

    // ══════════════════════════════════════════════════════════════
    // ODOO VIDEO 2 STYLE: DYNAMIC RADIO MATRIX VARIANT VIEW
    // ══════════════════════════════════════════════════════════════
    let currentLoadedProduct = null;
    let currentLoadedVariants = [];
    let currentSelectedAttrs = {};
    let currentMatchedVariant = null;
    let matrixShowPriceDigits = false;

    $(document).on('click', '#btnToggleMatrixPriceDigits', function(e) {
        e.preventDefault();
        matrixShowPriceDigits = !matrixShowPriceDigits;
        updateMatrixPriceVisibility();
    });

    function updateMatrixPriceVisibility() {
        if (matrixShowPriceDigits) {
            $('.matrix-numeric-price, .matrix-numeric-divider').removeClass('d-none');
            $('#iconMatrixPriceEye').removeClass('fa-eye-slash').addClass('fa-eye');
            $('#textMatrixPriceToggle').text('Hide Prices');
            $('#btnToggleMatrixPriceDigits').attr('title', 'Hide Numeric Prices');
        } else {
            $('.matrix-numeric-price, .matrix-numeric-divider').addClass('d-none');
            $('#iconMatrixPriceEye').removeClass('fa-eye').addClass('fa-eye-slash');
            $('#textMatrixPriceToggle').text('Show Prices');
            $('#btnToggleMatrixPriceDigits').attr('title', 'Show Numeric Prices');
        }
    }

    // View mode switch buttons inside modal
    $('#btnModeMatrix').on('click', function() {
        $('#modalMatrixContainer').removeClass('d-none');
        $('#modalContentRow').addClass('d-none');
        $('#btnModeMatrix').addClass('active').css({'background': '#2563eb', 'color': '#ffffff'}).removeClass('btn-light text-muted');
        $('#btnModeTable').removeClass('active btn-primary').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
    });

    $('#btnModeTable').on('click', function() {
        $('#modalMatrixContainer').addClass('d-none');
        $('#modalContentRow').removeClass('d-none');
        $('#btnModeTable').addClass('active btn-primary').css({'background': '#2563eb', 'color': '#ffffff'}).removeClass('btn-light text-muted');
        $('#btnModeMatrix').removeClass('active').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
    });

    // Row radio button click -> automatically opens Matrix View for that product
    $(document).on('click', '.row-matrix-radio', function(e) {
        let productId = $(this).data('id');
        openProductView(productId, 'matrix');
    });

    // Global matrix radio toggle
    $('#globalMatrixRadio').on('change', function() {
        if ($(this).is(':checked')) {
            $('.row-matrix-radio').prop('checked', true);
        } else {
            $('.row-matrix-radio').prop('checked', false);
        }
    });

    // View Button click
    $(document).on('click', '.viewProductBtn', function() {
        let productId = $(this).data('id');
        let isRowMatrix = $(`#rowMatrixRadio_${productId}`).is(':checked') || $(`#mRowMatrixRadio_${productId}`).is(':checked');
        let isGlobalMatrix = $('#globalMatrixRadio').is(':checked');
        let mode = (isRowMatrix || isGlobalMatrix) ? 'matrix' : 'table';
        openProductView(productId, mode);
    });

    function openProductView(productId, mode = 'matrix') {
        $('#modalContentRow').addClass('d-none');
        $('#modalMatrixContainer').addClass('d-none');
        $('#modalLoadingSpinner').removeClass('d-none');

        // Set mode buttons in modal
        if (mode === 'matrix') {
            $('#btnModeMatrix').addClass('active').css({'background': '#2563eb', 'color': '#ffffff'}).removeClass('btn-light text-muted');
            $('#btnModeTable').removeClass('active btn-primary').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
            $('#modalMatrixContainer').removeClass('d-none');
            $('#modalContentRow').addClass('d-none');
        } else {
            $('#btnModeTable').addClass('active btn-primary').css({'background': '#2563eb', 'color': '#ffffff'}).removeClass('btn-light text-muted');
            $('#btnModeMatrix').removeClass('active').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
            $('#modalContentRow').removeClass('d-none');
            $('#modalMatrixContainer').addClass('d-none');
        }

        const modalEl = document.getElementById('productViewModal');
        if (modalEl) {
            try {
                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    jQuery(modalEl).modal('show');
                } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                    const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(modalEl) : null;
                    (inst || new bootstrap.Modal(modalEl)).show();
                } else {
                    $('#productViewModal').modal('show');
                }
            } catch(e) {
                try { $('#productViewModal').modal('show'); } catch(err) {}
            }
        }

        $.ajax({
            url: "/productview/" + productId,
            type: "GET",
            success: function(product) {
                $('#modalLoadingSpinner').addClass('d-none');
                currentLoadedProduct = product;

                // 1. Setup Header
                $('#view_item_name').text(product.item_name ?? 'Unknown');
                let codeHtml = product.item_code ? `<span class="badge font-monospace" style="background: #0f172a; color: #ffffff; font-size: 10.5px; border-radius: 4px; padding: 2.5px 7px;">${escapeHtml(product.item_code)}</span>` : '';
                let catHtml = product.category_relation?.name ? `<span class="badge text-capitalize" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; padding: 2.5px 7px; border-radius: 4px;"><i class="fas fa-folder text-primary" style="margin-right: 4px;"></i>${escapeHtml(product.category_relation.name)}</span>` : '';
                let brandName = (product.brand?.name ?? '').trim();
                let brandHtml = (brandName && brandName !== '-' && brandName.toLowerCase() !== 'no brand') ? `<span class="badge text-capitalize" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; padding: 2.5px 7px; border-radius: 4px;"><i class="fas fa-tag text-secondary" style="margin-right: 4px;"></i>${escapeHtml(brandName)}</span>` : '';
                $('#view_item_subtext').html(`${codeHtml} ${catHtml} ${brandHtml}`);

                // 2. Parse Variants
                let variants = [];
                let colorList = ['-'];
                if (product.color) {
                    let parsed = product.color;
                    if (typeof parsed === 'string') {
                        try { parsed = JSON.parse(parsed); } catch (e) {}
                    }
                    if (typeof parsed === 'string') {
                        try { parsed = JSON.parse(parsed); } catch (e) {}
                    }
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        if (typeof parsed[0] === 'object' && parsed[0] !== null) {
                            variants = parsed;
                        } else {
                            colorList = parsed;
                        }
                    } else if (typeof parsed === 'object' && parsed !== null) {
                        variants = [parsed];
                    } else if (typeof parsed === 'string') {
                        colorList = [parsed];
                    }
                }
                currentLoadedVariants = variants;

                // 3. Render Table View (for Table Mode)
                renderTableView(product, variants, colorList);

                // 4. Render Odoo Video 2 Style Matrix View
                renderOdooMatrixView(product, variants);

                if (mode === 'matrix') {
                    $('#modalMatrixContainer').removeClass('d-none');
                    $('#modalContentRow').addClass('d-none');
                } else {
                    $('#modalContentRow').removeClass('d-none');
                    $('#modalMatrixContainer').addClass('d-none');
                }
            },
            error: function() {
                $('#modalLoadingSpinner').addClass('d-none');
                Swal.fire('Error', 'Could not fetch product details.', 'error');
            }
        });
    }

    function renderOdooMatrixView(product, variants) {
        // Base Info
        $('#matrix_item_name').text(product.item_name || 'Product');
        let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
        let baseRotPrice = product.wholesale_price || 0;
        $('#matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
        let defaultSkyPCode = (window.encodeToPCode ? window.encodeToPCode(basePrice) : '') || product.p_code;
        $('#matrix_item_pcode').text(defaultSkyPCode || '---');
        $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
        let defaultRotPCode = (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '') || product.rot_p_code;
        $('#matrix_item_rot_pcode').text(defaultRotPCode || '---');
        
        let totalStock = product.calculated_total_stock_qty ?? 0;
        let baseUnit = product.unit ? product.unit.name : 'Units';
        $('#matrix_stock_display').text(totalStock + ' ' + baseUnit);

        $('#matrix_serial_badge_container').addClass('d-none');
        $('#matrix_location_badge_wrap').addClass('d-none');
        $('#matrix_location_text').text('---');
        updateMatrixPriceVisibility();
        $('#btnMatrixAdd').prop('disabled', true);
        $('#matrixSelectedVariantInfo').html('<span class="text-muted"><i class="fas fa-info-circle me-1"></i> Select attributes above to view stock and pricing</span>');

        currentSelectedAttrs = {};
        currentMatchedVariant = null;

        const $attrGroups = $('#matrixAttributeGroups');
        $attrGroups.empty();

        if (!variants || variants.length === 0) {
            $attrGroups.html(`
                <div class="alert alert-light border text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">This product has no variants configured. It is a single standard product.</p>
                </div>
            `);
            $('#btnMatrixAdd').prop('disabled', false).html('<i class="fas fa-plus me-1"></i> Add Product');
            return;
        }

        // Extract Attributes from variants with smart fractional preservation
        const attrMap = extractProductAttributes(product, variants);
        const attrKeys = Object.keys(attrMap);

        if (attrKeys.length === 0) {
            $attrGroups.html(`
                <div class="alert alert-light border text-center py-4">
                    <p class="text-muted mb-0">No distinct attributes found.</p>
                </div>
            `);
            return;
        }

        // Build UI for each attribute
        attrKeys.forEach((attrKey, aIdx) => {
            const values = attrMap[attrKey];
            let radioItemsHtml = '';

            values.forEach((val, vIdx) => {
                const radioId = `matrix_radio_${aIdx}_${vIdx}`;
                radioItemsHtml += `
                    <label class="matrix-radio-pill" for="${radioId}">
                        <input type="radio" 
                               name="matrix_attr_${aIdx}" 
                               id="${radioId}" 
                               value="${val}" 
                               data-attr="${attrKey}" 
                               class="matrix-attr-input" 
                               style="position: static !important; width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer; margin: 0; vertical-align: middle;">
                        <span class="matrix-attr-val" style="font-size: 13.5px; color: #1e293b; font-weight: 600; margin-left: 7px; user-select: none;">
                            ${val}
                        </span>
                    </label>
                `;
            });

            const groupHtml = `
                <div class="matrix-attr-row mb-3" data-attr="${attrKey}">
                    <div class="fw-bold text-dark mb-2" style="font-size: 14px; letter-spacing: -0.2px;">
                        ${attrKey}
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 matrix-values-wrap">
                        ${radioItemsHtml}
                    </div>
                </div>
            `;
            $attrGroups.append(groupHtml);
        });

        // Pill change event handler
        $('.matrix-attr-input').on('change', function() {
            const changedAttr = $(this).data('attr');
            const changedVal = $(this).val();
            currentSelectedAttrs[changedAttr] = changedVal;

            // Highlight selected pill
            $(this).closest('.matrix-attr-row').find('.matrix-radio-pill').removeClass('selected-pill');
            $(this).closest('.matrix-radio-pill').addClass('selected-pill');

            updateMatrixCombinations(product, variants, attrKeys);
        });
    }

    function extractProductAttributes(product, variants) {
        let attrMap = {};
        let nameParts = [];
        let hasNamePattern = false;

        variants.forEach(v => {
            let vName = v.name || v.variant_name || '';
            let match = vName.match(/\((.*?)\)/);
            if (match && match[1]) {
                // Split strictly by ' / ' (space slash space) so fractions like 1/2 or 3/4 are not broken!
                let parts = match[1].split(/\s+\/\s+/).map(s => s.trim()).filter(Boolean);
                if (parts.length >= 2) {
                    hasNamePattern = true;
                    nameParts.push(parts);
                }
            }
        });

        if (hasNamePattern && nameParts.length === variants.length) {
            let numParts = nameParts[0].length;
            let partDefs = [];

            for (let i = 0; i < numParts; i++) {
                let distinctVals = [...new Set(nameParts.map(p => p[i]))];
                let isFractionOrMeasurement = distinctVals.some(v => v.includes('/') || /\d+["']|\d+mm|\d+cm/i.test(v));
                let isAllNumbers = distinctVals.every(v => /^\d+(\.\d+)?$/.test(v));
                
                // Check if matches v.size or v.color
                let matchesSize = variants.every((v, idx) => (v.size && v.size !== '-' && v.size === nameParts[idx][i]));
                let matchesColor = variants.every((v, idx) => (v.color && v.color !== '-' && v.color === nameParts[idx][i]));

                partDefs.push({
                    partIndex: i,
                    distinctVals: distinctVals,
                    isFractionOrMeasurement: isFractionOrMeasurement,
                    isAllNumbers: isAllNumbers,
                    matchesSize: matchesSize,
                    matchesColor: matchesColor,
                    count: distinctVals.length
                });
            }

            // Determine labels
            let pNameLower = (product.item_name || '').toLowerCase();
            let isScrewOrFastener = pNameLower.includes('screw') || pNameLower.includes('bolt') || pNameLower.includes('nut') || pNameLower.includes('nail');

            partDefs.forEach((pd, i) => {
                let label = '';
                if (pd.matchesSize || pd.isFractionOrMeasurement) {
                    label = isScrewOrFastener ? 'Length' : 'Size';
                } else if (pd.matchesColor || (isScrewOrFastener && pd.isAllNumbers)) {
                    label = isScrewOrFastener ? 'Gauge' : 'Color';
                } else {
                    label = `Option ${i + 1}`;
                }

                // If label already exists, append counter
                let finalLabel = label;
                let counter = 2;
                while (partDefs.some((other, oIdx) => oIdx < i && other.label === finalLabel)) {
                    finalLabel = `${label} ${counter++}`;
                }
                pd.label = finalLabel;
            });

            // If some parts vary (count > 1), only show parts that vary so single constant labels don't clutter
            let varyingCount = partDefs.filter(p => p.count > 1).length;
            let activeDefs = varyingCount > 0 ? partDefs.filter(p => p.count > 1) : partDefs;

            // Sort so Gauge comes before Length (standard video order)
            activeDefs.sort((a, b) => {
                if (a.label === 'Gauge' && b.label === 'Length') return -1;
                if (a.label === 'Length' && b.label === 'Gauge') return 1;
                return 0;
            });

            // Build attrMap and variant attributes
            activeDefs.forEach(pd => {
                attrMap[pd.label] = pd.distinctVals;
            });

            variants.forEach((v, vIdx) => {
                v._attrs = {};
                partDefs.forEach(pd => {
                    v._attrs[pd.label] = nameParts[vIdx][pd.partIndex];
                });
            });
        } else {
            let sizes = [...new Set(variants.map(v => v.size || v.variant_size).filter(s => s && s !== '-'))];
            let colors = [...new Set(variants.map(v => v.color || v.variant_color).filter(c => c && c !== '-'))];

            let pNameLower = (product.item_name || '').toLowerCase();
            let isScrewOrFastener = pNameLower.includes('screw') || pNameLower.includes('bolt') || pNameLower.includes('nut') || pNameLower.includes('nail');
            let sizeLabel = isScrewOrFastener ? 'Length' : 'Size';
            let colorLabel = isScrewOrFastener ? 'Gauge' : 'Color';

            if (colors.length > 0) attrMap[colorLabel] = colors;
            if (sizes.length > 0) attrMap[sizeLabel] = sizes;

            variants.forEach(v => {
                v._attrs = {};
                if (colors.length > 0) v._attrs[colorLabel] = v.color || v.variant_color || '-';
                if (sizes.length > 0) v._attrs[sizeLabel] = v.size || v.variant_size || '-';
            });
        }

        return attrMap;
    }

    function updateMatrixCombinations(product, variants, attrKeys) {
        // Dynamic Inactive / Disabled state as shown in video:
        // For each attribute row, check which options are valid with currentSelectedAttrs
        attrKeys.forEach((attrKey, aIdx) => {
            const $row = $(`.matrix-attr-row[data-attr="${attrKey}"]`);
            $row.find('.matrix-attr-input').each(function() {
                const $radio = $(this);
                const val = $radio.val();
                const $pill = $radio.closest('.matrix-radio-pill');

                // Hypothetical selection
                const testAttrs = Object.assign({}, currentSelectedAttrs);
                testAttrs[attrKey] = val;

                // Check if ANY variant matches testAttrs on all set keys
                const hasValidVariant = variants.some(v => {
                    return Object.entries(testAttrs).every(([k, vVal]) => {
                        return !vVal || v._attrs[k] === vVal;
                    });
                });

                if (hasValidVariant) {
                    $radio.prop('disabled', false);
                    $pill.removeClass('disabled-pill');
                } else {
                    $radio.prop('disabled', true);
                    if ($radio.is(':checked')) {
                        $radio.prop('checked', false);
                        delete currentSelectedAttrs[attrKey];
                        $pill.removeClass('selected-pill');
                    }
                    $pill.addClass('disabled-pill');
                }
            });
        });

        // Check if all attributes have been selected
        const allSelected = attrKeys.every(k => !!currentSelectedAttrs[k]);

        if (allSelected) {
            // Find exact variant
            const matched = variants.find(v => {
                return attrKeys.every(k => v._attrs[k] === currentSelectedAttrs[k]);
            });

            if (matched) {
                currentMatchedVariant = matched;
                
                // Update Price
                const salePrice = matched.sale_price !== undefined ? matched.sale_price : (product.sale_price_per_piece || 0);
                const rotPrice = matched.wholesale_price !== undefined ? matched.wholesale_price : (product.wholesale_price || 0);
                $('#matrix_item_price').text('Rs. ' + parseFloat(salePrice).toFixed(2));
                $('#matrix_item_rot_price').text('Rs. ' + parseFloat(rotPrice).toFixed(2));

                // Update P-Codes
                const vSkyPCode = matched.sky_p_code || matched.p_code || (window.encodeToPCode ? window.encodeToPCode(salePrice) : '');
                const vRotPCode = matched.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(rotPrice) : '');
                $('#matrix_item_pcode').text(vSkyPCode || '---');
                $('#matrix_item_rot_pcode').text(vRotPCode || '---');

                // Update Free to use stock
                const stockQty = matched.stock !== undefined ? matched.stock : 0;
                const unit = matched.unit || product.unit?.name || 'Units';
                $('#matrix_stock_display').text(`${stockQty} ${unit}`);

                // Serial No & Location
                const vSerial = matched.serial_no ? matched.serial_no.trim() : '';
                const vLocation = matched.location ? matched.location.trim() : (matched.rack_shelf ? matched.rack_shelf.trim() : (product.remarks ? product.remarks.trim() : ''));

                if (vSerial || vLocation) {
                    if (vSerial) {
                        $('#matrix_serial_badge').text(vSerial).removeClass('d-none');
                    } else {
                        $('#matrix_serial_badge').addClass('d-none');
                    }

                    if (vLocation) {
                        $('#matrix_location_text').text(vLocation);
                        $('#matrix_location_badge_wrap').removeClass('d-none');
                    } else {
                        $('#matrix_location_badge_wrap').addClass('d-none');
                        $('#matrix_location_text').text('---');
                    }
                    $('#matrix_serial_badge_container').removeClass('d-none');
                } else {
                    $('#matrix_serial_badge_container').addClass('d-none');
                    $('#matrix_location_badge_wrap').addClass('d-none');
                    $('#matrix_location_text').text('---');
                }

                $('#matrixSelectedVariantInfo').html(`
                    <span class="badge font-monospace" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px; padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center;">
                        <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Ready
                    </span>
                    <strong class="text-dark text-capitalize font-monospace" style="font-size: 12px; margin-left: 4px;">${escapeHtml(matched.name || matched.variant_name || product.item_name)}</strong>
                    ${vSerial ? `<span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">[${escapeHtml(vSerial)}]</span>` : ''}
                    ${vLocation ? `<span class="badge font-monospace" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;"><i class="fas fa-map-marker-alt text-danger" style="margin-right: 4px;"></i>${escapeHtml(vLocation)}</span>` : ''}
                    <span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">Sky: ${escapeHtml(vSkyPCode)}</span>
                    <span class="badge font-monospace" style="background: #f0fdf4; color: #065f46; border: 1px solid #bbf7d0; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">Rot: ${escapeHtml(vRotPCode)}</span>
                    <span class="text-muted small" style="margin-left: 6px;">(Stock: <strong>${stockQty}</strong> ${unit})</span>
                `);

                $('#btnMatrixAdd').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Add Variant');
            } else {
                currentMatchedVariant = null;
                let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
                let baseRotPrice = product.wholesale_price || 0;
                let defaultSkyPCode = (window.encodeToPCode ? window.encodeToPCode(basePrice) : '') || product.p_code;
                let defaultRotPCode = (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '') || product.rot_p_code;
                $('#matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
                $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
                $('#matrix_item_pcode').text(defaultSkyPCode || '---');
                $('#matrix_item_rot_pcode').text(defaultRotPCode || '---');
                $('#matrix_serial_badge_container').addClass('d-none');
                $('#matrix_location_badge_wrap').addClass('d-none');
                $('#matrix_location_text').text('---');
                $('#matrixSelectedVariantInfo').html('<span class="text-warning small"><i class="fas fa-exclamation-triangle me-1"></i> Combination not available in stock</span>');
                $('#btnMatrixAdd').prop('disabled', true).text('Add Variant');
            }
        } else {
            currentMatchedVariant = null;
            let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
            let baseRotPrice = product.wholesale_price || 0;
            let defaultSkyPCode = (window.encodeToPCode ? window.encodeToPCode(basePrice) : '') || product.p_code;
            let defaultRotPCode = (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '') || product.rot_p_code;
            $('#matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
            $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
            $('#matrix_item_pcode').text(defaultSkyPCode || '---');
            $('#matrix_item_rot_pcode').text(defaultRotPCode || '---');
            $('#matrix_serial_badge_container').addClass('d-none');
            $('#matrix_location_badge_wrap').addClass('d-none');
            $('#matrix_location_text').text('---');
            const remainingCount = attrKeys.filter(k => !currentSelectedAttrs[k]).length;
            $('#matrixSelectedVariantInfo').html(`<span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select remaining ${remainingCount} attribute(s) to view exact variant</span>`);
            $('#btnMatrixAdd').prop('disabled', true).text('Add Variant');
        }
    }

    // Add button handler
    $(document).on('click', '#btnMatrixAdd', function() {
        if (!currentMatchedVariant && (!currentLoadedVariants || currentLoadedVariants.length > 0)) return;

        const vName = currentMatchedVariant ? (currentMatchedVariant.name || currentLoadedProduct.item_name) : currentLoadedProduct.item_name;
        const vSerial = currentMatchedVariant ? (currentMatchedVariant.serial_no || 'N/A') : 'N/A';
        const vStock = currentMatchedVariant ? currentMatchedVariant.stock : (currentLoadedProduct.calculated_total_stock_qty ?? 0);
        const vUnit = currentMatchedVariant ? (currentMatchedVariant.unit || 'Units') : 'Units';

        Swal.fire({
            title: 'Variant Selected!',
            html: `
                <div class="text-start p-2">
                    <div class="fw-bold fs-6 text-dark mb-1">${vName}</div>
                    <div class="text-muted small mb-2 font-monospace">Serial No: <strong>${vSerial}</strong></div>
                    <div class="badge bg-success-subtle text-success border border-success-subtle mb-3">Free To Use: ${vStock} ${vUnit}</div>
                    <p class="small text-secondary mb-0">What would you like to do?</p>
                </div>
            `,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-cash-register me-1"></i> Open in Sales Invoice',
            confirmButtonColor: '#2563eb',
            cancelButtonText: 'Stay on Page'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('sale.add') }}";
            }
        });
    });

    // Render Table View Helper
    function renderTableView(product, variants, colorList) {
        let tbody = $('#variantTableBody');
        tbody.empty();

        let sizeStr = '-';
        if (product.size_mode === 'by_size')
            sizeStr = (product.height || 0) + ' x ' + (product.width || 0) + ' cm';

        let stock     = product.calculated_total_stock_qty ?? 0;
        let alertDef  = product.alert_carton_quantity != null ? product.alert_carton_quantity + '' : '-';
        let salePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
        let purchPrice= product.size_mode === 'by_size' ? product.purchase_price_per_m2 : (product.purchase_price_per_piece || 0);
        let priceLabel= product.size_mode === 'by_size' ? '/m²' : '/pc';

        function stockBadgeHtml(qty, alert) {
            let isLow = qty > 0 && alert != null && qty <= alert;
            let cls   = qty == 0 ? 'background:#fef3c7;color:#b45309;border:1px solid #fde68a;' : (isLow ? 'background:#fef2f2;color:#dc2626;border:1px solid #fecaca;' : 'background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;');
            return `<span style="${cls} border-radius:4px; padding:1px 6px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:3px;"><i class="fas fa-cubes" style="font-size:9.5px;"></i> ${qty}</span>`;
        }

        if (variants.length > 0) {
            variants.forEach(v => {
                let vName     = v.name || v.variant_name || product.item_name;
                let vSize     = v.size || v.variant_size || '-';
                let vColorVal = v.color || v.variant_color || '-';
                let vStock    = (v.stock !== undefined && v.stock !== null && v.stock !== '') ? v.stock : (v.variant_stock ?? 0);
                let vSale     = (v.sale_price !== undefined && v.sale_price !== null && v.sale_price !== '') ? v.sale_price : (v.variant_sale_price ?? 0);
                let vPurch    = (v.purch_price !== undefined && v.purch_price !== null && v.purch_price !== '') ? v.purch_price : (v.purchase_price ?? v.variant_purchase_price ?? 0);
                let vAlert    = (v.alert !== undefined && v.alert !== null && v.alert !== '') ? v.alert : (v.variant_alert_qty ?? 0);
                let vBarcode  = v.barcode || v.variant_barcode || (product.barcode_path ?? product.item_code);
                let vUnit     = v.unit || v.variant_unit || (product.unit ? product.unit.name : 'Pcs');

                let colorBadge = (vColorVal && vColorVal !== '-') ? `<span class="badge font-monospace" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-size:10.5px; padding:1px 5px; border-radius:3px;">${escapeHtml(vColorVal)}</span>` : '<span class="text-muted">—</span>';
                let sizeBadge  = (vSize && vSize !== '-') ? `<span class="badge font-monospace" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-size:10.5px; padding:1px 5px; border-radius:3px;">${escapeHtml(vSize)}</span>` : '<span class="text-muted">—</span>';
                let alertQty  = (vAlert != null && vAlert != 0) ? vAlert : '-';
                
                if (product.size_mode === 'by_kg' && v.conv_factor != 1 && !v.unit) vUnit = 'Pcs';
                let vPriceLabel = product.size_mode === 'by_size' ? '/m²' : '/' + vUnit;

                let vWholesale = (v.wholesale_price !== undefined && v.wholesale_price !== null && v.wholesale_price !== '') ? v.wholesale_price : 0;
                let vSkyPCode = window.encodeToPCode ? window.encodeToPCode(vSale) : (v.sky_p_code || v.p_code || '—');
                let vRotPCode = window.encodeToPCode ? window.encodeToPCode(vWholesale) : (v.rot_p_code || '—');

                tbody.append(`<tr>
                    <td class="text-start ps-3 fw-bold text-dark" style="font-size:12px;">${escapeHtml(vName)}</td>
                    <td class="text-center">${sizeBadge}</td>
                    <td class="text-center">${colorBadge}</td>
                    <td class="text-center">${stockBadgeHtml(vStock, vAlert)}</td>
                    <td class="text-end fw-bold" style="color:#059669; font-size:12px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(vSale||0).toFixed(2)}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size:11px;">${escapeHtml(vSkyPCode || '—')}</span></td>
                    <td class="text-end text-muted" style="font-size:11.5px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(vPurch||0).toFixed(2)}</td>
                    <td class="text-end fw-bold" style="color:#0284c7; font-size:12px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(vWholesale||0).toFixed(2)}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size:11px;">${escapeHtml(vRotPCode || '—')}</span></td>
                    <td class="text-end pe-3 font-monospace" style="font-size:11px; color:#475569;">${vBarcode ? `<span class="badge font-monospace" style="background:#f8fafc; color:#334155; border:1px solid #cbd5e1; font-size:11px; padding:2px 6px; border-radius:4px;">${escapeHtml(vBarcode)}</span>` : '—'}</td>
                </tr>`);
            });
        } else {
            colorList.forEach((color, index) => {
                let barcode   = (product.barcode_path ?? product.item_code ?? '') + (index > 0 ? '-' + String(index+1).padStart(2,'0') : '');
                let colorBadge = (color && color !== '-') ? `<span class="badge font-monospace" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-size:10.5px; padding:1px 5px; border-radius:3px;">${escapeHtml(color)}</span>` : '<span class="text-muted">—</span>';
                let prodSkyPCode = window.encodeToPCode ? window.encodeToPCode(salePrice) : (product.p_code || '—');
                let prodRotPrice = product.wholesale_price || 0;
                let prodRotPCode = window.encodeToPCode ? window.encodeToPCode(prodRotPrice) : (product.rot_p_code || '—');
                tbody.append(`<tr>
                    <td class="text-start ps-3 fw-bold text-dark" style="font-size:12px;">${escapeHtml(product.item_name)}</td>
                    <td class="text-center"><span class="badge font-monospace" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-size:10.5px; padding:1px 5px; border-radius:3px;">${escapeHtml(sizeStr)}</span></td>
                    <td class="text-center">${colorBadge}</td>
                    <td class="text-center">${stockBadgeHtml(stock, product.alert_carton_quantity)}</td>
                    <td class="text-end fw-bold" style="color:#059669; font-size:12px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(salePrice||0).toFixed(2)}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size:11px;">${escapeHtml(prodSkyPCode || '—')}</span></td>
                    <td class="text-end text-muted" style="font-size:11.5px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(purchPrice||0).toFixed(2)}</td>
                    <td class="text-end fw-bold" style="color:#0284c7; font-size:12px; font-variant-numeric:tabular-nums;">Rs. ${parseFloat(prodRotPrice||0).toFixed(2)}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border px-1.5 py-0.5 font-monospace fw-bold" style="font-size:11px;">${escapeHtml(prodRotPCode || '—')}</span></td>
                    <td class="text-end pe-3 font-monospace" style="font-size:11px; color:#475569;">${barcode ? `<span class="badge font-monospace" style="background:#f8fafc; color:#334155; border:1px solid #cbd5e1; font-size:11px; padding:2px 6px; border-radius:4px;">${escapeHtml(barcode)}</span>` : '—'}</td>
                </tr>`);
            });
        }
    }

    // ── Toggle Active ──
    $(document).on('click', '.toggle-active-btn', function () {
        const btn        = $(this);
        const productId  = btn.data('id');
        const isActive   = btn.data('active') == '1';
        const name       = btn.data('name');
        const actionText = isActive ? 'Deactivate' : 'Activate';

        Swal.fire({
            title: actionText + ' Product?',
            html:  `<b>${name}</b><br><small class="text-muted">${isActive ? 'Product will be hidden from Sale/Purchase forms.' : 'Product will be visible in Sale/Purchase forms.'}</small>`,
            icon:  isActive ? 'warning' : 'success',
            showCancelButton:    true,
            confirmButtonText:   'Yes, ' + actionText,
            confirmButtonColor:  isActive ? '#dc2626' : '#059669',
            cancelButtonText:    'Cancel',
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url:  `/product/${productId}/toggle-active`,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (!res.success) return;
                    const row    = $(`#product-row-${productId}`);
                    const card   = $(`#pmcard-${productId}`);
                    const badge  = $(`#status-badge-${productId}`);
                    const mbadge = $(`#mstatus-badge-${productId}`);
                    if (res.is_active) {
                        row.removeClass('row-inactive');
                        card.removeClass('row-inactive');
                        badge.attr('class', 'status-active').text('Active');
                        mbadge.attr('class', 'status-active').text('Active');
                        btn.removeClass('btn-act-act').addClass('btn-act-deact')
                           .attr('title','Deactivate').html('<i class="fas fa-ban"></i>')
                           .data('active','1');
                    } else {
                        row.addClass('row-inactive');
                        card.addClass('row-inactive');
                        badge.attr('class', 'status-inactive').text('Inactive');
                        mbadge.attr('class', 'status-inactive').text('Inactive');
                        btn.removeClass('btn-act-deact').addClass('btn-act-act')
                           .attr('title','Activate').html('<i class="fas fa-check"></i>')
                           .data('active','0');
                    }
                    Swal.fire({ toast:true, position:'top-end', icon:'success', title:res.message, showConfirmButton:false, timer:2500, timerProgressBar:true });
                }
            });
        });
    });

    // ── Delete Product Handler ──
    $(document).on('click', '.delete-product-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('id');
        const productName = btn.data('name') || 'this product';

        Swal.fire({
            title: 'Delete Product?',
            html: `Are you sure you want to delete <b>"${productName}"</b>?<br><small class="text-muted">This product and its variants will be removed.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: `/product/delete/${productId}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (res) {
                    if (res.status === 'success' || res.success) {
                        $(`#product-row-${productId}`).fadeOut(300, function() { $(this).remove(); });
                        $(`#pmcard-${productId}`).fadeOut(300, function() { $(this).remove(); });
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message || 'Product deleted successfully.',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Could not delete product.', 'error');
                    }
                },
                error: function (xhr) {
                    let msg = 'Could not delete product.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });

    // ── Subcategory fetch helpers ──
    $('#categorySelect').change(function() {
        var id = $(this).val();
        $('#subCategorySelect').html('<option value="">Loading...</option>');
        if (id) {
            $.get("/get-subcategories/" + id, { category_id: id }, function(data) {
                $('#subCategorySelect').html('<option value="">Select Sub-Category</option>');
                $.each(data, function(k, sub) {
                    $('#subCategorySelect').append('<option value="' + sub.id + '">' + sub.name + '</option>');
                });
            }).fail(() => alert('Error fetching subcategories.'));
        }
    });

    // ══════════════════════════════════════════════════════════════
    // 38mm x 26mm PRODUCT & VARIANT LABEL PRINTING SYSTEM
    // ══════════════════════════════════════════════════════════════
    let currentLabelProduct = null;
    let currentLabelVariants = [];

    // Click handler on table rows / mobile cards
    $(document).on('click', '.openLabelPrintModalBtn', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');
        openProductLabelModal(productId);
    });

    // Click handler from Matrix View modal
    $(document).on('click', '#btnMatrixPrintLabel', function(e) {
        e.preventDefault();
        if (!currentLoadedProduct) return;
        
        let targetVariantIdx = null;
        if (currentMatchedVariant && currentLoadedVariants && currentLoadedVariants.length > 0) {
            targetVariantIdx = currentLoadedVariants.findIndex(v => v === currentMatchedVariant || (v.serial_no && v.serial_no === currentMatchedVariant.serial_no));
            if (targetVariantIdx < 0) targetVariantIdx = 0;
        }

        openProductLabelModal(currentLoadedProduct.id, targetVariantIdx);
    });

    function openProductLabelModal(productId, preselectedVariantIndex = null) {
        const modalEl = document.getElementById('productLabelModal');
        if (modalEl) {
            try {
                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    jQuery(modalEl).modal('show');
                } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                    const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(modalEl) : null;
                    (inst || new bootstrap.Modal(modalEl)).show();
                } else {
                    $('#productLabelModal').modal('show');
                }
            } catch(e) {
                try { $('#productLabelModal').modal('show'); } catch(err) {}
            }
        }

        $('#lblModalLoading').removeClass('d-none');
        $('#lblModalContent').addClass('d-none');

        $.ajax({
            url: "/productview/" + productId,
            type: "GET",
            success: function(product) {
                $('#lblModalLoading').addClass('d-none');
                $('#lblModalContent').removeClass('d-none');

                currentLabelProduct = product;

                // Setup Title & Subtitle
                const pCode = product.item_code || '';
                const pName = product.item_name || '';
                const catName = product.category_relation?.name || '';

                let subtitleHtml = '';
                if (pCode) {
                    subtitleHtml += `<span class="badge font-monospace" style="background: #0f172a; color: #ffffff; font-size: 11px; padding: 2px 7px; border-radius: 4px; font-weight: 700; margin-right: 6px;">${escapeHtml(pCode)}</span>`;
                }
                if (pName) {
                    subtitleHtml += `<span class="fw-bold" style="font-size: 12.5px; color: #334155; margin-right: 6px;">${escapeHtml(pName)}</span>`;
                }
                if (catName) {
                    subtitleHtml += `<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><i class="fas fa-tag" style="font-size: 9px; margin-right: 4px;"></i>${escapeHtml(catName)}</span>`;
                }
                $('#lbl_modal_product_subtitle').html(subtitleHtml || '<span class="text-muted small">No details</span>');

                // Parse variants
                let variants = [];
                if (product.color) {
                    let parsed = product.color;
                    if (typeof parsed === 'string') {
                        try { parsed = JSON.parse(parsed); } catch (e) {}
                    }
                    if (typeof parsed === 'string') {
                        try { parsed = JSON.parse(parsed); } catch (e) {}
                    }
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        if (typeof parsed[0] === 'object' && parsed[0] !== null) {
                            variants = parsed;
                        }
                    }
                }
                currentLabelVariants = variants;

                renderLabelModalVariants(product, variants, preselectedVariantIndex);
            },
            error: function() {
                $('#lblModalLoading').addClass('d-none');
                Swal.fire('Error', 'Could not load product details for label printing.', 'error');
            }
        });
    }

    function renderLabelModalVariants(product, variants, preselectedVariantIndex = null) {
        const $tbody = $('#lblVariantsTableBody');
        $tbody.empty();

        if (variants && variants.length > 0) {
            variants.forEach((v, idx) => {
                const isSelected = (preselectedVariantIndex === null || preselectedVariantIndex === undefined) ? true : (idx === preselectedVariantIndex);
                const copies = isSelected ? 1 : 0;

                const salePrice = v.sale_price !== undefined ? v.sale_price : (product.sale_price_per_piece || 0);
                const rotPrice = v.wholesale_price !== undefined ? v.wholesale_price : (product.wholesale_price || 0);

                const skyPCode = (window.encodeToPCode && salePrice) ? window.encodeToPCode(salePrice) : (v.sky_p_code || v.p_code || '---');
                const rotPCode = (window.encodeToPCode && rotPrice !== undefined) ? window.encodeToPCode(rotPrice) : (v.rot_p_code || '---');

                const location = v.location ? v.location.trim() : (v.rack_shelf ? v.rack_shelf.trim() : (product.remarks ? product.remarks.trim() : ''));
                const vItemNum = (v.item_number && String(v.item_number).trim()) || (v.item_no && String(v.item_no).trim()) || (product.item_number && String(product.item_number).trim()) || (v.serial_no && String(v.serial_no).trim()) || '';
                const displayBadgeNum = vItemNum ? (String(vItemNum).startsWith('#') ? String(vItemNum) : '#' + String(vItemNum)) : '';

                let vName = v.name || v.variant_name || '';
                let cleanPart = '';
                if (vName) {
                    let m = vName.match(/\((.*?)\)/);
                    if (m && m[1]) cleanPart = m[1];
                    else cleanPart = vName.replace(product.item_name, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
                }
                if (!cleanPart) {
                    let parts = [];
                    if (v.size && v.size !== '-') parts.push(v.size);
                    if (v.color && v.color !== '-') parts.push(v.color);
                    cleanPart = parts.join(' / ');
                }
                const displayVarTitle = cleanPart || `Variant #${idx + 1}`;

                const rowHtml = `
                    <tr class="lbl-var-row ${idx === (preselectedVariantIndex || 0) ? 'table-active' : ''}" data-idx="${idx}" style="cursor: pointer;">
                        <td class="text-center align-middle" style="width: 34px;">
                            <input type="checkbox" class="lbl-var-check" data-idx="${idx}" ${isSelected ? 'checked' : ''} style="accent-color: #2563eb; cursor: pointer; width: 14px; height: 14px;">
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center flex-wrap" style="line-height: 1.25;">
                                <span class="fw-bold text-dark" style="font-size: 12.5px;">${escapeHtml(displayVarTitle)}</span>
                                ${displayBadgeNum ? `<span class="badge bg-primary text-white font-monospace" style="font-size: 10px; margin-left: 6px; padding: 1.5px 5.5px; border-radius: 4px; font-weight: 700;">${escapeHtml(displayBadgeNum)}</span>` : ''}
                            </div>
                        </td>
                        <td class="text-center align-middle font-monospace" style="font-size: 11px; white-space: nowrap;">
                            <span class="badge" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 2px 6px; font-size: 11px; font-weight: 700;">${escapeHtml(skyPCode)}</span>
                            <span class="text-muted" style="font-size: 10px; margin: 0 2px;">/</span>
                            <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 6px; font-size: 11px; font-weight: 700;">${escapeHtml(rotPCode)}</span>
                        </td>
                        <td class="text-center align-middle">
                            ${location ? `<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-family: monospace; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><i class="fas fa-map-marker-alt text-muted" style="margin-right: 3px; font-size: 9.5px;"></i>${escapeHtml(location)}</span>` : '<span class="text-muted" style="font-size: 11px;">—</span>'}
                        </td>
                        <td class="text-center align-middle">
                            <input type="number" class="form-control form-control-sm text-center py-0 lbl-var-copies fw-bold" data-idx="${idx}" min="0" max="100" value="${copies}" style="height: 26px; font-size: 12px; width: 60px; margin: 0 auto; border-radius: 5px; border-color: #cbd5e1; color: #2563eb;">
                        </td>
                    </tr>
                `;
                $tbody.append(rowHtml);
            });
        } else {
            // Master Product single item
            const basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
            const baseRotPrice = product.wholesale_price || 0;
            const skyPCode = (window.encodeToPCode && basePrice) ? window.encodeToPCode(basePrice) : (product.p_code || '---');
            const rotPCode = (window.encodeToPCode && baseRotPrice !== undefined) ? window.encodeToPCode(baseRotPrice) : (product.rot_p_code || '---');
            const location = product.remarks ? product.remarks.trim() : '';

            const rowHtml = `
                <tr class="lbl-var-row table-active" data-idx="master" style="cursor: pointer;">
                    <td class="text-center align-middle" style="width: 34px;">
                        <input type="checkbox" class="lbl-var-check" data-idx="master" checked style="accent-color: #2563eb; cursor: pointer; width: 14px; height: 14px;">
                    </td>
                    <td class="align-middle">
                        <div class="fw-bold text-dark" style="font-size: 12.5px;">${escapeHtml(product.item_name)}</div>
                        <small class="badge font-monospace mt-0.5" style="background: #0f172a; color: #fff; font-size: 10px; padding: 1.5px 5px; border-radius: 3px;">${escapeHtml(product.item_code || '')}</small>
                    </td>
                    <td class="text-center align-middle font-monospace" style="font-size: 11px; white-space: nowrap;">
                        <span class="badge" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 2px 6px; font-size: 11px; font-weight: 700;">${escapeHtml(skyPCode)}</span>
                        <span class="text-muted" style="font-size: 10px; margin: 0 2px;">/</span>
                        <span class="badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 6px; font-size: 11px; font-weight: 700;">${escapeHtml(rotPCode)}</span>
                    </td>
                    <td class="text-center align-middle">
                        ${location ? `<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-family: monospace; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><i class="fas fa-map-marker-alt text-muted" style="margin-right: 3px; font-size: 9.5px;"></i>${escapeHtml(location)}</span>` : '<span class="text-muted" style="font-size: 11px;">—</span>'}
                    </td>
                    <td class="text-center align-middle">
                        <input type="number" class="form-control form-control-sm text-center py-0 lbl-var-copies fw-bold" data-idx="master" min="1" max="100" value="1" style="height: 26px; font-size: 12px; width: 60px; margin: 0 auto; border-radius: 5px; border-color: #cbd5e1; color: #2563eb;">
                    </td>
                </tr>
            `;
            $tbody.append(rowHtml);
        }

        updateLabelSelectionCount();
        const initialIdx = (preselectedVariantIndex !== null && preselectedVariantIndex !== undefined) ? preselectedVariantIndex : (variants.length > 0 ? 0 : 'master');
        updateLiveLabelPreview(initialIdx);
    }

    function updateLabelSelectionCount() {
        const checkedCount = $('.lbl-var-check:checked').length;
        $('#lblSelectedCountText').text(checkedCount);
        $('#lblMasterCheck').prop('checked', checkedCount > 0 && checkedCount === $('.lbl-var-check').length);
        $('#lblBtnPrintNow').prop('disabled', checkedCount === 0);
    }

    function updateLiveLabelPreview(variantIdx) {
        if (!currentLabelProduct) return;

        let pName = currentLabelProduct.item_name || 'PRODUCT';
        let vName = '';
        let skyCode = '---';
        let rotCode = '---';
        let location = '';
        let itemNum = '';
        let barcodeVal = currentLabelProduct.barcode_path || currentLabelProduct.item_code || '';

        if (variantIdx !== 'master' && currentLabelVariants && currentLabelVariants[variantIdx]) {
            const v = currentLabelVariants[variantIdx];
            const salePrice = v.sale_price !== undefined ? v.sale_price : (currentLabelProduct.sale_price_per_piece || 0);
            const rotPrice = v.wholesale_price !== undefined ? v.wholesale_price : (currentLabelProduct.wholesale_price || 0);

            skyCode = (window.encodeToPCode && salePrice) ? window.encodeToPCode(salePrice) : (v.sky_p_code || v.p_code || '---');
            rotCode = (window.encodeToPCode && rotPrice !== undefined) ? window.encodeToPCode(rotPrice) : (v.rot_p_code || '---');
            location = v.location ? v.location.trim() : (v.rack_shelf ? v.rack_shelf.trim() : (currentLabelProduct.remarks ? currentLabelProduct.remarks.trim() : ''));
            itemNum = (v.item_number && String(v.item_number).trim()) || (v.item_no && String(v.item_no).trim()) || (currentLabelProduct.item_number && String(currentLabelProduct.item_number).trim()) || (v.serial_no && String(v.serial_no).trim()) || '';
            barcodeVal = v.barcode || itemNum || currentLabelProduct.barcode_path || currentLabelProduct.item_code;

            let origName = v.name || v.variant_name || '';
            let cleanPart = '';
            if (origName) {
                let m = origName.match(/\((.*?)\)/);
                if (m && m[1]) cleanPart = m[1];
                else cleanPart = origName.replace(pName, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
            }
            if (!cleanPart) {
                let parts = [];
                if (v.size && v.size !== '-') parts.push(v.size);
                if (v.color && v.color !== '-') parts.push(v.color);
                cleanPart = parts.join(' / ');
            }
            if (cleanPart) {
                cleanPart = cleanPart.replace(/\bstandard\b\s*[\/\-]?\s*/gi, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
            }
            vName = cleanPart;
        } else {
            const basePrice = currentLabelProduct.size_mode === 'by_size' ? currentLabelProduct.price_per_m2 : (currentLabelProduct.sale_price_per_piece || currentLabelProduct.sale_price_per_box || 0);
            const baseRotPrice = currentLabelProduct.wholesale_price || 0;
            skyCode = (window.encodeToPCode && basePrice) ? window.encodeToPCode(basePrice) : (currentLabelProduct.p_code || '---');
            rotCode = (window.encodeToPCode && baseRotPrice !== undefined) ? window.encodeToPCode(baseRotPrice) : (currentLabelProduct.rot_p_code || '---');
            location = currentLabelProduct.remarks ? currentLabelProduct.remarks.trim() : '';
            itemNum = (currentLabelProduct.item_number && String(currentLabelProduct.item_number).trim()) || currentLabelProduct.model || currentLabelProduct.item_code || '';
            vName = '';
        }

        // Extract clean variant specification
        let cleanPart = '';
        if (vName) {
            let m = vName.match(/\((.*?)\)/);
            if (m && m[1]) cleanPart = m[1];
            else cleanPart = vName.replace(pName, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
            cleanPart = cleanPart.replace(/\bstandard\b\s*[\/\-]?\s*/gi, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
        }

        // Serial / Code
        const codeOrSerial = itemNum || currentLabelProduct.model || currentLabelProduct.item_code || '';
        const displayItemNum = itemNum ? (String(itemNum).startsWith('#') ? String(itemNum) : '#' + String(itemNum)) : '—';

        // Line 1: Product Name & Variant Name
        let line1 = pName;
        if (cleanPart) {
            line1 = `${pName} - ${cleanPart}`;
        } else if (vName && vName !== pName) {
            let cleanV = vName.replace(/\bstandard\b\s*[\/\-]?\s*/gi, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
            line1 = cleanV ? `${pName} - ${cleanV}` : pName;
        }

        // Line 2: SKY - ROOT CODE (Format: sky-root, e.g. A.DB-A.DB)
        let line2 = '---';
        const hasSky = skyCode && skyCode !== '---';
        const hasRot = rotCode && rotCode !== '---';
        if (hasSky && hasRot) {
            if (skyCode.indexOf('-') !== -1 && (!rotCode || rotCode === skyCode)) {
                line2 = skyCode;
            } else {
                line2 = `${skyCode}-${rotCode}`;
            }
        } else if (hasSky) {
            line2 = skyCode;
        } else if (hasRot) {
            line2 = rotCode;
        }

        // Line 3: LOCATION
        const line3 = location || '—';

        // 3-Line Sticker: Line 1 = Unique Variant Item Number (e.g. #20226 / #20233), Line 2 = P-Code, Line 3 = Location
        $('#lblPrevSerial').text(displayItemNum);
        $('#lblPrevLine2').text(line2);
        $('#lblPrevLine3').text(line3);

        // Update Open Print Sheet href
        const printUrl = buildPrintUrl(currentLabelProduct.id, false);
        $('#lblBtnOpenPrintPage').attr('href', printUrl);
    }

    // Helper to build print URL with copies, layout, grouping and selected variants
    function buildPrintUrl(productId, autoPrint = false, groupBy = null) {
        let params = new URLSearchParams();
        if (autoPrint) params.set('autoprint', '1');

        const layout = $('input[name="lblPrintLayout"]:checked').val() || 'thermal';
        params.set('layout', layout);

        const groupChoice = groupBy || $('input[name="lblPrintGrouping"]:checked').val() || 'individual';
        params.set('group_by', groupChoice);

        let variantCopiesObj = {};

        $('.lbl-var-check:checked').each(function() {
            const idx = $(this).data('idx');
            const copies = parseInt($(`.lbl-var-copies[data-idx="${idx}"]`).val()) || 1;
            if (copies > 0) {
                variantCopiesObj[idx] = copies;
            }
        });

        if (Object.keys(variantCopiesObj).length > 0) {
            params.set('variant_copies', JSON.stringify(variantCopiesObj));
        }

        return `/products/${productId}/labels?` + params.toString();
    }

    // Check if multiple selected variants share the same rack/shelf and prompt user
    function checkSharedLocationsAndConfirm(callback) {
        if (!currentLabelProduct) return;

        const currentChoice = $('input[name="lblPrintGrouping"]:checked').val();
        if (currentChoice === 'location') {
            callback('location');
            return;
        }

        let locMap = {};
        $('.lbl-var-check:checked').each(function() {
            const idx = $(this).data('idx');
            const copies = parseInt($(`.lbl-var-copies[data-idx="${idx}"]`).val()) || 1;
            if (copies <= 0) return;

            let loc = '';
            let vTitle = '';
            if (idx !== 'master' && currentLabelVariants && currentLabelVariants[idx]) {
                const v = currentLabelVariants[idx];
                loc = (v.location || v.rack_shelf || currentLabelProduct.remarks || '').trim();
                let vName = v.name || v.variant_name || '';
                let cleanPart = '';
                if (vName) {
                    let m = vName.match(/\((.*?)\)/);
                    if (m && m[1]) cleanPart = m[1];
                    else cleanPart = vName.replace(currentLabelProduct.item_name, '').replace(/^[\s\-\/\(\)]+|[\s\-\/\(\)]+$/g, '');
                }
                if (!cleanPart) {
                    let parts = [];
                    if (v.size && v.size !== '-') parts.push(v.size);
                    if (v.color && v.color !== '-') parts.push(v.color);
                    cleanPart = parts.join('/');
                }
                vTitle = cleanPart || `Var #${idx + 1}`;
            } else {
                loc = (currentLabelProduct.remarks || '').trim();
                vTitle = currentLabelProduct.item_name;
            }

            if (loc && loc !== '—') {
                if (!locMap[loc]) locMap[loc] = [];
                locMap[loc].push(vTitle);
            }
        });

        let sharedList = [];
        for (let l in locMap) {
            if (locMap[l].length > 1) {
                sharedList.push({ loc: l, items: locMap[l] });
            }
        }

        if (sharedList.length > 0) {
            let sharedHtml = sharedList.map(s => `
                <div style="margin-bottom: 5px; padding: 6px 10px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <strong style="color: #2563eb;"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(s.loc)}:</strong>
                    <span style="color: #475569; font-size: 11.5px; margin-left: 4px;">${escapeHtml(s.items.join(', '))}</span>
                </div>
            `).join('');

            Swal.fire({
                title: '<span style="font-size: 16.5px; font-weight: 800; color: #0f172a;">Multiple Variants in Same Rack / Shelf</span>',
                html: `
                    <div style="font-size: 12.5px; text-align: left; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 12px;">
                        ${sharedHtml}
                    </div>
                    <p style="font-size: 13px; color: #334155; line-height: 1.4; margin-bottom: 0;">
                        Ek se zyada variants ek hi rack/shelf par maujood hain.<br>
                        <strong>Do you want to print other variant/product names on a single rack label, or print separate labels for each variant?</strong>
                    </p>
                `,
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-layer-group me-1" style="margin-right: 4px;"></i> Combine on Shelf Label',
                denyButtonText: '<i class="fas fa-tags me-1" style="margin-right: 4px;"></i> Separate Variant Labels',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#059669',
                denyButtonColor: '#2563eb',
            }).then((res) => {
                if (res.isConfirmed) {
                    $('#groupLocation').prop('checked', true);
                    callback('location');
                } else if (res.isDenied) {
                    $('#groupIndividual').prop('checked', true);
                    callback('individual');
                }
            });
        } else {
            callback(currentChoice || 'individual');
        }
    }

    $(document).on('change', 'input[name="lblPrintLayout"], input[name="lblPrintGrouping"]', function() {
        if (!currentLabelProduct) return;
        $('#lblBtnOpenPrintPage').attr('href', buildPrintUrl(currentLabelProduct.id, false));
    });

    // Row selection and table interactions
    $(document).on('click', '.lbl-var-row', function(e) {
        if ($(e.target).is('input')) return;
        $('.lbl-var-row').removeClass('table-active');
        $(this).addClass('table-active');
        const idx = $(this).data('idx');
        updateLiveLabelPreview(idx);
    });

    $(document).on('change', '.lbl-var-check', function() {
        const idx = $(this).data('idx');
        const isChecked = $(this).is(':checked');
        const $copiesInput = $(`.lbl-var-copies[data-idx="${idx}"]`);
        if (isChecked && parseInt($copiesInput.val()) <= 0) {
            $copiesInput.val(1);
        } else if (!isChecked) {
            $copiesInput.val(0);
        }
        updateLabelSelectionCount();
        updateLiveLabelPreview(idx);
    });

    $(document).on('input change', '.lbl-var-copies', function() {
        const idx = $(this).data('idx');
        const val = parseInt($(this).val()) || 0;
        $(`.lbl-var-check[data-idx="${idx}"]`).prop('checked', val > 0);
        updateLabelSelectionCount();
    });

    $('#lblMasterCheck').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.lbl-var-check').prop('checked', isChecked);
        $('.lbl-var-copies').each(function() {
            $(this).val(isChecked ? (parseInt($('#lblDefaultCopiesInput').val()) || 1) : 0);
        });
        updateLabelSelectionCount();
    });

    $('#lblSelectAllBtn').on('click', function() {
        $('.lbl-var-check').prop('checked', true);
        const defCopies = parseInt($('#lblDefaultCopiesInput').val()) || 1;
        $('.lbl-var-copies').val(defCopies);
        updateLabelSelectionCount();
    });

    $('#lblDeselectAllBtn').on('click', function() {
        $('.lbl-var-check').prop('checked', false);
        $('.lbl-var-copies').val(0);
        updateLabelSelectionCount();
    });

    $('#lblApplyAllCopiesBtn').on('click', function() {
        const count = Math.max(1, parseInt($('#lblDefaultCopiesInput').val()) || 1);
        $('.lbl-var-check:checked').each(function() {
            const idx = $(this).data('idx');
            $(`.lbl-var-copies[data-idx="${idx}"]`).val(count);
        });
    });

    // Open Print Page (links to print sheet in new tab)
    $(document).on('click', '#lblBtnOpenPrintPage', function(e) {
        e.preventDefault();
        if (!currentLabelProduct) return;

        checkSharedLocationsAndConfirm(function(chosenGrouping) {
            const printUrl = buildPrintUrl(currentLabelProduct.id, false, chosenGrouping);
            window.open(printUrl, '_blank');
        });
    });

    // Print Now button triggers popup window printing (eliminates iframe A4 scaling bugs)
    $(document).on('click', '#lblBtnPrintNow', function() {
        if (!currentLabelProduct) return;

        checkSharedLocationsAndConfirm(function(chosenGrouping) {
            const printUrl = buildPrintUrl(currentLabelProduct.id, true, chosenGrouping);
            const printWin = window.open(printUrl, 'LabelPrintPopup', 'width=460,height=600,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes');
            if (printWin) {
                printWin.focus();
            }
        });
    });

    // ══════════════════════════════════════════════════════════════
    // QUICKBOOKS POINT OF SALE DESKTOP 12.0 WORKFLOW CONTROLLER
    // ══════════════════════════════════════════════════════════════
    let qbActiveProductId = null;
    let qbCurrentProductData = null;
    let qbDetailsOpen = false;

    // Direct bulletproof toggle for "I Want to..." Green Button
    $(document).on('click', '#qbIWantToDropdownBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $menu = $(this).closest('.dropdown').find('.qb-iwantto-menu');
        $('.dropdown-menu').not($menu).removeClass('show');
        $menu.toggleClass('show');
    });

    // Direct bulletproof toggle for "More" Dropdown Button
    $(document).on('click', '#qbMoreActionsDropdown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $menu = $(this).closest('.dropdown').find('.dropdown-menu');
        $('.dropdown-menu').not($menu).removeClass('show');
        $menu.toggleClass('show');
    });

    // Close dropdowns on outside click or menu item click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.qb-iwantto-menu, #qbMoreActionsDropdown ~ .dropdown-menu').removeClass('show');
        }
    });

    $(document).on('click', '.qb-iwantto-menu .dropdown-item', function() {
        $('.qb-iwantto-menu').removeClass('show');
    });

    // 1. Table Row Selection (Single click highlights and loads into details panel)
    $('#productTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('input, button, a, label, .action-group').length) {
            return;
        }
        selectTableRow($(this));
    });

    // Double-click row opens Edit Product
    $('#productTable tbody').on('dblclick', 'tr', function(e) {
        if ($(e.target).closest('input, button, a').length) return;
        const prodId = $(this).find('.selectProduct').val() || $(this).attr('id')?.replace('product-row-', '');
        if (prodId) {
            window.location.href = `/products/${prodId}/edit`;
        }
    });

    function selectTableRow($row) {
        if (!$row || !$row.length) return;
        $('#productTable tbody tr').removeClass('row-qb-selected');
        $row.addClass('row-qb-selected');

        const prodId = $row.find('.selectProduct').val() || $row.attr('id')?.replace('product-row-', '');
        if (prodId) {
            qbActiveProductId = prodId;
            $('#qbEditSelectedBtn').prop('disabled', false).css({'opacity': '1', 'cursor': 'pointer'});

            $('.selectProduct').prop('checked', false);
            $row.find('.selectProduct').prop('checked', true);

            if (qbDetailsOpen) {
                loadProductIntoDetailsDrawer(prodId);
            }
        }
    }

    // 2. Toggle Show Details (F8)
    function toggleQbDetailsDrawer(forceOpen = null) {
        if (forceOpen !== null) {
            qbDetailsOpen = forceOpen;
        } else {
            qbDetailsOpen = !qbDetailsOpen;
        }

        const $drawer = $('#qbDetailsDrawer');
        if (qbDetailsOpen) {
            $drawer.addClass('open');
            $('#qbToggleDetailsBtn').addClass('active');
            $('#qbToggleDetailsLabel').text('Hide Details');
            $('#qbMenuDetailsText').text('Hide Details');

            if (!qbActiveProductId) {
                const $firstRow = $('#productTable tbody tr:first');
                if ($firstRow.length) {
                    selectTableRow($firstRow);
                }
            } else {
                loadProductIntoDetailsDrawer(qbActiveProductId);
            }
        } else {
            $drawer.removeClass('open');
            $('#qbToggleDetailsBtn').removeClass('active');
            $('#qbToggleDetailsLabel').text('Show Details');
            $('#qbMenuDetailsText').text('Show Details');
        }
    }

    $('#qbToggleDetailsBtn, #qbMenuToggleDetails, #qbCloseDetailsDrawer').on('click', function(e) {
        e.preventDefault();
        toggleQbDetailsDrawer();
    });

    // 3. Details Drawer Tabs
    $('.qb-drawer-tab-btn').on('click', function() {
        $('.qb-drawer-tab-btn').removeClass('active');
        $(this).addClass('active');
        const tab = $(this).data('tab');
        if (tab === 'style') {
            $('#qbTabContentStyle').show();
            $('#qbTabContentInventory').hide();
        } else {
            $('#qbTabContentStyle').hide();
            $('#qbTabContentInventory').show();
        }
    });

    // Collapsible sections inside drawer
    $(document).on('click', '.qb-section-head', function(e) {
        if ($(e.target).closest('#qbPriceSliderWrap, .qb-price-slider-wrap, #qbPriceSwitchBtn').length) {
            return;
        }
        $(this).closest('.qb-section-card').toggleClass('collapsed');
    });

    // Toggle Price Visibility (Slide ON Green / Slide OFF Red)
    let qbPricesVisible = false;
    window.toggleQbPriceSlider = function(e) {
        if (e) {
            try { e.preventDefault(); } catch(err){}
            try { e.stopPropagation(); } catch(err){}
        }

        qbPricesVisible = !qbPricesVisible;

        if (qbPricesVisible) {
            $('#qbPriceSliderWrap, .qb-price-slider-wrap').addClass('active');
            $('#qbPriceSwitchBtn').addClass('active');
            $('#qbPriceSliderStatus').text('PRICES: ON');
            $('.qb-price-row').css('display', 'flex').show();
            $('.qb-price-hero').css('display', 'inline-flex').show();
        } else {
            $('#qbPriceSliderWrap, .qb-price-slider-wrap').removeClass('active');
            $('#qbPriceSwitchBtn').removeClass('active');
            $('#qbPriceSliderStatus').text('PRICES: OFF');
            $('.qb-price-row').hide();
            $('.qb-price-hero').hide();
        }
    };

    $(document).on('click', '#qbPriceSliderWrap, .qb-price-slider-wrap', function(e) {
        toggleQbPriceSlider(e);
    });

    // 4. Load Product Data into Details Drawer
    function loadProductIntoDetailsDrawer(productId) {
        if (!productId) return;
        $('#qbDrawerItemTitle').text('Loading...');
        $('#qbHeroPrice').text('Rs. ...');
        $('#qbHeroStock').text('...');
        $('#qbDrawerMatrixWrap').html('<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div><p class="small text-muted mt-2 mb-0">Loading Style Matrix...</p></div>');

        $.ajax({
            url: '/productview/' + productId,
            type: 'GET',
            success: function(product) {
                qbCurrentProductData = product;

                $('#qbDrawerItemNum').text('#' + (product.item_code || product.id));
                $('#qbDrawerItemTitle').text(product.item_name || 'Item Details');
                $('#qbDrawerStyleName').text(product.item_name || '—');
                $('#qbDrawerStyleMeta').text(`${product.category_relation?.name || 'Uncategorized'} • ${product.brand?.name || 'No Brand'}`);

                if (product.image) {
                    const drawerImgSrc = (String(product.image).startsWith('http') || String(product.image).startsWith('/')) ? product.image : ('{{ asset("uploads/products") }}/' + product.image);
                    $('#qbDrawerImgWrap').html(`<img src="${drawerImgSrc}" style="width:100%; height:100%; object-fit:cover;">`);
                } else {
                    $('#qbDrawerImgWrap').html('<i class="fas fa-image text-muted fs-4"></i>');
                }

                $('#qbValType').text(product.size_mode === 'by_cartons' ? 'Cartons / Box Inventory' : (product.size_mode === 'by_size' ? 'Dimension Inventory' : 'Standard Inventory Part'));
                $('#qbValDepartment').text(product.category_relation?.name || '—');

                let regularPrice = (product.size_mode === 'by_size') ? (product.price_per_m2 || 0) : (product.sale_price_per_piece || product.sale_price_per_box || 0);
                let wholesalePrice = product.wholesale_price || 0;
                let costPrice = (product.size_mode === 'by_size') ? (product.purchase_price_per_m2 || 0) : (product.purchase_price_per_piece || product.purchase_price_per_box || 0);

                $('#qbValRegularPrice').text('Rs. ' + parseFloat(regularPrice).toFixed(2));
                $('#qbHeroPrice').text('Rs. ' + parseFloat(regularPrice).toFixed(2));
                const qbComb = window.formatCombinedPCode ? window.formatCombinedPCode(product.p_code, product.rot_p_code) : (product.p_code && product.rot_p_code ? `${product.p_code}-${product.rot_p_code}` : (product.p_code || product.rot_p_code || '---'));
                $('#qbValCombinedPCode').text(qbComb || '---');
                $('#qbValSkyPCode').text(product.p_code || '---');
                $('#qbValWholesalePrice').text('Rs. ' + parseFloat(wholesalePrice).toFixed(2));
                $('#qbValRotPCode').text(product.rot_p_code || '---');
                $('#qbValAvgCost').text('Rs. ' + parseFloat(costPrice).toFixed(2));

                let totalPieces = product.calculated_total_stock_qty || 0;
                $('#qbValOnHand').text(totalPieces + ' Pieces');
                $('#qbValAvailable').text(totalPieces + ' Pieces');
                $('#qbHeroStock').text(totalPieces + ' Pcs');

                $('#qbValVendor').text(product.brand?.name || '—');
                $('#qbValAlu').text(product.barcode_path || product.item_code || '—');
                $('#qbValLocation').text(product.remarks || '—');
                $('#qbValReorder').text((product.alert_carton_quantity || product.alert_quantity || '—') + ' min');

                $('#qbDrawerNotesMemo').val(product.remarks || '');

                $('#qbValWeight').text((product.weight_per_piece || 0) + ' kg');
                $('#qbValPiecesPerBox').text(product.pieces_per_box || 1);
                $('#qbValDimensions').text((product.height && product.width) ? `${product.height} x ${product.width} cm` : '—');

                let estProfit = Math.max(0, regularPrice - costPrice);
                let marginPct = regularPrice > 0 ? ((estProfit / regularPrice) * 100).toFixed(1) : '0.0';
                $('#qbValNetSales').text('Rs. ' + (totalPieces * regularPrice).toFixed(2));
                $('#qbValCostGoods').text('Rs. ' + (totalPieces * costPrice).toFixed(2));
                $('#qbValGrossProfit').text('Rs. ' + (totalPieces * estProfit).toFixed(2));
                $('#qbValProfitMargin').html(`<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5">${marginPct}%</span>`);
                $('#qbValQtyReceived').text(totalPieces);
                $('#qbValQtySold').text('0');
                $('#qbValQtyAdjusted').text('0');

                renderQbStyleMatrix(product);
                $('#qbBtnEditStyle').prop('disabled', false);

                if (qbPricesVisible) {
                    $('.qb-price-row').css('display', 'flex').show();
                    $('.qb-price-hero').css('display', 'inline-flex').show();
                } else {
                    $('.qb-price-row').hide();
                    $('.qb-price-hero').hide();
                }
            },
            error: function() {
                $('#qbDrawerMatrixWrap').html('<div class="text-danger small p-3 text-center">Failed to load product details.</div>');
            }
        });
    }

    // 5. Render 2D Style Matrix (Sizes on Y-axis, Attributes on X-axis)
    function renderQbStyleMatrix(product) {
        let variants = [];
        if (product.color) {
            let parsed = product.color;
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (Array.isArray(parsed) && parsed.length > 0 && typeof parsed[0] === 'object') {
                variants = parsed;
            }
        }

        if (!variants || variants.length === 0) {
            $('#qbDrawerMatrixWrap').html(`
                <div class="text-center py-3 px-2" style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px;">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-2" style="width:36px; height:36px; border:1px solid #e2e8f0;">
                        <i class="fas fa-box-open text-primary"></i>
                    </div>
                    <div class="fw-bold text-dark" style="font-size:12px;">Standard Single Item</div>
                    <div class="text-muted small mt-1" style="font-size:11px;">No color/size variant matrix defined.</div>
                    <div class="mt-2"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size:11px;">Stock: <strong>${product.calculated_total_stock_qty || 0}</strong> Pieces</span></div>
                </div>
            `);
            return;
        }

        let attributesSet = new Set();
        let sizesSet = new Set();
        let matrixMap = {};

        variants.forEach((v, idx) => {
            let attr = (v.color && v.color !== '-') ? String(v.color).trim() : 'Standard';
            let size = (v.size && v.size !== '-') ? String(v.size).trim() : 'Regular';
            attributesSet.add(attr);
            sizesSet.add(size);
            matrixMap[`${size}||${attr}`] = { variant: v, index: idx };
        });

        let attributes = Array.from(attributesSet);
        let sizes = Array.from(sizesSet);

        let html = '<table class="qb-matrix-table">';
        html += '<thead><tr>';
        html += '<th class="qb-corner-cell"><div class="matrix-corner-title"><span>SIZE</span><span class="matrix-slash">/</span><span>ATTR</span></div></th>';
        attributes.forEach(attr => {
            html += `<th><span class="matrix-attr-chip">${escapeHtml(attr)}</span></th>`;
        });
        html += '<th class="qb-total-cell"><span class="matrix-total-hdr">TOTAL</span></th>';
        html += '</tr></thead><tbody>';

        let colTotals = new Array(attributes.length).fill(0);
        let grandTotal = 0;

        sizes.forEach(size => {
            html += '<tr>';
            html += `<td class="qb-row-label"><span class="matrix-size-badge">${escapeHtml(size)}</span></td>`;
            let rowTotal = 0;

            attributes.forEach((attr, colIdx) => {
                let cellData = matrixMap[`${size}||${attr}`];
                let qty = cellData ? parseFloat(cellData.variant.stock || cellData.variant.current_stock || 0) : 0;
                rowTotal += qty;
                colTotals[colIdx] += qty;
                grandTotal += qty;

                if (qty > 0) {
                    html += `<td><span class="matrix-stock-chip in-stock" title="${escapeHtml(size)} • ${escapeHtml(attr)}: ${qty} Pieces">${qty}</span></td>`;
                } else {
                    html += `<td><span class="matrix-stock-chip zero-stock" title="${escapeHtml(size)} • ${escapeHtml(attr)}: 0 Pieces">0</span></td>`;
                }
            });

            html += `<td class="qb-total-cell"><span class="matrix-row-total">${rowTotal}</span></td>`;
            html += '</tr>';
        });

        html += '<tfoot><tr>';
        html += '<td class="qb-total-label"><i class="fas fa-layer-group me-1 text-primary"></i> Total</td>';
        colTotals.forEach(cTot => {
            html += `<td class="qb-col-total"><span class="matrix-col-total">${cTot}</span></td>`;
        });
        html += `<td class="qb-grand-total"><span class="matrix-grand-chip">${grandTotal}</span></td>`;
        html += '</tr></tfoot></table>';

        $('#qbDrawerMatrixWrap').html(html);
    }

    // 6. "Edit Style" Interactive Modal
    $('#qbBtnEditStyle, #qbMenuEditStyle').on('click', function() {
        if (!qbCurrentProductData) {
            alert('Please select a product first.');
            return;
        }
        openQbEditStyleModal(qbCurrentProductData);
    });

    function openQbEditStyleModal(product) {
        $('#qbEditStyleModalTitle').text(product.item_name || 'Product');
        $('#qbEditStyleStatus').html('');

        let variants = [];
        if (product.color) {
            let parsed = product.color;
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (Array.isArray(parsed) && parsed.length > 0 && typeof parsed[0] === 'object') {
                variants = parsed;
            }
        }

        let attributesSet = new Set();
        let sizesSet = new Set();
        let matrixMap = {};

        variants.forEach((v, idx) => {
            let attr = (v.color && v.color !== '-') ? String(v.color).trim() : 'Standard';
            let size = (v.size && v.size !== '-') ? String(v.size).trim() : 'Regular';
            attributesSet.add(attr);
            sizesSet.add(size);
            matrixMap[`${size}||${attr}`] = { variant: v, index: idx };
        });

        let attributes = Array.from(attributesSet);
        let sizes = Array.from(sizesSet);

        if (attributes.length === 0) {
            attributes = ['Standard'];
            sizes = ['Regular'];
            matrixMap['Regular||Standard'] = {
                variant: {
                    name: product.item_name,
                    color: 'Standard',
                    size: 'Regular',
                    stock: product.calculated_total_stock_qty || 0,
                    sale_price: product.sale_price_per_piece || product.price_per_m2 || 0,
                    wholesale_price: product.wholesale_price || 0
                },
                index: 0
            };
        }

        let html = '<table class="table table-bordered table-sm align-middle text-center qb-matrix-table mb-0" id="qbEditMatrixTable">';
        html += '<thead><tr>';
        html += '<th class="qb-corner-cell text-start">Size \\ Attribute</th>';
        attributes.forEach(attr => {
            html += `<th>${escapeHtml(attr)}</th>`;
        });
        html += '<th class="qb-total-cell">Total</th>';
        html += '</tr></thead><tbody>';

        let colTotals = new Array(attributes.length).fill(0);
        let grandTotal = 0;

        sizes.forEach((size, rIdx) => {
            html += `<tr data-row-idx="${rIdx}">`;
            html += `<td class="qb-row-label text-start fw-bold">${escapeHtml(size)}</td>`;
            let rowTotal = 0;

            attributes.forEach((attr, cIdx) => {
                let cellData = matrixMap[`${size}||${attr}`];
                let qty = cellData ? parseFloat(cellData.variant.stock || cellData.variant.current_stock || 0) : 0;
                let varIdx = cellData ? cellData.index : -1;
                rowTotal += qty;
                colTotals[cIdx] += qty;
                grandTotal += qty;

                html += `
                    <td>
                        <input type="number" step="any" min="0" 
                            class="qb-edit-matrix-input qb-matrix-cell-input" 
                            data-row="${rIdx}" 
                            data-col="${cIdx}" 
                            data-size="${escapeHtml(size)}" 
                            data-attr="${escapeHtml(attr)}" 
                            data-var-idx="${varIdx}" 
                            value="${qty}">
                    </td>
                `;
            });

            html += `<td class="qb-total-cell fw-bold qb-row-total-val" id="qbRowTotal_${rIdx}">${rowTotal}</td>`;
            html += '</tr>';
        });

        html += '<tfoot><tr>';
        html += '<td class="qb-total-cell text-start fw-bold">Total</td>';
        colTotals.forEach((cTot, cIdx) => {
            html += `<td class="qb-total-cell fw-bold qb-col-total-val" id="qbColTotal_${cIdx}">${cTot}</td>`;
        });
        html += `<td class="qb-grand-total fw-bold text-primary" id="qbGrandTotalVal">${grandTotal}</td>`;
        html += '</tr></tfoot></table>';

        $('#qbEditStyleMatrixContainer').html(html);
        $('#qbEditStyleModal').modal('show');
    }

    $(document).on('input', '.qb-matrix-cell-input', function() {
        let numCols = $('#qbEditMatrixTable thead th').length - 2;
        let numRows = $('#qbEditMatrixTable tbody tr').length;

        let colTotals = new Array(numCols).fill(0);
        let grandTotal = 0;

        for (let r = 0; r < numRows; r++) {
            let rowTotal = 0;
            for (let c = 0; c < numCols; c++) {
                let val = parseFloat($(`.qb-matrix-cell-input[data-row="${r}"][data-col="${c}"]`).val()) || 0;
                rowTotal += val;
                colTotals[c] += val;
                grandTotal += val;
            }
            $(`#qbRowTotal_${r}`).text(rowTotal);
        }

        colTotals.forEach((cTot, c) => {
            $(`#qbColTotal_${c}`).text(cTot);
        });
        $('#qbGrandTotalVal').text(grandTotal);
    });

    $('#qbBtnSaveStyleChanges').on('click', function() {
        if (!qbCurrentProductData) return;
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        let updatedVariants = [];
        $('.qb-matrix-cell-input').each(function() {
            let size = $(this).data('size');
            let attr = $(this).data('attr');
            let qty = parseFloat($(this).val()) || 0;
            let varIdx = parseInt($(this).data('var-idx'));

            let orig = (qbCurrentProductData.color && Array.isArray(qbCurrentProductData.color) && varIdx >= 0) ? qbCurrentProductData.color[varIdx] : {};

            updatedVariants.push({
                ...orig,
                name: orig.name || `${qbCurrentProductData.item_name} (${size} - ${attr})`,
                color: attr,
                size: size,
                stock: qty,
                current_stock: qty,
                sale_price: orig.sale_price || qbCurrentProductData.sale_price_per_piece || 0,
                wholesale_price: orig.wholesale_price || qbCurrentProductData.wholesale_price || 0,
                location: orig.location || qbCurrentProductData.remarks || ''
            });
        });

        $.ajax({
            url: `/products/${qbCurrentProductData.id}/update-style`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                variants: updatedVariants
            },
            success: function(resp) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                $('#qbEditStyleModal').modal('hide');

                const $targetRow = $(`#product-row-${qbCurrentProductData.id}`);
                if ($targetRow.length) {
                    $targetRow.find('.stock-badge').html(`<i class="fas fa-cubes" style="font-size:.65rem;"></i> ${resp.total_pieces} <span class="stock-unit">Pcs</span>`);
                }

                loadProductIntoDetailsDrawer(qbCurrentProductData.id);
                alert(resp.message || 'Style matrix updated successfully!');
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                alert('Error saving style matrix: ' + (xhr.responseJSON?.message || 'Server error.'));
            }
        });
    });

    // 7. "Physical Inventory & Enterprise Cycle Count" Engine
    let qbPiItems = [];
    let qbPiCurrentMode = 'product'; // 'product' or 'location'
    let qbPiSelectedDuration = 'auto'; // '1', '7', '30', '90', or 'auto'
    let qbPiLoadedVariantsCache = {}; // Cache: { [prodId]: [variants] }
    const qbPiStorageLocations = @json($storageLocationsList ?? []);

    $('#qbPhysicalInventoryBtn, #qbMenuPhysicalInventory, #qbMainBannerCountDueBtn').on('click', function(e) {
        e.preventDefault();
        const isFromDueBanner = $(this).is('#qbMainBannerCountDueBtn');
        openQbPhysicalInventoryModal(isFromDueBanner);
    });

    function closeQbPhysicalInventoryModal() {
        try { $('#qbPhysicalInventoryModal').modal('hide'); } catch(e) {}
        try {
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const el = document.getElementById('qbPhysicalInventoryModal');
                const inst = el && bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(el) : null;
                if (inst) inst.hide();
            }
        } catch(e) {}
        const m = document.getElementById('qbPhysicalInventoryModal');
        if (m) {
            m.classList.remove('show');
            m.style.display = 'none';
            m.setAttribute('aria-hidden', 'true');
        }
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    }

    $(document).on('click', '#qbPiTopCloseBtn, #qbPiCancelBtn, #qbPhysicalInventoryModal [data-dismiss="modal"], #qbPhysicalInventoryModal [data-bs-dismiss="modal"]', function(e) {
        e.preventDefault();
        closeQbPhysicalInventoryModal();
    });

    // ── Mode Switcher: By Product vs By Location (Top-Right Segmented Control) ──
    $(document).on('click', '.qb-pi-mode-btn', function(e) {
        e.preventDefault();
        const mode = $(this).data('mode');
        switchQbPiMode(mode);
    });

    function switchQbPiMode(mode) {
        qbPiCurrentMode = mode;
        if (mode === 'product') {
            $('#qbPiActiveWarehouseName').text('BY SELECTED PRODUCTS');
            syncSelectedProductsVariants();
        } else {
            const whVal = $('#qbPiWarehouseSelect').val();
            const whText = $('#qbPiWarehouseSelect option:selected').text();
            $('#qbPiActiveWarehouseName').text(whText.toUpperCase());
            updateQbPiRackShelfOptions(whVal);
            loadCycleCountByLocation(whVal, $('#qbPiRackShelfSelect').val());
        }
    }

    // ── Dependent Rack / Shelf Options Populator ──
    function updateQbPiRackShelfOptions(locVal) {
        const $rackSelect = $('#qbPiRackShelfSelect');
        const $rackLabel = $('#qbPiRackShelfLabel');
        $rackSelect.empty();

        locVal = String(locVal || 'all').trim();

        if (locVal.startsWith('wh_') || (!isNaN(locVal) && locVal !== 'all' && !locVal.startsWith('branch_'))) {
            const whId = parseInt(locVal.replace('wh_', ''));
            $rackLabel.html('<i class="fas fa-cubes text-muted me-1"></i> W. Rack');
            $rackSelect.append('<option value="all">📦 All Warehouse Racks</option>');

            const filteredRacks = qbPiStorageLocations.filter(item => {
                return (item.type === 'warehouse_rack' || !item.type) && parseInt(item.warehouse_id) === whId;
            });

            if (filteredRacks.length > 0) {
                filteredRacks.forEach(rack => {
                    const codeBadge = rack.code ? ` [${rack.code}]` : '';
                    $rackSelect.append(`<option value="${rack.id}">🗄️ ${escapeHtml(rack.name)}${codeBadge}</option>`);
                });
            } else {
                $rackSelect.append('<option value="none" disabled>(No racks registered for this warehouse)</option>');
            }
        } else if (locVal.startsWith('branch_') || locVal.startsWith('shop_')) {
            const branchId = parseInt(locVal.replace('branch_', '').replace('shop_', ''));
            $rackLabel.html('<i class="fas fa-layer-group text-muted me-1"></i> S. Shelf');
            $rackSelect.append('<option value="all">🏪 All Shop Shelves</option>');

            const filteredShelves = qbPiStorageLocations.filter(item => {
                return item.type === 'shop_shelf' && parseInt(item.branch_id) === branchId;
            });

            if (filteredShelves.length > 0) {
                filteredShelves.forEach(shelf => {
                    const codeBadge = shelf.code ? ` [${shelf.code}]` : '';
                    $rackSelect.append(`<option value="${shelf.id}">🗃️ ${escapeHtml(shelf.name)}${codeBadge}</option>`);
                });
            } else {
                $rackSelect.append('<option value="none" disabled>(No shelves registered for this shop)</option>');
            }
        } else {
            // "all" Warehouses & Shops
            $rackLabel.html('<i class="fas fa-boxes-stacked text-muted me-1"></i> W. Rack');
            $rackSelect.append('<option value="all">📦 All Racks &amp; Shelves</option>');

            const racks = qbPiStorageLocations.filter(item => item.type === 'warehouse_rack' || !item.type);
            const shelves = qbPiStorageLocations.filter(item => item.type === 'shop_shelf');

            if (racks.length > 0) {
                let $grp = $('<optgroup label="🏢 Warehouse Racks"></optgroup>');
                racks.forEach(r => {
                    const codeBadge = r.code ? ` [${r.code}]` : '';
                    $grp.append(`<option value="${r.id}">🗄️ ${escapeHtml(r.name)}${codeBadge}</option>`);
                });
                $rackSelect.append($grp);
            }
            if (shelves.length > 0) {
                let $grp = $('<optgroup label="🏪 Shop Shelves"></optgroup>');
                shelves.forEach(s => {
                    const codeBadge = s.code ? ` [${s.code}]` : '';
                    $grp.append(`<option value="${s.id}">🗃️ ${escapeHtml(s.name)}${codeBadge}</option>`);
                });
                $rackSelect.append($grp);
            }
        }

        $rackSelect.val('all');
    }

    // ── Warehouse / Location Selector Change ──
    $('#qbPiWarehouseSelect').on('change', function() {
        const whId = $(this).val();
        const whText = $('#qbPiWarehouseSelect option:selected').text();
        $('#qbPiActiveWarehouseName').text(whText.toUpperCase());
        
        // Update dependent Rack / Shelf options
        updateQbPiRackShelfOptions(whId);

        if (qbPiCurrentMode === 'location') {
            loadCycleCountByLocation(whId, $('#qbPiRackShelfSelect').val());
        }
    });

    // ── Rack / Shelf Selector Change ──
    $('#qbPiRackShelfSelect').on('change', function() {
        const rackShelfId = $(this).val();
        if (rackShelfId === 'none') return;
        const whId = $('#qbPiWarehouseSelect').val();
        if (qbPiCurrentMode === 'location') {
            loadCycleCountByLocation(whId, rackShelfId);
        }
    });

    // ── Reset Rack / Shelf Button ──
    $('#qbPiResetRackShelfBtn').on('click', function(e) {
        e.preventDefault();
        $('#qbPiRackShelfSelect').val('all');
        const whId = $('#qbPiWarehouseSelect').val();
        if (qbPiCurrentMode === 'location') {
            loadCycleCountByLocation(whId, 'all');
        }
    });

    $('#qbPiLoadWarehouseItemsBtn').on('click', function(e) {
        e.preventDefault();
        qbPiCurrentMode = 'location';
        const whText = $('#qbPiWarehouseSelect option:selected').text();
        $('#qbPiActiveWarehouseName').text(whText.toUpperCase());
        loadCycleCountByLocation($('#qbPiWarehouseSelect').val(), $('#qbPiRackShelfSelect').val());
    });

    function loadCycleCountByLocation(warehouseId, rackShelfId = null) {
        $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading inventory items for selected location...</td></tr>');

        const params = {};
        if (rackShelfId && rackShelfId !== 'all' && rackShelfId !== 'none') {
            params.storage_location_id = rackShelfId;
        }

        $.ajax({
            url: '/products/cycle-count/location/' + warehouseId,
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.products) {
                    let allItems = [];
                    resp.products.forEach(prod => {
                        const variants = extractProductVariants(prod);
                        allItems.push(...variants);
                    });

                    qbPiItems = allItems;
                    const rackShelfTxt = $('#qbPiRackShelfSelect option:selected').text() || '';
                    const isSpecificRack = rackShelfId && rackShelfId !== 'all';
                    const badgeText = isSpecificRack 
                        ? `<i class="fas fa-boxes-stacked text-primary me-1"></i> <strong>${resp.count}</strong> Products in ${escapeHtml(rackShelfTxt)}`
                        : `<i class="fas fa-warehouse text-primary me-1"></i> <strong>${resp.count}</strong> Products Loaded`;

                    $('#qbPiLocationLoadedBadge')
                        .html(badgeText)
                        .addClass('has-selection')
                        .show();

                    $('#qbPiCurrentProductName').html(`<strong>${resp.count}</strong> Products in Location &bull; <strong>${qbPiItems.length}</strong> Variants`);
                    $('#qbPiCurrentProductBadge').show();

                    renderQbPiTable();
                } else {
                    qbPiItems = [];
                    $('#qbPiLocationLoadedBadge').hide();
                    renderQbPiTable();
                }
            },
            error: function(xhr) {
                console.error('Error fetching location cycle count:', xhr);
                $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load location inventory.</td></tr>');
            }
        });
    }

    // ── Due Items Loader (1-Click Cycle Counting) ──
    $(document).on('click', '#qbPiLoadDueBtn, #qbPiLoadDueBtnLoc', function(e) {
        e.preventDefault();
        loadDueCycleCountItems();
    });

    function loadDueCycleCountItems() {
        $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-warning me-2"></div>Loading products due for cycle count today...</td></tr>');
        $('#qbPiActiveWarehouseName').text('DUE CYCLE COUNT (TODAY)');

        $.ajax({
            url: '{{ route("products.cycle_count.due_items") }}',
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.products) {
                    let allItems = [];
                    let prodIds = [];
                    resp.products.forEach(prod => {
                        prodIds.push(String(prod.id));
                        const variants = extractProductVariants(prod);
                        allItems.push(...variants);
                    });

                    qbPiItems = allItems;
                    if (qbPiCurrentMode === 'product') {
                        $('#qbPiProductSelect').val(prodIds).trigger('change.select2');
                    }

                    $('#qbPiSelectedCountBadge')
                        .html(`<i class="fas fa-bell text-warning me-1"></i> <strong>${resp.count}</strong> Due Products Loaded`)
                        .addClass('has-selection');

                    $('#qbPiModalDueCount, #qbPiModalDueCountLoc').text(resp.count || 0);

                    $('#qbPiCurrentProductName').html(`<strong>${resp.count}</strong> Due Products &bull; <strong>${qbPiItems.length}</strong> Variants for Counting`);
                    $('#qbPiCurrentProductBadge').show();

                    renderQbPiTable();

                    if (typeof Swal !== 'undefined' && Swal.mixin) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'info',
                            title: `Loaded ${resp.count} products due for count today`
                        });
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading due items', xhr);
                $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load due items.</td></tr>');
            }
        });
    }

    // ── Frequency Duration Schedule Handler (From Dropdown) ──
    $(document).on('change', '#qbPiFrequencySelect, #qbPiFrequencySelectLoc', function() {
        const val = $(this).val();
        $('#qbPiFrequencySelect, #qbPiFrequencySelectLoc').val(val);
        qbPiSelectedDuration = val;
    });

    $(document).on('click', '#qbPiApplyScheduleBtn, #qbPiApplyScheduleBtnLoc', function(e) {
        e.preventDefault();
        const selectedFrequency = (qbPiCurrentMode === 'location' ? $('#qbPiFrequencySelectLoc').val() : $('#qbPiFrequencySelect').val()) || 'auto';
        qbPiSelectedDuration = selectedFrequency;
        const distinctProdIds = [...new Set(qbPiItems.map(i => i.product_id))].filter(Boolean);

        if (distinctProdIds.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Products in Worksheet',
                    text: 'Please select or load products in the worksheet before applying cycle frequency.',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                alert('Please select or load products first.');
            }
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ route("products.cycle_count.update_schedule") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_ids: distinctProdIds,
                duration: selectedFrequency
            },
            success: function(resp) {
                btn.prop('disabled', false).html('<i class="fas fa-check"></i> Apply');

                // Update loaded items' metrics locally
                const daysNum = selectedFrequency === 'auto' ? 7 : parseInt(selectedFrequency);
                const nextDate = new Date();
                nextDate.setDate(nextDate.getDate() + daysNum);
                const nextDateStr = nextDate.toISOString().split('T')[0];

                qbPiItems.forEach(item => {
                    if (item.cycle_count_metrics) {
                        item.cycle_count_metrics.current_days = daysNum;
                        item.cycle_count_metrics.next_count_date = nextDateStr;
                        item.cycle_count_metrics.is_due = false;
                        if (selectedFrequency !== 'auto') {
                            item.cycle_count_metrics.current_class = daysNum <= 7 ? 'A' : (daysNum <= 30 ? 'B' : 'C');
                        }
                    }
                });

                renderQbPiTable();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Schedule Updated!',
                        text: resp.message || `Cycle count schedule updated for ${distinctProdIds.length} product(s).`,
                        confirmButtonColor: '#059669',
                        timer: 2500
                    });
                } else {
                    alert(resp.message || 'Schedule updated successfully!');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-check"></i> Apply');
                const msg = xhr.responseJSON?.message || 'Failed to update schedule.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Update Failed', text: msg, confirmButtonColor: '#dc2626' });
                } else {
                    alert('Error: ' + msg);
                }
            }
        });
    });

    // ── Select2 Combobox Initialization ──
    function initQbPiSelect2() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 === 'function') {
            try {
                if ($('#qbPiProductSelect').hasClass('select2-hidden-accessible')) {
                    $('#qbPiProductSelect').select2('destroy');
                }
            } catch(e) {}
            $('#qbPiProductSelect').select2({
                dropdownParent: $('#qbPhysicalInventoryModal'),
                placeholder: 'Search products by name or code...',
                allowClear: true,
                closeOnSelect: false,
                width: '100%',
                templateResult: function(item) {
                    if (!item.id) return item.text;
                    const text = item.text || '';
                    const parts = text.split(' • ');
                    const name = parts[0] || text;
                    const code = parts[1] ? parts[1].replace(/[\[\]]/g, '') : '';
                    return $(`
                        <div class="d-flex align-items-center justify-content-between w-100 py-1">
                            <span class="fw-semibold text-truncate me-2">${escapeHtml(name)}</span>
                            ${code ? `<span class="badge bg-light text-secondary border font-monospace" style="font-size:10px; font-weight:600; padding:2px 6px;">${escapeHtml(code)}</span>` : ''}
                        </div>
                    `);
                },
                templateSelection: function(item) {
                    if (!item.id) return item.text;
                    const text = item.text || '';
                    const parts = text.split(' • ');
                    return parts[0] || text;
                }
            });

            // Dedicated search input inside dropdown
            $('#qbPiProductSelect').off('select2:open.piSearch').on('select2:open.piSearch', function() {
                const $dropdown = $('#qbPhysicalInventoryModal .select2-dropdown');
                if (!$dropdown.find('.qb-pi-dropdown-search-wrap').length) {
                    const $searchBar = $(`
                        <div class="qb-pi-dropdown-search-wrap">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 py-0.5 px-2 text-primary" style="border-color:#cbd5e1;">
                                    <i class="fas fa-search" style="font-size:11px;"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 py-1 ps-1 qb-pi-dropdown-search-input" placeholder="Type to search product name or code..." style="border-color:#cbd5e1; font-size:12px; height:28px;">
                            </div>
                        </div>
                    `);
                    $dropdown.prepend($searchBar);
                }

                const $searchWrap = $dropdown.find('.qb-pi-dropdown-search-wrap');
                const $searchInput = $dropdown.find('.qb-pi-dropdown-search-input');

                $searchWrap.off('click mousedown').on('click mousedown', function(e) {
                    e.stopPropagation();
                });

                $searchInput.val('');
                $dropdown.find('.select2-results__option').show();
                setTimeout(() => $searchInput.focus(), 60);

                $searchInput.off('input').on('input', function() {
                    const q = $(this).val().toLowerCase().trim();
                    $dropdown.find('.select2-results__option').each(function() {
                        const txt = $(this).text().toLowerCase();
                        if (!q || txt.includes(q)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            });

            $('#qbPiProductSelect').off('select2:select.piSearch select2:unselect.piSearch').on('select2:select.piSearch select2:unselect.piSearch', function() {
                const $searchInput = $('#qbPhysicalInventoryModal .select2-dropdown .qb-pi-dropdown-search-input');
                if ($searchInput.length && $searchInput.val()) {
                    $searchInput.trigger('input');
                }
            });
        }
    }

    function extractProductVariants(product) {
        let variants = [];
        if (product.color) {
            let parsed = product.color;
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (typeof parsed === 'string') {
                try { parsed = JSON.parse(parsed); } catch(e) {}
            }
            if (Array.isArray(parsed) && parsed.length > 0) {
                variants = parsed;
            }
        }

        const locBreakdown = product.location_breakdown || [];
        const metrics = product.cycle_count_metrics || {};

        const prodImage = String(product.image || '');
        const prodCategory = String(product.category_relation?.name || product.category_name || '');

        if (variants.length === 0) {
            let expected = parseFloat(product.calculated_total_stock_qty || 0);
            if (product.warehouse_stocks && Array.isArray(product.warehouse_stocks)) {
                expected = product.warehouse_stocks.reduce((acc, ws) => acc + (parseFloat(ws.total_pieces) || 0), 0);
            }
            return [{
                product_id: parseInt(product.id),
                variant_index: null,
                item_code: String(product.item_code || ('#' + product.id)),
                base_item_name: String(product.item_name || 'Standard Item'),
                item_name: String(product.item_name || 'Standard Item'),
                image: prodImage,
                category_name: prodCategory,
                attribute: 'Standard',
                size: 'Regular',
                expected_qty: expected,
                counted_qty: expected,
                difference: 0,
                barcode: String(product.barcode_path || product.item_code || ''),
                serial_no: '',
                location_breakdown: locBreakdown,
                cycle_count_metrics: metrics
            }];
        }

        return variants.map((v, vIdx) => {
            let attr = (v.color && v.color !== '-') ? String(v.color).trim() : 'Standard';
            let size = (v.size && v.size !== '-') ? String(v.size).trim() : 'Regular';
            let expected = parseFloat(v.stock ?? v.current_stock ?? 0);
            let vCode = String(v.serial_no || v.barcode || v.sku || `${product.item_code || ('#' + product.id)}-V${vIdx + 1}`).trim();
            let vName = String(v.name || `${product.item_name} (${size} / ${attr})`).trim();

            return {
                product_id: parseInt(product.id),
                variant_index: vIdx,
                item_code: vCode,
                base_item_name: String(product.item_name || vName),
                item_name: vName,
                image: String(v.image || prodImage || ''),
                category_name: prodCategory,
                attribute: attr,
                size: size,
                expected_qty: expected,
                counted_qty: expected,
                difference: 0,
                barcode: String(v.barcode || ''),
                serial_no: String(v.serial_no || ''),
                sku: String(v.sku || ''),
                physical_location: String(v.location || '').trim(),
                location_breakdown: locBreakdown,
                cycle_count_metrics: metrics
            };
        });
    }

    function syncSelectedProductsVariants() {
        if (qbPiCurrentMode !== 'product') return;

        const rawVals = $('#qbPiProductSelect').val() || [];
        const selectedIds = rawVals.map(id => parseInt(id)).filter(id => !isNaN(id) && id > 0);
        const totalCatalogCount = $('#qbPiProductSelect option').length;

        if (selectedIds.length > 0) {
            $('#qbPiSelectedCountBadge')
                .html(`<i class="fas fa-check-circle text-success me-1"></i> <strong>${selectedIds.length}</strong> / ${totalCatalogCount} Selected`)
                .addClass('has-selection');
        } else {
            $('#qbPiSelectedCountBadge')
                .html(`<i class="fas fa-layer-group text-muted me-1"></i> <strong>0</strong> Selected`)
                .removeClass('has-selection');
        }

        if (selectedIds.length === 0) {
            qbPiItems = [];
            $('#qbPiCurrentProductBadge').hide();
            renderQbPiTable();
            return;
        }

        const missingIds = selectedIds.filter(id => !qbPiLoadedVariantsCache[id]);

        if (missingIds.length > 0) {
            $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading variants for ' + missingIds.length + ' product(s)...</td></tr>');

            const fetchPromises = missingIds.map(id => $.getJSON('/productview/' + id));
            Promise.all(fetchPromises).then(results => {
                results.forEach(prod => {
                    if (prod && prod.id) {
                        qbPiLoadedVariantsCache[prod.id] = extractProductVariants(prod);
                    }
                });
                rebuildPiItemsFromSelected(selectedIds);
            }).catch(err => {
                console.error('Error fetching variants', err);
                rebuildPiItemsFromSelected(selectedIds);
            });
        } else {
            rebuildPiItemsFromSelected(selectedIds);
        }
    }

    function rebuildPiItemsFromSelected(selectedIds) {
        const currentCountsMap = {};
        qbPiItems.forEach(item => {
            currentCountsMap[`${item.product_id}_${item.variant_index}`] = item.counted_qty;
        });

        let allItems = [];
        selectedIds.forEach(id => {
            if (qbPiLoadedVariantsCache[id]) {
                qbPiLoadedVariantsCache[id].forEach(v => {
                    let key = `${v.product_id}_${v.variant_index}`;
                    let countVal = currentCountsMap.hasOwnProperty(key) ? currentCountsMap[key] : v.expected_qty;
                    allItems.push({
                        ...v,
                        counted_qty: countVal,
                        difference: (parseFloat(countVal) || 0) - (parseFloat(v.expected_qty) || 0)
                    });
                });
            }
        });

        qbPiItems = allItems;
        renderQbPiTable();

        if (selectedIds.length > 0) {
            $('#qbPiCurrentProductName').html(`<strong>${selectedIds.length}</strong> Product(s) Selected &bull; <strong>${qbPiItems.length}</strong> Variants`);
            $('#qbPiCurrentProductBadge').show();
        } else {
            $('#qbPiCurrentProductBadge').hide();
        }
    }

    $(document).on('change', '#qbPiProductSelect', function() {
        qbPiCurrentMode = 'product';
        $('#qbPiActiveWarehouseName').text('BY SELECTED PRODUCTS');
        syncSelectedProductsVariants();
    });

    $('#qbPiSelectAllProductsBtn').on('click', function(e) {
        e.preventDefault();
        let allVals = [];
        $('#qbPiProductSelect option').each(function() {
            let v = $(this).val();
            if (v) allVals.push(v);
        });
        $('#qbPiProductSelect').val(allVals).trigger('change');
    });

    $('#qbPiClearAllProductsBtn').on('click', function(e) {
        e.preventDefault();
        $('#qbPiProductSelect').val([]).trigger('change');
    });

    function openQbPhysicalInventoryModal(loadDueImmediately = false) {
        qbPiItems = [];
        $('#qbPiScanItemInput, #qbPiScanItemInputLoc').val('');
        $('#qbPiScanQtyInput, #qbPiScanQtyInputLoc').val(1);
        $('#qbPiCurrentProductBadge').hide();
        $('#qbPiTableBody').html('<tr><td colspan="14" class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading inventory items for counting...</td></tr>');

        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery('#qbPhysicalInventoryModal').modal === 'function') {
                jQuery('#qbPhysicalInventoryModal').modal('show');
            } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const el = document.getElementById('qbPhysicalInventoryModal');
                const inst = el && bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(el) : null;
                (inst || new bootstrap.Modal(el)).show();
            } else {
                const el = document.getElementById('qbPhysicalInventoryModal');
                if (el) {
                    el.classList.add('show');
                    el.style.display = 'block';
                    el.removeAttribute('aria-hidden');
                    document.body.classList.add('modal-open');
                }
            }
        } catch(e) {
            const el = document.getElementById('qbPhysicalInventoryModal');
            if (el) {
                el.classList.add('show');
                el.style.display = 'block';
                el.removeAttribute('aria-hidden');
                document.body.classList.add('modal-open');
            }
        }

        setTimeout(function() {
            initQbPiSelect2();
            renderPiColumnsChecklist();
            applyPiColVisibility(getPiColPrefs());
            initPiTableColumnResizing();
            if (loadDueImmediately) {
                loadDueCycleCountItems();
            } else {
                switchQbPiMode('product');
                $('#qbPiProductSelect').val([]).trigger('change');
                $('#qbPiTableBody').html('<tr><td colspan="' + getPiVisibleColCount() + '" class="text-center py-5 text-muted"><i class="fas fa-hand-pointer fs-3 text-secondary mb-2 d-block"></i>Please select one or more products above, or switch to <strong>By Location</strong> mode or click <strong>Load Due Today</strong>.</td></tr>');
            }
        }, 120);
    }

    // ══════════════════════════════════════════════════════════════
    // QUICKBOOKS POS STYLE: CUSTOMIZE PI TABLE COLUMNS & RESIZING
    // ══════════════════════════════════════════════════════════════
    const PI_COLUMNS = [
        { key: 'sr_no',          label: 'Sr #',            default: true,  minWidth: 32 },
        { key: 'item_unique',    label: 'Item Unique',     default: true,  minWidth: 70 },
        { key: 'image',          label: 'Image',           default: true,  minWidth: 38 },
        { key: 'product_name',   label: 'Product Name',    default: true,  minWidth: 140 },
        { key: 'category',       label: 'Category',        default: true,  minWidth: 75 },
        { key: 'barcode',        label: 'Barcode',         default: true,  minWidth: 80 },
        { key: 'attribute',      label: 'Attribute',       default: true,  minWidth: 65 },
        { key: 'size',           label: 'Size',            default: true,  minWidth: 60 },
        { key: 'location',       label: 'Location',        default: true,  minWidth: 100 },
        { key: 'velocity',       label: 'Velocity',        default: true,  minWidth: 80 },
        { key: 'stock',          label: 'Stock',           default: true,  minWidth: 65 },
        { key: 'physical_count', label: 'Physical Count',  default: true,  minWidth: 100 },
        { key: 'variance',       label: 'Variance',        default: true,  minWidth: 70 },
        { key: 'actions',        label: 'Actions',         default: true,  minWidth: 45 },
    ];

    const PI_COL_STORAGE_KEY = 'kent_pi_columns_v1';
    const PI_WIDTH_STORAGE_KEY = 'kent_pi_col_widths_v1';

    function getPiColPrefs() {
        try {
            const saved = localStorage.getItem(PI_COL_STORAGE_KEY);
            if (saved) return JSON.parse(saved);
        } catch(e) {}
        const defaults = {};
        PI_COLUMNS.forEach(c => defaults[c.key] = c.default);
        return defaults;
    }

    function savePiColPrefs(prefs) {
        try {
            localStorage.setItem(PI_COL_STORAGE_KEY, JSON.stringify(prefs));
        } catch(e) {}
    }

    function getPiVisibleColCount() {
        const prefs = getPiColPrefs();
        let count = 0;
        PI_COLUMNS.forEach(c => {
            if (prefs[c.key] !== false) count++;
        });
        return Math.max(1, count);
    }

    function applyPiColVisibility(prefs) {
        if (!prefs) prefs = getPiColPrefs();
        PI_COLUMNS.forEach(col => {
            const isVisible = prefs[col.key] !== false;
            $(`#qbPiTable thead th[data-col-key="${col.key}"]`).toggle(isVisible);
            $(`#qbPiTable tbody tr td[data-col-key="${col.key}"]`).toggle(isVisible);
        });
        const visibleCount = getPiVisibleColCount();
        $('#qbPiTable tbody tr td[colspan]').attr('colspan', visibleCount);
        if (typeof initPiTableColumnResizing === 'function') {
            setTimeout(initPiTableColumnResizing, 40);
        }
    }

    function renderPiColumnsChecklist() {
        const container = document.getElementById('piColumnsChecklist');
        if (!container) return;

        const prefs = getPiColPrefs();
        container.innerHTML = '';

        PI_COLUMNS.forEach(col => {
            const isChecked = prefs[col.key] !== false;
            const colDiv = document.createElement('div');
            colDiv.className = 'col-6';
            colDiv.innerHTML = `
                <div class="form-check p-2 rounded border bg-light d-flex align-items-center gap-2" style="cursor: pointer; transition: background 0.15s;">
                    <input class="form-check-input ms-0 mt-0 pi-col-chk" type="checkbox" id="chk_pi_${col.key}" data-key="${col.key}" ${isChecked ? 'checked' : ''} style="cursor: pointer; width: 16px; height: 16px;">
                    <label class="form-check-label small fw-semibold text-dark mb-0 text-truncate" for="chk_pi_${col.key}" style="cursor: pointer; user-select: none;">
                        ${col.label}
                    </label>
                </div>
            `;
            container.appendChild(colDiv);
        });

        container.querySelectorAll('.pi-col-chk').forEach(chk => {
            chk.addEventListener('change', function() {
                const colKey = this.dataset.key;
                const currentPrefs = getPiColPrefs();
                currentPrefs[colKey] = this.checked;
                savePiColPrefs(currentPrefs);
                applyPiColVisibility(currentPrefs);
            });
        });
    }

    const customizePiModalEl = document.getElementById('customizePiColumnsModal');

    function showCustomizePiModal() {
        renderPiColumnsChecklist();
        if (!customizePiModalEl) return;
        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery(customizePiModalEl).modal === 'function') {
                jQuery(customizePiModalEl).modal('show');
                return;
            }
        } catch(e) {}
        try {
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(customizePiModalEl) : null;
                (inst || new bootstrap.Modal(customizePiModalEl)).show();
                return;
            }
        } catch(e) {}
        customizePiModalEl.classList.add('show');
        customizePiModalEl.style.display = 'block';
        customizePiModalEl.removeAttribute('aria-hidden');
        let bd = document.querySelector('.qb-pi-modal-backdrop');
        if (!bd) {
            bd = document.createElement('div');
            bd.className = 'modal-backdrop fade show qb-pi-modal-backdrop';
            document.body.appendChild(bd);
            bd.onclick = hideCustomizePiModal;
        }
    }

    function hideCustomizePiModal() {
        if (!customizePiModalEl) return;
        try {
            if (typeof jQuery !== 'undefined' && typeof jQuery(customizePiModalEl).modal === 'function') {
                jQuery(customizePiModalEl).modal('hide');
            }
        } catch(e) {}
        try {
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(customizePiModalEl) : null;
                if (inst) inst.hide();
            }
        } catch(e) {}
        customizePiModalEl.classList.remove('show');
        customizePiModalEl.style.display = 'none';
        customizePiModalEl.setAttribute('aria-hidden', 'true');
        const bd = document.querySelector('.qb-pi-modal-backdrop');
        if (bd) bd.remove();
    }

    $(document).on('click', '.qb-pi-modal-close', function(e) {
        e.preventDefault();
        hideCustomizePiModal();
    });

    $(document).on('click', '#customizePiColumnsBtn, #qbPiCtxCustomizeBtn', function(e) {
        e.preventDefault();
        hidePiContextMenu();
        showCustomizePiModal();
    });

    $('#piColSelectAll').on('click', function() {
        const prefs = {};
        PI_COLUMNS.forEach(c => prefs[c.key] = true);
        savePiColPrefs(prefs);
        applyPiColVisibility(prefs);
        renderPiColumnsChecklist();
    });

    $('#piColResetDefault').on('click', function() {
        const prefs = {};
        PI_COLUMNS.forEach(c => prefs[c.key] = c.default);
        savePiColPrefs(prefs);
        applyPiColVisibility(prefs);
        renderPiColumnsChecklist();
    });

    // ── Physical Inventory Table Context Menu ──
    const qbPiContextMenu = document.getElementById('qbPiContextMenu');

    if (qbPiContextMenu && qbPiContextMenu.parentElement !== document.body) {
        document.body.appendChild(qbPiContextMenu);
    }

    const hidePiContextMenu = function() {
        if (qbPiContextMenu) {
            qbPiContextMenu.style.display = 'none';
        }
    };

    const positionPiContextMenu = function(e) {
        if (!qbPiContextMenu) return;
        if (qbPiContextMenu.parentElement !== document.body) {
            document.body.appendChild(qbPiContextMenu);
        }

        qbPiContextMenu.style.visibility = 'hidden';
        qbPiContextMenu.style.display = 'block';

        const menuWidth = qbPiContextMenu.offsetWidth || 210;
        const menuHeight = qbPiContextMenu.offsetHeight || 105;

        let posX = e.clientX;
        let posY = e.clientY;

        if (posX + menuWidth > window.innerWidth - 8) {
            posX = window.innerWidth - menuWidth - 8;
        }
        if (posY + menuHeight > window.innerHeight - 8) {
            posY = window.innerHeight - menuHeight - 8;
        }

        posX = Math.max(8, posX);
        posY = Math.max(8, posY);

        qbPiContextMenu.style.left = posX + 'px';
        qbPiContextMenu.style.top = posY + 'px';
        qbPiContextMenu.style.visibility = 'visible';
    };

    $('#qbPiTable thead').on('contextmenu', function(e) {
        e.preventDefault();
        e.stopPropagation();
        positionPiContextMenu(e.originalEvent || e);
    });

    window.addEventListener('scroll', hidePiContextMenu, { passive: true });
    window.addEventListener('resize', hidePiContextMenu, { passive: true });
    document.addEventListener('scroll', hidePiContextMenu, { passive: true, capture: true });

    $(document).on('click', function(e) {
        if (qbPiContextMenu && !qbPiContextMenu.contains(e.target)) {
            hidePiContextMenu();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            hidePiContextMenu();
        }
    });

    $('#qbPiCtxResetBtn').on('click', function(e) {
        e.preventDefault();
        hidePiContextMenu();
        $('#piColResetDefault').click();
    });

    $('#qbPiCtxResetWidthsBtn').on('click', function(e) {
        e.preventDefault();
        hidePiContextMenu();
        localStorage.removeItem(PI_WIDTH_STORAGE_KEY);
        $('#qbPiTable thead th').css({ 'width': '', 'min-width': '', 'max-width': '' });
        $('#qbPiTable').css({ 'width': '100%', 'min-width': '100%' });
        setTimeout(initPiTableColumnResizing, 60);
    });

    // ── Draggable Column Width Resizing for #qbPiTable ──
    function getSavedPiColWidths() {
        try {
            const raw = localStorage.getItem(PI_WIDTH_STORAGE_KEY);
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        return {};
    }

    function savePiColWidths(widths) {
        try {
            localStorage.setItem(PI_WIDTH_STORAGE_KEY, JSON.stringify(widths));
        } catch(e) {}
    }

    function initPiTableColumnResizing() {
        const $table = $('#qbPiTable');
        if (!$table.length) return;

        const savedWidths = getSavedPiColWidths();
        const $visibleThs = $table.find('thead th:visible');

        let totalCalculatedWidth = 0;

        $visibleThs.each(function(i) {
            const $th = $(this);
            const colKey = $th.attr('data-col-key') || ('pi_col_' + i);

            if (savedWidths[colKey]) {
                const w = parseInt(savedWidths[colKey], 10);
                $th.css({
                    'width': w + 'px',
                    'min-width': w + 'px',
                    'max-width': w + 'px'
                });
                totalCalculatedWidth += w;
            } else {
                const currentW = Math.round($th.outerWidth());
                if (currentW > 0) {
                    $th.css({
                        'width': currentW + 'px',
                        'min-width': currentW + 'px',
                        'max-width': currentW + 'px'
                    });
                    totalCalculatedWidth += currentW;
                }
            }

            $th.find('.col-resizer').remove();

            const $resizer = $('<div class="col-resizer" title="Drag to adjust width (Double-click to reset)"></div>');
            $th.append($resizer);

            $resizer.on('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const startX = e.pageX;
                const startW = $th.outerWidth();
                const startTableW = $table.outerWidth();
                let hasMoved = false;

                $resizer.addClass('is-resizing');
                $('body').addClass('is-col-resizing');

                const colDef = PI_COLUMNS.find(c => c.key === colKey);
                const minWidth = colDef ? colDef.minWidth : 45;

                function onMouseMove(moveEvent) {
                    const diff = moveEvent.pageX - startX;
                    if (Math.abs(diff) > 2) {
                        hasMoved = true;
                    }

                    const newW = Math.max(minWidth, Math.round(startW + diff));
                    const actualDiff = newW - startW;

                    $th.css({
                        'width': newW + 'px',
                        'min-width': newW + 'px',
                        'max-width': newW + 'px'
                    });

                    const parentW = $table.parent().width();
                    const newTableW = Math.max(parentW, startTableW + actualDiff);
                    $table.css({
                        'width': newTableW + 'px',
                        'min-width': newTableW + 'px'
                    });
                }

                function onMouseUp() {
                    $(document).off('mousemove', onMouseMove);
                    $(document).off('mouseup', onMouseUp);

                    $resizer.removeClass('is-resizing');
                    $('body').removeClass('is-col-resizing');

                    if (hasMoved) {
                        const currentWidths = getSavedPiColWidths();
                        currentWidths[colKey] = Math.round($th.outerWidth());
                        savePiColWidths(currentWidths);
                    }
                }

                $(document).on('mousemove', onMouseMove);
                $(document).on('mouseup', onMouseUp);
            });

            $resizer.on('dblclick', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const currentWidths = getSavedPiColWidths();
                delete currentWidths[colKey];
                savePiColWidths(currentWidths);

                $th.css({
                    'width': '',
                    'min-width': '',
                    'max-width': ''
                });
                $table.css({
                    'width': '100%',
                    'min-width': '100%'
                });
                setTimeout(initPiTableColumnResizing, 60);
            });
        });

        if (totalCalculatedWidth > 0) {
            const parentWidth = $table.parent().width();
            const finalTableWidth = Math.max(parentWidth, totalCalculatedWidth);
            $table.css({
                'width': finalTableWidth + 'px',
                'min-width': finalTableWidth + 'px'
            });
        }
    }

    // ── Ledger Table Rendering (Clean Enterprise ERP Grid Matching Image 2) ──
    function renderQbPiTable() {
        if (!qbPiItems || qbPiItems.length === 0) {
            $('#qbPiTableBody').html('<tr><td colspan="' + getPiVisibleColCount() + '" class="text-center py-5 text-muted"><i class="fas fa-inbox fs-3 mb-2 text-secondary d-block"></i>No items loaded in worksheet.</td></tr>');
            $('#qbPiTotalCountItems').text(0);
            $('#qbPiMatchedItemsCount').text(0);
            $('#qbPiDiffItemsCount').text(0);
            $('#qbPiNetVarianceCount').text('0').attr('class', 'doc-summary-kpi-val text-dark');
            return;
        }

        let html = '';
        qbPiItems.forEach((item, idx) => {
            let diff = (parseFloat(item.counted_qty) || 0) - (parseFloat(item.expected_qty) || 0);
            item.difference = diff;

            let diffBadge = '';
            if (diff === 0) {
                diffBadge = '<span class="variance-chip variance-zero"><i class="fas fa-check-circle text-success me-1"></i>0 Diff</span>';
            } else if (diff > 0) {
                diffBadge = `<span class="variance-chip variance-surplus"><i class="fas fa-arrow-up me-1"></i>+${diff}</span>`;
            } else {
                diffBadge = `<span class="variance-chip variance-shortage"><i class="fas fa-arrow-down me-1"></i>${diff}</span>`;
            }

            // Image cell (matching Image 2)
            let imgHtml = '';
            if (item.image) {
                imgHtml = `<img src="${(String(item.image).startsWith('http') || String(item.image).startsWith('/')) ? escapeHtml(item.image) : ('{{ asset("uploads/products") }}/' + escapeHtml(item.image))}" alt="${escapeHtml(item.base_item_name || item.item_name)}" class="product-img" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'no-img-badge\\'><i class=\\'fas fa-image\\'></i></div>';">`;
            } else {
                imgHtml = '<div class="no-img-badge"><i class="fas fa-image"></i></div>';
            }

            // Dedicated column 1: Category
            let catBadge = item.category_name 
                ? `<span class="pi-cat-badge"><i class="fas fa-th-list me-1 text-primary" style="font-size:10px;"></i>${escapeHtml(item.category_name)}</span>` 
                : '<span class="text-muted small">—</span>';

            // Dedicated column 2: Barcode
            let barcodeBadge = item.barcode 
                ? `<span class="pi-barcode-badge"><i class="fas fa-barcode text-secondary" style="font-size:10px;"></i>${escapeHtml(item.barcode)}</span>` 
                : '<span class="text-muted small">—</span>';

            // Dedicated column 3: Attribute (Color / Finish)
            let attrBadge = (item.attribute && item.attribute !== 'Standard')
                ? `<span class="pi-attr-badge">${escapeHtml(item.attribute)}</span>`
                : '<span class="text-muted small">Standard</span>';

            // Dedicated column 4: Size
            let sizeBadge = (item.size && item.size !== 'Regular')
                ? `<span class="pi-size-badge">${escapeHtml(item.size)}</span>`
                : '<span class="text-muted small">Regular</span>';

            // Dedicated column 5: Location (1 clean element per location)
            let locDisplay = 'Main Store Floor';
            if (item.physical_location) {
                locDisplay = item.physical_location;
            } else if (item.location_breakdown && item.location_breakdown.length > 0) {
                const lb = item.location_breakdown[0];
                locDisplay = lb.warehouse_name || 'Warehouse';
                if (lb.rack_shelf) locDisplay += ` [${lb.rack_shelf}]`;
                if (item.location_breakdown.length > 1) {
                    locDisplay += ` (+${item.location_breakdown.length - 1})`;
                }
            }
            let locHtml = `<span class="pi-location-badge" title="${escapeHtml(locDisplay)}"><i class="fas fa-warehouse text-success me-1" style="font-size:10px;"></i>${escapeHtml(locDisplay)}</span>`;

            // Dedicated column 6: Velocity
            let m = item.cycle_count_metrics || {};
            let cls = m.current_class || m.suggested_class || 'C';
            let clsBadge = '';
            if (cls === 'A') {
                clsBadge = `<span class="pi-velocity-badge pi-velocity-a"><i class="fas fa-fire me-1"></i>Class A (${m.current_days || 7}d)</span>`;
            } else if (cls === 'B') {
                clsBadge = `<span class="pi-velocity-badge pi-velocity-b"><i class="fas fa-bolt me-1"></i>Class B (${m.current_days || 30}d)</span>`;
            } else {
                clsBadge = `<span class="pi-velocity-badge pi-velocity-c"><i class="fas fa-box-archive me-1"></i>Class C (${m.current_days || 90}d)</span>`;
            }

            // Dedicated column 7: Expected Stock
            let expStock = parseFloat(item.expected_qty) || 0;
            let stockBadgeClass = expStock === 0 ? 'zero' : '';
            let stockBadgeHtml = `<span class="pi-stock-badge ${stockBadgeClass}"><i class="fas fa-cubes text-secondary me-1" style="font-size:10px;"></i>${expStock} Pcs</span>`;

            html += `
                <tr data-pi-idx="${idx}">
                    <td data-col-key="sr_no" class="text-center" style="font-family:'JetBrains Mono',monospace; font-size:11px; color:#94a3b8;">${idx + 1}</td>
                    <td data-col-key="item_unique" class="text-center"><span class="doc-sku-pill">${escapeHtml(item.item_code)}</span></td>
                    <td data-col-key="image" class="text-center">${imgHtml}</td>
                    <td data-col-key="product_name" class="td-item-details" style="white-space:normal; min-width:170px;">
                        <span class="item-name text-dark fw-bold" style="font-family:'Inter',sans-serif; font-size:12px; line-height:1.3; display:block;">${escapeHtml(item.base_item_name || item.item_name)}</span>
                    </td>
                    <td data-col-key="category" class="text-center">${catBadge}</td>
                    <td data-col-key="barcode" class="text-center">${barcodeBadge}</td>
                    <td data-col-key="attribute" class="text-center">${attrBadge}</td>
                    <td data-col-key="size" class="text-center">${sizeBadge}</td>
                    <td data-col-key="location" class="text-start">${locHtml}</td>
                    <td data-col-key="velocity" class="text-center">${clsBadge}</td>
                    <td data-col-key="stock" class="text-center">${stockBadgeHtml}</td>
                    <td data-col-key="physical_count" class="text-center">
                        <div class="qb-pi-stepper">
                            <button type="button" class="qb-pi-stepper-btn qb-pi-step-minus" data-pi-idx="${idx}" title="Decrease count by 1">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" step="any" min="0" class="qb-pi-count-input" data-pi-idx="${idx}" value="${item.counted_qty}">
                            <button type="button" class="qb-pi-stepper-btn qb-pi-step-plus" data-pi-idx="${idx}" title="Increase count by 1">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td data-col-key="variance" class="text-center" id="qbPiDiff_${idx}">
                        ${diffBadge}
                    </td>
                    <td data-col-key="actions" class="text-center">
                        <div class="action-group">
                            <button type="button" class="btn-act btn-act-delete qb-pi-remove-btn" data-pi-idx="${idx}" title="Remove item from count">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#qbPiTableBody').html(html);
        recalcPiKpis();
        applyPiColVisibility(getPiColPrefs());
    }

    function updateSinglePiRow(idx) {
        const item = qbPiItems[idx];
        if (!item) return;
        const diff = (parseFloat(item.counted_qty) || 0) - (parseFloat(item.expected_qty) || 0);
        item.difference = diff;

        $(`tr[data-pi-idx="${idx}"] .qb-pi-count-input`).val(item.counted_qty);

        let diffBadge = '';
        if (diff === 0) {
            diffBadge = '<span class="variance-chip variance-zero"><i class="fas fa-check-circle text-success me-1"></i>0 Diff</span>';
        } else if (diff > 0) {
            diffBadge = `<span class="variance-chip variance-surplus"><i class="fas fa-arrow-up me-1"></i>+${diff}</span>`;
        } else {
            diffBadge = `<span class="variance-chip variance-shortage"><i class="fas fa-arrow-down me-1"></i>${diff}</span>`;
        }
        $(`#qbPiDiff_${idx}`).html(diffBadge);

        recalcPiKpis();
    }

    function recalcPiKpis() {
        let matchedCount = 0;
        let diffCount = 0;
        let netVariance = 0;
        qbPiItems.forEach(i => {
            let d = (parseFloat(i.counted_qty) || 0) - (parseFloat(i.expected_qty) || 0);
            netVariance += d;
            if (d === 0) matchedCount++;
            else diffCount++;
        });

        $('#qbPiTotalCountItems').text(qbPiItems.length);
        $('#qbPiMatchedItemsCount').text(matchedCount);
        $('#qbPiDiffItemsCount').text(diffCount);

        let netPrefix = netVariance > 0 ? '+' : '';
        $('#qbPiNetVarianceCount').text(`${netPrefix}${netVariance}`);
        if (netVariance > 0) {
            $('#qbPiNetVarianceCount').attr('class', 'doc-summary-kpi-val text-success');
        } else if (netVariance < 0) {
            $('#qbPiNetVarianceCount').attr('class', 'doc-summary-kpi-val text-danger');
        } else {
            $('#qbPiNetVarianceCount').attr('class', 'doc-summary-kpi-val text-dark');
        }
    }

    // Interactive Stepper Buttons
    $(document).on('click', '.qb-pi-step-plus', function(e) {
        e.preventDefault();
        const idx = $(this).data('pi-idx');
        if (qbPiItems[idx]) {
            let cur = parseFloat(qbPiItems[idx].counted_qty) || 0;
            qbPiItems[idx].counted_qty = cur + 1;
            updateSinglePiRow(idx);
        }
    });

    $(document).on('click', '.qb-pi-step-minus', function(e) {
        e.preventDefault();
        const idx = $(this).data('pi-idx');
        if (qbPiItems[idx]) {
            let cur = parseFloat(qbPiItems[idx].counted_qty) || 0;
            qbPiItems[idx].counted_qty = Math.max(0, cur - 1);
            updateSinglePiRow(idx);
        }
    });

    $(document).on('input change', '.qb-pi-count-input', function() {
        const idx = $(this).data('pi-idx');
        const val = parseFloat($(this).val()) || 0;
        if (qbPiItems[idx]) {
            qbPiItems[idx].counted_qty = val;
            updateSinglePiRow(idx);
        }
    });

    // Barcode / SKU Scanning & Adding
    // Barcode / SKU Scanning & Adding (Supports both Product & Location mode inputs)
    $(document).on('click', '#qbPiAddCountBtn, #qbPiAddCountBtnLoc', function(e) {
        e.preventDefault();
        const isLoc = (qbPiCurrentMode === 'location');
        const rawQuery = isLoc ? $('#qbPiScanItemInputLoc').val() : $('#qbPiScanItemInput').val();
        const query = String(rawQuery || '').trim().toLowerCase();
        const qty = parseFloat(isLoc ? $('#qbPiScanQtyInputLoc').val() : $('#qbPiScanQtyInput').val()) || 1;
        if (!query) {
            if (isLoc) $('#qbPiScanItemInputLoc').focus(); else $('#qbPiScanItemInput').focus();
            return;
        }

        let matchedIdx = qbPiItems.findIndex(i => {
            const code = String(i.item_code || '').toLowerCase();
            const name = String(i.item_name || '').toLowerCase();
            const pid  = String(i.product_id || '').toLowerCase();
            const barcode = String(i.barcode || '').toLowerCase();
            const serial  = String(i.serial_no || '').toLowerCase();
            const sku     = String(i.sku || '').toLowerCase();

            return barcode === query || serial === query || sku === query || code === query || code.includes(query) || name.includes(query) || pid === query;
        });

        if (matchedIdx !== -1) {
            let item = qbPiItems[matchedIdx];
            item.counted_qty = (parseFloat(item.counted_qty) || 0) + qty;
            updateSinglePiRow(matchedIdx);

            const $row = $(`tr[data-pi-idx="${matchedIdx}"]`);
            if ($row.length) {
                const container = $('.doc-table-sheet-wrap');
                const rowPos = $row.position();
                if (rowPos && container.length) {
                    container.scrollTop(rowPos.top + container.scrollTop() - 60);
                }
                $row.addClass('pi-row-flash');
                setTimeout(() => $row.removeClass('pi-row-flash'), 1500);
            }

            if (typeof Swal !== 'undefined' && Swal.mixin) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: `+${qty} added to "${item.item_name}" (Total: ${item.counted_qty})`
                });
            }

            $('#qbPiScanItemInput, #qbPiScanItemInputLoc').val('');
            if (isLoc) $('#qbPiScanItemInputLoc').focus(); else $('#qbPiScanItemInput').focus();
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Variant Not Found in Sheet',
                    text: `No matching variant found for "${rawQuery}" in current worksheet list. Try selecting its product or warehouse first.`,
                    confirmButtonColor: '#2563eb'
                });
            } else {
                alert(`No item matched "${rawQuery}" in current worksheet.`);
            }
        }
    });

    $(document).on('keydown', '#qbPiScanItemInput, #qbPiScanItemInputLoc', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            if (qbPiCurrentMode === 'location') {
                $('#qbPiAddCountBtnLoc').click();
            } else {
                $('#qbPiAddCountBtn').click();
            }
        }
    });

    // Set Uncounted to 0
    $(document).on('click', '#qbPiSetUncountedZeroBtn, #qbPiSetUncountedZeroBtnLoc', function(e) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Set All Counted Qty to 0?',
                text: 'This will reset the counted physical quantity to 0 for all items in the worksheet.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Set to 0'
            }).then((res) => {
                if (res.isConfirmed) {
                    qbPiItems.forEach(i => i.counted_qty = 0);
                    renderQbPiTable();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'All counted quantities set to 0',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        } else {
            if (confirm('Set counted quantity to 0 for all items in the count list?')) {
                qbPiItems.forEach(i => i.counted_qty = 0);
                renderQbPiTable();
            }
        }
    });

    // Reset All back to Book Stock
    $(document).on('click', '#qbPiResetExpectedBtn, #qbPiResetExpectedBtnLoc', function(e) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Reset to Book Stock?',
                text: 'This will reset all counted quantities back to match expected book stock.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Reset'
            }).then((res) => {
                if (res.isConfirmed) {
                    qbPiItems.forEach(i => i.counted_qty = i.expected_qty);
                    renderQbPiTable();
                }
            });
        } else {
            qbPiItems.forEach(i => i.counted_qty = i.expected_qty);
            renderQbPiTable();
        }
    });

    // Remove row from worksheet
    $(document).on('click', '.qb-pi-remove-btn', function(e) {
        e.preventDefault();
        const idx = $(this).data('pi-idx');
        if (idx !== undefined && qbPiItems[idx]) {
            qbPiItems.splice(idx, 1);
            renderQbPiTable();
        }
    });

    // Apply Changes & Post Adjustments (and Restart Cycle Count Timer)
    $('#qbPiApplyChangesBtn').on('click', function(e) {
        e.preventDefault();
        const diffItems = qbPiItems.filter(i => (parseFloat(i.difference) || 0) !== 0);
        if (diffItems.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'No Stock Discrepancies',
                    text: 'All counted items currently match system book stock (0 variance). There are no adjustments to post.',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                alert('No stock count differences found to adjust.');
            }
            return;
        }

        let netDelta = diffItems.reduce((acc, i) => acc + (parseFloat(i.difference) || 0), 0);
        let netSign = netDelta > 0 ? `+${netDelta}` : `${netDelta}`;

        const proceedPost = () => {
            const btn = $('#qbPiApplyChangesBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Posting & Restarting Timer...');

            const targetWarehouseId = (qbPiCurrentMode === 'location') ? $('#qbPiWarehouseSelect').val() : null;

            $.ajax({
                url: '{{ route("products.physical_inventory.apply") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: diffItems,
                    warehouse_id: targetWarehouseId
                },
                success: function(resp) {
                    btn.prop('disabled', false).html('<i class="fas fa-check-double me-1"></i> Post & Apply Stock Adjustments');
                    closeQbPhysicalInventoryModal();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Adjustments Posted & Timer Restarted!',
                            text: resp.message || 'Inventory has been reconciled with warehouse stock and cycle count timer restarted.',
                            confirmButtonColor: '#059669'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(resp.message || 'Physical inventory applied and timer restarted successfully!');
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-check-double me-1"></i> Post & Apply Stock Adjustments');
                    const errText = xhr.responseJSON?.message || 'Server error while applying physical inventory.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Reconciliation Failed',
                            text: errText,
                            confirmButtonColor: '#dc2626'
                        });
                    } else {
                        alert('Error: ' + errText);
                    }
                }
            });
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Post Inventory Reconciliation?',
                html: `You are about to post adjustments for <b>${diffItems.length} variant(s)</b> with a net variance of <b>${netSign} units</b>.<br><br>Stock ledger will be updated and <b>cycle count timer will restart</b> for the next scheduled period.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Post & Restart Timer'
            }).then((res) => {
                if (res.isConfirmed) {
                    proceedPost();
                }
            });
        } else {
            if (confirm(`Apply physical inventory reconciliation for ${diffItems.length} items with differences? Stock will be updated and cycle count timer restarted.`)) {
                proceedPost();
            }
        }
    });

    // 8. Edit / Print / Delete from Toolbar shortcuts
    $(document).on('click', '#qbEditSelectedBtn, #qbMenuEditItem', function(e) {
        e.preventDefault();
        if (!qbActiveProductId) {
            const $firstRow = $('#productTable tbody tr:first');
            if ($firstRow.length) selectTableRow($firstRow);
        }
        if (qbActiveProductId) {
            window.location.href = `/products/${qbActiveProductId}/edit`;
        } else {
            alert('Please select a product row in the table first.');
        }
    });

    $(document).on('click', '#qbMenuPrintTags', function(e) {
        e.preventDefault();
        if (!qbActiveProductId) {
            const $firstRow = $('#productTable tbody tr:first');
            if ($firstRow.length) selectTableRow($firstRow);
        }
        if (qbActiveProductId) {
            $(`.openLabelPrintModalBtn[data-id="${qbActiveProductId}"]`).click();
        } else {
            alert('Please select a product row in the table first.');
        }
    });

    $(document).on('click', '#qbMenuDeleteItem', function(e) {
        e.preventDefault();
        if (!qbActiveProductId) {
            const $firstRow = $('#productTable tbody tr:first');
            if ($firstRow.length) selectTableRow($firstRow);
        }
        if (qbActiveProductId) {
            $(`.delete-product-btn[data-id="${qbActiveProductId}"]`).click();
        } else {
            alert('Please select a product row in the table first.');
        }
    });

    $(document).on('click', '#customizeIndexColumnsBtn, #qbMenuCustomizeColumns', function(e) {
        e.preventDefault();
        showCustomizeIndexModal();
    });

    // 9. Search Inventory Bar
    let qbSearchTimer = null;
    $('#qbSearchInventoryInput').on('input', function() {
        clearTimeout(qbSearchTimer);
        const term = $(this).val();
        qbSearchTimer = setTimeout(() => {
            $('input[name="search"]').val(term);
            $('#filterForm').submit();
        }, 500);
    }).on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(qbSearchTimer);
            $('input[name="search"]').val($(this).val());
            $('#filterForm').submit();
        }
    });

    $('#qbQuickFilterSelect').on('change', function() {
        const val = $(this).val();
        if (val === 'active' || val === 'inactive') {
            window.location.href = `{{ route('product') }}?status=${val}`;
        } else if (val === 'all') {
            window.location.href = `{{ route('product') }}`;
        }
    });

    // 10. Global Keyboard Shortcuts: F8, Ctrl+F10, Esc, ArrowUp, ArrowDown
    $(document).on('keydown', function(e) {
        if (e.which === 119 || e.key === 'F8') {
            e.preventDefault();
            toggleQbDetailsDrawer();
            return;
        }

        if (e.ctrlKey && (e.which === 121 || e.key === 'F10')) {
            e.preventDefault();
            if (qbActiveProductId) {
                $(`.openLabelPrintModalBtn[data-id="${qbActiveProductId}"]`).click();
            }
            return;
        }

        if ((e.key === 'ArrowDown' || e.key === 'ArrowUp') && !$(e.target).is('input, textarea, select')) {
            const $selected = $('#productTable tbody tr.row-qb-selected');
            if ($selected.length) {
                e.preventDefault();
                const $target = e.key === 'ArrowDown' ? $selected.next('tr') : $selected.prev('tr');
                if ($target.length) {
                    selectTableRow($target);
                    $target[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }
    });

    // ── Physical Inventory to Formal Cycle Count Assignment Link ──
    $(document).on('click', '#qbPiAssignStaffBtn', function(e) {
        e.preventDefault();
        const rackId = $('#qbPiRackShelfSelect').val();
        let assignUrl = '{{ route("cycle_count.create") }}';
        if (rackId && rackId !== 'all' && rackId !== 'none') {
            assignUrl += '?location_id=' + encodeURIComponent(rackId);
        }
        window.location.href = assignUrl;
    });

    // ── Auto-open Physical Inventory Modal if open_pi query parameter present ──
    try {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_pi') === '1' || urlParams.get('open_audit') === '1') {
            setTimeout(function() {
                openQbPhysicalInventoryModal(urlParams.get('due') === '1');
                const targetLocId = urlParams.get('location_id');
                if (targetLocId) {
                    setTimeout(function() {
                        $('#qbPiRackShelfSelect').val(targetLocId).trigger('change');
                    }, 400);
                }
            }, 300);
        }
    } catch(e) {}

});  // ── end $(document).ready ──
</script>
@endsection
