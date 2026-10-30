@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 3 (Staff Blind Count Sheet) */
    .img3-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: calc(100vh - 56px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: auto;
    }
    .img3-container {
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
    .img3-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img3-top-bar .title-section {
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
    .img3-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
    }
    .img3-top-bar .badge-pill-role {
        background: #10b981;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img3-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* Sub-header Bar */
    .img3-sub-bar {
        padding: 10px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.8rem;
    }
    .img3-sub-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        color: #475569;
    }
    .img3-sub-meta strong {
        color: #0f172a;
    }
    .img3-sub-entered {
        color: #10b981;
        font-weight: 700;
    }
    .blind-yellow-badge {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Timer Controls in Sub-bar */
    .timer-strip-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 4px 10px;
        border-radius: 4px;
    }
    .stopwatch-digital {
        font-family: 'JetBrains Mono', monospace, 'Courier New';
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: 0.5px;
    }
    .stopwatch-digital.running {
        color: #16a34a;
        animation: pulseText 1.5s infinite;
    }
    @keyframes pulseText {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.75; }
    }
    .btn-start-timer {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }
    .btn-start-timer:hover {
        background: #059669;
    }

    /* 2-Column Side-by-Side Split Grid: Always 2 Columns */
    .img3-split-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-bottom: 1px solid #e2e8f0;
    }
    .img3-half-col {
        border-right: 1px solid #e2e8f0;
        overflow-x: auto;
    }
    .img3-half-col:last-child {
        border-right: none;
    }

    /* Blind Count Table */
    .blind-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
    }
    .blind-table th {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        padding: 6px 8px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .blind-table td {
        padding: 5px 8px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
        height: 38px;
    }
    .blind-table tbody tr.row-active {
        background: #e0edff !important;
    }
    .blind-table tbody tr:hover {
        background: #f8fafc;
    }

    .col-idx {
        color: #94a3b8;
        font-weight: 600;
        width: 26px;
        text-align: right;
        padding-right: 4px !important;
    }
    .col-unique {
        font-family: monospace;
        font-weight: 700;
        color: #1e293b;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
        white-space: nowrap;
        display: inline-block;
    }
    .col-img-wrap {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        overflow: hidden;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #94a3b8;
    }
    .col-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .col-name-box {
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .col-name-title {
        color: #0f172a;
        font-weight: 600;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .col-cat-badge {
        font-size: 0.65rem;
        background: #f3e8ff;
        color: #7e22ce;
        padding: 1px 5px;
        border-radius: 3px;
        font-weight: 600;
        display: inline-block;
    }
    .col-barcode {
        font-size: 0.7rem;
        color: #475569;
        font-family: monospace;
        white-space: nowrap;
    }
    .col-unit {
        color: #64748b;
        width: 30px;
    }

    /* Count Input Box */
    .count-input-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        width: 78px;
    }
    .count-box-input {
        width: 52px;
        height: 26px;
        border: 1px solid #3b82f6;
        border-radius: 4px;
        background: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        padding: 0 4px;
    }
    .count-box-input:focus {
        border-color: #1d4ed8;
        outline: none;
        background: #f0fdf4;
    }
    .count-check-icon {
        color: #10b981;
        font-weight: 700;
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .count-check-icon.visible {
        opacity: 1;
    }

    /* Bottom Toolbar */
    .img3-bottom-bar {
        padding: 10px 18px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .search-scanner-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        color: #475569;
    }
    .search-scanner-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 0.8rem;
        width: 220px;
    }
    .search-scanner-input:focus {
        border-color: #2563eb;
        outline: none;
    }

    .btn-sheet-draft {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-sheet-draft:hover {
        background: #475569;
    }
    .btn-sheet-submit {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-sheet-submit:hover {
        background: #059669;
    }

    /* Bottom 4 Explanation Boxes: Fixed 4 Columns */
    .img3-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }
    .img3-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img3-box:last-child {
        border-right: none;
    }
    .img3-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img3-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }

    /* ========================================================
       MOBILE ANDROID APP BLIND COUNT SHEET (Exact PDF Match)
       These apply on < 992px ONLY. Desktop is handled below.
       ======================================================== */
    @media (max-width: 991.98px) {

        /* ── TOP NAV APP BAR (Exact PDF Header) ── */
        .m-sheet-header {
            background-color: #0b1329;
            padding: 14px 16px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }
        .m-sheet-top-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .m-sheet-back-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }
        .m-sheet-back-btn:active {
            background: rgba(255, 255, 255, 0.22);
            transform: scale(0.96);
        }
        .m-sheet-title-box {
            display: flex;
            flex-direction: column;
        }
        .m-sheet-brand-name {
            font-size: 17px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
            margin: 0;
            letter-spacing: -0.2px;
        }
        .m-sheet-portal-label {
            font-size: 11.5px;
            color: #cbd5e1;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 2px;
        }
        .m-sheet-top-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .m-sheet-bell-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            text-decoration: none !important;
            cursor: pointer;
        }
        .m-sheet-avatar-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
        }

        /* ── MAIN BODY (Below Header) ── */
        .m-sheet-body {
            padding: 14px 14px 120px 14px;
            background: #f4f6f9;
        }

        /* ── TIME ELAPSED & PROGRESS CARD (Exact PDF) ── */
        .m-sticky-timer-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 18px 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }
        .m-timer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }
        .m-timer-left {
            display: flex;
            flex-direction: column;
        }
        .m-timer-label {
            font-size: 10.5px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .m-timer-display {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, monospace;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            line-height: 1;
        }
        .m-timer-display.running {
            color: #059669;
        }
        .m-btn-start-timer {
            background: #059669;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 8px rgba(5, 150, 105, 0.25);
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .m-btn-start-timer:active {
            background: #047857;
            transform: scale(0.97);
        }
        .m-progress-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .m-progress-meta-row .m-prog-val {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
        }
        .m-progress-track {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .m-progress-fill {
            height: 100%;
            background: #059669;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        .m-blind-notice-pill {
            background: #fef9ee;
            border: 1px solid #fef3c7;
            color: #78350f;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0;
            line-height: 1.4;
        }

        /* ── SEARCH & BARCODE PILL (Exact PDF) ── */
        .m-search-box {
            position: relative;
            margin-bottom: 12px;
        }
        .m-search-input-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0 14px;
            height: 48px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            transition: border-color 0.2s ease;
        }
        .m-search-input-wrap:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .m-search-input-wrap .search-icon {
            color: #64748b;
            font-size: 14px;
            margin-right: 10px;
        }
        .m-search-input {
            flex: 1;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #0f172a;
            font-weight: 500;
        }
        .m-search-input::placeholder {
            color: #64748b;
            font-weight: 400;
        }
        .m-scanner-target-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 4px;
            margin-left: 6px;
        }

        /* ── FILTER CHIPS (Exact PDF) ── */
        .m-filter-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            margin-bottom: 12px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .m-filter-chips::-webkit-scrollbar {
            display: none;
        }
        .m-chip-btn {
            flex-shrink: 0;
            padding: 7px 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .m-chip-btn.active {
            background: #0b1329;
            color: #ffffff;
            border-color: #0b1329;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(11, 19, 41, 0.25);
        }

        /* ── ITEM CARDS (Exact PDF) ── */
        .m-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .m-sheet-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            position: relative;
            transition: all 0.2s ease;
        }
        .m-sheet-card.is-active-focus {
            box-shadow: 0 0 0 2px #2563eb, 0 6px 20px rgba(37, 99, 235, 0.12);
            border-color: #2563eb;
        }
        .m-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 0;
        }
        .m-thumb-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            overflow: hidden;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
        }
        .m-thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .m-item-title-col {
            flex: 1;
            min-width: 0;
        }
        .m-item-name {
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            margin: 0 0 5px 0;
            line-height: 1.3;
            word-break: break-word;
        }
        .m-code-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px !important;
            font-weight: 800 !important;
            background: #f1f5f9;
            color: #334155 !important;
            padding: 2px 7px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .m-cat-badge {
            font-size: 11.5px !important;
            background: #ede9fe;
            color: #6d28d9 !important;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 700 !important;
        }
        .m-barcode-badge {
            font-family: monospace;
            font-size: 11px !important;
            color: #64748b !important;
            font-weight: 600 !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .m-counted-pill {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            color: #64748b !important;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px !important;
            font-weight: 600 !important;
            white-space: nowrap;
        }
        .m-counted-pill.is-counted {
            background: #dcfce7;
            color: #15803d !important;
            font-weight: 700 !important;
        }

        /* ── Stepper & Unit Row (Exact PDF) ── */
        .m-counter-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
        }
        .m-unit-col {
            display: flex;
            flex-direction: column;
        }
        .m-unit-tag {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .m-unit-text {
            font-size: 13.5px;
            color: #0f172a;
            font-weight: 700;
        }
        .m-stepper-box {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 3px;
            gap: 2px;
        }
        .m-btn-step {
            width: 38px !important;
            height: 38px !important;
            border-radius: 10px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .m-btn-minus {
            background: transparent;
            color: #64748b !important;
            font-size: 20px !important;
            font-weight: 700 !important;
        }
        .m-btn-plus {
            background: #0b1329 !important;
            color: #ffffff !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 5px rgba(11, 19, 41, 0.25);
        }
        .m-btn-plus:active {
            transform: scale(0.95);
        }
        .m-input-qty {
            width: 44px !important;
            height: 38px !important;
            border: none;
            outline: none;
            text-align: center;
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #0f172a;
            background: transparent;
            padding: 0;
        }
        .m-input-qty:focus {
            color: #2563eb;
        }

        /* ── Sticky Bottom Action Bar Pinned Above Bottom Navigation (Exact PDF: Pure White Bar) ── */
        .m-sticky-bottom-bar {
            position: fixed;
            bottom: 60px;
            left: 0;
            right: 0;
            background: #ffffff !important;
            border-top: 1px solid #e2e8f0;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 1025;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
            width: 100%;
        }
        .m-bottom-summary {
            display: flex;
            flex-direction: column;
        }
        .m-bottom-title {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
            line-height: 1.2;
        }
        .m-bottom-count {
            font-size: 19px;
            color: #0f172a;
            font-weight: 800;
            line-height: 1.2;
            margin: 1px 0;
        }
        .m-bottom-sub {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }
        .m-btn-submit-sheet {
            background: #475569;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(71, 85, 105, 0.25);
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .m-btn-submit-sheet:active {
            transform: scale(0.97);
            background: #334155;
        }
    }

    /* =========================================================
       SCREEN-SIZE BASED LAYOUT TOGGLE
       Desktop (>= 992px) = original 2-column table UI exactly
       Mobile / Tablet (< 992px) = Flutter-style app UI
       ========================================================= */

    /* --- DESKTOP (>= 992px): Show table UI, hide app view --- */
    @media (min-width: 992px) {
        .mobile-count-sheet-app,
        .m-sticky-bottom-bar {
            display: none !important;
        }
        .img3-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            color: #1e293b !important;
            background: #f1f5f9 !important;
            padding: 12px !important;
            min-height: calc(100vh - 56px) !important;
            width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: auto !important;
            display: block !important;
        }
        .img3-container {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            overflow: hidden !important;
            min-width: 1080px !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            display: block !important;
        }
        .img3-top-bar {
            display: flex !important;
        }
        .img3-sub-bar {
            display: flex !important;
        }
        .img3-split-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
        }
        .img3-half-col {
            display: block !important;
        }
        .blind-table {
            display: table !important;
        }
        .img3-bottom-bar {
            display: flex !important;
        }
        .img3-bottom-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
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
            background: #f0f4f8 !important;
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

        /* 2. Kill legacy mobile topbar */
        .app-mobile-topbar,
        header.app-mobile-topbar {
            display: none !important;
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
        }

        /* 3. Strip desktop wrappers */
        .img3-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            min-height: auto !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }
        .img3-container {
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
        .img3-top-bar,
        .img3-sub-bar,
        .img3-split-grid,
        .img3-bottom-bar,
        .img3-bottom-grid {
            display: none !important;
        }

        /* 5. Ensure app mobile view and bottom bar are shown */
        .mobile-count-sheet-app {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .m-sticky-bottom-bar {
            display: flex !important;
            position: fixed !important;
            bottom: 60px !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            transform: none !important;
            box-sizing: border-box !important;
        }
    }

    /* ── Pulse animation for Start Count button when user tries unauthorized input ── */
    @keyframes pulseAttention {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.85);
        }
        50% {
            transform: scale(1.08);
            box-shadow: 0 0 0 14px rgba(5, 150, 105, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(5, 150, 105, 0);
        }
    }
    .pulse-highlight {
        animation: pulseAttention 0.5s ease-in-out 3 !important;
        outline: 3px solid #10b981 !important;
        outline-offset: 2px !important;
    }

    /* ── Readonly state for unstarted inputs ── */
    .m-input-qty[readonly],
    .count-box-input[readonly] {
        cursor: not-allowed !important;
        background-color: rgba(241, 245, 249, 0.75) !important;
        opacity: 0.85 !important;
    }

    /* ── Floating Start Count Alert Toast ── */
    .sheet-start-toast {
        position: fixed;
        bottom: 130px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 99999;
        max-width: 92%;
        width: 440px;
        background: #0b1329;
        color: #ffffff;
        border: 1.5px solid #f59e0b;
        border-radius: 16px;
        padding: 14px 18px;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.45);
        animation: toastSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes toastSlideUp {
        from {
            opacity: 0;
            transform: translate(-50%, 24px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    .toast-inner {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .toast-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.18);
        color: #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .toast-body {
        flex: 1;
        font-size: 13px;
        line-height: 1.35;
        color: #cbd5e1;
    }
    .toast-body strong {
        display: block;
        color: #f59e0b;
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 2px;
    }
    .toast-body span {
        display: block;
        color: #e2e8f0;
        font-size: 12px;
    }
    .toast-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
        padding: 0 6px;
        line-height: 1;
        border-radius: 6px;
    }
    .toast-close:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }
