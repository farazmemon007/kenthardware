@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ── Kent Hardware ERP Design System Tokens ── */
    :root {
        --erp-primary:    #2563eb;
        --erp-primary-lt: #eff6ff;
        --erp-success:    #059669;
        --erp-success-lt: #ecfdf5;
        --erp-warning:    #d97706;
        --erp-warning-lt: #fffbeb;
        --erp-danger:     #dc2626;
        --erp-danger-lt:  #fef2f2;
        --erp-indigo:     #4f46e5;
        --erp-indigo-lt:  #eef2ff;
        --erp-border:     #e2e8f0;
        --erp-bg:         #f8fafc;
        --erp-card-bg:    #ffffff;
        --erp-text:       #0f172a;
        --erp-muted:      #64748b;
        --erp-radius:     12px;
    }

    /* Page Header */
    .stock-header {
        margin-bottom: 22px;
    }
    .stock-header .page-title h4 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--erp-text);
        letter-spacing: -0.3px;
    }

    /* Stat Summary Cards */
    .stat-summary-card {
        border-radius: var(--erp-radius) !important;
        background: #ffffff !important;
        border: 1px solid var(--erp-border) !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08) !important;
    }
    .stat-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bg-blue-50 { background-color: #eff6ff !important; color: #2563eb !important; }
    .bg-emerald-50 { background-color: #ecfdf5 !important; color: #059669 !important; }
    .bg-indigo-50 { background-color: #eef2ff !important; color: #4f46e5 !important; }
    .bg-amber-50 { background-color: #fffbeb !important; color: #d97706 !important; }

    /* Main Container Card */
    .storage-main-card {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: var(--erp-radius);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .storage-main-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--erp-border);
        padding: 14px 20px;
    }

    /* Nav Tabs / Pills (Matching storage_locations theme) */
    .nav-pills-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: none !important;
    }
    .nav-pills-custom .nav-link {
        border-radius: 8px !important;
        padding: 9px 18px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--erp-muted) !important;
        background-color: #f8fafc !important;
        border: 1px solid var(--erp-border) !important;
        transition: all 0.15s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        cursor: pointer !important;
        text-decoration: none !important;
    }
    .nav-pills-custom .nav-link:hover {
        color: var(--erp-text) !important;
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
    .nav-pills-custom .nav-link.active {
        background-color: var(--erp-primary) !important;
        border-color: var(--erp-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25) !important;
    }
    .nav-pills-custom .nav-link.active.shop-tab {
        background-color: var(--erp-indigo) !important;
        border-color: var(--erp-indigo) !important;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25) !important;
    }
    .nav-pills-custom .nav-link.active.entries-tab {
        background-color: #475569 !important;
        border-color: #475569 !important;
        box-shadow: 0 2px 6px rgba(71, 85, 105, 0.25) !important;
    }
    .nav-pills-custom .nav-link .badge-counter {
        background-color: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 8px;
    }
    .nav-pills-custom .nav-link.active .badge-counter {
        background-color: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Tab Panes Visibility */
    .tab-content > .tab-pane {
        display: none;
    }
    .tab-content > .tab-pane.active {
        display: block;
    }

    /* Filter Panel */
    .filter-panel {
        background: #f8fafc;
        border-bottom: 1px solid var(--erp-border);
        padding: 14px 20px;
    }
    .search-wrap {
        position: relative;
    }
    .search-wrap .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--erp-muted);
        font-size: 13px;
        pointer-events: none;
        z-index: 1;
    }
    .search-input-field {
        height: 38px !important;
        padding-left: 36px !important;
        border: 1px solid var(--erp-border) !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        color: var(--erp-text) !important;
        background: #ffffff !important;
        outline: none !important;
        transition: border-color .15s, box-shadow .15s !important;
    }
    .search-input-field:focus {
        border-color: var(--erp-primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    .filter-select-field {
        height: 38px !important;
        border: 1px solid var(--erp-border) !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--erp-text) !important;
        background-color: #ffffff !important;
    }

    .btn-toggle-all {
        height: 38px !important;
        border-radius: 8px !important;
        padding: 0 14px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        border: 1px solid var(--erp-border) !important;
        background: #ffffff !important;
        color: var(--erp-muted) !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
    }
    .btn-toggle-all:hover {
        background: #f1f5f9 !important;
        color: var(--erp-text) !important;
        border-color: #cbd5e1 !important;
    }

    /* Section Banner */
    .warehouse-group-banner {
        background: #f8fafc;
        border: 1px solid var(--erp-border);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 14px;
    }

    /* Rack / Shelf Container Card */
    .storage-box-card {
        border: 1px solid var(--erp-border);
        border-radius: 10px;
        background: #ffffff;
        margin-bottom: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .storage-box-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .storage-box-header {
        padding: 12px 18px;
        background: #fafbfc;
        border-bottom: 1px solid var(--erp-border);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
    }
    .storage-box-header:hover {
        background: #f1f5f9;
    }
    .storage-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--erp-text);
        display: flex;
        align-items: center;
    }
    .transition-icon {
        transition: transform 0.2s ease;
    }

    /* ERP Storage Table (Exact Match to storage_locations) */
    .storage-table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    .storage-table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 10px 14px !important;
        border-top: none !important;
        border-bottom: 1px solid var(--erp-border) !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    .storage-table tbody td {
        padding: 11px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        color: #1e293b !important;
        font-size: 13px !important;
    }
    .storage-table tbody tr:last-child td {
        border-bottom: none !important;
    }
    .storage-table tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Badges & Chips */
    .badge-pill-custom {
        display: inline-flex !important;
        align-items: center !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        white-space: nowrap !important;
    }
    .badge-warehouse {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }
    .badge-shop {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }
    .badge-code {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
        background-color: #f8fafc !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
        padding: 3px 8px !important;
        border-radius: 5px !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
    }
    .badge-zone {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
        padding: 3px 8px !important;
        border-radius: 5px !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
    }

    /* Action Button (Exact Match to storage_locations) */
    .btn-action-edit {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 5px 12px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        line-height: 1.4 !important;
        white-space: nowrap !important;
        cursor: pointer !important;
        text-decoration: none !important;
        background-color: #eff6ff !important;
        border: 1px solid #bfdbfe !important;
        color: #1d4ed8 !important;
        transition: all 0.15s ease-in-out !important;
    }
    .btn-action-edit:hover {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25) !important;
        transform: translateY(-1px) !important;
    }
    .btn-action-delete {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 5px 12px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        line-height: 1.4 !important;
        white-space: nowrap !important;
        cursor: pointer !important;
        text-decoration: none !important;
        background-color: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        color: #dc2626 !important;
        transition: all 0.15s ease-in-out !important;
    }
    .btn-action-delete:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25) !important;
        transform: translateY(-1px) !important;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px !important;
        border: none !important;
        overflow: hidden !important;
    }
    .modal-header {
        background: #ffffff !important;
        border-bottom: 1px solid var(--erp-border) !important;
        padding: 16px 20px !important;
    }
    .modal-header .modal-title {
        color: var(--erp-text) !important;
        font-weight: 800 !important;
        font-size: 16px !important;
    }
    .modal-header .close {
        color: var(--erp-muted) !important;
        opacity: 0.7 !important;
    }
    .modal-header .close:hover {
        opacity: 1 !important;
        color: var(--erp-text) !important;
    }
    .modal-body {
        padding: 20px !important;
        background: #ffffff !important;
    }
    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 6px;
        display: block;
        letter-spacing: 0.4px;
    }
    .big-input {
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 14px;
    }
    .details-card {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 18px;
        display: none;
    }
    .calc-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    .calc-number {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0369a1;
    }
    .select2-container {
        width: 100% !important;
        z-index: 9999;
    }
    .select2-dropdown {
        z-index: 9999;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        display: flex;
        align-items: center;
        border-color: #e2e8f0;
        border-radius: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="container-fluid px-3 px-md-4 py-3">

    <!-- Top Header -->
    <div class="stock-header d-flex justify-content-between align-items-center flex-wrap">
        <div class="page-title col-md-6 p-0">
            <h4 class="font-weight-bold mb-1"><i class="fas fa-boxes text-primary mr-2"></i> Stock Management</h4>
            <p class="text-muted mb-0 small">Overview of inventory across Warehouse Racks and Shop Shelves</p>
        </div>
        <div class="page-btn d-flex justify-content-md-end col-md-6 mt-3 mt-md-0" style="gap: 8px;">
            <a href="{{ route('storage_locations.index') }}" class="btn btn-outline-secondary px-3 py-2 font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                <i class="fas fa-layer-group text-primary mr-1"></i> Racks & Shelves
            </a>
            @can('warehouse.stock.create')
                <button type="button" onclick="openAddModal()" class="btn btn-primary px-3 py-2 shadow-sm font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-plus mr-1"></i> Add Stock Entry
                </button>
            @endcan
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="row mb-4">
        <!-- Warehouse Stock Card -->
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card stat-summary-card">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Warehouse Stock</span>
                        <h3 class="font-weight-bold mb-0 text-primary mt-1" style="font-size: 22px;">{{ number_format($totalWarehouseStock) }} <span class="small font-weight-normal text-muted" style="font-size: 13px;">Pcs</span></h3>
                        <small class="text-muted" style="font-size: 11.5px;">{{ $totalRacksCount }} Active Racks @if($totalWarehouseBoxes > 0) &bull; {{ number_format($totalWarehouseBoxes) }} Boxes @endif</small>
                    </div>
                    <div class="stat-icon-box bg-blue-50">
                        <i class="fas fa-warehouse fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Stock Card -->
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card stat-summary-card">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Shop Stock</span>
                        <h3 class="font-weight-bold mb-0 text-success mt-1" style="font-size: 22px;">{{ number_format($totalShopStock) }} <span class="small font-weight-normal text-muted" style="font-size: 13px;">Pcs</span></h3>
                        <small class="text-muted" style="font-size: 11.5px;">{{ $totalShelvesCount }} Active Shelves @if($totalShopBoxes > 0) &bull; {{ number_format($totalShopBoxes) }} Boxes @endif</small>
                    </div>
                    <div class="stat-icon-box bg-emerald-50">
                        <i class="fas fa-store fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Locations Card -->
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card stat-summary-card">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Storage Locations</span>
                        <h3 class="font-weight-bold mb-0 text-dark mt-1" style="font-size: 22px;">{{ $totalRacksCount + $totalShelvesCount }}</h3>
                        <small class="text-muted" style="font-size: 11.5px;">{{ $totalRacksCount }} Racks &bull; {{ $totalShelvesCount }} Shelves</small>
                    </div>
                    <div class="stat-icon-box bg-indigo-50">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stocked Variants Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-summary-card">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Stocked Items</span>
                        <h3 class="font-weight-bold mb-0 text-warning mt-1" style="font-size: 22px;">{{ number_format($totalStockedVariants) }}</h3>
                        <small class="text-success" style="font-size: 11.5px;"><i class="fas fa-check-circle mr-1"></i>Active in inventory</small>
                    </div>
                    <div class="stat-icon-box bg-amber-50">
                        <i class="fas fa-tags fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container Card with Tabs -->
    <div class="storage-main-card">
        <!-- Tabs Header -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <ul class="nav nav-pills nav-pills-custom" id="stockTabs" role="tablist">
                <!-- Warehouse Tab -->
                <li class="nav-item">
                    <a class="nav-link active" id="tab-warehouse-btn" href="#tab-warehouse" data-toggle="tab" role="tab">
                        <i class="fas fa-warehouse mr-2"></i>Warehouse (Racks)
                        <span class="badge-counter">{{ number_format($totalWarehouseStock) }} Pcs</span>
                    </a>
                </li>
                <!-- Shop Tab -->
                <li class="nav-item">
                    <a class="nav-link shop-tab" id="tab-shop-btn" href="#tab-shop" data-toggle="tab" role="tab">
                        <i class="fas fa-store mr-2"></i>Shop (Shelves)
                        <span class="badge-counter">{{ number_format($totalShopStock) }} Pcs</span>
                    </a>
                </li>
                <!-- All Raw Entries Tab -->
                <li class="nav-item">
                    <a class="nav-link entries-tab" id="tab-entries-btn" href="#tab-entries" data-toggle="tab" role="tab">
                        <i class="fas fa-list mr-2"></i>All Stock Entries
                        <span class="badge-counter">{{ $stocks->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="stockTabsContent">

            <!-- ══════════════════════════════════════════════════ -->
            <!-- TAB 1: WAREHOUSE (RACKS)                           -->
            <!-- ══════════════════════════════════════════════════ -->
            <div class="tab-pane fade show active" id="tab-warehouse" role="tabpanel">
                <!-- Filter Panel -->
                <div class="filter-panel d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <div class="search-wrap" style="width: 320px;">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="warehouseSearchInput" class="form-control search-input-field" placeholder="Search product, variant, barcode, rack...">
                        </div>
                        <select id="warehouseSelectFilter" class="form-control filter-select-field" style="width: 220px;">
                            <option value="all">All Warehouses ({{ count($warehouseGroups) }})</option>
                            @foreach($warehouseGroups as $wg)
                                <option value="{{ $wg['warehouse']->id }}">{{ $wg['warehouse']->warehouse_name }} ({{ count($wg['racks']) }} Racks)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
                        <button type="button" class="btn-toggle-all btn-expand-all" data-tab="tab-warehouse" title="Expand all racks">
                            <i class="fas fa-angle-double-down mr-1 text-primary"></i> Expand All
                        </button>
                        <button type="button" class="btn-toggle-all btn-collapse-all" data-tab="tab-warehouse" title="Collapse all racks">
                            <i class="fas fa-angle-double-up mr-1 text-muted"></i> Collapse All
                        </button>
                    </div>
                </div>

                <div class="p-3 p-md-4">
                    <!-- Warehouse Groups -->
                    @forelse($warehouseGroups as $wg)
                        <div class="warehouse-group-section mb-4" id="wh-group-{{ $wg['warehouse']->id }}">
                            <!-- Warehouse Header Banner -->
                            <div class="warehouse-group-banner d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-box bg-blue-50 mr-3" style="width: 38px; height: 38px; font-size: 16px; border-radius: 8px;">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">{{ $wg['warehouse']->warehouse_name }}</h6>
                                        <span class="text-muted small" style="font-size: 11.5px;">Warehouse Storage Area</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-2 mt-sm-0" style="gap: 8px;">
                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 12px;"><i class="fas fa-layer-group text-primary mr-1"></i> {{ count($wg['racks']) }} Racks</span>
                                    <span class="badge-pill-custom badge-warehouse font-weight-bold"><i class="fas fa-cubes mr-1"></i> {{ number_format($wg['total_pieces']) }} Pcs</span>
                                    @if($wg['total_boxes'] > 0)
                                        <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 12px;">{{ number_format($wg['total_boxes']) }} Boxes</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Racks in this Warehouse -->
                            @if(count($wg['racks']) === 0)
                                <div class="alert alert-light border text-center py-4 text-muted rounded-3">
                                    <i class="fas fa-layer-group fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                    No racks have been created in this warehouse yet.
                                    <div class="mt-2">
                                        <a href="{{ route('storage_locations.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-plus mr-1"></i> Create Rack
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="racks-list">
                                    @foreach($wg['racks'] as $rackData)
                                        @php
                                            $loc = $rackData['location'];
                                            $items = $rackData['items'];
                                            $itemCount = count($items);
                                            $containerId = 'rack-collapse-' . $loc->id;
                                        @endphp
                                        <div class="storage-box-card" data-location-name="{{ strtolower($loc->name . ' ' . $loc->code) }}">
                                            <!-- Rack Header (Click to toggle) -->
                                            <div class="storage-box-header" data-target="#{{ $containerId }}" aria-expanded="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="stat-icon-box bg-blue-50 mr-2" style="width: 32px; height: 32px; font-size: 14px; border-radius: 8px;">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <span class="font-weight-bold text-dark mr-2" style="font-size: 14px;">{{ $loc->name }}</span>
                                                    @if($loc->code)
                                                        <span class="badge-code mr-2">{{ $loc->code }}</span>
                                                    @endif
                                                    @if($loc->zone_or_aisle)
                                                        <span class="badge-zone"><i class="fas fa-map-pin text-info mr-1"></i> {{ $loc->zone_or_aisle }}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 11.5px;">{{ $itemCount }} {{ Str::plural('Variant', $itemCount) }}</span>
                                                    <span class="badge-pill-custom badge-warehouse font-weight-bold" style="font-size: 11.5px;">{{ number_format($rackData['total_pieces']) }} Pcs</span>
                                                    @if($rackData['total_boxes'] > 0)
                                                        <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 11.5px;">{{ number_format($rackData['total_boxes']) }} Boxes</span>
                                                    @endif
                                                    <i class="fas fa-chevron-down text-muted transition-icon ml-2" style="font-size: 12px;"></i>
                                                </div>
                                            </div>

                                            <!-- Rack Items Table (Collapsible) -->
                                            <div id="{{ $containerId }}" class="rack-collapse-body show">
                                                @if($itemCount === 0)
                                                    <div class="p-4 text-center text-muted">
                                                        <i class="fas fa-box-open fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        <span class="small">No products or variants currently assigned to this rack.</span>
                                                    </div>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table storage-table w-100 mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" style="width: 45px;">#</th>
                                                                    <th>Product Name & Code</th>
                                                                    <th>Variant / Specification</th>
                                                                    <th>Serial No / SKU</th>
                                                                    <th>Barcode</th>
                                                                    <th class="text-center">Stock In Rack</th>
                                                                    <th class="text-center" style="width: 100px;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($items as $idx => $item)
                                                                    <tr class="stock-item-row" data-search-text="{{ strtolower($item['product_name'] . ' ' . $item['product_code'] . ' ' . $item['variant_name'] . ' ' . $item['serial_no'] . ' ' . $item['barcode'] . ' ' . $loc->name) }}">
                                                                        <td class="font-weight-bold text-muted text-center">{{ $idx + 1 }}</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                @if($item['product_image'])
                                                                                    <img src="{{ asset('uploads/products/' . $item['product_image']) }}" class="rounded mr-2" style="width: 34px; height: 34px; object-fit: cover; border: 1px solid #e2e8f0;">
                                                                                @else
                                                                                    <div class="rounded mr-2 bg-light d-flex align-items-center justify-content-center text-muted border" style="width: 34px; height: 34px; font-size: 12px;">
                                                                                        <i class="fas fa-box"></i>
                                                                                    </div>
                                                                                @endif
                                                                                <div>
                                                                                    <div class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $item['product_name'] }}</div>
                                                                                    <div class="small text-muted" style="font-family: monospace;">{{ $item['product_code'] }}</div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <span class="font-weight-bold text-dark" style="font-size: 13px;">{{ $item['variant_name'] }}</span>
                                                                            @if(!empty($item['size']))
                                                                                <span class="badge-pill-custom badge-warehouse ml-1" style="font-size: 11px; padding: 2px 7px;">Size: {{ $item['size'] }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge-code">{{ $item['serial_no'] ?: '—' }}</span>
                                                                        </td>
                                                                        <td>
                                                                            @if($item['barcode'])
                                                                                <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-barcode mr-1"></i>{{ $item['barcode'] }}</span>
                                                                            @else
                                                                                <span class="text-muted small">—</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div>
                                                                                <span class="font-weight-bold text-primary" style="font-size: 15px;">{{ number_format($item['stock']) }}</span>
                                                                                <span class="text-muted small font-weight-bold">{{ $item['unit'] }}</span>
                                                                            </div>
                                                                            @if($item['pieces_per_box'] > 0 && $item['stock'] > 0)
                                                                                <div class="small text-muted mt-1" style="font-size: 11px;">
                                                                                    <span class="badge badge-light border text-secondary px-2 py-1">
                                                                                        {{ $item['boxes'] }} Box{{ $item['boxes'] != 1 ? 'es' : '' }}
                                                                                        @if($item['loose'] > 0)
                                                                                            + {{ $item['loose'] }} Loose
                                                                                        @endif
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn-action-edit btn-view-variant-details" title="View Product & Variant Details"
                                                                                data-product-id="{{ $item['product_id'] }}"
                                                                                data-product-name="{{ $item['product_name'] }}"
                                                                                data-product-code="{{ $item['product_code'] }}"
                                                                                data-product-image="{{ $item['product_image'] ? asset('uploads/products/' . $item['product_image']) : '' }}"
                                                                                data-variant-name="{{ $item['variant_name'] }}"
                                                                                data-size="{{ $item['size'] }}"
                                                                                data-color="{{ $item['color'] }}"
                                                                                data-serial-no="{{ $item['serial_no'] }}"
                                                                                data-barcode="{{ $item['barcode'] }}"
                                                                                data-stock="{{ $item['stock'] }}"
                                                                                data-boxes="{{ $item['boxes'] }}"
                                                                                data-loose="{{ $item['loose'] }}"
                                                                                data-ppb="{{ $item['pieces_per_box'] }}"
                                                                                data-unit="{{ $item['unit'] }}"
                                                                                data-location-type="warehouse_rack"
                                                                                data-location-name="{{ $loc->name }}"
                                                                                data-location-code="{{ $loc->code ?: '' }}"
                                                                                data-location-zone="{{ $loc->zone_or_aisle ?: '' }}"
                                                                                data-facility="{{ $wg['warehouse']->warehouse_name }}"
                                                                                data-edit-url="{{ route('products.edit', $item['product_id']) }}">
                                                                                <i class="fas fa-eye mr-1"></i> View
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-warehouse fa-3x mb-3 text-secondary opacity-50"></i>
                            <h6 class="font-weight-bold text-dark">No Warehouses Configured</h6>
                            <p class="small text-muted">Add warehouses and racks to start tracking storage inventory.</p>
                        </div>
                    @endforelse

                    <!-- Unassigned Warehouse Items (if any) -->
                    @if(count($unassignedWarehouseItems) > 0)
                        <div class="warehouse-group-section mt-4 pt-3 border-top" id="wh-group-unassigned">
                            <div class="warehouse-group-banner d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-box bg-amber-50 mr-3" style="width: 38px; height: 38px; font-size: 16px; border-radius: 8px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Other / Unassigned Warehouse Items</h6>
                                        <span class="text-muted small" style="font-size: 11.5px;">Products with unlinked or general warehouse location text</span>
                                    </div>
                                </div>
                                <span class="badge badge-light border text-dark px-2 py-1 font-weight-bold">{{ count($unassignedWarehouseItems) }} Items</span>
                            </div>
                            <div class="storage-box-card">
                                <div class="table-responsive">
                                    <table class="table storage-table w-100 mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 45px;">#</th>
                                                <th>Product Name</th>
                                                <th>Variant</th>
                                                <th>Serial No</th>
                                                <th>Saved Location String</th>
                                                <th class="text-center">Stock</th>
                                                <th class="text-center" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unassignedWarehouseItems as $idx => $item)
                                                <tr class="stock-item-row" data-search-text="{{ strtolower($item['product_name'] . ' ' . $item['product_code'] . ' ' . $item['variant_name'] . ' ' . $item['location_str']) }}">
                                                    <td class="font-weight-bold text-muted text-center">{{ $idx + 1 }}</td>
                                                    <td class="font-weight-bold text-dark">{{ $item['product_name'] }}</td>
                                                    <td><span class="badge badge-light text-dark border">{{ $item['variant_name'] }}</span></td>
                                                    <td><span class="badge-code">{{ $item['serial_no'] ?: '—' }}</span></td>
                                                    <td>
                                                        @if(!empty($item['location_str']))
                                                            <span class="badge badge-light border text-muted">{{ $item['location_str'] }}</span>
                                                        @else
                                                            <span class="text-muted small fst-italic">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center font-weight-bold text-primary">{{ number_format($item['stock']) }} {{ $item['unit'] }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn-action-edit btn-view-variant-details" title="View Product & Variant Details"
                                                            data-product-id="{{ $item['product_id'] }}"
                                                            data-product-name="{{ $item['product_name'] }}"
                                                            data-product-code="{{ $item['product_code'] }}"
                                                            data-product-image="{{ $item['product_image'] ? asset('uploads/products/' . $item['product_image']) : '' }}"
                                                            data-variant-name="{{ $item['variant_name'] }}"
                                                            data-size="{{ $item['size'] }}"
                                                            data-color="{{ $item['color'] }}"
                                                            data-serial-no="{{ $item['serial_no'] }}"
                                                            data-barcode="{{ $item['barcode'] }}"
                                                            data-stock="{{ $item['stock'] }}"
                                                            data-boxes="{{ $item['boxes'] }}"
                                                            data-loose="{{ $item['loose'] }}"
                                                            data-ppb="{{ $item['pieces_per_box'] }}"
                                                            data-unit="{{ $item['unit'] }}"
                                                            data-location-type="unassigned_warehouse"
                                                            data-location-name="{{ $item['location_str'] ?: 'Unassigned Warehouse' }}"
                                                            data-location-code=""
                                                            data-location-zone=""
                                                            data-facility="Warehouse"
                                                            data-edit-url="{{ route('products.edit', $item['product_id']) }}">
                                                            <i class="fas fa-eye mr-1"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════ -->
            <!-- TAB 2: SHOP (SHELVES)                              -->
            <!-- ══════════════════════════════════════════════════ -->
            <div class="tab-pane fade" id="tab-shop" role="tabpanel">
                <!-- Filter Panel -->
                <div class="filter-panel d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <div class="search-wrap" style="width: 320px;">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="shopSearchInput" class="form-control search-input-field" placeholder="Search product, variant, barcode, shelf...">
                        </div>
                        <select id="shopSelectFilter" class="form-control filter-select-field" style="width: 220px;">
                            <option value="all">All Shops ({{ count($shopGroups) }})</option>
                            @foreach($shopGroups as $sg)
                                <option value="{{ $sg['branch']->id }}">{{ $sg['branch']->name }} ({{ count($sg['shelves']) }} Shelves)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
                        <button type="button" class="btn-toggle-all btn-expand-all" data-tab="tab-shop" title="Expand all shelves">
                            <i class="fas fa-angle-double-down mr-1 text-primary"></i> Expand All
                        </button>
                        <button type="button" class="btn-toggle-all btn-collapse-all" data-tab="tab-shop" title="Collapse all shelves">
                            <i class="fas fa-angle-double-up mr-1 text-muted"></i> Collapse All
                        </button>
                    </div>
                </div>

                <div class="p-3 p-md-4">
                    <!-- Shop Groups -->
                    @forelse($shopGroups as $sg)
                        <div class="shop-group-section mb-4" id="shop-group-{{ $sg['branch']->id }}">
                            <!-- Shop Header Banner -->
                            <div class="warehouse-group-banner d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-box bg-emerald-50 mr-3" style="width: 38px; height: 38px; font-size: 16px; border-radius: 8px;">
                                        <i class="fas fa-store text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">{{ $sg['branch']->name }}</h6>
                                        <span class="text-muted small" style="font-size: 11.5px;">Shop Retail & Storage Area</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-2 mt-sm-0" style="gap: 8px;">
                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 12px;"><i class="fas fa-cubes text-success mr-1"></i> {{ count($sg['shelves']) }} Shelves</span>
                                    <span class="badge-pill-custom badge-shop font-weight-bold"><i class="fas fa-boxes mr-1"></i> {{ number_format($sg['total_pieces']) }} Pcs</span>
                                    @if($sg['total_boxes'] > 0)
                                        <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 12px;">{{ number_format($sg['total_boxes']) }} Boxes</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Shelves in this Shop -->
                            @if(count($sg['shelves']) === 0)
                                <div class="alert alert-light border text-center py-4 text-muted rounded-3">
                                    <i class="fas fa-cubes fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                    No shelves have been created for this shop yet.
                                    <div class="mt-2">
                                        <a href="{{ route('storage_locations.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-plus mr-1"></i> Create Shelf
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="shelves-list">
                                    @foreach($sg['shelves'] as $shelfData)
                                        @php
                                            $loc = $shelfData['location'];
                                            $items = $shelfData['items'];
                                            $itemCount = count($items);
                                            $containerId = 'shelf-collapse-' . $loc->id;
                                        @endphp
                                        <div class="storage-box-card" data-location-name="{{ strtolower($loc->name . ' ' . $loc->code) }}">
                                            <!-- Shelf Header (Click to toggle) -->
                                            <div class="storage-box-header" data-target="#{{ $containerId }}" aria-expanded="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="stat-icon-box bg-emerald-50 mr-2" style="width: 32px; height: 32px; font-size: 14px; border-radius: 8px;">
                                                        <i class="fas fa-cubes text-success"></i>
                                                    </div>
                                                    <span class="font-weight-bold text-dark mr-2" style="font-size: 14px;">{{ $loc->name }}</span>
                                                    @if($loc->code)
                                                        <span class="badge-code mr-2">{{ $loc->code }}</span>
                                                    @endif
                                                    @if($loc->zone_or_aisle)
                                                        <span class="badge-zone"><i class="fas fa-map-pin text-info mr-1"></i> {{ $loc->zone_or_aisle }}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 11.5px;">{{ $itemCount }} {{ Str::plural('Variant', $itemCount) }}</span>
                                                    <span class="badge-pill-custom badge-shop font-weight-bold" style="font-size: 11.5px;">{{ number_format($shelfData['total_pieces']) }} Pcs</span>
                                                    @if($shelfData['total_boxes'] > 0)
                                                        <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 11.5px;">{{ number_format($shelfData['total_boxes']) }} Boxes</span>
                                                    @endif
                                                    <i class="fas fa-chevron-down text-muted transition-icon ml-2" style="font-size: 12px;"></i>
                                                </div>
                                            </div>

                                            <!-- Shelf Items Table (Collapsible) -->
                                            <div id="{{ $containerId }}" class="rack-collapse-body show">
                                                @if($itemCount === 0)
                                                    <div class="p-4 text-center text-muted">
                                                        <i class="fas fa-box-open fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        <span class="small">No products or variants currently placed on this shelf.</span>
                                                    </div>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table storage-table w-100 mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" style="width: 45px;">#</th>
                                                                    <th>Product Name & Code</th>
                                                                    <th>Variant / Specification</th>
                                                                    <th>Serial No / SKU</th>
                                                                    <th>Barcode</th>
                                                                    <th class="text-center">Stock On Shelf</th>
                                                                    <th class="text-center" style="width: 100px;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($items as $idx => $item)
                                                                    <tr class="stock-item-row" data-search-text="{{ strtolower($item['product_name'] . ' ' . $item['product_code'] . ' ' . $item['variant_name'] . ' ' . $item['serial_no'] . ' ' . $item['barcode'] . ' ' . $loc->name) }}">
                                                                        <td class="font-weight-bold text-muted text-center">{{ $idx + 1 }}</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                @if($item['product_image'])
                                                                                    <img src="{{ asset('uploads/products/' . $item['product_image']) }}" class="rounded mr-2" style="width: 34px; height: 34px; object-fit: cover; border: 1px solid #e2e8f0;">
                                                                                @else
                                                                                    <div class="rounded mr-2 bg-light d-flex align-items-center justify-content-center text-muted border" style="width: 34px; height: 34px; font-size: 12px;">
                                                                                        <i class="fas fa-box"></i>
                                                                                    </div>
                                                                                @endif
                                                                                <div>
                                                                                    <div class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $item['product_name'] }}</div>
                                                                                    <div class="small text-muted" style="font-family: monospace;">{{ $item['product_code'] }}</div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <span class="font-weight-bold text-dark" style="font-size: 13px;">{{ $item['variant_name'] }}</span>
                                                                            @if(!empty($item['size']))
                                                                                <span class="badge-pill-custom badge-shop ml-1" style="font-size: 11px; padding: 2px 7px;">Size: {{ $item['size'] }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge-code">{{ $item['serial_no'] ?: '—' }}</span>
                                                                        </td>
                                                                        <td>
                                                                            @if($item['barcode'])
                                                                                <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-barcode mr-1"></i>{{ $item['barcode'] }}</span>
                                                                            @else
                                                                                <span class="text-muted small">—</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div>
                                                                                <span class="font-weight-bold text-success" style="font-size: 15px;">{{ number_format($item['stock']) }}</span>
                                                                                <span class="text-muted small font-weight-bold">{{ $item['unit'] }}</span>
                                                                            </div>
                                                                            @if($item['pieces_per_box'] > 0 && $item['stock'] > 0)
                                                                                <div class="small text-muted mt-1" style="font-size: 11px;">
                                                                                    <span class="badge badge-light border text-secondary px-2 py-1">
                                                                                        {{ $item['boxes'] }} Box{{ $item['boxes'] != 1 ? 'es' : '' }}
                                                                                        @if($item['loose'] > 0)
                                                                                            + {{ $item['loose'] }} Loose
                                                                                        @endif
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn-action-edit btn-view-variant-details" title="View Product & Variant Details"
                                                                                data-product-id="{{ $item['product_id'] }}"
                                                                                data-product-name="{{ $item['product_name'] }}"
                                                                                data-product-code="{{ $item['product_code'] }}"
                                                                                data-product-image="{{ $item['product_image'] ? asset('uploads/products/' . $item['product_image']) : '' }}"
                                                                                data-variant-name="{{ $item['variant_name'] }}"
                                                                                data-size="{{ $item['size'] }}"
                                                                                data-color="{{ $item['color'] }}"
                                                                                data-serial-no="{{ $item['serial_no'] }}"
                                                                                data-barcode="{{ $item['barcode'] }}"
                                                                                data-stock="{{ $item['stock'] }}"
                                                                                data-boxes="{{ $item['boxes'] }}"
                                                                                data-loose="{{ $item['loose'] }}"
                                                                                data-ppb="{{ $item['pieces_per_box'] }}"
                                                                                data-unit="{{ $item['unit'] }}"
                                                                                data-location-type="shop_shelf"
                                                                                data-location-name="{{ $loc->name }}"
                                                                                data-location-code="{{ $loc->code ?: '' }}"
                                                                                data-location-zone="{{ $loc->zone_or_aisle ?: '' }}"
                                                                                data-facility="{{ $sg['branch']->name }}"
                                                                                data-edit-url="{{ route('products.edit', $item['product_id']) }}">
                                                                                <i class="fas fa-eye mr-1"></i> View
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-store fa-3x mb-3 text-secondary opacity-50"></i>
                            <h6 class="font-weight-bold text-dark">No Shops Configured</h6>
                            <p class="small text-muted">Add shop branches and shelves to start tracking shop inventory.</p>
                        </div>
                    @endforelse

                    <!-- Unassigned Shop Items (if any) -->
                    @if(count($unassignedShopItems) > 0)
                        <div class="shop-group-section mt-4 pt-3 border-top" id="shop-group-unassigned">
                            <div class="warehouse-group-banner d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-box bg-amber-50 mr-3" style="width: 38px; height: 38px; font-size: 16px; border-radius: 8px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Other / Unassigned Shop Items</h6>
                                        <span class="text-muted small" style="font-size: 11.5px;">Products with unlinked or general shop shelf location text</span>
                                    </div>
                                </div>
                                <span class="badge badge-light border text-dark px-2 py-1 font-weight-bold">{{ count($unassignedShopItems) }} Items</span>
                            </div>
                            <div class="storage-box-card">
                                <div class="table-responsive">
                                    <table class="table storage-table w-100 mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 45px;">#</th>
                                                <th>Product Name</th>
                                                <th>Variant</th>
                                                <th>Serial No</th>
                                                <th>Saved Location String</th>
                                                <th class="text-center">Stock</th>
                                                <th class="text-center" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unassignedShopItems as $idx => $item)
                                                <tr class="stock-item-row" data-search-text="{{ strtolower($item['product_name'] . ' ' . $item['product_code'] . ' ' . $item['variant_name'] . ' ' . $item['location_str']) }}">
                                                    <td class="font-weight-bold text-muted text-center">{{ $idx + 1 }}</td>
                                                    <td class="font-weight-bold text-dark">{{ $item['product_name'] }}</td>
                                                    <td><span class="badge badge-light text-dark border">{{ $item['variant_name'] }}</span></td>
                                                    <td><span class="badge-code">{{ $item['serial_no'] ?: '—' }}</span></td>
                                                    <td>
                                                        @if(!empty($item['location_str']))
                                                            <span class="badge badge-light border text-muted">{{ $item['location_str'] }}</span>
                                                        @else
                                                            <span class="text-muted small fst-italic">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center font-weight-bold text-success">{{ number_format($item['stock']) }} {{ $item['unit'] }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn-action-edit btn-view-variant-details" title="View Product & Variant Details"
                                                            data-product-id="{{ $item['product_id'] }}"
                                                            data-product-name="{{ $item['product_name'] }}"
                                                            data-product-code="{{ $item['product_code'] }}"
                                                            data-product-image="{{ $item['product_image'] ? asset('uploads/products/' . $item['product_image']) : '' }}"
                                                            data-variant-name="{{ $item['variant_name'] }}"
                                                            data-size="{{ $item['size'] }}"
                                                            data-color="{{ $item['color'] }}"
                                                            data-serial-no="{{ $item['serial_no'] }}"
                                                            data-barcode="{{ $item['barcode'] }}"
                                                            data-stock="{{ $item['stock'] }}"
                                                            data-boxes="{{ $item['boxes'] }}"
                                                            data-loose="{{ $item['loose'] }}"
                                                            data-ppb="{{ $item['pieces_per_box'] }}"
                                                            data-unit="{{ $item['unit'] }}"
                                                            data-location-type="unassigned_shop"
                                                            data-location-name="{{ $item['location_str'] ?: 'Unassigned Shop Shelf' }}"
                                                            data-location-code=""
                                                            data-location-zone=""
                                                            data-facility="Shop"
                                                            data-edit-url="{{ route('products.edit', $item['product_id']) }}">
                                                            <i class="fas fa-eye mr-1"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════ -->
            <!-- TAB 3: ALL STOCK ENTRIES (EXISTING TABLE & CRUD)   -->
            <!-- ══════════════════════════════════════════════════ -->
            <div class="tab-pane fade" id="tab-entries" role="tabpanel">
                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">Raw Warehouse Stock Records</h6>
                            <p class="text-muted mb-0 small">Direct warehouse stock balance records with quick add, update, and delete actions</p>
                        </div>
                        @can('warehouse.stock.create')
                            <button type="button" onclick="openAddModal()" class="btn btn-sm btn-primary px-3 shadow-sm font-weight-bold" style="border-radius: 6px;">
                                <i class="fas fa-plus mr-1"></i> Add Stock Entry
                            </button>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table storage-table w-100" id="stockTable">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 45px;">#</th>
                                    <th>Warehouse</th>
                                    <th>Product</th>
                                    <th class="text-center">Total Box</th>
                                    <th class="text-center">Total Pieces</th>
                                    <th class="text-center" style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stocks as $stock)
                                    <tr>
                                        <td class="font-weight-bold text-muted text-center">{{ $stocks->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="badge-pill-custom badge-warehouse">
                                                <i class="fas fa-warehouse mr-1 text-primary"></i>{{ $stock->warehouse->warehouse_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($stock->product && $stock->product->image)
                                                    <img src="{{ asset('uploads/products/' . $stock->product->image) }}"
                                                        class="rounded mr-2"
                                                        style="width: 34px; height: 34px; object-fit: cover; border: 1px solid #e2e8f0;">
                                                @else
                                                    <div class="rounded mr-2 bg-light d-flex align-items-center justify-content-center text-muted border"
                                                        style="width: 34px; height: 34px; font-size: 12px;">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $stock->product->item_name ?? 'N/A' }}</div>
                                                    <div class="small text-muted" style="font-family: monospace;">{{ $stock->product->item_code ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <span class="badge badge-light border text-secondary font-weight-bold px-2 py-1">{{ number_format($stock->quantity) }} Boxes</span>
                                        </td>
                                        <td class="text-center font-weight-bold text-primary" style="font-size: 15px;">
                                            {{ number_format($stock->total_pieces) }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                                @can('warehouse.stock.edit')
                                                    <button type="button" onclick="editStock({{ $stock->id }})"
                                                        class="btn-action-edit" title="Edit Stock">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </button>
                                                @endcan

                                                <form action="{{ route('warehouse_stocks.destroy', $stock->id) }}" method="POST"
                                                    class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this stock entry?');">
                                                    @csrf @method('DELETE')
                                                    @can('warehouse.stock.delete')
                                                        <button type="submit" class="btn-action-delete" title="Delete Stock">
                                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                                        </button>
                                                    @endcan
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No warehouse stock records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $stocks->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Smart Modal (Add & Edit Stock) -->
<div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title mb-0" id="modalTitle">
                    <i class="fas fa-boxes text-primary mr-2"></i> Add Stock Entry
                </h5>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; background: transparent; border: none; cursor: pointer;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="stockForm">
                    @csrf
                    <input type="hidden" id="stock_id" name="stock_id">
                    <input type="hidden" id="method_field" name="_method" value="POST">

                    <div class="row">
                        <!-- Warehouse (Col 1) -->
                        <div class="col-md-6 mb-3">
                            <label class="info-label">Warehouse <span class="text-danger">*</span></label>
                            <select id="warehouse_id" name="warehouse_id" class="form-control filter-select-field" required>
                                <option value="">Select Warehouse</option>
                            </select>
                        </div>

                        <!-- Product (Col 2) -->
                        <div class="col-md-6 mb-3">
                            <label class="info-label">Product <span class="text-danger">*</span></label>
                            <select id="product_id" name="product_id" class="form-control filter-select-field" disabled>
                                <option value="">Select Warehouse First</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Info Card -->
                    <div id="product-details" class="details-card mt-1">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <img id="prod-img" src=""
                                    style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; display: none; border: 1px solid #e2e8f0;">
                                <div id="prod-icon" class="text-secondary"><i class="fas fa-box fa-2x"></i></div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-dark" id="prod-name" style="font-size: 14px;">--</div>
                                <div class="d-flex small text-muted mt-1" style="gap: 15px;">
                                    <span>Code: <b id="prod-code" class="text-dark">--</b></span>
                                    <span>PPB: <b id="prod-ppb" class="text-primary">--</b></span>
                                    <span>Current: <b id="prod-current" class="text-success">--</b></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Input -->
                    <div class="row mt-3 align-items-end">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="info-label" id="qty-label">Total Pieces <span class="text-danger">*</span></label>
                            <input type="number" name="quantity_input" id="quantity_input"
                                class="form-control search-input-field" placeholder="0" min="0" disabled style="height: 42px !important; font-size: 15px !important; font-weight: 700 !important;">
                            <small class="text-muted" id="qty-help">Enter amount in pieces.</small>
                        </div>
                        <div class="col-md-6">
                            <div class="calc-box">
                                <div class="text-uppercase small text-muted font-weight-bold mb-1">Calculated Boxes</div>
                                <div class="calc-number" id="calc-result">0 Pcs</div>
                                <input type="hidden" name="total_pieces" id="total_pieces" value="0">
                                <input type="hidden" name="total_box" id="total_box" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="info-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="2" placeholder="Optional notes..." style="border-radius: 8px; border-color: #e2e8f0; font-size: 13px;"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" id="btn-save" style="border-radius: 8px; font-size: 14px;">
                            <span class="btn-text"><i class="fas fa-save mr-1"></i> Save Stock Entry</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════ -->
<!-- MODAL: PRODUCT & VARIANT DETAILS MODAL             -->
<!-- ══════════════════════════════════════════════════ -->
<div class="modal fade" id="variantDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 860px;">
        <div class="modal-content" style="border-radius: var(--erp-radius); border: 1px solid var(--erp-border); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden;">
            <!-- Header -->
            <div class="modal-header d-flex justify-content-between align-items-center" style="background: #ffffff; border-bottom: 1px solid var(--erp-border); padding: 16px 22px;">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box bg-blue-50 mr-3" style="width: 38px; height: 38px; border-radius: 8px;">
                        <i class="fas fa-cube text-primary" style="font-size: 16px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-dark mb-0" style="font-size: 16px; letter-spacing: -0.2px;">Product &amp; Variant Details</h5>
                        <div class="text-muted small mt-0" style="font-size: 12px;" id="modalLocationBreadcrumb">Storage Location Information</div>
                    </div>
                </div>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; background: transparent; border: none; cursor: pointer; opacity: 0.7;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4" style="background: #f8fafc; max-height: calc(85vh - 120px); overflow-y: auto;">
                <!-- Main Product & Variant Info Card -->
                <div class="bg-white p-3 rounded mb-3 border" style="border-color: var(--erp-border) !important; box-shadow: 0 1px 3px rgba(15,23,42,0.03);">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div id="modalProdImgWrap" style="width: 72px; height: 72px; border-radius: 10px; border: 1px solid var(--erp-border); background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img id="modalProdImg" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <i id="modalProdIcon" class="fas fa-box fa-2x text-secondary opacity-50"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <h5 class="font-weight-bold text-dark mb-0" id="modalProdName" style="font-size: 17px;">--</h5>
                                <span class="badge-code" id="modalProdCode">--</span>
                                <span id="modalFacilityBadge" class="badge-pill-custom badge-warehouse font-weight-bold">Warehouse</span>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-muted small mt-2" style="gap: 14px; font-size: 12.5px;">
                                <span><i class="fas fa-tags mr-1 text-secondary"></i> Category: <b id="modalCategory" class="text-dark">--</b></span>
                                <span><i class="fas fa-sitemap mr-1 text-secondary"></i> Sub-Category: <b id="modalSubCategory" class="text-dark">--</b></span>
                                <span><i class="fas fa-trademark mr-1 text-secondary"></i> Brand: <b id="modalBrand" class="text-dark">--</b></span>
                                <span><i class="fas fa-balance-scale mr-1 text-secondary"></i> Base Unit: <b id="modalBaseUnit" class="text-dark">--</b></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid: 1. Current Storage & Stock Breakdown | 2. Pricing & Master Inventory -->
                <div class="row">
                    <!-- Storage & Stock in this Location -->
                    <div class="col-md-6 mb-3">
                        <div class="bg-white p-3 rounded h-100 border" style="border-color: var(--erp-border) !important; box-shadow: 0 1px 3px rgba(15,23,42,0.03);">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <span class="font-weight-bold text-dark" style="font-size: 13px;">
                                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Current Storage Location
                                </span>
                                <span class="badge-zone" id="modalLocationTypeBadge">Rack</span>
                            </div>
                            <table class="table table-sm table-borderless mb-0" style="font-size: 12.5px;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted py-1" style="width: 40%;">Location Name:</td>
                                        <td class="font-weight-bold text-dark py-1" id="modalLocName">--</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Location Code:</td>
                                        <td class="py-1"><span class="badge-code" id="modalLocCode">--</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Zone / Aisle:</td>
                                        <td class="py-1"><span class="badge-zone" id="modalLocZone">--</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Facility / Branch:</td>
                                        <td class="font-weight-bold text-dark py-1" id="modalLocFacility">--</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-3 pt-2 border-top">
                                <div class="text-muted small font-weight-bold mb-1 text-uppercase" style="font-size: 11px;">Stock In This Location</div>
                                <div class="d-flex align-items-baseline">
                                    <span class="font-weight-bold text-primary mr-2" id="modalStockNumber" style="font-size: 24px; line-height: 1;">0</span>
                                    <span class="text-muted font-weight-bold" id="modalStockUnit" style="font-size: 14px;">Pcs</span>
                                </div>
                                <div class="mt-2" id="modalBoxBreakdownWrap">
                                    <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" id="modalBoxBreakdown" style="font-size: 12px;">--</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Variant Specs & Pricing -->
                    <div class="col-md-6 mb-3">
                        <div class="bg-white p-3 rounded h-100 border" style="border-color: var(--erp-border) !important; box-shadow: 0 1px 3px rgba(15,23,42,0.03);">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <span class="font-weight-bold text-dark" style="font-size: 13px;">
                                    <i class="fas fa-info-circle text-info mr-1"></i> Variant Specifications
                                </span>
                                <span class="badge badge-primary px-2 py-1" id="modalVariantBadge">Standard</span>
                            </div>
                            <table class="table table-sm table-borderless mb-0" style="font-size: 12.5px;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted py-1" style="width: 40%;">Size / Color:</td>
                                        <td class="font-weight-bold text-dark py-1" id="modalVariantSizeColor">--</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Serial No / SKU:</td>
                                        <td class="py-1"><span class="badge-code" id="modalVariantSerial">--</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Barcode:</td>
                                        <td class="py-1" id="modalVariantBarcode">--</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1">Pieces Per Box:</td>
                                        <td class="font-weight-bold text-dark py-1" id="modalVariantPPB">--</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-3 pt-2 border-top">
                                <div class="text-muted small font-weight-bold mb-1 text-uppercase" style="font-size: 11px;">Master Catalog Pricing &amp; Stock</div>
                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 12.5px;">
                                    <span class="text-muted">Sale Price / Pc:</span>
                                    <span class="font-weight-bold text-success" id="modalSalePrice">--</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 12.5px;">
                                    <span class="text-muted">Wholesale Price:</span>
                                    <span class="font-weight-bold text-dark" id="modalWholesalePrice">--</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 12.5px;">
                                    <span class="text-muted">Purchase / Cost:</span>
                                    <span class="font-weight-bold text-muted" id="modalPurchasePrice">--</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-top mt-1" style="font-size: 12.5px;">
                                    <span class="text-muted">Total Catalog Stock:</span>
                                    <span class="font-weight-bold text-primary" id="modalTotalStock">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: All Product Variants Breakdown -->
                <div class="bg-white rounded border overflow-hidden" style="border-color: var(--erp-border) !important; box-shadow: 0 1px 3px rgba(15,23,42,0.03);">
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="background: #fafbfc;">
                        <span class="font-weight-bold text-dark" style="font-size: 13px;">
                            <i class="fas fa-th-list mr-1 text-secondary"></i> All Variants for this Product
                        </span>
                        <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" id="modalVariantsCount">Loading...</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table storage-table w-100 mb-0" id="modalVariantsTable">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>Variant Name</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Serial No</th>
                                    <th>Barcode</th>
                                    <th>Saved Location</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody id="modalVariantsTableBody">
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Loading all variants...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-between align-items-center" style="background: #ffffff; border-top: 1px solid var(--erp-border); padding: 12px 22px;">
                <a id="modalEditProductLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary px-3 font-weight-bold" style="border-radius: 6px;">
                    <i class="fas fa-external-link-alt mr-1"></i> Open Product in Catalog
                </a>
                <button type="button" class="btn btn-sm btn-secondary px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 6px;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- Select2 -->
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/vendors/select2/js/select2.min.js') }}"></script>

    <script>
        // Global Variables
        var piecesPerBox = 0;
        var selectedProduct = null;
        var isEditMode = false;

        // ── 1. Tab Switching (Direct jQuery Handler) ──
        $(document).on('click', '.nav-pills-custom .nav-link', function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            if (!targetId || targetId.indexOf('#') !== 0) return;

            // Toggle active on tabs
            $('.nav-pills-custom .nav-link').removeClass('active');
            $(this).addClass('active');

            // Toggle active on panes
            $('#stockTabsContent > .tab-pane').removeClass('show active').css('display', 'none');
            $(targetId).addClass('show active').css('display', 'block');
        });

        // ── 2. Expand All & Collapse All Handlers ──
        $(document).on('click', '.btn-expand-all', function(e) {
            e.preventDefault();
            var tabId = $(this).attr('data-tab') || 'tab-warehouse';
            var $tab = $('#' + tabId);
            $tab.find('.rack-collapse-body').slideDown(200).addClass('show');
            $tab.find('.storage-box-header').attr('aria-expanded', 'true');
            $tab.find('.transition-icon').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        });

        $(document).on('click', '.btn-collapse-all', function(e) {
            e.preventDefault();
            var tabId = $(this).attr('data-tab') || 'tab-warehouse';
            var $tab = $('#' + tabId);
            $tab.find('.rack-collapse-body').slideUp(200).removeClass('show');
            $tab.find('.storage-box-header').attr('aria-expanded', 'false');
            $tab.find('.transition-icon').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        });

        window.toggleAllContainers = function(tabId, expand) {
            if (expand) {
                $('.btn-expand-all[data-tab="' + tabId + '"]').trigger('click');
            } else {
                $('.btn-collapse-all[data-tab="' + tabId + '"]').trigger('click');
            }
        };

        // ── 3. Rack & Shelf Card Header Accordion Click ──
        $(document).on('click', '.storage-box-header', function(e) {
            var targetId = $(this).attr('data-target') || $(this).data('target');
            if (!targetId) return;

            var $target = $(targetId);
            var $icon = $(this).find('.transition-icon');
            var $header = $(this);

            if ($target.is(':visible')) {
                $target.slideUp(200, function() {
                    $target.removeClass('show');
                    $header.attr('aria-expanded', 'false');
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                });
            } else {
                $target.slideDown(200, function() {
                    $target.addClass('show');
                    $header.attr('aria-expanded', 'true');
                    $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                });
            }
        });

        // ── 4. Dropdown Filters (Warehouse & Shop) ──
        $(document).on('change', '#warehouseSelectFilter', function() {
            var selectedId = $(this).val();
            if (selectedId === 'all') {
                $('.warehouse-group-section').show();
            } else {
                $('.warehouse-group-section').hide();
                $('#wh-group-' + selectedId).show();
            }
        });

        $(document).on('change', '#shopSelectFilter', function() {
            var selectedId = $(this).val();
            if (selectedId === 'all') {
                $('.shop-group-section').show();
            } else {
                $('.shop-group-section').hide();
                $('#shop-group-' + selectedId).show();
            }
        });

        // ── 5. Live Search Filtering ──
        $(document).on('keyup input', '#warehouseSearchInput', function() {
            var term = $(this).val().toLowerCase().trim();
            filterStockTab('tab-warehouse', term);
        });

        $(document).on('keyup input', '#shopSearchInput', function() {
            var term = $(this).val().toLowerCase().trim();
            filterStockTab('tab-shop', term);
        });

        window.filterStockTab = function(tabId, term) {
            var $tab = $('#' + tabId);
            if (!term) {
                $tab.find('.storage-box-card').show();
                $tab.find('.stock-item-row').show();
                $tab.find('.warehouse-group-section, .shop-group-section').show();
                return;
            }

            $tab.find('.storage-box-card').each(function() {
                var $card = $(this);
                var locName = ($card.attr('data-location-name') || '').toLowerCase();
                var $rows = $card.find('.stock-item-row');
                var matchedRows = 0;

                $rows.each(function() {
                    var $row = $(this);
                    var searchTxt = ($row.attr('data-search-text') || '').toLowerCase();
                    if (searchTxt.indexOf(term) !== -1 || locName.indexOf(term) !== -1) {
                        $row.show();
                        matchedRows++;
                    } else {
                        $row.hide();
                    }
                });

                if (matchedRows > 0 || locName.indexOf(term) !== -1) {
                    $card.show();
                    $card.find('.rack-collapse-body').slideDown(150).addClass('show');
                    $card.find('.transition-icon').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                } else {
                    $card.hide();
                }
            });

            // Auto-hide sections with no matching cards
            $tab.find('.warehouse-group-section, .shop-group-section').each(function() {
                var visibleCards = $(this).find('.storage-box-card:visible').length;
                if (visibleCards > 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        };

        // ── 5b. Variant Details Modal Handler ──
        $(document).on('click', '.btn-view-variant-details', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var productId = $btn.data('product-id');
            var productName = $btn.data('product-name') || '';
            var productCode = $btn.data('product-code') || '';
            var productImage = $btn.data('product-image') || '';
            var variantName = $btn.data('variant-name') || '';
            var size = $btn.data('size') || '';
            var color = $btn.data('color') || '';
            var serialNo = $btn.data('serial-no') || '';
            var barcode = $btn.data('barcode') || '';
            var stock = parseFloat($btn.data('stock')) || 0;
            var boxes = parseInt($btn.data('boxes')) || 0;
            var loose = parseFloat($btn.data('loose')) || 0;
            var ppb = parseInt($btn.data('ppb')) || 0;
            var unit = $btn.data('unit') || 'Pcs';
            var locType = $btn.data('location-type') || '';
            var locName = $btn.data('location-name') || '';
            var locCode = $btn.data('location-code') || '';
            var locZone = $btn.data('location-zone') || '';
            var facility = $btn.data('facility') || '';
            var editUrl = $btn.data('edit-url') || '#';

            // 1. Populate Product Information Immediately
            $('#modalProdName').text(productName);
            $('#modalProdCode').text(productCode || '—');

            if (productImage) {
                $('#modalProdImg').attr('src', productImage).show();
                $('#modalProdIcon').hide();
            } else {
                $('#modalProdImg').hide();
                $('#modalProdIcon').show();
            }

            var isShop = locType.indexOf('shop') !== -1;
            var facilityLabel = facility || (isShop ? 'Shop' : 'Warehouse');
            $('#modalFacilityBadge')
                .text(facilityLabel)
                .removeClass('badge-warehouse badge-shop')
                .addClass(isShop ? 'badge-shop' : 'badge-warehouse');

            var breadcrumbText = facilityLabel + ' › ' + (locName || 'Storage');
            if (locZone) {
                breadcrumbText += ' (' + locZone + ')';
            }
            $('#modalLocationBreadcrumb').text(breadcrumbText);

            // 2. Populate Location Card
            $('#modalLocationTypeBadge').text(isShop ? 'Shop Shelf' : 'Warehouse Rack');
            $('#modalLocName').text(locName || '—');
            $('#modalLocCode').text(locCode || '—');
            $('#modalLocZone').text(locZone || '—');
            $('#modalLocFacility').text(facilityLabel);

            // Stock in Location
            $('#modalStockNumber')
                .text(stock.toLocaleString())
                .removeClass('text-primary text-success')
                .addClass(isShop ? 'text-success' : 'text-primary');
            $('#modalStockUnit').text(unit);

            if (ppb > 0 && stock > 0) {
                var boxText = boxes + ' Box' + (boxes !== 1 ? 'es' : '');
                if (loose > 0) {
                    boxText += ' + ' + loose + ' Loose';
                }
                boxText += ' (PPB: ' + ppb + ')';
                $('#modalBoxBreakdown').text(boxText).show();
            } else if (ppb > 0) {
                $('#modalBoxBreakdown').text('0 Boxes (PPB: ' + ppb + ')').show();
            } else {
                $('#modalBoxBreakdown').text('Single Unit / Loose Item').show();
            }

            // 3. Variant Details Card
            $('#modalVariantBadge').text(variantName || 'Standard');
            var sizeColorParts = [];
            if (size) sizeColorParts.push('Size: ' + size);
            if (color) sizeColorParts.push('Color: ' + color);
            $('#modalVariantSizeColor').text(sizeColorParts.join(' | ') || '—');
            $('#modalVariantSerial').text(serialNo || '—');

            if (barcode) {
                $('#modalVariantBarcode').html('<span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-barcode mr-1"></i>' + barcode + '</span>');
            } else {
                $('#modalVariantBarcode').html('<span class="text-muted small">—</span>');
            }

            $('#modalVariantPPB').text(ppb > 0 ? (ppb + ' ' + unit + ' / Box') : ('1 ' + unit));
            $('#modalEditProductLink').attr('href', editUrl);

            // 4. Set Placeholders for Catalog Details
            $('#modalCategory').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalSubCategory').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalBrand').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalBaseUnit').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalSalePrice').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalWholesalePrice').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalPurchasePrice').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalTotalStock').html('<span class="text-muted font-italic">Loading...</span>');
            $('#modalVariantsCount').text('Loading...');
            $('#modalVariantsTableBody').html('<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Fetching master details &amp; variant list...</td></tr>');

            // 5. Open Modal
            $('#variantDetailsModal').modal('show');

            // 6. Fetch Master Product Info via Ajax
            $.get('/productview/' + productId, function(res) {
                if (!res || res.error) {
                    $('#modalVariantsTableBody').html('<tr><td colspan="8" class="text-center py-3 text-muted">No additional catalog details found.</td></tr>');
                    return;
                }

                // Master Info
                $('#modalCategory').text((res.category_relation && res.category_relation.name) ? res.category_relation.name : '—');
                $('#modalSubCategory').text((res.sub_category_relation && res.sub_category_relation.name) ? res.sub_category_relation.name : '—');
                $('#modalBrand').text((res.brand && res.brand.name) ? res.brand.name : '—');
                $('#modalBaseUnit').text((res.unit && res.unit.name) ? res.unit.name : unit);

                var salePrice = parseFloat(res.price_per_piece) || 0;
                var wholesalePrice = parseFloat(res.whole_sale_price_per_piece) || 0;
                var purchPrice = parseFloat(res.purchase_price_per_piece) || 0;

                $('#modalSalePrice').text(salePrice > 0 ? ('Rs. ' + salePrice.toLocaleString()) : '—');
                $('#modalWholesalePrice').text(wholesalePrice > 0 ? ('Rs. ' + wholesalePrice.toLocaleString()) : '—');
                $('#modalPurchasePrice').text(purchPrice > 0 ? ('Rs. ' + purchPrice.toLocaleString()) : '—');

                var totalPiecesAll = (res.calculated_total_stock_qty !== undefined) ? parseFloat(res.calculated_total_stock_qty) : (parseFloat(res.stock) || 0);
                var baseUnitName = (res.unit && res.unit.name) ? res.unit.name : unit;
                $('#modalTotalStock').text(totalPiecesAll.toLocaleString() + ' ' + baseUnitName);

                // Variants Breakdown Table
                var variants = [];
                if (Array.isArray(res.color)) {
                    variants = res.color;
                } else if (typeof res.color === 'string') {
                    try { variants = JSON.parse(res.color); } catch(err) { variants = []; }
                }

                if (variants && variants.length > 0) {
                    $('#modalVariantsCount').text(variants.length + ' ' + (variants.length === 1 ? 'Variant' : 'Variants'));
                    var rowsHtml = '';
                    $.each(variants, function(i, v) {
                        var vName = v.name || v.color || 'Standard';
                        var vSize = v.size || '—';
                        var vColor = v.color || '—';
                        var vSerial = v.serial_no || '—';
                        var vBarcode = v.barcode ? ('<span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-barcode mr-1"></i>' + v.barcode + '</span>') : '<span class="text-muted small">—</span>';
                        var vLoc = v.location || '—';
                        var vStock = (v.stock !== undefined) ? parseFloat(v.stock) : (parseFloat(v.current_stock) || 0);
                        var vUnit = v.unit || baseUnitName;

                        // Highlight current variant row
                        var isSelected = (serialNo && vSerial === serialNo) ||
                                         (barcode && v.barcode === barcode) ||
                                         (variantName && vName.toLowerCase() === variantName.toLowerCase());

                        var rowClass = isSelected ? 'style="background-color: #eff6ff; font-weight: 600;"' : '';
                        var highlightBadge = isSelected ? ' <span class="badge badge-primary ml-1" style="font-size: 10px;">Viewing</span>' : '';

                        rowsHtml += '<tr ' + rowClass + '>';
                        rowsHtml += '<td class="text-center text-muted font-weight-bold">' + (i + 1) + '</td>';
                        rowsHtml += '<td><span class="text-dark">' + vName + '</span>' + highlightBadge + '</td>';
                        rowsHtml += '<td>' + (vSize !== '—' ? ('<span class="badge badge-light border">' + vSize + '</span>') : '—') + '</td>';
                        rowsHtml += '<td>' + vColor + '</td>';
                        rowsHtml += '<td>' + (vSerial !== '—' ? ('<span class="badge-code">' + vSerial + '</span>') : '—') + '</td>';
                        rowsHtml += '<td>' + vBarcode + '</td>';
                        rowsHtml += '<td>' + (vLoc !== '—' ? ('<span class="badge badge-light border text-secondary">' + vLoc + '</span>') : '<span class="text-muted small">Not set</span>') + '</td>';
                        rowsHtml += '<td class="text-center font-weight-bold text-primary">' + vStock.toLocaleString() + ' ' + vUnit + '</td>';
                        rowsHtml += '</tr>';
                    });
                    $('#modalVariantsTableBody').html(rowsHtml);
                } else {
                    $('#modalVariantsCount').text('1 Variant');
                    $('#modalVariantsTableBody').html('<tr>' +
                        '<td class="text-center text-muted">1</td>' +
                        '<td>' + (variantName || 'Standard') + ' <span class="badge badge-primary ml-1" style="font-size: 10px;">Viewing</span></td>' +
                        '<td>' + (size ? '<span class="badge badge-light border">' + size + '</span>' : '—') + '</td>' +
                        '<td>' + (color || '—') + '</td>' +
                        '<td>' + (serialNo ? '<span class="badge-code">' + serialNo + '</span>' : '—') + '</td>' +
                        '<td>' + (barcode ? '<span class="badge badge-light border text-muted px-2 py-1">' + barcode + '</span>' : '—') + '</td>' +
                        '<td>' + (locName || '—') + '</td>' +
                        '<td class="text-center font-weight-bold text-primary">' + stock.toLocaleString() + ' ' + unit + '</td>' +
                    '</tr>');
                }
            }).fail(function() {
                $('#modalCategory').text('—');
                $('#modalSubCategory').text('—');
                $('#modalBrand').text('—');
                $('#modalBaseUnit').text(unit);
                $('#modalSalePrice').text('—');
                $('#modalWholesalePrice').text('—');
                $('#modalPurchasePrice').text('—');
                $('#modalTotalStock').text(stock.toLocaleString() + ' ' + unit);
                $('#modalVariantsCount').text('1');
                $('#modalVariantsTableBody').html('<tr><td colspan="8" class="text-center py-3 text-muted">Could not load additional variants from catalog.</td></tr>');
            });
        });

        // ── 6. Select2 & Form Calculation Logic ──
        $(document).ready(function() {
            initSelect2();

            // Clear modal when hidden
            $('#stockModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // Select2 product select
            $('#product_id').on('select2:select', function(e) {
                handleProductSelect(e.params.data);
            });

            // Warehouse Change logic
            $('#warehouse_id').on('change', function() {
                if (selectedProduct && $(this).val()) {
                    fetchCurrentStock($(this).val(), selectedProduct.id);
                }
            });

            $('#product_id').prop('disabled', false);

            $('#quantity_input').on('input', function() {
                calculateTotal();
            });
        });

        function initSelect2() {
            $('#warehouse_id').select2({
                dropdownParent: $('#stockModal'),
                placeholder: "Select Warehouse",
                allowClear: true,
                ajax: {
                    url: "{{ route('warehouse_stock.search-warehouses') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return { results: data };
                    }
                }
            });

            $('#product_id').select2({
                dropdownParent: $('#stockModal'),
                placeholder: "Select Product",
                allowClear: true,
                ajax: {
                    url: "{{ route('warehouse_stock.search-products') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return { results: data };
                    }
                }
            });
        }

        // ── 7. Add & Edit Stock Modals ──
        window.openAddModal = function() {
            isEditMode = false;
            $('#modalTitle').html('<i class="fas fa-plus text-primary mr-2"></i> Add Stock Entry');
            $('#method_field').val('POST');
            $('#btn-save .btn-text').html('<i class="fas fa-save mr-1"></i> Save Stock Entry');
            resetForm();
            $('#stockModal').modal('show');
        };

        window.editStock = function(id) {
            isEditMode = true;
            $('#modalTitle').html('<i class="fas fa-edit text-primary mr-2"></i> Update Stock Entry');
            $('#method_field').val('PUT');
            $('#btn-save .btn-text').html('<i class="fas fa-save mr-1"></i> Update Stock Entry');
            $('#stock_id').val(id);

            var url = "{{ route('warehouse_stock.edit-data', ':id') }}".replace(':id', id);

            $.get(url, function(data) {
                var whOption = new Option(data.warehouse_name, data.warehouse_id, true, true);
                $('#warehouse_id').empty().append(whOption).trigger('change');

                var option = new Option(data.product_name, data.product_id, true, true);
                $('#product_id').empty().append(option).trigger('change');

                var mockData = {
                    id: data.product_id,
                    item_name: data.product_name,
                    item_code: data.product_code,
                    pieces_per_box: data.pieces_per_box,
                    image: data.image
                };
                handleProductSelect(mockData);

                $('#quantity_input').val(data.total_pieces);
                $('#total_pieces').val(data.total_pieces);

                if (data.pieces_per_box > 0 && data.total_pieces > 0) {
                    var boxes = Math.floor(data.total_pieces / data.pieces_per_box);
                    var loose = data.total_pieces % data.pieces_per_box;
                    $('#calc-result').html('<span class="text-primary font-weight-bold">' + boxes + ' Boxes</span>' + (loose > 0 ? ' <span class="text-muted text-small">+ ' + loose + ' Loose</span>' : ''));
                    $('#total_box').val(boxes);
                } else {
                    $('#calc-result').text(data.total_pieces + ' Pcs');
                    $('#total_box').val(0);
                }

                $('#remarks').val(data.remarks);
                $('#stockModal').modal('show');
            }).fail(function() {
                Swal.fire('Error', 'Could not fetch stock data.', 'error');
            });
        };

        function handleProductSelect(data) {
            selectedProduct = data;
            piecesPerBox = parseInt(data.pieces_per_box) || 0;

            $('#product-details').fadeIn();
            $('#prod-name').text(data.item_name);
            $('#prod-code').text(data.item_code || '--');
            $('#prod-ppb').text(piecesPerBox > 0 ? piecesPerBox : 'N/A (Loose)');

            if (data.image) {
                $('#prod-img').attr('src', data.image).show();
                $('#prod-icon').hide();
            } else {
                $('#prod-img').hide();
                $('#prod-icon').show();
            }

            $('#quantity_input').prop('disabled', false).focus();
            $('#qty-label').html('Total Pieces <span class="text-danger">*</span>');
            $('#qty-help').text('Enter total quantity in pieces.');

            var whId = $('#warehouse_id').val();
            if (whId) {
                fetchCurrentStock(whId, data.id);
            } else {
                $('#prod-current').text('Select Warehouse');
            }

            calculateTotal();
        }

        function fetchCurrentStock(warehouseId, productId) {
            if (!isEditMode) {
                $.get("{{ route('warehouse_stock.get-stock') }}", {
                    warehouse_id: warehouseId,
                    product_id: productId
                }, function(res) {
                    var currentTotal = parseInt(res.total_pieces) || 0;
                    var displayStock = currentTotal + " Pcs";

                    if (piecesPerBox > 0) {
                        var b = Math.floor(currentTotal / piecesPerBox);
                        var l = currentTotal % piecesPerBox;
                        displayStock = b + " Boxes" + (l > 0 ? " + " + l + " Loose" : "");
                    }
                    $('#prod-current').text(displayStock);
                });
            } else {
                var currentTotal = parseInt($('#total_pieces').val()) || 0;
                var displayStock = currentTotal + " Pcs (Current Rec)";

                if (piecesPerBox > 0) {
                    var b = Math.floor(currentTotal / piecesPerBox);
                    var l = currentTotal % piecesPerBox;
                    displayStock = b + " Boxes" + (l > 0 ? " + " + l + " Loose" : "") + " (Current Rec)";
                }
                $('#prod-current').text(displayStock);
            }
        }

        function calculateTotal() {
            var inputQty = parseInt($('#quantity_input').val()) || 0;
            var total = inputQty;

            $('#total_pieces').val(total);

            if (piecesPerBox > 0 && total > 0) {
                var boxes = Math.floor(total / piecesPerBox);
                var loose = total % piecesPerBox;
                $('#calc-result').html('<span class="text-primary font-weight-bold">' + boxes + ' Boxes</span>' + (loose > 0 ? ' <span class="text-muted text-small">+ ' + loose + ' Loose</span>' : ''));
                $('#total_box').val(boxes);
            } else {
                $('#calc-result').text(total + ' Pcs');
                $('#total_box').val(0);
            }
        }

        function resetForm() {
            selectedProduct = null;
            piecesPerBox = 0;

            if ($('#stockForm').length) {
                $('#stockForm')[0].reset();
            }
            $('#warehouse_id').val(null).trigger('change');
            $('#product_id').val(null).trigger('change');
            $('#product-details').hide();
            $('#quantity_input').prop('disabled', true);
            $('#calc-result').text('0 Pcs');
            $('#stock_id').val('');
        }

        // Form Submission
        $('#stockForm').on('submit', function(e) {
            e.preventDefault();

            var url = "{{ route('warehouse_stocks.store') }}";
            if (isEditMode) {
                var id = $('#stock_id').val();
                url = "{{ route('warehouse_stocks.update', ':id') }}".replace(':id', id);
            }

            var formData = $(this).serialize();
            var $btn = $('#btn-save');
            $btn.prop('disabled', true);

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var msg = 'Error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire('Error', msg, 'error');
                    $btn.prop('disabled', false);
                }
            });
        });
    </script>
@endsection
