@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#18385e'
        });
    });
    </script>
@endif

<style>
    /* ==========================================================
       QUICKBOOKS & ODOO POS HIGH-DENSITY ZERO-SCROLL THEME
       Kent Hardware ERP Theme Standard (Matches Chart of Accounts & POS)
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
        white-space: nowrap !important;
    }
    .qb-btn-blue:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

    /* 2. Sub-Header Ribbon: KPIs, Search & Length (No Wasted Space) */
    .qb-sub-ribbon {
        background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
        border: 1px solid #b2c5dc;
        border-radius: 4px;
        padding: 3px 8px;
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
        color: #0f172a;
    }

    /* 3. Table Card & Grid (Strict Fixed Layout to Eliminate Any Misalignment) */
    .qb-table-card {
        background: #ffffff;
        border: 1px solid #94a3b8;
        border-radius: 4px;
        padding: 3px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .table-responsive {
        border: 1px solid #94a3b8 !important;
        border-radius: 3px !important;
        background-color: #ffffff;
        overflow-x: auto;
        min-height: 0 !important;
    }
    table#default-datatable,
    table.qb-table {
        margin-bottom: 0 !important;
        font-size: 12px !important;
        width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        display: table !important;
    }
    table#default-datatable thead,
    table.qb-table thead {
        display: table-header-group !important;
        width: 100% !important;
    }
    table#default-datatable tbody,
    table.qb-table tbody {
        display: table-row-group !important;
        width: 100% !important;
    }
    table#default-datatable tr,
    table.qb-table tr {
        display: table-row !important;
        width: 100% !important;
    }
    table#default-datatable thead th,
    table.qb-table thead th {
        display: table-cell !important;
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #091e36 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 11.5px !important;
        letter-spacing: 0.4px;
        border-bottom: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-top: none !important;
        padding: 7px 8px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }
    table#default-datatable thead th:last-child,
    table.qb-table thead th:last-child {
        border-right: none !important;
    }
    table#default-datatable tbody td,
    table.qb-table tbody td {
        display: table-cell !important;
        border-bottom: 1px solid #cbd5e1 !important;
        border-right: 1px solid #edf2f7 !important;
        border-top: none !important;
        padding: 6px 8px !important;
        font-size: 13px !important;
        color: #0f172a !important;
        font-weight: 600;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }
    table#default-datatable tbody td:last-child,
    table.qb-table tbody td:last-child {
        border-right: none !important;
    }
    table#default-datatable tbody tr:hover td,
    table.qb-table tbody tr:hover td {
        background-color: #d5eed7 !important;
        color: #000000 !important;
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

    /* Action View Buttons (Classic Odoo/QuickBooks Action Button) */
    .odoo-action-btn {
        background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
        border: 1px solid #718dae !important;
        color: #081d33 !important;
        font-weight: 800 !important;
        border-radius: 3px !important;
        height: 24px !important;
        padding: 0 9px !important;
        font-size: 11.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: all 0.12s ease;
        text-decoration: none !important;
        white-space: nowrap !important;
        cursor: pointer;
    }
    .odoo-action-btn:hover {
        background: #dbeafe !important;
        border-color: #1d4ed8 !important;
        color: #1e40af !important;
    }
    .odoo-btn-delete:hover {
        background: #fee2e2 !important;
        border-color: #dc2626 !important;
        color: #991b1b !important;
    }

    /* DataTables Footer Info & Pagination */
    .dataTables_wrapper .dataTables_info {
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        padding: 4px 0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-link {
        color: #0f2744 !important;
        border: 1px solid #8ca4c3 !important;
        padding: 3px 9px !important;
        font-size: 12px !important;
        font-weight: 800 !important;
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
       QUICKBOOKS & ODOO POS MODAL DESIGN SYSTEM
       ============================================================== */
    #uomSetModal .modal-dialog {
        max-width: 750px !important;
        margin: 1.5rem auto;
    }
    .qb-modal-content {
        border-radius: 6px !important;
        overflow: hidden !important;
        border: 1.5px solid #1e3a5f !important;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.3) !important;
        background: #ffffff !important;
    }
    .qb-modal-header {
        background: linear-gradient(180deg, #18385e 0%, #102641 100%) !important;
        border-bottom: 2px solid #1e3a5f !important;
        padding: 8px 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        color: #ffffff !important;
    }
    .qb-modal-icon-badge {
        width: 26px;
        height: 26px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
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
        font-size: 10px !important;
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
        padding: 3px 6px !important;
        border-radius: 4px !important;
    }
    .qb-modal-close:hover {
        opacity: 1 !important;
        background: rgba(255, 255, 255, 0.18) !important;
        color: #ffffff !important;
    }
    .qb-modal-body {
        padding: 10px 12px;
        background: #eef2f8;
        overflow: visible !important;
    }
    .qb-modal-footer {
        background: #e2e8f0 !important;
        border-top: 1px solid #cbd5e1 !important;
        padding: 7px 14px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    .qb-modal-btn-cancel {
        background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%) !important;
        border: 1px solid #8ca4c3 !important;
        color: #334155 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        height: 28px !important;
        padding: 0 14px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
    }
    .qb-modal-btn-cancel:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
    }
    .qb-modal-btn-save {
        background: linear-gradient(180deg, #18385e 0%, #102641 100%) !important;
        border: 1px solid #102641 !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 11.5px !important;
        height: 28px !important;
        padding: 0 16px !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }
    .qb-modal-btn-save:hover {
        filter: brightness(1.15) !important;
        color: #ffffff !important;
    }

    /* Modal Step Panels - Compact Cardless */
    .uom-step-box {
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        background: #ffffff;
        padding: 8px 10px;
        margin-bottom: 8px;
    }
    .uom-step-header {
        font-size: 11px;
        font-weight: 700;
        color: #0f2744;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        padding-bottom: 4px;
        border-bottom: 1px solid #f1f5f9;
    }

    #tblRelatedUnits {
        width: 100% !important;
        table-layout: fixed !important;
    }
    #tblRelatedUnits td {
        padding: 3px 6px;
        vertical-align: middle;
    }
    #tblRelatedUnits th {
        padding: 4px 6px;
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        font-size: 11px;
        font-weight: 700;
        color: #0f2744;
        border-bottom: 1px solid #94a3b8;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">
            
            {{-- Row 1: Top Window Title Bar (Navy Blue POS Replica) --}}
            <div class="qb-win-header">
                <div class="qb-win-title">
                    <i class="fas fa-cubes"></i>
                    <span>Unit of Measure (UOM)</span>
                    <span class="qb-win-sub d-none d-sm-inline">/ Odoo &amp; QuickBooks POS System</span>
                </div>

                {{-- Top Action Button --}}
                @can('units.create')
                    <button type="button" class="qb-btn-blue" id="btnCreateUomSet" data-toggle="modal" data-target="#uomSetModal" data-bs-toggle="modal" data-bs-target="#uomSetModal">
                        <i class="fas fa-plus"></i> + Create Unit of Measure
                    </button>
                @endcan
            </div>

            {{-- Row 2: Sub-Header Ribbon: KPI Chips + Live Search + Page Length (Zero Wasted Space) --}}
            <div class="qb-sub-ribbon">
                {{-- Left: KPI Chips --}}
                <div class="qb-kpi-group">
                    <span class="qb-kpi-chip">
                        <i class="fas fa-boxes text-primary"></i>
                        <span class="chip-label">Total Units:</span>
                        <span class="chip-val">{{ count($unit) }}</span>
                    </span>
                    <span class="qb-kpi-chip">
                        <i class="fas fa-anchor text-info"></i>
                        <span class="chip-label">Base Units:</span>
                        <span class="chip-val">{{ $unit->pluck('base_unit')->filter()->unique()->count() ?: count($unit) }}</span>
                    </span>
                    <span class="qb-kpi-chip d-none d-md-inline-flex">
                        <i class="fas fa-layer-group text-success"></i>
                        <span class="chip-label">Multi-Tier:</span>
                        <span class="chip-val">{{ $unit->filter(function($u) { return !empty($u->getRelatedUnits()); })->count() }}</span>
                    </span>
                </div>

                {{-- Right: Live Search Box & Page Length Dropdown --}}
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 190px;">
                        <span class="input-group-text bg-white py-0 px-2" style="border: 1px solid #8ca4c3; border-right: none; height: 25px;">
                            <i class="fas fa-search text-muted" style="font-size: 10px;"></i>
                        </span>
                        <input type="search" id="uomSearchInput" class="form-control form-control-sm border-start-0 ps-1" placeholder="Search UOM..." style="border: 1px solid #8ca4c3; height: 25px; font-size: 11px; border-radius: 0 3px 3px 0;">
                    </div>

                    <select id="uomLengthSelect" class="form-control form-control-sm custom-select custom-select-sm" style="width: 62px; height: 25px; font-size: 11px; border: 1px solid #8ca4c3; border-radius: 3px; padding: 0 4px;" title="Rows per page">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            {{-- Row 3: Table Card (Starts Immediately without Gaps) --}}
            <div class="qb-table-card">
                <div class="table-responsive">
                    <table id="default-datatable" class="table qb-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%;" class="text-center">#</th>
                                <th style="width: 22%;" class="text-start">Unit of Measure</th>
                                <th style="width: 12%;" class="text-center">Category / Type</th>
                                <th style="width: 11%;" class="text-center">Base Unit</th>
                                <th style="width: 24%;" class="text-start">Related Conversion Units</th>
                                <th style="width: 14%;" class="text-start">Default Preferences</th>
                                <th style="width: 12%;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unit as $company)
                                @php
                                    $baseUnit = $company->getBaseUnit();
                                    $related = $company->getRelatedUnits();
                                    $hasMulti = !empty($related) && count($related) > 0;
                                    $uData = $company->uom_data ?? [];
                                    $orderUnit = $uData['order_unit'] ?? $baseUnit;
                                    $sellUnit = $uData['sell_unit'] ?? $baseUnit;
                                    $hasCustomDefaults = ($orderUnit !== $baseUnit) || ($sellUnit !== $baseUnit);
                                    $uType = $company->unit_type ?? 'Count';
                                @endphp
                                <tr>
                                    <td class="text-center font-monospace" style="color: #0f172a; font-size: 12.5px; font-weight: 800;">{{ $loop->iteration }}</td>
                                    
                                    {{-- 1. Unit of Measure (Name) --}}
                                    <td class="text-start">
                                        <span style="font-size: 13.5px; font-weight: 800; color: #091e36; letter-spacing: 0.1px;">{{ $company->name }}</span>
                                        @if($hasMulti)
                                            <span class="badge ms-1" style="background-color: #dbeafe; color: #1e3a8a; border: 1.5px solid #93c5fd; font-size: 10px; font-weight: 800; padding: 2px 6px;">Multi-tier Set</span>
                                        @endif
                                    </td>

                                    {{-- 2. Category / Type (Count / Weight / Length / Volume / Area) --}}
                                    <td class="text-center">
                                        <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $uType }}</span>
                                    </td>

                                    {{-- 3. Base Unit (Smallest Unit of Measure) --}}
                                    <td class="text-center">
                                        <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $baseUnit }}</span>
                                    </td>

                                    {{-- 4. Related Conversion Units (Multipliers) --}}
                                    <td class="text-start">
                                        @if($hasMulti)
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                @foreach ($related as $rel)
                                                    <span class="badge" style="background-color: #dcfce7; color: #14532d; border: 1.5px solid #4ade80; font-size: 11.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px;" title="1 {{ $rel['name'] ?? '' }} = {{ $rel['ratio'] ?? 1 }} {{ $baseUnit }}">
                                                        <i class="fas fa-layer-group text-success me-1"></i><b>1 {{ $rel['name'] ?? '' }}</b> = {{ $rel['ratio'] ?? 1 }} {{ $baseUnit }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="font-size: 15px; font-weight: 800; color: #64748b;" title="Standard base unit without sub-packages">&mdash;</span>
                                        @endif
                                    </td>

                                    {{-- 5. Default Preferences (Order / Sell Units) --}}
                                    <td class="text-start">
                                        @if($hasCustomDefaults)
                                            <div class="d-flex align-items-center flex-wrap gap-1.5" style="font-size: 12px; color: #0f172a;">
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <i class="fas fa-shopping-cart text-primary" style="font-size: 11px;"></i>
                                                    <span class="text-muted fw-bold">PO:</span>
                                                    <strong style="color: #0f172a; font-weight: 800;">{{ $orderUnit }}</strong>
                                                </span>
                                                <span class="text-muted mx-1">&bull;</span>
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <i class="fas fa-cash-register text-success" style="font-size: 11px;"></i>
                                                    <span class="text-muted fw-bold">Sale:</span>
                                                    <strong style="color: #0f172a; font-weight: 800;">{{ $sellUnit }}</strong>
                                                </span>
                                            </div>
                                        @else
                                            <span style="font-size: 12px; font-weight: 700; color: #1e293b;">
                                                <i class="fas fa-check-circle text-success me-1" style="font-size: 11px;"></i>Standard (1 {{ $baseUnit }})
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 6. Actions --}}
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            @can('units.edit')
                                                <button type="button" class="odoo-action-btn btn-edit-uom" data-id="{{ $company->id }}" title="Edit Unit">
                                                    <i class="fas fa-edit me-1 text-primary"></i> Edit
                                                </button>
                                            @endcan
                                            @can('units.delete')
                                                <button type="button" class="odoo-action-btn odoo-btn-delete btn-delete-uom" data-id="{{ $company->id }}" data-url="{{ route('delete.Unit', $company->id) }}" title="Delete Unit">
                                                    <i class="fas fa-trash-alt me-1 text-danger"></i> Delete
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- QUICKBOOKS & ODOO POS ZERO-SCROLL MODAL --}}
<div class="modal fade" id="uomSetModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 750px !important; width: 100% !important;">
        <div class="modal-content qb-modal-content">
            
            {{-- Modal Header (Navy Blue Kent Hardware) --}}
            <div class="qb-modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="qb-modal-icon-badge">
                        <i class="fas fa-cubes text-warning"></i>
                    </div>
                    <div>
                        <div class="qb-modal-title" id="uomModalHeaderTitle">Create Unit of Measure Set</div>
                        <div class="qb-modal-subtitle">Define Base Unit &amp; Multiple Conversion Multipliers</div>
                    </div>
                </div>
                <button type="button" class="qb-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="uomSetForm" action="{{ route('store.Unit') }}" method="POST">
                @csrf
                <input type="hidden" name="edit_id" id="uom_edit_id" value="">
                
                <div class="modal-body qb-modal-body">
                    
                    {{-- Step 1: Set Identification & Base Unit --}}
                    <div class="uom-step-box">
                        <div class="uom-step-header">
                            <span>
                                <i class="fas fa-tag text-primary me-1"></i> Step 1: Unit of Measure Name &amp; Category
                            </span>
                            <span class="badge" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; font-size: 9.5px; font-weight: 700;">
                                <i class="fas fa-anchor me-1"></i> Base Unit = Smallest
                            </span>
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-5">
                                <label class="form-label font-weight-bold text-dark mb-0.5" style="font-size: 11px;">
                                    Unit / Set Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="uom_set_name" class="form-control form-control-sm font-weight-bold px-2 py-0.5" required placeholder="e.g. Pipe (Feet), Box" style="border: 1px solid #8ca4c3; height: 28px; border-radius: 3px; font-size: 11.5px;">
                            </div>
                            <div class="col-3">
                                <label class="form-label font-weight-bold text-dark mb-0.5" style="font-size: 11px;">
                                    Category
                                </label>
                                <select name="unit_type" id="uom_unit_type" class="form-control form-control-sm custom-select custom-select-sm font-weight-bold" style="border: 1px solid #8ca4c3; height: 28px; border-radius: 3px; font-size: 11px;">
                                    <option value="Count">Count</option>
                                    <option value="Length">Length</option>
                                    <option value="Weight">Weight</option>
                                    <option value="Volume">Volume</option>
                                    <option value="Area">Area</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label font-weight-bold text-dark mb-0.5" style="font-size: 11px;">
                                    Base Unit <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="base_unit" id="uom_base_unit_input" class="form-control form-control-sm font-weight-bold" required placeholder="e.g. Feet, Pcs" style="border: 1px solid #8ca4c3; height: 28px; border-radius: 3px 0 0 3px; font-size: 11.5px;">
                                    <span class="input-group-text small bg-light font-weight-bold px-1.5" style="font-size: 9.5px; border: 1px solid #8ca4c3; border-left: none; color: #15457a; height: 28px;">1.0 Base</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Related Multi-tier Units --}}
                    <div class="uom-step-box">
                        <div class="uom-step-header">
                            <div class="d-flex align-items-center gap-1">
                                <span><i class="fas fa-boxes text-primary me-1"></i> Step 2: Related Units &amp; Multipliers</span>
                                <span class="text-muted d-none d-sm-inline" style="font-size: 9.5px;">(1 Box = 12 Pcs)</span>
                            </div>
                            <button type="button" class="odoo-action-btn" id="btnAddRelatedRow" style="height: 20px; font-size: 10px; padding: 0 6px;">
                                <i class="fas fa-plus me-1 text-primary"></i> + Add Unit
                            </button>
                        </div>
                        <div style="max-height: 145px; overflow-y: auto; overflow-x: hidden;">
                            <table class="table table-bordered table-sm align-middle mb-0" id="tblRelatedUnits" style="border-color: #cbd5e1; font-size: 11px; width: 100%; table-layout: fixed;">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 38%;">Related Unit Name</th>
                                        <th style="width: 28%;">Multiplier (# Base)</th>
                                        <th style="width: 26%;">Preview</th>
                                        <th style="width: 8%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tblRelatedUnitsBody">
                                    <!-- Injected via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Step 3: Default Ordering and Selling Preferences --}}
                    <div class="uom-step-box mb-0">
                        <div class="uom-step-header mb-1.5 pb-1">
                            <span><i class="fas fa-exchange-alt text-primary me-1"></i> Step 3: Defaults (Ordering &amp; Selling)</span>
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-6">
                                <label class="small text-muted mb-1 font-weight-bold d-block" style="font-size: 10.5px;">
                                    <i class="fas fa-shopping-cart text-primary me-1"></i> Buy Order (PO):
                                </label>
                                <select name="order_unit" id="uom_order_unit" class="form-control form-control-sm custom-select custom-select-sm font-weight-bold" style="border: 1px solid #8ca4c3; height: 28px; font-size: 11px; width: 100%;">
                                    <!-- Populated via JS -->
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1 font-weight-bold d-block" style="font-size: 10.5px;">
                                    <i class="fas fa-cash-register text-success me-1"></i> Sell Unit:
                                </label>
                                <select name="sell_unit" id="uom_sell_unit" class="form-control form-control-sm custom-select custom-select-sm font-weight-bold" style="border: 1px solid #8ca4c3; height: 28px; font-size: 11px; width: 100%;">
                                    <!-- Populated via JS -->
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="qb-modal-footer">
                    <button type="button" class="qb-modal-btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="qb-modal-btn-save" id="btnSubmitUomSet">
                        <i class="fas fa-check"></i> Save Unit of Measure
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/js/mycode.js') }}"></script>
<script>
$(document).ready(function() {
    'use strict';

    // Fix ARIA focus warning on modal close
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    // Initialize DataTable with strict layout and column alignment
    if ($.fn.DataTable.isDataTable('#default-datatable')) {
        $('#default-datatable').DataTable().destroy();
    }
    const table = $('#default-datatable').DataTable({
        pageLength: 10,
        autoWidth: false,
        responsive: false,
        dom: 'rt<"d-flex align-items-center justify-content-between flex-wrap gap-2 px-2 py-1 border-top bg-light"ip>',
        order: [],
        columns: [
            { width: '5%', className: 'text-center', orderable: false },
            { width: '22%', className: 'text-start' },
            { width: '12%', className: 'text-center' },
            { width: '11%', className: 'text-center' },
            { width: '24%', className: 'text-start' },
            { width: '14%', className: 'text-start' },
            { width: '12%', className: 'text-center', orderable: false }
        ],
        language: {
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    // Keep clean sequential counting (1, 2, 3...) without '#' symbol across search/sort
    table.on('order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });

    // Live search input integration
    $('#uomSearchInput').on('keyup input', function() {
        table.search(this.value).draw();
    });

    // Page length dropdown integration
    $('#uomLengthSelect').on('change', function() {
        table.page.len(parseInt(this.value, 10)).draw();
    });

    // Helper to get current base unit name from input
    function getModalBaseUnit() {
        return $('#uom_base_unit_input').val().trim() || 'Base Unit';
    }

    // Helper to refresh the live conversion label in related rows
    function refreshRowConversions() {
        const base = getModalBaseUnit();
        $('.base-suffix-span').text(base);
        $('#tblRelatedUnitsBody tr').each(function() {
            const name = $(this).find('.rel-name-input').val().trim() || 'Unit';
            const ratio = parseFloat($(this).find('.rel-ratio-input').val()) || 1;
            $(this).find('.conversion-preview').html(`1 <b>${name}</b> = <b>${ratio}</b> ${base}`);
        });
        refreshDefaultDropdowns();
    }

    // Helper to build default Order and Sell dropdown options
    function refreshDefaultDropdowns() {
        const base = getModalBaseUnit();
        const units = [base];

        $('#tblRelatedUnitsBody tr').each(function() {
            const name = $(this).find('.rel-name-input').val().trim();
            if (name && !units.includes(name)) {
                units.push(name);
            }
        });

        const currentOrder = $('#uom_order_unit').val();
        const currentSell = $('#uom_sell_unit').val();

        let opts = '';
        units.forEach(function(u) {
            const label = (u === base) ? `${u} (Base Unit)` : u;
            opts += `<option value="${u}">${label}</option>`;
        });

        $('#uom_order_unit').html(opts);
        $('#uom_sell_unit').html(opts);

        if (currentOrder && units.includes(currentOrder)) $('#uom_order_unit').val(currentOrder);
        if (currentSell && units.includes(currentSell)) $('#uom_sell_unit').val(currentSell);
    }

    // Helper to check if related rows table is empty and show guidance
    function checkEmptyRelatedRows() {
        if ($('#tblRelatedUnitsBody tr.rel-data-row').length === 0) {
            $('#tblRelatedUnitsBody').html(`
                <tr class="empty-hint-row">
                    <td colspan="4" class="text-center text-muted py-2" style="font-size: 11px; background: #fbfcfe;">
                        <i class="fas fa-info-circle text-info me-1"></i> Standard single unit. If this unit has packaging (e.g. 1 Box = 12 Pcs), click <a href="javascript:void(0)" class="fw-bold text-primary text-decoration-none" id="btnEmptyAddUnit"><i class="fas fa-plus-circle"></i> Add Related Unit</a>
                    </td>
                </tr>
            `);
        } else {
            $('#tblRelatedUnitsBody tr.empty-hint-row').remove();
        }
    }

    // Append a related unit row
    function appendRelatedRow(name, ratio) {
        $('#tblRelatedUnitsBody tr.empty-hint-row').remove();
        name = name || '';
        ratio = (ratio !== undefined && ratio !== null && ratio !== '') ? ratio : '';
        const base = getModalBaseUnit();

        const tr = `
            <tr class="rel-data-row">
                <td>
                    <input type="text" name="related_names[]" class="form-control form-control-sm font-weight-bold rel-name-input" placeholder="e.g. Box, Carton, Bundle" value="${name}" style="border: 1px solid #8ca4c3; height: 26px; font-size: 11px;">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" min="0.0001" name="related_ratios[]" class="form-control form-control-sm text-end font-weight-bold rel-ratio-input" placeholder="e.g. 12" value="${ratio}" style="border: 1px solid #8ca4c3; height: 26px; font-size: 11px; border-radius: 3px 0 0 3px;">
                        <span class="input-group-text small bg-light base-suffix-span font-weight-bold px-2" style="border: 1px solid #8ca4c3; border-left: none; border-radius: 0 3px 3px 0; font-size: 10px; height: 26px; color: #15457a;">${base}</span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="conversion-preview badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; font-size: 10.5px; font-weight: 700; padding: 2px 6px; border-radius: 3px;">
                        1 <b>${name || 'Unit'}</b> = <b>${ratio || 1}</b> ${base}
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-remove-rel-row" title="Remove unit" style="font-size: 12px;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tblRelatedUnitsBody').append(tr);
        refreshRowConversions();
    }

    // Events on Base Unit input
    $('#uom_base_unit_input').on('input change', function() {
        refreshRowConversions();
    });

    // Auto-sync base unit when typing Set Name if creating new and base unit was unchanged
    $('#uom_set_name').on('input', function() {
        if (!$('#uom_edit_id').val()) {
            const val = $(this).val().trim();
            const $b = $('#uom_base_unit_input');
            if ($b.val() === 'Pcs' || !$b.val()) {
                $b.val(val);
                refreshRowConversions();
            }
        }
    });

    // Events on Related Unit rows
    $(document).on('input change', '.rel-name-input, .rel-ratio-input', function() {
        refreshRowConversions();
    });

    // Add Related Row button
    $('#btnAddRelatedRow').on('click', function() {
        appendRelatedRow('', '');
    });

    // Empty state Add Unit link
    $(document).on('click', '#btnEmptyAddUnit', function(e) {
        e.preventDefault();
        appendRelatedRow('', '');
    });

    // Remove Related Row button
    $(document).on('click', '.btn-remove-rel-row', function() {
        $(this).closest('tr').remove();
        checkEmptyRelatedRows();
        refreshRowConversions();
    });

    // Reset Form for New Creation
    function resetUomForm() {
        $('#uom_edit_id').val('');
        $('#uomModalHeaderTitle').text('Create Unit of Measure Set');
        $('#uom_set_name').val('');
        $('#uom_unit_type').val('Count');
        $('#uom_base_unit_input').val('Pcs');
        $('#tblRelatedUnitsBody').empty();
        checkEmptyRelatedRows();
        refreshRowConversions();
    }

    // Open Modal for Create
    $(document).on('click', '#btnCreateUomSet', function() {
        resetUomForm();
        $('#uomSetModal').modal('show');
    });

    // Open Modal for Edit
    $(document).on('click', '.btn-edit-uom', function() {
        const id = $(this).data('id');
        resetUomForm();
        $('#uomModalHeaderTitle').text('Edit Unit of Measure Set #' + id);
        $('#uom_edit_id').val(id);

        $.ajax({
            url: '/Unit/show/' + id,
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(unit) {
                if (!unit) return;
                $('#uom_set_name').val(unit.name || '');
                $('#uom_unit_type').val(unit.unit_type || 'Count');
                $('#uom_base_unit_input').val(unit.base_unit || unit.name || 'Pcs');

                $('#tblRelatedUnitsBody').empty();

                let uData = unit.uom_data;
                if (typeof uData === 'string') {
                    try { uData = JSON.parse(uData); } catch(e) { uData = {}; }
                }
                uData = uData || {};

                if (Array.isArray(uData.units) && uData.units.length > 0) {
                    uData.units.forEach(function(u) {
                        appendRelatedRow(u.name, u.ratio);
                    });
                } else {
                    checkEmptyRelatedRows();
                }

                refreshRowConversions();

                if (uData.order_unit) $('#uom_order_unit').val(uData.order_unit);
                if (uData.sell_unit) $('#uom_sell_unit').val(uData.sell_unit);

                $('#uomSetModal').modal('show');
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load Unit of Measure details' });
            }
        });
    });

    // Handle Form Submit via AJAX
    $('#uomSetForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#btnSubmitUomSet');
        const origText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                if (res.status === 'success' || res.success) {
                    $('#uomSetModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.success || 'Unit of Measure Set saved successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (res.errors) {
                    let msg = '';
                    for (var key in res.errors) {
                        msg += res.errors[key].join('<br>') + '<br>';
                    }
                    Swal.fire({ icon: 'warning', title: 'Validation Warning', html: msg });
                }
            },
            error: function(err) {
                let msg = 'Failed to save Unit of Measure Set!';
                if (err.responseJSON && err.responseJSON.errors) {
                    let errs = [];
                    for (var k in err.responseJSON.errors) {
                        errs.push(err.responseJSON.errors[k].join(', '));
                    }
                    msg = errs.join('<br>');
                } else if (err.responseJSON && err.responseJSON.message) {
                    msg = err.responseJSON.message;
                }
                Swal.fire({ icon: 'error', title: 'Error', html: msg });
            },
            complete: function() {
                $btn.html(origText).prop('disabled', false);
            }
        });
    });

    // Handle Delete
    $(document).on('click', '.btn-delete-uom', function() {
        const url = $(this).data('url');
        Swal.fire({
            title: 'Delete Unit of Measure?',
            text: "Products using this unit of measure will retain their existing data, but this unit will be removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.success || 'Unit deleted successfully',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete unit' });
                    }
                });
            }
        });
    });

});
</script>
@endsection
