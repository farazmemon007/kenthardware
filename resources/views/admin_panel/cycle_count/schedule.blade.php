@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 2 */
    .img2-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: calc(100vh - 56px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: auto;
    }
    .img2-container {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        min-width: 1060px;
        max-width: 100%;
        margin: 0 auto;
    }

    /* Top Dark Bar */
    .img2-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img2-top-bar .title-section {
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
    .img2-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
        letter-spacing: -0.2px;
    }
    .img2-top-bar .badge-pill-role {
        background: #2563eb;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img2-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* Sub-header Filter Toolbar */
    .img2-toolbar {
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .img2-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-assign-count {
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-assign-count:hover {
        background: #1d4ed8;
        color: #ffffff;
    }
    .filter-inline-select {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8rem;
        color: #475569;
    }
    .filter-select-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 0.8rem;
        color: #0f172a;
        cursor: pointer;
    }
    .toolbar-right-note {
        font-size: 0.76rem;
        color: #64748b;
    }

    /* Schedule Data Table */
    .img2-table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .img2-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.82rem;
    }
    .img2-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 8px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .img2-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }
    .img2-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Columns specific */
    .col-count-num {
        font-family: inherit;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
    }
    .col-loc-name {
        font-weight: 700;
        color: #0f172a;
    }
    .col-items-qty {
        font-weight: 500;
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-pill.started {
        background: #10b981;
        color: #ffffff;
    }
    .status-pill.not-started {
        background: #64748b;
        color: #ffffff;
    }
    .status-pill.submitted {
        background: #2563eb;
        color: #ffffff;
    }
    .status-pill.approved {
        background: #0f172a;
        color: #ffffff;
    }
    .status-waiting-note {
        font-size: 0.74rem;
        color: #2563eb;
        margin-left: 6px;
        font-weight: 500;
    }

    /* Progress Live Bar */
    .img2-progress-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 140px;
        max-width: 170px;
    }
    .img2-progress-track {
        flex: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }
    .img2-progress-fill {
        height: 100%;
        background: #10b981;
        border-radius: 3px;
    }
    .img2-progress-fraction {
        font-size: 0.72rem;
        color: #64748b;
        white-space: nowrap;
        min-width: 36px;
        text-align: right;
    }

    /* Action Links */
    .action-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .action-link:hover {
        text-decoration: underline;
    }
    .action-sep {
        color: #cbd5e1;
        margin: 0 4px;
    }

    /* Table Footer Summary */
    .img2-table-footer {
        padding: 8px 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.76rem;
        color: #475569;
        flex-wrap: wrap;
        gap: 8px;
    }
    .img2-table-footer-left {
        font-weight: 600;
    }
    .img2-table-footer-right {
        color: #94a3b8;
    }

    /* Bottom 4 Explanation Boxes: Fixed 4 Columns */
    .img2-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }
    .img2-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img2-box:last-child {
        border-right: none;
    }
    .img2-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img2-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }

    /* ========================================================
       MOBILE ANDROID & FLUTTER-GRADE APP STYLES
       ======================================================== */
    @media (max-width: 991.98px) {
        .img2-wrapper {
            padding: 12px 12px 90px 12px !important;
            background: #f8fafc !important;
            overflow-x: hidden !important;
            min-height: calc(100vh - 60px) !important;
            width: 100% !important;
        }
        .img2-container {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Completely hide desktop bars and desktop table on mobile */
        .img2-top-bar,
        .img2-toolbar,
        .img2-table-wrap,
        .img2-table-footer,
        .img2-bottom-grid {
            display: none !important;
        }

        /* ── Flutter Filter & Week Selector Card ── */
        .m-filter-bar-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 12px 14px;
            margin-bottom: 14px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }
        .m-select-pill-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 0 12px;
            height: 46px;
            transition: border-color 0.15s ease;
        }
        .m-select-pill-wrap:focus-within {
            border-color: #2563eb;
            background: #ffffff;
        }
        .m-select-icon {
            font-size: 14px;
            margin-right: 8px;
            color: #475569;
        }
        .m-chip-select {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            cursor: pointer;
        }
        .m-filter-reset-btn {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            text-decoration: none !important;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }
        .m-filter-reset-btn:active {
            background: #e2e8f0;
            transform: scale(0.95);
        }

        /* ── Metric KPI Badges Grid (2x2 Flutter Style) ── */
        .m-kpi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }
        .m-kpi-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2e8f0;
            padding: 15px 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .m-kpi-card:active {
            transform: scale(0.97);
            background: #f8fafc;
        }
        .m-kpi-label {
            font-size: 12.5px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .m-kpi-icon-wrap {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .bg-blue-subtle { background: #eff6ff; color: #1d4ed8; }
        .bg-green-subtle { background: #f0fdf4; color: #15803d; }
        .bg-cyan-subtle { background: #fef3c7; color: #b45309; }
        .bg-emerald-subtle { background: #ecfdf5; color: #047857; }
        .m-kpi-value {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
            margin-top: 8px;
        }

        /* ── Flutter Segmented Chips Bar ── */
        .m-chips-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
            margin-bottom: 14px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .m-chips-filter::-webkit-scrollbar { display: none; }
        .m-chip {
            flex-shrink: 0;
            height: 42px;
            padding: 8px 18px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 22px;
            font-size: 13.5px;
            font-weight: 800;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .m-chip.active {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
        }

        /* ── Search Bar (Flutter TextField Style) ── */
        .m-search-pill-box {
            position: relative;
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 0 16px;
            height: 48px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease;
        }
        .m-search-pill-box:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .m-search-icon {
            color: #64748b;
            font-size: 16px;
            margin-right: 10px;
        }
        .m-search-pill-input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14.5px;
            font-weight: 600;
            color: #0f172a;
        }
        .m-search-clear {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
        }

        /* ── High-Contrast Flutter Material 3 Task Cards ── */
        .m-cards-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .android-count-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            padding: 18px 16px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.08), 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.12s ease;
        }
        .android-count-card:active {
            transform: scale(0.99);
        }
        .acc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .acc-loc-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #1e40af;
            border: 1.5px solid #93c5fd;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: -0.2px;
        }
        .acc-code-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: #475569;
            font-weight: 800;
            background: #f8fafc;
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .acc-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }
        .acc-status-started { background: #dcfce7; color: #14532d; border: 1.5px solid #86efac; }
        .acc-status-pending { background: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1; }
        .acc-status-submitted { background: #fef3c7; color: #78350f; border: 1.5px solid #fde68a; }
        .acc-status-approved { background: #ecfdf5; color: #064e3b; border: 1.5px solid #a7f3d0; }

        .acc-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #16a34a;
            animation: pulseGlow 1.5s infinite;
        }

        .acc-meta-strip {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            background: #f8fafc;
            border: 1.5px solid #edf2f7;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 14px;
        }
        .acc-meta-col {
            display: flex;
            flex-direction: column;
        }
        .acc-meta-k {
            font-size: 11px;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .acc-meta-v {
            font-size: 14.5px;
            font-weight: 800;
            color: #0f172a;
        }
        .acc-meta-v.text-primary {
            color: #2563eb !important;
            font-weight: 900;
            font-size: 15px;
        }

        .acc-progress-box {
            margin-bottom: 16px;
        }
        .acc-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #475569;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .acc-progress-header strong {
            color: #0f172a;
            font-size: 14px;
            font-weight: 900;
        }
        .acc-progress-track {
            height: 10px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .acc-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 6px;
            transition: width 0.3s ease;
        }

        /* ── Flutter Elevated Full-Width Buttons (52px Touch Target) ── */
        .acc-btn {
            width: 100%;
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.3px;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .acc-btn-start {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }
        .acc-btn-start:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.5);
        }
        .acc-btn-resume {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .acc-btn-resume:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.5);
        }
        .acc-btn-waiting {
            background: #fef3c7;
            color: #78350f !important;
            border: 1.5px solid #fde68a;
            font-size: 14.5px;
            cursor: default;
        }
        .acc-btn-approved {
            background: #ecfdf5;
            color: #064e3b !important;
            border: 1.5px solid #a7f3d0;
            font-size: 14.5px;
            cursor: default;
        }

        /* ── Android Empty State Card ── */
        .acc-empty-card {
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 20px;
            padding: 38px 20px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
        .acc-empty-icon-badge {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #eff6ff;
            color: #2563eb;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
        }
        .acc-empty-card h5 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        /* ── Collapsible Guide Card ── */
        .m-guide-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            padding: 16px 18px;
            margin-top: 16px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }
        .m-guide-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }
        .m-guide-body {
            margin-top: 12px;
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            border-top: 1px dashed #e2e8f0;
            padding-top: 12px;
        }
    }

    @media (min-width: 992px) {
        .mobile-schedule-app-view {
            display: none !important;
        }
    }
