@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO POS REPLICA THEME FOR CARPENTER COMMISSIONS
       ========================================================== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

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

    /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS Title Bar) */
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

    .qb-btn-red {
        background: linear-gradient(180deg, #e05252 0%, #c33838 100%) !important;
        border: 1px solid #942222 !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
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
    .qb-btn-red:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

    /* 2. Sub-Header Strip (Steel Blue Gradient - Exact POS Order Ribbon) */
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

    /* Status Tabs */
    .qb-status-tabs {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }
    .qb-tab-pill {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid #a8bed8;
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        color: #193452;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        height: 24px;
        transition: all 0.12s ease;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }
    .qb-tab-pill:hover {
        background: #ffffff;
        color: #0f2744;
        border-color: #8ca4c3;
    }
    .qb-tab-pill.active {
        background: #ffffff !important;
        border: 1.5px solid #18385e !important;
        color: #102641 !important;
        font-weight: 800;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) !important;
    }
    .qb-tab-pill.active-warning {
        background: #ffffff !important;
        border: 1.5px solid #b45309 !important;
        color: #b45309 !important;
        font-weight: 800;
    }
    .qb-tab-pill.active-info {
        background: #ffffff !important;
        border: 1.5px solid #0369a1 !important;
        color: #0369a1 !important;
        font-weight: 800;
    }
    .qb-tab-pill.active-success {
        background: #ffffff !important;
        border: 1.5px solid #15803d !important;
        color: #15803d !important;
        font-weight: 800;
    }
    .qb-tab-pill .badge {
        font-size: 9.5px;
        padding: 1px 4px;
        border-radius: 6px;
        font-family: Consolas, monospace;
    }

    /* Financial Summary Chips */
    .qb-kpi-group {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }
    .qb-kpi-chip {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 4px;
        padding: 2px 7px;
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
        font-family: 'JetBrains Mono', Consolas, monospace;
    }

    /* 3. Filter Bar (Light Slate Blue Gradient Strip) */
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
    .qb-filter-col {
        position: relative;
        display: flex;
        align-items: center;
        flex-shrink: 0;
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

    .qb-filter-col.date-col input {
        width: 110px !important;
        height: 26px !important;
        font-size: 11px !important;
        padding: 2px 6px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        color: #0f2744 !important;
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

    /* 4. Table Card (Exact Replica of QuickBooks Grid Table) */
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

    /* Table Headers (Exact QuickBooks Replica: Light Blue-Gray Gradient with Dividers) */
    .qb-table thead th {
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #193452 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 11px !important;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #aebfd4 !important;
        border-right: 1px solid #c9d7e5 !important;
        border-top: none !important;
        padding: 7px 10px !important;
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

    /* Customer & Carpenter Initials Avatar */
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

    /* Actions Button (Mini Tool Button) */
    .odoo-action-btn, .btn-premium-action {
        background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 26px !important;
        padding: 0 8px !important;
        font-size: 11.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        transition: all 0.12s ease;
    }
    .odoo-action-btn:hover {
        background: #dbeafe !important;
        border-color: #2563eb !important;
        color: #1d4ed8 !important;
    }

    .btn-action-approve {
        background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%) !important;
        border: 1.5px solid #059669 !important;
        color: #047857 !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 24px !important;
        padding: 0 8px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        cursor: pointer;
    }
    .btn-action-approve:hover {
        background: #059669 !important;
        color: #ffffff !important;
    }

    .btn-action-cancel {
        background: linear-gradient(180deg, #fef2f2 0%, #fee2e2 100%) !important;
        border: 1.5px solid #dc2626 !important;
        color: #b91c1c !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 24px !important;
        padding: 0 7px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        cursor: pointer;
    }
    .btn-action-cancel:hover {
        background: #dc2626 !important;
        color: #ffffff !important;
    }

    .btn-action-pay {
        background: linear-gradient(180deg, #53af41 0%, #35832a 100%) !important;
        border: 1.5px solid #286c1f !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 24px !important;
        padding: 0 10px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .btn-action-pay:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

    .btn-action-slip {
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%) !important;
        border: 1.5px solid #2563eb !important;
        color: #1d4ed8 !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 24px !important;
        padding: 0 8px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        cursor: pointer;
        text-decoration: none !important;
    }
    .btn-action-slip:hover {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    .btn-action-thermal {
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
        border: 1.5px solid #64748b !important;
        color: #334155 !important;
        font-weight: 700 !important;
        border-radius: 4px !important;
        height: 24px !important;
        padding: 0 6px !important;
        font-size: 11px !important;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        cursor: pointer;
        text-decoration: none !important;
    }
    .btn-action-thermal:hover {
        background: #475569 !important;
        color: #ffffff !important;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- Row 1: Top Window Title Bar (Navy Blue POS Replica) --}}
            <div class="qb-win-header">
                <div class="qb-win-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Carpenter Commissions</span>
                    <span class="qb-win-sub d-none d-sm-inline">/ Commissions & Payouts</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="qb-btn-green" id="btnBatchApprove" style="display: none;">
                        <i class="fas fa-check-double"></i> Approve Selected (<span id="batchSelectedCount">0</span>)
                    </button>
                    <a href="{{ route('carpenters.index') }}" class="qb-btn-blue">
                        <i class="fas fa-hammer"></i> Manage Carpenters
                    </a>
                </div>
            </div>

            {{-- Row 2: Sub-Ribbon Strip (Status Tabs + Financial KPI Chips) --}}
            <div class="qb-sub-ribbon">
                {{-- Status Tabs --}}
                <div class="qb-status-tabs">
                    <a href="{{ route('carpenter_commissions.index') }}?status=all" class="qb-tab-pill {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">
                        <i class="fas fa-list text-primary"></i> All <span class="badge bg-secondary text-white">{{ $totalCount ?? ($commissions->total() ?? 0) }}</span>
                    </a>
                    <a href="{{ route('carpenter_commissions.index') }}?status=pending" class="qb-tab-pill {{ request('status') === 'pending' ? 'active-warning' : '' }}">
                        <i class="fas fa-clock text-warning"></i> Pending <span class="badge bg-warning text-dark">{{ $pendingCount ?? 0 }}</span>
                    </a>
                    <a href="{{ route('carpenter_commissions.index') }}?status=approved" class="qb-tab-pill {{ request('status') === 'approved' ? 'active-info' : '' }}">
                        <i class="fas fa-check-double text-info"></i> Approved (Unpaid) <span class="badge bg-info text-white">{{ $approvedCount ?? 0 }}</span>
                    </a>
                    <a href="{{ route('carpenter_commissions.index') }}?status=paid" class="qb-tab-pill {{ request('status') === 'paid' ? 'active-success' : '' }}">
                        <i class="fas fa-hand-holding-usd text-success"></i> Paid <span class="badge bg-success text-white">{{ $paidCount ?? 0 }}</span>
                    </a>
                </div>

                {{-- Financial KPI Chips --}}
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip">
                        <span class="chip-label text-primary"><i class="fas fa-coins me-1"></i>Total Comm:</span>
                        <span class="chip-val text-primary">Rs. {{ number_format($totalCommission ?? 0, 2) }}</span>
                    </div>
                    <div class="qb-kpi-chip">
                        <span class="chip-label text-warning"><i class="fas fa-clock me-1"></i>Pending:</span>
                        <span class="chip-val text-warning">Rs. {{ number_format($pendingCommission ?? 0, 2) }}</span>
                    </div>
                    <div class="qb-kpi-chip">
                        <span class="chip-label text-info"><i class="fas fa-check-circle me-1"></i>Approved:</span>
                        <span class="chip-val text-info">Rs. {{ number_format($approvedCommission ?? 0, 2) }}</span>
                    </div>
                    <div class="qb-kpi-chip">
                        <span class="chip-label text-success"><i class="fas fa-hand-holding-usd me-1"></i>Paid Out:</span>
                        <span class="chip-val text-success">Rs. {{ number_format($paidCommission ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Row 3: Filter Bar (Light Slate Blue Strip) --}}
            <div class="qb-filter-bar">
                <form method="GET" action="{{ route('carpenter_commissions.index') }}" class="qb-filter-form">
                    <div class="qb-filter-col" style="flex: 1 1 200px; max-width: 250px;">
                        <select name="carpenter_id" class="form-select qb-input">
                            <option value="">All Carpenters</option>
                            @foreach($carpenters as $c)
                                <option value="{{ $c->id }}" {{ request('carpenter_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="qb-filter-col" style="width: 130px; flex: 0 0 130px;">
                        <select name="status" class="form-select qb-input">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="qb-filter-col date-col">
                        <input type="date" name="start_date" class="form-control qb-input" value="{{ request('start_date') }}" title="From Date">
                    </div>

                    <div class="qb-filter-col date-col">
                        <input type="date" name="end_date" class="form-control qb-input" value="{{ request('end_date') }}" title="To Date">
                    </div>

                    <div class="qb-filter-col" style="flex: 1 1 180px; max-width: 220px;">
                        <input type="text" name="search" class="form-control qb-input" placeholder="Invoice # or Carpenter..." value="{{ request('search') }}">
                    </div>

                    <div class="qb-filter-col d-flex align-items-center gap-1" style="flex: 0 0 auto;">
                        <button type="submit" class="btn qb-btn-search">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['carpenter_id', 'status', 'start_date', 'end_date', 'search']))
                            <a href="{{ route('carpenter_commissions.index') }}" class="btn qb-btn-reset" title="Reset Filters">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Flash Messages --}}
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

            {{-- Row 4: Table Card (Exact Replica of QuickBooks POS Table) --}}
            <div class="qb-table-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 qb-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 32px;" class="text-center px-1">
                                    <input type="checkbox" id="checkAllCommissions" class="form-check-input mt-0" style="cursor: pointer;">
                                </th>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Carpenter</th>
                                <th class="text-end">Sale Net (Rs.)</th>
                                <th class="text-center">Comm. Rate</th>
                                <th class="text-end">Commission (Rs.)</th>
                                <th class="text-center">Status</th>
                                <th>Approved By</th>
                                <th class="text-center" style="width: 190px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commissions as $comm)
                                <tr>
                                    <td class="text-center px-1">
                                        @if($comm->status === 'pending')
                                            <input type="checkbox" class="form-check-input comm-checkbox mt-0" value="{{ $comm->id }}" style="cursor: pointer;">
                                        @else
                                            <i class="fas fa-minus text-muted" style="font-size: 10px;"></i>
                                        @endif
                                    </td>
                                    <td class="text-nowrap text-dark fw-bold" style="font-size: 12.5px;">
                                        {{ $comm->created_at ? $comm->created_at->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-nowrap">
                                        @if($comm->sale)
                                            <a href="{{ route('sales.receipt', $comm->sale_id) }}" target="_blank" class="fw-bold text-primary text-decoration-none font-monospace" style="font-size: 13px;" title="View Sales Receipt">
                                                <i class="fas fa-receipt me-1"></i>{{ $comm->sale->invoice_no }}
                                            </a>
                                            <small class="text-secondary d-block fw-semibold" style="font-size: 10.5px;">#{{ $comm->sale_id }}</small>
                                        @else
                                            <span class="text-secondary font-monospace fw-semibold">Sale #{{ $comm->sale_id }}</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap" style="min-width: 160px;">
                                        @php
                                            $isWalkin = empty($comm->sale?->customer_id);
                                            $cName = $comm->sale ? ($comm->sale->customer_relation->customer_name ?? ($comm->sale->walkin_name ?: 'Walk-in')) : 'Walk-in';
                                            $cInitial = strtoupper(substr($cName ?: 'W', 0, 1));
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle {{ $isWalkin ? 'bg-secondary text-white' : 'bg-primary text-white' }} fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                                {{ $cInitial }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size: 13px; line-height: 1.25;">{{ $cName }}</span>
                                                @if($isWalkin)
                                                    <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace py-0 px-1" style="font-size: 9.5px;">Walk-in</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap" style="min-width: 160px;">
                                        @php
                                            $kName = $comm->carpenter->name ?? 'N/A';
                                            $kInitial = strtoupper(substr($kName ?: 'C', 0, 1));
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                                {{ $kInitial }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 13px; line-height: 1.25;">{{ $kName }}</div>
                                                @if($comm->carpenter && $comm->carpenter->phone)
                                                    <small class="text-secondary font-monospace fw-semibold" style="font-size: 10.5px;">{{ $comm->carpenter->phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-dark font-monospace text-nowrap" style="font-size: 13px;">
                                        {{ number_format($comm->sale_amount, 2) }}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if($comm->commission_type === 'percentage')
                                            <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace px-1.5 py-0.5 fw-bold" style="font-size: 11px;">
                                                {{ number_format($comm->commission_rate, 1) }}%
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace px-1.5 py-0.5 fw-bold" style="font-size: 11px;">
                                                Rs. {{ number_format($comm->commission_rate, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-primary text-nowrap" style="font-size: 13.5px; font-weight: 800 !important;">
                                        Rs. {{ number_format($comm->commission_amount, 2) }}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @if($comm->status === 'pending')
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-clock me-1 mr-1"></i>Pending
                                            </span>
                                        @elseif($comm->status === 'approved')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-check me-1 mr-1"></i>Approved
                                            </span>
                                        @elseif($comm->status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-check-double me-1 mr-1"></i>Paid
                                            </span>
                                        @elseif($comm->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap" style="font-size: 12px;">
                                        @if($comm->approver)
                                            <div class="fw-bold text-dark">{{ $comm->approver->name }}</div>
                                            <small class="text-secondary" style="font-size: 10px;">{{ $comm->approved_at ? $comm->approved_at->format('d/m/Y H:i') : '' }}</small>
                                        @else
                                            <span class="text-secondary">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="{{ route('carpenter_commissions.receipt', $comm->id) }}" target="_blank" class="btn-action-slip" title="Print Commission Voucher Slip (A4/A5)">
                                                <i class="fas fa-print"></i> Slip
                                            </a>
                                            <a href="{{ route('carpenter_commissions.thermal', $comm->id) }}" target="_blank" class="btn-action-thermal" title="Print 80mm POS Thermal Slip">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            @if($comm->status === 'pending')
                                                <button type="button" class="btn-action-approve btn-approve-single"
                                                        data-id="{{ $comm->id }}"
                                                        title="Approve Commission">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn-action-cancel btn-cancel-single"
                                                        data-id="{{ $comm->id }}"
                                                        title="Cancel Commission">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($comm->status === 'approved')
                                                <button type="button" class="btn-action-pay btn-pay-modal"
                                                        data-id="{{ $comm->id }}"
                                                        data-carpenter="{{ $comm->carpenter->name ?? 'Carpenter' }}"
                                                        data-invoice="{{ $comm->sale ? $comm->sale->invoice_no : '' }}"
                                                        data-amount="{{ $comm->commission_amount }}">
                                                    <i class="fas fa-money-bill-wave"></i> Pay
                                                </button>
                                            @elseif($comm->status === 'paid')
                                                <div class="text-success fw-bold text-nowrap" style="font-size: 11px;">
                                                    <i class="fas fa-check-circle me-1"></i>{{ $comm->paymentAccount->title ?? 'Paid' }}
                                                </div>
                                                <small class="text-secondary" style="font-size: 10px;">{{ $comm->paid_at ? $comm->paid_at->format('d/m/Y') : '' }}</small>
                                            @else
                                                <span class="text-secondary">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="fas fa-hand-holding-usd fs-3 d-block mb-2 text-secondary opacity-50"></i>
                                        No carpenter commissions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                    <div class="small text-muted fw-semibold" style="font-size: 11.5px;">
                        Showing {{ $commissions->firstItem() ?? 0 }} to {{ $commissions->lastItem() ?? 0 }} of {{ $commissions->total() }} commissions
                    </div>
                    <div>
                        {{ $commissions->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal: Pay Commission (QuickBooks Styled Modal) --}}
<div class="modal fade" id="modalPayCommission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden;">
            <form id="formPayCommission" method="POST">
                @csrf
                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13px;">
                        <i class="fas fa-money-bill-wave me-1" style="color: #38bdf8;"></i> Pay Carpenter Commission
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>
                <div class="modal-body p-3 bg-white">
                    <div class="p-2 mb-3 rounded" style="background: #f8fafc; border: 1px solid #cbd5e1; font-size: 12px;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted fw-semibold">Carpenter:</span>
                            <strong class="text-dark" id="pay_carpenter_name">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted fw-semibold">Invoice #:</span>
                            <strong class="text-primary font-monospace" id="pay_invoice_no">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fw-semibold">Payable Commission:</span>
                            <strong class="text-success font-monospace" id="pay_commission_display" style="font-size: 13.5px; font-weight: 800;">Rs. 0.00</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Payment Account (Paid From) <span class="text-danger">*</span></label>
                        <select name="payment_account_id" id="pay_account_id" class="form-select qb-input" style="height: 30px !important;" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ str_contains(strtolower($acc->title), 'cash') ? 'selected' : '' }}>
                                    {{ $acc->title }} ({{ $acc->head->name ?? 'Account' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Payment Amount (Rs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="paid_amount" id="pay_amount_input" class="form-control qb-input font-monospace fw-bold text-end" style="height: 30px !important; font-size: 13px;" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Notes / Narration</label>
                        <input type="text" name="notes" class="form-control qb-input" style="height: 30px !important;" placeholder="Optional payment note...">
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="qb-btn-green">
                        <i class="fas fa-check"></i> Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Single Approve
    $(document).on('click', '.btn-approve-single', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('carpenter-commissions') }}/" + id + "/approve",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: res.message,
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Approval failed.', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Approval failed.';
                Swal.fire('Error', msg, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve');
            }
        });
    });

    // Single Cancel
    $(document).on('click', '.btn-cancel-single', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Cancel Commission?',
            text: 'Are you sure you want to cancel this carpenter commission?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('carpenter-commissions') }}/" + id + "/cancel",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire('Cancelled', res.message || 'Cancelled successfully.', 'success').then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to cancel commission.', 'error');
                    }
                });
            }
        });
    });

    // Check All & Batch Selection
    $('#checkAllCommissions').on('change', function() {
        $('.comm-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchButton();
    });

    $(document).on('change', '.comm-checkbox', function() {
        updateBatchButton();
    });

    function updateBatchButton() {
        const checkedCount = $('.comm-checkbox:checked').length;
        $('#batchSelectedCount').text(checkedCount);
        if (checkedCount > 0) {
            $('#btnBatchApprove').fadeIn(150);
        } else {
            $('#btnBatchApprove').fadeOut(150);
        }
    }

    // Batch Approve
    $('#btnBatchApprove').on('click', function() {
        const ids = $('.comm-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (!ids.length) return;

        Swal.fire({
            title: `Approve ${ids.length} Commissions?`,
            text: 'Are you sure you want to approve all selected commissions?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve All'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('carpenter_commissions.batch_approve') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(res) {
                        Swal.fire('Success', res.message, 'success').then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'Batch approval failed.', 'error');
                    }
                });
            }
        });
    });

    // Pay Modal
    $(document).on('click', '.btn-pay-modal', function() {
        const id = $(this).data('id');
        const carp = $(this).data('carpenter');
        const inv = $(this).data('invoice');
        const amt = parseFloat($(this).data('amount')) || 0;

        $('#pay_carpenter_name').text(carp);
        $('#pay_invoice_no').text(inv || '-');
        $('#pay_commission_display').text('Rs. ' + amt.toLocaleString('en-US', { minimumFractionDigits: 2 }));
        $('#pay_amount_input').val(amt.toFixed(2));

        $('#formPayCommission').attr('action', "{{ url('carpenter-commissions') }}/" + id + "/pay");
        $('#modalPayCommission').modal('show');
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