</style>

{{-- Toast notification when staff tries to enter data before starting count --}}
<div id="mStartCountToast" class="sheet-start-toast" style="display: none;">
    <div class="toast-inner">
        <div class="toast-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="toast-body">
            <strong>Counter Not Started!</strong>
            <span>Counter start kiye baghair data enter nahi ho sakta. Pehle <strong>"Start Count"</strong> button dabayein.</span>
        </div>
        <button type="button" class="toast-close" onclick="hideStartCountPrompt()" aria-label="Close">&times;</button>
    </div>
</div>

<div class="img3-wrapper">
    <div class="img3-container">
        {{-- Top Dark Bar --}}
        <div class="img3-top-bar">
            <div class="title-section">
                <span class="main-title">3 · Count sheet {{ $cycleCount->count_number }} — Location {{ $cycleCount->location ? $cycleCount->location->name : ($cycleCount->location_name ?: 'General') }}</span>
                <span class="badge-pill-role">Staff screen · blind</span>
            </div>
            <div class="d-flex align-items-center" style="gap:10px;">
                @if(in_array($cycleCount->status, ['submitted', 'approved']) && Auth::check() && Auth::user()->usertype === 'admin')
                    <a href="{{ route('cycle_count.approve', $cycleCount->id) }}"
                       style="display:inline-flex; align-items:center; gap:5px; background:#dc2626; color:#fff; padding:4px 12px; border-radius:4px; font-size:0.78rem; font-weight:700; text-decoration:none; white-space:nowrap;">
                        <i class="fas fa-tasks"></i> Back to Approval / Review
                    </a>
                @endif
                <div class="concept-note">
                    BLIND AUDIT • System quantities hidden • Real-time duration tracked
                </div>
                <a href="{{ route('cycle_count.index') }}" onclick="if(document.referrer && document.referrer.indexOf(window.location.host) !== -1 && document.referrer !== window.location.href) { history.back(); return false; }" class="top-strip-back-btn" title="Back to Schedule">
                    <i class="fas fa-arrow-left"></i> <span>Back</span>
                </a>
            </div>
        </div>

        {{-- Sub-header Bar --}}
        <div class="img3-sub-bar">
            <div class="img3-sub-meta">
                <span>Location <strong>{{ $cycleCount->location ? $cycleCount->location->name : ($cycleCount->location_name ?: 'General') }}</strong></span>
                <span>Assigned to <strong>[{{ $cycleCount->counter ? $cycleCount->counter->name : 'Staff' }}]</strong></span>
                <span>Date <strong>{{ $cycleCount->count_date ? $cycleCount->count_date->format('d M Y') : date('d M Y') }}</strong></span>
                <span>Items <strong>{{ $cycleCount->total_items }}</strong></span>
                <span class="img3-sub-entered" id="enteredProgressText">Entered {{ $cycleCount->entered_items }} / {{ $cycleCount->total_items }}</span>
            </div>

            {{-- Timer Section --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="timer-strip-box">
                    <span style="font-size:0.75rem; color:#64748b; font-weight:600;"><i class="fas fa-stopwatch text-primary"></i> DURATION:</span>
                    <span class="stopwatch-digital {{ $cycleCount->started_at ? 'running' : '' }}" id="stopwatchDisplay">00:00:00</span>
                    @if(!$cycleCount->started_at && $cycleCount->status === 'not_started')
                        <button type="button" class="btn-start-timer" id="btnStartCountTimer" onclick="startTimerNow()">
                            <i class="fas fa-play"></i> Start Count
                        </button>
                    @endif
                </div>

                <div class="blind-yellow-badge">
                    <i class="fas fa-shield-alt text-warning"></i> Count what you see physically. System quantity is hidden.
                </div>
            </div>
        </div>

        @php
            $totalCount = $items->count();
            $midPoint = ceil($totalCount / 2);
            $leftItems = $items->slice(0, $midPoint);
            $rightItems = $items->slice($midPoint);
        @endphp

        {{-- ========================================================
             MOBILE ANDROID ERP COUNTING APP (Exact PDF Reference Match)
             ======================================================== --}}
        <div class="mobile-count-sheet-app">
            {{-- 1. Exact PDF Top App Bar --}}
            <div class="m-sheet-header">
                <div class="m-sheet-top-left">
                    <a href="{{ route('cycle_count.index') }}" class="m-sheet-back-btn" aria-label="Back to counts">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="m-sheet-title-box">
                        <h1 class="m-sheet-brand-name">Count Sheet</h1>
                        <span class="m-sheet-portal-label">Blind Physical Inventory</span>
                    </div>
                </div>
                <div class="m-sheet-top-right">
                    <a href="{{ route('notifications.index') }}" class="m-sheet-bell-btn" title="Notifications" aria-label="Notifications">
                        <i class="far fa-bell"></i>
                    </a>
                    <div class="m-sheet-avatar-btn" title="{{ Auth::user()->name ?? 'User' }}">
                        {{ strtoupper(substr(Auth::user()->name ?? 'F', 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="m-sheet-body">
                {{-- 2. Time Elapsed, Progress & Blind Notice Card --}}
                <div class="m-sticky-timer-card">
                    <div class="m-timer-row">
                        <div class="m-timer-left">
                            <span class="m-timer-label">TIME ELAPSED</span>
                            <span class="m-timer-display {{ $cycleCount->started_at ? 'running' : '' }}" id="mStopwatchDisplay">00:00:00</span>
                        </div>
                        @if(!$cycleCount->started_at && $cycleCount->status === 'not_started')
                            <button type="button" class="m-btn-start-timer" id="mBtnStartCountTimer" onclick="startTimerNow()">
                                <i class="fas fa-play" style="font-size: 11px;"></i> Start Count
                            </button>
                        @endif
                    </div>

                    <div class="m-progress-meta-row">
                        <span>Items counted</span>
                        <span class="m-prog-val">
                            <span id="mEnteredCount">{{ $cycleCount->entered_items }}</span> / {{ $cycleCount->total_items }}
                            <span id="mEnteredPercent">{{ round(($cycleCount->entered_items / max(1, $cycleCount->total_items)) * 100) }}%</span>
                        </span>
                    </div>
                    <div class="m-progress-track">
                        <div class="m-progress-fill" id="mProgressBar" style="width: {{ round(($cycleCount->entered_items / max(1, $cycleCount->total_items)) * 100) }}%;"></div>
                    </div>

                    <div class="m-blind-notice-pill">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                        </svg>
                        <span>Blind audit. Count what is on the shelf; system stock is hidden.</span>
                    </div>
                </div>

                {{-- 3. Search & Barcode Input Pill --}}
                <div class="m-search-box">
                    <div class="m-search-input-wrap">
                        <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="mQuickScannerInput" class="m-search-input" placeholder="Search item or barcode" oninput="handleMobileSearch(this.value)">
                        </div>
                        <div class="m-scanner-target-icon" onclick="document.getElementById('mQuickScannerInput').focus();" title="Scan Barcode">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                <line x1="7" y1="8" x2="7" y2="16"></line>
                                <line x1="10" y1="8" x2="10" y2="16"></line>
                                <line x1="13" y1="8" x2="13" y2="16"></line>
                                <line x1="17" y1="8" x2="17" y2="16"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- 4. Filter Chips --}}
                <div class="m-filter-chips">
                    <button type="button" class="m-chip-btn active" data-filter="all" onclick="filterMobileSheetItems('all', this)">
                        All {{ $totalCount }}
                    </button>
                    <button type="button" class="m-chip-btn" data-filter="uncounted" onclick="filterMobileSheetItems('uncounted', this)">
                        Uncounted <span id="mUncountedChip">{{ max(0, $cycleCount->total_items - $cycleCount->entered_items) }}</span>
                    </button>
                    <button type="button" class="m-chip-btn" data-filter="counted" onclick="filterMobileSheetItems('counted', this)">
                        Counted <span id="mCountedChip">{{ $cycleCount->entered_items }}</span>
                    </button>
                </div>

                {{-- 5. Items List --}}
                <div class="m-items-list" id="mItemsList">
                    @forelse($items as $idx => $it)
                        @php
                            $isEntered = $it->is_entered || ($it->counted_qty !== null && $it->counted_qty !== '');
                            $uniqueCode = $it->p_code ?: ($it->item_code ?: 'B');
                            $catName = $it->category_name ?: 'General';
                            $barcodeNum = $it->barcode_path ?: ($it->item_code ?: '');
                        @endphp
                        <div class="m-sheet-card {{ $isEntered ? 'is-counted' : '' }}"
                             id="m-card-{{ $it->id }}"
                             data-item-id="{{ $it->id }}"
                             data-is-counted="{{ $isEntered ? '1' : '0' }}"
                             data-barcode="{{ strtolower($it->barcode_path ?? '') }}"
                             data-code="{{ strtolower($uniqueCode) }}"
                             data-name="{{ strtolower($it->item_name) }}"
                             data-search="{{ strtolower(($it->barcode_path ?? '') . ' ' . $uniqueCode . ' ' . $it->item_name) }}">

                            <div class="m-card-header">
                                <div class="d-flex align-items-center gap-2 overflow-hidden flex-grow-1">
                                    <div class="m-thumb-wrap" onclick="openImageModal('{{ $it->image_path ? '/uploads/products/' . $it->image_path : '' }}', '{{ addslashes($it->item_name) }}')">
                                        @if($it->image_path)
                                            <img src="/uploads/products/{{ $it->image_path }}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'far fa-image\' style=\'color: #0284c7; font-size: 18px;\'></i>';">
                                        @else
                                            <i class="far fa-image" style="color: #0284c7; font-size: 18px;"></i>
                                        @endif
                                    </div>
                                    <div class="m-item-title-col">
                                        <h6 class="m-item-name" title="{{ $it->item_name }}">{{ $it->item_name }}</h6>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="m-code-badge">{{ $uniqueCode }}</span>
                                            <span class="m-cat-badge">{{ $catName }}</span>
                                            @if($barcodeNum)
                                                <span class="m-barcode-badge"><i class="fas fa-barcode"></i> {{ $barcodeNum }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="m-status-wrap">
                                    <span class="m-counted-pill {{ $isEntered ? 'is-counted' : '' }}" id="m-pill-{{ $it->id }}">
                                        {{ $isEntered ? 'Counted' : 'Pending' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Stepper & Unit Row --}}
                            <div class="m-counter-section">
                                <div class="m-unit-col">
                                    <span class="m-unit-tag">UNIT</span>
                                    <span class="m-unit-text">Single / Loose &bull; {{ $it->unit ?: '1 pc' }}</span>
                                </div>

                                <div class="m-stepper-box">
                                    <button type="button" class="m-btn-step m-btn-minus" onclick="stepMobileQty({{ $it->id }}, -1)" aria-label="Decrease quantity">
                                        &ndash;
                                    </button>

                                    <input type="number"
                                           step="any"
                                           min="0"
                                           inputmode="decimal"
                                           pattern="[0-9]*"
                                           class="m-input-qty"
                                           id="m-input-{{ $it->id }}"
                                           data-item-id="{{ $it->id }}"
                                           value="{{ $it->counted_qty !== null ? (float)$it->counted_qty : '0' }}"
                                           placeholder="0"
                                           {{ !$cycleCount->started_at && $cycleCount->status === 'not_started' ? 'readonly' : '' }}
                                           onclick="handleQtyInputClick(this)"
                                           onfocus="handleQtyInputFocus(this, {{ $it->id }})"
                                           onblur="saveCountedQtyMobile(this);">

                                    <button type="button" class="m-btn-step m-btn-plus" onclick="stepMobileQty({{ $it->id }}, 1)" aria-label="Increase quantity">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted bg-white rounded-3 p-3">
                            No items assigned in this cycle count.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 5. Exact PDF Sticky Bottom Action Bar --}}
        <div class="m-sticky-bottom-bar">
            <div class="m-bottom-summary">
                <span class="m-bottom-title">Counted</span>
                <span class="m-bottom-count" id="mStickyEntered">{{ $cycleCount->entered_items }} / {{ $cycleCount->total_items }}</span>
                <span class="m-bottom-sub">Auto-saves as you type</span>
            </div>

            <button type="button" class="m-btn-submit-sheet" id="mBtnSubmitSheet" onclick="document.getElementById('btnSubmitSheetCount').click()">
                <i class="fas fa-paper-plane" style="font-size: 13px;"></i> Submit Count
            </button>
        </div>

        {{-- Lightbox Photo Modal --}}
        <div class="modal fade" id="mImageModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header py-2 px-3 border-0 bg-dark text-white d-flex align-items-center justify-content-between">
                        <span class="modal-title font-weight-bold small text-truncate" id="mImageModalTitle">Product Image</span>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-0 text-center bg-light" style="min-height: 220px; display: flex; align-items: center; justify-content: center;">
                        <img id="mImageModalSrc" src="" alt="Product Photo" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2-Column Side-by-Side Split Grid --}}
        <div class="img3-split-grid">
            {{-- Left Column (Items 1 to Mid) --}}
            <div class="img3-half-col">
                <table class="blind-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th style="width: 34px;">IMG</th>
                            <th>ITEM UNIQUE</th>
                            <th>PRODUCT &amp; VARIANT</th>
                            <th>BARCODE</th>
                            <th>UNIT</th>
                            <th>COUNTED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leftItems as $idx => $it)
                            <tr id="tr-item-{{ $it->id }}" data-barcode="{{ strtolower($it->barcode_path ?? '') }}" data-code="{{ strtolower($it->p_code ?: $it->item_code) }}" data-name="{{ strtolower($it->item_name) }}">
                                <td class="col-idx">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="col-img-wrap">
                                        @if($it->image_path)
                                            <img src="/uploads/products/{{ $it->image_path }}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'far fa-image\'></i>';">
                                        @else
                                            <i class="far fa-image"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="col-unique">{{ $it->p_code ?: ($it->item_code ?: 'ITEM-' . ($idx + 1)) }}</span>
                                </td>
                                <td>
                                    <div class="col-name-box">
                                        <span class="col-name-title" title="{{ $it->item_name }}">{{ $it->item_name }}</span>
                                        @if($it->category_name)
                                            <span class="col-cat-badge">{{ $it->category_name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-barcode">
                                    @if($it->barcode_path)
                                        <i class="fas fa-barcode text-muted me-1"></i>{{ $it->barcode_path }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="col-unit">{{ $it->unit ?: 'Pcs' }}</td>
                                <td>
                                    <div class="count-input-wrap">
                                        <input type="number" step="any" min="0"
                                               class="count-box-input"
                                               data-item-id="{{ $it->id }}"
                                               value="{{ $it->counted_qty !== null ? (float)$it->counted_qty : '' }}"
                                               placeholder="—"
                                               {{ !$cycleCount->started_at && $cycleCount->status === 'not_started' ? 'readonly' : '' }}
                                               onclick="handleQtyInputClick(this)"
                                               onfocus="handleDesktopInputFocus(this, {{ $it->id }})"
                                               onblur="unhighlightRow({{ $it->id }}); saveCountedQty(this);">
                                        <span class="count-check-icon {{ $it->is_entered ? 'visible' : '' }}" id="check-icon-{{ $it->id }}">✓</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No items in this column.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Right Column (Items Mid+1 to End) --}}
            <div class="img3-half-col">
                <table class="blind-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th style="width: 34px;">IMG</th>
                            <th>ITEM UNIQUE</th>
                            <th>PRODUCT &amp; VARIANT</th>
                            <th>BARCODE</th>
                            <th>UNIT</th>
                            <th>COUNTED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rightItems as $idx => $it)
                            <tr id="tr-item-{{ $it->id }}" data-barcode="{{ strtolower($it->barcode_path ?? '') }}" data-code="{{ strtolower($it->p_code ?: $it->item_code) }}" data-name="{{ strtolower($it->item_name) }}">
                                <td class="col-idx">{{ $midPoint + $idx + 1 }}</td>
                                <td>
                                    <div class="col-img-wrap">
                                        @if($it->image_path)
                                            <img src="/uploads/products/{{ $it->image_path }}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'far fa-image\'></i>';">
                                        @else
                                            <i class="far fa-image"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="col-unique">{{ $it->p_code ?: ($it->item_code ?: 'ITEM-' . ($midPoint + $idx + 1)) }}</span>
                                </td>
                                <td>
                                    <div class="col-name-box">
                                        <span class="col-name-title" title="{{ $it->item_name }}">{{ $it->item_name }}</span>
                                        @if($it->category_name)
                                            <span class="col-cat-badge">{{ $it->category_name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-barcode">
                                    @if($it->barcode_path)
                                        <i class="fas fa-barcode text-muted me-1"></i>{{ $it->barcode_path }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="col-unit">{{ $it->unit ?: 'Pcs' }}</td>
                                <td>
                                    <div class="count-input-wrap">
                                        <input type="number" step="any" min="0"
                                               class="count-box-input"
                                               data-item-id="{{ $it->id }}"
                                               value="{{ $it->counted_qty !== null ? (float)$it->counted_qty : '' }}"
                                               placeholder="—"
                                               {{ !$cycleCount->started_at && $cycleCount->status === 'not_started' ? 'readonly' : '' }}
                                               onclick="handleQtyInputClick(this)"
                                               onfocus="handleDesktopInputFocus(this, {{ $it->id }})"
                                               onblur="unhighlightRow({{ $it->id }}); saveCountedQty(this);">
                                        <span class="count-check-icon {{ $it->is_entered ? 'visible' : '' }}" id="check-icon-{{ $it->id }}">✓</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No items in this column.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Action Toolbar --}}
        <div class="img3-bottom-bar">
            <div class="search-scanner-box">
                <i class="fas fa-barcode text-secondary"></i>
                <span>Search / Scan Barcode:</span>
                <input type="text" id="quickScannerInput" class="search-scanner-input" placeholder="Type barcode, code or name...">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-sheet-draft" onclick="alert('Progress is automatically saved as you enter each count.')">Save draft</button>
                <button type="button" class="btn-sheet-submit" id="btnSubmitSheetCount">
                    Submit count (<span id="uncountedNumber">{{ max(0, $cycleCount->total_items - $cycleCount->entered_items) }}</span> left)
                </button>
            </div>
        </div>

        {{-- Bottom 4 Explanatory Boxes --}}
        <div class="img3-bottom-grid">
            <div class="img3-box">
                <h6>No system quantity</h6>
                <p>No "System qty" column, tooltip or total anywhere on this screen — before, during or after typing.</p>
            </div>
            <div class="img3-box">
                <h6>After Submit</h6>
                <p>Counter sees only "Submitted". No match / mismatch result is shown to the counter.</p>
            </div>
            <div class="img3-box">
                <h6>Live Timer &amp; Autosave</h6>
                <p>Each entered quantity is saved at once; the timer tracks exact duration for owner review.</p>
            </div>
            <div class="img3-box">
                <h6>Barcode Scanning</h6>
                <p>Scan barcode sticker on bin/shelf to jump directly to the item and enter physical count.</p>
            </div>
        </div>
    </div>
</div>

<script>
const countId = {{ $cycleCount->id }};
const totalItems = {{ $cycleCount->total_items }};
let enteredItems = {{ $cycleCount->entered_items }};
const csrfToken = '{{ csrf_token() }}';

// ── Stopwatch Timer Implementation ──
let stopwatchInterval = null;
let startedAtTimestamp = {{ $cycleCount->started_at ? $cycleCount->started_at->timestamp * 1000 : 'null' }};
const isStarted = {{ $cycleCount->started_at ? 'true' : 'false' }};

function formatStopwatch(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
}

function updateStopwatchDisplay() {
    if (!startedAtTimestamp) return;
    const now = Date.now();
    const elapsedSeconds = Math.max(0, Math.floor((now - startedAtTimestamp) / 1000));
    const formatted = formatStopwatch(elapsedSeconds);

    const el = document.getElementById('stopwatchDisplay');
    if (el) {
        el.textContent = formatted;
        el.classList.add('running');
    }

    const mEl = document.getElementById('mStopwatchDisplay');
    if (mEl) {
        mEl.textContent = formatted;
        mEl.classList.add('running');
    }
}

if (isStarted && startedAtTimestamp) {
    updateStopwatchDisplay();
    stopwatchInterval = setInterval(updateStopwatchDisplay, 1000);
}

function startTimerNow() {
    const btn = document.getElementById('btnStartCountTimer');
    const mBtn = document.getElementById('mBtnStartCountTimer');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
    }
    if (mBtn) {
        mBtn.disabled = true;
        mBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
    }

    fetch('{{ route("cycle_count.start_timer", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            startedAtTimestamp = new Date(data.started_at).getTime();
            if (btn) btn.remove();
            if (mBtn) mBtn.remove();
            updateStopwatchDisplay();
            if (!stopwatchInterval) {
                stopwatchInterval = setInterval(updateStopwatchDisplay, 1000);
            }
            unlockInputsForCounting();
        } else {
            alert('Could not start timer: ' + (data.message || 'Error'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Start Count';
            }
            if (mBtn) {
                mBtn.disabled = false;
                mBtn.innerHTML = '<i class="fas fa-play"></i> Start Count';
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play"></i> Start Count';
        }
        if (mBtn) {
            mBtn.disabled = false;
            mBtn.innerHTML = '<i class="fas fa-play"></i> Start Count';
        }
    });
}

