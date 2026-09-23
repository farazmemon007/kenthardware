@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ── ERP Storage Locations Design System ── */
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
    .storage-header {
        margin-bottom: 24px;
    }
    .storage-header .page-title h4 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--erp-text);
        letter-spacing: -0.3px;
    }

    /* Stat Cards */
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
        padding: 16px 20px;
    }

    /* Nav Tabs / Pills */
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
    .nav-pills-custom .nav-link .badge-counter {
        background-color: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 12px;
        margin-left: 8px;
    }
    .nav-pills-custom .nav-link.active .badge-counter {
        background-color: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Table Styling */
    .storage-table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    .storage-table thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 12px 14px !important;
        border-top: none !important;
        border-bottom: 2px solid var(--erp-border) !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    .storage-table tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        color: #1e293b !important;
        font-size: 13px !important;
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
        letter-spacing: 0.3px !important;
        white-space: nowrap !important;
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

    /* ── Action Buttons: Single Row Strict Layout ── */
    .col-actions {
        white-space: nowrap !important;
        min-width: 165px !important;
        width: 165px !important;
        text-align: center !important;
        vertical-align: middle !important;
    }
    .action-btn-group {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
        width: 100% !important;
    }
    .btn-action-edit,
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
        flex-shrink: 0 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.15s ease-in-out !important;
    }
    .btn-action-edit {
        background-color: #eff6ff !important;
        border: 1px solid #bfdbfe !important;
        color: #1d4ed8 !important;
    }
    .btn-action-edit:hover {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25) !important;
        transform: translateY(-1px) !important;
    }
    .btn-action-delete {
        background-color: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        color: #dc2626 !important;
    }
    .btn-action-delete:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25) !important;
        transform: translateY(-1px) !important;
    }

    /* DataTables Fixes: clean sorting glyphs without broken icon boxes */
    .dataTables_wrapper .dataTable thead th.sorting:before,
    .dataTables_wrapper .dataTable thead th.sorting_asc:before,
    .dataTables_wrapper .dataTable thead th.sorting_desc:before {
        content: "\f0de" !important;
        font-family: "Font Awesome 5 Free" !important;
        font-weight: 900 !important;
        font-size: 10px !important;
        top: 50% !important;
        transform: translateY(-80%) !important;
        right: 14px !important;
        opacity: 0.25 !important;
    }
    .dataTables_wrapper .dataTable thead th.sorting:after,
    .dataTables_wrapper .dataTable thead th.sorting_asc:after,
    .dataTables_wrapper .dataTable thead th.sorting_desc:after {
        content: "\f0dd" !important;
        font-family: "Font Awesome 5 Free" !important;
        font-weight: 900 !important;
        font-size: 10px !important;
        top: 50% !important;
        transform: translateY(-20%) !important;
        right: 6px !important;
        opacity: 0.25 !important;
    }
    .dataTables_wrapper .dataTable thead th.sorting_asc:before {
        opacity: 0.9 !important;
        color: #2563eb !important;
    }
    .dataTables_wrapper .dataTable thead th.sorting_desc:after {
        opacity: 0.9 !important;
        color: #2563eb !important;
    }
    .dataTables_wrapper .dataTable thead th.sorting_disabled:before,
    .dataTables_wrapper .dataTable thead th.sorting_disabled:after {
        display: none !important;
        content: "" !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 5px 12px !important;
        font-size: 13px !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 13px !important;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="storage-header row align-items-center">
                <div class="page-title col-md-6">
                    <h4 class="font-weight-bold mb-1 d-flex align-items-center">
                        <i class="fas fa-layer-group text-primary mr-2"></i> Storage Locations
                    </h4>
                    <p class="text-muted mb-0 small">
                        Manage Warehouse Racks and Shop Shelves for product variant placement.
                    </p>
                </div>
                <div class="page-btn d-flex justify-content-md-end col-md-6 mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary px-3 py-2 shadow-sm font-weight-bold" id="btnAddLocation"
                        data-toggle="modal" data-target="#storageLocationModal"
                        data-bs-toggle="modal" data-bs-target="#storageLocationModal"
                        onclick="openCreateModal()">
                        <i class="fas fa-plus mr-1"></i> Add Location
                    </button>
                </div>
            </div>

            <!-- Stats Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                    <div class="card stat-summary-card">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Racks</span>
                                <h3 class="font-weight-bold mb-0 text-primary mt-1" style="font-size: 22px;">{{ $racks->count() }}</h3>
                                <small class="text-muted" style="font-size: 11.5px;">Warehouse storage</small>
                            </div>
                            <div class="stat-icon-box bg-blue-50">
                                <i class="fas fa-warehouse fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                    <div class="card stat-summary-card">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Shelves</span>
                                <h3 class="font-weight-bold mb-0 text-success mt-1" style="font-size: 22px;">{{ $shelves->count() }}</h3>
                                <small class="text-muted" style="font-size: 11.5px;">Shop & branch storage</small>
                            </div>
                            <div class="stat-icon-box bg-emerald-50">
                                <i class="fas fa-store fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                    <div class="card stat-summary-card">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Warehouses</span>
                                <h3 class="font-weight-bold mb-0 text-dark mt-1" style="font-size: 22px;">{{ $warehouses->count() }}</h3>
                                <small class="text-muted" style="font-size: 11.5px;">Active warehouses</small>
                            </div>
                            <div class="stat-icon-box bg-indigo-50">
                                <i class="fas fa-boxes fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-summary-card">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Shops / Branches</span>
                                <h3 class="font-weight-bold mb-0 text-warning mt-1" style="font-size: 22px;">{{ $branches->count() }}</h3>
                                <small class="text-muted" style="font-size: 11.5px;">Active branches</small>
                            </div>
                            <div class="stat-icon-box bg-amber-50">
                                <i class="fas fa-building fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Container Card -->
            <div class="storage-main-card">
                <div class="card-header">
                    <ul class="nav nav-pills nav-pills-custom" id="locationTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="racks-tab" data-toggle="tab" href="#racksTabContent" role="tab">
                                <i class="fas fa-warehouse mr-2"></i> Warehouse Racks 
                                <span class="badge-counter">{{ $racks->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="shelves-tab" data-toggle="tab" href="#shelvesTabContent" role="tab">
                                <i class="fas fa-store mr-2"></i> Shop Shelves 
                                <span class="badge-counter">{{ $shelves->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3 p-md-4">
                    <div class="tab-content" id="locationTabsContent">
                        
                        <!-- TAB 1: Warehouse Racks -->
                        <div class="tab-pane fade show active" id="racksTabContent" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle storage-table w-100" id="racksTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 45px;">#</th>
                                            <th>Warehouse</th>
                                            <th>Rack Name / ID</th>
                                            <th>Rack Code</th>
                                            <th>Aisle / Zone</th>
                                            <th>Remarks</th>
                                            <th>Created By</th>
                                            <th class="text-center col-actions">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($racks as $key => $rack)
                                            <tr>
                                                <td class="font-weight-bold text-muted text-center">{{ $key + 1 }}</td>
                                                <td>
                                                    <span class="badge-pill-custom badge-warehouse">
                                                        <i class="fas fa-warehouse mr-1 text-primary"></i> {{ $rack->warehouse->warehouse_name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $rack->name }}</span>
                                                </td>
                                                <td>
                                                    @if($rack->code)
                                                        <span class="badge-code">{{ $rack->code }}</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rack->zone_or_aisle)
                                                        <span class="badge-zone"><i class="fas fa-map-pin text-info mr-1"></i> {{ $rack->zone_or_aisle }}</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ $rack->description ?: '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="small text-secondary"><i class="far fa-user mr-1 text-muted"></i> {{ $rack->creator->name ?? 'System' }}</span>
                                                </td>
                                                <td class="col-actions">
                                                    <div class="action-btn-group">
                                                        <button type="button" class="btn-action-edit edit-location-btn"
                                                            data-id="{{ $rack->id }}"
                                                            data-type="warehouse_rack"
                                                            data-warehouse_id="{{ $rack->warehouse_id }}"
                                                            data-branch_id=""
                                                            data-name="{{ $rack->name }}"
                                                            data-code="{{ $rack->code }}"
                                                            data-zone_or_aisle="{{ $rack->zone_or_aisle }}"
                                                            data-description="{{ $rack->description }}"
                                                            title="Edit Rack">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn-action-delete"
                                                            data-url="{{ route('storage_locations.delete', $rack->id) }}"
                                                            data-msg="Are you sure you want to delete this Warehouse Rack?"
                                                            data-method="get"
                                                            onclick="logoutAndDeleteFunction(this)"
                                                            title="Delete Rack">
                                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <div class="py-2">
                                                        <i class="fas fa-warehouse fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                                        <h6 class="font-weight-bold text-dark">No Warehouse Racks Found</h6>
                                                        <p class="text-muted small mb-3">Add racks to organize inventory storage across warehouses.</p>
                                                        <button type="button" class="btn btn-sm btn-primary px-3 shadow-sm font-weight-bold" onclick="openCreateModal('warehouse_rack')">
                                                            <i class="fas fa-plus mr-1"></i> Add First Rack
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: Shop Shelves -->
                        <div class="tab-pane fade" id="shelvesTabContent" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle storage-table w-100" id="shelvesTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 45px;">#</th>
                                            <th>Shop / Branch</th>
                                            <th>Shelf Name / ID</th>
                                            <th>Shelf Code</th>
                                            <th>Section / Floor</th>
                                            <th>Remarks</th>
                                            <th>Created By</th>
                                            <th class="text-center col-actions">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($shelves as $key => $shelf)
                                            <tr>
                                                <td class="font-weight-bold text-muted text-center">{{ $key + 1 }}</td>
                                                <td>
                                                    <span class="badge-pill-custom badge-shop">
                                                        <i class="fas fa-store mr-1 text-success"></i> {{ $shelf->branch->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $shelf->name }}</span>
                                                </td>
                                                <td>
                                                    @if($shelf->code)
                                                        <span class="badge-code">{{ $shelf->code }}</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($shelf->zone_or_aisle)
                                                        <span class="badge-zone"><i class="fas fa-map-pin text-success mr-1"></i> {{ $shelf->zone_or_aisle }}</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ $shelf->description ?: '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="small text-secondary"><i class="far fa-user mr-1 text-muted"></i> {{ $shelf->creator->name ?? 'System' }}</span>
                                                </td>
                                                <td class="col-actions">
                                                    <div class="action-btn-group">
                                                        <button type="button" class="btn-action-edit edit-location-btn"
                                                            data-id="{{ $shelf->id }}"
                                                            data-type="shop_shelf"
                                                            data-warehouse_id=""
                                                            data-branch_id="{{ $shelf->branch_id }}"
                                                            data-name="{{ $shelf->name }}"
                                                            data-code="{{ $shelf->code }}"
                                                            data-zone_or_aisle="{{ $shelf->zone_or_aisle }}"
                                                            data-description="{{ $shelf->description }}"
                                                            title="Edit Shelf">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn-action-delete"
                                                            data-url="{{ route('storage_locations.delete', $shelf->id) }}"
                                                            data-msg="Are you sure you want to delete this Shop Shelf?"
                                                            data-method="get"
                                                            onclick="logoutAndDeleteFunction(this)"
                                                            title="Delete Shelf">
                                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <div class="py-2">
                                                        <i class="fas fa-store fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                                        <h6 class="font-weight-bold text-dark">No Shop Shelves Found</h6>
                                                        <p class="text-muted small mb-3">Add shelves to organize variant placement in your retail shops.</p>
                                                        <button type="button" class="btn btn-sm btn-success px-3 shadow-sm font-weight-bold" onclick="openCreateModal('shop_shelf')">
                                                            <i class="fas fa-plus mr-1"></i> Add First Shelf
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Add / Edit Storage Location -->
<div class="modal fade" id="storageLocationModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="storageLocationForm" action="{{ route('storage_locations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="loc_id" value="">
            <input type="hidden" name="type" id="loc_type" value="warehouse_rack">

            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title font-weight-bold text-dark" id="modalTitle">
                        <i class="fas fa-warehouse text-primary mr-2"></i> Add Warehouse Rack
                    </h5>
                    <button type="button" class="close text-secondary" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <!-- Alert Error Box for AJAX feedback -->
                    <div class="alert alert-danger d-none" id="modalErrorBox" style="font-size: 13px;"></div>

                    <!-- Type Selector Segmented Pill -->
                    <div class="p-1 mb-3 rounded d-flex" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                        <button type="button" class="btn btn-sm w-50 font-weight-bold py-2 bg-white shadow-sm text-primary transition" id="labelTypeRack" onclick="switchLocationType('warehouse_rack')" style="border-radius: 6px;">
                            <i class="fas fa-warehouse mr-1 text-primary"></i> Warehouse Rack
                        </button>
                        <button type="button" class="btn btn-sm w-50 font-weight-bold py-2 text-muted bg-transparent shadow-none transition" id="labelTypeShelf" onclick="switchLocationType('shop_shelf')" style="border-radius: 6px;">
                            <i class="fas fa-store mr-1 text-success"></i> Shop Shelf
                        </button>
                    </div>

                    <!-- Warehouse Selection (For Racks) -->
                    <div class="form-group mb-3" id="warehouseSelectGroup">
                        <label class="font-weight-bold text-dark small mb-1">
                            Warehouse <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="warehouse_id" id="loc_warehouse_id">
                            <option value="">-- Select Warehouse --</option>
                            @foreach ($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->warehouse_name }} {{ $w->location ? "({$w->location})" : '' }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger error-text" id="err_warehouse_id"></small>
                    </div>

                    <!-- Shop/Branch Selection (For Shelves) -->
                    <div class="form-group mb-3 d-none" id="branchSelectGroup">
                        <label class="font-weight-bold text-dark small mb-1">
                            Shop / Branch <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="branch_id" id="loc_branch_id">
                            <option value="">-- Select Shop / Branch --</option>
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }} {{ $b->address ? "({$b->address})" : '' }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger error-text" id="err_branch_id"></small>
                    </div>

                    <!-- Name Field -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1" id="labelLocationName">
                            Rack Name / Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" id="loc_name" placeholder="e.g. Rack A-1, Rack 10, or Top Rack" required>
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle text-info mr-1"></i> Duplicate names are not allowed in the same warehouse or shop.
                        </small>
                        <small class="text-danger error-text d-block" id="err_name"></small>
                    </div>

                    <div class="row">
                        <!-- Code Field -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">
                                Code / Short Identifier
                            </label>
                            <input type="text" class="form-control" name="code" id="loc_code" placeholder="e.g. WR-A1, SH-01">
                            <small class="text-danger error-text" id="err_code"></small>
                        </div>

                        <!-- Aisle / Zone / Floor -->
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1" id="labelZoneOrAisle">
                                Aisle / Zone
                            </label>
                            <input type="text" class="form-control" name="zone_or_aisle" id="loc_zone_or_aisle" placeholder="e.g. Aisle 2, East Wing">
                            <small class="text-danger error-text" id="err_zone_or_aisle"></small>
                        </div>
                    </div>

                    <!-- Description / Remarks -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small mb-1">
                            Remarks / Details (Optional)
                        </label>
                        <textarea class="form-control" name="description" id="loc_description" rows="2" placeholder="e.g. For small hardware fittings or paint cans"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" id="saveLocBtn">
                        <i class="fas fa-save mr-1"></i> Save Location
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        if ($('#racksTable').length && !$.fn.DataTable.isDataTable('#racksTable')) {
            $('#racksTable').DataTable({
                order: [[0, 'asc']],
                pageLength: 25,
                responsive: false,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0, 5, 7] }, // Disable sorting on #, Remarks, Action
                    { className: "text-center align-middle", targets: [0, 7] }
                ]
            });
        }
        if ($('#shelvesTable').length && !$.fn.DataTable.isDataTable('#shelvesTable')) {
            $('#shelvesTable').DataTable({
                order: [[0, 'asc']],
                pageLength: 25,
                responsive: false,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0, 5, 7] }, // Disable sorting on #, Remarks, Action
                    { className: "text-center align-middle", targets: [0, 7] }
                ]
            });
        }

        // Adjust column sizes when tab switches
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    }
});

