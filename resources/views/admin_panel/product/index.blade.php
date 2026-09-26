@extends('admin_panel.layout.app')
@section('content')

<style>
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

    /* ── Table ── */
    .erp-table-wrap { padding: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #productTable {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: .82rem;
    }
    #productTable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .67rem;
        letter-spacing: .5px;
        padding: 12px 14px;
        border-bottom: 2px solid var(--erp-border) !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        white-space: nowrap;
        position: sticky; top: 0; z-index: 2;
    }
    #productTable tbody td {
        padding: 10px 14px;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: var(--erp-text);
        vertical-align: middle;
        white-space: nowrap;
    }
    #productTable tbody td.td-item-details { white-space: normal; min-width: 180px; max-width: 260px; }
    #productTable tbody tr { transition: background .12s ease; }
    #productTable tbody tr:hover { background: #f8fafc !important; }
    #productTable tbody tr.row-inactive { opacity: .6; background: #fafafa; }

    /* Image cell */
    .product-img {
        width: 40px; height: 40px; object-fit: cover;
        border-radius: 8px; border: 1px solid var(--erp-border);
        transition: transform .2s ease, box-shadow .2s ease;
        cursor: pointer; display: block;
    }
    .product-img:hover { transform: scale(1.35); z-index: 10; position: relative; box-shadow: var(--erp-shadow-md); }
    .no-img-badge {
        width: 40px; height: 40px; border-radius: 8px;
        background: #f1f5f9; border: 1px dashed #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; color: #94a3b8;
    }

    /* Item details cell */
    .item-name { font-weight: 700; color: var(--erp-text); margin-bottom: 3px; font-size: .85rem; line-height: 1.3; }
    .item-meta { font-size: .7rem; color: var(--erp-muted); display: flex; flex-wrap: wrap; gap: 4px; align-items: center; margin-top: 2px; }
    .item-meta .meta-chip {
        background: #f1f5f9; border-radius: 4px; padding: 2px 6px;
        font-size: .67rem; font-weight: 600; color: #475569;
        display: inline-flex; align-items: center; gap: 3px;
    }
    .item-code { font-family: 'Courier New', monospace; background: #f8fafc; border: 1px solid var(--erp-border); border-radius: 4px; padding: 1px 6px; font-size: .7rem; font-weight: 600; color: #334155; }

    /* Stock badge */
    .stock-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: var(--erp-success-lt); color: var(--erp-success);
        border: 1px solid #a7f3d0; border-radius: 6px;
        padding: 3px 8px; font-size: .75rem; font-weight: 700;
        white-space: nowrap;
    }
    .stock-badge.low  { background: var(--erp-danger-lt); color: var(--erp-danger); border-color: #fecaca; }
    .stock-badge.zero { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .stock-unit { font-weight: 500; font-size: .67rem; opacity: .85; }

    /* Price cells */
    .price-purchase { color: var(--erp-muted); font-weight: 600; font-size: .81rem; white-space: nowrap; }
    .price-sale     { color: var(--erp-success); font-weight: 800; font-size: .84rem; white-space: nowrap; }

    /* Status badge */
    .status-active {
        background: var(--erp-success-lt); color: var(--erp-success);
        border: 1px solid #a7f3d0; border-radius: 20px;
        padding: 3px 10px; font-size: .7rem; font-weight: 700;
        letter-spacing: .2px; white-space: nowrap;
    }
    .status-inactive {
        background: #f1f5f9; color: #64748b;
        border: 1px solid #cbd5e1; border-radius: 20px;
        padding: 3px 10px; font-size: .7rem; font-weight: 700;
        white-space: nowrap;
    }

    /* Action buttons – single row, compact */
    .action-group {
        display: flex; align-items: center; gap: 4px;
        flex-wrap: nowrap;
        justify-content: flex-start;
    }
    .btn-act {
        border-radius: 6px; padding: 4px 9px; font-size: .72rem;
        font-weight: 600; display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid transparent; transition: all .12s; cursor: pointer;
        line-height: 1.5; white-space: nowrap; flex-shrink: 0;
    }
    .btn-act-view    { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
    .btn-act-view:hover    { background: #0284c7; color: #fff; }
    .btn-act-edit    { background: var(--erp-primary-lt); color: var(--erp-primary); border-color: #c7d2fe; }
    .btn-act-edit:hover    { background: var(--erp-primary); color: #fff; }
    .btn-act-barcode { background: var(--erp-success-lt); color: var(--erp-success); border-color: #a7f3d0; }
    .btn-act-barcode:hover { background: var(--erp-success); color: #fff; }
    .btn-act-deact   { background: var(--erp-danger-lt); color: var(--erp-danger); border-color: #fecaca; }
    .btn-act-deact:hover   { background: var(--erp-danger); color: #fff; }
    .btn-act-act     { background: var(--erp-success-lt); color: var(--erp-success); border-color: #a7f3d0; }
    .btn-act-act:hover     { background: var(--erp-success); color: #fff; }

    /* ── Pagination ── */
    .erp-pagination { padding: 14px 20px; border-top: 1px solid var(--erp-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .erp-pagination .showing { font-size: .78rem; color: var(--erp-muted); }
    .erp-pagination .page-link { border-radius: 6px !important; border-color: var(--erp-border) !important; color: var(--erp-text) !important; font-size: .8rem; padding: 5px 12px; }
    .erp-pagination .page-item.active .page-link { background: var(--erp-primary) !important; border-color: var(--erp-primary) !important; color: #fff !important; }

    /* ── Actions column – force min-width so buttons never wrap ── */
    #productTable th:last-child,
    #productTable td:last-child { min-width: 220px; }

    /* ── Select checkbox ── */
    input[type="checkbox"].row-check { width: 16px; height: 16px; accent-color: var(--erp-primary); cursor: pointer; }

    /* ── DataTable override ── */
    div.dataTables_wrapper div.dataTables_length select { width: 75px !important; }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate { display: none !important; }
    .dataTables_wrapper { overflow-x: visible !important; }

    /* ── Odoo Video Matrix View: Attribute Pills & Radios ── */
    .matrix-radio-pill {
        display: inline-flex !important;
        align-items: center !important;
        padding: 7px 16px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        cursor: pointer !important;
        margin: 0 !important;
        transition: all 0.15s ease !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        user-select: none !important;
    }
    .matrix-radio-pill:hover {
        border-color: #00A09D !important;
        background: #f0fdfa !important;
    }
    .matrix-radio-pill.selected-pill {
        border-color: #00A09D !important;
        background: #e6fffa !important;
        box-shadow: 0 0 0 2px rgba(0, 160, 157, 0.25) !important;
    }
    .matrix-radio-pill.selected-pill .matrix-attr-val {
        color: #00A09D !important;
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
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid px-3 py-3">

    {{-- ── Stats Row ── --}}
    <div class="stat-grid mb-4" style="display:grid; grid-template-columns: repeat(4,1fr); gap:16px;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fas fa-box-open"></i></div>
            <div>
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ $products->total() }}</div>
                <div class="stat-sub">in catalog</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#ecfdf5; color:#059669;"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Active</div>
                <div class="stat-value">{{ $products->getCollection()->where('is_active',1)->count() }}</div>
                <div class="stat-sub">on this page</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2; color:#dc2626;"><i class="fas fa-times-circle"></i></div>
            <div>
                <div class="stat-label">Inactive</div>
                <div class="stat-value">{{ $products->getCollection()->where('is_active',0)->count() }}</div>
                <div class="stat-sub">on this page</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb; color:#d97706;"><i class="fas fa-filter"></i></div>
            <div>
                <div class="stat-label">Filtered Results</div>
                <div class="stat-value">{{ $products->total() }}</div>
                <div class="stat-sub">
                    @if(request()->hasAny(['search','category_id','brand_id','status']))
                        <span style="color:#d97706; font-weight:700;">Filters active</span>
                    @else
                        all products
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="erp-card">

        {{-- Card Header --}}
        <div class="erp-card-header">
            <div>
                <p class="page-title"><i class="fas fa-box me-2" style="color:var(--erp-primary);"></i>Product Catalog</p>
                <p class="page-sub">Manage, filter and bulk-edit your entire product inventory</p>
            </div>
            <div class="erp-hdr-actions">
                <a href="{{ route('products.template') }}" class="btn-hdr btn-hdr-outline" title="Download blank CSV template">
                    <i class="fas fa-file-csv"></i> Template
                </a>
                <a href="{{ route('products.export') }}" class="btn-hdr btn-hdr-success" title="Export all products to CSV">
                    <i class="fas fa-file-download"></i> Export CSV
                </a>
                <button type="button" class="btn-hdr btn-hdr-outline" id="customizeIndexColumnsBtn" data-toggle="modal" data-target="#customizeIndexColumnsModal" data-bs-toggle="modal" data-bs-target="#customizeIndexColumnsModal" title="Customize Columns (or right-click table header)">
                    <i class="fas fa-columns text-primary"></i> Customize Columns
                </button>
                @if (auth()->user()->can('products.create') || auth()->user()->email === 'admin@admin.com')
                    <button type="button" class="btn-hdr btn-hdr-warning" id="openImportModalBtn">
                        <i class="fas fa-file-upload"></i> Import CSV
                    </button>
                    <a href="create_prodcut" class="btn-hdr btn-hdr-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                @endif
            </div>
        </div>


        {{-- ── Filter Panel ── --}}
        <div class="filter-panel">
            <div class="filter-heading"><i class="fas fa-sliders-h"></i> Filters &amp; Search</div>
            <form method="GET" action="{{ route('product') }}" id="filterForm">
                <div class="erp-filter-row">

                    {{-- Search --}}
                    <div class="erp-filter-field erp-filter-search">
                        <label class="erp-flabel">Search</label>
                        <div class="search-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="erp-finput" placeholder="Item name, code, barcode…">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="erp-filter-field">
                        <label class="erp-flabel">Category</label>
                        <select name="category_id" class="erp-finput">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div class="erp-filter-field">
                        <label class="erp-flabel">Brand</label>
                        <select name="brand_id" class="erp-finput">
                            <option value="">All Brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="erp-filter-field" style="min-width:120px;">
                        <label class="erp-flabel">Status</label>
                        <select name="status" class="erp-finput">
                            <option value="">All Status</option>
                            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>✅ Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⛔ Inactive</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="erp-filter-field erp-filter-btns">
                        <label class="erp-flabel">&nbsp;</label>
                        <div style="display:flex; gap:6px;">
                            <button type="submit" class="btn-erp-filter">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                            <a href="{{ route('product') }}" class="btn-erp-clear">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Active filter chips --}}
                @if(request()->hasAny(['search','category_id','brand_id','status']))
                <div class="active-filters" style="margin-top:10px;">
                    <span style="font-size:.72rem; font-weight:600; color:var(--erp-muted);">Active:</span>
                    @if(request('search'))
                        <span class="filter-chip"><i class="fas fa-search" style="font-size:.6rem;"></i> "{{ request('search') }}"</span>
                    @endif
                    @if(request('category_id'))
                        <span class="filter-chip"><i class="fas fa-list" style="font-size:.6rem;"></i> {{ $categories->firstWhere('id', request('category_id'))->name ?? 'Category' }}</span>
                    @endif
                    @if(request('brand_id'))
                        <span class="filter-chip"><i class="fas fa-trademark" style="font-size:.6rem;"></i> {{ $brands->firstWhere('id', request('brand_id'))->name ?? 'Brand' }}</span>
                    @endif
                    @if(request('status'))
                        <span class="filter-chip"><i class="fas fa-circle" style="font-size:.6rem;"></i> {{ ucfirst(request('status')) }}</span>
                    @endif
                    <span style="font-size:.72rem; color:var(--erp-muted);">— <strong>{{ $products->total() }}</strong> result(s)</span>
                </div>
                @endif
            </form>
        </div>

        {{-- ── Success Alert ── --}}
        @if (session()->has('success'))
        <div class="mx-4 mt-3 alert d-flex align-items-center gap-2" style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; color:#065f46; font-size:.85rem; padding:10px 14px;">
            <i class="fas fa-check-circle" style="color:#059669; font-size:16px;"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
        </div>
        @endif


        {{-- ── Table ── --}}
        <div class="erp-table-wrap table-responsive">

                <table id="productTable" class="table table-hover align-middle nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="selectAll" class="row-check"></th>
                            <th style="width:40px;">#</th>
                            <th style="width:52px;">Image</th>
                            <th>Item Details</th>
                            <th>Stock</th>
                            <th>Purchase Price</th>
                            <th>Sky Price</th>
                            <th style="width:85px;">Sky P-Code</th>
                            <th>Rot Price.</th>
                            <th style="width:85px;">Rot P-Code</th>
                            <th style="width:75px;">Status</th>
                            <th class="text-center" style="width:220px;">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span>Actions</span>
                                    <label class="d-inline-flex align-items-center gap-1 m-0 px-2 py-0 rounded border" style="background:#fdf4ff; border-color:#f5d0fe; cursor:pointer;" title="Check to open all products in Matrix View on View click">
                                        <input type="checkbox" id="globalMatrixRadio" style="cursor:pointer; width:12px; height:12px; accent-color:#714B67; margin:0;">
                                        <span style="font-size:9.5px; font-weight:700; color:#714B67;">Matrix</span>
                                    </label>
                                </div>
                            </th>
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
                                <td><input type="checkbox" class="selectProduct row-check" value="{{ $product->id }}"></td>
                                <td style="color:var(--erp-muted); font-size:.72rem;">{{ $products->firstItem() + $key }}</td>
                                <td>
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
                                        <span class="item-code">{{ $product->item_code }}</span>
                                        @if($product->category_relation)
                                            <span class="meta-chip"><i class="fas fa-list" style="font-size:.6rem;"></i> {{ $product->category_relation->name }}</span>
                                        @endif
                                        @if($product->brand)
                                            <span class="meta-chip"><i class="fas fa-trademark" style="font-size:.6rem;"></i> {{ $product->brand->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $stockClass }}">
                                        <i class="fas fa-cubes" style="font-size:.65rem;"></i>
                                        {{ $stockDisplay }}
                                        <span class="stock-unit">{{ $stockUnit }}</span>
                                    </span>
                                </td>
                                <td class="price-purchase">Rs. {{ number_format($tradePrice, 2) }}</td>
                                <td class="price-sale">Rs. {{ number_format($retailPrice, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-bold" style="font-size: 0.82rem; letter-spacing: 0.5px;" title="Sky P-Code">
                                        {{ $product->p_code ?: \App\Services\PCodeService::encode($retailPrice) }}
                                    </span>
                                </td>
                                <td class="price-wholesale">Rs. {{ number_format((float)($product->wholesale_price ?? 0), 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-bold" style="font-size: 0.82rem; letter-spacing: 0.5px;" title="Rot P-Code">
                                        {{ $product->rot_p_code ?: \App\Services\PCodeService::encode($product->wholesale_price ?? 0) }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="status-active" id="status-badge-{{ $product->id }}">Active</span>
                                    @else
                                        <span class="status-inactive" id="status-badge-{{ $product->id }}">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group align-items-center">
                                        <div class="d-flex flex-column align-items-center me-1" style="min-width: 58px;">
                                            <div class="d-flex align-items-center justify-content-center gap-1 mb-1" style="cursor: pointer; line-height: 1;" title="Radio Matrix View (from Video)">
                                                <input type="radio" class="form-check-input row-matrix-radio" name="row_matrix_radio" id="rowMatrixRadio_{{ $product->id }}" data-id="{{ $product->id }}" style="cursor: pointer; width: 13px; height: 13px; accent-color: #714B67; margin: 0;">
                                                <label for="rowMatrixRadio_{{ $product->id }}" class="form-check-label m-0 fw-bold" style="font-size: 10px; color: #714B67; cursor: pointer; white-space: nowrap;">Matrix</label>
                                            </div>
                                            <button type="button" class="btn-act btn-act-view viewProductBtn w-100 justify-content-center"
                                                data-id="{{ $product->id }}" title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn-act btn-act-edit" title="Edit Product">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                        @endif
                                        <a href="{{ route('generate-barcode-image', $product->id) }}"
                                            class="btn-act btn-act-barcode" title="Generate Barcode">
                                            <i class="fas fa-barcode"></i>
                                        </a>
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>{{-- /erp-table-wrap --}}

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
                                <input type="radio" class="form-check-input row-matrix-radio" name="row_matrix_radio" id="mRowMatrixRadio_{{ $product->id }}" data-id="{{ $product->id }}" style="cursor: pointer; width: 13px; height: 13px; accent-color: #714B67; margin: 0;">
                                <label for="mRowMatrixRadio_{{ $product->id }}" class="form-check-label m-0 fw-bold" style="font-size: 9px; color: #714B67; cursor: pointer;">Matrix View</label>
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
                        <a href="{{ route('generate-barcode-image', $product->id) }}" class="btn-act btn-act-barcode">
                            <i class="fas fa-barcode"></i> Barcode
                        </a>
                        @if (auth()->user()->can('products.edit') || auth()->user()->email === 'admin@admin.com')
                            <button type="button"
                                class="btn-act {{ $product->is_active ? 'btn-act-deact' : 'btn-act-act' }} toggle-active-btn"
                                data-id="{{ $product->id }}"
                                data-active="{{ $product->is_active ? '1' : '0' }}"
                                data-name="{{ $product->item_name }}">
                                <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }}"></i>
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
        </div>{{-- /container-fluid --}}
    </div>{{-- /main-content-inner --}}
</div>{{-- /main-content --}}


{{-- ══════════════════════════════════════════════════════════════
     IMPORT MODAL
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border-bottom: none;">
                <div>
                    <h5 class="modal-title fw-bold" id="importModalLabel">
                        <i class="fas fa-file-upload me-2"></i>Import Products from CSV
                    </h5>
                    <small style="color: rgba(255,255,255,0.85);">New products will be created. Existing ones (matched by Barcode or Item Code) will be updated.</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:.8; text-shadow:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background:#f8fafc;">
                <form action="{{ route('products.import.validate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(session('error'))
                        <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
                    @endif
                    <div class="alert alert-info d-flex gap-2 align-items-start mb-4" style="font-size:.85rem;">
                        <i class="fas fa-info-circle fs-5 mt-1 flex-shrink-0"></i>
                        <div>
                            <strong>How to use:</strong><br>
                            1. Download the <a href="{{ route('products.template') }}" class="alert-link">CSV Template</a> first.<br>
                            2. Fill in your data in Excel and save as <strong>CSV</strong>.<br>
                            3. Upload here to validate and preview the changes.<br>
                            4. Confirm the preview to actually import the data.
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Import Mode</label>
                        <select name="import_mode" class="form-select form-control" required>
                            <option value="create">Create (Add new products &amp; variants)</option>
                            <option value="update_only">Update Only (Update existing variants only)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoCreate" name="auto_create" value="1" checked>
                            <label class="form-check-label fw-bold ms-2" for="autoCreate">Auto-create missing Category &amp; Brand</label>
                        </div>
                        <small class="text-muted ms-4 d-block">If disabled, missing master data will throw validation errors.</small>
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">Upload CSV File</label>
                        <input type="file" name="csv_file" class="form-control p-1" accept=".csv,.txt" required>
                        <small class="text-muted">Max 5 MB</small>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-arrow-right me-1"></i> Next: Validate &amp; Preview</button>
                    </div>
                </form>
            </div>
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
    <button type="button" class="qb-ctx-item text-muted" id="qbCtxResetIndexBtn">
        <i class="fas fa-undo"></i>
        <span>Reset to Default</span>
    </button>
</div>

{{-- ══════════════════════════════════════════════════════════════
     PRODUCT VIEW MODAL (Compact Kent ERP Theme)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="productViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 740px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            {{-- Header --}}
            <div class="modal-header border-bottom bg-light px-4 py-2.5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 36px; height: 36px; border-radius: 9px; background: #f3e8ff; color: #714B67; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="productViewModalLabel" style="font-size: 1.08rem; letter-spacing: -0.2px;">
                            <span id="view_item_name">Product</span>
                        </h5>
                        <small class="text-muted font-monospace" id="view_item_subtext" style="font-size: 11px;">CODE</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <!-- View Mode Switcher: Table List vs Video Matrix -->
                    <div class="btn-group btn-group-sm p-0.5 bg-white rounded-pill border shadow-sm" role="group" aria-label="View Mode">
                        <button type="button" class="btn btn-sm px-3 fw-bold active rounded-pill" id="btnModeMatrix" style="font-size: 11px; transition: all 0.2s; background: #714B67; color: #ffffff; padding: 4px 12px;">
                            <i class="fas fa-th-large me-1"></i> Matrix
                        </button>
                        <button type="button" class="btn btn-sm px-3 fw-bold rounded-pill btn-light text-muted" id="btnModeTable" style="font-size: 11px; transition: all 0.2s; padding: 4px 12px;">
                            <i class="fas fa-table me-1"></i> Table
                        </button>
                    </div>

                    <button type="button" class="close text-secondary ms-1" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 22px; border: none; background: transparent; line-height: 1; cursor: pointer; padding: 0 4px; outline: none;">
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
                    <!-- Top Product Quick Info Bar -->
                    <div class="d-flex justify-content-between align-items-center p-2.5 px-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <!-- Numeric Sky Price (Hidden by default, toggleable) -->
                            <div class="matrix-numeric-price d-none">
                                <span class="text-muted fw-semibold" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">Sky Price</span>
                                <div class="fw-bold font-monospace" id="matrix_item_price" style="font-size: 1.15rem; color: #059669;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-numeric-divider d-none" style="width: 1px; height: 26px; background: #cbd5e1;"></div>

                            <!-- Secret Sky P-Code -->
                            <div>
                                <span class="text-muted fw-semibold" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">Sky P-Code</span>
                                <div class="fw-bold font-monospace" id="matrix_item_pcode" style="font-size: 1.15rem; color: #714B67;">---</div>
                            </div>
                            <div style="width: 1px; height: 26px; background: #cbd5e1;"></div>

                            <!-- Numeric Rot Price (Hidden by default, toggleable) -->
                            <div class="matrix-numeric-price d-none">
                                <span class="text-muted fw-semibold" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">Rot Price.</span>
                                <div class="fw-bold font-monospace" id="matrix_item_rot_price" style="font-size: 1.15rem; color: #0284c7;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-numeric-divider d-none" style="width: 1px; height: 26px; background: #cbd5e1;"></div>

                            <!-- Secret Rot P-Code -->
                            <div>
                                <span class="text-muted fw-semibold" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">Rot P-Code</span>
                                <div class="fw-bold font-monospace" id="matrix_item_rot_pcode" style="font-size: 1.15rem; color: #714B67;">---</div>
                            </div>
                            <div style="width: 1px; height: 26px; background: #cbd5e1;"></div>

                            <!-- Free To Use -->
                            <div>
                                <span class="text-muted fw-semibold" style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px;">Free To Use</span>
                                <div class="fw-bold text-dark font-monospace" id="matrix_stock_display" style="font-size: 1.1rem;">0 Units</div>
                            </div>

                            <!-- Toggle Eye Button to reveal/hide numeric prices -->
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 border rounded-pill ms-1 d-inline-flex align-items-center gap-1 shadow-sm" id="btnToggleMatrixPriceDigits" title="Show/Hide Numeric Prices (Sky Price & Rot Price)" style="background: #ffffff; color: #64748b; font-size: 11px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-eye-slash" id="iconMatrixPriceEye"></i>
                                <span id="textMatrixPriceToggle">Show Prices</span>
                            </button>
                        </div>
                        <div id="matrix_serial_badge_container" class="d-none text-end d-flex flex-column align-items-end justify-content-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2.5 py-1" style="font-size: 12px;" id="matrix_serial_badge"></span>
                            <div id="matrix_location_badge_wrap" class="mt-1 d-none">
                                <span class="badge bg-light text-dark border font-monospace px-2 py-0.5 shadow-sm d-inline-flex align-items-center gap-1" id="matrix_location_badge" style="font-size: 11px;" title="Variant Location">
                                    <i class="fas fa-map-marker-alt text-danger" style="font-size: 10px;"></i>
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
                <div id="modalContentRow" class="table-responsive d-none p-3">
                    <table class="table table-hover table-sm align-middle mb-0 text-center" style="font-size:.84rem;">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="text-start ps-3" style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Variant Name</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Size</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Color</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Stock</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Sky Price</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Sky P-Code</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Cost (Purch)</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Rot Price.</th>
                                <th style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Rot P-Code</th>
                                <th class="text-end pe-3" style="font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#475569;">Barcode</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Single Unified Footer --}}
            <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between align-items-center">
                <div id="matrixSelectedVariantInfo" class="text-muted small font-monospace d-flex align-items-center flex-wrap gap-1">
                    <span class="text-muted"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" data-dismiss="modal" data-bs-dismiss="modal" id="btnMatrixDiscard" style="font-size: 12px;">Close</button>
                    <button type="button" class="btn btn-sm px-4 fw-bold rounded-pill shadow-sm" id="btnMatrixAdd" style="background: #714B67; color: #ffffff; border: none; font-size: 12.5px; min-width: 120px;" disabled>
                        <i class="fas fa-check me-1"></i> Add Variant
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script>
$(document).ready(function () {

    // ── Open Import Modal ──
    $('#openImportModalBtn').on('click', function () {
        $('#importModal').modal('show');
    });

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
        order:      [[3, 'asc']],
        dom:        'rt',
        scrollX:    false,
        columnDefs: [{ targets: [0, 8], orderable: false, searchable: false }]
    });   // DataTable closes here

    // ══════════════════════════════════════════════════════════════
    // QUICKBOOKS POS STYLE: CUSTOMIZE PRODUCT TABLE COLUMNS
    // ══════════════════════════════════════════════════════════════
    const INDEX_COLUMNS = [
        { index: 1, key: 'index_num',     label: '# Number',       default: true },
        { index: 2, key: 'image',         label: 'Image',          default: true },
        { index: 3, key: 'item_details',  label: 'Item Details',   default: true },
        { index: 4, key: 'stock',         label: 'Stock',          default: true },
        { index: 5, key: 'cost_price',    label: 'Purchase Price', default: true },
        { index: 6, key: 'sale_price',    label: 'Sale Price',     default: true },
        { index: 7, key: 'status',        label: 'Status',         default: true },
        { index: 8, key: 'actions',       label: 'Actions',        default: true },
    ];

    const IDX_STORAGE_KEY = 'kent_product_table_columns_v1';

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
        $('#btnModeMatrix').addClass('active').css({'background': '#714B67', 'color': '#ffffff'}).removeClass('btn-light text-muted');
        $('#btnModeTable').removeClass('active btn-primary').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
    });

    $('#btnModeTable').on('click', function() {
        $('#modalMatrixContainer').addClass('d-none');
        $('#modalContentRow').removeClass('d-none');
        $('#btnModeTable').addClass('active btn-primary').removeClass('btn-light text-muted');
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
            $('#btnModeMatrix').addClass('active').css({'background': '#714B67', 'color': '#ffffff'}).removeClass('btn-light text-muted');
            $('#btnModeTable').removeClass('active btn-primary').css({'background': '', 'color': ''}).addClass('btn-light text-muted');
            $('#modalMatrixContainer').removeClass('d-none');
            $('#modalContentRow').addClass('d-none');
        } else {
            $('#btnModeTable').addClass('active btn-primary').removeClass('btn-light text-muted');
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
                $('#view_item_subtext').text(
                    (product.item_code ?? '') + ' | ' +
                    (product.category_relation?.name ?? '') + ' | ' +
                    (product.brand?.name ?? '')
                );

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
        let defaultSkyPCode = product.p_code || (window.encodeToPCode ? window.encodeToPCode(basePrice) : '');
        $('#matrix_item_pcode').text(defaultSkyPCode || '---');
        $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
        let defaultRotPCode = product.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '');
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
                               style="position: static !important; width: 16px; height: 16px; accent-color: #00A09D; cursor: pointer; margin: 0; vertical-align: middle;">
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
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 me-2 font-monospace">
                        <i class="fas fa-check-circle me-1"></i> Ready
                    </span>
                    <span class="fw-bold text-dark font-monospace">${matched.name || matched.variant_name || product.item_name}</span>
                    ${vSerial ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace ms-2">[${vSerial}]</span>` : ''}
                    ${vLocation ? `<span class="badge bg-light text-dark border font-monospace ms-1"><i class="fas fa-map-marker-alt text-danger me-1"></i>${vLocation}</span>` : ''}
                    <span class="badge bg-secondary-subtle text-secondary border font-monospace ms-2">Sky P-Code: ${vSkyPCode}</span>
                    <span class="badge bg-secondary-subtle text-secondary border font-monospace ms-1">Rot P-Code: ${vRotPCode}</span>
                    <span class="text-muted ms-2">(Stock: ${stockQty} ${unit})</span>
                `);

                $('#btnMatrixAdd').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Add Variant');
            } else {
                currentMatchedVariant = null;
                let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
                let baseRotPrice = product.wholesale_price || 0;
                let defaultSkyPCode = product.p_code || (window.encodeToPCode ? window.encodeToPCode(basePrice) : '');
                let defaultRotPCode = product.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '');
                $('#matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
                $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
                $('#matrix_item_pcode').text(defaultSkyPCode || '---');
                $('#matrix_item_rot_pcode').text(defaultRotPCode || '---');
                $('#matrix_serial_badge_container').addClass('d-none');
                $('#matrix_location_badge_wrap').addClass('d-none');
                $('#matrix_location_text').text('---');
                $('#matrixSelectedVariantInfo').html('<span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Combination not available in stock</span>');
                $('#btnMatrixAdd').prop('disabled', true).text('Add');
            }
        } else {
            currentMatchedVariant = null;
            let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
            let baseRotPrice = product.wholesale_price || 0;
            let defaultSkyPCode = product.p_code || (window.encodeToPCode ? window.encodeToPCode(basePrice) : '');
            let defaultRotPCode = product.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '');
            $('#matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
            $('#matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
            $('#matrix_item_pcode').text(defaultSkyPCode || '---');
            $('#matrix_item_rot_pcode').text(defaultRotPCode || '---');
            $('#matrix_serial_badge_container').addClass('d-none');
            $('#matrix_location_badge_wrap').addClass('d-none');
            $('#matrix_location_text').text('---');
            const remainingCount = attrKeys.filter(k => !currentSelectedAttrs[k]).length;
            $('#matrixSelectedVariantInfo').html(`<span class="text-muted"><i class="fas fa-info-circle me-1"></i> Select remaining ${remainingCount} attribute(s) to view exact variant</span>`);
            $('#btnMatrixAdd').prop('disabled', true).text('Add');
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
            confirmButtonColor: '#714B67',
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
            return `<span style="${cls} border-radius:6px; padding:3px 8px; font-size:.78rem; font-weight:600;">${qty}</span>`;
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

                let colorBadge = (vColorVal && vColorVal !== '-') ? `<span style="background:#e2e8f0;border-radius:4px;padding:2px 6px;font-size:.72rem;">${vColorVal}</span>` : '<span style="color:#94a3b8;">—</span>';
                let alertQty  = (vAlert != null && vAlert != 0) ? vAlert : '-';
                
                if (product.size_mode === 'by_kg' && v.conv_factor != 1 && !v.unit) vUnit = 'Pcs';
                let vPriceLabel = product.size_mode === 'by_size' ? '/m²' : '/' + vUnit;

                let vWholesale = (v.wholesale_price !== undefined && v.wholesale_price !== null && v.wholesale_price !== '') ? v.wholesale_price : 0;
                let vSkyPCode = v.sky_p_code || v.p_code || (window.encodeToPCode ? window.encodeToPCode(vSale) : '—');
                let vRotPCode = v.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(vWholesale) : '—');

                tbody.append(`<tr>
                    <td class="text-start ps-4 fw-semibold">${vName}</td>
                    <td>${vSize}</td>
                    <td>${colorBadge}</td>
                    <td>${stockBadgeHtml(vStock, vAlert)}</td>
                    <td class="fw-bold" style="color:#059669;">Rs. ${parseFloat(vSale||0).toFixed(2)} <small class="fw-normal text-muted">${vPriceLabel}</small></td>
                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace px-2 py-0.5 fw-bold" style="font-size:.78rem;">${vSkyPCode || '—'}</span></td>
                    <td class="text-muted">Rs. ${parseFloat(vPurch||0).toFixed(2)} <small>${vPriceLabel}</small></td>
                    <td class="fw-bold" style="color:#0284c7;">Rs. ${parseFloat(vWholesale||0).toFixed(2)} <small class="fw-normal text-muted">${vPriceLabel}</small></td>
                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace px-2 py-0.5 fw-bold" style="font-size:.78rem;">${vRotPCode || '—'}</span></td>
                    <td class="text-end pe-4"><code style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:2px 6px;font-size:.75rem;">${vBarcode}</code></td>
                </tr>`);
            });
        } else {
            colorList.forEach((color, index) => {
                let barcode   = (product.barcode_path ?? product.item_code ?? '') + (index > 0 ? '-' + String(index+1).padStart(2,'0') : '');
                let colorBadge = (color && color !== '-') ? `<span style="background:#e2e8f0;border-radius:4px;padding:2px 6px;font-size:.72rem;">${color}</span>` : '<span style="color:#94a3b8;">—</span>';
                let prodSkyPCode = product.p_code || (window.encodeToPCode ? window.encodeToPCode(salePrice) : '—');
                let prodRotPrice = product.wholesale_price || 0;
                let prodRotPCode = product.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(prodRotPrice) : '—');
                tbody.append(`<tr>
                    <td class="text-start ps-4 fw-semibold">${product.item_name}</td>
                    <td>${sizeStr}</td>
                    <td>${colorBadge}</td>
                    <td>${stockBadgeHtml(stock, product.alert_carton_quantity)}</td>
                    <td class="fw-bold" style="color:#059669;">Rs. ${parseFloat(salePrice||0).toFixed(2)} <small class="fw-normal text-muted">${priceLabel}</small></td>
                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace px-2 py-0.5 fw-bold" style="font-size:.78rem;">${prodSkyPCode || '—'}</span></td>
                    <td class="text-muted">Rs. ${parseFloat(purchPrice||0).toFixed(2)} <small>${priceLabel}</small></td>
                    <td class="fw-bold" style="color:#0284c7;">Rs. ${parseFloat(prodRotPrice||0).toFixed(2)} <small class="fw-normal text-muted">${priceLabel}</small></td>
                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace px-2 py-0.5 fw-bold" style="font-size:.78rem;">${prodRotPCode || '—'}</span></td>
                    <td class="text-end pe-4"><code style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:2px 6px;font-size:.75rem;">${barcode}</code></td>
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
                },
                error: () => Swal.fire('Error', 'Could not update product status.', 'error')
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
        } else {
            $('#subCategorySelect').html('<option value="">Select Sub-Category</option>');
        }
    });

});  // ── end $(document).ready ──
</script>
@endsection