function highlightRow(id) {
    document.querySelectorAll('.blind-table tbody tr').forEach(r => r.classList.remove('row-active'));
    const tr = document.getElementById('tr-item-' + id);
    if (tr) tr.classList.add('row-active');
}

function unhighlightRow(id) {
    // Keep clean
}

function highlightMobileCard(id) {
    document.querySelectorAll('.m-sheet-card').forEach(c => c.classList.remove('is-active-focus'));
    const card = document.getElementById('m-card-' + id);
    if (card) card.classList.add('is-active-focus');
}

function isCountStarted() {
    return (startedAtTimestamp !== null && startedAtTimestamp > 0);
}

let toastTimer = null;
function showStartCountPrompt() {
    const toast = document.getElementById('mStartCountToast');
    if (toast) {
        toast.style.display = 'block';
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            hideStartCountPrompt();
        }, 4000);
    }

    // Scroll to and pulse the Start Count button
    const mBtn = document.getElementById('mBtnStartCountTimer');
    const btn = document.getElementById('btnStartCountTimer');
    const activeBtn = (window.innerWidth < 992) ? mBtn : (btn || mBtn);

    if (activeBtn) {
        activeBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        activeBtn.classList.remove('pulse-highlight');
        void activeBtn.offsetWidth; // Reflow to re-trigger CSS keyframes
        activeBtn.classList.add('pulse-highlight');
    }
}