function switchLocationType(type) {
    $('#loc_type').val(type);
    $('.error-text').text('');
    $('#modalErrorBox').addClass('d-none').text('');

    if (type === 'warehouse_rack') {
        $('#labelTypeRack').addClass('bg-white shadow-sm text-primary').removeClass('text-muted bg-transparent shadow-none');
        $('#labelTypeShelf').addClass('text-muted bg-transparent shadow-none').removeClass('bg-white shadow-sm text-success');

        $('#modalTitle').html('<i class="fas fa-warehouse text-primary mr-2"></i> ' + ($('#loc_id').val() ? 'Edit Warehouse Rack' : 'Add Warehouse Rack'));
        
        $('#warehouseSelectGroup').removeClass('d-none');
        $('#branchSelectGroup').addClass('d-none');
        $('#loc_warehouse_id').prop('required', true);
        $('#loc_branch_id').prop('required', false);

        $('#labelLocationName').html('Rack Name / Number <span class="text-danger">*</span>');
        $('#loc_name').attr('placeholder', 'e.g. Rack A-1, Rack 10, or Top Rack');
        $('#labelZoneOrAisle').text('Aisle / Zone');
        $('#loc_zone_or_aisle').attr('placeholder', 'e.g. Aisle 2, East Wing');
    } else {
        $('#labelTypeShelf').addClass('bg-white shadow-sm text-success').removeClass('text-muted bg-transparent shadow-none');
        $('#labelTypeRack').addClass('text-muted bg-transparent shadow-none').removeClass('bg-white shadow-sm text-primary');

        $('#modalTitle').html('<i class="fas fa-store text-success mr-2"></i> ' + ($('#loc_id').val() ? 'Edit Shop Shelf' : 'Add Shop Shelf'));

        $('#warehouseSelectGroup').addClass('d-none');
        $('#branchSelectGroup').removeClass('d-none');
        $('#loc_warehouse_id').prop('required', false);
        $('#loc_branch_id').prop('required', true);

        $('#labelLocationName').html('Shelf Name / Number <span class="text-danger">*</span>');
        $('#loc_name').attr('placeholder', 'e.g. Shelf 1, Front Shelf, or Counter Shelf A');
        $('#labelZoneOrAisle').text('Section / Floor');
        $('#loc_zone_or_aisle').attr('placeholder', 'e.g. Ground Floor, Section C');
    }
}

