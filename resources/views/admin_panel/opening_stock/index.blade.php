@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ── Kent Hardware ERP Opening Stock & Matrix Design Tokens (Odoo Enterprise Standard) ── */
    :root {
        --erp-primary:        #2563eb;
        --erp-primary-hover:  #1d4ed8;
        --erp-primary-lt:     #eff6ff;
        --erp-success:        #059669;
        --erp-success-lt:     #ecfdf5;
        --erp-warning:        #d97706;
        --erp-warning-lt:     #fffbeb;
        --erp-danger:         #dc2626;
        --erp-danger-lt:      #fef2f2;
        --erp-border:         #e2e8f0;
        --erp-border-dark:    #cbd5e1;
        --erp-bg:             #f8fafc;
        --erp-card-bg:        #ffffff;
        --erp-text:           #0f172a;
        --erp-muted:          #64748b;
        --erp-radius:         8px;
    }

    /* Container: strictly contained within viewport, ZERO whole-page vertical scroll */
    .opening-stock-wrapper {
        height: calc(100vh - 65px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #f8fafc;
        padding: 10px 18px;
        box-sizing: border-box;
    }

    /* Top Navigation & Action Header */
    .op-top-bar {
        flex-shrink: 0;
        margin-bottom: 8px;
    }
    .op-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--erp-text);
        letter-spacing: -0.2px;
    }
    .op-wh-pill {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0 10px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.15s ease;
    }
    .op-wh-pill:hover, .op-wh-pill:focus-within {
        border-color: var(--erp-primary);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }
    .op-header-select {
        border: none;
        outline: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        padding: 2px 4px;
        min-width: 130px;
        max-width: 200px;
    }

    /* Odoo Unified Filter Ribbon (Sleek & Seamless) */
    .op-stat-ribbon {
        flex-shrink: 0;
        margin-bottom: 8px;
    }
    .op-odoo-filter-bar {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        padding: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }
    .op-filter-tab {
        flex: 1;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 5px;
        padding: 5px 12px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
        outline: none;
    }
    .op-filter-tab:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .op-filter-tab.active {
        background: #eff6ff !important;
        border-color: #bfdbfe !important;
        color: #1e40af !important;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.1) !important;
    }
    .op-tab-label {
        font-weight: 600;
        letter-spacing: 0.1px;
    }
    .op-tab-count {
        font-size: 11px;
        font-weight: 700;
        padding: 1.5px 7px;
        border-radius: 10px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .op-count-primary { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .op-count-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .op-count-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .op-count-info    { background: #f0fdf4; color: #047857; border: 1px solid #a7f3d0; }

    /* Active Product Session Workspace Card */
    .op-workspace-card {
        flex: 1;
        min-height: 0;
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: var(--erp-radius);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Product Header / Hero Bar inside Workspace */
    .op-product-hero {
        flex-shrink: 0;
        background: #ffffff;
        border-bottom: 1px solid var(--erp-border);
        padding: 8px 16px;
    }

    /* Bulk Action Toolbar (Odoo Style) */
    .op-bulk-toolbar {
        flex-shrink: 0;
        background: #f8fafc;
        border-bottom: 1px solid var(--erp-border);
        padding: 7px 16px;
    }

    /* Matrix Table Container (Internal scrolling ONLY) */
    .op-matrix-container {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overflow-x: auto;
        background: #ffffff;
        position: relative;
    }

    /* Matrix Table Styles (Odoo Clean Enterprise Grid) */
    .op-matrix-table {
        margin-bottom: 0;
        font-size: 12px;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .op-matrix-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 10.5px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 8px 8px;
        vertical-align: middle;
        border-bottom: 2px solid var(--erp-border);
        border-top: none;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }
    .op-matrix-table tbody tr {
        transition: background-color 0.12s ease;
    }
    .op-matrix-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .op-matrix-table tbody tr.row-modified {
        background-color: #fffdf5 !important;
        border-left: 3px solid #f59e0b !important;
    }
    .op-matrix-table tbody tr.row-saved {
        background-color: #f0fdf4 !important;
        border-left: 3px solid #10b981 !important;
    }
    .op-matrix-table tbody td {
        padding: 5px 6px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Odoo Crisp Cell Inputs */
    .odoo-input {
        font-size: 11.5px;
        font-weight: 600;
        height: 30px;
        border-radius: 5px;
        border: 1px solid #cbd5e1;
        padding: 3px 7px;
        background-color: #ffffff;
        color: #0f172a;
        transition: all 0.15s ease;
        width: 100%;
        box-sizing: border-box;
    }
    .odoo-input:hover {
        border-color: #94a3b8;
    }
    .odoo-input:focus {
        border-color: var(--erp-primary);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.16);
        outline: none;
        background-color: #ffffff;
    }

    /* P-Code Encrypted Box (Odoo Monospace Cipher) */
    .odoo-pcode {
        background-color: #f8fafc !important;
        border: 1px dashed #cbd5e1 !important;
        color: #1e293b !important;
        font-weight: 800 !important;
        letter-spacing: 1.2px !important;
        text-align: center !important;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        height: 30px;
        font-size: 12px;
        border-radius: 5px;
        width: 100%;
    }

    /* Odoo Style Segmented Switcher for WH / Shop (NO RAW RADIO CIRCLES) */
    .loc-segment-switch {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        padding: 2px;
        gap: 2px;
        flex-shrink: 0;
        user-select: none;
    }
    .loc-segment-switch input[type="radio"] {
        position: absolute !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        margin: 0 !important;
        pointer-events: none !important;
    }
    .loc-segment-switch label {
        margin: 0 !important;
        padding: 2px 8px !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        color: #64748b !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        line-height: 1.4 !important;
        display: inline-flex;
        align-items: center;
    }
    .loc-segment-switch input[type="radio"]:checked + label {
        background: var(--erp-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.25) !important;
    }

    /* Row Warehouse Dropdown */
    .matrix-loc-cell .row-wh-select {
        height: 30px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 2px 18px 2px 6px !important;
        border-radius: 5px !important;
        border: 1px solid #cbd5e1 !important;
        max-width: 100px !important;
        background-color: #ffffff !important;
        color: #1e293b !important;
    }
    .matrix-loc-cell .row-shop-badge {
        font-size: 10.5px !important;
        height: 28px !important;
        padding: 0 8px !important;
        background: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        border-radius: 5px !important;
        display: inline-flex;
        align-items: center;
    }

    /* Row Save Action Button */
    .odoo-btn-save {
        width: 28px;
        height: 28px;
        border-radius: 5px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: var(--erp-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        cursor: pointer;
        padding: 0;
    }
    .odoo-btn-save:hover {
        background: var(--erp-primary);
        border-color: var(--erp-primary);
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25);
    }

    /* Empty state */
    .op-empty-workspace {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        text-align: center;
    }
    .op-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: var(--erp-primary-lt);
        color: var(--erp-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin-bottom: 14px;
    }

    /* Column Visibility class */
    .col-hidden { display: none !important; }

    /* Sticky Footer Bar */
    .op-footer-bar {
        flex-shrink: 0;
        background: #ffffff;
        border-top: 1px solid var(--erp-border);
        padding: 7px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Modal Close compatibility */
    .modal-header .close, .modal-header .btn-close {
        padding: 8px;
        margin: -8px -8px -8px auto;
        background: transparent;
        border: none;
        font-size: 22px;
        opacity: 0.6;
        cursor: pointer;
    }
    .modal-header .close:hover, .modal-header .btn-close:hover {
        opacity: 1;
    }
</style>

<div class="opening-stock-wrapper">

    {{-- Top Action & Warehouse Header --}}
    <div class="op-top-bar d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="op-title"><i class="fas fa-boxes text-primary me-2"></i>Opening Stock & Pricing Matrix</span>
            <span class="badge font-monospace fw-bold" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 10px;">ERP MATRIX</span>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Warehouse Selector Pill --}}
            <form method="GET" action="{{ route('opening_stock.index') }}" id="whForm" class="d-inline-flex align-items-center m-0">
                <input type="hidden" name="filter_type" id="filterTypeInput" value="{{ $filterType }}">
                @if(!empty($productId))
                    <input type="hidden" name="product_id" value="{{ $productId }}">
                @endif
                <div class="op-wh-pill">
                    <i class="fas fa-warehouse text-primary me-1.5" style="font-size: 12px;"></i>
                    <select name="warehouse_id" id="warehouseSelect" class="op-header-select" onchange="document.getElementById('whForm').submit();">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ $selectedWarehouse->id == $wh->id ? 'selected' : '' }}>
                                {{ $wh->warehouse_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- SELECT / ADD PRODUCT BUTTON (The main trigger requested by user) --}}
            <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1 btn-open-prod-modal"
                    data-toggle="modal" data-target="#productSelectModal"
                    data-bs-toggle="modal" data-bs-target="#productSelectModal"
                    onclick="openProductSelectModal();" title="Search and load product into matrix (F2)"
                    style="height: 32px; border-radius: 6px; box-shadow: 0 1px 2px rgba(37, 99, 235, 0.2);">
                <i class="fas fa-search-plus me-1.5"></i> Select Product <span class="badge bg-white text-primary ms-1.5 font-monospace" style="font-size: 9.5px; padding: 2px 5px; border-radius: 3px;">F2</span>
            </button>

            {{-- Customize Columns Button --}}
            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5"
                    data-toggle="modal" data-target="#customizeColsModal"
                    data-bs-toggle="modal" data-bs-target="#customizeColsModal"
                    title="Show/Hide table columns" style="height: 32px; font-size: 11.5px; border-radius: 6px; border-color: #cbd5e1; background: #fff;">
                <i class="fas fa-columns text-secondary me-1"></i> Columns
            </button>

            {{-- Save Changes Batch Button --}}
            <button type="button" class="btn btn-sm btn-outline-success fw-bold py-1 px-3" id="btnSaveBatchTop" onclick="saveAllBatch();" style="height: 32px; font-size: 11.5px; border-radius: 6px; border-color: #10b981;">
                <i class="fas fa-save me-1"></i> Save Changes (<span class="modified-count">0</span>)
            </button>
        </div>
    </div>

    {{-- Odoo Unified Filter Ribbon --}}
    <div class="op-stat-ribbon">
        <div class="op-odoo-filter-bar">
            <button type="button" class="op-filter-tab {{ $filterType === 'all' ? 'active' : '' }}" onclick="applyFilterType('all');">
                <i class="fas fa-cubes text-primary"></i>
                <span class="op-tab-label">All Variants</span>
                <span class="op-tab-count op-count-primary">{{ $totalVariantsCount }}</span>
            </button>

            <button type="button" class="op-filter-tab {{ $filterType === 'missing_location' ? 'active' : '' }}" onclick="applyFilterType('missing_location');">
                <i class="fas fa-map-marker-alt text-warning"></i>
                <span class="op-tab-label">Missing Rack/Shelf</span>
                <span class="op-tab-count op-count-warning">{{ $missingLocationCount }}</span>
            </button>

            <button type="button" class="op-filter-tab {{ $filterType === 'missing_price' ? 'active' : '' }}" onclick="applyFilterType('missing_price');">
                <i class="fas fa-tags text-danger"></i>
                <span class="op-tab-label">Missing Sky/Rot</span>
                <span class="op-tab-count op-count-danger">{{ $missingPriceCount }}</span>
            </button>

            <button type="button" class="op-filter-tab {{ $filterType === 'zero_stock' ? 'active' : '' }}" onclick="applyFilterType('zero_stock');">
                <i class="fas fa-box-open text-info"></i>
                <span class="op-tab-label">Zero Stock</span>
                <span class="op-tab-count op-count-info">{{ $zeroStockCount }}</span>
            </button>
        </div>
    </div>

    {{-- Main Workspace Card (Zero Page Scroll, internally scrollable table) --}}
    <div class="op-workspace-card" id="opWorkspaceCard">

        {{-- Active Product Hero Bar (Clean Odoo Document Header) --}}
        <div class="op-product-hero d-flex flex-wrap justify-content-between align-items-center" id="productHeroBar" style="{{ empty($items) ? 'display: none !important;' : '' }}; padding: 10px 16px; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                <span class="badge font-monospace px-2.5 py-1.5" style="background: #0f172a; color: #ffffff; font-size: 11px; border-radius: 4px; letter-spacing: 0.5px;" id="heroProductCode">
                    {{ !empty($items[0]['product']->item_code) ? $items[0]['product']->item_code : 'NO-CODE' }}
                </span>
                
                {{-- Highly Visible Product Title --}}
                <span class="fw-bold text-dark text-capitalize" style="font-size: 18px; font-weight: 800; color: #0f172a; line-height: 1.2; letter-spacing: -0.3px;" id="heroProductName">
                    {{ !empty($items[0]['product']->item_name) ? $items[0]['product']->item_name : '' }}
                </span>

                <span class="badge text-capitalize" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; padding: 4px 9px; border-radius: 5px; font-weight: 600; display: inline-flex; align-items: center;" id="heroProductCategory">
                    <i class="fas fa-folder text-primary" style="margin-right: 6px;"></i>{{ !empty($items[0]['product']->category_relation->name) ? $items[0]['product']->category_relation->name : '-' }}
                </span>

                @php
                    $bName = !empty($items[0]['product']->brand->name) ? trim($items[0]['product']->brand->name) : '';
                    $hasBrand = !empty($bName) && $bName !== '-' && strtolower($bName) !== 'no brand';
                @endphp
                <span class="badge text-capitalize" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; padding: 4px 9px; border-radius: 5px; font-weight: 600; display: {{ $hasBrand ? 'inline-flex' : 'none !important' }}; align-items: center;" id="heroProductBrand">
                    <i class="fas fa-tag text-secondary" style="margin-right: 6px;"></i>{{ $bName }}
                </span>

                <span class="badge font-monospace fw-bold" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; padding: 4px 9px; border-radius: 5px; display: inline-flex; align-items: center;" id="heroVariantCountBadge">
                    <i class="fas fa-layer-group opacity-75" style="margin-right: 6px;"></i><span id="heroVariantCount">{{ !empty($items[0]['matrix_rows']) ? count($items[0]['matrix_rows']) : 0 }}</span> Variants
                </span>

                <a href="{{ !empty($items[0]['product']->id) ? route('products.edit', $items[0]['product']->id) : '#' }}" id="heroEditLink" target="_blank" class="text-muted small text-decoration-none" title="Open master product profile">
                    <i class="fas fa-external-link-alt text-primary" style="font-size: 11px;"></i>
                </a>
            </div>

            <div class="d-flex align-items-center" style="gap: 10px;">
                <div class="d-inline-flex align-items-center px-3 py-1" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 11.5px; height: 32px;">
                    <span class="text-muted" style="margin-right: 6px;">Warehouse Stock:</span>
                    <strong class="text-primary font-monospace" style="font-size: 13.5px;" id="heroStockPieces">{{ !empty($items[0]['wh_total_pieces']) ? $items[0]['wh_total_pieces'] : 0 }}</strong>
                    <span class="text-muted" style="margin-left: 4px;">Pcs</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3 fw-semibold d-inline-flex align-items-center"
                        data-toggle="modal" data-target="#productSelectModal"
                        data-bs-toggle="modal" data-bs-target="#productSelectModal"
                        onclick="openProductSelectModal();" title="Switch to another product"
                        style="font-size: 11.5px; border-radius: 6px; height: 32px;">
                    <i class="fas fa-exchange-alt" style="margin-right: 6px;"></i> Switch Product
                </button>
            </div>
        </div>

        {{-- Bulk Action Bar for this Product (Assign Location to All/Checked) --}}
        <div class="op-bulk-toolbar d-flex flex-wrap justify-content-between align-items-center" id="productBulkBar" style="{{ empty($items) ? 'display: none !important;' : '' }}; padding: 7px 16px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; gap: 10px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                <span class="fw-bold text-muted text-uppercase d-inline-flex align-items-center" style="font-size: 11px; letter-spacing: 0.5px;">
                    <i class="fas fa-bolt text-warning" style="margin-right: 6px;"></i>Bulk Assign:
                </span>

                {{-- Location Type Segment Switcher --}}
                <div class="loc-segment-switch" style="height: 32px; padding: 2px 3px;">
                    <input type="radio" name="bulk_loc_type" id="bulkLocWh" value="warehouse" checked autocomplete="off" onchange="onBulkLocTypeChange();">
                    <label for="bulkLocWh" title="Warehouse Rack" style="padding: 4px 10px !important; font-size: 11px !important;">Warehouse</label>

                    <input type="radio" name="bulk_loc_type" id="bulkLocShop" value="shop" autocomplete="off" onchange="onBulkLocTypeChange();">
                    <label for="bulkLocShop" title="Shop Shelf" style="padding: 4px 10px !important; font-size: 11px !important;">Shop</label>
                </div>

                {{-- Warehouse Dropdown for Bulk --}}
                <select id="bulkWarehouseSelect" class="form-select form-select-sm fw-semibold" style="font-size: 11.5px; width: 160px; min-width: 140px; height: 32px; border-color: #cbd5e1; border-radius: 6px; background-color: #fff;" onchange="onBulkWarehouseChange();">
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ $selectedWarehouse->id == $wh->id ? 'selected' : '' }}>
                            {{ $wh->warehouse_name }}
                        </option>
                    @endforeach
                </select>

                {{-- Rack / Shelf Dropdown for Bulk --}}
                <select id="bulkRackShelfSelect" class="form-select form-select-sm fw-semibold text-primary" style="font-size: 11.5px; width: 200px; min-width: 170px; height: 32px; border-color: #cbd5e1; border-radius: 6px; background-color: #fff;">
                    <option value="">-- Select Target Rack --</option>
                </select>

                {{-- Apply to All Button --}}
                <button type="button" class="btn btn-sm btn-primary py-1 px-3 fw-semibold d-inline-flex align-items-center" onclick="applyBulkLocationToVariants('all');" style="font-size: 11.5px; height: 32px; border-radius: 6px;">
                    <i class="fas fa-check-double" style="margin-right: 6px;"></i> Apply to All
                </button>

                {{-- Apply to Checked Button --}}
                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3 fw-semibold d-inline-flex align-items-center" onclick="applyBulkLocationToVariants('checked');" style="font-size: 11.5px; height: 32px; border-radius: 6px;">
                    <i class="fas fa-check-square" style="margin-right: 6px;"></i> Apply to Checked (<span id="checkedCountBadge">0</span>)
                </button>
            </div>

            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-success py-1 px-3 fw-bold shadow-sm d-inline-flex align-items-center" onclick="saveActiveProductRows();" style="font-size: 11.5px; height: 32px; border-radius: 6px; background: #059669; border-color: #059669;">
                    <i class="fas fa-save" style="margin-right: 6px;"></i> Save Product Variants
                </button>
            </div>
        </div>

        {{-- Table Container with Sticky Header and Strict Viewport Bounds --}}
        <div class="op-matrix-container" id="matrixContainer" style="{{ empty($items) ? 'display: none !important;' : '' }}">
            <table class="op-matrix-table align-middle" id="openingStockTable">
                <thead>
                    <tr>
                        <th class="col-select text-center" style="width: 32px;"><input type="checkbox" class="form-check-input" id="checkAllRows" title="Select All Variants"></th>
                        <th class="col-product" style="min-width: 220px;">Variant Specifications</th>
                        <th class="col-storage-type text-center" style="min-width: 170px;">Storage Mode</th>
                        <th class="col-location text-center" style="min-width: 165px;">Rack / Shelf</th>
                        <th class="col-stock text-center" style="width: 85px;">On Hand</th>
                        <th class="col-conv text-center" style="width: 75px;">Conv/Box</th>
                        <th class="col-weight text-center" style="width: 80px;">Piece Wt</th>
                        <th class="col-sale text-center" style="width: 95px;">Sky Price</th>
                        <th class="col-sky-pcode text-center" style="width: 85px;">Sky P-Code</th>
                        <th class="col-cost text-center" style="width: 85px;">Cost</th>
                        <th class="col-wholesale text-center" style="width: 95px;">Rot Price</th>
                        <th class="col-rot-pcode text-center" style="width: 85px;">Rot P-Code</th>
                        <th class="col-action text-center" style="width: 45px;">Save</th>
                    </tr>
                </thead>
                <tbody id="matrixTbody">
                    @if(!empty($items) && count($items) > 0)
                        @foreach($items[0]['matrix_rows'] as $row)
                            @php
                                $isShopLoc = false;
                                if (!empty($row['location'])) {
                                    $matchingLoc = $storageLocations->firstWhere('name', $row['location']);
                                    if ($matchingLoc && $matchingLoc->type === 'shop_shelf') {
                                        $isShopLoc = true;
                                    }
                                }
                            @endphp
                            <tr class="matrix-row" id="row_{{ $row['row_id'] }}"
                                data-row-id="{{ $row['row_id'] }}"
                                data-product-id="{{ $row['product_id'] }}"
                                data-variant-idx="{{ $row['variant_index'] }}">
                                
                                {{-- Checkbox --}}
                                <td class="col-select text-center">
                                    <input type="checkbox" class="form-check-input row-check" data-row-id="{{ $row['row_id'] }}">
                                </td>

                                {{-- Variant Details (Clean Odoo ERP Presentation) --}}
                                <td class="col-product">
                                    <div class="d-flex flex-column py-1 justify-content-center">
                                        {{-- Top: Variant Title (Clean Semibold 13.5px, Title Cased) --}}
                                        <div class="fw-semibold text-dark text-capitalize" style="font-size: 13.5px; font-weight: 600; color: #0f172a; line-height: 1.35; letter-spacing: -0.1px;">
                                            {{ $row['variant_title'] }}
                                        </div>

                                        {{-- Bottom: Sleek Badges (Serial No, Size, Color) --}}
                                        <div class="d-flex align-items-center flex-wrap" style="gap: 5px; margin-top: 4px;">
                                            <span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 10px; font-weight: 700; padding: 2.5px 7px; border-radius: 4px; letter-spacing: 0.3px;">
                                                <i class="fas fa-hashtag me-0.5 opacity-75" style="font-size: 8.5px;"></i>{{ $row['serial_no'] }}
                                            </span>

                                            @if($row['is_variant'])
                                                @if(!empty($row['attributes']['size']) && $row['attributes']['size'] !== '-')
                                                    <span class="badge" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; font-weight: 500; padding: 2.5px 7px; border-radius: 4px; display: inline-flex; align-items: center;">
                                                        <span style="color: #64748b; font-weight: 500; margin-right: 3px;">Size:</span>
                                                        <strong style="color: #0f172a; font-weight: 700;">{{ $row['attributes']['size'] }}</strong>
                                                    </span>
                                                @endif
                                                @if(!empty($row['attributes']['color']) && $row['attributes']['color'] !== '-')
                                                    <span class="badge" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; font-weight: 500; padding: 2.5px 7px; border-radius: 4px; display: inline-flex; align-items: center;">
                                                        <span style="color: #64748b; font-weight: 500; margin-right: 3px;">Color:</span>
                                                        <strong style="color: #0f172a; font-weight: 700;">{{ $row['attributes']['color'] }}</strong>
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Storage Mode (WH vs Shop Switcher - Clean Odoo segmented pill, NO RAW RADIOS) --}}
                                <td class="col-storage-type">
                                    <div class="matrix-loc-cell d-flex align-items-center gap-1.5" data-row-id="{{ $row['row_id'] }}">
                                        <div class="loc-segment-switch">
                                            <input type="radio" class="row-loc-type-radio row-loc-wh" name="loc_type_{{ $row['row_id'] }}" id="loc_wh_{{ $row['row_id'] }}" value="warehouse" {{ !$isShopLoc ? 'checked' : '' }} autocomplete="off">
                                            <label for="loc_wh_{{ $row['row_id'] }}" title="Warehouse Rack">WH</label>

                                            <input type="radio" class="row-loc-type-radio row-loc-shop" name="loc_type_{{ $row['row_id'] }}" id="loc_shop_{{ $row['row_id'] }}" value="shop" {{ $isShopLoc ? 'checked' : '' }} autocomplete="off">
                                            <label for="loc_shop_{{ $row['row_id'] }}" title="Shop Shelf">Shop</label>
                                        </div>
                                        <select class="form-select form-select-sm row-wh-select" style="{{ $isShopLoc ? 'display: none !important;' : '' }}">
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}" {{ $selectedWarehouse->id == $wh->id ? 'selected' : '' }}>{{ $wh->warehouse_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="badge row-shop-badge" style="{{ !$isShopLoc ? 'display: none !important;' : 'display: inline-flex;' }}"><i class="fas fa-store me-1 text-primary"></i>Shop</span>
                                    </div>
                                </td>

                                {{-- Rack / Shelf Dropdown --}}
                                <td class="col-location">
                                    <select class="form-select odoo-input field-location"
                                            name="location"
                                            data-current-val="{{ $row['location'] }}">
                                        <option value="">-- {{ $isShopLoc ? 'Select Shelf' : 'Select Rack' }} --</option>
                                        @if(!empty($row['location']))
                                            <option value="{{ $row['location'] }}" selected>{{ $row['location'] }}</option>
                                        @endif
                                        <option value="__create_new__" style="color: #2563eb; font-weight: bold;">+ Create New...</option>
                                    </select>
                                </td>

                                {{-- On Hand Stock --}}
                                <td class="col-stock">
                                    <input type="number" step="any" class="form-control odoo-input text-center fw-bold text-primary field-stock"
                                           name="stock" value="{{ $row['stock'] }}" placeholder="0">
                                </td>

                                {{-- Conv / Box --}}
                                <td class="col-conv">
                                    <input type="number" step="any" class="form-control odoo-input text-center fw-bold text-success field-conv"
                                           name="conv_factor" value="{{ $row['conv_factor'] }}" placeholder="1">
                                </td>

                                {{-- Piece Weight --}}
                                <td class="col-weight">
                                    <input type="number" step="any" class="form-control odoo-input text-end field-weight"
                                           name="weight_per_piece" value="{{ $row['weight_per_piece'] > 0 ? $row['weight_per_piece'] : '' }}" placeholder="0g">
                                </td>

                                {{-- Sky Price --}}
                                <td class="col-sale">
                                    <input type="number" step="any" class="form-control odoo-input text-end fw-bold field-sky-price"
                                           name="sky_price" value="{{ number_format($row['sky_price'], 2, '.', '') }}" placeholder="0.00">
                                </td>

                                {{-- Sky P-Code (Live Encrypted Cipher) --}}
                                <td class="col-sky-pcode">
                                    <input type="text" class="form-control odoo-pcode field-sky-pcode"
                                           name="sky_pcode" value="{{ $row['sky_pcode'] }}" readonly>
                                </td>

                                {{-- Cost --}}
                                <td class="col-cost">
                                    <input type="number" step="any" class="form-control odoo-input text-end text-muted field-cost"
                                           name="cost" value="{{ number_format($row['cost'], 2, '.', '') }}" placeholder="0.00">
                                </td>

                                {{-- Rot Price --}}
                                <td class="col-wholesale">
                                    <input type="number" step="any" class="form-control odoo-input text-end fw-bold field-rot-price"
                                           name="rot_price" value="{{ number_format($row['rot_price'], 2, '.', '') }}" placeholder="0.00">
                                </td>

                                {{-- Rot P-Code (Live Encrypted Cipher) --}}
                                <td class="col-rot-pcode">
                                    <input type="text" class="form-control odoo-pcode field-rot-pcode"
                                           name="rot_pcode" value="{{ $row['rot_pcode'] }}" readonly>
                                </td>

                                {{-- Row Save Action --}}
                                <td class="col-action text-center">
                                    <button type="button" class="odoo-btn-save btn-save-row" title="Save this row">
                                        <i class="fas fa-save" style="font-size: 12px;"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Empty State (Shown when no product is loaded) --}}
        <div class="op-empty-workspace" id="opEmptyWorkspace" style="{{ !empty($items) && count($items) > 0 ? 'display: none !important;' : '' }}">
            <div class="op-empty-icon shadow-sm">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Product Loaded in Workspace</h5>
            <p class="text-muted small mb-3" style="max-width: 480px;">
                Click below or press <kbd class="bg-light text-dark border">F2</kbd> to search and select any product from your catalog. Its variants will load into this matrix view for zero-scroll location assignment and pricing.
            </p>
            <button type="button" class="btn btn-primary px-4 py-2 fw-bold shadow-sm btn-open-prod-modal"
                    data-toggle="modal" data-target="#productSelectModal"
                    data-bs-toggle="modal" data-bs-target="#productSelectModal"
                    onclick="openProductSelectModal();">
                <i class="fas fa-search me-1.5"></i> Select Product to Manage
            </button>
        </div>

        {{-- Footer Status Bar --}}
        <div class="op-footer-bar" id="opFooterBar" style="{{ empty($items) ? 'display: none !important;' : '' }}">
            <div class="d-flex align-items-center gap-2">
                <span class="badge font-monospace" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 11px;" id="footerRowCountBadge">
                    {{ !empty($items[0]['matrix_rows']) ? count($items[0]['matrix_rows']) : 0 }} Variants Loaded
                </span>
                <span class="text-muted small" id="footerUnsavedMsg" style="display: none;">
                    <i class="fas fa-info-circle text-warning me-1"></i> <span class="modified-count">0</span> rows have unsaved changes.
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" onclick="discardActiveEdits();" style="font-size: 11.5px;">
                    Discard Changes
                </button>
                <button type="button" class="btn btn-sm btn-primary py-1 px-4 fw-bold shadow-sm" onclick="saveAllBatch();" style="font-size: 11.5px;">
                    <i class="fas fa-save me-1"></i> Save All (<span class="modified-count">0</span>)
                </button>
            </div>
        </div>

    </div>