function hideStartCountPrompt() {
    const toast = document.getElementById('mStartCountToast');
    if (toast) toast.style.display = 'none';
}

function handleQtyInputClick(inputEl) {
    if (!isCountStarted()) {
        inputEl.blur();
        showStartCountPrompt();
        return false;
    }
}

function handleQtyInputFocus(inputEl, itemId) {
    if (!isCountStarted()) {
        inputEl.blur();
        showStartCountPrompt();
        return false;
    }
    highlightMobileCard(itemId);
}

function handleDesktopInputFocus(inputEl, itemId) {
    if (!isCountStarted()) {
        inputEl.blur();
        showStartCountPrompt();
        return false;
    }
    highlightRow(itemId);
}

function unlockInputsForCounting() {
    hideStartCountPrompt();
    document.querySelectorAll('.m-input-qty, .count-box-input').forEach(inp => {
        inp.removeAttribute('readonly');
    });
}

function stepMobileQty(itemId, delta) {
    if (!isCountStarted()) {
        showStartCountPrompt();
        return;
    }

    const inputEl = document.getElementById('m-input-' + itemId);
    if (!inputEl) return;
    let cur = parseFloat(inputEl.value);
    if (isNaN(cur)) cur = 0;
    let next = Math.max(0, cur + delta);
    inputEl.value = next;

    // Sync to desktop input if exists
    const desktopInput = document.querySelector(`.blind-table .count-box-input[data-item-id="${itemId}"]`);
    if (desktopInput) desktopInput.value = next;

    highlightMobileCard(itemId);
    saveCountedQty(inputEl);
}

