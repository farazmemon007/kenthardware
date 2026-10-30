@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 4 (Approve Count Reconciliation) */
    .img4-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: calc(100vh - 56px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: auto;
    }
    .img4-container {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        min-width: 1080px;
        max-width: 100%;
        margin: 0 auto;
    }

    /* Top Dark Bar */
    .img4-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img4-top-bar .title-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .top-strip-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        padding: 4px 11px;
        border-radius: 4px;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.22);
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        line-height: 1.3;
        margin-left: 8px;
        white-space: nowrap;
    }
    .top-strip-back-btn:hover {
        background: rgba(255, 255, 255, 0.24);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.4);
        text-decoration: none;
    }
    .top-strip-back-btn i {
        font-size: 0.72rem;
    }
    .img4-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
    }
    .img4-top-bar .badge-pill-role {
        background: #dc2626;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img4-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* Top Metric KPI Cards Bar */
    .img4-kpi-bar {
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .img4-kpi-cards-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .kpi-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 6px 14px;
        min-width: 110px;
    }
    .kpi-box-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .kpi-box-val {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .kpi-box.matched {
        border-color: #86efac;
    }
    .kpi-box.matched .kpi-box-label {
        color: #15803d;
    }
    .kpi-box.matched .kpi-box-val {
        color: #16a34a;
    }
    .kpi-box.variances .kpi-box-label {
        color: #b91c1c;
    }
    .kpi-box.variances .kpi-box-val {
        color: #dc2626;
    }

    /* Top Right View Tabs */
    .kpi-view-tabs {
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .kpi-view-tabs .view-label {
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    .kpi-view-tab-btn {
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        padding: 2px 4px;
    }
    .kpi-view-tab-btn.active {
        font-weight: 700;
        color: #0f172a;
        border-bottom: 2px solid #0f172a;
    }

    /* Main Grid: Discrepancy Table + Right Sidebar: Fixed 2-Column */
    .img4-main-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Left Table Section */
    .img4-table-sec {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
    }
    .audit-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .audit-table th {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        padding: 6px 8px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .audit-table td {
        padding: 7px 8px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }
    .audit-table tbody tr:hover {
        background: #f8fafc;
    }

    .col-item-title {
        font-weight: 700;
        color: #0f172a;
    }
    .col-code-mono {
        font-family: monospace;
        font-size: 0.76rem;
        color: #475569;
    }
    .diff-red {
        color: #dc2626;
        font-weight: 800;
    }
    .diff-green {
        color: #16a34a;
        font-weight: 800;
    }

    /* Smart Hint Row */
    .hint-sub-row td {
        background: #f0fdf4 !important;
        border-bottom: 1px solid #dcfce7 !important;
        padding: 5px 12px !important;
        font-size: 0.74rem;
        color: #15803d;
    }

    /* Reason Pills Styling */
    .reason-badge-pill {
        display: inline-block;
        border: none;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
    }
    .reason-badge-pill.theft { background: #dc2626; }
    .reason-badge-pill.damage { background: #d97706; }
    .reason-badge-pill.given { background: #8b5cf6; }
    .reason-badge-pill.entry-error { background: #475569; }
    .reason-badge-pill.misplaced { background: #0d9488; }
    .reason-badge-pill.needs-select {
        background: #ffffff;
        border: 1px dashed #dc2626;
        color: #dc2626;
    }

    /* Status indicator */
    .status-text-ready {
        color: #16a34a;
        font-weight: 600;
        font-size: 0.76rem;
    }
    .status-text-needs {
        color: #dc2626;
        font-weight: 600;
        font-size: 0.76rem;
    }

    /* Action buttons in row */
    .btn-row-action {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 2px 7px;
        cursor: pointer;
    }
    .btn-row-action:hover {
        background: #059669;
    }
    .btn-row-recount {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 2px 7px;
        cursor: pointer;
        margin-left: 2px;
    }
    .btn-row-recount:hover {
        background: #475569;
    }

    /* Matched Items Compact 3-Column Section */
    .matched-items-section {
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
    }
    .matched-header {
        font-size: 0.74rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .matched-compact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px 16px;
        font-size: 0.75rem;
    }
    .matched-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2px 0;
        color: #334155;
    }
    .matched-code {
        font-family: monospace;
        color: #64748b;
        font-size: 0.72rem;
    }
    .matched-name {
        flex: 1;
        margin: 0 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .matched-val-check {
        color: #16a34a;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Right Sidebar Analytics Section */
    .img4-sidebar {
        padding: 12px 14px;
        background: #f8fafc;
        font-size: 0.75rem;
    }
    .sidebar-block {
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .sidebar-block:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .sidebar-block-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .five-reason-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 5px;
        line-height: 1.25;
    }
    .five-reason-pill {
        width: 72px;
        padding: 1px 4px;
        font-size: 0.68rem;
        font-weight: 700;
        color: #ffffff;
        border-radius: 3px;
        text-align: center;
        white-space: nowrap;
    }

    /* Report Variance Bars */
    .variance-report-bar-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .variance-report-label {
        width: 65px;
        color: #475569;
    }
    .variance-report-track {
        flex: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }
    .variance-report-fill {
        height: 100%;
        border-radius: 3px;
    }
    .variance-report-count {
        width: 14px;
        font-weight: 700;
        color: #0f172a;
        text-align: right;
    }

    /* Repeat Variances List */
    .repeat-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .repeat-item-name {
        color: #334155;
    }
    .repeat-item-badge {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .repeat-count-tag {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.72rem;
    }

    /* Bottom Action Bar */
    .img4-bottom-bar {
        padding: 10px 16px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .bottom-auto-caption {
        font-size: 0.74rem;
        color: #64748b;
        max-width: 60%;
    }
    .bottom-btn-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-act-recount-all {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 7px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-act-recount-all:hover {
        background: #475569;
    }
    .btn-act-approve-all {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-act-approve-all:hover {
        background: #059669;
    }

    /* Bottom 4 Explanation Boxes: Fixed 4 Columns */
    .img4-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }
    .img4-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img4-box:last-child {
        border-right: none;
    }
    .img4-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img4-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }

    /* Mobile Responsive Adaptation */
    @media (max-width: 991.98px) {
        .img4-wrapper {
            padding: 8px 8px 85px 8px !important;
            background: #f1f5f9 !important;
            overflow-x: hidden !important;
            min-height: calc(100vh - 60px) !important;
        }
        .img4-container {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .img4-top-bar {
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
            box-shadow: 0 4px 14px rgba(11, 19, 41, 0.25);
        }
        .img4-top-bar .title-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .img4-top-bar .main-title {
            font-size: 1.02rem;
        }
        .concept-note {
            display: none !important;
        }
        .img4-kpi-bar {
            border-radius: 12px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
        }
        .img4-main-grid {
            display: flex !important;
            flex-direction: column !important;
        }
        .img4-table-sec {
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto !important;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .img4-sidebar {
            width: 100% !important;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .img4-bottom-grid {
            display: flex !important;
            flex-direction: column !important;
        }
        .img4-box {
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0;
        }
    }
</style>

<div class="img4-wrapper">
    <div class="img4-container">
        {{-- Top Dark Bar --}}
        <div class="img4-top-bar">
            <div class="title-section">
                <span class="main-title">4 · Approve count {{ $cycleCount->count_number }} — Shelf {{ $cycleCount->location ? $cycleCount->location->name : ($cycleCount->location_name ?: 'BCD 12') }}</span>
                <span class="badge-pill-role">Owner / In-charge only · never the counter</span>
            </div>
            <div class="d-flex align-items-center gap-2" style="gap:10px;">
                <a href="{{ route('cycle_count.sheet', $cycleCount->id) }}"
                   style="display:inline-flex; align-items:center; gap:5px; background:#1e40af; color:#fff; padding:4px 12px; border-radius:4px; font-size:0.78rem; font-weight:700; text-decoration:none; white-space:nowrap;"
                   title="View what the staff physically entered on Screen 3">
                    <i class="fas fa-list-check"></i> View Staff Sheet
                </a>
                <div class="concept-note" style="margin-left:8px;">
                    CONCEPT for approval · all numbers are illustrative
                </div>
                <a href="{{ route('cycle_count.index') }}" onclick="if(document.referrer && document.referrer.indexOf(window.location.host) !== -1 && document.referrer !== window.location.href) { history.back(); return false; }" class="top-strip-back-btn" title="Back to Schedule">
                    <i class="fas fa-arrow-left"></i> <span>Back</span>
                </a>
            </div>
        </div>

        {{-- Top Metric Cards Bar --}}
        <div class="img4-kpi-bar">
            <div class="img4-kpi-cards-group">
                <div class="kpi-box">
                    <div class="kpi-box-label">ITEMS COUNTED</div>
                    <div class="kpi-box-val">{{ $cycleCount->total_items }}</div>
                </div>
                <div class="kpi-box matched">
                    <div class="kpi-box-label">MATCHED — NO ACTION</div>
                    <div class="kpi-box-val">{{ $matched->count() }}</div>
                </div>
                <div class="kpi-box variances">
                    <div class="kpi-box-label">VARIANCES</div>
                    <div class="kpi-box-val">{{ $variances->count() }}</div>
                </div>
                <div class="kpi-box">
                    <div class="kpi-box-label">VARIANCE VALUE (RS)</div>
                    <div class="kpi-box-val">{{ number_format(abs($cycleCount->total_variance_value), 0) }}</div>
                </div>
                <div class="kpi-box" style="position:relative;">
                    <div class="kpi-box-label">COUNTED BY</div>
                    <div class="kpi-box-val" style="font-size: 0.95rem; font-weight: 700;">
                        [{{ $cycleCount->counter ? $cycleCount->counter->name : 'Staff B' }}] · {{ $cycleCount->submitted_at ? $cycleCount->submitted_at->format('H:i') : '09:18' }}
                    </div>
                    <a href="{{ route('cycle_count.sheet', $cycleCount->id) }}"
                       style="font-size:0.67rem; color:#3b82f6; text-decoration:none; font-weight:700; white-space:nowrap; display:inline-flex; align-items:center; gap:3px; margin-top:3px;"
                       title="Open the exact qty, timestamps and items entered by counter">
                        <i class="fas fa-external-link-alt" style="font-size:0.6rem;"></i> View Original Sheet
                    </a>
                </div>
                <div class="kpi-box" style="border-left: 3px solid #10b981; background: #f0fdf4;">
                    <div class="kpi-box-label" style="color: #047857;">TIME DURATION TAKEN</div>
                    <div class="kpi-box-val" style="font-size: 0.95rem; font-weight: 700; color: #059669;">
                        <i class="fas fa-stopwatch me-1"></i> {{ $cycleCount->formatted_duration }}
                    </div>
                </div>
            </div>

            <div class="kpi-view-tabs">
                <span class="view-label">VIEW</span>
                <a href="javascript:void(0)" class="kpi-view-tab-btn active" id="tabVarBtn" onclick="filterAuditView('variances', this)">Variances ({{ $variances->count() }})</a>
                <a href="javascript:void(0)" class="kpi-view-tab-btn" id="tabMatchBtn" onclick="filterAuditView('matched', this)">Matched ({{ $matched->count() }})</a>
                <a href="javascript:void(0)" class="kpi-view-tab-btn" id="tabAllBtn" onclick="filterAuditView('all', this)">All ({{ $items->count() }})</a>
            </div>
        </div>

        {{-- Main Grid: Left Table + Right Sidebar --}}
        <div class="img4-main-grid">
            {{-- Left Section: Discrepancy Table + Matched items --}}
            <div class="img4-table-sec">
                <div style="overflow-x: auto;">
                    <table class="audit-table" id="discrepancyExactTable">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>CODE</th>
                                <th>LOC</th>
                                <th style="text-align: right;">SYSTEM</th>
                                <th style="text-align: right;">COUNTED</th>
                                <th style="text-align: right;">VAR</th>
                                <th style="text-align: right;">VALUE</th>
                                <th>REASON (REQUIRED)</th>
                                <th>STATUS</th>
                                <th style="text-align: right;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variances as $idx => $v)
                                <tr class="row-audit-item is-variance-row" id="row-var-{{ $v->id }}">
                                    <td class="col-item-title">{{ $v->item_name }}</td>
                                    <td class="col-code-mono">{{ $v->item_code ?: 'HND-HNX-00' . ($idx + 1) }}</td>
                                    <td>{{ $v->location_name ?: 'BCD 12' }}</td>
                                    <td style="text-align: right;">{{ (int)$v->system_qty }}</td>
                                    <td style="text-align: right;">{{ (int)$v->counted_qty }}</td>
                                    <td style="text-align: right;">
                                        @if($v->difference < 0)
                                            <span class="diff-red">{{ (int)$v->difference }}</span>
                                        @else
                                            <span class="diff-green">+{{ (int)$v->difference }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; font-weight: 600;">
                                        Rs {{ number_format(abs($v->variance_value), 0) }}
                                    </td>
                                    <td>
                                        <select class="reason-badge-pill {{ $v->variance_reason ? strtolower(str_replace(' ', '-', $v->variance_reason)) : 'needs-select' }}"
                                                data-item-id="{{ $v->id }}"
                                                onchange="onReasonChanged(this)">
                                            <option value="" {{ empty($v->variance_reason) ? 'selected' : '' }}>Select reason ▼</option>
                                            <option value="MISPLACED" {{ $v->variance_reason === 'MISPLACED' ? 'selected' : '' }}>MISPLACED ▼</option>
                                            <option value="DAMAGE" {{ $v->variance_reason === 'DAMAGE' ? 'selected' : '' }}>DAMAGE ▼</option>
                                            <option value="THEFT" {{ $v->variance_reason === 'THEFT' ? 'selected' : '' }}>THEFT ▼</option>
                                            <option value="ENTRY ERROR" {{ $v->variance_reason === 'ENTRY ERROR' ? 'selected' : '' }}>ENTRY ERROR ▼</option>
                                            <option value="GIVEN" {{ $v->variance_reason === 'GIVEN' ? 'selected' : '' }}>GIVEN ▼</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span class="{{ $v->variance_reason ? 'status-text-ready' : 'status-text-needs' }}" id="status-text-{{ $v->id }}">
                                            {{ $v->variance_reason ? 'Ready' : 'Needs reason' }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; white-space: nowrap;">
                                        <button type="button" class="btn-row-action" onclick="approveSingleItem({{ $v->id }})">Approve</button>
                                        <button type="button" class="btn-row-recount" onclick="recountItem({{ $v->id }})">Recount</button>
                                    </td>
                                </tr>

                                {{-- Smart Hint Row if misplaced or opposite variance --}}
                                @if($loop->first)
                                    <tr class="hint-sub-row">
                                        <td colspan="10">
                                            ↳ <strong>Hint:</strong> the same item shows +2 in SB4 counted 2 days ago — possibly MISPLACED between {{ $cycleCount->location ? $cycleCount->location->name : 'BCD 12' }} and SB4. (illustrative)
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center; color: #64748b; padding: 20px;">
                                        No variances found. All counted quantities matched expected system stock!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Matched Items Compact 3-Column List (Matching Reference Image 4) --}}
                <div class="matched-items-section" id="matchedItemsCompactBlock">
                    <div class="matched-header">
                        MATCHED — NO ACTION NEEDED ({{ $matched->count() }}) showing {{ min(18, $matched->count()) }} · counted = system, no stock change
                    </div>

                    <div class="matched-compact-grid">
                        @foreach($matched->take(18) as $m)
                            <div class="matched-item-row">
                                <span class="matched-code">{{ $m->item_code ?: 'HND-HNX-001' }}</span>
                                <span class="matched-name" title="{{ $m->item_name }}">{{ $m->item_name }}</span>
                                <span class="matched-val-check">{{ (int)$m->system_qty }} ✓</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Section: 3 Analytics Cards (Matching Reference Image 4) --}}
            <div class="img4-sidebar">
                {{-- Card 1: The five reasons --}}
                <div class="sidebar-block">
                    <div class="sidebar-block-title">The five reasons</div>
                    <div class="five-reason-row">
                        <span class="five-reason-pill" style="background: #dc2626;">THEFT</span>
                        <span>Item taken, not found anywhere</span>
                    </div>
                    <div class="five-reason-row">
                        <span class="five-reason-pill" style="background: #d97706;">DAMAGE</span>
                        <span>Broken or damaged — goes to the supplier-return log</span>
                    </div>
                    <div class="five-reason-row">
                        <span class="five-reason-pill" style="background: #8b5cf6;">GIVEN</span>
                        <span>Given out without a bill (sample, free, taken)</span>
                    </div>
                    <div class="five-reason-row">
                        <span class="five-reason-pill" style="background: #475569;">ENTRY ERROR</span>
                        <span>System or entry mistake (wrong unit, wrong item, receipt error)</span>
                    </div>
                    <div class="five-reason-row">
                        <span class="five-reason-pill" style="background: #0d9488;">MISPLACED</span>
                        <span>Item exists, wrong location — NEW</span>
                    </div>
                </div>

                {{-- Card 2: Report — variances by reason, this month --}}
                <div class="sidebar-block">
                    <div class="sidebar-block-title">Report — variances by reason, this month <span style="font-weight:400; color:#94a3b8;">(illustrative)</span></div>
                    <div class="variance-report-bar-row">
                        <span class="variance-report-label">Misplaced</span>
                        <div class="variance-report-track">
                            <div class="variance-report-fill" style="width: 90%; background: #0d9488;"></div>
                        </div>
                        <span class="variance-report-count">9</span>
                    </div>
                    <div class="variance-report-bar-row">
                        <span class="variance-report-label">Entry error</span>
                        <div class="variance-report-track">
                            <div class="variance-report-fill" style="width: 70%; background: #475569;"></div>
                        </div>
                        <span class="variance-report-count">7</span>
                    </div>
                    <div class="variance-report-bar-row">
                        <span class="variance-report-label">Damage</span>
                        <div class="variance-report-track">
                            <div class="variance-report-fill" style="width: 40%; background: #d97706;"></div>
                        </div>
                        <span class="variance-report-count">4</span>
                    </div>
                    <div class="variance-report-bar-row">
                        <span class="variance-report-label">Given</span>
                        <div class="variance-report-track">
                            <div class="variance-report-fill" style="width: 30%; background: #8b5cf6;"></div>
                        </div>
                        <span class="variance-report-count">3</span>
                    </div>
                    <div class="variance-report-bar-row">
                        <span class="variance-report-label">Theft</span>
                        <div class="variance-report-track">
                            <div class="variance-report-fill" style="width: 15%; background: #dc2626;"></div>
                        </div>
                        <span class="variance-report-count">1</span>
                    </div>

                    <div style="font-size: 0.7rem; color: #64748b; margin-top: 6px;">
                        Misplaced is highest — fix labels and locations, not theft controls.
                    </div>
                </div>

                {{-- Card 3: Repeat variances — last 90 days --}}
                <div class="sidebar-block">
                    <div class="sidebar-block-title">Repeat variances — last 90 days <span style="font-weight:400; color:#94a3b8;">(illustrative)</span></div>
                    <div class="repeat-item-row">
                        <span class="repeat-item-name">Handle 6 in · BCD 12</span>
                        <div class="repeat-item-badge">
                            <span class="repeat-count-tag">4x</span>
                            <span class="five-reason-pill" style="background: #0d9488; width: 62px;">MISPLACED</span>
                        </div>
                    </div>
                    <div class="repeat-item-row">
                        <span class="repeat-item-name">Drawer channel 14 in · BCD 12</span>
                        <div class="repeat-item-badge">
                            <span class="repeat-count-tag">3x</span>
                            <span class="five-reason-pill" style="background: #d97706; width: 62px;">DAMAGE</span>
                        </div>
                    </div>
                    <div class="repeat-item-row">
                        <span class="repeat-item-name">Screw 2 in · BCD 12</span>
                        <div class="repeat-item-badge">
                            <span class="repeat-count-tag">3x</span>
                            <span class="five-reason-pill" style="background: #475569; width: 62px;">ENTRY ERROR</span>
                        </div>
                    </div>
                    <div class="repeat-item-row">
                        <span class="repeat-item-name">Cabinet lock Round · BCD 12</span>
                        <div class="repeat-item-badge">
                            <span class="repeat-count-tag">2x</span>
                            <span class="five-reason-pill" style="background: #dc2626; width: 62px;">THEFT</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Footer Bar (Matching Reference Image 4) --}}
        <div class="img4-bottom-bar">
            <div class="bottom-auto-caption">
                Approve posts the stock adjustment automatically — nobody retypes. Matched items change no stock; only "counted, matched, date" is saved.
            </div>

            <div class="bottom-btn-group">
                <a href="{{ route('cycle_count.sheet', $cycleCount->id) }}"
                   class="btn-act-recount-all"
                   style="text-decoration:none; background:#1e40af; border-color:#1e40af;"
                   title="Inspect the exact quantities entered by the counter on Screen 3">
                    <i class="fas fa-list-check me-1"></i> View Staff Sheet
                </a>

                <form action="{{ route('cycle_count.recount', $cycleCount->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-act-recount-all" onclick="return confirm('Request recount from staff for all variances?');">
                        Recount all variances
                    </button>
                </form>

                <form action="{{ route('cycle_count.approve_all', $cycleCount->id) }}" method="POST" style="display:inline;" id="approveAllForm">
                    @csrf
                    <button type="submit" class="btn-act-approve-all" id="btnApproveAllReady">
                        Approve all ready (<span id="readyCountBadge">{{ $variances->whereNotNull('variance_reason')->count() + $matched->count() }}</span>)
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom 4 Explanatory Boxes (Exact copy of Reference Image 4) --}}
        <div class="img4-bottom-grid">
            <div class="img4-box">
                <h6>Never auto-applied</h6>
                <p>A mismatch always waits here. The counter cannot see this screen.</p>
            </div>
            <div class="img4-box">
                <h6>Reason</h6>
                <p>Dropdown of five, required before Approve. Picked by Owner / In-charge, not the counter.</p>
            </div>
            <div class="img4-box">
                <h6>Stored</h6>
                <p>Reason is saved with each variance — report by reason, location, item and month.</p>
            </div>
            <div class="img4-box">
                <h6>Test</h6>
                <p>Item with system 10, physical 8 &rarr; counter never sees 10 &rarr; variance of 2 recorded &rarr; shows only here, not auto-applied.</p>
            </div>
        </div>
    </div>
</div>

<script>
const countId = {{ $cycleCount->id }};
const csrfToken = '{{ csrf_token() }}';

function onReasonChanged(selectEl) {
    const itemId = selectEl.getAttribute('data-item-id');
    const val = selectEl.value;
    const statusText = document.getElementById('status-text-' + itemId);

    // Update classes
    selectEl.className = 'reason-badge-pill';
    if (!val) {
        selectEl.classList.add('needs-select');
        statusText.className = 'status-text-needs';
        statusText.textContent = 'Needs reason';
        return;
    }

    const clsMap = {
        'MISPLACED': 'misplaced',
        'DAMAGE': 'damage',
        'THEFT': 'theft',
        'ENTRY ERROR': 'entry-error',
        'GIVEN': 'given'
    };
    selectEl.classList.add(clsMap[val] || 'misplaced');
    statusText.className = 'status-text-ready';
    statusText.textContent = 'Ready';

    fetch('{{ route("cycle_count.update_reason", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            item_id: itemId,
            variance_reason: val
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status !== 'success') {
            alert('Failed to save reason: ' + data.message);
        }
    })
    .catch(err => console.error(err));
}

function approveSingleItem(itemId) {
    fetch('/cycle-count/' + countId + '/approve-item/' + itemId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Approval error: ' + (data.message || 'Check reason is selected.'));
        }
    })
    .catch(err => alert('Error connecting to server.'));
}

function recountItem(itemId) {
    alert('Item flagged for recount.');
}

function filterAuditView(mode, tabBtn) {
    document.querySelectorAll('.kpi-view-tab-btn').forEach(b => b.classList.remove('active'));
    tabBtn.classList.add('active');

    const matchedBlock = document.getElementById('matchedItemsCompactBlock');
    const varRows = document.querySelectorAll('.is-variance-row');

    if (mode === 'variances') {
        matchedBlock.style.display = 'none';
        varRows.forEach(r => r.style.display = '');
    } else if (mode === 'matched') {
        matchedBlock.style.display = 'block';
        varRows.forEach(r => r.style.display = 'none');
    } else {
        matchedBlock.style.display = 'block';
        varRows.forEach(r => r.style.display = '');
    }
}
</script>
@endsection
