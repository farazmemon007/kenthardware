@extends('admin_panel.layout.app')
@section('title', 'Product Import Preview - ERP & QuickBooks')

@section('content')
<style>
    /* ═══════════════════════════════════════════════════════════════════
       Odoo & QuickBooks POS High-Density Compact ERP Import Preview
       ═══════════════════════════════════════════════════════════════════ */
    .qb-preview-wrap {
        background: #f8fafc;
        height: calc(100vh - 65px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .qb-control-panel {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        flex-shrink: 0;
        z-index: 1020;
    }
    .qb-pill-item-num {
        display: inline-block;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 700;
        font-size: 11.5px;
        color: #1e40af;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 1px 6px;
        border-radius: 4px;
        letter-spacing: 0.2px;
    }
    .qb-table-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        min-height: 0;
        overflow: hidden;
        margin: 8px 16px 8px 16px;
    }
    .qb-table-scroll-area {
        flex-grow: 1;
        overflow-y: auto;
        min-height: 0;
    }
    .qb-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .qb-table thead th {
        background: #f1f5f9;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 8px 10px;
        border-bottom: 2px solid #cbd5e1;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .qb-table td {
        padding: 6px 10px;
        font-size: 12px;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .qb-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .qb-tag {
        display: inline-block;
        padding: 1px 5px;
        font-size: 10.5px;
        font-weight: 600;
        border-radius: 3px;
        white-space: nowrap;
    }
    .qb-tag-cat {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .qb-tag-size {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .qb-tag-create {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .qb-tag-update {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .qb-search-input {
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 12px;
        padding: 4px 8px 4px 28px;
        width: 240px;
        height: 30px;
        transition: all 0.2s ease;
    }
    .qb-search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
        outline: none;
    }
    .qb-tab-btn {
        border: none;
        background: transparent;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 2px solid transparent;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .qb-tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
        background: #eff6ff;
    }
    .qb-chip-scroll {
        max-height: 220px;
        overflow-y: auto;
        padding: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .qb-chip {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 12px;
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        white-space: nowrap;
    }
    .variant-drawer-row {
        background: #f8fafc;
        display: none;
    }
    .variant-drawer-row.show {
        display: table-row;
    }
</style>

<div class="content-wrapper p-0 qb-preview-wrap">

    @php
        $catCount = count($payload['master_data']['categories'] ?? []);
        $brandCount = count($payload['master_data']['brands'] ?? []);
        $errorCount = count($payload['errors'] ?? []);
        $totalVariants = $payload['total_variants'] ?? 0;
        $totalProducts = count($payload['products'] ?? []);
    @endphp

    {{-- ══════════════════════════════════════════════════════════════
         1. COMPACT ULTRA-SLIM CONTROL PANEL (ZERO WASTED HEIGHT)
         ══════════════════════════════════════════════════════════════ --}}
    <div class="qb-control-panel py-2 px-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <!-- Left: Title & Inline KPI Badges -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <a href="{{ route('product') }}" class="text-secondary me-1" title="Back to Catalog" style="font-size: 14px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-1.5" style="font-size: 15px;">
                    <i class="fas fa-file-excel text-success"></i> Import Preview
                </h5>

                <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2" style="font-size: 11px;">
                    <i class="fas fa-check-circle me-1"></i> Validated
                </span>

                <!-- KPI Badges (Merged into header to save 120px) -->
                <span class="badge bg-info-subtle text-dark border border-info-subtle py-1 px-2.5" style="font-size: 11.5px; background:#eff6ff;" title="Total Unique Rows / Variants from your file">
                    <i class="fas fa-file-excel text-success me-1"></i><strong>{{ number_format($totalVariants) }}</strong> Total Items in File
                </span>

                <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2.5" style="font-size: 11.5px;" title="Grouped into Master Products by Item Name">
                    <i class="fas fa-layer-group me-1"></i><strong>{{ number_format($totalProducts) }}</strong> Grouped Products (Odoo Style)
                </span>

                @if($catCount > 0 || $brandCount > 0)
                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-semibold d-inline-flex align-items-center gap-1" data-toggle="modal" data-target="#masterDataModal" style="height: 22px; font-size: 11px; border-radius: 4px;" title="Click to view auto-created master data">
                    <i class="fas fa-magic text-warning"></i> Auto Master: <strong>{{ $catCount }}</strong> Cats, <strong>{{ $brandCount }}</strong> Brands
                </button>
                @endif

                @if($errorCount > 0)
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2" style="font-size: 11px; cursor: pointer;" data-toggle="modal" data-target="#errorsModal" title="Click to view skipped empty lines">
                    <i class="fas fa-info-circle me-1"></i> {{ $errorCount }} empty row(s) skipped
                </span>
                @endif
            </div>

            <!-- Right: Action Buttons -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('product') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 fw-semibold" style="font-size: 11.5px; border-radius: 6px; height: 30px; display: inline-flex; align-items: center;">
                    <i class="fas fa-times me-1"></i> Discard
                </a>
                @if($totalVariants > 0 || $totalProducts > 0)
                <button type="button" id="btnConfirmImport" class="btn btn-success btn-sm py-1 px-3 fw-bold shadow-sm d-inline-flex align-items-center" style="background: linear-gradient(135deg, #10b981, #059669); border: none; font-size: 12px; height: 30px; border-radius: 6px;">
                    <i class="fas fa-cloud-upload-alt me-1.5" style="font-size: 13px;"></i>
                    <span>Confirm &amp; Import ({{ number_format($totalVariants) }} Items)</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         2. HIGH-DENSITY ERP DATA TABLE CONTAINER (MAXIMIZED VIEWPORT)
         ══════════════════════════════════════════════════════════════ --}}
    <div class="qb-table-container">

        <!-- Toolbar: Tabs + Search + Pagination -->
        <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom px-2 py-1 bg-white gap-2" style="flex-shrink: 0;">
            <div class="d-flex align-items-center">
                <button type="button" class="qb-tab-btn active" id="tabFlatViewBtn">
                    <i class="fas fa-list"></i> All Items Grid (QuickBooks Style)
                    <span class="badge bg-primary text-white ms-1 rounded-pill" id="flatItemCountBadge" style="font-size: 10.5px;">{{ number_format($totalVariants) }}</span>
                </button>
                <button type="button" class="qb-tab-btn" id="tabGroupedViewBtn">
                    <i class="fas fa-layer-group"></i> Grouped by Product (Odoo Style)
                    <span class="badge bg-secondary text-white ms-1 rounded-pill" style="font-size: 10.5px;">{{ number_format($totalProducts) }}</span>
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <i class="fas fa-search text-muted position-absolute" style="left: 9px; top: 8px; font-size: 11px;"></i>
                    <input type="text" id="previewSearchInput" class="qb-search-input" placeholder="Search Item #, Name, Size, Dept...">
                </div>
                <select id="pageSizeSelect" class="form-select form-control form-control-sm py-0 px-2" style="width: 105px; height: 30px; font-size: 11.5px; border-radius: 6px;">
                    <option value="50">50 / page</option>
                    <option value="100" selected>100 / page</option>
                    <option value="250">250 / page</option>
                    <option value="all">All items</option>
                </select>
                <div class="text-muted ms-1" style="font-size: 11.5px;" id="paginationInfo">
                    Showing 0 to 0 of 0
                </div>
                <div class="d-flex align-items-center gap-1 ms-1" id="paginationControls">
                    {{-- Mini Pagination Buttons --}}
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════
             VIEW 1: QUICKBOOKS FLAT ITEM GRID (FULL SCROLLABLE AREA)
             ══════════════════════════════════════════════════════════════ -->
        <div id="flatGridView" class="qb-table-scroll-area">
            <table class="table qb-table mb-0" id="qbFlatTable">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th style="width: 85px;" class="text-center">Item #</th>
                        <th>Item Name</th>
                        <th>Attribute / Color</th>
                        <th style="width: 110px;">Size</th>
                        <th>Department</th>
                        <th style="width: 95px;" class="text-end">Cost</th>
                        <th style="width: 95px;" class="text-end">Sale Price</th>
                        <th style="width: 75px;" class="text-center">Stock</th>
                        <th>P-Code / CTSS</th>
                        <th>Location</th>
                        <th style="width: 70px;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="flatTableBody">
                    {{-- Rendered dynamically by high-performance JavaScript pagination --}}
                </tbody>
            </table>
        </div>

        <!-- ══════════════════════════════════════════════════════════════
             VIEW 2: ODOO GROUPED BY PRODUCT HIERARCHY
             ══════════════════════════════════════════════════════════════ -->
        <div id="groupedGridView" class="qb-table-scroll-area" style="display: none;">
            <table class="table qb-table mb-0" id="qbGroupedTable">
                <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th style="width: 120px;">Ref / Code</th>
                        <th>Master Product Name</th>
                        <th>Category / Department</th>
                        <th>Brand / Vendor</th>
                        <th style="width: 110px;" class="text-center">Variants</th>
                        <th style="width: 90px;" class="text-center">Unit</th>
                        <th style="width: 100px;" class="text-end">Base Price</th>
                        <th style="width: 75px;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="groupedTableBody">
                    @forelse($payload['products'] ?? [] as $ref => $pData)
                        @php
                            $exists = App\Models\Product::where('item_code', $ref)->exists();
                            $vCount = count($pData['variants'] ?? []);
                            $firstVar = $pData['variants'][0] ?? [];
                        @endphp
                        <tr class="product-parent-row" data-ref="{{ $ref }}" style="cursor: pointer;">
                            <td class="text-center text-muted">
                                <i class="fas fa-chevron-right toggle-icon" style="font-size: 10px; transition: transform 0.2s ease;"></i>
                            </td>
                            <td><span class="font-monospace fw-bold text-dark" style="font-size: 11.5px;">{{ $ref }}</span></td>
                            <td>
                                <strong class="text-primary">{{ $pData['name'] }}</strong>
                                @if(!empty($firstVar['item_number']))
                                    <span class="qb-pill-item-num ms-1">#{{ $firstVar['item_number'] }}</span>
                                @endif
                            </td>
                            <td><span class="qb-tag qb-tag-cat">{{ $pData['category'] ?: 'Unassigned' }}</span></td>
                            <td><span class="text-muted">{{ $pData['brand'] ?: '-' }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-0.5" style="font-size: 11px;">
                                    <i class="fas fa-tags me-1"></i>{{ $vCount }} Variant{{ $vCount > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $firstVar['unit'] ?? 'Pcs' }}</span></td>
                            <td class="text-end fw-bold">Rs {{ number_format($firstVar['sale_price'] ?? 0, 2) }}</td>
                            <td class="text-center">
                                @if($exists)
                                    <span class="qb-tag qb-tag-update">Update</span>
                                @else
                                    <span class="qb-tag qb-tag-create">Create</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="variant-drawer-row" id="drawer-{{ $ref }}">
                            <td colspan="9" class="p-0">
                                <div class="p-2.5 bg-light border-top border-bottom drawer-content" data-ref="{{ $ref }}" data-name="{{ $pData['name'] }}">
                                    {{-- Dynamically populated on click --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No products found to preview.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ══════════════════════════════════════════════════════════════
             3. FOOTER PAGINATION BAR (ALWAYS VISIBLE AT BOTTOM OF TABLE)
             ══════════════════════════════════════════════════════════════ -->
        <div class="d-flex flex-wrap align-items-center justify-content-between border-top px-3 py-2 bg-light gap-2" style="flex-shrink: 0;" id="bottomPaginationBar">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 12px;" id="bottomPaginationInfo">
                    Showing 1–100 of {{ number_format($totalVariants) }}
                </span>
                <span class="badge bg-white text-dark border py-1 px-2" style="font-size: 11px;">
                    <i class="fas fa-check-circle text-success me-1"></i> All <strong>{{ number_format($totalVariants) }}</strong> rows ready to import
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fw-semibold" id="btnShowAllItems" style="height: 26px; font-size: 11.5px; border-radius: 4px;">
                    <i class="fas fa-list-ol me-1"></i> View All {{ number_format($totalVariants) }} Items on Screen
                </button>
                <div class="d-flex align-items-center gap-1" id="bottomPaginationControls">
                    {{-- Synced Pagination Buttons --}}
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL: AUTO-CREATED MASTER DATA (CLEAN ODOO MODAL DIALOG)
     ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="masterDataModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-magic text-primary fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-dark">Master Data Auto-Registration</h6>
                        <small class="text-muted">Will be automatically created during import if not already registered</small>
                    </div>
                </div>
                <button type="button" class="close text-secondary" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="border:none; background:transparent; font-size:22px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <div class="col-md-5 border-end">
                        <label class="fw-bold text-dark mb-1 d-flex justify-content-between align-items-center" style="font-size: 12px;">
                            <span><i class="fas fa-sitemap text-purple me-1"></i> Categories ({{ $catCount }}):</span>
                        </label>
                        <div class="qb-chip-scroll" style="max-height: 280px;">
                            @foreach($payload['master_data']['categories'] ?? [] as $cat)
                                <span class="qb-chip"><i class="fas fa-tag text-muted me-1"></i>{{ $cat }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="fw-bold text-dark mb-0" style="font-size: 12px;"><i class="fas fa-building text-warning me-1"></i> Brands / Vendors ({{ $brandCount }}):</label>
                            <input type="text" id="brandChipFilter" class="form-control form-control-sm py-0 px-2" placeholder="Search brands..." style="width: 140px; font-size: 11px; height: 26px;">
                        </div>
                        <div class="qb-chip-scroll" id="brandChipContainer" style="max-height: 280px;">
                            @foreach($payload['master_data']['brands'] ?? [] as $b)
                                <span class="qb-chip brand-chip">{{ $b }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top">
                <button type="button" class="btn btn-sm btn-primary px-3" data-dismiss="modal" data-bs-dismiss="modal">Got it</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL: SKIPPED ROWS DETAILS
     ══════════════════════════════════════════════════════════════ --}}
@if($errorCount > 0)
<div class="modal fade" id="errorsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-warning fs-5"></i>
                    <h6 class="modal-title fw-bold mb-0 text-dark">Skipped Empty / Blank Rows ({{ $errorCount }})</h6>
                </div>
                <button type="button" class="close text-secondary" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="border:none; background:transparent; font-size:22px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="text-muted mb-2" style="font-size: 12px;">
                    The following trailing or incomplete rows in your Excel file have no Item Name / data and will be safely skipped:
                </div>
                <ul class="mb-0 text-dark ps-3" style="max-height: 180px; overflow-y: auto; font-size: 12px;">
                    @foreach($payload['errors'] as $err)
                        <li>Line {{ $err['row'] }}: {{ $err['msg'] }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════
     MODAL: IMPORT PROGRESS OVERLAY (ODOO / QUICKBOOKS ANIMATED)
     ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="importProgressModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="spinner-grow text-success" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Importing...</span>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="modalProgressTitle">Importing Products to Inventory</h5>
                <p class="text-muted mb-3" style="font-size: 12.5px;" id="modalProgressSubtitle">
                    Please keep this window open while Odoo / QuickBooks engine writes variants and stocks into the database.
                </p>

                <div class="progress mb-2" style="height: 20px; border-radius: 10px; background: #e2e8f0; overflow: hidden;">
                    <div id="modalProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%; font-weight: 700; font-size: 11px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>

                <div class="d-flex justify-content-between text-muted" style="font-size: 11.5px;">
                    <span id="modalProgressCount">Processed 0 of 0</span>
                    <span id="modalProgressPercent">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
// Pass full flattened items from backend to client JavaScript for blazing instant search & pagination
window.PREVIEW_FLAT_ITEMS = @json($payload['flat_items'] ?? []);

$(document).ready(function () {
    let allItems = window.PREVIEW_FLAT_ITEMS || [];
    let filteredItems = allItems.slice();
    let currentPage = 1;
    let pageSize = 100;

    // ── Helper: Format Currency ──
    function formatMoney(num) {
        return 'Rs ' + parseFloat(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // ── Helper: Escape HTML ──
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ── Render Flat Table Page ──
    function renderTable() {
        let total = filteredItems.length;
        let tbody = $('#flatTableBody');
        tbody.empty();

        if (total === 0) {
            tbody.html('<tr><td colspan="12" class="text-center py-4 text-muted"><i class="fas fa-search me-1"></i> No matching products found for your search filter.</td></tr>');
            $('#paginationInfo, #bottomPaginationInfo').text('0 items');
            $('#paginationControls, #bottomPaginationControls').empty();
            return;
        }

        let start = 0;
        let end = total;

        if (pageSize !== 'all') {
            let limit = parseInt(pageSize, 10);
            let totalPages = Math.ceil(total / limit);
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;
            start = (currentPage - 1) * limit;
            end = Math.min(start + limit, total);
        }

        let rowsHtml = '';
        for (let i = start; i < end; i++) {
            let it = filteredItems[i];
            let itemNumBadge = it.item_number 
                ? '<span class="qb-pill-item-num">#' + esc(it.item_number) + '</span>' 
                : '<span class="text-muted">-</span>';

            let sizeBadge = (it.size && it.size !== '-') 
                ? '<span class="qb-tag qb-tag-size">' + esc(it.size) + '</span>' 
                : '<span class="text-muted">-</span>';

            let catBadge = it.category 
                ? '<span class="qb-tag qb-tag-cat">' + esc(it.category) + '</span>' 
                : '<span class="text-muted">-</span>';

            let pcodeBadge = it.p_code 
                ? '<span class="font-monospace text-secondary" style="font-size:11px;">' + esc(it.p_code) + '</span>' 
                : '<span class="text-muted">-</span>';

            rowsHtml += '<tr>' +
                '<td class="text-center text-muted" style="font-size:10.5px;">' + (i + 1) + '</td>' +
                '<td class="text-center">' + itemNumBadge + '</td>' +
                '<td><strong class="text-dark">' + esc(it.name) + '</strong></td>' +
                '<td>' + (esc(it.attribute) || '-') + '</td>' +
                '<td>' + sizeBadge + '</td>' +
                '<td>' + catBadge + '</td>' +
                '<td class="text-end text-muted">' + formatMoney(it.cost) + '</td>' +
                '<td class="text-end fw-bold text-dark">' + formatMoney(it.sale_price) + '</td>' +
                '<td class="text-center"><strong>' + it.stock + '</strong> <small class="text-muted">' + esc(it.unit) + '</small></td>' +
                '<td>' + pcodeBadge + '</td>' +
                '<td>' + (esc(it.location) || '-') + '</td>' +
                '<td class="text-center"><span class="qb-tag qb-tag-create">Create</span></td>' +
            '</tr>';
        }

        tbody.html(rowsHtml);

        // Update Info Text & Pagination Controls (Both Top and Bottom)
        if (pageSize === 'all') {
            var allText = 'All ' + total.toLocaleString() + ' items showing';
            $('#paginationInfo').text(allText);
            $('#bottomPaginationInfo').text(allText);
            $('#paginationControls, #bottomPaginationControls').empty();
            $('#btnShowAllItems').html('<i class="fas fa-compress me-1"></i> Switch to Paginated (100 / page)');
        } else {
            let limit = parseInt(pageSize, 10);
            let totalPages = Math.ceil(total / limit);
            var pageInfo = '<strong>' + (start + 1).toLocaleString() + '–' + end.toLocaleString() + '</strong> of <strong>' + total.toLocaleString() + '</strong>';
            $('#paginationInfo').html(pageInfo);
            $('#bottomPaginationInfo').html('Showing ' + pageInfo + ' items');

            renderPaginationControls(totalPages);
            $('#btnShowAllItems').html('<i class="fas fa-list-ol me-1"></i> View All ' + total.toLocaleString() + ' Items on Screen');
        }
    }

    function renderPaginationControls(totalPages) {
        let controls = $('#paginationControls, #bottomPaginationControls');
        controls.empty();
        if (totalPages <= 1) return;

        let prevDisabled = currentPage === 1 ? 'disabled' : '';
        let nextDisabled = currentPage === totalPages ? 'disabled' : '';

        controls.each(function() {
            var $c = $(this);
            $c.append('<button type="button" class="btn btn-sm btn-light border py-0 px-2 prev-page-btn" ' + prevDisabled + ' style="height:26px;font-size:11px;" title="Previous Page"><i class="fas fa-chevron-left"></i></button>');
            $c.append('<span class="px-1 text-muted fw-bold" style="font-size:11.5px;">' + currentPage + ' / ' + totalPages + '</span>');
            $c.append('<button type="button" class="btn btn-sm btn-light border py-0 px-2 next-page-btn" ' + nextDisabled + ' style="height:26px;font-size:11px;" title="Next Page"><i class="fas fa-chevron-right"></i></button>');
        });
    }

    // Initial render
    renderTable();

    // ── Pagination Events (Handles both top & bottom buttons) ──
    $(document).on('click', '.prev-page-btn, #prevPageBtn', function () {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
            $('#flatGridView').scrollTop(0);
        }
    });

    $(document).on('click', '.next-page-btn, #nextPageBtn', function () {
        let limit = parseInt(pageSize, 10);
        let totalPages = Math.ceil(filteredItems.length / limit);
        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
            $('#flatGridView').scrollTop(0);
        }
    });

    $('#btnShowAllItems').on('click', function () {
        if (pageSize === 'all') {
            pageSize = 100;
            $('#pageSizeSelect').val('100');
        } else {
            pageSize = 'all';
            $('#pageSizeSelect').val('all');
        }
        currentPage = 1;
        renderTable();
    });

    $('#pageSizeSelect').on('change', function () {
        pageSize = $(this).val();
        currentPage = 1;
        renderTable();
    });

    // ── Instant Live Search Filter ──
    let searchTimeout = null;
    $('#previewSearchInput').on('input', function () {
        clearTimeout(searchTimeout);
        let query = $(this).val().toLowerCase().trim();
        searchTimeout = setTimeout(function () {
            if (!query) {
                filteredItems = allItems.slice();
            } else {
                filteredItems = allItems.filter(function (it) {
                    return (it.item_number && String(it.item_number).toLowerCase().indexOf(query) !== -1) ||
                           (it.name && it.name.toLowerCase().indexOf(query) !== -1) ||
                           (it.size && it.size.toLowerCase().indexOf(query) !== -1) ||
                           (it.attribute && it.attribute.toLowerCase().indexOf(query) !== -1) ||
                           (it.category && it.category.toLowerCase().indexOf(query) !== -1) ||
                           (it.p_code && it.p_code.toLowerCase().indexOf(query) !== -1) ||
                           (it.location && it.location.toLowerCase().indexOf(query) !== -1);
                });
            }
            currentPage = 1;
            $('#flatItemCountBadge').text(filteredItems.length.toLocaleString());
            renderTable();

            // Filter Grouped View
            if (query) {
                $('#qbGroupedTable tbody tr.product-parent-row').each(function () {
                    let text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(query) !== -1);
                });
            } else {
                $('#qbGroupedTable tbody tr.product-parent-row').show();
            }
        }, 150);
    });

    // ── Tab Switching (Flat vs Grouped) ──
    $('#tabFlatViewBtn').on('click', function () {
        $(this).addClass('active');
        $('#tabGroupedViewBtn').removeClass('active');
        $('#flatGridView').show();
        $('#groupedGridView').hide();
        $('#paginationInfo, #paginationControls, #pageSizeSelect').show();
    });

    $('#tabGroupedViewBtn').on('click', function () {
        $(this).addClass('active');
        $('#tabFlatViewBtn').removeClass('active');
        $('#flatGridView').hide();
        $('#groupedGridView').show();
        $('#paginationInfo, #paginationControls, #pageSizeSelect').hide();
    });

    // ── Grouped View: Toggle Drawer on Click & Dynamically Render Variants ──
    $(document).on('click', '.product-parent-row', function () {
        let ref = $(this).data('ref');
        let drawer = $('#drawer-' + ref);
        let icon = $(this).find('.toggle-icon');

        if (drawer.hasClass('show')) {
            drawer.removeClass('show');
            icon.css('transform', 'rotate(0deg)');
        } else {
            let contentDiv = drawer.find('.drawer-content');
            if (contentDiv.is(':empty') || contentDiv.text().trim() === '') {
                let prodName = contentDiv.data('name') || ref;
                let pVars = allItems.filter(function (it) { return it.parent_ref === ref || it.ref === ref; });
                
                let subRows = '';
                pVars.forEach(function (v) {
                    let itNum = v.item_number ? '<span class="qb-pill-item-num">#' + esc(v.item_number) + '</span>' : '<span class="text-muted">-</span>';
                    let sz = (v.size && v.size !== '-') ? '<span class="qb-tag qb-tag-size">' + esc(v.size) + '</span>' : '<span class="text-muted">-</span>';
                    
                    subRows += '<tr>' +
                        '<td class="text-center">' + itNum + '</td>' +
                        '<td>' + esc(v.name) + '</td>' +
                        '<td>' + sz + '</td>' +
                        '<td>' + (esc(v.attribute) || '-') + '</td>' +
                        '<td class="text-end text-muted">' + formatMoney(v.cost) + '</td>' +
                        '<td class="text-end fw-bold text-dark">' + formatMoney(v.sale_price) + '</td>' +
                        '<td class="text-center"><strong>' + v.stock + '</strong> <small class="text-muted">' + esc(v.unit) + '</small></td>' +
                        '<td>' + (esc(v.location) || '-') + '</td>' +
                        '<td><span class="font-monospace text-muted">' + (esc(v.p_code) || '-') + '</span></td>' +
                    '</tr>';
                });

                contentDiv.html(
                    '<div class="fw-bold text-secondary mb-1.5" style="font-size: 11px; text-transform: uppercase;">' +
                        '<i class="fas fa-list-ul me-1"></i> Variants of ' + esc(prodName) + ' (' + pVars.length + ' items):' +
                    '</div>' +
                    '<table class="table table-sm table-bordered bg-white mb-0" style="font-size: 11.5px;">' +
                        '<thead class="bg-light text-dark">' +
                            '<tr>' +
                                '<th style="width: 75px;" class="text-center">Item #</th>' +
                                '<th>Variant Name</th>' +
                                '<th>Size</th>' +
                                '<th>Attribute / Color</th>' +
                                '<th class="text-end" style="width: 85px;">Cost</th>' +
                                '<th class="text-end" style="width: 85px;">Sale Price</th>' +
                                '<th class="text-center" style="width: 75px;">Stock</th>' +
                                '<th>Location</th>' +
                                '<th>P-Code</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' + subRows + '</tbody>' +
                    '</table>'
                );
            }
            drawer.addClass('show');
            icon.css('transform', 'rotate(90deg)');
        }
    });

    // ── Filter Brands Chip Search in Modal ──
    $('#brandChipFilter').on('input', function () {
        let q = $(this).val().toLowerCase().trim();
        $('.brand-chip').each(function () {
            let txt = $(this).text().toLowerCase();
            $(this).toggle(!q || txt.indexOf(q) !== -1);
        });
    });

    // ══════════════════════════════════════════════════════════════
    //  CONFIRM IMPORT (CHUNKED AJAX PROGRESS ENGINE)
    // ══════════════════════════════════════════════════════════════
    $('#btnConfirmImport').click(function (e) {
        e.preventDefault();
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1.5"></i> Starting...');
        
        // Show Animated Modal
        showProgressModal();
        
        processImportChunk(0);
    });

    function showProgressModal() {
        var el = document.getElementById('importProgressModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                var modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el, {backdrop: 'static', keyboard: false});
                modal.show();
                return;
            } catch(e) {}
        }
        try {
            $('#importProgressModal').modal({backdrop: 'static', keyboard: false, show: true});
        } catch(e) {
            $('#importProgressModal').show();
        }
    }

    function hideProgressModal() {
        var el = document.getElementById('importProgressModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                var modal = bootstrap.Modal.getInstance(el);
                if (modal) {
                    modal.hide();
                    return;
                }
            } catch(e) {}
        }
        try {
            $('#importProgressModal').modal('hide');
        } catch(e) {
            $('#importProgressModal').hide();
        }
    }

    function processImportChunk(offset) {
        $.ajax({
            url: "{{ route('products.import.confirm') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                offset: offset,
                chunk_size: 250
            },
            success: function (res) {
                if (res.status === 'success') {
                    // Import is complete
                    $('#modalProgressBar').css('width', '100%').text('100%');
                    $('#modalProgressPercent').text('100%');
                    $('#modalProgressTitle').text('Import Completed Successfully!');
                    $('#modalProgressSubtitle').html('<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> All products, variants, and stock movements have been saved.</span> Redirecting to catalog...');
                    
                    setTimeout(function () {
                        window.location.href = "{{ route('product') }}";
                    }, 1200);

                } else if (res.status === 'continue') {
                    let pct = Math.round((res.processed / res.total) * 100);
                    $('#modalProgressBar').css('width', pct + '%').text(pct + '%');
                    $('#modalProgressPercent').text(pct + '%');
                    $('#modalProgressCount').text('Processed ' + res.processed + ' of ' + res.total + ' products (' + pct + '%)');
                    
                    processImportChunk(res.next_offset);
                } else {
                    hideProgressModal();
                    alert('Import failed: ' + (res.message || 'Unknown error occurred.'));
                    $('#btnConfirmImport').prop('disabled', false).html('<i class="fas fa-cloud-upload-alt me-1.5"></i> Retry Import');
                }
            },
            error: function (err) {
                console.error(err);
                hideProgressModal();
                alert('A network error occurred during import. Please check your network or try again.');
                $('#btnConfirmImport').prop('disabled', false).html('<i class="fas fa-cloud-upload-alt me-1.5"></i> Retry Import');
            }
        });
    }

});
</script>
@endsection