function saveCountedQtyMobile(inputEl) {
    if (!isCountStarted()) {
        showStartCountPrompt();
        return;
    }

    const itemId = inputEl.getAttribute('data-item-id');
    const val = inputEl.value.trim();

    // Sync to desktop input
    const desktopInput = document.querySelector(`.blind-table .count-box-input[data-item-id="${itemId}"]`);
    if (desktopInput) desktopInput.value = val;

    saveCountedQty(inputEl);
}

function saveCountedQty(inputEl) {
    if (!isCountStarted()) {
        showStartCountPrompt();
        return;
    }

    const itemId = inputEl.getAttribute('data-item-id');
    const val = inputEl.value.trim();
    const checkIcon = document.getElementById('check-icon-' + itemId);

    fetch('{{ route("cycle_count.save_item", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            item_id: itemId,
            counted_qty: val
        })
    })
    .then(res => {
        if (res.status === 403) {
            return res.json().then(data => {
                showStartCountPrompt();
                throw new Error(data.message || 'Counter not started');
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Desktop check indicator
            if (checkIcon) {
                if (data.is_entered) {
                    checkIcon.classList.add('visible');
                } else {
                    checkIcon.classList.remove('visible');
                }
            }

            // Mobile card state update
            const mCard = document.getElementById('m-card-' + itemId);
            const mPill = document.getElementById('m-pill-' + itemId);
            if (mCard) {
                if (data.is_entered) {
                    mCard.classList.add('is-counted');
                    mCard.setAttribute('data-is-counted', '1');
                    if (mPill) {
                        mPill.classList.add('is-counted');
                        mPill.textContent = 'Counted';
                    }
                } else {
                    mCard.classList.remove('is-counted');
                    mCard.setAttribute('data-is-counted', '0');
                    if (mPill) {
                        mPill.classList.remove('is-counted');
                        mPill.textContent = 'Pending';
                    }
                }
            }

            enteredItems = data.entered_items;
            const uncounted = Math.max(0, totalItems - enteredItems);
            const percent = Math.round((enteredItems / Math.max(1, totalItems)) * 100);

            // Update Desktop counters
            const elEnteredText = document.getElementById('enteredProgressText');
            if (elEnteredText) elEnteredText.textContent = 'Entered ' + enteredItems + ' / ' + totalItems;
            const elUncounted = document.getElementById('uncountedNumber');
            if (elUncounted) elUncounted.textContent = uncounted;

            // Update Mobile counters & progress bar
            const mEnt = document.getElementById('mEnteredCount');
            if (mEnt) mEnt.textContent = enteredItems;
            const mPct = document.getElementById('mEnteredPercent');
            if (mPct) mPct.textContent = percent + '%';
            const mBar = document.getElementById('mProgressBar');
            if (mBar) mBar.style.width = percent + '%';
            const mStick = document.getElementById('mStickyEntered');
            if (mStick) mStick.textContent = enteredItems + ' / ' + totalItems;
            const mUncChip = document.getElementById('mUncountedChip');
            if (mUncChip) mUncChip.textContent = uncounted;
            const mCntChip = document.getElementById('mCountedChip');
            if (mCntChip) mCntChip.textContent = enteredItems;
        }
    })
    .catch(err => console.error(err));
}

// Mobile Filter Chips (All, Uncounted, Counted)
let currentMobileSheetFilter = 'all';

function filterMobileSheetItems(type, btnEl) {
    currentMobileSheetFilter = type;
    document.querySelectorAll('.m-chip-btn').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');

    applyMobileSheetFilter();
}

function handleMobileSearch(q) {
    const clearBtn = document.getElementById('mSearchClearBtn');
    if (clearBtn) {
        clearBtn.style.display = q && q.length > 0 ? 'block' : 'none';
    }
    applyMobileSheetFilter();
}

function clearMobileItemSearch() {
    const inp = document.getElementById('mQuickScannerInput');
    if (inp) inp.value = '';
    const clearBtn = document.getElementById('mSearchClearBtn');
    if (clearBtn) clearBtn.style.display = 'none';
    applyMobileSheetFilter();
}

function applyMobileSheetFilter() {
    const query = (document.getElementById('mQuickScannerInput')?.value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.m-sheet-card');

    cards.forEach(card => {
        const isCounted = card.getAttribute('data-is-counted') === '1';
        const searchData = card.getAttribute('data-search') || '';

        let matchesFilter = true;
        if (currentMobileSheetFilter === 'uncounted') {
            matchesFilter = !isCounted;
        } else if (currentMobileSheetFilter === 'counted') {
            matchesFilter = isCounted;
        }

        let matchesQuery = true;
        if (query) {
            matchesQuery = searchData.indexOf(query) !== -1;
        }

        if (matchesFilter && matchesQuery) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // If query matches exact barcode/code, scroll to and highlight first match
    if (query && query.length >= 2) {
        const firstMatch = document.querySelector(`.m-sheet-card[data-barcode="${query}"], .m-sheet-card[data-code="${query}"]`);
        if (firstMatch && firstMatch.style.display !== 'none') {
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstMatch.classList.add('is-active-focus');
            if (isCountStarted()) {
                const inp = firstMatch.querySelector('.m-input-qty');
                if (inp) inp.focus();
            } else {
                showStartCountPrompt();
            }
        }
    }
}

// Lightbox Modal for Photo Preview
function openImageModal(imgSrc, title) {
    if (!imgSrc) return;
    const modalEl = document.getElementById('mImageModal');
    const modalSrc = document.getElementById('mImageModalSrc');
    const modalTitle = document.getElementById('mImageModalTitle');
    if (modalSrc) modalSrc.src = imgSrc;
    if (modalTitle) modalTitle.textContent = title || 'Product Image';
    if (typeof $ !== 'undefined' && $('#mImageModal').modal) {
        $('#mImageModal').modal('show');
    }
}

// Quick Search / Barcode Scanner input
document.getElementById('quickScannerInput').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    if (q.length < 2) return;

    const rows = document.querySelectorAll('.blind-table tbody tr');
    for (let r of rows) {
        const barcode = r.getAttribute('data-barcode') || '';
        const code = r.getAttribute('data-code') || '';
        const name = r.getAttribute('data-name') || '';

        if (barcode === q || code === q || barcode.includes(q) || code.includes(q) || name.includes(q) || r.innerText.toLowerCase().includes(q)) {
            r.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelectorAll('.blind-table tbody tr').forEach(row => row.classList.remove('row-active'));
            r.classList.add('row-active');
            if (isCountStarted()) {
                const inp = r.querySelector('.count-box-input');
                if (inp) {
                    inp.focus();
                    inp.select();
                }
            } else {
                showStartCountPrompt();
            }
            break;
        }
    }
});

// Final Submit
document.getElementById('btnSubmitSheetCount').addEventListener('click', function () {
    const left = Math.max(0, totalItems - enteredItems);
    let msg = 'Submit count sheet for approval?';
    if (left > 0) {
        msg = 'You have ' + left + ' unentered item(s). Submit count sheet anyway?';
    }
    if (!confirm(msg)) return;

    this.disabled = true;
    this.textContent = 'Submitting...';

    if (stopwatchInterval) {
        clearInterval(stopwatchInterval);
    }

    fetch('{{ route("cycle_count.submit", $cycleCount->id) }}', {
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
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + data.message);
            this.disabled = false;
        }
    })
    .catch(err => {
        alert('Connection error submitting count.');
        this.disabled = false;
    });
});
</script>
@endsection
