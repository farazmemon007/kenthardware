@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO POS REPLICA THEME FOR CARPENTER DIRECTORY
       ========================================================== */
    /* Native QuickBooks Font Stack - Crystal Clear on Windows with ClearType Subpixel Rendering */
    html, body,
    .odoo-page-wrapper,
    .qb-table,
    .qb-table thead th,
    .qb-table tbody td,
    .qb-win-header,
    .qb-filter-bar,
    .qb-table-card,
    input, button, select, textarea {
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Tahoma, Roboto, "Helvetica Neue", Arial, sans-serif !important;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: subpixel-antialiased !important;
        -moz-osx-font-smoothing: auto !important;
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
        padding: 0 4px !important;
    }

    /* 1. Top Window Title Bar */
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
        padding: 3px 10px !important;
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

    .qb-btn-yellow {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%) !important;
        border: 1px solid #b45309 !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 11.5px !important;
        padding: 3px 10px !important;
        height: 26px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        cursor: pointer;
    }
    .qb-btn-yellow:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

    /* Sub Ribbon */
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

    /* Filter Bar */
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
    .qb-input {
        height: 26px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
        color: #0f2744 !important;
        padding: 1px 6px !important;
        background-color: #ffffff;
    }
    .qb-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
    }

    .qb-btn-search {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        height: 26px !important;
        padding: 0 10px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        cursor: pointer;
    }
    .qb-btn-search:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }
    .qb-btn-reset {
        background: linear-gradient(180deg, #ffffff 0%, #e5e7eb 100%) !important;
        border: 1px solid #9ca3af !important;
        color: #374151 !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        height: 26px !important;
        padding: 0 8px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        text-decoration: none !important;
    }
    .qb-btn-reset:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
    }

    /* Table Card */
    .qb-table-card {
        background: #ffffff;
        border: 1px solid #aebfd4;
        border-radius: 4px;
        padding: 4px 6px 6px 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .table-responsive {
        border: 1px solid #aebfd4 !important;
        border-radius: 3px !important;
        background-color: #ffffff;
        overflow-x: auto;
        min-height: 0 !important;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 13px !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }

    /* Table Headers (Dark Navy Blue - 100% High Contrast Against White Data Rows) */
    .qb-table thead,
    .qb-table thead tr,
    .qb-table thead th,
    table.dataTable thead th {
        background: linear-gradient(180deg, #18385e 0%, #0f2238 100%) !important;
        background-color: #18385e !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 11.5px !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #081422 !important;
        border-right: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-top: none !important;
        padding: 8px 10px !important;
        white-space: nowrap !important;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }

    /* Table Body Cells */
    .qb-table tbody td {
        border-bottom: 1px solid #e2e8f0 !important;
        border-right: 1px solid #edf2f7 !important;
        border-top: none !important;
        padding: 7px 10px !important;
        font-size: 13px !important;
        color: #1a202c !important;
        vertical-align: middle !important;
        font-variant-numeric: tabular-nums;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* ROW CURSOR HOVER: PASTEL MINT GREEN */
    .qb-table tbody tr {
        cursor: pointer;
        transition: background-color 0.1s ease-in-out;
    }
    .qb-table tbody tr:hover td,
    .qb-table tbody tr:hover th {
        background-color: #d5eed7 !important;
        color: #111827 !important;
    }
    .qb-table tbody tr.qb-active-row td {
        background-color: #d5eed7 !important;
        color: #111827 !important;
    }

    .font-monospace,
    .qb-mono {
        font-family: 'JetBrains Mono', 'Consolas', 'SFMono-Regular', Menlo, monospace !important;
    }

    .avatar-circle {
        width: 26px !important;
        height: 26px !important;
        min-width: 26px !important;
        min-height: 26px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        border-radius: 50% !important;
        margin-right: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    .btn-qb-mini {
        background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
        border: 1px solid #8ca4c3 !important;
        color: #18385e !important;
        font-weight: 700 !important;
        border-radius: 3px !important;
        height: 22px !important;
        padding: 0 6px !important;
        font-size: 11px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
        text-decoration: none !important;
        cursor: pointer;
    }
    .btn-qb-mini:hover {
        background: #dbeafe !important;
        border-color: #2563eb !important;
        color: #1d4ed8 !important;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- Row 1: Top Window Title Bar --}}
            <div class="qb-win-header">
                <div class="qb-win-title">
                    <i class="fas fa-hammer"></i>
                    <span>Carpenter Directory</span>
                    <span class="qb-win-sub d-none d-sm-inline">/ Master Management</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="qb-btn-yellow" data-bs-toggle="modal" data-bs-target="#modalInactivitySettings" title="Configure Inactivity Reminder Alert">
                        <i class="fas fa-bell"></i> Alert: <strong>{{ $thresholdDays ?? 7 }}d</strong>
                    </button>
                    <a href="{{ route('carpenter_commissions.index') }}" class="qb-btn-blue">
                        <i class="fas fa-hand-holding-usd"></i> View Commissions
                    </a>
                    <button type="button" class="qb-btn-green" data-bs-toggle="modal" data-bs-target="#modalAddCarpenter">
                        <i class="fas fa-plus"></i> Add Carpenter
                    </button>
                </div>
            </div>

            {{-- Inactivity Alert Strip --}}
            @if(isset($inactiveCount) && $inactiveCount > 0)
                <div class="alert alert-warning py-1.5 px-3 mb-1 d-flex align-items-center justify-content-between" style="border-left: 4px solid #d97706; background-color: #fef3c7; border-radius: 4px; font-size: 12px;">
                    <div>
                        <i class="fas fa-exclamation-triangle text-warning me-1.5 fs-6"></i>
                        <strong class="text-dark">{{ $inactiveCount }} Active Carpenter(s) Absent!</strong>
                        <span class="text-dark">No sales recorded for {{ $thresholdDays }} or more days. Notifications have been dispatched.</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-dark fw-bold px-2 py-0.5" data-bs-toggle="modal" data-bs-target="#modalInactivitySettings">
                        <i class="fas fa-cog me-1"></i> Change ({{ $thresholdDays }}d)
                    </button>
                </div>
            @endif

            {{-- Filter Bar --}}
            <div class="qb-filter-bar">
                <form method="GET" action="{{ route('carpenters.index') }}" class="qb-filter-form">
                    <div class="qb-filter-col" style="flex: 1 1 280px; max-width: 380px;">
                        <input type="text" name="search" class="form-control qb-input" placeholder="Search carpenter name, phone or CNIC..." value="{{ request('search') }}">
                    </div>

                    <div class="qb-filter-col" style="width: 140px; flex: 0 0 140px;">
                        <select name="status" class="form-select qb-input">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="qb-filter-col d-flex align-items-center gap-1" style="flex: 0 0 auto;">
                        <button type="submit" class="btn qb-btn-search">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('carpenters.index') }}" class="btn qb-btn-reset" title="Reset Filters">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Flash Alerts --}}
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 rounded py-1 px-2 mb-1" style="font-size: 11px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto p-1" data-bs-dismiss="alert" aria-label="Close" style="font-size: 8px;"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning d-flex align-items-center gap-2 rounded py-1 px-2 mb-1" style="font-size: 11px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                    <button type="button" class="btn-close ms-auto p-1" data-bs-dismiss="alert" aria-label="Close" style="font-size: 8px;"></button>
                </div>
            @endif

            {{-- Table Card --}}
            <div class="qb-table-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 qb-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 45px;" class="text-center">#</th>
                                <th>Carpenter Name</th>
                                <th>Contact / Phone</th>
                                <th>CNIC</th>
                                <th>Default Commission</th>
                                <th class="text-center">Total Sales</th>
                                <th class="text-end">Total Commission</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($carpenters as $index => $carpenter)
                                <tr>
                                    <td class="text-center text-muted fw-bold font-monospace" style="font-size: 11.5px;">
                                        {{ $carpenters->firstItem() + $index }}
                                    </td>
                                    <td class="text-nowrap" style="min-width: 180px;">
                                        @php
                                            $cInitial = strtoupper(substr($carpenter->name ?: 'C', 0, 1));
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                                {{ $cInitial }}
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="fw-bold text-dark" style="font-size: 13px;">{{ $carpenter->name }}</span>
                                                    @if($carpenter->status && $carpenter->days_inactive >= $thresholdDays)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace py-0 px-1" style="font-size: 9.5px;" title="No sales recorded for {{ $carpenter->days_inactive }} days">
                                                            <i class="fas fa-user-clock me-1"></i>{{ $carpenter->days_inactive }}d Absent
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-muted" style="font-size: 10.5px;">
                                                    @if($carpenter->lastSale)
                                                        <i class="fas fa-history me-1"></i>Last sale: {{ $carpenter->lastSale->created_at->format('d/m/Y') }} ({{ $carpenter->days_inactive }}d ago)
                                                    @else
                                                        <i class="fas fa-calendar-plus me-1"></i>Registered {{ $carpenter->created_at ? $carpenter->created_at->format('d/m/Y') : '-' }}
                                                    @endif
                                                </div>
                                                @if($carpenter->address)
                                                    <small class="text-secondary d-block" style="font-size: 10.5px;"><i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($carpenter->address, 35) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap font-monospace fw-semibold text-dark" style="font-size: 12.5px;">
                                        {{ $carpenter->phone ?: '—' }}
                                    </td>
                                    <td class="text-nowrap font-monospace text-secondary" style="font-size: 12px;">
                                        {{ $carpenter->cnic ?: '—' }}
                                    </td>
                                    <td class="text-nowrap">
                                        @if($carpenter->default_commission_type === 'percentage')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-1.5 py-0.5 fw-bold" style="font-size: 11px;">
                                                {{ number_format($carpenter->default_commission_rate, 2) }}%
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace px-1.5 py-0.5 fw-bold" style="font-size: 11px;">
                                                Rs. {{ number_format($carpenter->default_commission_rate, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center font-monospace fw-bold text-dark text-nowrap" style="font-size: 13px;">
                                        <span class="badge bg-light text-dark border">{{ $carpenter->total_sales_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark text-nowrap" style="font-size: 13px;">
                                        <a href="{{ route('carpenter_commissions.index', ['carpenter_id' => $carpenter->id]) }}" class="text-primary text-decoration-none" title="View commissions for {{ $carpenter->name }}">
                                            Rs. {{ number_format($carpenter->total_commission_amount ?? 0, 2) }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size: 9px;"></i>
                                        </a>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if($carpenter->status)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 11px;">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 11px;">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            @if($carpenter->lastCommission)
                                                <a href="{{ route('carpenter_commissions.receipt', $carpenter->lastCommission->id) }}" target="_blank" class="btn-qb-mini text-primary" title="Print Commission Voucher Slip (A4/A5)">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('carpenter_commissions.thermal', $carpenter->lastCommission->id) }}" target="_blank" class="btn-qb-mini text-secondary" title="Print 80mm Thermal Slip">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            @else
                                                <button type="button" class="btn-qb-mini text-muted opacity-50" disabled title="No sales or commissions recorded yet for this carpenter">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('carpenter_commissions.index', ['carpenter_id' => $carpenter->id]) }}" class="btn-qb-mini text-info" title="View All Commissions & Slips for {{ $carpenter->name }}">
                                                <i class="fas fa-list-ul"></i>
                                            </a>
                                            <button type="button" class="btn-qb-mini btn-edit-carpenter" data-carpenter='@json($carpenter)' title="Edit Carpenter">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('carpenters.destroy', $carpenter->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to deactivate/delete this carpenter?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-qb-mini text-danger" title="Delete or Deactivate">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-hammer fs-3 d-block mb-2 text-secondary opacity-50"></i>
                                        No carpenters found. Click <strong>Add Carpenter</strong> above to create one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                    <div class="small text-muted fw-semibold" style="font-size: 11.5px;">
                        Showing {{ $carpenters->firstItem() ?? 0 }} to {{ $carpenters->lastItem() ?? 0 }} of {{ $carpenters->total() }} carpenters
                    </div>
                    <div>
                        {{ $carpenters->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal: Add Carpenter --}}
<div class="modal fade" id="modalAddCarpenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden;">
            <form action="{{ route('carpenters.store') }}" method="POST">
                @csrf
                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13px;">
                        <i class="fas fa-user-plus me-1 text-warning"></i> Add New Carpenter
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>
                <div class="modal-body p-3 bg-white">
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark mb-1">Carpenter Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control qb-input" style="height: 30px !important;" required placeholder="e.g. Ustad Tariq">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Phone / Mobile</label>
                            <input type="text" name="phone" class="form-control qb-input" style="height: 30px !important;" placeholder="0300-1234567">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">CNIC</label>
                            <input type="text" name="cnic" class="form-control qb-input" style="height: 30px !important;" placeholder="42101-1234567-1">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Default Commission Type <span class="text-danger">*</span></label>
                            <select name="default_commission_type" class="form-select qb-input" style="height: 30px !important;" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Rupees (Rs.)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Default Rate / Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="default_commission_rate" class="form-control qb-input text-end font-monospace fw-bold" style="height: 30px !important;" value="0.00" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Address / Workshop Location</label>
                        <textarea name="address" rows="2" class="form-control qb-input" style="height: auto !important;" placeholder="Optional address..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Notes</label>
                        <input type="text" name="notes" class="form-control qb-input" style="height: 30px !important;" placeholder="Optional notes...">
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="addStatusSwitch" checked style="cursor: pointer;">
                        <label class="form-check-label fw-bold small" for="addStatusSwitch">Active</label>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="qb-btn-green">
                        <i class="fas fa-save me-1"></i> Save Carpenter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Edit Carpenter --}}
<div class="modal fade" id="modalEditCarpenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden;">
            <form id="formEditCarpenter" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13px;">
                        <i class="fas fa-edit me-1 text-warning"></i> Edit Carpenter
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>
                <div class="modal-body p-3 bg-white">
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark mb-1">Carpenter Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control qb-input" style="height: 30px !important;" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Phone / Mobile</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control qb-input" style="height: 30px !important;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">CNIC</label>
                            <input type="text" name="cnic" id="edit_cnic" class="form-control qb-input" style="height: 30px !important;">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Default Commission Type <span class="text-danger">*</span></label>
                            <select name="default_commission_type" id="edit_type" class="form-select qb-input" style="height: 30px !important;" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Rupees (Rs.)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Default Rate / Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="default_commission_rate" id="edit_rate" class="form-control qb-input text-end font-monospace fw-bold" style="height: 30px !important;" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Address / Workshop Location</label>
                        <textarea name="address" id="edit_address" rows="2" class="form-control qb-input" style="height: auto !important;"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Notes</label>
                        <input type="text" name="notes" id="edit_notes" class="form-control qb-input" style="height: 30px !important;">
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="edit_status" style="cursor: pointer;">
                        <label class="form-check-label fw-bold small" for="edit_status">Active</label>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="qb-btn-blue">
                        <i class="fas fa-save me-1"></i> Update Carpenter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Inactivity Reminder Settings --}}
<div class="modal fade" id="modalInactivitySettings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden;">
            <form action="{{ route('carpenters.settings.inactivity') }}" method="POST">
                @csrf
                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13px;">
                        <i class="fas fa-bell text-warning me-2"></i> Carpenter Inactivity Reminder
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>
                <div class="modal-body p-3" style="background: #f8fafc;">
                    <p class="small text-muted mb-2">
                        Agar koi active carpenter is muddat tak koi sale na banwaye, to admin aur staff ko notification reminder bhej diya jayega.
                    </p>

                    <label class="form-label fw-bold small text-dark mb-1">Reminder Duration (Time Period):</label>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <label class="p-2 border rounded bg-white d-flex align-items-center justify-content-between" style="cursor: pointer;">
                            <div>
                                <input type="radio" name="threshold_preset" class="form-check-input me-2" value="7" {{ ($thresholdDays ?? 7) == 7 ? 'checked' : '' }}>
                                <strong class="text-dark">1 Week (7 Days)</strong>
                                <small class="text-muted d-block ps-4">Default: 7 din tak sale na hone par alert</small>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border font-monospace">1 Week</span>
                        </label>
                        <label class="p-2 border rounded bg-white d-flex align-items-center justify-content-between" style="cursor: pointer;">
                            <div>
                                <input type="radio" name="threshold_preset" class="form-check-input me-2" value="15" {{ ($thresholdDays ?? 7) == 15 ? 'checked' : '' }}>
                                <strong class="text-dark">15 Days (2 Weeks)</strong>
                                <small class="text-muted d-block ps-4">15 din tak sale na hone par alert</small>
                            </div>
                            <span class="badge bg-warning-subtle text-dark border font-monospace">15 Days</span>
                        </label>
                        <label class="p-2 border rounded bg-white d-flex align-items-center justify-content-between" style="cursor: pointer;">
                            <div>
                                <input type="radio" name="threshold_preset" class="form-check-input me-2" value="30" {{ ($thresholdDays ?? 7) == 30 ? 'checked' : '' }}>
                                <strong class="text-dark">1 Month (30 Days)</strong>
                                <small class="text-muted d-block ps-4">1 mahine tak sale na hone par alert</small>
                            </div>
                            <span class="badge bg-danger-subtle text-danger border font-monospace">1 Month</span>
                        </label>
                        <label class="p-2 border rounded bg-white d-flex align-items-center justify-content-between" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <input type="radio" name="threshold_preset" class="form-check-input me-2" value="custom" {{ !in_array(($thresholdDays ?? 7), [7, 15, 30]) ? 'checked' : '' }}>
                                <strong class="text-dark">Custom Days:</strong>
                            </div>
                            <div style="width: 110px;">
                                <input type="number" id="custom_threshold_input" min="1" max="365" class="form-control qb-input text-end font-monospace" value="{{ $thresholdDays ?? 7 }}">
                            </div>
                        </label>
                    </div>

                    <input type="hidden" name="threshold_days" id="final_threshold_days" value="{{ $thresholdDays ?? 7 }}">
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="qb-btn-blue">
                        <i class="fas fa-save me-1"></i> Save Reminder Setting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.btn-edit-carpenter').on('click', function() {
        const c = $(this).data('carpenter');
        $('#edit_name').val(c.name);
        $('#edit_phone').val(c.phone || '');
        $('#edit_cnic').val(c.cnic || '');
        $('#edit_type').val(c.default_commission_type);
        $('#edit_rate').val(c.default_commission_rate);
        $('#edit_address').val(c.address || '');
        $('#edit_notes').val(c.notes || '');
        $('#edit_status').prop('checked', c.status == 1);

        const updateUrl = "{{ url('carpenters') }}/" + c.id;
        $('#formEditCarpenter').attr('action', updateUrl);

        $('#modalEditCarpenter').modal('show');
    });

    // Inactivity threshold radio sync
    $('input[name="threshold_preset"]').on('change', function() {
        const val = $(this).val();
        if (val === 'custom') {
            $('#final_threshold_days').val($('#custom_threshold_input').val() || 7);
            $('#custom_threshold_input').focus();
        } else {
            $('#final_threshold_days').val(val);
            $('#custom_threshold_input').val(val);
        }
    });

    $('#custom_threshold_input').on('input', function() {
        $('input[name="threshold_preset"][value="custom"]').prop('checked', true);
        $('#final_threshold_days').val($(this).val() || 7);
    });

    // Row selection highlight
    $(document).on('click', '.qb-table tbody tr', function(e) {
        if ($(e.target).closest('a, button, input').length > 0) return;
        $('.qb-table tbody tr').removeClass('qb-active-row');
        $(this).addClass('qb-active-row');
    });
});
</script>
@endsection