</div>

{{-- ======================================================== --}}
{{-- 1. PRODUCT SELECTION MODAL (AJAX search & instant select) --}}
{{-- ======================================================== --}}
<div class="modal fade" id="productSelectModal" tabindex="-1" role="dialog" aria-labelledby="productSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 920px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1 !important;">
            {{-- Modal Header --}}
            <div class="modal-header py-2.5 px-4 bg-white border-bottom" style="border-color: #e2e8f0;">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="productSelectModalLabel" style="font-size: 15px; letter-spacing: -0.2px;">Select Product for Matrix</h6>
                        <div class="text-muted" style="font-size: 11px;">Search by name, code, barcode, or filter by category & brand</div>
                    </div>
                </div>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.6;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3" style="background: #ffffff;">
                {{-- Odoo Style Search & Filter Toolbar --}}
                <div class="p-2 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted border-end-0" style="border-color: #cbd5e1; height: 32px;"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="modalProductSearchInput" class="form-control form-control-sm border-start-0" placeholder="Search product name, item code, barcode..." autocomplete="off" style="border-color: #cbd5e1; font-size: 12px; height: 32px;">
                                <div class="input-group-append" id="modalProductSearchClearWrap" style="display: none;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="modalProductSearchClear" style="border-color: #cbd5e1; height: 32px;" onclick="clearProductModalSearch();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted border-end-0" style="border-color: #cbd5e1; height: 32px;"><i class="fas fa-folder text-muted" style="font-size: 11px;"></i></span>
                                </div>
                                <select id="modalCategoryFilter" class="custom-select custom-select-sm border-start-0" style="border-color: #cbd5e1; font-size: 12px; height: 32px; font-weight: 500;" onchange="runProductSearch();">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-muted border-end-0" style="border-color: #cbd5e1; height: 32px;"><i class="fas fa-tag text-muted" style="font-size: 11px;"></i></span>
                                </div>
                                <select id="modalBrandFilter" class="custom-select custom-select-sm border-start-0" style="border-color: #cbd5e1; font-size: 12px; height: 32px; font-weight: 500;" onchange="runProductSearch();">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Results Table Box --}}
                <div class="table-responsive border rounded" style="max-height: 400px; min-height: 240px; overflow-y: auto; border-color: #e2e8f0 !important; background: #ffffff;">
                    <table class="table table-hover table-sm align-middle mb-0" id="modalProductSearchTable" style="font-size: 12px; border-collapse: separate; border-spacing: 0;">
                        <thead style="position: sticky; top: 0; z-index: 5; background: #f8fafc;">
                            <tr>
                                <th style="width: 110px; background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">Item Code</th>
                                <th style="background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">Product Name</th>
                                <th style="width: 170px; background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">Category & Brand</th>
                                <th class="text-center" style="width: 120px; background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">Variants</th>
                                <th class="text-center" style="width: 100px; background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">On Hand</th>
                                <th class="text-end" style="width: 95px; background: #f8fafc; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 8px 10px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="modalProductSearchResultsTbody">
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                                    <div>Loading product catalog...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer py-2 px-4 bg-light border-top d-flex justify-content-between align-items-center" style="border-color: #e2e8f0;">
                <span class="small text-muted font-monospace" id="modalResultsCountText">
                    <i class="fas fa-list me-1 opacity-75"></i>Showing 0 products
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 fw-semibold" data-dismiss="modal" data-bs-dismiss="modal" style="font-size: 12px; border-radius: 5px; border-color: #cbd5e1; background: #fff;">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 2. CUSTOMIZE COLUMNS MODAL --}}
{{-- ======================================================== --}}
<div class="modal fade" id="customizeColsModal" tabindex="-1" role="dialog" aria-labelledby="customizeColsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h6 class="modal-title fw-bold text-dark" id="customizeColsModalLabel">
                    <i class="fas fa-columns text-primary me-2"></i>Customize Matrix Columns
                </h6>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Toggle columns you want visible in the Opening Stock matrix:</p>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-storage-type" data-col="col-storage-type" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-storage-type">Storage Mode</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-location" data-col="col-location" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-location">Rack / Shelf</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-stock" data-col="col-stock" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-stock">On Hand Stock</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-conv" data-col="col-conv" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-conv">Conv / Box</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-weight" data-col="col-weight" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-weight">Piece Wt (g)</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-sale" data-col="col-sale" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-sale">Sky Price</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-sky-pcode" data-col="col-sky-pcode" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-sky-pcode">Sky P-Code</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-cost" data-col="col-cost" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-cost">Cost (Purch)</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-wholesale" data-col="col-wholesale" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-wholesale">Rot Price</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch p-2 bg-light rounded border">
                            <input class="form-check-input col-toggle" type="checkbox" id="toggle_col-rot-pcode" data-col="col-rot-pcode" checked>
                            <label class="form-check-label fw-bold small ms-2" for="toggle_col-rot-pcode">Rot P-Code</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetDefaultColumns();">Reset Defaults</button>
                <button type="button" class="btn btn-sm btn-primary px-4" data-dismiss="modal" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- 3. QUICK ADD RACK / SHELF MODAL --}}
{{-- ======================================================== --}}
<div id="quickAddStorageLocationModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <form id="quickAddStorageLocationForm" action="{{ route('storage_locations.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold" id="quickLocModalTitle" style="font-size:13.5px;">
                        <i class="fas fa-cubes text-primary me-1"></i> <span id="quickLocModalTitleText">Create Storage Location</span>
                    </h6>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-2">
                    {{-- Type Switcher Segment --}}
                    <div class="mb-2.5">
                        <label class="form-label fw-bold text-muted small mb-1" style="font-size: 10.5px;">LOCATION TYPE</label>
                        <div class="loc-segment-switch w-100 justify-content-center">
                            <input type="radio" name="type" id="quickLocTypeRack" value="warehouse_rack" checked autocomplete="off">
                            <label for="quickLocTypeRack" style="flex: 1; text-align: center; justify-content: center; padding: 4px 8px !important;">
                                <i class="fas fa-warehouse me-1"></i> Warehouse Rack
                            </label>

                            <input type="radio" name="type" id="quickLocTypeShelf" value="shop_shelf" autocomplete="off">
                            <label for="quickLocTypeShelf" style="flex: 1; text-align: center; justify-content: center; padding: 4px 8px !important;">
                                <i class="fas fa-store me-1"></i> Shop Shelf
                            </label>
                        </div>
                    </div>

                    {{-- Warehouse Selector (Visible when warehouse_rack) --}}
                    <div class="mb-2" id="quickLocWarehouseGroup">
                        <label class="form-label fw-bold" style="font-size:11.5px;">Warehouse <span class="text-danger">*</span></label>
                        <select name="warehouse_id" id="quickLocWarehouseSelect" class="form-select form-select-sm" style="font-size: 12px; border-color: #cbd5e1;">
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $selectedWarehouse->id == $wh->id ? 'selected' : '' }}>{{ $wh->warehouse_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Branch / Shop Selector (Visible when shop_shelf) --}}
                    <div class="mb-2" id="quickLocBranchGroup" style="display: none;">
                        <label class="form-label fw-bold" style="font-size:11.5px;">Shop / Branch <span class="text-danger">*</span></label>
                        <select name="branch_id" id="quickLocBranchSelect" class="form-select form-select-sm" style="font-size: 12px; border-color: #cbd5e1;">
                            @foreach($branches as $br)
                                <option value="{{ $br->id }}">{{ $br->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Location Name --}}
                    <div class="mb-2">
                        <label class="form-label fw-bold" id="quickLocNameLabel" style="font-size:11.5px;">Rack Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="quickLocNameInput" class="form-control form-control-sm" required placeholder="e.g. Rack A-1" style="border-color: #cbd5e1;">
                        <div class="invalid-feedback" id="quickLocNameError" style="font-size: 11px;"></div>
                    </div>

                    {{-- Location Code (Optional) --}}
                    <div class="mb-2">
                        <label class="form-label text-muted fw-semibold" style="font-size:11px;">Code / Reference (Optional)</label>
                        <input type="text" name="code" id="quickLocCodeInput" class="form-control form-control-sm" placeholder="e.g. RA-01" style="border-color: #cbd5e1;">
                    </div>

                    {{-- Zone or Aisle (Optional) --}}
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:11px;">Zone / Aisle (Optional)</label>
                        <input type="text" name="zone_or_aisle" id="quickLocZoneInput" class="form-control form-control-sm" placeholder="e.g. Aisle 3, Row B" style="border-color: #cbd5e1;">
                    </div>

                    <button type="submit" id="btnSubmitQuickStorageLoc" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">
                        <i class="fas fa-check me-1"></i> Save & Select
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Global in-memory storage locations, warehouses & branches
    window.allStorageLocations = @json($storageLocations ?? []);
    window.allWarehouses = @json($warehouses ?? []);
    window.allBranches = @json($branches ?? []);
    window.pcodeMapping = @json($currentPCodeMapping);

    const selectedWarehouseId = {{ $selectedWarehouse->id }};
    const modifiedRows = new Set();
    let currentLoadedProductId = {{ !empty($items[0]['product']->id) ? $items[0]['product']->id : 'null' }};
    let productSearchDebounceTimer = null;

    /**
     * Live client-side P-Code cipher encoder
     */
    window.encodeToPCode = function(amount) {
        if (amount === undefined || amount === null || amount === '') return '';
        let str = String(amount).replace(/[^0-9.]/g, '');
        if (!str) return '';

        let num = parseFloat(str);
        if (isNaN(num)) return '';

        if (Math.floor(num) === num) {
            str = String(Math.round(num));
        } else {
            str = String(parseFloat(num.toFixed(2)));
        }

        let map = window.pcodeMapping || {};
        let output = '';
        for (let i = 0; i < str.length; i++) {
            let ch = str[i];
            if (map[ch] !== undefined && map[ch] !== '') {
                output += map[ch];
            } else {
                output += ch;
            }
        }
        return output.toUpperCase();
    };

    /**
     * Filter storage locations STRICTLY by mode (shop vs warehouse) and warehouseId
     */
    window.getFilteredStorageLocationsFor = function(type, whId) {
        if (!window.allStorageLocations || !Array.isArray(window.allStorageLocations)) return [];
        if (type === 'shop') {
            return window.allStorageLocations.filter(function(loc) {
                return loc.type === 'shop_shelf';
            });
        } else {
            var targetWh = whId || selectedWarehouseId;
            return window.allStorageLocations.filter(function(loc) {
                if (loc.type !== 'warehouse_rack') return false;
                if (targetWh) {
                    return String(loc.warehouse_id) === String(targetWh);
                }
                return true;
            });
        }
    };

    /**
     * Render options inside a Rack/Shelf <select>
     * PREVENTS CROSS-CONTAMINATION (Shelves NEVER show in WH, Racks NEVER show in Shop)
     */
    window.renderLocationSelectOptions = function($select, currentVal, type, whId) {
        type = type || 'warehouse';
        var locs = window.getFilteredStorageLocationsFor(type, whId);
        var placeholder = (type === 'shop') ? 'Select Shelf' : 'Select Rack';
        var optionsHtml = '<option value="">-- ' + placeholder + ' --</option>';
        var hasCurrent = false;

        // Check if currentVal belongs to the OTHER location type (e.g. shelf when in WH mode)
        if (currentVal && window.allStorageLocations && Array.isArray(window.allStorageLocations)) {
            var belongsToOther = window.allStorageLocations.some(function(loc) {
                var isSameName = (String(loc.name).trim().toLowerCase() === String(currentVal).trim().toLowerCase());
                if (!isSameName) return false;
                if (type === 'warehouse' && loc.type === 'shop_shelf') return true;
                if (type === 'shop' && loc.type === 'warehouse_rack') return true;
                return false;
            });
            if (belongsToOther) {
                currentVal = ''; // Completely discard from this dropdown!
            }
        }

        locs.forEach(function(loc) {
            var val = loc.name;
            var isSelected = (currentVal && String(currentVal).trim().toLowerCase() === String(val).trim().toLowerCase());
            if (isSelected) hasCurrent = true;
            var label = loc.name + (loc.code ? ' (' + loc.code + ')' : '');
            optionsHtml += '<option value="' + escapeHtml(val) + '" ' + (isSelected ? 'selected' : '') + '>' + escapeHtml(label) + '</option>';
        });

        // ONLY if it is truly custom (and not a known shelf/rack of the other type)
        if (currentVal && !hasCurrent && String(currentVal).trim() !== '' && currentVal !== '__custom__' && currentVal !== '__create_new__') {
            optionsHtml += '<option value="' + escapeHtml(currentVal) + '" selected>' + escapeHtml(currentVal) + ' (Custom)</option>';
        }

        var createLabel = (type === 'shop') ? '+ Create New Shelf...' : '+ Create New Rack...';
        optionsHtml += '<option value="__create_new__" style="color: #2563eb; font-weight: bold;">' + createLabel + '</option>';

        $select.html(optionsHtml);
        if (currentVal && currentVal !== '__create_new__') {
            $select.val(currentVal);
        } else {
            $select.val('');
        }
    };

    $(document).ready(function() {

        // 1. Initialize all rendered row storage selects
        initMatrixRowsLocationSelects();

        // 2. Initialize bulk toolbar location select
        initBulkToolbarLocations();

        // 3. Setup global shortcut key F2 for opening Product Select Modal
        $(document).on('keydown', function(e) {
            if (e.key === 'F2') {
                e.preventDefault();
                openProductSelectModal();
            }
        });

        // 4. Click listener on all modal trigger buttons
        $(document).on('click', '.btn-open-prod-modal', function(e) {
            e.preventDefault();
            openProductSelectModal();
        });

        // 5. Live P-Code updates on Sky Price & Rot Price inputs
        $(document).on('input', '.field-sky-price', function() {
            const val = $(this).val();
            const $row = $(this).closest('tr');
            const code = window.encodeToPCode(val);
            $row.find('.field-sky-pcode').val(code);
            markRowModified($row);
        });

        $(document).on('input', '.field-rot-price', function() {
            const val = $(this).val();
            const $row = $(this).closest('tr');
            const code = window.encodeToPCode(val);
            $row.find('.field-rot-pcode').val(code);
            markRowModified($row);
        });

        // 6. Mark row modified on any field change
        $(document).on('input change', '.field-location, .field-stock, .field-conv, .field-weight, .field-cost', function() {
            const $row = $(this).closest('tr');
            markRowModified($row);
        });

        // 7. Enter key inside matrix inputs saves current row
        $(document).on('keydown', '.odoo-input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const $row = $(this).closest('tr');
                saveSingleRow($row);
            }
        });

        // 8. Single row save button click
        $(document).on('click', '.btn-save-row', function(e) {
            e.preventDefault();
            const $row = $(this).closest('tr');
            saveSingleRow($row);
        });

        // 9. Row Storage Mode Toggle (WH vs Shop)
        $(document).on('change', '.row-loc-type-radio', function() {
            var $cell = $(this).closest('.matrix-loc-cell');
            var $row = $(this).closest('tr');
            var isWh = ($(this).val() === 'warehouse');

            if (isWh) {
                $cell.find('.row-wh-select').show();
                $cell.find('.row-shop-badge').hide();
            } else {
                $cell.find('.row-wh-select').hide();
                $cell.find('.row-shop-badge').css('display', 'inline-flex');
            }

            var type = isWh ? 'warehouse' : 'shop';
            var whId = $cell.find('.row-wh-select').val();
            var $locSelect = $row.find('.field-location');
            var currentVal = $locSelect.val();

            window.renderLocationSelectOptions($locSelect, currentVal, type, whId);
            markRowModified($row);
        });

        $(document).on('change', '.row-wh-select', function() {
            var $cell = $(this).closest('.matrix-loc-cell');
            var $row = $(this).closest('tr');
            var whId = $(this).val();
            var $locSelect = $row.find('.field-location');
            var currentVal = $locSelect.val();

            window.renderLocationSelectOptions($locSelect, currentVal, 'warehouse', whId);
            markRowModified($row);
        });

        // 10. Quick Create Rack/Shelf trigger on '+ Create New...'
        $(document).on('change', '.field-location, #bulkRackShelfSelect', function() {
            var val = $(this).val();
            if (val === '__create_new__' || val === '__custom__') {
                $(this).val('');
                window.openQuickAddStorageLocationModal(this);
            }
        });

        // 11. Check All Rows Checkbox
        $('#checkAllRows').on('change', function() {
            const checked = $(this).is(':checked');
            $('.row-check').prop('checked', checked);
            updateCheckedCount();
        });

        $(document).on('change', '.row-check', function() {
            updateCheckedCount();
        });

        // 12. Modal search input typing with debounce
        $('#modalProductSearchInput').on('input', function() {
            const query = $(this).val();
            if (query.trim().length > 0) {
                $('#modalProductSearchClearWrap').show();
            } else {
                $('#modalProductSearchClearWrap').hide();
            }

            clearTimeout(productSearchDebounceTimer);
            productSearchDebounceTimer = setTimeout(function() {
                runProductSearch();
            }, 250);
        });

        // 13. Load Column Visibility Preferences
        loadColumnPreferences();
        $('.col-toggle').on('change', function() {
            const colClass = $(this).data('col');
            const isVisible = $(this).is(':checked');
            toggleColumn(colClass, isVisible);
            saveColumnPreferences();
        });

        // 14. Quick Add Storage Location Form Submission
        $('#quickAddStorageLocationForm').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $btn = $('#btnSubmitQuickStorageLoc');
            var origBtnText = $btn.html();

            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            $('#quickLocNameError').text('').hide();
            $('#quickLocNameInput').removeClass('is-invalid');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(res) {
                    $btn.prop('disabled', false).html(origBtnText);
                    var loc = res.location || {};
                    var locName = loc.name || $('#quickLocNameInput').val().trim();
                    var locCode = loc.code || $('#quickLocCodeInput').val().trim();
                    var locType = loc.type || $('input[name="type"]:checked', $form).val();
                    var whId = loc.warehouse_id || $('#quickLocWarehouseSelect').val();
                    var branchId = loc.branch_id || $('#quickLocBranchSelect').val();

                    // Push to global array
                    if (!window.allStorageLocations) window.allStorageLocations = [];
                    window.allStorageLocations.push({
                        id: loc.id || Date.now(),
                        name: locName,
                        code: locCode,
                        type: locType,
                        warehouse_id: whId,
                        branch_id: branchId
                    });

                    // Select in triggering select with AUTOMATIC SWITCHING
                    if (window.activeLocSelectForQuickAdd) {
                        var $sel = $(window.activeLocSelectForQuickAdd);
                        var $row = $sel.closest('tr');

                        if ($sel.attr('id') === 'bulkRackShelfSelect') {
                            // Bulk toolbar
                            if (locType === 'shop_shelf') {
                                $('#bulkLocShop').prop('checked', true);
                                $('#bulkWarehouseSelect').hide();
                                initBulkToolbarLocations();
                                $('#bulkRackShelfSelect').val(locName);
                            } else {
                                $('#bulkLocWh').prop('checked', true);
                                $('#bulkWarehouseSelect').val(whId).show();
                                initBulkToolbarLocations();
                                $('#bulkRackShelfSelect').val(locName);
                            }
                        } else if ($row.length) {
                            // Target Matrix Row
                            if (locType === 'shop_shelf') {
                                // Automatically switch this row to Shop!
                                $row.find('.row-loc-shop').prop('checked', true);
                                $row.find('.row-wh-select').hide();
                                $row.find('.row-shop-badge').css('display', 'inline-flex');
                                window.renderLocationSelectOptions($sel, locName, 'shop');
                                $sel.val(locName);
                            } else {
                                // Automatically switch this row to Warehouse!
                                $row.find('.row-loc-wh').prop('checked', true);
                                $row.find('.row-wh-select').val(whId).show();
                                $row.find('.row-shop-badge').hide();
                                window.renderLocationSelectOptions($sel, locName, 'warehouse', whId);
                                $sel.val(locName);
                            }
                            markRowModified($row);
                        }
                    }

                    // Refresh bulk toolbar options as well
                    initBulkToolbarLocations();

                    // Hide modal
                    $('#quickAddStorageLocationModal').modal('hide');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Created & Selected!',
                            text: (locType === 'shop_shelf' ? 'Shop Shelf "' : 'Warehouse Rack "') + locName + '" created and assigned!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(origBtnText);
                    var err = 'Failed to create storage location.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            err = errors.name[0];
                            $('#quickLocNameError').text(err).show();
                            $('#quickLocNameInput').addClass('is-invalid');
                            return;
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        err = xhr.responseJSON.message;
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', err, 'error');
                    } else {
                        alert(err);
                    }
                }
            });
        });

        // Quick Add Modal radio switcher
        $('input[name="type"]', '#quickAddStorageLocationForm').on('change', function() {
            var isWh = ($(this).val() === 'warehouse_rack');
            if (isWh) {
                $('#quickLocModalTitleText').text('Create New Warehouse Rack');
                $('#quickLocNameLabel').html('Rack Name <span class="text-danger">*</span>');
                $('#quickLocNameInput').attr('placeholder', 'e.g. Rack A-1, Rack 05');
                $('#quickLocCodeInput').attr('placeholder', 'e.g. RA-01');
                $('#quickLocWarehouseGroup').show();
                $('#quickLocBranchGroup').hide();
            } else {
                $('#quickLocModalTitleText').text('Create New Shop Shelf');
                $('#quickLocNameLabel').html('Shelf Name <span class="text-danger">*</span>');
                $('#quickLocNameInput').attr('placeholder', 'e.g. Shelf 01, Shelf 02');
                $('#quickLocCodeInput').attr('placeholder', 'e.g. SH-01');
                $('#quickLocWarehouseGroup').hide();
                $('#quickLocBranchGroup').show();
            }
        });

    });

    /**
     * Initialize location select dropdowns in matrix rows
     */
    function initMatrixRowsLocationSelects() {
        $('#matrixTbody tr.matrix-row').each(function() {
            var $row = $(this);
            var $cell = $row.find('.matrix-loc-cell');
            var isWh = $cell.find('.row-loc-wh').is(':checked');
            var type = isWh ? 'warehouse' : 'shop';
            var whId = $cell.find('.row-wh-select').val();
            var $locSelect = $row.find('.field-location');
            var curVal = $locSelect.data('current-val') || $locSelect.val();
            window.renderLocationSelectOptions($locSelect, curVal, type, whId);
        });
    }

    /**
     * Initialize the bulk toolbar rack/shelf select
     */
    function initBulkToolbarLocations() {
        var isWh = $('#bulkLocWh').is(':checked');
        var type = isWh ? 'warehouse' : 'shop';
        var whId = $('#bulkWarehouseSelect').val();
        var $select = $('#bulkRackShelfSelect');
        window.renderLocationSelectOptions($select, '', type, whId);
    }

    function onBulkLocTypeChange() {
        var isWh = $('#bulkLocWh').is(':checked');
        if (isWh) {
            $('#bulkWarehouseSelect').show();
        } else {
            $('#bulkWarehouseSelect').hide();
        }
        initBulkToolbarLocations();
    }

    function onBulkWarehouseChange() {
        initBulkToolbarLocations();
    }

    function updateCheckedCount() {
        var count = $('.row-check:checked').length;
        $('#checkedCountBadge').text(count);
    }

    function markRowModified($row) {
        const rowId = $row.data('row-id');
        $row.addClass('row-modified').removeClass('row-saved');
        modifiedRows.add(rowId);
        updateModifiedCount();
    }

    function updateModifiedCount() {
        const count = modifiedRows.size;
        $('.modified-count').text(count);
        if (count > 0) {
            $('#footerUnsavedMsg').show();
            $('#btnSaveBatchTop').removeClass('btn-outline-success').addClass('btn-warning text-dark');
        } else {
            $('#footerUnsavedMsg').hide();
            $('#btnSaveBatchTop').removeClass('btn-warning text-dark').addClass('btn-outline-success');
        }
    }

    function applyFilterType(type) {
        $('#filterTypeInput').val(type);
        $('#filterTypeInput').closest('form').submit();
    }

    /**
     * Open Product Selection Modal (Works with both Bootstrap 4 & 5)
     */
    window.openProductSelectModal = function() {
        if (window.jQuery && typeof $('#productSelectModal').modal === 'function') {
            $('#productSelectModal').modal('show');
        } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
            var modalEl = document.getElementById('productSelectModal');
            if (modalEl) {
                var inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                inst.show();
            }
        }

        setTimeout(function() {
            $('#modalProductSearchInput').focus();
            runProductSearch();
        }, 200);
    };

    function clearProductModalSearch() {
        $('#modalProductSearchInput').val('');
        $('#modalProductSearchClearWrap').hide();
        runProductSearch();
        $('#modalProductSearchInput').focus();
    }

    /**
     * Run AJAX Search for Product Selection Modal
     */
    function runProductSearch() {
        const q = $('#modalProductSearchInput').val();
        const catId = $('#modalCategoryFilter').val();
        const brandId = $('#modalBrandFilter').val();

        $('#modalProductSearchResultsTbody').html(`
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                    <div>Searching products...</div>
                </td>
            </tr>
        `);

        $.ajax({
            url: "{{ route('opening_stock.search_products') }}",
            type: "GET",
            data: {
                q: q,
                category_id: catId,
                brand_id: brandId,
                warehouse_id: selectedWarehouseId
            },
            success: function(resp) {
                if (!resp.success || !resp.products || resp.products.length === 0) {
                    $('#modalProductSearchResultsTbody').html(`
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 text-secondary opacity-50"></i>
                                <div class="fw-bold">No Products Found</div>
                                <div class="small">Try different keywords or reset category/brand filters.</div>
                            </td>
                        </tr>
                    `);
                    $('#modalResultsCountText').text('Showing 0 products');
                    return;
                }

                let html = '';
                resp.products.forEach(function(p) {
                    const isCurrent = (currentLoadedProductId === p.id);
                    
                    // Brand pill only if not '-' and not empty
                    const hasBrand = p.brand_name && p.brand_name !== '-' && p.brand_name.toLowerCase() !== 'no brand';
                    const brandPill = hasBrand 
                        ? `<span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; font-size: 10px; padding: 2.5px 7px; border-radius: 4px;"><i class="fas fa-tag me-1 text-secondary opacity-75"></i>${escapeHtml(p.brand_name)}</span>` 
                        : '';

                    // Barcode pill
                    const barcodePill = p.barcode 
                        ? `<div class="mt-1"><span class="badge font-monospace" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 9.5px; padding: 2px 6px; border-radius: 3px;"><i class="fas fa-barcode me-1 opacity-75"></i>${escapeHtml(p.barcode)}</span></div>` 
                        : '';

                    // Variant Pill
                    const variantPill = p.has_variants 
                        ? `<span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;"><i class="fas fa-layer-group me-1 opacity-75"></i>${p.variant_count} Variants</span>` 
                        : `<span class="badge" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 10.5px; padding: 3px 8px; border-radius: 4px;">Standard</span>`;

                    // Stock
                    const stockVal = parseFloat(p.current_stock) || 0;
                    const stockColor = stockVal > 0 ? '#0f172a' : '#94a3b8';

                    html += `
                        <tr class="${isCurrent ? 'op-modal-active-row' : ''}" style="${isCurrent ? 'background-color: #f0f7ff; border-left: 3px solid #2563eb;' : ''}">
                            <td class="align-middle">
                                <span class="badge font-monospace" style="background: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; font-weight: 700; font-size: 11px; padding: 3px 7px; border-radius: 4px;">
                                    ${escapeHtml(p.item_code)}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="fw-bold text-dark text-capitalize" style="font-size: 13.5px; line-height: 1.3;">${escapeHtml(p.item_name)}</div>
                                ${barcodePill}
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; font-size: 10px; padding: 2.5px 7px; border-radius: 4px;">
                                        <i class="fas fa-folder me-1 text-primary opacity-75"></i>${escapeHtml(p.category_name)}
                                    </span>
                                    ${brandPill}
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                ${variantPill}
                            </td>
                            <td class="text-center align-middle">
                                <span class="fw-bold font-monospace" style="font-size: 13px; color: ${stockColor};">${p.current_stock}</span> 
                                <span class="text-muted" style="font-size: 10.5px;">${escapeHtml(p.unit_name)}</span>
                            </td>
                            <td class="text-end align-middle">
                                ${isCurrent 
                                    ? `<span class="badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px; padding: 4.5px 10px; border-radius: 4px; font-weight: 600;"><i class="fas fa-check me-1"></i> Loaded</span>` 
                                    : `<button type="button" class="btn btn-sm btn-primary py-1 px-3 fw-semibold" onclick="selectProductFromModal(${p.id});" style="font-size: 11.5px; border-radius: 5px; background: #2563eb; border-color: #2563eb; box-shadow: 0 1px 2px rgba(37,99,235,0.2);">Select <i class="fas fa-arrow-right ms-1" style="font-size: 10px;"></i></button>`
                                }
                            </td>
                        </tr>
                    `;
                });

                $('#modalProductSearchResultsTbody').html(html);
                $('#modalResultsCountText').text(`Showing ${resp.products.length} products`);
            },
            error: function() {
                $('#modalProductSearchResultsTbody').html(`
                    <tr>
                        <td colspan="6" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <div>Failed to load products. Please check network and try again.</div>
                        </td>
                    </tr>
                `);
            }
        });
    }

    /**
     * User clicked "Select" on a product in the modal
     */
    function selectProductFromModal(productId) {
        // Close modal
        $('#productSelectModal').modal('hide');

        // Show loading state in workspace
        $('#opEmptyWorkspace').hide();
        $('#productHeroBar').show();
        $('#productBulkBar').show();
        $('#matrixContainer').show();
        $('#opFooterBar').show();

        $('#matrixTbody').html(`
            <tr>
                <td colspan="13" class="text-center py-5 text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                    <div class="fw-bold">Loading product matrix & variants...</div>
                </td>
            </tr>
        `);

        // Fetch product and rows via AJAX
        $.ajax({
            url: "{{ route('opening_stock.fetch_product') }}",
            type: "GET",
            data: {
                product_id: productId,
                warehouse_id: selectedWarehouseId
            },
            success: function(resp) {
                if (!resp.success || !resp.product) {
                    Swal.fire('Error', 'Product details could not be retrieved.', 'error');
                    return;
                }

                currentLoadedProductId = resp.product.id;

                // Update Hero Bar
                $('#heroProductCode').text(resp.product.code || 'NO-CODE');
                $('#heroProductName').text(resp.product.name);

                if (resp.product.category && resp.product.category !== '-') {
                    $('#heroProductCategory').html('<i class="fas fa-folder text-primary" style="margin-right: 6px;"></i>' + escapeHtml(resp.product.category)).css('display', 'inline-flex');
                } else {
                    $('#heroProductCategory').hide();
                }

                const brandVal = (resp.product.brand || '').trim();
                if (brandVal && brandVal !== '-' && brandVal.toLowerCase() !== 'no brand') {
                    $('#heroProductBrand').html('<i class="fas fa-tag text-secondary" style="margin-right: 6px;"></i>' + escapeHtml(brandVal)).css('display', 'inline-flex');
                } else {
                    $('#heroProductBrand').hide();
                }

                $('#heroVariantCount').text(resp.rows ? resp.rows.length : 0);
                $('#heroStockPieces').text(resp.product.wh_total_pieces || 0);
                $('#heroEditLink').attr('href', resp.product.edit_url || '#');

                $('#footerRowCountBadge').text((resp.rows ? resp.rows.length : 0) + ' Variants Loaded');

                // Render Rows
                renderProductMatrixRows(resp.rows);

                // Initialize column visibility
                loadColumnPreferences();

                // Sweetalert toast notification
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: `"${resp.product.name}" loaded into Matrix!`
                    });
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to fetch product details';
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    /**
     * Render fetched rows into table DOM (Odoo Standard with Segmented WH/Shop)
     */
    function renderProductMatrixRows(rows) {
        if (!rows || rows.length === 0) {
            $('#matrixTbody').html(`
                <tr>
                    <td colspan="13" class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 text-secondary opacity-50"></i>
                        <div class="fw-bold">No Variants Found for this Product</div>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        rows.forEach(function(row) {
            const sizeBadge = (row.attributes && row.attributes.size && row.attributes.size !== '-') ? `<span class="badge" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; font-weight: 500; padding: 2.5px 7px; border-radius: 4px; display: inline-flex; align-items: center;"><span style="color: #64748b; font-weight: 500; margin-right: 3px;">Size:</span><strong style="color: #0f172a; font-weight: 700;">${escapeHtml(row.attributes.size)}</strong></span>` : '';
            const colorBadge = (row.attributes && row.attributes.color && row.attributes.color !== '-') ? `<span class="badge" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 10.5px; font-weight: 500; padding: 2.5px 7px; border-radius: 4px; display: inline-flex; align-items: center;"><span style="color: #64748b; font-weight: 500; margin-right: 3px;">Color:</span><strong style="color: #0f172a; font-weight: 700;">${escapeHtml(row.attributes.color)}</strong></span>` : '';

            // Detect if row's location belongs to shop
            var isShopLoc = false;
            if (row.location && window.allStorageLocations) {
                var match = window.allStorageLocations.find(function(l) {
                    return String(l.name).trim().toLowerCase() === String(row.location).trim().toLowerCase();
                });
                if (match && match.type === 'shop_shelf') {
                    isShopLoc = true;
                }
            }

            html += `
                <tr class="matrix-row" id="row_${row.row_id}"
                    data-row-id="${row.row_id}"
                    data-product-id="${row.product_id}"
                    data-variant-idx="${row.variant_index}">
                    
                    <td class="col-select text-center">
                        <input type="checkbox" class="form-check-input row-check" data-row-id="${row.row_id}">
                    </td>

                    <td class="col-product">
                        <div class="d-flex flex-column py-1 justify-content-center">
                            <div class="fw-semibold text-dark text-capitalize" style="font-size: 13.5px; font-weight: 600; color: #0f172a; line-height: 1.35; letter-spacing: -0.1px;">
                                ${escapeHtml(row.variant_title)}
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 5px; margin-top: 4px;">
                                <span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 10px; font-weight: 700; padding: 2.5px 7px; border-radius: 4px; letter-spacing: 0.3px;">
                                    <i class="fas fa-hashtag me-0.5 opacity-75" style="font-size: 8.5px;"></i>${escapeHtml(row.serial_no)}
                                </span>
                                ${sizeBadge}
                                ${colorBadge}
                            </div>
                        </div>
                    </td>

                    <td class="col-storage-type">
                        <div class="matrix-loc-cell d-flex align-items-center gap-1.5" data-row-id="${row.row_id}">
                            <div class="loc-segment-switch">
                                <input type="radio" class="row-loc-type-radio row-loc-wh" name="loc_type_${row.row_id}" id="loc_wh_${row.row_id}" value="warehouse" ${!isShopLoc ? 'checked' : ''} autocomplete="off">
                                <label for="loc_wh_${row.row_id}" title="Warehouse Rack">WH</label>

                                <input type="radio" class="row-loc-type-radio row-loc-shop" name="loc_type_${row.row_id}" id="loc_shop_${row.row_id}" value="shop" ${isShopLoc ? 'checked' : ''} autocomplete="off">
                                <label for="loc_shop_${row.row_id}" title="Shop Shelf">Shop</label>
                            </div>
                            <select class="form-select form-select-sm row-wh-select" style="${isShopLoc ? 'display: none !important;' : ''}">
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ $selectedWarehouse->id == $wh->id ? 'selected' : '' }}>{{ $wh->warehouse_name }}</option>
                                @endforeach
                            </select>
                            <span class="badge row-shop-badge" style="${!isShopLoc ? 'display: none !important;' : 'display: inline-flex;'}"><i class="fas fa-store me-1 text-primary"></i>Shop</span>
                        </div>
                    </td>

                    <td class="col-location">
                        <select class="form-select odoo-input field-location"
                                name="location"
                                data-current-val="${escapeHtml(row.location || '')}">
                            <option value="">-- ${isShopLoc ? 'Select Shelf' : 'Select Rack'} --</option>
                            ${row.location ? `<option value="${escapeHtml(row.location)}" selected>${escapeHtml(row.location)}</option>` : ''}
                            <option value="__create_new__" style="color: #2563eb; font-weight: bold;">+ Create New...</option>
                        </select>
                    </td>

                    <td class="col-stock">
                        <input type="number" step="any" class="form-control odoo-input text-center fw-bold text-primary field-stock"
                               name="stock" value="${row.stock || 0}" placeholder="0">
                    </td>

                    <td class="col-conv">
                        <input type="number" step="any" class="form-control odoo-input text-center fw-bold text-success field-conv"
                               name="conv_factor" value="${row.conv_factor || 1}" placeholder="1">
                    </td>

                    <td class="col-weight">
                        <input type="number" step="any" class="form-control odoo-input text-end field-weight"
                               name="weight_per_piece" value="${row.weight_per_piece > 0 ? row.weight_per_piece : ''}" placeholder="0g">
                    </td>

                    <td class="col-sale">
                        <input type="number" step="any" class="form-control odoo-input text-end fw-bold field-sky-price"
                               name="sky_price" value="${parseFloat(row.sky_price || 0).toFixed(2)}" placeholder="0.00">
                    </td>

                    <td class="col-sky-pcode">
                        <input type="text" class="form-control odoo-pcode field-sky-pcode"
                               name="sky_pcode" value="${escapeHtml(row.sky_pcode || '')}" readonly>
                    </td>

                    <td class="col-cost">
                        <input type="number" step="any" class="form-control odoo-input text-end text-muted field-cost"
                               name="cost" value="${parseFloat(row.cost || 0).toFixed(2)}" placeholder="0.00">
                    </td>

                    <td class="col-wholesale">
                        <input type="number" step="any" class="form-control odoo-input text-end fw-bold field-rot-price"
                               name="rot_price" value="${parseFloat(row.rot_price || 0).toFixed(2)}" placeholder="0.00">
                    </td>

                    <td class="col-rot-pcode">
                        <input type="text" class="form-control odoo-pcode field-rot-pcode"
                               name="rot_pcode" value="${escapeHtml(row.rot_pcode || '')}" readonly>
                    </td>

                    <td class="col-action text-center">
                        <button type="button" class="odoo-btn-save btn-save-row" title="Save this row">
                            <i class="fas fa-save" style="font-size: 12px;"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#matrixTbody').html(html);

        // Re-initialize location dropdown options for these new rows
        initMatrixRowsLocationSelects();
    }

    /**
     * Bulk Assign Rack/Shelf Location to All or Checked variants
     */
    function applyBulkLocationToVariants(targetScope) {
        const isWh = $('#bulkLocWh').is(':checked');
        const whId = $('#bulkWarehouseSelect').val();
        const locVal = $('#bulkRackShelfSelect').val();

        if (!locVal) {
            Swal.fire('Please Select', 'Select a Rack or Shelf from the dropdown first.', 'info');
            return;
        }

        let $targetRows;
        if (targetScope === 'checked') {
            $targetRows = $('#matrixTbody tr.matrix-row').filter(function() {
                return $(this).find('.row-check').is(':checked');
            });
            if ($targetRows.length === 0) {
                Swal.fire('No Rows Checked', 'Please check at least one variant row checkbox first.', 'info');
                return;
            }
        } else {
            $targetRows = $('#matrixTbody tr.matrix-row');
        }

        $targetRows.each(function() {
            const $row = $(this);
            const $cell = $row.find('.matrix-loc-cell');

            if (isWh) {
                $cell.find('.row-loc-wh').prop('checked', true);
                $cell.find('.row-wh-select').val(whId).show();
                $cell.find('.row-shop-badge').hide();
            } else {
                $cell.find('.row-loc-shop').prop('checked', true);
                $cell.find('.row-wh-select').hide();
                $cell.find('.row-shop-badge').css('display', 'inline-flex');
            }

            const type = isWh ? 'warehouse' : 'shop';
            const $locSelect = $row.find('.field-location');

            // Render options and set val
            window.renderLocationSelectOptions($locSelect, locVal, type, whId);
            $locSelect.val(locVal);

            markRowModified($row);
        });

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Applied Successfully!',
                text: (isWh ? 'Warehouse Rack "' : 'Shop Shelf "') + locVal + '" applied to ' + $targetRows.length + ' variant(s).',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }

    /**
     * Save all active product rows currently loaded
     */
    function saveActiveProductRows() {
        $('#matrixTbody tr.matrix-row').each(function() {
            markRowModified($(this));
        });
        saveAllBatch();
    }

    /**
     * Single Row AJAX Save
     */
    function saveSingleRow($row) {
        const productId = $row.data('product-id');
        const variantIdx = $row.data('variant-idx');
        const rowId = $row.data('row-id');

        const location = $row.find('.field-location').val();
        const stock = $row.find('.field-stock').val();
        const conv = $row.find('.field-conv').val();
        const weight = $row.find('.field-weight').val();
        const skyPrice = $row.find('.field-sky-price').val();
        const cost = $row.find('.field-cost').val();
        const rotPrice = $row.find('.field-rot-price').val();

        // Detect warehouse ID for row
        const isWh = $row.find('.row-loc-wh').is(':checked');
        const whId = isWh ? ($row.find('.row-wh-select').val() || selectedWarehouseId) : selectedWarehouseId;

        const $btn = $row.find('.btn-save-row');
        const origHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: "{{ route('opening_stock.save_row') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                warehouse_id: whId,
                product_id: productId,
                variant_index: variantIdx,
                location: location,
                stock: stock,
                conv_factor: conv,
                weight_per_piece: weight,
                sky_price: skyPrice,
                cost: cost,
                rot_price: rotPrice,
            },
            success: function(resp) {
                $btn.html('<i class="fas fa-check text-success"></i>');
                setTimeout(() => { $btn.html(origHtml).prop('disabled', false); }, 1500);

                if (resp.success) {
                    $row.removeClass('row-modified').addClass('row-saved');
                    $row.find('.field-sky-pcode').val(resp.sky_pcode || '');
                    $row.find('.field-rot-pcode').val(resp.rot_pcode || '');
                    modifiedRows.delete(rowId);
                    updateModifiedCount();
                }
            },
            error: function(xhr) {
                $btn.html(origHtml).prop('disabled', false);
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error saving row';
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    /**
     * Save All Modified Rows (Batch Save)
     */
    function saveAllBatch() {
        if (modifiedRows.size === 0) {
            Swal.fire('Notice', 'No unsaved changes detected in the matrix.', 'info');
            return;
        }

        const rowsData = [];
        modifiedRows.forEach(rowId => {
            const $row = $(`#row_${rowId}`);
            if ($row.length) {
                rowsData.push({
                    product_id: $row.data('product-id'),
                    variant_index: $row.data('variant-idx'),
                    location: $row.find('.field-location').val(),
                    stock: $row.find('.field-stock').val(),
                    conv_factor: $row.find('.field-conv').val(),
                    weight_per_piece: $row.find('.field-weight').val(),
                    sky_price: $row.find('.field-sky-price').val(),
                    cost: $row.find('.field-cost').val(),
                    rot_price: $row.find('.field-rot-price').val(),
                });
            }
        });

        const $saveBtn = $('#btnSaveBatchTop');
        $saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: "{{ route('opening_stock.save_batch') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                warehouse_id: selectedWarehouseId,
                rows: rowsData,
            },
            success: function(resp) {
                $saveBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes (<span class="modified-count">0</span>)');

                if (resp.success) {
                    modifiedRows.forEach(rowId => {
                        $(`#row_${rowId}`).removeClass('row-modified').addClass('row-saved');
                    });
                    modifiedRows.clear();
                    updateModifiedCount();

                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: resp.message || 'All changes saved to warehouse inventory!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                $saveBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes (<span class="modified-count">' + modifiedRows.size + '</span>)');
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error batch saving';
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    function discardActiveEdits() {
        if (modifiedRows.size === 0) {
            Swal.fire('Notice', 'No unsaved edits to discard.', 'info');
            return;
        }

        Swal.fire({
            title: 'Discard Unsaved Edits?',
            text: 'Are you sure you want to discard your unsaved changes? The product variants will reload.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Discard',
            confirmButtonColor: '#dc2626'
        }).then((result) => {
            if (result.isConfirmed && currentLoadedProductId) {
                modifiedRows.clear();
                updateModifiedCount();
                selectProductFromModal(currentLoadedProductId);
            }
        });
    }

    /**
     * Quick Add Rack/Shelf Modal Trigger
     */
    window.openQuickAddStorageLocationModal = function(triggerSelect) {
        window.activeLocSelectForQuickAdd = triggerSelect;
        var $row = triggerSelect ? $(triggerSelect).closest('tr') : null;
        var isWh = true;
        var whId = null;

        if ($row && $row.length) {
            isWh = $row.find('.row-loc-wh').is(':checked');
            whId = $row.find('.row-wh-select').val();
        } else if (triggerSelect && triggerSelect.id === 'bulkRackShelfSelect') {
            isWh = $('#bulkLocWh').is(':checked');
            whId = $('#bulkWarehouseSelect').val();
        }

        var modalEl = document.getElementById('quickAddStorageLocationModal');
        if (!modalEl) return;

        var form = document.getElementById('quickAddStorageLocationForm');
        if (form) form.reset();
        $('#quickLocNameError').text('').hide();
        $('#quickLocNameInput').removeClass('is-invalid');

        if (isWh) {
            $('#quickLocTypeRack').prop('checked', true);
            $('#quickLocModalTitleText').text('Create New Warehouse Rack');
            $('#quickLocNameLabel').html('Rack Name <span class="text-danger">*</span>');
            $('#quickLocNameInput').attr('placeholder', 'e.g. Rack A-1, Rack 05');
            $('#quickLocCodeInput').attr('placeholder', 'e.g. RA-01');
            $('#quickLocWarehouseGroup').show();
            $('#quickLocBranchGroup').hide();
            if (whId) $('#quickLocWarehouseSelect').val(whId);
        } else {
            $('#quickLocTypeShelf').prop('checked', true);
            $('#quickLocModalTitleText').text('Create New Shop Shelf');
            $('#quickLocNameLabel').html('Shelf Name <span class="text-danger">*</span>');
            $('#quickLocNameInput').attr('placeholder', 'e.g. Shelf 01, Shelf 02');
            $('#quickLocCodeInput').attr('placeholder', 'e.g. SH-01');
            $('#quickLocWarehouseGroup').hide();
            $('#quickLocBranchGroup').show();
        }

        $('#quickAddStorageLocationModal').modal('show');

        setTimeout(function() {
            $('#quickLocNameInput').focus();
        }, 300);
    };

    /**
     * Column Visibility Management
     */
    function toggleColumn(colClass, isVisible) {
        if (isVisible) {
            $(`.${colClass}`).removeClass('col-hidden');
        } else {
            $(`.${colClass}`).addClass('col-hidden');
        }
    }

    function saveColumnPreferences() {
        const prefs = {};
        $('.col-toggle').each(function() {
            prefs[$(this).data('col')] = $(this).is(':checked');
        });
        localStorage.setItem('op_stock_col_prefs', JSON.stringify(prefs));
    }

    function loadColumnPreferences() {
        try {
            const saved = localStorage.getItem('op_stock_col_prefs');
            if (saved) {
                const prefs = JSON.parse(saved);
                for (const colClass in prefs) {
                    const isVisible = prefs[colClass];
                    $(`#toggle_${colClass}`).prop('checked', isVisible);
                    toggleColumn(colClass, isVisible);
                }
            }
        } catch(e) {}
    }

    function resetDefaultColumns() {
        $('.col-toggle').prop('checked', true);
        $('.col-toggle').each(function() {
            toggleColumn($(this).data('col'), true);
        });
        localStorage.removeItem('op_stock_col_prefs');
    }
</script>
@endsection