</style>

<div class="img2-wrapper">
    <div class="img2-container">
        {{-- Top Dark Bar --}}
        <div class="img2-top-bar">
            <div class="title-section">
                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                    <span class="main-title">My Assigned Cycle Counts</span>
                    <span class="badge-pill-role" style="background: #2563eb; color: #ffffff;">Staff Portal · Physical Counting</span>
                @else
                    <span class="main-title">2 · Cycle Count — Schedule</span>
                    <span class="badge-pill-role">Owner / In-charge only</span>
                @endif
            </div>
            <div class="d-flex align-items-center" style="gap: 12px;">
                <div class="concept-note">
                    @if(Auth::check() && Auth::user()->usertype !== 'admin')
                        Physical count tasks assigned to you. Click <strong>Start Count</strong> to open the blind count sheet.
                    @else
                        CONCEPT for approval · item names, dates and staff are illustrative
                    @endif
                </div>
                <a href="{{ route('home') }}" onclick="if(document.referrer && document.referrer.indexOf(window.location.host) !== -1 && document.referrer !== window.location.href) { history.back(); return false; }" class="top-strip-back-btn" title="Back to Previous Page">
                    <i class="fas fa-arrow-left"></i> <span>Back</span>
                </a>
            </div>
        </div>

        {{-- Sub-header Toolbar --}}
        <div class="img2-toolbar">
            <div class="img2-toolbar-left">
                @if(Auth::check() && Auth::user()->usertype === 'admin')
                    <a href="{{ route('cycle_count.create') }}" class="btn-assign-count">
                        + Assign cycle count
                    </a>

                    <a href="{{ route('product') }}?open_pi=1" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border-radius: 4px; text-decoration: none; border-color: #cbd5e1; color: #334155; background: #ffffff;">
                        <i class="fas fa-clipboard-check text-primary"></i> Open Audit Sheet (PI-101)
                    </a>
                @endif

                <form method="GET" action="{{ route('cycle_count.index') }}" id="filterFormSchedule" style="display: inline-flex; align-items: center; gap: 10px; margin: 0;">
                    <div class="filter-inline-select">
                        <span>Week</span>
                        <select name="week" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                            <option value="all" {{ request('week', 'all') == 'all' ? 'selected' : '' }}>[all weeks] ▼</option>
                            <option value="this_week" {{ request('week') == 'this_week' ? 'selected' : '' }}>[this week] ▼</option>
                            <option value="last_week" {{ request('week') == 'last_week' ? 'selected' : '' }}>[last week] ▼</option>
                            <option value="next_week" {{ request('week') == 'next_week' ? 'selected' : '' }}>[next week] ▼</option>
                        </select>
                    </div>

                    @if(Auth::check() && Auth::user()->usertype === 'admin')
                        <div class="filter-inline-select">
                            <span>Staff</span>
                            <select name="staff_id" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                                <option value="all" {{ request('staff_id') == 'all' ? 'selected' : '' }}>All ▼</option>
                                @foreach($allStaff as $st)
                                    <option value="{{ $st->id }}" {{ request('staff_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }} ▼
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="filter-inline-select">
                        <span>Status</span>
                        <select name="status" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All ▼</option>
                            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not started</option>
                            <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Started</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="toolbar-right-note">
                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                    Open your assigned rack/shelf, click <strong>Start Count</strong>, count items blindly, and submit.
                @else
                    Counts are created on screen 1 <strong>Assign</strong> and appear here at once as "Not started".
                @endif
            </div>
        </div>

        {{-- ========================================================
             MOBILE ANDROID & FLUTTER APP VIEW
             ======================================================== --}}
        <div class="mobile-schedule-app-view">
            {{-- 1. Flutter Filter & Week Selector Pill Bar --}}
            <form method="GET" action="{{ route('cycle_count.index') }}" id="mobileFilterFormSchedule" class="m-0">
                <div class="m-filter-bar-card">
                    <div class="d-flex align-items-center gap-2">
                        <div class="m-select-pill-wrap flex-grow-1">
                            <i class="far fa-calendar-alt m-select-icon"></i>
                            <select name="week" class="m-chip-select" onchange="document.getElementById('mobileFilterFormSchedule').submit()">
                                <option value="all" {{ request('week', 'all') == 'all' ? 'selected' : '' }}>All Weeks</option>
                                <option value="this_week" {{ request('week') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="last_week" {{ request('week') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                <option value="next_week" {{ request('week') == 'next_week' ? 'selected' : '' }}>Next Week</option>
                            </select>
                        </div>

                        <div class="m-select-pill-wrap flex-grow-1">
                            <i class="fas fa-filter m-select-icon"></i>
                            <select name="status" class="m-chip-select" onchange="document.getElementById('mobileFilterFormSchedule').submit()">
                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not started</option>
                                <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Started</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>

                        <a href="{{ route('cycle_count.index') }}?week=all" class="m-filter-reset-btn" title="Reset to All Weeks">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </div>
            </form>

            {{-- 3. Metric KPI Badges Grid (2x2) --}}
            <div class="m-kpi-grid">
                <div class="m-kpi-card" onclick="filterMobileStatus('all')">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="m-kpi-label">Total Assigned</span>
                        <div class="m-kpi-icon-wrap bg-blue-subtle"><i class="fas fa-clipboard-list"></i></div>
                    </div>
                    <div class="m-kpi-value text-primary">{{ $totalCountsCount }}</div>
                </div>

                <div class="m-kpi-card" onclick="filterMobileStatus('started')">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="m-kpi-label">In Progress</span>
                        <div class="m-kpi-icon-wrap bg-green-subtle"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="m-kpi-value text-success">{{ $inProgressCount }}</div>
                </div>

                <div class="m-kpi-card" onclick="filterMobileStatus('submitted')">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="m-kpi-label">Waiting Review</span>
                        <div class="m-kpi-icon-wrap bg-cyan-subtle"><i class="fas fa-clock"></i></div>
                    </div>
                    <div class="m-kpi-value text-info">{{ $pendingApproval }}</div>
                </div>

                <div class="m-kpi-card" onclick="filterMobileStatus('approved')">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="m-kpi-label">Approved</span>
                        <div class="m-kpi-icon-wrap bg-emerald-subtle"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div class="m-kpi-value text-dark">{{ $completedThisWeek }}</div>
                </div>
            </div>

            {{-- 4. Odoo Segmented Chips Filter --}}
            <div class="m-chips-filter">
                <button type="button" class="m-chip active" data-filter="all" onclick="filterMobileStatus('all', this)">
                    <i class="fas fa-layer-group me-1"></i> All ({{ $counts->count() }})
                </button>
                <button type="button" class="m-chip" data-filter="not_started" onclick="filterMobileStatus('not_started', this)">
                    <i class="far fa-circle me-1"></i> Not Started
                </button>
                <button type="button" class="m-chip" data-filter="started" onclick="filterMobileStatus('started', this)">
                    <i class="fas fa-play-circle text-success me-1"></i> In Progress
                </button>
                <button type="button" class="m-chip" data-filter="submitted" onclick="filterMobileStatus('submitted', this)">
                    <i class="fas fa-clock text-info me-1"></i> Waiting Review
                </button>
                <button type="button" class="m-chip" data-filter="approved" onclick="filterMobileStatus('approved', this)">
                    <i class="fas fa-check-circle text-dark me-1"></i> Approved
                </button>
            </div>

            {{-- 5. Mobile Search Pill Box --}}
            <div class="m-search-pill-box">
                <i class="fas fa-search m-search-icon"></i>
                <input type="text" id="mobileSearchInput" placeholder="Search count #, rack location, staff..." class="m-search-pill-input" oninput="searchMobileCounts(this.value)">
                <button type="button" class="m-search-clear" id="mobileSearchClearBtn" onclick="clearMobileSearch()" style="display:none;">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            {{-- 4. Tactile Flutter Task Cards List --}}
            <div class="m-cards-list" id="mobileCountsList">
                @forelse($counts as $count)
                    @php
                        $locRaw = $count->location ? $count->location->name : ($count->location_name ?: 'A1');
                        $locDisplay = str_starts_with(strtolower($locRaw), 'rack') ? $locRaw : 'Rack ' . $locRaw;
                        $staffName = $count->counter ? $count->counter->name : 'Staff';
                        $percent = $count->progress_percent ?? 0;
                    @endphp
                    <div class="android-count-card mobile-count-card"
                         data-status="{{ $count->status }}"
                         data-search="{{ strtolower($count->count_number . ' ' . $locRaw . ' ' . $staffName) }}"
                         id="mobileCard{{ $count->id }}">
                        
                        {{-- Card Top: Location Pill & Status Badge --}}
                        <div class="acc-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="acc-loc-pill">
                                    <i class="fas fa-map-marker-alt text-primary"></i> {{ $locDisplay }}
                                </span>
                                <span class="acc-code-chip">#{{ $count->count_number }}</span>
                            </div>

                            @if($count->status === 'started')
                                <span class="acc-status-pill acc-status-started">
                                    <span class="acc-pulse-dot"></span> In Progress
                                </span>
                            @elseif($count->status === 'submitted')
                                <span class="acc-status-pill acc-status-submitted">
                                    <i class="fas fa-clock"></i> Waiting Review
                                </span>
                            @elseif($count->status === 'approved')
                                <span class="acc-status-pill acc-status-approved">
                                    <i class="fas fa-check-double"></i> Approved
                                </span>
                            @else
                                <span class="acc-status-pill acc-status-pending">
                                    <i class="far fa-circle"></i> Not Started
                                </span>
                            @endif
                        </div>

                        {{-- Metadata Strip --}}
                        <div class="acc-meta-strip">
                            <div class="acc-meta-col">
                                <span class="acc-meta-k"><i class="far fa-calendar-alt me-1 text-primary"></i> Date</span>
                                <span class="acc-meta-v">{{ $count->count_date ? $count->count_date->format('d M Y') : '—' }}</span>
                            </div>
                            <div class="acc-meta-col">
                                <span class="acc-meta-k"><i class="far fa-user me-1 text-primary"></i> Staff</span>
                                <span class="acc-meta-v text-truncate">{{ $staffName }}</span>
                            </div>
                            <div class="acc-meta-col">
                                <span class="acc-meta-k"><i class="fas fa-boxes me-1 text-primary"></i> Items</span>
                                <span class="acc-meta-v text-primary font-weight-bold">{{ $count->total_items }} items</span>
                            </div>
                        </div>

                        {{-- Live Progress Bar --}}
                        <div class="acc-progress-box">
                            <div class="acc-progress-header">
                                <span><i class="fas fa-tasks text-muted me-1"></i> Counting Progress</span>
                                <strong>{{ $count->entered_items }}/{{ $count->total_items }} counted ({{ $percent }}%)</strong>
                            </div>
                            <div class="acc-progress-track">
                                <div class="acc-progress-fill" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>

                        {{-- Full-Width Android Tactile Touch Action --}}
                        @if(Auth::check() && Auth::user()->usertype !== 'admin')
                            {{-- Staff Touch Action Buttons --}}
                            @if($count->status === 'not_started')
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="acc-btn acc-btn-start">
                                    <i class="fas fa-play me-1"></i> Start Blind Count
                                </a>
                            @elseif($count->status === 'started')
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="acc-btn acc-btn-resume">
                                    <i class="fas fa-stopwatch me-1"></i> Continue Counting
                                </a>
                            @elseif($count->status === 'submitted')
                                <div class="acc-btn acc-btn-waiting">
                                    <i class="fas fa-hourglass-half me-1"></i> Submitted — Waiting Manager Approval
                                </div>
                            @elseif($count->status === 'approved')
                                <div class="acc-btn acc-btn-approved">
                                    <i class="fas fa-check-double me-1"></i> Count Completed &amp; Approved
                                </div>
                            @endif
                        @else
                            {{-- Manager / Admin Action Buttons --}}
                            @if($count->status === 'submitted')
                                <a href="{{ route('cycle_count.approve', $count->id) }}" class="acc-btn acc-btn-start">
                                    <i class="fas fa-tasks me-1"></i> Open Review &amp; Reconcile
                                </a>
                            @elseif($count->status === 'started')
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="acc-btn acc-btn-resume">
                                    <i class="fas fa-eye me-1"></i> Inspect Count Sheet
                                </a>
                            @elseif($count->status === 'approved')
                                <a href="{{ route('cycle_count.approve', $count->id) }}" class="acc-btn acc-btn-approved">
                                    <i class="fas fa-check-circle me-1"></i> View Approved Stock
                                </a>
                            @else
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="acc-btn acc-btn-start">
                                    <i class="fas fa-clipboard-list me-1"></i> Open Count Sheet
                                </a>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="acc-empty-card">
                        <div class="acc-empty-icon-badge">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5>No Cycle Counts Scheduled</h5>
                        <p class="text-muted small mb-3">No physical inventory count tasks match your current filter.</p>
                        @if(request('week') !== 'all')
                            <a href="{{ route('cycle_count.index') }}?week=all" class="acc-btn acc-btn-start" style="display: inline-flex; width: auto; padding: 12px 24px;">
                                <i class="fas fa-calendar-alt me-1"></i> View All Weeks (Show All Counts)
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- 7. Collapsible Staff Guide Accordion --}}
            <div class="m-guide-card">
                <div class="m-guide-title" onclick="toggleMobileHelp()">
                    <span><i class="fas fa-info-circle text-primary me-2"></i> How Blind Physical Counting Works</span>
                    <i class="fas fa-chevron-down" id="helpChevron"></i>
                </div>
                <div class="m-guide-body" id="helpContent" style="display: none;">
                    <div class="mb-2"><strong>1. Blind Counting:</strong> System stock is hidden so you count objectively what is physically on the shelf.</div>
                    <div class="mb-2"><strong>2. Live Timer:</strong> Automatically measures elapsed duration from when you start until submission.</div>
                    <div class="mb-2"><strong>3. Instant Auto-save:</strong> Every entered quantity is automatically saved to the server as you type.</div>
                    <div><strong>4. Manager Review:</strong> Once submitted, the manager verifies discrepancies and approves inventory adjustments.</div>
                </div>
            </div>
        </div>

        {{-- Main Schedule Table --}}
        <div class="img2-table-wrap">
            <table class="img2-table">
                <thead>
                    <tr>
                        <th>COUNT #</th>
                        <th>LOCATION</th>
                        <th>ITEMS</th>
                        <th>DATE</th>
                        <th>ASSIGNED TO</th>
                        <th>STATUS</th>
                        <th>PROGRESS (LIVE)</th>
                        <th>SUBMITTED</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counts as $count)
                        <tr>
                            <td>
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="col-count-num">
                                    {{ $count->count_number }}
                                </a>
                            </td>
                            <td>
                                <span class="col-loc-name">
                                    {{ $count->location ? $count->location->name : ($count->location_name ?: 'SA3') }}
                                </span>
                            </td>
                            <td>
                                <span class="col-items-qty">{{ $count->total_items }}</span>
                            </td>
                            <td>
                                {{ $count->count_date ? $count->count_date->format('d M') : '[date]' }}
                            </td>
                            <td>
                                [{{ $count->counter ? $count->counter->name : 'Staff A' }}]
                            </td>
                            <td>
                                @if($count->status === 'started')
                                    <span class="status-pill started">
                                        Started {{ $count->started_at ? $count->started_at->format('H:i') : '' }}
                                    </span>
                                @elseif($count->status === 'submitted')
                                    <span class="status-pill submitted">Submitted</span>
                                    <span class="status-waiting-note">-- waiting for you</span>
                                @elseif($count->status === 'approved')
                                    <span class="status-pill approved">Approved</span>
                                @else
                                    <span class="status-pill not-started">Not started</span>
                                @endif
                            </td>
                            <td>
                                <div class="img2-progress-cell">
                                    <div class="img2-progress-track">
                                        <div class="img2-progress-fill" style="width: {{ $count->progress_percent }}%;"></div>
                                    </div>
                                    <span class="img2-progress-fraction">{{ $count->entered_items }}/{{ $count->total_items }}</span>
                                </div>
                            </td>
                            <td>
                                @if($count->submitted_at)
                                    {{ $count->submitted_at->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                                    {{-- STAFF VIEW: Never sees Open Review or Admin Approval Screen --}}
                                    @if($count->status === 'not_started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="btn btn-sm btn-primary text-white" style="font-size: 0.78rem; padding: 4px 12px; font-weight: 700; border-radius: 4px;">
                                            <i class="fas fa-play me-1"></i> Start Count
                                        </a>
                                    @elseif($count->status === 'started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="btn btn-sm btn-success text-white" style="font-size: 0.78rem; padding: 4px 12px; font-weight: 700; border-radius: 4px;">
                                            <i class="fas fa-stopwatch me-1"></i> Continue Count
                                        </a>
                                    @elseif($count->status === 'submitted')
                                        <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.78rem; padding: 5px 10px; font-weight: 600;">
                                            <i class="fas fa-clock me-1"></i> Submitted (Waiting for Approval)
                                        </span>
                                    @elseif($count->status === 'approved')
                                        <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.78rem; padding: 5px 10px; font-weight: 600;">
                                            <i class="fas fa-check-circle me-1"></i> Completed &amp; Approved
                                        </span>
                                    @endif
                                @else
                                    {{-- ADMIN / IN-CHARGE VIEW --}}
                                    @if($count->status === 'submitted')
                                        <a href="{{ route('cycle_count.approve', $count->id) }}" class="btn btn-sm btn-primary text-white font-weight-bold" style="font-size: 0.78rem; padding: 4px 12px; border-radius: 4px;">
                                            <i class="fas fa-tasks me-1"></i> Open Review
                                        </a>
                                    @elseif($count->status === 'approved')
                                        <a href="{{ route('cycle_count.approve', $count->id) }}" class="action-link font-weight-bold">View Approval</a>
                                    @elseif($count->status === 'started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="action-link text-success font-weight-bold">Inspect Sheet</a>
                                    @else
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="action-link text-primary font-weight-bold">Count Sheet</a>
                                        <span class="action-sep ms-1">|</span>
                                        <a href="#" class="action-link" data-toggle="modal" data-target="#reassignModal{{ $count->id }}">Reassign</a>
                                        <span class="action-sep">|</span>
                                        <form action="{{ route('cycle_count.cancel', $count->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this count?');">
                                            @csrf
                                            <button type="submit" class="action-link text-danger" style="border:none; background:none; padding:0; cursor:pointer;">Cancel</button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Reassign Modal --}}
                                <div class="modal fade text-left" id="reassignModal{{ $count->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 8px;">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold">Reassign Count {{ $count->count_number }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{ route('cycle_count.reassign', $count->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Assign to Staff</label>
                                                        <select name="staff_id" class="form-control" required>
                                                            @foreach($allStaff as $st)
                                                                <option value="{{ $st->id }}" {{ $count->assigned_to == $st->id ? 'selected' : '' }}>
                                                                    {{ $st->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Confirm Reassign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">
                                No counts scheduled. Click <strong>+ Assign cycle count</strong> to schedule a physical count.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer Summary --}}
        <div class="img2-table-footer">
            <div class="img2-table-footer-left">
                {{ $totalCountsCount }} counts this week · {{ $inProgressCount }} started · {{ $pendingApproval }} waiting for approval · {{ $completedThisWeek }} approved
            </div>
            <div class="img2-table-footer-right">
                Status and progress change by themselves — no refresh, no asking.
            </div>
        </div>

        {{-- Bottom 4 Explanatory Boxes (Image 2) --}}
        <div class="img2-bottom-grid">
            @if(Auth::check() && Auth::user()->usertype !== 'admin')
                <div class="img2-box">
                    <h6>Your Task List</h6>
                    <p>Locations (racks and shelves) assigned to you for physical counting. Click <strong>Start Count</strong> to open your sheet.</p>
                </div>
                <div class="img2-box">
                    <h6>Blind Counting</h6>
                    <p>Computer stock is hidden so you count objectively what is physically on the shelf without guessing.</p>
                </div>
                <div class="img2-box">
                    <h6>Live Timer</h6>
                    <p>Click "Start Count Timer" before counting. Each counted quantity auto-saves instantly as you type.</p>
                </div>
                <div class="img2-box">
                    <h6>Manager Approval</h6>
                    <p>After you submit, the manager / in-charge reviews any discrepancies and approves stock adjustments.</p>
                </div>
            @else
                <div class="img2-box">
                    <h6>Assignment</h6>
                    <p>Done on screen 1 (Assign). Each saved assignment becomes one row here. Not-started counts can be reassigned or cancelled.</p>
                </div>
                <div class="img2-box">
                    <h6>Staff view</h6>
                    <p>A staff member sees only their own assigned counts — not this table.</p>
                </div>
                <div class="img2-box">
                    <h6>Live status</h6>
                    <p>"Started" the moment the staff opens the count. Progress counter updates as each quantity is entered. "Submitted" the moment they submit.</p>
                </div>
                <div class="img2-box">
                    <h6>Approval</h6>
                    <p>Only Owner / In-charge approves. A counter never approves their own count.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let currentMobileStatusFilter = 'all';

    function filterMobileStatus(status, btnEl) {
        currentMobileStatusFilter = status;
        
        // Update active chip across both class variants
        document.querySelectorAll('.m-chip, .mobile-chip').forEach(btn => btn.classList.remove('active'));
        if (btnEl) {
            btnEl.classList.add('active');
        } else {
            const target = document.querySelector(`.m-chip[data-filter="${status}"], .mobile-chip[data-filter="${status}"]`);
            if (target) target.classList.add('active');
        }

        applyMobileFilters();
    }

    function searchMobileCounts(query) {
        const clearBtn = document.getElementById('mobileSearchClearBtn');
        if (clearBtn) {
            clearBtn.style.display = query && query.length > 0 ? 'inline-block' : 'none';
        }
        applyMobileFilters();
    }

    function clearMobileSearch() {
        const inp = document.getElementById('mobileSearchInput');
        if (inp) {
            inp.value = '';
            const clearBtn = document.getElementById('mobileSearchClearBtn');
            if (clearBtn) clearBtn.style.display = 'none';
        }
        applyMobileFilters();
    }

    function applyMobileFilters() {
        const query = (document.getElementById('mobileSearchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.android-count-card, .mobile-count-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const searchData = card.getAttribute('data-search') || '';

            const matchesStatus = (currentMobileStatusFilter === 'all') || (cardStatus === currentMobileStatusFilter);
            const matchesQuery = (!query) || (searchData.indexOf(query) !== -1);

            if (matchesStatus && matchesQuery) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
    }

    function toggleMobileHelp() {
        const content = document.getElementById('helpContent');
        const chevron = document.getElementById('helpChevron');
        if (!content) return;
        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            if (chevron) chevron.className = 'fas fa-chevron-up';
        } else {
            content.style.display = 'none';
            if (chevron) chevron.className = 'fas fa-chevron-down';
        }
    }
</script>
@endsection
