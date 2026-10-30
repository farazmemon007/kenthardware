@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 1 */
    .img1-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: calc(100vh - 56px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: auto;
    }
    .img1-container {
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
    .img1-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    .img1-top-bar .title-section {
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
    .img1-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
    }
    .img1-top-bar .badge-pill-role {
        background: #2563eb;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img1-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* 3-Column Layout: Fixed Solid Board Structure Across All Devices */
    .img1-main-grid {
        display: grid;
        grid-template-columns: 260px 1fr 360px;
        border-bottom: 1px solid #e2e8f0;
        min-height: 580px;
    }

    /* Column 1: Left Form */
    .col-form {
        padding: 14px 16px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.8rem;
        width: 260px;
        box-sizing: border-box;
    }
    .form-step-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #334155;
        text-transform: uppercase;
        margin: 12px 0 6px 0;
        letter-spacing: 0.2px;
    }
    .form-step-title:first-child {
        margin-top: 0;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        color: #1e293b;
        font-size: 0.79rem;
        cursor: pointer;
    }
    .radio-option input {
        cursor: pointer;
    }
    .form-input-clean {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 5px 8px;
        font-size: 0.78rem;
        color: #0f172a;
    }
    .form-input-clean:focus {
        border-color: #2563eb;
        outline: none;
    }

    /* Workload section */
    .workload-section {
        margin-top: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.74rem;
    }
    .workload-title {
        font-weight: 700;
        color: #475569;
        font-size: 0.71rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .workload-card {
        padding: 6px 8px;
        margin-bottom: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }
    .workload-card:last-child {
        margin-bottom: 0;
    }
    .workload-card:hover {
        border-color: #93c5fd;
        background: #f0f7ff;
    }
    .workload-card.is-active-staff {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.12);
    }
    .workload-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 4px;
    }
    .workload-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .workload-count-badge {
        font-size: 0.68rem;
        color: #64748b;
        font-weight: 600;
        white-space: nowrap;
    }
    .workload-card.is-active-staff .workload-count-badge {
        color: #1d4ed8;
    }
    .workload-bar-track {
        width: 100%;
        height: 5px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }
    .workload-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* Column 2: Middle Location Picker Table */
    .col-middle-table {
        padding: 14px 16px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        min-width: 440px;
        box-sizing: border-box;
    }
    .middle-header-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.2px;
    }
    .loc-picker-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .loc-picker-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .loc-picker-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .loc-picker-table tbody tr.row-selected {
        background: #e0edff !important;
    }
    .loc-picker-table tbody tr.row-disabled {
        color: #94a3b8;
    }
    .loc-picker-table tbody tr.row-disabled td {
        color: #94a3b8;
    }

    /* Priority Pills */
    .priority-pill {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 0.68rem;
        font-weight: 700;
        color: #ffffff;
        text-align: center;
        min-width: 36px;
    }
    .priority-pill.high { background: #ef4444; }
    .priority-pill.med { background: #f97316; }
    .priority-pill.low { background: #64748b; }

    .open-count-text {
        font-size: 0.74rem;
        color: #d97706;
    }

    .middle-footer-count {
        margin-top: 8px;
        font-size: 0.74rem;
        color: #475569;
        font-weight: 600;
    }

    /* Column 3: Right Preview & Checks */
    .col-right-preview {
        padding: 14px 14px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 355px;
        box-sizing: border-box;
    }
    .preview-header-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.2px;
    }
    .preview-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.76rem;
        margin-bottom: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .preview-table th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 4px 6px;
        border-bottom: 1px solid #e2e8f0;
    }
    .preview-table td {
        padding: 4px 6px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    /* Checks Before Saving */
    .checks-list {
        font-size: 0.74rem;
        margin-bottom: 12px;
    }
    .check-line {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .check-icon-pass {
        color: #10b981;
        font-weight: 700;
    }
    .check-icon-warn {
        color: #f59e0b;
        font-weight: 700;
    }

    /* Blue explanation box: What happens on assign */
    .blue-info-box {
        background: #eff6ff;
        border-left: 3px solid #2563eb;
        padding: 8px 10px;
        border-radius: 2px;
        font-size: 0.73rem;
        margin-bottom: 10px;
    }
    .blue-info-box-title {
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 4px;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    .blue-info-box ol {
        margin: 0;
        padding-left: 14px;
        color: #1e3a8a;
    }
    .blue-info-box li {
        margin-bottom: 2px;
    }

    /* Priority explanation */
    .priority-info-box {
        font-size: 0.72rem;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.35;
    }

    /* Action Buttons */
    .preview-actions-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        margin-top: 10px;
        white-space: nowrap;
        flex-wrap: nowrap;
    }
    .btn-act-cancel {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-act-cancel:hover {
        background: #475569;
        color: #ffffff;
    }
    .btn-act-draft {
        background: #475569;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-act-draft:hover {
        background: #334155;
    }
    .btn-act-assign {
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-act-assign:hover {
        background: #1d4ed8;
    }

    /* Bottom 4 Explanation Boxes: Fixed 4-Columns Side-by-Side */
    .img1-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #ffffff;
    }
    .img1-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img1-box:last-child {
        border-right: none;
    }
    .img1-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img1-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }

    /* Mobile Responsive Adaptation */
    @media (max-width: 991.98px) {
        .img1-wrapper {
            padding: 8px 8px 85px 8px !important;
            background: #f1f5f9 !important;
            overflow-x: hidden !important;
            min-height: calc(100vh - 60px) !important;
        }
        .img1-container {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .img1-top-bar {
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
            box-shadow: 0 4px 14px rgba(11, 19, 41, 0.25);
        }
        .img1-top-bar .title-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .concept-note {
            display: none !important;
        }
        .img1-main-grid {
            display: flex !important;
            flex-direction: column !important;
            min-height: auto !important;
        }
        .col-form, .col-table, .col-summary {
            width: 100% !important;
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .col-table-scroll {
            max-height: 400px !important;
        }
        .img1-bottom-grid {
            display: flex !important;
            flex-direction: column !important;
        }
        .img1-box {
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0;
        }
    }
</style>

<div class="img1-wrapper">
    <div class="img1-container">
        {{-- Top Dark Bar --}}
        <div class="img1-top-bar">
            <div class="title-section">
                <span class="main-title">1 · Assign Cycle Count</span>
                <span class="badge-pill-role">Owner / In-charge only</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 12px;">
                <div class="concept-note">
                    CONCEPT for approval · locations, dates and staff are illustrative
                </div>
                <a href="{{ route('cycle_count.index') }}" onclick="if(document.referrer && document.referrer.indexOf(window.location.host) !== -1 && document.referrer !== window.location.href) { history.back(); return false; }" class="top-strip-back-btn" title="Back to Schedule">
                    <i class="fas fa-arrow-left"></i> <span>Back</span>
                </a>
            </div>
        </div>

        {{-- Main 3-Column Section --}}
        <form id="assignFormExact" method="POST" action="{{ route('cycle_count.store') }}">
            @csrf
            <input type="hidden" name="action" id="actionHidden" value="assign">

            <div class="img1-main-grid">
                {{-- Column 1: Left Form --}}
                <div class="col-form">
                    <div class="form-step-title">1 · COUNT TYPE (types per D-03 — still open)</div>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="fast_item" checked>
                        <span>Fast-item list — weekly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="salepoint_shelves">
                        <span>Sale-point shelves — quarterly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="godown_racks">
                        <span>Godown racks — half-yearly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="adhoc">
                        <span>Ad hoc — owner's request</span>
                    </label>

                    <div class="form-step-title">2 · DATE</div>
                    <div style="display: flex; gap: 4px; align-items: center;">
                        <input type="date" name="count_date" id="dateInput" class="form-input-clean" value="{{ $todayDate }}" required style="width: 50%;">
                        <input type="text" name="time_window" class="form-input-clean" placeholder="time window: [from] - [to]" style="width: 50%;">
                    </div>

                    <div class="form-step-title">3 · ASSIGN TO</div>
                    <select name="assigned_to" id="assignedToSelect" class="form-input-clean" required>
                        @foreach($staffUsers as $st)
                            <option value="{{ $st->id }}" data-name="{{ $st->name }}">
                                [{{ $st->name }}] ▼
                            </option>
                        @endforeach
                    </select>

                    {{-- Staff workload this week --}}
                    <div class="workload-section">
                        <div class="workload-title">
                            <span><i class="fas fa-chart-bar text-primary me-1"></i> Staff workload this week</span>
                        </div>
                        @foreach($staffUsers as $st)
                            @php
                                $wCount = $staffWorkload[$st->id] ?? 0;
                                $percent = min(100, $wCount * 20);
                                $barColor = $wCount > 5 ? '#ef4444' : ($wCount >= 3 ? '#f59e0b' : ($wCount > 0 ? '#2563eb' : '#94a3b8'));
                            @endphp
                            <div class="workload-card" id="workload_card_{{ $st->id }}" data-staff-id="{{ $st->id }}" title="Click to assign to {{ $st->name }}">
                                <div class="workload-card-header">
                                    <span class="workload-name">
                                        <i class="fas fa-user-circle" style="color: {{ $barColor }}; font-size: 0.8rem;"></i>
                                        {{ $st->name }}
                                    </span>
                                    <span class="workload-count-badge">
                                        {{ $wCount }} {{ $wCount === 1 ? 'count' : 'counts' }} this week
                                    </span>
                                </div>
                                <div class="workload-bar-track">
                                    <div class="workload-bar-fill" style="width: {{ $percent }}%; background: {{ $barColor }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-step-title">4 · APPROVER (NEVER THE COUNTER)</div>
                    <select name="approver_id" id="approverSelect" class="form-input-clean" required>
                        @foreach($staffUsers as $st)
                            <option value="{{ $st->id }}" {{ $st->usertype === 'admin' ? 'selected' : '' }} data-name="{{ $st->name }}">
                                {{ $st->usertype === 'admin' ? 'Owner' : $st->name }} ▼ / In-charge
                            </option>
                        @endforeach
                    </select>

                    <div class="form-step-title">5 · NOTE TO STAFF (optional)</div>
                    <input type="text" name="notes" class="form-input-clean" placeholder='e.g. "check the back row too"'>
                </div>

                {{-- Column 2: Middle Location Picker Table --}}
                <div class="col-middle-table">
                    <div class="middle-header-title d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">
                        <span>PICK LOCATION(S) — SORTED BY PRIORITY (SALES VELOCITY, DAYS SINCE LAST COUNT) · EACH LOCATION BECOMES ITS OWN COUNT</span>
                        <span id="filterBadgeText" class="badge" style="background: #2563eb; color: #fff; font-size: 0.68rem; padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">All Locations</span>
                    </div>

                    {{-- Mode Switch Toolbar: Single vs Multi Locations --}}
                    <div class="loc-mode-toolbar d-flex align-items-center justify-content-between flex-wrap mb-2 p-2 rounded" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                        <div class="d-flex align-items-center gap-3">
                            <span style="font-size: 0.74rem; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">
                                <i class="fas fa-layer-group text-primary me-1"></i> Mode:
                            </span>
                            <label class="radio-option mb-0" style="cursor: pointer; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; color: #334155;">
                                <input type="radio" name="loc_selection_mode" id="modeSingle" value="single" checked>
                                <span>Single Location</span>
                            </label>
                            <label class="radio-option mb-0" style="cursor: pointer; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; color: #1d4ed8;">
                                <input type="radio" name="loc_selection_mode" id="modeMulti" value="multi">
                                <span>Multi Locations (Multi)</span>
                            </label>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span id="multiStatusPill" class="badge" style="background: #64748b; color: #fff; font-size: 0.68rem; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                Multi: OFF (1 location)
                            </span>
                            <div id="multiActionBtns" style="display: none; align-items: center; gap: 4px;">
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnSelectAll" style="font-size: 0.7rem; padding: 2px 8px; border-radius: 3px; font-weight: 600;">
                                    <i class="fas fa-check-double me-1"></i> Select All
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary" id="btnClearAll" style="font-size: 0.7rem; padding: 2px 8px; border-radius: 3px; font-weight: 600;">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="loc-picker-table" id="locPickerTable">
                            <thead>
                                <tr>
                                    <th style="width: 28px; text-align: center;">
                                        <input type="checkbox" id="masterCheckbox" title="Select / Deselect all visible locations" style="display: none; cursor: pointer;">
                                    </th>
                                    <th>LOCATION</th>
                                    <th>ZONE</th>
                                    <th>ITEMS</th>
                                    <th>LAST COUNTED</th>
                                    <th>PRIORITY</th>
                                    <th>OPEN COUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $loc)
                                    @php
                                        $locType = strtolower($loc['type'] ?? '');
                                        $locNameLower = strtolower($loc['name'] ?? '');
                                        $locZoneLower = strtolower($loc['zone'] ?? '');
                                        $isShelf = str_contains($locType, 'shelf') || str_contains($locNameLower, 'shelf') || str_contains($locZoneLower, 'shop') || str_contains($locZoneLower, 'branch');
                                        $isRack = str_contains($locType, 'rack') || str_contains($locNameLower, 'rack') || str_contains($locZoneLower, 'godown') || str_contains($locZoneLower, 'warehouse') || str_contains($locZoneLower, 'east') || str_contains($locZoneLower, 'west');
                                        $isHighVelocity = ($loc['priority'] === 'High') || ($loc['items_count'] > 0);
                                    @endphp
                                    <tr class="loc-row-item {{ $loc['has_open_count'] ? 'row-disabled' : '' }}"
                                        data-id="{{ $loc['id'] }}"
                                        data-name="{{ $loc['name'] }}"
                                        data-items="{{ $loc['items_count'] }}"
                                        data-priority="{{ $loc['priority'] }}"
                                        data-open="{{ $loc['has_open_count'] ? 'yes' : 'no' }}"
                                        data-is-shelf="{{ $isShelf ? '1' : '0' }}"
                                        data-is-rack="{{ $isRack ? '1' : '0' }}"
                                        data-is-fast="{{ $isHighVelocity ? '1' : '0' }}"
                                        data-loc-type="{{ $locType }}">
                                        <td style="text-align: center;">
                                            <input type="radio" name="location_id" value="{{ $loc['id'] }}"
                                                   class="loc-select-input"
                                                   id="loc_r_{{ $loc['id'] }}"
                                                   {{ $loc['has_open_count'] ? 'disabled' : '' }}
                                                   {{ ((request('location_id') && request('location_id') == $loc['id']) || (!request('location_id') && $loop->first)) && !$loc['has_open_count'] ? 'checked' : '' }}>
                                        </td>
                                        <td style="font-weight: 700;">
                                            <label for="loc_r_{{ $loc['id'] }}" style="margin:0; cursor:pointer;">
                                                {{ $loc['name'] }}
                                            </label>
                                        </td>
                                        <td>
                                            {{ $loc['zone'] ?: 'Godown rack' }}
                                        </td>
                                        <td>
                                            {{ $loc['items_count'] }}
                                        </td>
                                        <td>
                                            {{ $loc['last_counted_text'] }}
                                        </td>
                                        <td>
                                            @if($loc['priority'] === 'High')
                                                 <span class="priority-pill high">High</span>
                                            @elseif($loc['priority'] === 'Med')
                                                <span class="priority-pill med">Med</span>
                                            @else
                                                <span class="priority-pill low">Low</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loc['has_open_count'])
                                                <span class="open-count-text">{{ $loc['open_count_number'] ?: 'C-0412' }} {{ strtolower($loc['open_count_status']) }}</span>
                                            @else
                                                <span style="color: #94a3b8;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr id="noMatchRow" style="display: none;">
                                    <td colspan="7" class="text-center py-4 text-muted" style="font-size: 0.8rem;">
                                        <i class="fas fa-info-circle text-primary me-1"></i> No locations match this count type.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="middle-footer-count" id="selectedSummaryLine">
                        1 location selected · <span id="totalItemsSelected">54</span> items in total
                    </div>
                </div>

                {{-- Column 3: Right Preview & Checks --}}
                <div class="col-right-preview">
                    <div>
                        <div class="preview-header-title" id="previewHeaderTitle">PREVIEW — COUNTS THAT WILL BE CREATED (1)</div>
                        <div style="max-height: 220px; overflow-y: auto; margin-bottom: 12px; border: 1px solid #e2e8f0; border-radius: 4px; background: #ffffff;">
                            <table class="preview-table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>COUNT #</th>
                                        <th>LOCATION</th>
                                        <th>ITEMS</th>
                                        <th>DATE</th>
                                        <th>STAFF</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTbody">
                                    <tr>
                                        <td>{{ $nextCountNumber }}</td>
                                        <td id="prevLocCell" style="font-weight: 700;">SA3</td>
                                        <td id="prevItemsCell">54</td>
                                        <td id="prevDateCell">[date]</td>
                                        <td id="prevStaffCell">[Staff A]</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="preview-header-title">CHECKS BEFORE SAVING</div>
                        <div class="checks-list">
                            <div class="check-line" id="checkTargetItems">
                                <span class="check-icon-pass">✓</span>
                                <span>Items per sheet <span id="checkItemsText">54</span> — inside the 50–70 target</span>
                            </div>
                            <div class="check-line">
                                <span class="check-icon-pass">✓</span>
                                <span>No open count on selected location</span>
                            </div>
                            <div class="check-line" id="checkApproverFourEyes">
                                <span class="check-icon-pass">✓</span>
                                <span>Approver is not the assigned counter</span>
                            </div>
                            <div class="check-line">
                                <span class="check-icon-pass">✓</span>
                                <span>One location per count, one count per day</span>
                            </div>
                            <div class="check-line" style="color: #475569;">
                                <span class="check-icon-warn">!</span>
                                <span id="warnStaffLimit">[Staff A] has counts scheduled this week — within limit</span>
                            </div>
                            <div class="check-line" style="color: #94a3b8;">
                                <span>·</span>
                                <span>Estimated time per count: [measure the first counts]</span>
                            </div>
                        </div>

                        {{-- What happens on assign --}}
                        <div class="blue-info-box">
                            <div class="blue-info-box-title">WHAT HAPPENS ON "ASSIGN"</div>
                            <ol>
                                <li>Both counts appear on screen 2 (Schedule) as "Not started".</li>
                                <li>They appear on [<span id="boxStaffName">Staff A</span>]'s own "My counts" list only.</li>
                                <li>The item list for each location loads by itself — nobody types items.</li>
                                <li>Reassign or cancel is possible until the staff member opens the count.</li>
                            </ol>
                        </div>

                        {{-- Priority Column Note --}}
                        <div class="priority-info-box">
                            <strong style="color: #334155;">PRIORITY COLUMN:</strong>
                            <span class="priority-pill high" style="padding:0 4px;">High</span>
                            <span class="priority-pill med" style="padding:0 4px;">Med</span>
                            <span class="priority-pill low" style="padding:0 4px;">Low</span>
                            system suggestion from sales velocity and days since last count. The exact formula and the days limits are [BLANK] — the owner decides (D-03). Greyed rows already have a count on the Schedule and cannot be picked twice.
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="preview-actions-bar">
                        <a href="{{ route('cycle_count.index') }}" class="btn-act-cancel">Cancel</a>
                        <button type="button" class="btn-act-draft" id="btnSaveDraft">Save as draft</button>
                        <button type="button" class="btn-act-assign" id="btnAssignNotify">Assign & notify staff</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Bottom 4 Explanatory Boxes (Exact copy of Reference Image 1) --}}
        <div class="img1-bottom-grid">
            <div class="img1-box">
                <h6>Who</h6>
                <p>Only Owner / In-charge sees this screen. Staff cannot assign or see other staff's counts.</p>
            </div>
            <div class="img1-box">
                <h6>One location per count</h6>
                <p>Picking several locations creates several counts, one per day (24 Sep rule: one location at a time).</p>
            </div>
            <div class="img1-box">
                <h6>Auto item list</h6>
                <p>The sheet's items come from the location master — about 50–70 per sheet. No manual item entry.</p>
            </div>
            <div class="img1-box">
                <h6>Link to schedule</h6>
                <p>Every saved assignment is a row on the Schedule with status, progress and approver.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('locPickerTable');
    const rows = table.querySelectorAll('tbody tr.loc-row-item');
    const assignedSelect = document.getElementById('assignedToSelect');
    const approverSelect = document.getElementById('approverSelect');
    const dateInput = document.getElementById('dateInput');

    const modeSingle = document.getElementById('modeSingle');
    const modeMulti = document.getElementById('modeMulti');
    const multiStatusPill = document.getElementById('multiStatusPill');
    const multiActionBtns = document.getElementById('multiActionBtns');
    const masterCheckbox = document.getElementById('masterCheckbox');
    const baseCountNumberStr = '{{ $nextCountNumber }}';

    function formatCountNumber(index) {
        const match = baseCountNumberStr.match(/^([A-Za-z]+-?)(\d+)$/);
        if (match) {
            const prefix = match[1];
            const num = parseInt(match[2], 10) + index;
            const padLen = match[2].length;
            return prefix + String(num).padStart(padLen, '0');
        }
        return baseCountNumberStr;
    }

    function setSelectionMode(isMulti) {
        if (isMulti) {
            multiStatusPill.textContent = 'Multi: ON (Multi-location assign)';
            multiStatusPill.style.background = '#2563eb';
            multiActionBtns.style.display = 'inline-flex';
            masterCheckbox.style.display = 'inline-block';

            // Convert inputs to checkboxes with name="location_ids[]"
            document.querySelectorAll('.loc-select-input').forEach(inp => {
                inp.type = 'checkbox';
                inp.name = 'location_ids[]';
            });
        } else {
            multiStatusPill.textContent = 'Multi: OFF (1 location)';
            multiStatusPill.style.background = '#64748b';
            multiActionBtns.style.display = 'none';
            masterCheckbox.style.display = 'none';

            // Convert inputs to radio with name="location_id"
            const allInputs = Array.from(document.querySelectorAll('.loc-select-input'));
            const firstChecked = allInputs.find(i => i.checked);

            allInputs.forEach(inp => {
                inp.type = 'radio';
                inp.name = 'location_id';
                inp.checked = false;
            });

            // Keep only 1 checked
            if (firstChecked) {
                firstChecked.checked = true;
            } else {
                const firstVisible = allInputs.find(i => !i.disabled && i.closest('tr').style.display !== 'none');
                if (firstVisible) firstVisible.checked = true;
            }
        }
        syncState();
    }

    modeSingle.addEventListener('change', function () {
        if (this.checked) setSelectionMode(false);
    });

    modeMulti.addEventListener('change', function () {
        if (this.checked) setSelectionMode(true);
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function syncState() {
        const isMulti = modeMulti.checked;
        const checkedInputs = Array.from(document.querySelectorAll('.loc-select-input:checked'));

        rows.forEach(tr => tr.classList.remove('row-selected'));

        let totalItems = 0;
        let selectedLocs = [];

        checkedInputs.forEach(inp => {
            const tr = inp.closest('tr');
            if (tr) {
                tr.classList.add('row-selected');
                const name = tr.getAttribute('data-name');
                const items = parseInt(tr.getAttribute('data-items')) || 0;
                totalItems += items;
                selectedLocs.push({
                    id: inp.value,
                    name: name,
                    items: items
                });
            }
        });

        // Staff name
        const staffOpt = assignedSelect.options[assignedSelect.selectedIndex];
        const staffName = staffOpt ? staffOpt.getAttribute('data-name') : 'Staff A';

        // Date
        const dateVal = dateInput.value || '[date]';

        // Update Preview Header & Table
        const countTotal = selectedLocs.length;
        const previewHeader = document.getElementById('previewHeaderTitle');
        if (previewHeader) {
            previewHeader.textContent = `PREVIEW — COUNTS THAT WILL BE CREATED (${countTotal})`;
        }

        const tbody = document.getElementById('previewTbody');
        if (tbody) {
            tbody.innerHTML = '';
            if (countTotal === 0) {
                const emptyTr = document.createElement('tr');
                emptyTr.innerHTML = `<td colspan="5" style="text-align:center; color:#94a3b8; padding:10px;">No location selected</td>`;
                tbody.appendChild(emptyTr);
            } else {
                selectedLocs.forEach((loc, idx) => {
                    const tr = document.createElement('tr');
                    const cNum = formatCountNumber(idx);
                    tr.innerHTML = `
                        <td style="font-weight: 600; color: #2563eb;">${cNum}</td>
                        <td style="font-weight: 700;">${escapeHtml(loc.name)}</td>
                        <td>${loc.items}</td>
                        <td>${escapeHtml(dateVal)}</td>
                        <td>[${escapeHtml(staffName)}]</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }

        // Selected summary line
        const summaryLine = document.getElementById('selectedSummaryLine');
        if (summaryLine) {
            summaryLine.innerHTML = `<strong>${countTotal}</strong> location${countTotal === 1 ? '' : 's'} selected · <span id="totalItemsSelected">${totalItems}</span> items in total`;
        }

        // Checks dynamic updates
        const checkTargetItems = document.getElementById('checkTargetItems');
        if (checkTargetItems) {
            const icon = checkTargetItems.querySelector('span:first-child');
            const text = checkTargetItems.querySelector('span:last-child');
            if (countTotal === 0) {
                icon.className = 'check-icon-warn';
                icon.textContent = '!';
                text.textContent = 'Please select at least one location';
            } else if (countTotal === 1) {
                if (totalItems >= 50 && totalItems <= 70) {
                    icon.className = 'check-icon-pass';
                    icon.textContent = '✓';
                    text.textContent = `Items per sheet ${totalItems} — inside the 50–70 target`;
                } else if (totalItems === 0) {
                    icon.className = 'check-icon-warn';
                    icon.textContent = '!';
                    text.textContent = 'Selected location has 0 items mapped currently';
                } else if (totalItems < 50) {
                    icon.className = 'check-icon-pass';
                    icon.textContent = '✓';
                    text.textContent = `Items per sheet ${totalItems} — below 50–70 target (quick count)`;
                } else {
                    icon.className = 'check-icon-warn';
                    icon.textContent = '!';
                    text.textContent = `Items per sheet ${totalItems} — above 50–70 target`;
                }
            } else {
                const avgItems = Math.round(totalItems / countTotal);
                icon.className = 'check-icon-pass';
                icon.textContent = '✓';
                text.textContent = `Total ${totalItems} items across ${countTotal} sheets (avg ${avgItems}/sheet)`;
            }
        }

        // Four eyes check
        const approverOpt = approverSelect.options[approverSelect.selectedIndex];
        const checkApprover = document.getElementById('checkApproverFourEyes');
        if (checkApprover) {
            if (staffOpt && approverOpt && staffOpt.value === approverOpt.value) {
                checkApprover.querySelector('span:first-child').className = 'check-icon-warn';
                checkApprover.querySelector('span:first-child').textContent = '!';
                checkApprover.querySelector('span:last-child').textContent = 'Warning: Approver cannot be the same as counter';
            } else {
                checkApprover.querySelector('span:first-child').className = 'check-icon-pass';
                checkApprover.querySelector('span:first-child').textContent = '✓';
                checkApprover.querySelector('span:last-child').textContent = 'Approver is not the assigned counter';
            }
        }

        // Workload warning
        const warnStaffLimit = document.getElementById('warnStaffLimit');
        if (warnStaffLimit) {
            if (countTotal > 1) {
                warnStaffLimit.textContent = `[${staffName}] will receive ${countTotal} count assignments today`;
            } else {
                warnStaffLimit.textContent = `[${staffName}] has counts scheduled this week — within limit`;
            }
        }

        // Box staff name
        const boxStaffName = document.getElementById('boxStaffName');
        if (boxStaffName) boxStaffName.textContent = staffName;

        // Highlight selected staff in workload cards
        document.querySelectorAll('.workload-card').forEach(card => card.classList.remove('is-active-staff'));
        const activeCard = document.getElementById('workload_card_' + assignedSelect.value);
        if (activeCard) activeCard.classList.add('is-active-staff');

        // Master checkbox sync in multi mode
        if (masterCheckbox && isMulti) {
            const visibleInputs = Array.from(document.querySelectorAll('.loc-select-input')).filter(i => !i.disabled && i.closest('tr').style.display !== 'none');
            masterCheckbox.checked = visibleInputs.length > 0 && visibleInputs.every(i => i.checked);
        }
    }

    function filterLocationsByCountType() {
        const typeRadio = document.querySelector('input[name="count_type"]:checked');
        const selectedType = typeRadio ? typeRadio.value : 'fast_item';
        let visibleCount = 0;
        let isCurrentCheckedVisible = false;
        let firstValidVisibleInput = null;

        rows.forEach(tr => {
            const isShelf = tr.getAttribute('data-is-shelf') === '1';
            const isRack = tr.getAttribute('data-is-rack') === '1';
            const priority = tr.getAttribute('data-priority');
            const items = parseInt(tr.getAttribute('data-items')) || 0;
            const isDisabled = tr.classList.contains('row-disabled');

            let matches = true;
            if (selectedType === 'salepoint_shelves') {
                matches = isShelf;
            } else if (selectedType === 'godown_racks') {
                matches = isRack;
            } else if (selectedType === 'fast_item') {
                matches = (priority === 'High') || (items > 0);
            } else if (selectedType === 'adhoc') {
                matches = true;
            }

            if (matches) {
                tr.style.display = '';
                visibleCount++;
                const inp = tr.querySelector('.loc-select-input');
                if (inp && inp.checked) {
                    isCurrentCheckedVisible = true;
                }
                if (!firstValidVisibleInput && inp && !isDisabled) {
                    firstValidVisibleInput = inp;
                }
            } else {
                tr.style.display = 'none';
            }
        });

        // If Single mode and currently checked location is hidden by this filter, automatically check the first valid visible location
        if (!modeMulti.checked && !isCurrentCheckedVisible && firstValidVisibleInput) {
            firstValidVisibleInput.checked = true;
        }

        const noMatchRow = document.getElementById('noMatchRow');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0) ? '' : 'none';
        }

        // Update filter badge text in middle table header
        const filterBadge = document.getElementById('filterBadgeText');
        if (filterBadge) {
            if (selectedType === 'salepoint_shelves') {
                filterBadge.textContent = 'Sale-Point Shelves (' + visibleCount + ')';
                filterBadge.style.background = '#0284c7';
            } else if (selectedType === 'godown_racks') {
                filterBadge.textContent = 'Godown Racks (' + visibleCount + ')';
                filterBadge.style.background = '#475569';
            } else if (selectedType === 'fast_item') {
                filterBadge.textContent = 'Fast-Item Weekly (' + visibleCount + ')';
                filterBadge.style.background = '#dc2626';
            } else {
                filterBadge.textContent = 'All Locations (' + visibleCount + ')';
                filterBadge.style.background = '#2563eb';
            }
        }

        syncState();
    }

    // Attach click events on rows
    rows.forEach(tr => {
        if (!tr.classList.contains('row-disabled')) {
            tr.addEventListener('click', function (e) {
                if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                    const inp = tr.querySelector('.loc-select-input');
                    if (inp && !inp.disabled) {
                        if (inp.type === 'checkbox') {
                            inp.checked = !inp.checked;
                        } else {
                            inp.checked = true;
                        }
                    }
                }
                syncState();
            });
        }
    });

    // Inputs direct change listener
    document.querySelectorAll('.loc-select-input').forEach(inp => {
        inp.addEventListener('change', syncState);
    });

    // Master Checkbox
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            rows.forEach(tr => {
                if (tr.style.display !== 'none' && !tr.classList.contains('row-disabled')) {
                    const inp = tr.querySelector('.loc-select-input');
                    if (inp && !inp.disabled) inp.checked = isChecked;
                }
            });
            syncState();
        });
    }

    // Select All Visible Button
    const btnSelectAll = document.getElementById('btnSelectAll');
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function () {
            rows.forEach(tr => {
                if (tr.style.display !== 'none' && !tr.classList.contains('row-disabled')) {
                    const inp = tr.querySelector('.loc-select-input');
                    if (inp && !inp.disabled) inp.checked = true;
                }
            });
            if (masterCheckbox) masterCheckbox.checked = true;
            syncState();
        });
    }

    // Clear All Button
    const btnClearAll = document.getElementById('btnClearAll');
    if (btnClearAll) {
        btnClearAll.addEventListener('click', function () {
            document.querySelectorAll('.loc-select-input').forEach(inp => {
                inp.checked = false;
            });
            if (masterCheckbox) masterCheckbox.checked = false;
            syncState();
        });
    }

    // Reactive filter on Count Type radio buttons
    document.querySelectorAll('input[name="count_type"]').forEach(r => {
        r.addEventListener('change', filterLocationsByCountType);
    });

    assignedSelect.addEventListener('change', syncState);
    approverSelect.addEventListener('change', syncState);
    dateInput.addEventListener('change', syncState);

    // Click on workload card to quickly select staff in dropdown
    document.querySelectorAll('.workload-card').forEach(card => {
        card.addEventListener('click', function () {
            const staffId = this.getAttribute('data-staff-id');
            if (staffId && assignedSelect) {
                assignedSelect.value = staffId;
                syncState();
            }
        });
    });

    document.getElementById('btnAssignNotify').addEventListener('click', function () {
        if (!validate()) return;
        document.getElementById('actionHidden').value = 'assign';
        document.getElementById('assignFormExact').submit();
    });

    document.getElementById('btnSaveDraft').addEventListener('click', function () {
        if (!validate()) return;
        document.getElementById('actionHidden').value = 'draft';
        document.getElementById('assignFormExact').submit();
    });

    function validate() {
        const checked = document.querySelectorAll('.loc-select-input:checked');
        if (checked.length === 0) {
            alert('Please select at least one location to count.');
            return false;
        }
        if (assignedSelect.value === approverSelect.value) {
            alert('Four-eyes violation: The approver cannot be the assigned counter.');
            return false;
        }
        return true;
    }

    // Initialize on page load
    filterLocationsByCountType();
});
</script>
@endsection
