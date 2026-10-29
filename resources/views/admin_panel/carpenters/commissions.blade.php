@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO POS REPLICA THEME FOR CARPENTER COMMISSIONS
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

    /* Payment Source Selection Cards (Cash vs Bank) */
    .pay-method-card {
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        padding: 8px 10px;
        cursor: pointer;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.15s ease-in-out;
        user-select: none;
    }
    .pay-method-card:hover {
        border-color: #64748b;
        background: #f8fafc;
    }
    .pay-method-card.active.method-cash {
        border-color: #16a34a !important;
        background: #f0fdf4 !important;
        box-shadow: 0 0 0 1px #16a34a;
    }
    .pay-method-card.active.method-bank {
        border-color: #2563eb !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 1px #2563eb;
    }
    .method-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .method-title {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    .method-desc {
        font-size: 10px;
        color: #64748b;
    }
    .live-balance-box {
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 11.5px;
        transition: all 0.2s ease;
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
                                            @php
                                                $duesAmt = (float)$comm->pending_return_dues_total;
                                                $netPay = (float)$comm->net_payable;
                                            @endphp
                                            <input type="checkbox" class="form-check-input comm-checkbox mt-0" value="{{ $comm->id }}"
                                                   data-amount="{{ $netPay }}"
                                                   data-gross-amount="{{ (float)$comm->commission_amount }}"
                                                   data-dues-amount="{{ $duesAmt }}"
                                                   style="cursor: pointer;">
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
                                    <td class="text-end font-monospace text-nowrap">
                                        @php
                                            $returnDues = $comm->status === 'paid' ? (float)($comm->return_deduction_amount ?? 0) : (float)$comm->pending_return_dues_total;
                                            $netPayable = (float)$comm->net_payable;
                                        @endphp
                                        @if($returnDues > 0)
                                            <div class="fw-bold text-success" style="font-size: 13.5px; font-weight: 800 !important;">
                                                Rs. {{ number_format($netPayable, 2) }}
                                            </div>
                                            <div class="text-muted" style="font-size: 10px; line-height: 1.1;" title="Gross: Rs. {{ number_format($comm->commission_amount, 2) }} | Return Due: -Rs. {{ number_format($returnDues, 2) }}">
                                                <span class="text-decoration-line-through">Rs. {{ number_format($comm->commission_amount, 2) }}</span>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-0 px-1" style="font-size: 9.5px;">
                                                    <i class="fas fa-undo-alt"></i> -{{ number_format($returnDues, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="fw-bold text-primary" style="font-size: 13.5px; font-weight: 800 !important;">
                                                Rs. {{ number_format($comm->commission_amount, 2) }}
                                            </div>
                                        @endif
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
                                            @php
                                                $btnReturnDues = $comm->status === 'paid' ? (float)($comm->return_deduction_amount ?? 0) : (float)$comm->pending_return_dues_total;
                                                $btnNetPayable = (float)$comm->net_payable;
                                            @endphp
                                            @if($comm->status === 'pending')
                                                <button type="button" class="btn-action-approve btn-open-comm-modal"
                                                        data-id="{{ $comm->id }}"
                                                        data-mode="approve"
                                                        data-carpenter="{{ $comm->carpenter->name ?? 'Carpenter' }}"
                                                        data-carpenter-phone="{{ $comm->carpenter->phone ?? '' }}"
                                                        data-invoice="{{ $comm->sale ? $comm->sale->invoice_no : '' }}"
                                                        data-customer="{{ $comm->sale && $comm->sale->customer_relation ? $comm->sale->customer_relation->customer_name : ($comm->sale->walkin_name ?? 'Walk-in') }}"
                                                        data-sale-amount="{{ (float)$comm->sale_amount }}"
                                                        data-rate="{{ $comm->commission_rate }}{{ $comm->commission_type === 'percentage' ? '%' : ' Fixed' }}"
                                                        data-gross-amount="{{ (float)$comm->commission_amount }}"
                                                        data-dues-amount="{{ $btnReturnDues }}"
                                                        data-amount="{{ $btnNetPayable }}"
                                                        title="Approve & Pay Commission">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn-action-cancel btn-cancel-single"
                                                        data-id="{{ $comm->id }}"
                                                        title="Cancel Commission">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($comm->status === 'approved')
                                                <button type="button" class="btn-action-pay btn-open-comm-modal"
                                                        data-id="{{ $comm->id }}"
                                                        data-mode="pay"
                                                        data-carpenter="{{ $comm->carpenter->name ?? 'Carpenter' }}"
                                                        data-carpenter-phone="{{ $comm->carpenter->phone ?? '' }}"
                                                        data-invoice="{{ $comm->sale ? $comm->sale->invoice_no : '' }}"
                                                        data-customer="{{ $comm->sale && $comm->sale->customer_relation ? $comm->sale->customer_relation->customer_name : ($comm->sale->walkin_name ?? 'Walk-in') }}"
                                                        data-sale-amount="{{ (float)$comm->sale_amount }}"
                                                        data-rate="{{ $comm->commission_rate }}{{ $comm->commission_type === 'percentage' ? '%' : ' Fixed' }}"
                                                        data-gross-amount="{{ (float)$comm->commission_amount }}"
                                                        data-dues-amount="{{ $btnReturnDues }}"
                                                        data-amount="{{ $btnNetPayable }}"
                                                        title="Pay Approved Commission">
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

{{-- Modal 1: Single Approve & Pay Commission (QuickBooks / Odoo POS Replica) --}}
<div class="modal fade" id="modalApproveCommission" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 490px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <form id="formApproveCommission" method="POST">
                @csrf
                <input type="hidden" name="commission_id" id="modal_comm_id">
                <input type="hidden" name="action_type" id="modal_action_type" value="approve_and_pay">

                {{-- Modal Header --}}
                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-hand-holding-usd fs-5" style="color: #38bdf8;"></i>
                        <div>
                            <h6 class="modal-title fw-bold mb-0 text-white" id="modal_comm_header_title" style="font-size: 13.5px;">
                                Approve & Pay Carpenter Commission
                            </h6>
                            <small class="text-info d-block" id="modal_comm_header_sub" style="font-size: 10.5px;">
                                Invoice Approval & Payment Source Deduction
                            </small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>

                <div class="modal-body p-3 bg-white" style="font-size: 12px;">
                    {{-- 1. Commission Details Card --}}
                    <div class="p-2.5 mb-2 rounded border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <div class="row g-1">
                            <div class="col-7">
                                <div class="text-secondary small fw-semibold" style="font-size: 10.5px;">CARPENTER:</div>
                                <strong class="text-dark d-block text-truncate" id="comm_carpenter_name">-</strong>
                                <small class="text-muted font-monospace" id="comm_carpenter_phone" style="font-size: 11px;"></small>
                            </div>
                            <div class="col-5 text-end">
                                <div class="text-secondary small fw-semibold" style="font-size: 10.5px;">INVOICE #:</div>
                                <span class="font-monospace fw-bold text-primary" id="comm_invoice_no">-</span>
                                <small class="text-muted d-block text-truncate" id="comm_customer_name" style="font-size: 11px;"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <div>
                                <span class="text-muted" style="font-size: 11px;">Sale Net:</span>
                                <span class="font-monospace text-dark fw-bold" id="comm_sale_net">Rs. 0.00</span>
                                <span class="badge bg-light text-dark border ms-1" id="comm_rate_badge" style="font-size: 10px;">0%</span>
                            </div>
                            <div>
                                <span class="text-muted me-1" style="font-size: 11px;">Payable:</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace fw-bold" id="comm_payable_display" style="font-size: 13px; padding: 4px 8px;">
                                    Rs. 0.00
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Return Dues Adjustment Alert (Shows only when return dues exist on this carpenter) --}}
                    <div id="comm_return_dues_banner" class="p-2.5 mb-2.5 rounded border border-danger-subtle" style="background: #fff8f8; display: none;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center gap-1.5 text-danger fw-bold" style="font-size: 11.5px;">
                                <i class="fas fa-undo-alt"></i>
                                <span>Customer Return Adjustment (واپسی کٹوتی)</span>
                            </div>
                            <span class="badge bg-danger text-white font-monospace" id="modal_dues_badge">-Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-1.5 border-top border-danger-subtle" style="font-size: 11px;">
                            <span class="text-secondary">Gross: <strong class="text-dark font-monospace" id="modal_gross_amt">Rs. 0.00</strong></span>
                            <span class="text-danger">Return Due: <strong class="font-monospace" id="modal_due_amt">-Rs. 0.00</strong></span>
                            <span class="text-success fw-bold">Net Payout: <strong class="font-monospace" id="modal_net_amt">Rs. 0.00</strong></span>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 10px; line-height: 1.2;">
                            * کسٹمر پروڈکٹ ریٹرن پر کارپینٹر کی بقایا رقم خودکار مائنس ہو کر صرف نیٹ رقم اکاؤنٹ سے ادا ہو گی۔
                        </div>
                    </div>

                    {{-- 2. Payment Source Selection ("پیسے کہاں سے دینے ہیں؟") --}}
                    <div class="mb-2.5">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">
                            Payment Source / Method (پیسے کہاں سے ادا کرنے ہیں؟) <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="pay-method-card active method-cash" data-method="cash" id="btn_method_cash">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="method-icon-box bg-success text-white">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <div class="method-title">Cash (کیش)</div>
                                            <div class="method-desc">Shop Cash Drawer</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle method-check text-success fs-6" id="check_cash"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="pay-method-card method-bank" data-method="bank" id="btn_method_bank">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="method-icon-box bg-primary text-white">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div>
                                            <div class="method-title">Account Transfer</div>
                                            <div class="method-desc">Bank / Online (بینک)</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle method-check text-primary fs-6" id="check_bank" style="display: none;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Deduct From Account Dropdown --}}
                    <div class="mb-2.5">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold text-dark mb-0" style="font-size: 11.5px;">
                                Deduct From Account (اکاؤنٹ جہاں سے رقم کٹے گی) <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" id="account_filter_badge" style="font-size: 10px;">Cash Accounts</span>
                        </div>
                        <select name="payment_account_id" id="modal_comm_account_id" class="form-select qb-input" style="height: 32px !important; font-size: 12px; font-weight: 600;" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                        data-head="{{ strtolower($acc->head->name ?? '') }}"
                                        data-balance="{{ (float)$acc->current_balance }}"
                                        data-title="{{ $acc->title }}"
                                        {{ str_contains(strtolower($acc->title), 'cash') ? 'selected' : '' }}>
                                    {{ $acc->title }} ({{ $acc->head->name ?? 'Account' }}) — Current Balance: Rs. {{ number_format($acc->current_balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Real-time Live Balance Math Box --}}
                    <div class="live-balance-box mb-2.5 border" id="comm_live_balance_card" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                        <div class="d-flex justify-content-between mb-1 text-muted" style="font-size: 11px;">
                            <span>Current Account Balance:</span>
                            <span class="font-monospace fw-bold text-dark" id="live_curr_bal">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-muted" style="font-size: 11px;">
                            <span>Commission Deduction:</span>
                            <span class="font-monospace fw-bold text-danger" id="live_deduct_amt">- Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between pt-1 border-top" style="font-size: 12px;">
                            <span class="fw-bold text-dark">Remaining Balance After Deduction:</span>
                            <span class="font-monospace fw-bold text-success" id="live_rem_bal" style="font-size: 12.5px;">Rs. 0.00</span>
                        </div>
                        <div id="live_bal_warning" class="text-danger small mt-1 fw-bold" style="display: none; font-size: 10.5px;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Warning: Payment exceeds current account balance. Balance will become negative.
                        </div>
                    </div>

                    {{-- 5. Payment Amount & Date --}}
                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 11px;">
                                Payment Amount (Rs.) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.00" name="paid_amount" id="modal_comm_amount"
                                   class="form-control qb-input font-monospace fw-bold text-end"
                                   style="height: 30px !important; font-size: 13px;" required>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px;">
                                Payment Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="payment_date" id="modal_comm_date"
                                   class="form-control qb-input"
                                   value="{{ date('Y-m-d') }}"
                                   style="height: 30px !important; font-size: 11.5px;" required>
                        </div>
                    </div>

                    {{-- 6. Reference # (for Bank) & Notes --}}
                    <div class="row g-2">
                        <div class="col-5" id="col_reference_no">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px;">
                                Ref / Cheque / Slip #
                            </label>
                            <input type="text" name="reference_no" id="modal_comm_reference"
                                   class="form-control qb-input font-monospace"
                                   style="height: 30px !important; font-size: 11.5px;"
                                   placeholder="Txn ID...">
                        </div>
                        <div class="col-7" id="col_notes">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px;">
                                Notes / Narration
                            </label>
                            <input type="text" name="notes" id="modal_comm_notes"
                                   class="form-control qb-input"
                                   style="height: 30px !important; font-size: 11.5px;"
                                   placeholder="Payment remarks...">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer py-2 px-3 d-flex justify-content-between" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 11.5px; font-weight: 600;">
                        Cancel
                    </button>
                    <div class="d-flex align-items-center gap-1.5">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn_modal_approve_only" style="font-size: 11px; font-weight: 700; height: 26px;">
                            <i class="fas fa-check"></i> Approve Only (Pay Later)
                        </button>
                        <button type="submit" class="qb-btn-green" id="btn_modal_confirm_pay">
                            <i class="fas fa-check-circle"></i> <span id="btn_modal_pay_text">Approve & Pay Now (Rs. 0.00)</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: Batch Approve & Pay Commissions --}}
<div class="modal fade" id="modalBatchCommission" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 490px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <form id="formBatchCommission" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="batch_action_type" value="approve_and_pay">

                <div class="modal-header py-2 px-3" style="background: linear-gradient(180deg, #18385e 0%, #102641 100%); color: #ffffff;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-double fs-5" style="color: #38bdf8;"></i>
                        <div>
                            <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13.5px;">
                                Batch Approve & Pay Commissions
                            </h6>
                            <small class="text-info d-block" style="font-size: 10.5px;">
                                Multiple Selected Commissions Payout
                            </small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                </div>

                <div class="modal-body p-3 bg-white" style="font-size: 12px;">
                    {{-- Summary --}}
                    <div class="p-2.5 mb-2.5 rounded border d-flex justify-content-between align-items-center" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <div>
                            <div class="text-secondary small fw-semibold" style="font-size: 10.5px;">SELECTED COMMISSIONS:</div>
                            <strong class="text-dark fs-6" id="batch_modal_count">0</strong> <span class="text-muted">commissions</span>
                        </div>
                        <div class="text-end">
                            <div class="text-secondary small fw-semibold" style="font-size: 10.5px;">TOTAL PAYABLE:</div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace fw-bold fs-6 px-2.5 py-1" id="batch_modal_total_display">
                                Rs. 0.00
                            </span>
                        </div>
                    </div>

                    {{-- Payment Source Selection --}}
                    <div class="mb-2.5">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">
                            Payment Source / Method (پیسے کہاں سے ادا کرنے ہیں؟) <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="pay-method-card active method-cash" data-method="cash" id="batch_btn_cash">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="method-icon-box bg-success text-white">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <div class="method-title">Cash (کیش)</div>
                                            <div class="method-desc">Shop Cash Drawer</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle method-check text-success fs-6" id="batch_check_cash"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="pay-method-card method-bank" data-method="bank" id="batch_btn_bank">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="method-icon-box bg-primary text-white">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div>
                                            <div class="method-title">Account Transfer</div>
                                            <div class="method-desc">Bank / Online (بینک)</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle method-check text-primary fs-6" id="batch_check_bank" style="display: none;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deduct From Account --}}
                    <div class="mb-2.5">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">
                            Deduct From Account <span class="text-danger">*</span>
                        </label>
                        <select name="payment_account_id" id="batch_account_select" class="form-select qb-input" style="height: 32px !important; font-size: 12px; font-weight: 600;" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                        data-head="{{ strtolower($acc->head->name ?? '') }}"
                                        data-balance="{{ (float)$acc->current_balance }}"
                                        data-title="{{ $acc->title }}"
                                        {{ str_contains(strtolower($acc->title), 'cash') ? 'selected' : '' }}>
                                    {{ $acc->title }} ({{ $acc->head->name ?? 'Account' }}) — Current Balance: Rs. {{ number_format($acc->current_balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Batch Live Balance Math Box --}}
                    <div class="live-balance-box mb-2.5 border" id="batch_live_balance_card" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                        <div class="d-flex justify-content-between mb-1 text-muted" style="font-size: 11px;">
                            <span>Current Account Balance:</span>
                            <span class="font-monospace fw-bold text-dark" id="batch_curr_bal">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-muted" style="font-size: 11px;">
                            <span>Total Batch Deduction:</span>
                            <span class="font-monospace fw-bold text-danger" id="batch_deduct_amt">- Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between pt-1 border-top" style="font-size: 12px;">
                            <span class="fw-bold text-dark">Remaining Balance After Deduction:</span>
                            <span class="font-monospace fw-bold text-success" id="batch_rem_bal" style="font-size: 12.5px;">Rs. 0.00</span>
                        </div>
                        <div id="batch_bal_warning" class="text-danger small mt-1 fw-bold" style="display: none; font-size: 10.5px;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Warning: Total deduction exceeds current account balance.
                        </div>
                    </div>

                    {{-- Batch Date & Notes --}}
                    <div class="row g-2">
                        <div class="col-5">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px;">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control qb-input" value="{{ date('Y-m-d') }}" style="height: 30px !important; font-size: 11.5px;">
                        </div>
                        <div class="col-7">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11px;">Ref # / Notes</label>
                            <input type="text" name="notes" class="form-control qb-input" placeholder="Optional batch notes..." style="height: 30px !important; font-size: 11.5px;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2 px-3 d-flex justify-content-between" style="background: #f1f5f9; border-top: 1px solid #cbd5e1;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <div class="d-flex align-items-center gap-1.5">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn_batch_approve_only" style="font-size: 11px; font-weight: 700; height: 26px;">
                            <i class="fas fa-check"></i> Approve All Only (Pay Later)
                        </button>
                        <button type="submit" class="qb-btn-green" id="btn_batch_confirm_pay">
                            <i class="fas fa-check-circle"></i> <span id="btn_batch_pay_text">Approve & Pay All</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // -------------------------------------------------------------------------
    // Helper: Format Number to Rs. Currency
    // -------------------------------------------------------------------------
    function formatRs(num) {
        return 'Rs. ' + (parseFloat(num) || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // -------------------------------------------------------------------------
    // Single Commission Modal: Open Handler (Triggered on Approve or Pay click)
    // -------------------------------------------------------------------------
    $(document).on('click', '.btn-open-comm-modal, .btn-approve-single, .btn-pay-modal', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const id = $btn.data('id');
        const mode = $btn.data('mode') || ($btn.hasClass('btn-pay-modal') ? 'pay' : 'approve');
        const carp = $btn.data('carpenter') || 'Carpenter';
        const phone = $btn.data('carpenter-phone') || '';
        const inv = $btn.data('invoice') || '-';
        const cust = $btn.data('customer') || '-';
        const saleAmt = parseFloat($btn.data('sale-amount')) || 0;
        const rate = $btn.data('rate') || '';
        const amt = parseFloat($btn.data('amount')) || 0;
        const grossAmt = parseFloat($btn.data('gross-amount')) || amt;
        const duesAmt = parseFloat($btn.data('dues-amount')) || 0;
        const netAmt = parseFloat($btn.data('amount')) || Math.max(0, grossAmt - duesAmt);

        $('#modal_comm_id').val(id);
        $('#modal_action_type').val('approve_and_pay');

        // Populate summary
        $('#comm_carpenter_name').text(carp);
        $('#comm_carpenter_phone').text(phone ? `📞 ${phone}` : '');
        $('#comm_invoice_no').text(inv);
        $('#comm_customer_name').text(cust ? `Customer: ${cust}` : '');
        $('#comm_sale_net').text(formatRs(saleAmt));
        $('#comm_rate_badge').text(rate);

        // Show/hide return dues banner
        if (duesAmt > 0) {
            $('#comm_return_dues_banner').show();
            $('#modal_dues_badge').text('- ' + formatRs(duesAmt));
            $('#modal_gross_amt').text(formatRs(grossAmt));
            $('#modal_due_amt').text('- ' + formatRs(duesAmt));
            $('#modal_net_amt').text(formatRs(netAmt));
            $('#comm_payable_display').text(formatRs(netAmt));
            $('#modal_comm_amount').val(netAmt.toFixed(2));
            $('#modal_comm_notes').val(`Carpenter Commission: ${carp} (Invoice #${inv}) [Gross: Rs. ${grossAmt.toFixed(2)} - Return Due: Rs. ${duesAmt.toFixed(2)}]`);
        } else {
            $('#comm_return_dues_banner').hide();
            $('#comm_payable_display').text(formatRs(grossAmt));
            $('#modal_comm_amount').val(netAmt.toFixed(2));
            $('#modal_comm_notes').val(`Carpenter Commission: ${carp} (Invoice #${inv})`);
        }
        $('#modal_comm_reference').val('');

        // Action URLs & Titles based on mode
        if (mode === 'pay') {
            $('#formApproveCommission').attr('action', "{{ url('carpenter-commissions') }}/" + id + "/pay");
            $('#modal_comm_header_title').html('Pay Carpenter Commission');
            $('#modal_comm_header_sub').text('Record Payment Voucher & Deduct Account');
            $('#btn_modal_approve_only').hide();
            $('#btn_modal_pay_text').text(`Confirm Payment (${formatRs(netAmt)})`);
        } else {
            $('#formApproveCommission').attr('action', "{{ url('carpenter-commissions') }}/" + id + "/approve");
            $('#modal_comm_header_title').html('Approve & Pay Carpenter Commission');
            $('#modal_comm_header_sub').text('Invoice Approval & Payment Source Deduction');
            $('#btn_modal_approve_only').show();
            $('#btn_modal_pay_text').text(`Approve & Pay Now (${formatRs(netAmt)})`);
        }

        // Set default method to Cash
        switchSinglePaymentMethod('cash');

        $('#modalApproveCommission').modal('show');
    });

    // -------------------------------------------------------------------------
    // Method Switcher (Single Modal)
    // -------------------------------------------------------------------------
    function switchSinglePaymentMethod(method) {
        if (method === 'cash') {
            $('#btn_method_cash').addClass('active method-cash').removeClass('method-bank');
            $('#btn_method_bank').removeClass('active method-bank');
            $('#check_cash').show();
            $('#check_bank').hide();
            $('#account_filter_badge').text('Cash Accounts').removeClass('bg-primary-subtle text-primary border-primary-subtle').addClass('bg-success-subtle text-success border-success-subtle');

            // Select first Cash account
            let cashOpt = $('#modal_comm_account_id option').filter(function() {
                const head = ($(this).data('head') || '').toString().toLowerCase();
                const title = ($(this).data('title') || '').toString().toLowerCase();
                return head.includes('cash') || title.includes('cash');
            }).first();

            if (cashOpt.length) {
                $('#modal_comm_account_id').val(cashOpt.val());
            }
        } else {
            $('#btn_method_bank').addClass('active method-bank').removeClass('method-cash');
            $('#btn_method_cash').removeClass('active method-cash');
            $('#check_bank').show();
            $('#check_cash').hide();
            $('#account_filter_badge').text('Bank Accounts').removeClass('bg-success-subtle text-success border-success-subtle').addClass('bg-primary-subtle text-primary border-primary-subtle');

            // Select first Bank account
            let bankOpt = $('#modal_comm_account_id option').filter(function() {
                const head = ($(this).data('head') || '').toString().toLowerCase();
                const title = ($(this).data('title') || '').toString().toLowerCase();
                return head.includes('bank') || title.includes('meezan') || title.includes('hbl') || title.includes('bank');
            }).first();

            if (bankOpt.length) {
                $('#modal_comm_account_id').val(bankOpt.val());
            }
        }
        updateSingleLiveBalance();
    }

    $('#btn_method_cash').on('click', function() {
        switchSinglePaymentMethod('cash');
    });

    $('#btn_method_bank').on('click', function() {
        switchSinglePaymentMethod('bank');
    });

    // Account change in Single Modal
    $('#modal_comm_account_id').on('change', function() {
        const $opt = $(this).find(':selected');
        const head = ($opt.data('head') || '').toString().toLowerCase();
        const title = ($opt.data('title') || '').toString().toLowerCase();
        if (head.includes('bank') || title.includes('bank') || title.includes('meezan') || title.includes('hbl')) {
            $('#btn_method_bank').addClass('active method-bank');
            $('#btn_method_cash').removeClass('active method-cash');
            $('#check_bank').show();
            $('#check_cash').hide();
            $('#account_filter_badge').text('Bank Account').removeClass('bg-success-subtle text-success border-success-subtle').addClass('bg-primary-subtle text-primary border-primary-subtle');
        } else {
            $('#btn_method_cash').addClass('active method-cash');
            $('#btn_method_bank').removeClass('active method-bank');
            $('#check_cash').show();
            $('#check_bank').hide();
            $('#account_filter_badge').text('Cash Account').removeClass('bg-primary-subtle text-primary border-primary-subtle').addClass('bg-success-subtle text-success border-success-subtle');
        }
        updateSingleLiveBalance();
    });

    // Amount input change
    $('#modal_comm_amount').on('input', function() {
        updateSingleLiveBalance();
    });

    // Calculate Single Live Balance
    function updateSingleLiveBalance() {
        const $opt = $('#modal_comm_account_id').find(':selected');
        const currBal = parseFloat($opt.data('balance')) || 0;
        const deductAmt = parseFloat($('#modal_comm_amount').val()) || 0;
        const remaining = currBal - deductAmt;

        $('#live_curr_bal').text(formatRs(currBal));
        $('#live_deduct_amt').text('- ' + formatRs(deductAmt));
        $('#live_rem_bal').text(formatRs(remaining));

        if (remaining < 0) {
            $('#comm_live_balance_card').css({
                'background': '#fffbeb',
                'border-color': '#fde68a'
            });
            $('#live_rem_bal').removeClass('text-success').addClass('text-danger');
            $('#live_bal_warning').show();
        } else {
            $('#comm_live_balance_card').css({
                'background': '#f0fdf4',
                'border-color': '#bbf7d0'
            });
            $('#live_rem_bal').removeClass('text-danger').addClass('text-success');
            $('#live_bal_warning').hide();
        }

        // Update button text
        const isPayMode = ($('#formApproveCommission').attr('action') || '').indexOf('/pay') !== -1;
        if (isPayMode) {
            $('#btn_modal_pay_text').text(`Confirm Payment (${formatRs(deductAmt)})`);
        } else {
            $('#btn_modal_pay_text').text(`Approve & Pay Now (${formatRs(deductAmt)})`);
        }
    }

    // Submit: Approve Only (Pay Later)
    $('#btn_modal_approve_only').on('click', function() {
        const id = $('#modal_comm_id').val();
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');

        $.ajax({
            url: "{{ url('carpenter-commissions') }}/" + id + "/approve",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action_type: 'approve_only'
            },
            success: function(res) {
                $('#modalApproveCommission').modal('hide');
                if (res.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: res.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Approval failed.', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve Only (Pay Later)');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Approval failed.';
                Swal.fire('Error', msg, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve Only (Pay Later)');
            }
        });
    });

    // Submit: Confirm Approve & Pay / Pay Now
    $('#formApproveCommission').on('submit', function(e) {
        e.preventDefault();
        const url = $(this).attr('action');
        const $submitBtn = $('#btn_modal_confirm_pay');
        const originalBtnHtml = $submitBtn.html();

        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing Payment...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    $('#modalApproveCommission').modal('hide');
                    Swal.fire({
                        title: 'Payment & Approval Successful!',
                        html: `<div class="text-start p-2" style="font-size: 13px;">
                                   <div class="mb-2 text-dark font-monospace">${res.message}</div>
                                   ${res.voucher_no ? `<div class="badge bg-primary text-white p-2">Voucher #: ${res.voucher_no}</div>` : ''}
                               </div>`,
                        icon: 'success',
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'OK'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Payment failed.', 'error');
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to process payment.';
                Swal.fire('Error', msg, 'error');
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    // -------------------------------------------------------------------------
    // Single Cancel
    // -------------------------------------------------------------------------
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
                        if (res.success) {
                            Swal.fire('Cancelled', res.message || 'Cancelled successfully.', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message || 'Failed to cancel.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to cancel commission.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    // -------------------------------------------------------------------------
    // Batch Checkboxes & Batch Modal
    // -------------------------------------------------------------------------
    $('#checkAllCommissions').on('change', function() {
        $('.comm-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchButton();
    });

    $(document).on('change', '.comm-checkbox', function() {
        updateBatchButton();
    });

    function updateBatchButton() {
        const checkedBoxes = $('.comm-checkbox:checked');
        const count = checkedBoxes.length;
        $('#batchSelectedCount').text(count);
        if (count > 0) {
            $('#btnBatchApprove').fadeIn(150);
        } else {
            $('#btnBatchApprove').fadeOut(150);
        }
    }

    // Open Batch Modal
    $('#btnBatchApprove').on('click', function() {
        const checkedBoxes = $('.comm-checkbox:checked');
        const ids = checkedBoxes.map(function() { return $(this).val(); }).get();
        if (!ids.length) return;

        let totalAmt = 0;
        checkedBoxes.each(function() {
            totalAmt += parseFloat($(this).data('amount')) || 0;
        });

        $('#batch_modal_count').text(ids.length);
        $('#batch_modal_total_display').text(formatRs(totalAmt));
        $('#batch_deduct_amt').text('- ' + formatRs(totalAmt));
        $('#btn_batch_pay_text').text(`Approve & Pay All (${formatRs(totalAmt)})`);

        switchBatchPaymentMethod('cash');
        $('#modalBatchCommission').modal('show');
    });

    function switchBatchPaymentMethod(method) {
        if (method === 'cash') {
            $('#batch_btn_cash').addClass('active method-cash').removeClass('method-bank');
            $('#batch_btn_bank').removeClass('active method-bank');
            $('#batch_check_cash').show();
            $('#batch_check_bank').hide();

            let cashOpt = $('#batch_account_select option').filter(function() {
                const head = ($(this).data('head') || '').toString().toLowerCase();
                const title = ($(this).data('title') || '').toString().toLowerCase();
                return head.includes('cash') || title.includes('cash');
            }).first();
            if (cashOpt.length) $('#batch_account_select').val(cashOpt.val());
        } else {
            $('#batch_btn_bank').addClass('active method-bank').removeClass('method-cash');
            $('#batch_btn_cash').removeClass('active method-cash');
            $('#batch_check_bank').show();
            $('#batch_check_cash').hide();

            let bankOpt = $('#batch_account_select option').filter(function() {
                const head = ($(this).data('head') || '').toString().toLowerCase();
                const title = ($(this).data('title') || '').toString().toLowerCase();
                return head.includes('bank') || title.includes('meezan') || title.includes('hbl');
            }).first();
            if (bankOpt.length) $('#batch_account_select').val(bankOpt.val());
        }
        updateBatchLiveBalance();
    }

    $('#batch_btn_cash').on('click', function() { switchBatchPaymentMethod('cash'); });
    $('#batch_btn_bank').on('click', function() { switchBatchPaymentMethod('bank'); });

    $('#batch_account_select').on('change', function() {
        updateBatchLiveBalance();
    });

    function updateBatchLiveBalance() {
        const checkedBoxes = $('.comm-checkbox:checked');
        let totalAmt = 0;
        checkedBoxes.each(function() { totalAmt += parseFloat($(this).data('amount')) || 0; });

        const $opt = $('#batch_account_select').find(':selected');
        const currBal = parseFloat($opt.data('balance')) || 0;
        const remaining = currBal - totalAmt;

        $('#batch_curr_bal').text(formatRs(currBal));
        $('#batch_deduct_amt').text('- ' + formatRs(totalAmt));
        $('#batch_rem_bal').text(formatRs(remaining));

        if (remaining < 0) {
            $('#batch_live_balance_card').css({ 'background': '#fffbeb', 'border-color': '#fde68a' });
            $('#batch_rem_bal').removeClass('text-success').addClass('text-danger');
            $('#batch_bal_warning').show();
        } else {
            $('#batch_live_balance_card').css({ 'background': '#f0fdf4', 'border-color': '#bbf7d0' });
            $('#batch_rem_bal').removeClass('text-danger').addClass('text-success');
            $('#batch_bal_warning').hide();
        }
    }

    // Batch: Approve All Only (Pay Later)
    $('#btn_batch_approve_only').on('click', function() {
        const ids = $('.comm-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (!ids.length) return;

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');

        $.ajax({
            url: "{{ route('carpenter_commissions.batch_approve') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids,
                action_type: 'approve_only'
            },
            success: function(res) {
                $('#modalBatchCommission').modal('hide');
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve All Only (Pay Later)');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Batch approval failed.';
                Swal.fire('Error', msg, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve All Only (Pay Later)');
            }
        });
    });

    // Batch: Confirm Approve & Pay All
    $('#formBatchCommission').on('submit', function(e) {
        e.preventDefault();
        const ids = $('.comm-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (!ids.length) return;

        const $submitBtn = $('#btn_batch_confirm_pay');
        const originalBtnHtml = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing Batch...');

        const formData = $(this).serializeArray();
        ids.forEach(function(id) {
            formData.push({ name: 'ids[]', value: id });
        });

        $.ajax({
            url: "{{ route('carpenter_commissions.batch_approve') }}",
            type: 'POST',
            data: $.param(formData),
            success: function(res) {
                if (res.success) {
                    $('#modalBatchCommission').modal('hide');
                    Swal.fire('Batch Payment & Approval Successful!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Batch payment failed.', 'error');
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Batch payment failed.';
                Swal.fire('Error', msg, 'error');
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
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
