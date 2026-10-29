@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "INVENTORY ON-HAND REPORT" THEME
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
    .qb-input {
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
        max-height: calc(100vh - 225px);
        min-height: 380px;
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

    .acc-badge-unique {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        background-color: #1d4ed8 !important;
        border: 1px solid #1e40af !important;
        border-radius: 3px !important;
        padding: 2px 7px !important;
        display: inline-block;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .qb-table tbody tr:hover td .acc-badge-unique {
        background-color: #1e40af !important;
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

    @php
        $totalProducts = count($rows);
        $totalOnhandQty = $rows->sum('onhand_qty');
        $inStockCount = $rows->where('onhand_qty', '>', 0)->count();
        $outOfStockCount = $rows->where('onhand_qty', '<=', 0)->count();
    @endphp

    {{-- 1. Top Window Title Bar --}}
    <div class="qb-win-header no-print">
        <div class="qb-win-title">
            <i class="fas fa-warehouse"></i>
            <span>Inventory On-Hand Report</span>
            <span class="qb-win-sub d-none d-sm-inline">| Warehouse Stock Levels</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-gray btnExportExcel" title="Export to CSV / Excel">
                <i class="fa-solid fa-file-excel text-success"></i>
                <span class="d-none d-sm-inline">Excel</span>
            </button>
            <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
            <a href="{{ route('product') }}" class="qb-btn-blue text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>
                <span class="d-none d-sm-inline">Products</span>
            </a>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Stock Status KPIs (Desktop) --}}
    <div class="qb-sub-ribbon no-print d-none d-md-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-secondary"><i class="fas fa-boxes me-1"></i>Total Items:</span>
                <span class="chip-val text-dark">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-info"><i class="fas fa-cubes me-1"></i>Total On-Hand Qty:</span>
                <span class="chip-val text-primary">{{ rtrim(rtrim(number_format($totalOnhandQty, 3, '.', ''), '0'), '.') }}</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-success"><i class="fas fa-check-circle me-1"></i>In Stock:</span>
                <span class="chip-val text-success">{{ number_format($inStockCount) }}</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fas fa-times-circle me-1"></i>Out of Stock:</span>
                <span class="chip-val text-danger">{{ number_format($outOfStockCount) }}</span>
            </div>
        </div>
        <div class="text-muted small fw-bold">
            <i class="fa-solid fa-layer-group me-1 text-primary"></i> Current Stock Balances
        </div>
    </div>

    {{-- Mobile Summary Cards --}}
    <div class="row g-2 mb-1 d-md-none no-print px-1">
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-muted">Total Items</span>
                <div class="mob-metric-val text-dark mt-1">{{ number_format($totalProducts) }}</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-primary">On-Hand Qty</span>
                <div class="mob-metric-val text-primary mt-1">{{ rtrim(rtrim(number_format($totalOnhandQty, 3, '.', ''), '0'), '.') }}</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-success">In Stock</span>
                <div class="mob-metric-val text-success mt-1">{{ number_format($inStockCount) }}</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-danger">Out of Stock</span>
                <div class="mob-metric-val text-danger mt-1">{{ number_format($outOfStockCount) }}</div>
            </div>
        </div>
    </div>

    {{-- 3. Filter Bar (Desktop) --}}
    <div class="qb-filter-bar no-print d-none d-md-block">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 450px;">
                <span class="qb-flabel">Filter / Search:</span>
                <div class="position-relative flex-grow-1">
                    <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 11px;"></i>
                    <input type="text" class="qb-input searchOnhandInput w-100" placeholder="Type Code, Unique #, Item Name, Brand or UOM…" style="padding-left: 28px !important;">
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button type="button" class="qb-btn-reset btnResetTrigger">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Bar (Mobile) --}}
    <div class="card border-0 shadow-sm mb-2 no-print d-md-none p-2" style="border-radius: 6px; background: #e8f0f8;">
        <div class="row g-2">
            <div class="col-12">
                <span class="qb-flabel">Search Inventory</span>
                <input type="text" class="form-control form-control-sm searchOnhandInput" placeholder="Search Code / Unique # / Name / Brand…" style="font-size: 11.5px;">
            </div>
            <div class="col-12 d-flex gap-1 mt-1">
                <button type="button" class="qb-btn-reset flex-fill justify-content-center btnResetTrigger">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Search
                </button>
            </div>
        </div>
    </div>

    {{-- 4. Main Data Table Card --}}
    <div class="qb-table-card d-none d-md-block">
        <div class="sale-table-wrap">
            <table class="qb-table table-bordered align-middle" id="onhandTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 45px;">#</th>
                        <th style="width: 125px;">CODE</th>
                        <th class="text-center" style="width: 140px;" title="Unique Variant / Item No">UNIQUE VARIANT NO</th>
                        <th>ITEM NAME</th>
                        <th style="width: 170px;">BRAND</th>
                        <th style="width: 100px;">UOM</th>
                        <th class="text-end" style="width: 150px;">ON-HAND QTY</th>
                        <th class="text-center" style="width: 120px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $r)
                        @php
                            $qtyVal = rtrim(rtrim(number_format($r->onhand_qty, 3, '.', ''), '0'), '.');
                            $qtyNum = (float)$r->onhand_qty;

                            // Variant / Unique item numbers resolution
                            $variants = !empty($r->color) ? (is_string($r->color) ? json_decode($r->color, true) : $r->color) : [];
                            $itemNumbers = [];
                            if (is_array($variants)) {
                                foreach ($variants as $v) {
                                    $num = trim((string)($v['item_number'] ?? ($v['item_no'] ?? '')));
                                    if ($num !== '' && !in_array($num, $itemNumbers)) {
                                        $itemNumbers[] = $num;
                                    }
                                }
                            }
                            if (empty($itemNumbers) && !empty($r->item_number)) {
                                $itemNumbers[] = trim((string)$r->item_number);
                            }
                            $primaryNum = !empty($itemNumbers) ? $itemNumbers[0] : (!empty($r->item_number) ? trim((string)$r->item_number) : '');
                            $extraCount = count($itemNumbers) > 1 ? (count($itemNumbers) - 1) : 0;
                            $allNumsTitle = !empty($itemNumbers) ? ('Unique Variant Nos: #' . implode(', #', $itemNumbers)) : '';
                            $searchVariants = implode(' ', array_map(function($n){ return '#' . ltrim($n, '#') . ' ' . ltrim($n, '#'); }, $itemNumbers));
                        @endphp
                        <tr data-search="{{ strtolower($r->item_code . ' ' . $searchVariants . ' ' . $r->item_name . ' ' . $r->brand_name . ' ' . $r->unit_name) }}">
                            <td class="text-center text-muted fw-bold">{{ $i + 1 }}</td>
                            <td><span class="acc-badge-code">{{ $r->item_code }}</span></td>
                            <td class="text-center">
                                @if(!empty($primaryNum))
                                    <span class="acc-badge-unique" title="{{ $allNumsTitle }}">#{{ ltrim($primaryNum, '#') }}</span>
                                    @if($extraCount > 0)
                                        <span class="badge bg-light text-secondary border ms-1" style="font-size: 9.5px; padding: 2px 4px; vertical-align: middle;" title="{{ $allNumsTitle }}">+{{ $extraCount }}</span>
                                    @endif
                                @else
                                    <span class="text-muted tabular-nums">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $r->item_name }}</td>
                            <td class="text-muted fw-semibold" style="font-size: 12px;">{{ $r->brand_name ?? '-' }}</td>
                            <td><span class="badge" style="background: #e2e8f0; color: #334155; border: 1px solid #cbd5e1; font-size: 11px;">{{ $r->unit_name ?? '-' }}</span></td>
                            <td class="text-end fw-bold qb-mono {{ $qtyNum > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $qtyVal }}
                            </td>
                            <td class="text-center">
                                @if($r->is_part)
                                    <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; font-size:11px; font-weight:700;">Part</span>
                                @elseif($r->is_assembled)
                                    <span class="badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; font-size:11px; font-weight:700;">Assembled</span>
                                @else
                                    <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; font-size:11px; font-weight:700;">Simple</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No Inventory Data Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARDS CONTAINER (d-md-none) --}}
    <div class="d-md-none" id="onhandMobileContainer">
        @forelse($rows as $i => $r)
            @php
                $qtyVal = rtrim(rtrim(number_format($r->onhand_qty, 3, '.', ''), '0'), '.');
                $qtyNum = (float)$r->onhand_qty;

                $variants = !empty($r->color) ? (is_string($r->color) ? json_decode($r->color, true) : $r->color) : [];
                $itemNumbers = [];
                if (is_array($variants)) {
                    foreach ($variants as $v) {
                        $num = trim((string)($v['item_number'] ?? ($v['item_no'] ?? '')));
                        if ($num !== '' && !in_array($num, $itemNumbers)) {
                            $itemNumbers[] = $num;
                        }
                    }
                }
                if (empty($itemNumbers) && !empty($r->item_number)) {
                    $itemNumbers[] = trim((string)$r->item_number);
                }
                $primaryNum = !empty($itemNumbers) ? $itemNumbers[0] : (!empty($r->item_number) ? trim((string)$r->item_number) : '');
                $extraCount = count($itemNumbers) > 1 ? (count($itemNumbers) - 1) : 0;
                $allNumsTitle = !empty($itemNumbers) ? ('Unique Variant Nos: #' . implode(', #', $itemNumbers)) : '';
                $searchVariants = implode(' ', array_map(function($n){ return '#' . ltrim($n, '#') . ' ' . ltrim($n, '#'); }, $itemNumbers));
            @endphp
            <div class="mob-card p-2 mb-2 mob-onhand-card" data-search="{{ strtolower($r->item_code . ' ' . $searchVariants . ' ' . $r->item_name . ' ' . $r->brand_name . ' ' . $r->unit_name) }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span class="acc-badge-code" style="font-size: 11px;">{{ $r->item_code }}</span>
                        @if(!empty($primaryNum))
                            <span class="acc-badge-unique" style="font-size: 10px; padding: 1px 5px;" title="{{ $allNumsTitle }}">#{{ ltrim($primaryNum, '#') }}</span>
                            @if($extraCount > 0)
                                <span class="badge bg-light text-secondary border" style="font-size: 9px; padding: 1px 3px;" title="{{ $allNumsTitle }}">+{{ $extraCount }}</span>
                            @endif
                        @endif
                        <strong class="text-dark ms-1" style="font-size: 13px;">{{ $r->item_name }}</strong>
                    </div>
                    <div>
                        @if($r->is_part)
                            <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; font-size:10px;">Part</span>
                        @elseif($r->is_assembled)
                            <span class="badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; font-size:10px;">Assembled</span>
                        @else
                            <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; font-size:10px;">Simple</span>
                        @endif
                    </div>
                </div>
                <div class="mb-1">
                    <small class="text-muted" style="font-size: 11px;">Brand: {{ $r->brand_name ?? '-' }} | UOM: {{ $r->unit_name ?? '-' }}</small>
                </div>
                <div class="border-top pt-2 mt-1">
                    <div class="d-flex justify-content-between align-items-center" style="font-size: 11.5px;">
                        <span class="text-muted fw-bold">On-Hand Quantity:</span>
                        <strong class="qb-mono {{ $qtyNum > 0 ? 'text-success' : 'text-danger' }}" style="font-size: 13px;">{{ $qtyVal }} {{ $r->unit_name }}</strong>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-3 text-center py-4 bg-white">
                <div class="card-body py-4 text-muted">
                    <i class="fas fa-folder-open fa-2x mb-2 text-secondary"></i>
                    <p class="small fw-bold mb-0">No Inventory Data Found</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        
        // Real-time Search Handler for Desktop & Mobile
        $('.searchOnhandInput').on('input', function() {
            let val = $(this).val().toLowerCase().trim();
            $('.searchOnhandInput').val($(this).val()); // sync input value

            // Desktop Table Filter
            $('#onhandTable tbody tr').each(function() {
                let searchStr = $(this).attr('data-search') || $(this).text().toLowerCase();
                $(this).toggle(searchStr.indexOf(val) > -1);
            });

            // Mobile Cards Filter
            $('.mob-onhand-card').each(function() {
                let searchStr = $(this).attr('data-search') || $(this).text().toLowerCase();
                $(this).toggle(searchStr.indexOf(val) > -1);
            });
        });

        // Reset Handler
        $('.btnResetTrigger').on('click', function() {
            $('.searchOnhandInput').val('').trigger('input');
        });

        // Print Handler
        $('.btnPrintReport').on('click', function() {
            window.print();
        });

        // Export Excel CSV Handler
        $('.btnExportExcel').on('click', function() {
            let csv = [];
            csv.push(['#', 'Code', 'Unique Variant No', 'Item Name', 'Brand', 'UOM', 'On-Hand Qty', 'Status'].join(','));
            
            $('#onhandTable tbody tr').each(function() {
                if ($(this).is(':visible')) {
                    let row = [];
                    $(this).find('td').each(function(idx) {
                        let text = $(this).text().trim().replace(/,/g, '').replace(/\n/g, ' ');
                        row.push('"' + text + '"');
                    });
                    if (row.length > 1) csv.push(row.join(','));
                }
            });

            let blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Inventory_Onhand_Report.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

    });
</script>
@endsection