function showStorageLocationModal() {
    try {
        $('#storageLocationModal').modal('show');
    } catch (err) {
        console.warn('jQuery modal show failed:', err);
    }
}

function hideStorageLocationModal() {
    try {
        $('#storageLocationModal').modal('hide');
    } catch (err) {
        console.warn('jQuery modal hide failed:', err);
    }
}

function openCreateModal(type) {
    if (!type) {
        if ($('#shelves-tab').hasClass('active')) {
            type = 'shop_shelf';
        } else {
            type = 'warehouse_rack';
        }
    }
    $('#loc_id').val('');
    if ($('#storageLocationForm').length && $('#storageLocationForm')[0]) {
        $('#storageLocationForm')[0].reset();
    }
    $('.error-text').text('');
    $('#modalErrorBox').addClass('d-none').text('');
    
    switchLocationType(type);
    showStorageLocationModal();
}

$(document).ready(function() {
    $('#btnAddLocation').on('click', function(e) {
        e.preventDefault();
        openCreateModal();
    });
});

// Edit handler
$(document).on('click', '.edit-location-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var type = $(this).data('type');
    var warehouseId = $(this).data('warehouse_id');
    var branchId = $(this).data('branch_id');
    var name = $(this).data('name');
    var code = $(this).data('code');
    var zoneOrAisle = $(this).data('zone_or_aisle');
    var description = $(this).data('description');

    $('#loc_id').val(id);
    switchLocationType(type);

    $('#loc_name').val(name);
    $('#loc_code').val(code);
    $('#loc_zone_or_aisle').val(zoneOrAisle);
    $('#loc_description').val(description);

    if (type === 'warehouse_rack') {
        $('#loc_warehouse_id').val(warehouseId);
        $('#modalTitle').html('<i class="fas fa-warehouse mr-2"></i> Edit Warehouse Rack: ' + name);
    } else {
        $('#loc_branch_id').val(branchId);
        $('#modalTitle').html('<i class="fas fa-store mr-2"></i> Edit Shop Shelf: ' + name);
    }

    showStorageLocationModal();
});

// AJAX Form Submit with duplicate validation feedback
$('#storageLocationForm').on('submit', function(e) {
    e.preventDefault();
    $('.error-text').text('');
    $('#modalErrorBox').addClass('d-none').text('');
    var $btn = $('#saveLocBtn');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Location');
            hideStorageLocationModal();

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.success || 'Saved successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Location');
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                var errors = xhr.responseJSON.errors;
                var generalMsg = [];
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        $('#err_' + key).text(errors[key][0]);
                        generalMsg.push(errors[key][0]);
                    }
                }
                if (generalMsg.length) {
                    $('#modalErrorBox').removeClass('d-none').html(generalMsg.join('<br>'));
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please check your inputs and try again.'
                });
            }
        }
    });
});
</script>
@endsection
