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
       EXACT MOBILE ANDROID APP STYLES (Pixel-Perfect Mockup Match)
       These apply on < 992px ONLY. Desktop is handled above.
       ======================================================== */
    @media (max-width: 991.98px) {

        /* ── EXACT DARK CURVED HEADER ── */
        .m-exact-header {
            background-color: #0b1329;
            padding: 16px 16px 26px 16px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            color: #ffffff;
            box-shadow: 0 6px 24px rgba(11, 19, 41, 0.35);
        }
        .m-exact-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .m-exact-brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .m-exact-ham-btn {
            background: transparent;
            border: none;
            padding: 0;
            width: 22px;
            height: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }
        .m-exact-ham-btn span {
            display: block;
            height: 2.2px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 2px;
        }
        .m-exact-title-box {
            display: flex;
            flex-direction: column;
        }
        .m-exact-brand-name {
            font-size: 14.5px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }
        .m-exact-portal-label {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 1px;
        }
        .m-exact-right-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .m-exact-bell-btn {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 18px;
            padding: 4px;
            cursor: pointer;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .m-exact-avatar-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            line-height: 1;
            padding: 0;
        }

        .m-exact-hero-tag {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #60a5fa;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .m-exact-hero-title {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 4px 0;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .m-exact-hero-sub {
            font-size: 12px;
            color: #cbd5e1;
            margin: 0;
            line-height: 1.3;
        }

        /* ── MAIN CONTENT CONTAINER (Below Curved Header) ── */
        .m-exact-body {
            padding: 14px 14px 85px 14px;
            background: #f0f4f8;
        }

        /* ── FILTER ROW (All Weeks, All Status, Refresh) ── */
        .m-exact-filter-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }
        .m-exact-select-wrap {
            flex: 1;
            position: relative;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            height: 42px;
            display: flex;
            align-items: center;
            padding: 0 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .m-exact-select-input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 18px;
        }
        .m-exact-caret {
            position: absolute;
            right: 12px;
            pointer-events: none;
            font-size: 11px;
            color: #64748b;
        }
        .m-exact-refresh-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            text-decoration: none !important;
            flex-shrink: 0;
            transition: background 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .m-exact-refresh-btn:active {
            background: #e2e8f0;
        }

        /* ── 2x2 KPI STAT CARDS ── */
        .m-exact-kpi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }
        .m-exact-kpi-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        .m-exact-kpi-card:active {
            transform: scale(0.98);
        }
        .m-exact-kpi-card.active {
            border-color: #2563eb !important;
            background: #eff6ff !important;
        }
        .m-exact-kpi-label {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .m-exact-kpi-val {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
        }
        .val-blue { color: #2563eb; }
        .val-green { color: #059669; }
        .val-amber { color: #d97706; }

        /* ── SEARCH BAR ── */
        .m-exact-search-wrap {
            height: 44px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 22px;
            padding: 0 14px;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        .m-exact-search-icon {
            color: #64748b;
            font-size: 13px;
            margin-right: 8px;
        }
        .m-exact-search-input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
            color: #0f172a;
        }
        .m-exact-search-clear {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
        }

        /* ── TASK CARDS ── */
        .m-exact-cards-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .m-exact-task-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            padding: 14px 16px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }
        .m-exact-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .m-exact-loc-badge {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 800;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid #bfdbfe;
            display: inline-flex;
            align-items: center;
        }
        .m-exact-code-txt {
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            margin-left: 6px;
        }
        .m-exact-status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 14px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-not-started {
            background: transparent;
            border: 1px solid #94a3b8;
            color: #334155;
        }
        .status-started {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #15803d;
        }
        .status-submitted {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .status-approved {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
        }

        /* Gray Meta Strip */
        .m-exact-meta-strip {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 8px 12px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 1fr 1.3fr 1fr;
            gap: 6px;
        }
        .m-exact-meta-col {
            display: flex;
            flex-direction: column;
        }
        .m-exact-meta-k {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.3px;
        }
        .m-exact-meta-v {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
        }
        .m-exact-meta-v.items-blue {
            color: #2563eb;
        }

        /* Counting Progress */
        .m-exact-progress-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            margin-bottom: 6px;
        }
        .m-exact-prog-label {
            color: #475569;
            font-weight: 500;
        }
        .m-exact-prog-frac {
            color: #0f172a;
            font-weight: 600;
        }
        .m-exact-prog-track {
            height: 6px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .m-exact-prog-fill {
            height: 100%;
            background: #2563eb;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Exact Button Pill */
        .m-exact-btn-pill {
            width: 100%;
            height: 46px;
            background: #2563eb;
            color: #ffffff !important;
            border-radius: 23px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .m-exact-btn-pill:active {
            transform: scale(0.98);
            background: #1d4ed8;
        }
        .m-exact-btn-pill.btn-resume {
            background: #10b981;
        }
        .m-exact-btn-pill.btn-waiting {
            background: #eff6ff;
            color: #1d4ed8 !important;
            border: 1.5px solid #bfdbfe;
            cursor: default;
        }
        .m-exact-btn-pill.btn-approved {
            background: #ecfdf5;
            color: #047857 !important;
            border: 1.5px solid #a7f3d0;
            cursor: default;
        }

        /* Empty Card */
        .m-exact-empty-card {
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 34px 20px;
            text-align: center;
        }
        .m-exact-empty-card h6 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
    }

    /* =========================================================
       SCREEN-SIZE BASED LAYOUT TOGGLE
       Desktop (>= 992px) = original table UI
       Mobile / Tablet (< 992px) = Flutter-style app UI
       ========================================================= */

    /* --- DESKTOP (>= 992px): Show table UI, hide app view --- */
    @media (min-width: 992px) {
        .mobile-schedule-app-view {
            display: none !important;
        }
        .img2-top-bar,
        .img2-toolbar,
        .img2-table-wrap,
        .img2-table-footer,
        .img2-bottom-grid {
            display: block !important;
        }
        .img2-table-footer,
        .img2-bottom-grid {
            display: flex !important;
        }
        .img2-table-wrap {
            display: block !important;
        }
        .img2-top-bar,
        .img2-toolbar {
            display: flex !important;
        }
        .img2-bottom-grid {
            display: grid !important;
        }
    }

    /* --- MOBILE / TABLET (< 992px): Show app view, hide desktop table --- */
    @media (max-width: 991.98px) {
        /* 1. Zero-gap flush header: kill ALL body/container top padding on this page */
        html { padding-top: 0 !important; }

        body,
        body.product-page-body,
        body.cycle-count-screen,
        body.product-page-body.cycle-count-screen {
            padding-top: 0 !important;
            margin-top: 0 !important;
            background: #0b1329 !important;
            padding-bottom: 74px !important;
        }

        .container-scroller,
        .app-page-body,
        body.product-page-body .app-page-body,
        body.cycle-count-screen .app-page-body,
        body.product-page-body.cycle-count-screen .app-page-body {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* 2. Kill the legacy mobile topbar (this page has its own header) */
        .app-mobile-topbar,
        header.app-mobile-topbar {
            display: none !important;
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
        }

        /* 3. Strip the desktop-UI wrappers of any layout */
        .img2-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            min-height: auto !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }
        .img2-container {
            min-width: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* 4. Hide all desktop-only UI elements */
        .img2-top-bar,
        .img2-toolbar,
        .img2-table-wrap,
        .img2-table-footer,
        .img2-bottom-grid,
        .toolbar-right-note {
            display: none !important;
        }

        /* 5. Ensure app mobile view is shown as block */
        .mobile-schedule-app-view {
            display: block !important;
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
            {{-- 1. Dark Curved Hero Header (Exact Visual Match) --}}
            <div class="m-exact-header">
                <div class="m-exact-top-bar">
                    <div class="m-exact-brand-wrap">
                        <button type="button" class="m-exact-ham-btn" id="mobileDrawerOpenBtn" onclick="if(typeof openMobileAppDrawer === 'function'){ openMobileAppDrawer(); } else { $('#bottomDrawerTriggerBtn').click(); }" aria-label="Open Menu">
                            <span></span><span></span><span></span>
                        </button>
                        <div class="m-exact-title-box">
                            <span class="m-exact-brand-name">{{ \App\Models\Setting::get('company_name', 'Kent Hardware Center') }}</span>
                            <span class="m-exact-portal-label">Staff Portal</span>
                        </div>
                    </div>
                    <div class="m-exact-right-actions">
                        <a href="{{ route('notifications.index') }}" class="m-exact-bell-btn" title="Notifications" aria-label="Notifications">
                            <i class="far fa-bell"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="m-exact-avatar-btn" data-toggle="dropdown" aria-expanded="false" title="Account Profile">
                                {{ strtoupper(substr(Auth::user()->name ?? 'F', 0, 1)) }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg p-2" style="border-radius: 14px; margin-top: 8px; min-width: 220px; z-index: 99999;">
                                <div class="px-3 py-2 border-bottom mb-2 bg-light rounded-3">
                                    <p class="mb-0 text-dark fw-bold small text-truncate">{{ Auth::user()->name ?? 'Staff User' }}</p>
                                    <small class="text-muted text-break" style="font-size: 11px;">{{ Auth::user()->email ?? '' }}</small>
                                </div>
                                @canany(['settings.view', 'settings.read'])
                                    <a class="dropdown-item d-flex align-items-center text-dark fw-semibold py-2 rounded-2" href="{{ route('settings.index') }}">
                                        <i class="fas fa-cog text-primary me-2"></i> Settings
                                    </a>
                                @endcanany
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger fw-semibold py-2 rounded-2">
                                        <i class="fas fa-power-off text-danger me-2"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-exact-hero-tag">PHYSICAL COUNTING</div>
                <h1 class="m-exact-hero-title">Cycle Counts &amp; Stock</h1>
                <p class="m-exact-hero-sub">Staff: {{ Auth::user()->name ?? 'Faraz Memon' }} · Real-time warehouse counting</p>
            </div>

            {{-- 2. Main Canvas Body --}}
            <div class="m-exact-body">
                {{-- Filter Row: All Weeks, All Status, Refresh --}}
                <form method="GET" action="{{ route('cycle_count.index') }}" id="mExactFilterForm" class="m-exact-filter-row">
                    <div class="m-exact-select-wrap">
                        <select name="week" onchange="document.getElementById('mExactFilterForm').submit()" class="m-exact-select-input">
                            <option value="all" {{ request('week', 'all') == 'all' ? 'selected' : '' }}>All Weeks</option>
                            <option value="this_week" {{ request('week') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ request('week') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="next_week" {{ request('week') == 'next_week' ? 'selected' : '' }}>Next Week</option>
                        </select>
                        <i class="fas fa-caret-down m-exact-caret"></i>
                    </div>

                    <div class="m-exact-select-wrap">
                        <select name="status" onchange="document.getElementById('mExactFilterForm').submit()" class="m-exact-select-input">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not Started</option>
                            <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>In Progress</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Waiting Review</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                        <i class="fas fa-caret-down m-exact-caret"></i>
                    </div>

                    <a href="{{ route('cycle_count.index') }}?week=all" class="m-exact-refresh-btn" title="Refresh">
                        <i class="fas fa-redo-alt"></i>
                    </a>
                </form>

                {{-- 2x2 KPI Stat Cards --}}
                <div class="m-exact-kpi-grid">
                    <div class="m-exact-kpi-card" data-kpi="all" onclick="filterByExactStatus('all', this)">
                        <div class="m-exact-kpi-label">TOTAL ASSIGNED</div>
                        <div class="m-exact-kpi-val val-blue">{{ $totalCountsCount }}</div>
                    </div>
                    <div class="m-exact-kpi-card" data-kpi="started" onclick="filterByExactStatus('started', this)">
                        <div class="m-exact-kpi-label">IN PROGRESS</div>
                        <div class="m-exact-kpi-val val-green">{{ $inProgressCount }}</div>
                    </div>
                    <div class="m-exact-kpi-card" data-kpi="submitted" onclick="filterByExactStatus('submitted', this)">
                        <div class="m-exact-kpi-label">WAITING REVIEW</div>
                        <div class="m-exact-kpi-val val-amber">{{ $pendingApproval }}</div>
                    </div>
                    <div class="m-exact-kpi-card" data-kpi="approved" onclick="filterByExactStatus('approved', this)">
                        <div class="m-exact-kpi-label">APPROVED</div>
                        <div class="m-exact-kpi-val val-green">{{ $completedThisWeek }}</div>
                    </div>
                </div>

                {{-- Search Pill Bar --}}
                <div class="m-exact-search-wrap">
                    <i class="fas fa-search m-exact-search-icon"></i>
                    <input type="text" id="mExactSearchInput" placeholder="Search count #, rack, staff..." class="m-exact-search-input" oninput="handleExactSearch(this.value)">
                    <button type="button" class="m-exact-search-clear" id="mExactSearchClear" onclick="clearExactSearch()" style="display: none;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                {{-- Task Cards List --}}
                <div class="m-exact-cards-list" id="mExactCardsList">
                    @forelse($counts as $count)
                        @php
                            $locRaw = $count->location ? $count->location->name : ($count->location_name ?: 'A1');
                            $staffName = $count->counter ? $count->counter->name : (Auth::user()->name ?? 'Staff');
                            $percent = $count->progress_percent ?? 0;
                            $statusLabel = 'Not Started';
                            $statusClass = 'status-not-started';
                            if ($count->status === 'started') {
                                $statusLabel = 'In Progress';
                                $statusClass = 'status-started';
                            } elseif ($count->status === 'submitted') {
                                $statusLabel = 'Waiting Review';
                                $statusClass = 'status-submitted';
                            } elseif ($count->status === 'approved') {
                                $statusLabel = 'Approved';
                                $statusClass = 'status-approved';
                            }
                        @endphp
                        <div class="m-exact-task-card"
                             data-status="{{ $count->status }}"
                             data-search="{{ strtolower($count->count_number . ' ' . $locRaw . ' ' . $staffName . ' ' . $statusLabel) }}"
                             id="mExactCard{{ $count->id }}">
                            <div class="m-exact-card-top">
                                <div class="d-flex align-items-center">
                                    <span class="m-exact-loc-badge">{{ $locRaw }}</span>
                                    <span class="m-exact-code-txt">#{{ $count->count_number }}</span>
                                </div>
                                <span class="m-exact-status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>

                            <div class="m-exact-meta-strip">
                                <div class="m-exact-meta-col">
                                    <span class="m-exact-meta-k">DATE</span>
                                    <span class="m-exact-meta-v">{{ $count->count_date ? $count->count_date->format('d M') : now()->format('d M') }}</span>
                                </div>
                                <div class="m-exact-meta-col">
                                    <span class="m-exact-meta-k">ASSIGNED</span>
                                    <span class="m-exact-meta-v text-truncate">{{ $staffName }}</span>
                                </div>
                                <div class="m-exact-meta-col">
                                    <span class="m-exact-meta-k">ITEMS</span>
                                    <span class="m-exact-meta-v items-blue">{{ $count->total_items }} items</span>
                                </div>
                            </div>

                            <div class="m-exact-progress-row">
                                <span class="m-exact-prog-label">Counting Progress</span>
                                <span class="m-exact-prog-frac">{{ $count->entered_items }}/{{ $count->total_items }} ({{ $percent }}%)</span>
                            </div>
                            <div class="m-exact-prog-track">
                                <div class="m-exact-prog-fill" style="width: {{ $percent }}%;"></div>
                            </div>

                            @if(Auth::check() && Auth::user()->usertype !== 'admin')
                                @if($count->status === 'not_started')
                                    <a href="{{ route('cycle_count.sheet', $count->id) }}" class="m-exact-btn-pill">
                                        <i class="fas fa-play" style="font-size: 11px;"></i> Start Blind Count
                                    </a>
                                @elseif($count->status === 'started')
                                    <a href="{{ route('cycle_count.sheet', $count->id) }}" class="m-exact-btn-pill btn-resume">
                                        <i class="fas fa-stopwatch" style="font-size: 12px;"></i> Continue Counting
                                    </a>
                                @elseif($count->status === 'submitted')
                                    <div class="m-exact-btn-pill btn-waiting">
                                        <i class="fas fa-clock" style="font-size: 12px;"></i> Waiting for Manager
                                    </div>
                                @elseif($count->status === 'approved')
                                    <div class="m-exact-btn-pill btn-approved">
                                        <i class="fas fa-check-double" style="font-size: 12px;"></i> Approved
                                    </div>
                                @endif
                            @else
                                @if($count->status === 'submitted')
                                    <a href="{{ route('cycle_count.approve', $count->id) }}" class="m-exact-btn-pill">
                                        <i class="fas fa-tasks" style="font-size: 12px;"></i> Open Review &amp; Reconcile
                                    </a>
                                @elseif($count->status === 'started')
                                    <a href="{{ route('cycle_count.sheet', $count->id) }}" class="m-exact-btn-pill btn-resume">
                                        <i class="fas fa-eye" style="font-size: 12px;"></i> Inspect Count Sheet
                                    </a>
                                @elseif($count->status === 'approved')
                                    <a href="{{ route('cycle_count.approve', $count->id) }}" class="m-exact-btn-pill btn-approved">
                                        <i class="fas fa-check-circle" style="font-size: 12px;"></i> View Approved Stock
                                    </a>
                                @else
                                    <a href="{{ route('cycle_count.sheet', $count->id) }}" class="m-exact-btn-pill">
                                        <i class="fas fa-clipboard-list" style="font-size: 12px;"></i> Open Count Sheet
                                    </a>
                                @endif
                            @endif
                        </div>
                    @empty
                        <div class="m-exact-empty-card">
                            <h6>No counts found</h6>
                            <p class="text-muted small mb-3">No physical counts match your filter.</p>
                            <a href="{{ route('cycle_count.index') }}?week=all" class="m-exact-btn-pill" style="display:inline-flex; width:auto; padding:10px 24px;">View All Weeks</a>
                        </div>
                    @endforelse
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
    let currentExactStatus = 'all';

    function filterByExactStatus(status, el) {
        currentExactStatus = status;
        document.querySelectorAll('.m-exact-kpi-card').forEach(c => c.classList.remove('active'));
        if (el) {
            el.classList.add('active');
        } else if (status !== 'all') {
            const target = document.querySelector(`.m-exact-kpi-card[data-kpi="${status}"]`);
            if (target) target.classList.add('active');
        }
        applyExactFilters();
    }

    function handleExactSearch(val) {
        const clearBtn = document.getElementById('mExactSearchClear');
        if (clearBtn) {
            clearBtn.style.display = val && val.length > 0 ? 'inline-block' : 'none';
        }
        applyExactFilters();
    }

    function clearExactSearch() {
        const inp = document.getElementById('mExactSearchInput');
        if (inp) {
            inp.value = '';
            const clearBtn = document.getElementById('mExactSearchClear');
            if (clearBtn) clearBtn.style.display = 'none';
        }
        applyExactFilters();
    }

    function applyExactFilters() {
        const query = (document.getElementById('mExactSearchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.m-exact-task-card, .android-count-card, .mobile-count-card');

        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const searchData = (card.getAttribute('data-search') || '').toLowerCase();

            const matchesStatus = (currentExactStatus === 'all') || (cardStatus === currentExactStatus);
            const matchesQuery = (!query) || (searchData.indexOf(query) !== -1);

            if (matchesStatus && matchesQuery) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Aliases
    function filterMobileStatus(status, el) { filterByExactStatus(status, el); }
    function searchMobileCounts(query) { handleExactSearch(query); }
    function clearMobileSearch() { clearExactSearch(); }
</script>
@endsection
