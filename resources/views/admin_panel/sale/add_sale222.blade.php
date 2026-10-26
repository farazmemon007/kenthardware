@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS POS "SALES RECEIPT" PIXEL-PERFECT REPLICA
       ========================================================== */
    html, body {
        overflow: hidden !important;
        height: 100vh !important;
        max-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #090e1a !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    .container-scroller {
        height: 100vh !important;
        max-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .rt_nav_header {
        flex: 0 0 auto !important;
        margin-bottom: 0 !important;
    }

    footer {
        display: none !important;
    }

    .app-page-body, .main-content, .content-wrapper, .main-panel {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        max-height: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
    }

    /* Fullscreen Mode (toggles off ERP navbar for true 100% full screen) */
    body.qb-fullscreen-mode .rt_nav_header {
        display: none !important;
    }
    body.qb-fullscreen-mode .app-page-body {
        height: 100vh !important;
        max-height: 100vh !important;
    }

    /* Outer Window Container: Occupies full remaining viewport without borders or empty margins */
    .qb-window {
        flex: 1 1 0% !important;
        display: flex !important;
        flex-direction: column !important;
        min-height: 0 !important;
        height: 100% !important;
        max-height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: hidden !important;
    }

    /* 1. Top Window Title Bar (Navy Blue / Odoo POS Style Multi-Order Strip) */
    .qb-title-bar {
        height: 34px !important;
        min-height: 34px !important;
        flex: 0 0 34px !important;
        background: linear-gradient(180deg, #18385e 0%, #102641 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 8px 0 12px;
        user-select: none;
        border-bottom: 1px solid #0a192b;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .qb-title-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .qb-title-tabs::-webkit-scrollbar {
        display: none;
    }

    .qb-title-text {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
        white-space: nowrap;
        margin-right: 2px;
    }

    .qb-title-divider {
        width: 1px;
        height: 18px;
        background: rgba(255, 255, 255, 0.22);
        margin: 0 4px;
        flex-shrink: 0;
    }

    .qb-orders-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #93c5fd;
        white-space: nowrap;
        margin-right: 2px;
    }

    .qb-order-tabs-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    /* Odoo POS Order Pill Button */
    .qb-order-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.24);
        color: #e2ecf8;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
        white-space: nowrap;
        line-height: 1.2;
    }
    .qb-order-tab:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.45);
        transform: translateY(-1px);
    }
    .qb-order-tab.active {
        background: #ffffff !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        border-color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
    }

    /* Item count badge inside tab */
    .qb-order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        background: #0284c7;
        color: #ffffff;
        line-height: 1;
    }
    .qb-order-tab.active .qb-order-badge {
        background: #0f2744;
        color: #ffffff;
    }

    /* Discard / Close cross button */
    .qb-order-tab-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        margin-left: 2px;
        opacity: 0.65;
        transition: all 0.12s ease;
        cursor: pointer;
    }
    .qb-order-tab-close:hover {
        background: #ef4444;
        color: #ffffff !important;
        opacity: 1 !important;
        transform: scale(1.15);
    }

    /* Circular Add Button (+) */
    .qb-btn-add-order {
        width: 22px;
        height: 22px;
        min-width: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00b4b0 0%, #008784 100%);
        border: 1px solid #006b68;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 0;
        flex-shrink: 0;
    }
    .qb-btn-add-order:hover {
        background: linear-gradient(135deg, #00cbca 0%, #009d9a 100%);
        transform: scale(1.12);
        box-shadow: 0 2px 8px rgba(0, 180, 176, 0.5);
    }

    .qb-win-close-btn {
        width: 20px;
        height: 18px;
        background: #c34343;
        border: 1px solid #7a1e1e;
        border-radius: 2px;
        color: #ffffff;
        font-size: 12px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
    }
    .qb-win-close-btn:hover {
        background: #e02424;
        color: #fff;
    }

    .qb-win-tool-btn {
        width: 20px;
        height: 18px;
        background: #234d7c;
        border: 1px solid #123257;
        border-radius: 2px;
        color: #ffffff;
        font-size: 10px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        padding: 0;
        transition: background 0.15s;
    }
    .qb-win-tool-btn:hover {
        background: #3970b1;
        color: #ffffff;
    }

    /* 2. Sub-Header: Search & Actions Strip */
    .qb-subheader-bar {
        height: 38px !important;
        min-height: 38px !important;
        flex: 0 0 38px !important;
        background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%);
        border-bottom: 1px solid #b2c5dc;
        display: flex;
        align-items: center;
        padding: 0 10px;
        gap: 10px;
    }

    /* Green "I Want to... ▼" Button */
    .qb-btn-iwantto {
        background: linear-gradient(180deg, #53af41 0%, #35832a 100%);
        border: 1px solid #286c1f;
        border-radius: 4px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
        user-select: none;
    }
    .qb-btn-iwantto:hover {
        filter: brightness(1.08);
        color: #ffffff;
    }

    /* Input Search Wrappers */
    .qb-search-box-wrap {
        position: relative;
        flex: 1;
        max-width: 420px;
    }

    .qb-search-input {
        width: 100%;
        height: 27px;
        padding: 2px 26px 2px 8px;
        font-size: 12px;
        font-weight: 500;
        color: #1e293b;
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 3px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        outline: none;
    }
    .qb-search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .qb-search-input::placeholder {
        color: #72849a;
        font-style: italic;
    }

    .qb-input-caret {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #4b78aa;
        font-size: 11px;
        pointer-events: auto;
        cursor: pointer;
        z-index: 5;
        padding: 4px;
        transition: color 0.15s ease;
    }
    .qb-input-caret:hover {
        color: #1e3a8a;
    }

    /* Autocomplete Dropdown */
    .qb-autocomplete-dropdown {
        position: absolute;
        top: calc(100% + 2px);
        left: 0;
        right: 0;
        max-height: 320px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #7c98b9;
        border-radius: 4px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
        z-index: 1050;
        display: none;
    }

    .qb-autocomplete-item {
        padding: 6px 10px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qb-autocomplete-item:hover, .qb-autocomplete-item.selected {
        background-color: #eaf2fc;
    }

    /* Odoo Style Customer Picker Modal & Cards */
    .modal-odoo-customer-dialog {
        max-width: 1120px;
    }
    .odoo-cust-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        padding: 4px;
    }
    .odoo-customer-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .odoo-customer-card:hover {
        border-color: #1b4273;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(27, 66, 115, 0.12);
    }
    .odoo-customer-card.is-active-customer {
        border-color: #059669 !important;
        background-color: #f0fdf4 !important;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.25);
    }
    .odoo-card-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0,0,0,0.12);
    }
    .odoo-card-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .odoo-card-meta {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 4px;
    }
    .odoo-card-footer {
        border-top: 1px dashed #e2e8f0;
        margin-top: 10px;
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qb-odoo-customer-btn {
        height: 28px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 0 10px;
        background: #ffffff;
        border: 1px solid #93c5fd;
        color: #1e40af;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    }
    .qb-odoo-customer-btn:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #1d4ed8;
    }
    .qb-odoo-customer-btn kbd {
        background: rgba(30, 64, 175, 0.12);
        color: #1e40af;
        font-size: 9px;
        padding: 1px 4px;
        border-radius: 3px;
        border: 1px solid #bfdbfe;
        margin-left: 2px;
    }

    /* Icon Buttons on Far Right of Subheader */
    .qb-icon-btn {
        width: 26px;
        height: 26px;
        background: linear-gradient(180deg, #5da0e8 0%, #2e71bd 100%);
        border: 1px solid #1c528f;
        border-radius: 3px;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.15);
    }
    .qb-icon-btn:hover {
        filter: brightness(1.1);
        color: #fff;
    }

    /* 3. Customer Status Banner */
    .qb-status-banner {
        height: 26px !important;
        min-height: 26px !important;
        flex: 0 0 26px !important;
        background: #fbfdff;
        border-bottom: 1px solid #cbd7e6;
        padding: 0 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        user-select: none;
    }

    /* Form Wrapper: stretches to fill all vertical space inside window */
    #saleForm {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }

    /* 4. Main Body: Split Sidebar & Grid Table */
    .qb-main-split {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        display: flex !important;
        overflow: hidden !important;
        background: #ffffff;
    }

    /* Left Action Sidebar */
    .qb-left-sidebar {
        width: 156px !important;
        flex: 0 0 156px !important;
        height: 100% !important;
        background: linear-gradient(180deg, #dbe4ee 0%, #cbd8e8 100%);
        border-right: 1px solid #adc1d7;
        padding: 8px 6px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        overflow-y: auto;
    }

    /* Glossy Blue Vertical Button */
    .qb-side-btn {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%);
        border: 1px solid #15457a;
        border-radius: 4px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 2px 3px rgba(0, 0, 0, 0.2);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 11.5px;
        text-align: center;
        padding: 7px 4px;
        cursor: pointer;
        user-select: none;
        line-height: 1.25;
        text-decoration: none;
        display: block;
        width: 100%;
        transition: filter 0.1s ease;
    }
    .qb-side-btn:hover {
        filter: brightness(1.1);
        text-decoration: none;
    }
    .qb-side-btn:active {
        background: linear-gradient(180deg, #195293 0%, #2061a9 50%, #3077c1 100%);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.35);
    }

    /* Right Main Table Area */
    .qb-table-pane {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        min-height: 0 !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        background: #ffffff;
        position: relative;
    }

    .qb-table-scroll {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        overflow-x: auto !important;
        background: #ffffff;
    }

    .qb-table-scroll::-webkit-scrollbar,
    .qb-left-sidebar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .qb-table-scroll::-webkit-scrollbar-track,
    .qb-left-sidebar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .qb-table-scroll::-webkit-scrollbar-thumb,
    .qb-left-sidebar::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 3px;
    }

    /* QuickBooks Table Grid - 100% Full-Width Responsive Across Entire Row */
    .qb-grid-table {
        width: 100% !important;
        min-width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important;
    }

    .qb-grid-table th {
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%);
        border-bottom: 1px solid #aebfd4;
        border-right: 1px solid #c9d7e5;
        color: #193452;
        font-size: 11.5px;
        font-weight: 700;
        padding: 6px 10px;
        text-align: left;
        white-space: nowrap;
        user-select: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .qb-grid-table td {
        padding: 5px 10px;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #edf2f7;
        font-size: 12px;
        color: #1a202c;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
    }

    /* Column Width Proportions & Alignments */
    .qb-grid-table th.col-item-no, .qb-grid-table td.col-item-no { width: 7%; min-width: 70px; }
    .qb-grid-table th.col-item-name, .qb-grid-table td.col-item-name { width: 18%; min-width: 130px; font-weight: 700; }
    .qb-grid-table th.col-vendor-desc, .qb-grid-table td.col-vendor-desc { width: 18%; min-width: 130px; }
    .qb-grid-table th.col-attribute, .qb-grid-table td.col-attribute { width: 10%; min-width: 85px; }
    .qb-grid-table th.col-size, .qb-grid-table td.col-size { width: 9%; min-width: 80px; }
    .qb-grid-table th.col-qty, .qb-grid-table td.col-qty { width: 6%; min-width: 60px; text-align: right; }
    .qb-grid-table th.col-price, .qb-grid-table td.col-price { width: 8%; min-width: 80px; text-align: right; }
    .qb-grid-table th.col-ext-price, .qb-grid-table td.col-ext-price { width: 9%; min-width: 90px; text-align: right; font-weight: 700; }
    .qb-grid-table th.col-sales-desc, .qb-grid-table td.col-sales-desc { width: 15%; min-width: 120px; }

    /* Active Row Highlight: Pastel Mint Green */
    .qb-grid-table tr.qb-active-row td {
        background-color: #d5eed7 !important;
        color: #111827 !important;
    }

    /* Return Row: Bold Red */
    .qb-grid-table tr.qb-return-row td {
        color: #cc0000 !important;
        font-weight: 600;
    }

    /* Sub-text under Return Item / Size */
    .qb-row-subtext {
        font-size: 10.5px;
        margin-top: 2px;
        line-height: 1.2;
    }
    .qb-return-badge {
        color: #cc0000;
        font-weight: 700;
    }
    .qb-pcode-text {
        color: #4b5563;
        font-weight: 600;
        font-family: monospace;
    }

    /* In-Row Action Toolbar Attached to Selected Row */
    .qb-inrow-actionbar {
        background: #d5eed7;
        padding: 4px 8px;
        border-bottom: 1px solid #b7dcbb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .qb-row-btn {
        background: linear-gradient(180deg, #5398e0 0%, #266cb7 100%);
        border: 1px solid #1b508d;
        border-radius: 3px;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 10.5px;
        padding: 2px 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.15);
        user-select: none;
    }
    .qb-row-btn:hover {
        filter: brightness(1.12);
    }
    .qb-row-btn:active {
        background: #1b508d;
    }

    /* 5. Bottom Summary Section (Compact, Space-Saving & Clean Modern POS Style) */
    .qb-summary-wrapper {
        flex: 0 0 auto !important;
        padding: 4px 16px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        min-height: 36px !important;
        gap: 12px !important;
        flex-wrap: wrap !important;
    }

    .qb-summary-stats-strip {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-wrap: wrap !important;
    }
    .qb-stat-chip {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 11.5px !important;
        color: #475569 !important;
    }
    .qb-stat-chip.qb-chip-ret {
        background: #fff5f5 !important;
        border-color: #fecaca !important;
    }
    .qb-stat-lbl {
        font-weight: 500 !important;
        color: #64748b !important;
    }
    .qb-stat-num {
        font-weight: 700 !important;
        color: #0f172a !important;
    }

    .qb-summary-fin-strip {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        flex-wrap: wrap !important;
    }
    .qb-fin-item {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        font-size: 12px !important;
        color: #334155 !important;
    }
    .qb-fin-lbl {
        font-weight: 500 !important;
        color: #64748b !important;
    }
    .qb-fin-val {
        font-weight: 700 !important;
        color: #0f172a !important;
    }
    .qb-fin-total-box {
        display: inline-flex !important;
        align-items: baseline !important;
        gap: 6px !important;
        padding-left: 10px !important;
        margin-left: 4px !important;
        border-left: 2px solid #cbd5e1 !important;
    }
    .qb-fin-total-lbl {
        font-size: 13px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
    }
    .qb-fin-total-val {
        font-size: 16px !important;
        font-weight: 900 !important;
        color: #0f172a !important;
    }

    /* 6. Bottom Payment Tabs Strip */
    .qb-payment-bar {
        height: 38px !important;
        min-height: 38px !important;
        flex: 0 0 38px !important;
        background: linear-gradient(180deg, #dce5f1 0%, #cbd8e8 100%);
        border-top: 1px solid #b2c5dc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 12px;
    }

    .qb-payment-tabs-group {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .qb-pay-tab {
        background: linear-gradient(180deg, #4f96e2 0%, #2566b0 100%);
        border: 1px solid #1a4d87;
        border-radius: 4px;
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        padding: 5px 16px;
        cursor: pointer;
        user-select: none;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    .qb-pay-tab:hover {
        filter: brightness(1.08);
    }
    .qb-pay-tab.active {
        background: linear-gradient(180deg, #74b3f5 0%, #3e88dc 50%, #2974ca 51%, #1f64b4 100%) !important;
        border: 1px solid #14437a;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.25), 0 1px 0 rgba(255, 255, 255, 0.4) !important;
    }
    .qb-pay-tab[data-method="split"] {
        background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%);
        border-color: #0369a1;
    }
    .qb-pay-tab[data-method="credit"] {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        border-color: #b45309;
    }
    .qb-pay-tab[data-method="account"] {
        background: linear-gradient(180deg, #6366f1 0%, #4338ca 100%);
        border-color: #3730a3;
    }

    .qb-amount-due-banner {
        display: flex;
        align-items: baseline;
        gap: 12px;
    }
    .qb-due-label {
        font-size: 18px;
        font-weight: 800;
        color: #cc0000;
        letter-spacing: -0.3px;
    }
    .qb-due-value {
        font-size: 23px;
        font-weight: 800;
        color: #cc0000;
    }

    /* 7. Bottom Dark Slate Command Bar */
    .qb-command-bar {
        height: 42px !important;
        min-height: 42px !important;
        flex: 0 0 42px !important;
        background: linear-gradient(180deg, #273e57 0%, #1c2d40 100%);
        border-top: 1px solid #142231;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 12px;
        gap: 8px;
    }

    .qb-cmd-btn-dark {
        background: linear-gradient(180deg, #3f5d7f 0%, #273d57 100%);
        border: 1px solid #1a2a3e;
        color: #e2eaf4;
        border-radius: 4px;
        padding: 5px 16px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    .qb-cmd-btn-dark:hover {
        filter: brightness(1.15);
        color: #ffffff;
    }

    .qb-cmd-btn-blue {
        background: linear-gradient(180deg, #5398e0 0%, #256bb6 100%);
        border: 1px solid #194f8b;
        color: #ffffff;
        border-radius: 4px;
        padding: 5px 18px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    .qb-cmd-btn-blue:hover {
        filter: brightness(1.12);
        color: #ffffff;
    }
    .qb-cmd-btn-blue:disabled, .qb-cmd-btn-dark:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    /* ==========================================================
       CATALOG SIDE PANEL (SLIDE-IN RIGHT DRAWER - IMAGE 1)
       ========================================================== */
    .qb-catalog-panel {
        position: fixed !important;
        top: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 450px !important;
        max-width: 92vw !important;
        height: 100vh !important;
        background: #f8fafc !important; /* Odoo Clean POS Canvas */
        z-index: 1060 !important;
        box-shadow: -8px 0 30px rgba(15, 23, 42, 0.25) !important;
        display: flex !important;
        flex-direction: column !important;
        transform: translateX(100%) !important;
        transition: transform 0.26s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border-left: 3px solid #714b67 !important; /* Odoo Signature Plum Accent */
    }
    .qb-catalog-panel.open {
        transform: translateX(0) !important;
    }

    .qb-catalog-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(15, 23, 42, 0.45) !important;
        z-index: 1055 !important;
        display: none !important;
        backdrop-filter: blur(2px) !important;
    }
    .qb-catalog-backdrop.open {
        display: block !important;
    }

    /* Odoo Top Bar Catalog Slider Button */
    .qb-catalog-top-toggle-btn {
        height: 28px;
        background: linear-gradient(180deg, #714b67 0%, #53334c 100%);
        color: #ffffff;
        border: 1px solid #3d2337;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 10px;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .qb-catalog-top-toggle-btn:hover {
        background: linear-gradient(180deg, #86597b 0%, #714b67 100%);
        color: #ffffff;
    }
    .qb-catalog-top-toggle-btn kbd {
        background: rgba(0, 0, 0, 0.35);
        color: #f3e8ff;
        font-size: 9px;
        padding: 1px 4px;
        border-radius: 3px;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .qb-catalog-top-toggle-btn.active {
        background: linear-gradient(180deg, #dc2626 0%, #b91c1c 100%);
        border-color: #991b1b;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .qb-banner-toggle-btn {
        background: #ffffff;
        border: 1.5px solid #714b67;
        color: #714b67;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s ease;
    }
    .qb-banner-toggle-btn:hover {
        background: #fdf2f8;
        color: #53334c;
    }
    .qb-banner-toggle-btn.active {
        background: #dc2626;
        border-color: #b91c1c;
        color: #ffffff;
    }

    /* Invisible hover sensor along the right edge of the device screen */
    .qb-right-edge-sensor {
        position: fixed !important;
        top: 0 !important;
        right: 0 !important;
        width: 18px !important;
        height: 100vh !important;
        z-index: 1049 !important;
        background: transparent !important;
        pointer-events: auto !important;
    }
    body.catalog-open .qb-right-edge-sensor {
        display: none !important;
        pointer-events: none !important;
    }

    /* Floating handle on the right edge of the screen */
    .qb-floating-catalog-handle {
        position: fixed !important;
        top: 45% !important;
        right: 0 !important;
        transform: translateY(-50%) translateX(100%) !important;
        z-index: 1050 !important;
        background: linear-gradient(180deg, #714b67 0%, #53334c 100%) !important;
        color: #ffffff !important;
        border: 1px solid #3d2337 !important;
        border-right: none !important;
        border-radius: 8px 0 0 8px !important;
        padding: 14px 9px !important;
        cursor: pointer !important;
        box-shadow: -3px 3px 12px rgba(0, 0, 0, 0.25) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        gap: 8px !important;
        user-select: none !important;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), right 0.28s cubic-bezier(0.16, 1, 0.3, 1), background 0.15s ease, box-shadow 0.25s ease !important;
    }
    .qb-right-edge-sensor:hover + .qb-floating-catalog-handle,
    .qb-floating-catalog-handle:hover,
    .qb-floating-catalog-handle.qb-handle-revealed {
        transform: translateY(-50%) translateX(0) !important;
        box-shadow: -5px 4px 18px rgba(113, 75, 103, 0.45), 0 0 10px rgba(113, 75, 103, 0.3) !important;
    }
    .qb-floating-catalog-handle:hover {
        background: linear-gradient(180deg, #86597b 0%, #714b67 100%) !important;
        padding-left: 12px !important;
        color: #ffffff !important;
    }
    .qb-floating-catalog-handle .qb-handle-label {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        letter-spacing: 1px;
        font-size: 11px;
        font-weight: 800;
        color: #ffffff;
    }
    body.catalog-open .qb-floating-catalog-handle {
        right: 450px !important;
        transform: translateY(-50%) translateX(0) !important;
        background: linear-gradient(180deg, #dc2626 0%, #991b1b 100%) !important;
        border-color: #7f1d1d !important;
    }

    .active-btn-glow {
        box-shadow: 0 0 10px rgba(113, 75, 103, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.6) !important;
        border-color: #a855f7 !important;
    }
    .qb-catalog-header {
        background: linear-gradient(180deg, #714b67 0%, #53334c 100%) !important; /* Odoo Signature Plum */
        color: #ffffff !important;
        padding: 9px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        font-size: 13.5px;
        flex: 0 0 auto;
        border-bottom: 2px solid #3d2337;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }
    .qb-catalog-header-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        font-size: 12px;
        cursor: pointer;
        padding: 3px 10px;
        border-radius: 4px;
        transition: all 0.15s;
    }
    .qb-catalog-header-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.3);
    }
    .qb-cat-tiles-wrapper {
        background: #ffffff !important;
        padding: 8px 10px;
        border-bottom: 1px solid #e2e8f0;
        flex: 0 0 auto;
        max-height: 180px;
        overflow-y: auto;
    }
    .qb-cat-tiles-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
    }
    .qb-cat-tile {
        padding: 8px 6px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #1e293b;
        text-align: center;
        user-select: none;
        line-height: 1.25;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        word-break: break-word;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.15s ease;
    }
    .qb-cat-tile:hover {
        background: #fdf2f8;
        border-color: #714b67;
        color: #714b67;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(113, 75, 103, 0.12);
    }
    .qb-cat-tile.active {
        background: #714b67 !important;
        border-color: #53334c !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(113, 75, 103, 0.35) !important;
    }
    .qb-cat-tile.all-tile {
        background: #f0fdfa;
        color: #0f766e;
        border: 1.5px dashed #0d9488;
        font-weight: 800;
    }
    .qb-cat-tile.all-tile:hover {
        background: #ccfbf1;
        color: #115e59;
        border-color: #0f766e;
    }
    .qb-cat-tile.all-tile.active {
        background: #00a09d !important; /* Odoo Teal */
        border-color: #007977 !important;
        color: #ffffff !important;
        border-style: solid !important;
        box-shadow: 0 3px 8px rgba(0, 160, 157, 0.35) !important;
    }
    .qb-catalog-search-bar {
        padding: 8px 10px;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        flex: 0 0 auto;
        position: relative;
    }
    .qb-catalog-search-bar input {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 32px 6px 10px;
        font-size: 12px;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
    }
    .qb-catalog-search-bar input:focus {
        border-color: #714b67;
        box-shadow: 0 0 0 3px rgba(113, 75, 103, 0.15);
        background: #ffffff;
    }
    .qb-catalog-search-bar i {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 12px;
    }
    /* Catalog Sub-Toolbar: Items Count & Price Toggle Slider */
    .qb-catalog-toolbar {
        padding: 5px 12px;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        flex: 0 0 auto;
        user-select: none;
    }
    .qb-toggle-price-label {
        font-size: 11px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    /* Compact Odoo style toggle switch slider */
    .qb-switch {
        position: relative;
        display: inline-block;
        width: 32px;
        height: 18px;
        margin: 0;
    }
    .qb-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .qb-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
    }
    .qb-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 2px;
        bottom: 2px;
        background-color: #ffffff;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.25);
    }
    .qb-switch input:checked + .qb-slider {
        background-color: #714b67; /* Odoo signature Plum */
    }
    .qb-switch input:checked + .qb-slider:before {
        transform: translateX(14px);
    }
    /* Hide prices state */
    #qbCatalogPanel.hide-prices .qb-card-price {
        display: none !important;
    }
    #qbCatalogPanel.hide-prices .qb-prod-card {
        min-height: 88px !important;
    }
    .qb-prod-grid {
        flex: 1 1 0%;
        min-height: 0;
        overflow-y: auto;
        padding: 10px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        align-content: start;
        background: #f8fafc;
    }
    .qb-prod-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 9px 10px !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        display: flex !important;
        flex-direction: column !important;
        min-height: 116px !important;
        position: relative !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    }
    .qb-prod-card:hover {
        border-color: #714b67 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 14px rgba(113, 75, 103, 0.15) !important;
    }
    .qb-card-top-badge {
        font-size: 10.5px !important;
        font-weight: 700 !important;
        padding: 2px 7px !important;
        border-radius: 4px !important;
        display: inline-flex !important;
        align-items: center !important;
        width: fit-content !important;
        margin-bottom: 0 !important;
        white-space: nowrap !important;
    }
    .qb-card-top-badge.in-stock {
        background: #dcfce7 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
    }
    .qb-card-top-badge.out-stock {
        background: #fee2e2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
    }
    .qb-card-title {
        font-weight: 800 !important;
        color: #000000 !important; /* Pure solid black for maximum visibility */
        font-size: 15.5px !important;
        line-height: 1.25 !important;
        margin: 5px 0 6px 0 !important;
        display: block !important;
        max-height: 42px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        word-break: break-word !important;
        flex: 0 0 auto !important;
        letter-spacing: -0.2px !important;
    }
    .qb-card-sku {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .qb-card-pcode {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        color: #714b67 !important;
        background: #fdf2f8 !important;
        border: 1px solid #fbcfe8 !important;
        padding: 1px 5px !important;
        border-radius: 4px !important;
        letter-spacing: 0.3px !important;
        white-space: nowrap !important;
    }
    .qb-card-price {
        font-weight: 800 !important;
        color: #059669 !important; /* Emerald green price */
        font-size: 13.5px !important;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        margin-top: 2px !important;
    }

    /* ==========================================================
       ZERO ITEM PRICE MODAL (IMAGE 2 REPLICA)
       ========================================================== */
    .qb-zero-price-header {
        background: linear-gradient(180deg, #3264a8 0%, #204a82 100%);
        color: #ffffff;
        padding: 5px 10px;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qb-info-circle-big {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-family: serif;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .qb-modal-spec-table {
        width: 100%;
        font-size: 14.5px !important;
        margin-bottom: 0px !important;
        line-height: 1.3 !important;
    }
    .qb-modal-spec-table td {
        padding: 2px 4px !important;
        vertical-align: middle !important;
    }
    .qb-modal-spec-table td.spec-label {
        width: 34% !important;
        text-align: right !important;
        color: #475569 !important;
        font-weight: 500 !important; /* Clean readable weight - not bold */
        font-size: 14.5px !important; /* Larger readable size */
        padding-right: 14px !important;
        white-space: nowrap !important;
    }
    .qb-modal-spec-table td.spec-value {
        width: 66% !important;
        text-align: left !important;
        color: #0f172a !important;
        font-weight: 700 !important; /* Bold only where needed (values) */
        font-size: 15px !important; /* Larger readable size */
        word-break: break-word !important;
    }
    .qb-zero-price-box {
        background: #ffffff;
        border: 1.5px solid #94a3b8;
        border-radius: 6px;
        padding: 6px 12px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    /* ==========================================================
       INSUFFICIENT QUANTITY MODAL (IMAGE 3 REPLICA)
       ========================================================== */
    .qb-insufficient-header {
        background: linear-gradient(180deg, #1e3a8a 0%, #172554 100%);
        color: #ffffff;
        padding: 6px 12px !important;
        font-weight: 700;
        font-size: 14.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qb-qty-stat-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2.5px 2px !important;
        font-size: 14px !important;
        color: #475569 !important;
        font-weight: 500 !important; /* Regular clean label */
    }
    .qb-qty-stat-line.total-avail {
        border-top: 1.5px solid #cbd5e1 !important;
        margin-top: 2px !important;
        padding-top: 3px !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        color: #0f172a !important;
    }

    /* ==========================================================
       ODOO ENTERPRISE MATRIX VIEW FOR SALE SCREEN (Kent Theme)
       ========================================================== */
    .matrix-radio-pill {
        display: inline-flex !important;
        align-items: center !important;
        padding: 6px 14px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        cursor: pointer !important;
        margin: 0 !important;
        transition: all 0.15s ease !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        user-select: none !important;
    }
    .matrix-radio-pill:hover {
        border-color: #2563eb !important;
        background: #f8fafc !important;
    }
    .matrix-radio-pill.selected-pill {
        border-color: #2563eb !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
    }
    .matrix-radio-pill.selected-pill .matrix-attr-val {
        color: #1e40af !important;
        font-weight: 700 !important;
    }
    .matrix-radio-pill.disabled-pill {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    .matrix-radio-pill.disabled-pill .matrix-attr-val {
        color: #94a3b8 !important;
    }

    /* Shift Matrix Modal slightly left without breaking Bootstrap's smooth fade/slide animation */
    .modal-variant-picker-dialog {
        max-width: 830px !important;
        position: relative !important;
        left: -70px !important;
    }
    @media (max-width: 1200px) {
        .modal-variant-picker-dialog {
            max-width: 760px !important;
            left: -50px !important;
        }
    }

    .matrix-kpi-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 9px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 14px;
        gap: 12px;
    }
    .matrix-kpi-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
    .matrix-kpi-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 12px;
    }
    .matrix-kpi-item:first-child {
        padding-left: 0;
    }
    .matrix-kpi-divider {
        width: 1px;
        height: 28px;
        background: #cbd5e1;
        margin: 0 4px;
        flex-shrink: 0;
    }
    .matrix-kpi-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        margin-bottom: 2px;
        white-space: nowrap;
    }
    .matrix-kpi-value {
        font-size: 17px;
        font-weight: 800;
        line-height: 1.2;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }

    /* Customize Columns Modal Card Styles (Matching Product Create ERP UI) */
    .col-toggle-card {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 9px 12px !important;
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        cursor: pointer !important;
        user-select: none !important;
        transition: all 0.15s ease-in-out !important;
        margin-bottom: 0 !important;
        position: relative !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .col-toggle-card * {
        pointer-events: none !important;
    }
    .col-toggle-card:hover {
        border-color: #4f46e5 !important;
        background: #f8fafc !important;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.1) !important;
    }
    .col-toggle-card.is-active {
        border-color: #4f46e5 !important;
        background: #f5f3ff !important;
    }
    .col-toggle-checkbox {
        width: 18px !important;
        height: 18px !important;
        border-radius: 4px !important;
        border: 1.5px solid #94a3b8 !important;
        background: #ffffff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        transition: all 0.15s ease-in-out !important;
        color: #ffffff !important;
        font-size: 10px !important;
    }
    .col-toggle-checkbox i {
        display: none !important;
    }
    .col-toggle-card.is-active .col-toggle-checkbox {
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
    }
    .col-toggle-card.is-active .col-toggle-checkbox i {
        display: inline-block !important;
    }
    .col-toggle-card .col-toggle-label {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        line-height: 1.2 !important;
        flex-grow: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .col-toggle-card.is-active .col-toggle-label {
        color: #312e81 !important;
    }

    /* ==============================================================
       QUICKBOOKS & ODOO SPLIT PAYMENT MODAL PREMIUM STYLING
       ============================================================== */
    #modalSelectPaymentAccount .modal-content {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #0f2744;
        box-shadow: 0 25px 50px -12px rgba(15, 39, 68, 0.45);
    }
    #modalSelectPaymentAccount .modal-header {
        background: linear-gradient(180deg, #18385e 0%, #0f2744 100%);
        border-bottom: 2px solid #0077c5;
        padding: 10px 18px;
    }
    .qb-split-hero-card {
        background: linear-gradient(135deg, #0e2238 0%, #17375a 100%);
        border-radius: 8px;
        padding: 12px 18px;
        color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.15);
    }
    .qb-hero-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #93c5fd;
    }
    .qb-hero-amount {
        font-size: 26px;
        font-weight: 800;
        color: #facc15;
        font-family: 'Consolas', 'Courier New', monospace;
        line-height: 1.1;
    }
    .qb-hero-cust-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 4px;
    }
    .qb-hero-bal-val {
        font-size: 13px;
        font-weight: 700;
        color: #f87171;
        font-family: 'Consolas', monospace;
    }

    /* Preset Buttons */
    .qb-split-presets-ribbon {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        background: #ffffff;
        padding: 7px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .qb-split-presets-ribbon .qb-preset-title {
        font-size: 10.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 4px;
    }
    .qb-preset-btn {
        display: inline-flex;
        align-items: center;
        padding: 4px 11px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 20px;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease-in-out;
    }
    .qb-preset-cash {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .qb-preset-cash:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #059669;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }
    .qb-preset-bank {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .qb-preset-bank:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #1d4ed8;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
    }
    .qb-preset-khata {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .qb-preset-khata:hover {
        background: #f59e0b;
        color: #ffffff;
        border-color: #d97706;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
    }
    .qb-preset-split {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }
    .qb-preset-split:hover {
        background: #7c3aed;
        color: #ffffff;
        border-color: #6d28d9;
        box-shadow: 0 2px 4px rgba(124, 58, 237, 0.3);
    }
    .qb-preset-clear {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .qb-preset-clear:hover {
        background: #64748b;
        color: #ffffff;
        border-color: #475569;
    }

    /* Tender Cards */
    .qb-tender-col-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .qb-tender-col-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    .qb-tender-card-cash {
        border-top: 3px solid #10b981 !important;
    }
    .qb-tender-card-bank {
        border-top: 3px solid #0284c7 !important;
    }
    .qb-tender-card-khata {
        border-top: 3px solid #f59e0b !important;
    }
    .qb-tender-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px 6px;
        border-bottom: 1px solid #f1f5f9;
    }
    .qb-tender-title-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .qb-tender-circle-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }
    .qb-tender-title {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .qb-tender-full-btn {
        font-size: 9.5px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 4px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .qb-tender-body {
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .qb-tender-field-lbl {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
        display: block;
    }
    .qb-tender-select {
        height: 28px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 5px !important;
        padding-top: 2px !important;
        padding-bottom: 2px !important;
    }
    .qb-tender-select:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15) !important;
    }
    .qb-tender-input-wrap {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .qb-tender-input-wrap:focus-within {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .qb-tender-curr-addon {
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        padding: 0 8px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        line-height: 32px;
        height: 32px;
    }
    .qb-tender-amount-input {
        border: none !important;
        box-shadow: none !important;
        height: 32px !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        font-family: 'Consolas', 'Courier New', monospace !important;
        text-align: right !important;
        padding: 0 10px !important;
        width: 100% !important;
        -moz-appearance: textfield !important;
    }
    .qb-tender-amount-input::-webkit-outer-spin-button,
    .qb-tender-amount-input::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
    }

    /* Live Reconciliation Ribbon */
    .qb-reconcile-ribbon {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .qb-rec-calc-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .qb-rec-item {
        display: flex;
        flex-direction: column;
    }
    .qb-rec-lbl {
        font-size: 9.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .qb-rec-val {
        font-size: 14px;
        font-weight: 800;
        font-family: 'Consolas', monospace;
    }
    .qb-rec-operator {
        font-size: 16px;
        font-weight: 800;
        color: #94a3b8;
    }
</style>

<div class="qb-window">
    {{-- 1. WINDOW TITLE BAR (NAVY BLUE - ODOO POS MULTI-ORDER TABS) --}}
    <div class="qb-title-bar">
        <div class="qb-title-tabs">
            <span class="qb-title-text"><i class="fas fa-file-invoice text-info me-1"></i> Sales Receipt</span>

            <div class="qb-title-divider"></div>

            <span class="qb-orders-label"><i class="fas fa-layer-group me-1"></i> Orders:</span>

            {{-- Multi-Order Pills Strip --}}
            <div class="qb-order-tabs-wrapper" id="qbOrderTabsWrapper">
                <!-- Dynamically rendered by renderOrderTabs() -->
            </div>

            {{-- Circular Add Order (+) Button --}}
            <button type="button" class="qb-btn-add-order" id="qbBtnAddOrder" title="Add New Order Bill (+)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-win-tool-btn" id="btnToggleFullscreen" title="Toggle Fullscreen">
                <i class="fas fa-expand"></i>
            </button>
            <a href="{{ route('sale.index', [], false) }}" class="qb-win-close-btn" title="Close Receipt">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    {{-- 2. SUB-HEADER BAR: SEARCH & I WANT TO --}}
    <div class="qb-subheader-bar">
        {{-- "I Want to... ▼" Dropdown --}}
        <div class="dropdown">
            <button class="qb-btn-iwantto dropdown-toggle" type="button" id="btnIWantTo" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-receipt"></i> <span>I Want to...</span>
            </button>
            <ul class="dropdown-menu shadow-lg border-0 p-1" aria-labelledby="btnIWantTo" style="font-size: 12px; min-width: 190px;">
                <li><a class="dropdown-item fw-bold" href="#" id="menuQuickAddCustomer"><i class="fas fa-user-plus text-primary me-2"></i> Quick Customer (F2)</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuAddSeries"><i class="fas fa-barcode text-success me-2"></i> Add Invoice Series</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuChangeDate"><i class="far fa-calendar-alt text-info me-2"></i> Change Sale Date</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuCashier"><i class="fas fa-user-tie text-secondary me-2"></i> Cashier / Associate</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuCustomizeColumns"><i class="fas fa-columns text-primary me-2"></i> Customize Columns</a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuPrintA4"><i class="fas fa-print text-dark me-2"></i> Print A4 Invoice</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuPrintThermal"><i class="fas fa-receipt text-dark me-2"></i> Print Thermal Receipt</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuPrintDC"><i class="fas fa-truck text-dark me-2"></i> Delivery Challan (DC)</a></li>
                <li><a class="dropdown-item fw-bold" href="#" id="menuQuotation"><i class="fas fa-file-invoice text-dark me-2"></i> Quotation</a></li>
            </ul>
        </div>

        {{-- Input 1: Scan or enter item information --}}
        <div class="qb-search-box-wrap">
            <input type="text" id="qbItemSearchInput" class="qb-search-input" placeholder="Scan or enter item information" autocomplete="off">
            <i class="fas fa-caret-down qb-input-caret" id="btnToggleItemDropdown" title="Show Products"></i>
            <div id="qbItemSearchResults" class="qb-autocomplete-dropdown"></div>
        </div>

        {{-- Input 2: Enter customer name or phone with direct Odoo Screen access --}}
        <div class="qb-search-box-wrap" style="position: relative;">
            <input type="text" id="qbCustomerSearchInput" class="qb-search-input" placeholder="Enter customer name or phone (F2)" autocomplete="off">
            <i class="fas fa-caret-down qb-input-caret" id="btnToggleCustDropdown" title="Show Customers"></i>
            <div id="qbCustomerSearchResults" class="qb-autocomplete-dropdown"></div>
        </div>

        {{-- Far Right Tool Buttons --}}
        <button type="button" class="qb-odoo-customer-btn" id="btnOpenOdooCustomerModal" onclick="openOdooCustomerPicker()" title="Select Customer (F2)">
            <i class="fas fa-user-friends text-primary"></i> <span>Customers</span> <kbd>F2</kbd>
        </button>
        <button type="button" class="qb-catalog-top-toggle-btn" id="qbBtnToggleCatalog" onclick="toggleCatalog(event)" title="Show / Hide Product Catalog Slider (F4)">
            <i class="fas fa-th-large"></i> <span>Catalog Slider</span> <kbd>F4</kbd>
        </button>
        <button type="button" class="qb-icon-btn" id="qbBtnSearchFocus" title="Search Item (Focus)"><i class="fas fa-search"></i></button>
        <button type="button" class="qb-icon-btn" id="qbBtnClearCustomer" title="Quick Walk-in Customer (F3)" onclick="openQuickWalkinModal()"><i class="fas fa-walking"></i></button>
    </div>

    {{-- 3. CUSTOMER STATUS BANNER --}}
    <div class="qb-status-banner">
        <div class="d-flex align-items-center gap-2">
            <div id="qbCustomerAvatarBadge" style="width: 24px; height: 24px; border-radius: 50%; background: #d97706; color: #fff; font-size: 10px; font-weight: bold; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">WK</div>
            <div>
                <span id="qbCustomerStatusLabel" class="fw-bold text-dark">Chase up, Walk-in</span>
                <span id="qbCustomerDetailsText" class="text-secondary fw-semibold ms-2" style="font-size: 12px;"><span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace">Walk-in</span></span>
            </div>
            <button type="button" class="btn btn-xs btn-outline-warning text-dark py-0 px-1 ms-1" id="qbBtnResetCustomerBanner" title="Edit Walk-in Details (F3)" onclick="openQuickWalkinModal()" style="font-size: 10px; border-radius: 3px;">
                <i class="fas fa-user-edit"></i> Edit Walk-in
            </button>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="qb-banner-toggle-btn" id="btnCarpenterCommission" onclick="openCarpenterModal(event)" title="Carpenter Commission (Hidden Backend)">
                <i class="fas fa-hammer me-1 text-secondary" id="carpenterBtnIcon"></i> <span id="carpenterBtnText">Carpenter</span>
                <span class="badge bg-success ms-1 d-none" id="carpenterActiveBadge" style="font-size: 9px; padding: 2px 4px;">Set</span>
            </button>
            <button type="button" class="qb-banner-toggle-btn" id="btnCustomizeSalesColumns" onclick="openCustomizeSalesColumnsModal(event)" data-toggle="modal" data-target="#modalCustomizeSalesColumns" data-bs-toggle="modal" data-bs-target="#modalCustomizeSalesColumns" title="Customize Columns (Show / Hide)">
                <i class="fas fa-columns me-1 text-primary"></i> <span>Columns</span>
            </button>
            <span class="badge bg-light text-primary border font-monospace px-2 py-1" id="qbInvoiceNoBadge">{{ $nextInvoiceNumber ?? 'INV-0001' }}</span>
            <span class="text-muted" style="font-size: 11.5px;" id="qbDisplayDateText">{{ date('d-M-Y') }}</span>
        </div>
    </div>

    {{-- MAIN FORM FOR BACKEND COMPATIBILITY --}}
    <form id="saleForm" autocomplete="off" class="d-flex flex-column flex-grow-1 overflow-hidden" style="margin: 0;">
        @csrf
        {{-- Standard Hidden Fields required by SaleController --}}
        <input type="hidden" id="booking_id" name="booking_id" value="{{ $sale->id ?? '' }}">
        <input type="hidden" id="action" name="action" value="sale">
        <input type="hidden" id="allow_insufficient_stock" name="allow_insufficient_stock" value="0">
        <input type="hidden" id="inputCashReceived" name="cash" value="0">
        <input type="hidden" id="inputCardAmount" name="card" value="0">
        <input type="hidden" id="inputPaymentMethod" name="payment_method" value="cash">
        <input type="hidden" id="selectedPaymentAccountId" value="">
        <input type="hidden" id="totalBalance" value="0">
        <input type="hidden" id="inputInvoiceNo" name="Invoice_no" value="{{ $nextInvoiceNumber ?? 'INV-0001' }}">
        <input type="hidden" id="displayDateInput" name="sale_date" value="{{ date('Y-m-d') }}">
        <input type="hidden" id="creditDaysInput" name="credit_days" value="0">
        <input type="hidden" id="remarks" name="reference" value="">
        <input type="hidden" id="partyTypeSelect" name="partyType" value="Main Customer">

        {{-- Carpenter Commission Hidden Fields --}}
        <input type="hidden" id="carpenter_id" name="carpenter_id" value="">
        <input type="hidden" id="carpenter_commission_type" name="carpenter_commission_type" value="percentage">
        <input type="hidden" id="carpenter_commission_rate" name="carpenter_commission_rate" value="0">
        <input type="hidden" id="carpenter_commission_amount" name="carpenter_commission_amount" value="0">
        <input type="hidden" id="is_walkin" name="is_walkin" value="1">
        <input type="hidden" id="walkinNameInput" name="walkin_name" value="Walk-in Customer">
        <input type="hidden" id="walkinPhoneInput" name="walkin_phone" value="">
        <input type="hidden" id="walkinAddressInput" name="walkin_address" value="">
        <input type="hidden" id="walkinCnicInput" name="walkin_cnic" value="">
        <input type="hidden" id="customerSelect" name="customer" value="">
        <input type="hidden" id="address" name="address" value="">
        <input type="hidden" id="tel" name="tel" value="">
        <input type="hidden" id="previousBalance" value="0">
        <input type="hidden" id="rangeBalance" value="0">
        <input type="hidden" id="salesOfficerId" name="sales_officer_id" value="">
        <input type="hidden" id="overallDiscountVal" value="0">
        <input type="hidden" id="overallDiscountType" value="percent">

        {{-- Dynamic Payment Voucher Accounts Container --}}
        <div id="paymentAccountsContainer" class="d-none"></div>
        {{-- Dynamic Sale Items Form Inputs Container (Prevents Duplicate Array Inputs) --}}
        <div id="dynamicSaleItemsContainer" class="d-none"></div>

        {{-- 4. MAIN SPLIT: LEFT BUTTONS & MAIN TABLE --}}
        <div class="qb-main-split">
            {{-- Left Vertical Buttons Strip --}}
            <div class="qb-left-sidebar">
                <button type="button" class="qb-side-btn" id="btnQuickPick" onclick="toggleCatalog(event)"><i class="fas fa-th-large me-1"></i> Catalog Slider</button>
                <button type="button" class="qb-side-btn" id="btnAddNewItem">Add New Item</button>
                <button type="button" class="qb-side-btn" id="btnGiveDiscount">Give Discount</button>
                <button type="button" class="qb-side-btn" id="btnAcceptReturn">Accept Return</button>
                <button type="button" class="qb-side-btn" id="btnCashierAssociate">Cashier/Associate</button>
                <button type="button" class="qb-side-btn" id="btnShipItems">Ship Items</button>
                <button type="button" class="qb-side-btn" id="btnShowMessages">Show Messages (1)</button>
                <button type="button" class="qb-side-btn" id="btnChangeDate">Change Date</button>
            </div>

            {{-- Main Table Area --}}
            <div class="qb-table-pane">
                <div class="qb-table-scroll">
                    <table class="qb-grid-table" id="qbMainGridTable">
                        <thead>
                            <tr title="Right-click table header anytime to customize columns">
                                <th class="col-item-no">Item #</th>
                                <th class="col-item-name">Item Name</th>
                                <th class="col-vendor-desc">Vendor Description</th>
                                <th class="col-attribute">Attribute</th>
                                <th class="col-size">Size</th>
                                <th class="col-qty text-end">Qty</th>
                                <th class="col-price text-end">Price</th>
                                <th class="col-ext-price text-end">Ext Price</th>
                                <th class="col-sales-desc">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Sales Description</span>
                                        <i class="fas fa-columns text-primary ms-1" style="cursor: pointer; opacity: 0.85;" onclick="openCustomizeSalesColumnsModal(event)" title="Customize Columns (Show/Hide)"></i>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            {{-- Rows dynamically rendered here --}}
                        </tbody>
                    </table>
                </div>

                {{-- In-Row Action Toolbar (Moves below active selected row) --}}
                <div id="qbActiveRowToolbar" class="qb-inrow-actionbar d-none">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="qb-row-btn" id="btnRowEdit"><i class="fas fa-edit"></i> Edit</button>
                        <button type="button" class="qb-row-btn" id="btnRowReturnItem"><i class="fas fa-undo"></i> Return Item</button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="qb-row-btn" id="btnRowQtyPriceDisc">Qty/Price/Discount</button>
                        <button type="button" class="qb-row-btn" id="btnRowQtyPlus"><i class="fas fa-plus"></i> Qty+</button>
                        <button type="button" class="qb-row-btn" id="btnRowQtyMinus"><i class="fas fa-minus"></i> Qty-</button>
                        <button type="button" class="qb-row-btn" id="btnRowRemove" style="background: linear-gradient(180deg, #dc3545 0%, #b02a37 100%); border-color: #842029;"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                </div>

                {{-- 5. BOTTOM SUMMARY SECTION (Compact Modern Strip) --}}
                <div class="qb-summary-wrapper">
                    {{-- Left: Item & Qty Stats (Compact Pills) --}}
                    <div class="qb-summary-stats-strip">
                        <div class="qb-stat-chip" title="Number of distinct items sold">
                            <span class="qb-stat-lbl">No. of Items Sold:</span>
                            <span class="qb-stat-num" id="qbStatItemsSold">0</span>
                        </div>
                        <div class="qb-stat-chip" title="Total pieces / quantity sold">
                            <span class="qb-stat-lbl">Total Qty Sold:</span>
                            <span class="qb-stat-num" id="qbStatQtySold">0</span>
                        </div>
                        <div class="qb-stat-chip qb-chip-ret" title="Number of returned items">
                            <span class="qb-stat-lbl">Items Returned:</span>
                            <span class="qb-stat-num text-danger" id="qbStatItemsReturned">0</span>
                        </div>
                        <div class="qb-stat-chip qb-chip-ret" title="Total returned quantity">
                            <span class="qb-stat-lbl">Qty Returned:</span>
                            <span class="qb-stat-num text-danger" id="qbStatQtyReturned">0</span>
                        </div>
                    </div>

                    {{-- Right: Financial Totals (Inline Row) --}}
                    <div class="qb-summary-fin-strip">
                        <div class="qb-fin-item">
                            <span class="qb-fin-lbl">SubTotal:</span>
                            <span class="qb-fin-val" id="qbStatSubTotal">0.00</span>
                        </div>
                        <div class="qb-fin-item">
                            <span class="qb-fin-lbl">Discount:</span>
                            <span class="qb-fin-val text-danger" id="qbStatDiscount">0.00</span>
                        </div>
                        <div class="qb-fin-item">
                            <span class="qb-fin-lbl">Tax:</span>
                            <span class="qb-fin-val">0.00</span>
                        </div>
                        <div class="qb-fin-total-box">
                            <span class="qb-fin-total-lbl">Total:</span>
                            <span class="qb-fin-total-val" id="qbStatGrandTotal">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. BOTTOM PAYMENT TABS STRIP --}}
        <div class="qb-payment-bar">
            <div class="qb-payment-tabs-group">
                <button type="button" class="qb-pay-tab active" data-method="cash" title="100% Cash Payment"><i class="fas fa-money-bill-wave me-1"></i> Cash</button>
                <button type="button" class="qb-pay-tab" data-method="credit" title="Credit / Custom Amount Allocation"><i class="fas fa-file-invoice me-1"></i> Credit</button>
                <button type="button" class="qb-pay-tab" data-method="debit" title="100% Bank / Card"><i class="fas fa-credit-card me-1"></i> Debit</button>
                <button type="button" class="qb-pay-tab" data-method="check" title="100% Bank Check"><i class="fas fa-money-check me-1"></i> Check</button>
                <button type="button" class="qb-pay-tab" data-method="gift" title="100% Gift Card"><i class="fas fa-gift me-1"></i> Gift</button>
                <button type="button" class="qb-pay-tab" data-method="account" title="100% Customer Ledger / Khata (Main Customer Only)"><i class="fas fa-book me-1"></i> Account</button>
                <button type="button" class="qb-pay-tab" data-method="split" style="background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%); border-color: #0369a1;" title="Split Payment Across Accounts"><i class="fas fa-random me-1"></i> Split</button>

                <div class="ms-2 d-inline-flex align-items-center">
                    <span class="badge bg-white text-dark border px-2 py-1 shadow-sm d-inline-flex align-items-center" style="font-size:11px; cursor:pointer;" onclick="openPaymentAccountSelectorModal()" title="Click to view / split payment across Chart of Accounts & Khata">
                        <span id="qbActiveAccountBadge"><i class="fas fa-wallet text-success me-1"></i> Account: <strong>Shop Cash</strong></span>
                        <i class="fas fa-cog ms-2 text-primary" style="font-size:10px;"></i>
                    </span>
                </div>
            </div>
            <div class="qb-amount-due-banner">
                <span class="qb-due-label">Amount Due</span>
                <span class="qb-due-value" id="qbAmountDueVal">0.00</span>
            </div>
        </div>

        {{-- 7. BOTTOM COMMAND ACTIONS BAR (DARK SLATE) --}}
        <div class="qb-command-bar">
            <button type="button" class="qb-cmd-btn-dark" id="btnPutOnHold">Put on Hold</button>
            <button type="button" class="qb-cmd-btn-dark" id="btnCancelReceipt" onclick="handleCancelReceipt(event)">Cancel</button>
            <button type="button" class="qb-cmd-btn-blue" id="btnSaveOnly">Save Only</button>
            <button type="button" class="qb-cmd-btn-blue" id="btnSaveAndPrint">Save & Print</button>
        </div>
    </form>
</div>

{{-- ==========================================================
     PRODUCT CATALOG SLIDER (ROOT LEVEL - IMAGE 1 REPLICA)
     ========================================================== --}}
{{-- Edge hover sensor & Floating Toggle Tab on the Right Boundary of Screen --}}
<div class="qb-right-edge-sensor" id="qbRightEdgeSensor" title="Move cursor here to reveal catalog handle"></div>
<div class="qb-floating-catalog-handle" id="qbFloatingToggleCatalog" onclick="toggleCatalog(event)" title="Show / Hide Product Catalog Slider (F4)">
    <i class="fas fa-chevron-left" id="qbHandleArrow"></i>
    <span class="qb-handle-label">CATALOG</span>
</div>

{{-- Catalog Backdrop for Click-Outside --}}
<div class="qb-catalog-backdrop" id="qbCatalogBackdrop" onclick="closeCatalog(event)"></div>

{{-- Slide-in Right Catalog Panel --}}
<div class="qb-catalog-panel" id="qbCatalogPanel">
    <div class="qb-catalog-header">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-th-large text-info"></i>
            <span>Product Catalog</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 fw-bold text-nowrap" id="btnCloseCatalog" onclick="closeCatalog(event)" title="Hide Catalog Panel (ESC or F4)" style="font-size: 11px;">
            <i class="fas fa-times me-1"></i> Hide Slider <kbd style="background: rgba(0,0,0,0.3); font-size: 9px; padding: 1px 4px; border-radius: 3px;">ESC</kbd>
        </button>
    </div>
    
    {{-- Category Tiles Strip (Image 1 top colorful buttons) --}}
    <div class="qb-cat-tiles-wrapper">
        <div class="qb-cat-tiles-grid" id="qbCatTilesGrid">
            <div class="qb-cat-tile all-tile active" data-cat-id="all">
                <span>All Items</span>
            </div>
        </div>
    </div>

    {{-- Product Search Input --}}
    <div class="qb-catalog-search-bar">
        <input type="text" id="qbCatalogSearchInput" placeholder="Search products in catalog..." autocomplete="off">
        <i class="fas fa-search"></i>
    </div>

    {{-- Catalog Toolbar: Item Count & Slide Radio/Toggle Button for Prices --}}
    <div class="qb-catalog-toolbar">
        <div class="d-flex align-items-center">
            <i class="fas fa-cubes text-secondary me-1" style="font-size: 11px;"></i>
            <span id="qbCatalogCountBadge" class="text-secondary fw-bold" style="font-size: 11px;">0 items</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="qb-toggle-price-label" id="qbTogglePriceLabel" title="Click to show/hide prices">
                <i class="fas fa-eye text-primary me-1" id="qbTogglePriceIcon" style="font-size: 10.5px;"></i>
                <span id="qbTogglePriceText">Show Prices</span>
            </span>
            <label class="qb-switch" title="Toggle prices on product cards">
                <input type="checkbox" id="qbToggleCatalogPrice" checked>
                <span class="qb-slider"></span>
            </label>
        </div>
    </div>

    {{-- Product Cards Grid (Image 1 bottom cards) --}}
    <div class="qb-prod-grid" id="qbCatalogProdGrid">
        <div class="text-center text-muted py-5" style="grid-column: 1 / -1; font-size: 12px;">
            <i class="fas fa-spinner fa-spin me-2"></i> Loading items...
        </div>
    </div>
</div>

{{-- ==========================================================
     MODALS: ACCEPT RETURN, QUICK PRODUCTS, EDIT ROW, ETC.
     ========================================================== --}}

{{-- Modal: Carpenter Commission (Hidden Backend) --}}
<div class="modal fade" id="modalCarpenterCommission" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 430px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header text-white py-2 px-3" style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-hammer text-warning"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 14px;">Carpenter Commission</h6>
                        <small class="text-white-50" style="font-size: 10.5px;">Set hidden backend commission</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="background: #f8fafc;">
                {{-- Carpenter Select Field with Quick Add button --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">
                        <i class="fas fa-user-tie text-primary me-1"></i> Carpenter Name
                    </label>
                    <div class="input-group input-group-sm">
                        <select id="modal_carpenter_id" class="form-select form-select-sm fw-bold">
                            <option value="">-- No Carpenter / None --</option>
                            @if(isset($carpenters))
                                @foreach($carpenters as $carp)
                                    <option value="{{ $carp->id }}" 
                                            data-type="{{ $carp->default_commission_type }}" 
                                            data-rate="{{ $carp->default_commission_rate }}"
                                            data-name="{{ $carp->name }}"
                                            data-phone="{{ $carp->phone }}">
                                        {{ $carp->name }} {{ $carp->phone ? '('.$carp->phone.')' : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="button" class="btn btn-outline-primary fw-bold" id="btnOpenAddCarpenterModal" title="Add New Carpenter">
                            <i class="fas fa-plus"></i> New
                        </button>
                    </div>
                </div>

                {{-- Commission Type & Rate --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">
                            <i class="fas fa-percentage text-success me-1"></i> Commission Type
                        </label>
                        <select id="modal_carpenter_comm_type" class="form-select form-select-sm fw-bold">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Rupees (Rs.)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">
                            <i class="fas fa-coins text-warning me-1"></i> Commission Value
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="modal_carpenter_comm_rate" class="form-control form-control-sm fw-bold text-end" step="0.01" min="0" placeholder="0.00" value="0">
                            <span class="input-group-text fw-bold text-muted" id="modal_comm_unit_symbol">%</span>
                        </div>
                    </div>
                </div>

                {{-- Live Preview / Helper box --}}
                <div class="p-2 rounded border bg-white mb-2" style="font-size: 12px;">
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <span>Current Sale Net:</span>
                        <strong class="text-dark font-monospace" id="modal_carp_sale_total">Rs. 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <span>Est. Commission:</span>
                        <strong class="text-success font-monospace" id="modal_carp_est_commission">Rs. 0.00</strong>
                    </div>
                    <div class="text-muted fst-italic" style="font-size: 10.5px; border-top: 1px dashed #e2e8f0; padding-top: 4px;">
                        <i class="fas fa-eye-slash text-secondary me-1"></i> Hidden: Will not show on customer receipt/invoice.
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold" id="btnClearCarpenterCommission">
                    <i class="fas fa-times me-1"></i> Clear
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnApplyCarpenterCommission">
                        <i class="fas fa-check me-1"></i> Save & Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Quick Add Carpenter --}}
<div class="modal fade" id="modalQuickAddCarpenter" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header text-white py-2 px-3" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 14px;">
                    <i class="fas fa-user-plus me-1 text-warning"></i> Quick Add New Carpenter
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" style="background: #f8fafc;">
                <form id="formQuickAddCarpenter">
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Carpenter Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm fw-bold" id="quick_carp_name" required placeholder="e.g. Ustad Tariq">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11.5px;">Phone / Mobile</label>
                            <input type="text" class="form-control form-control-sm" id="quick_carp_phone" placeholder="0300-1234567">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11.5px;">CNIC</label>
                            <input type="text" class="form-control form-control-sm" id="quick_carp_cnic" placeholder="Optional">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11.5px;">Default Comm. Type</label>
                            <select id="quick_carp_type" class="form-select form-select-sm">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Rupees (Rs.)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11.5px;">Default Rate</label>
                            <input type="number" class="form-control form-control-sm text-end" id="quick_carp_rate" step="0.01" min="0" value="0" placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 11.5px;">Address / Area</label>
                        <input type="text" class="form-control form-control-sm" id="quick_carp_address" placeholder="Optional">
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" id="btnCancelQuickCarpenter">Cancel</button>
                <button type="button" class="btn btn-success btn-sm fw-bold px-3" id="btnSaveQuickCarpenter">
                    <i class="fas fa-save me-1"></i> Save Carpenter
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 1: Accept Return / Receipt Lookup --}}
<div class="modal fade" id="modalAcceptReturn" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="fas fa-undo me-2"></i> Accept Return - Find Sales Receipt</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text bg-white fw-bold">Receipt / Invoice #</span>
                    <input type="text" id="returnSearchInvoiceNo" class="form-control fw-bold" placeholder="e.g. 1883 or INV-000045">
                    <button class="btn btn-primary px-3 fw-bold" type="button" id="btnSearchReturnReceipt"><i class="fas fa-search me-1"></i> Find Receipt</button>
                </div>

                <div id="returnItemsContainer" class="border rounded-2 p-2 bg-light" style="max-height: 320px; overflow-y: auto;">
                    <div class="text-center text-muted py-4">Enter a sales receipt number above to load sold items for return.</div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <span class="text-muted small" id="returnReceiptMeta"></span>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: QuickBooks & Odoo Split Tender & Chart of Accounts Selector --}}
<div class="modal fade" id="modalSelectPaymentAccount" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            {{-- Modal Header: QuickBooks Deep Blue --}}
            <div class="modal-header text-white py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-cash-register text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 14px; letter-spacing: 0.3px;">Payment Allocation & Chart of Accounts</h6>
                        <small class="text-white-50" style="font-size: 11px;">QuickBooks & Odoo Multi-Payment / Split Tender Engine</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#modalSelectPaymentAccount').modal('hide')"></button>
            </div>

            <div class="modal-body p-3 bg-light">
                {{-- Hero Bill & Customer Dashboard Card --}}
                <div class="qb-split-hero-card mb-3">
                    <div class="row align-items-center g-2">
                        <div class="col-sm-5 border-end border-white-10 pe-3">
                            <span class="qb-hero-lbl d-block"><i class="fas fa-file-invoice-dollar me-1"></i> Net Invoice Payable</span>
                            <div class="d-flex align-items-baseline gap-1 mt-1">
                                <span class="text-warning fw-bold fs-6">Rs.</span>
                                <span class="qb-hero-amount" id="qbModalGrandTotalDisplay">0.00</span>
                            </div>
                        </div>
                        <div class="col-sm-7 ps-sm-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-white-50 small fw-bold"><i class="fas fa-user me-1"></i> Customer:</span>
                                <span class="qb-hero-cust-badge" id="qbModalCustName">Walk-in Customer</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-white-50 small fw-bold"><i class="fas fa-history me-1"></i> Previous Khata Bal:</span>
                                <span class="qb-hero-bal-val" id="qbModalCustPrevBal">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fast 1-Click Preset Action Bar --}}
                <div class="qb-split-presets-ribbon mb-3">
                    <span class="qb-preset-title"><i class="fas fa-bolt text-warning me-1"></i> Quick Presets:</span>
                    <button type="button" class="qb-preset-btn qb-preset-cash" data-preset="full_cash">
                        <i class="fas fa-money-bill-wave me-1"></i> 100% Cash
                    </button>
                    <button type="button" class="qb-preset-btn qb-preset-bank" data-preset="full_bank">
                        <i class="fas fa-university me-1"></i> 100% Bank / Card
                    </button>
                    <button type="button" class="qb-preset-btn qb-preset-khata" data-preset="full_khata" id="qbPresetKhataBtn">
                        <i class="fas fa-book me-1"></i> 100% Khata (Udhar)
                    </button>
                    <button type="button" class="qb-preset-btn qb-preset-split" data-preset="split_cash_bank">
                        <i class="fas fa-balance-scale me-1"></i> 50/50 Cash + Bank
                    </button>
                    <button type="button" class="qb-preset-btn qb-preset-clear" data-preset="clear">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>

                {{-- Payment Methods & Accounts Container (3 Aligned Cards) --}}
                <div class="row g-2 mb-3">
                    {{-- 1. Cash Tender Card --}}
                    <div class="col-md-4">
                        <div class="qb-tender-col-card qb-tender-card-cash">
                            <div class="qb-tender-header">
                                <div class="qb-tender-title-wrap">
                                    <span class="qb-tender-circle-icon bg-success-subtle text-success"><i class="fas fa-money-bill-wave"></i></span>
                                    <span class="qb-tender-title text-success">Cash Tender</span>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-success qb-tender-full-btn" onclick="allocateFullTo('cash')">Full</button>
                            </div>
                            <div class="qb-tender-body">
                                <div>
                                    <label class="qb-tender-field-lbl">Deposit Account:</label>
                                    <select id="qbSplitCashAccountId" class="form-select qb-tender-select">
                                        @if(isset($allAccounts) && $allAccounts->count())
                                            @foreach($allAccounts as $acc)
                                                @if(stripos($acc->title, 'cash') !== false || $acc->id == ($defaultCashAccountId ?? 2))
                                                    <option value="{{ $acc->id }}" {{ $acc->id == ($defaultCashAccountId ?? 2) ? 'selected' : '' }}>
                                                        {{ $acc->title }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="2" selected>Shop Cash</option>
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="qb-tender-field-lbl">Amount Received:</label>
                                    <div class="qb-tender-input-wrap">
                                        <span class="qb-tender-curr-addon text-success">Rs.</span>
                                        <input type="number" step="0.01" min="0" id="qbSplitCashInput" class="form-control qb-tender-amount-input text-success" placeholder="0.00" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Bank / Digital Account Card --}}
                    <div class="col-md-4">
                        <div class="qb-tender-col-card qb-tender-card-bank">
                            <div class="qb-tender-header">
                                <div class="qb-tender-title-wrap">
                                    <span class="qb-tender-circle-icon bg-primary-subtle text-primary"><i class="fas fa-university"></i></span>
                                    <span class="qb-tender-title text-primary">Bank / Account</span>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-primary qb-tender-full-btn" onclick="allocateFullTo('bank')">Full</button>
                            </div>
                            <div class="qb-tender-body">
                                <div>
                                    <label class="qb-tender-field-lbl">Select Bank Account:</label>
                                    <select id="qbSplitBankAccountId" class="form-select qb-tender-select">
                                        @if(isset($allAccounts) && $allAccounts->count())
                                            @foreach($allAccounts as $acc)
                                                @if(!in_array(strtolower($acc->title), ['accounts payable', 'sales revenue']))
                                                    <option value="{{ $acc->id }}" {{ $acc->id == ($defaultBankAccountId ?? 3) ? 'selected' : '' }}>
                                                        {{ $acc->title }} ({{ optional($acc->head)->name ?: ($acc->type ?: 'Bank') }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="3" selected>Meezan Bank</option>
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="qb-tender-field-lbl">Amount Paid:</label>
                                    <div class="qb-tender-input-wrap">
                                        <span class="qb-tender-curr-addon text-primary">Rs.</span>
                                        <input type="number" step="0.01" min="0" id="qbSplitBankInput" class="form-control qb-tender-amount-input text-primary" placeholder="0.00" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Customer Khata / Ledger (Due) Card --}}
                    <div class="col-md-4" id="qbKhataCardCol">
                        <div class="qb-tender-col-card qb-tender-card-khata">
                            <div class="qb-tender-header">
                                <div class="qb-tender-title-wrap">
                                    <span class="qb-tender-circle-icon bg-warning-subtle text-warning-emphasis"><i class="fas fa-book"></i></span>
                                    <span class="qb-tender-title text-warning-emphasis">Ledger</span>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-warning text-dark qb-tender-full-btn" onclick="allocateFullTo('khata')">Full</button>
                            </div>
                            <div class="qb-tender-body">
                                <div>
                                    <label class="qb-tender-field-lbl">Ledger Type:</label>
                                    <div class="form-control qb-tender-select bg-light text-truncate d-flex align-items-center" id="qbModalCustKhataTarget">
                                        Customer Ledger
                                    </div>
                                </div>
                                <div>
                                    <label class="qb-tender-field-lbl">Due on Ledger:</label>
                                    <div class="qb-tender-input-wrap">
                                        <span class="qb-tender-curr-addon text-warning-emphasis">Rs.</span>
                                        <input type="number" step="0.01" min="0" id="qbSplitKhataInput" class="form-control qb-tender-amount-input text-dark" placeholder="0.00" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Live Double-Entry Balancing & Equation Dashboard --}}
                <div class="qb-reconcile-ribbon mb-2">
                    <div class="qb-rec-calc-group">
                        <div class="qb-rec-item">
                            <span class="qb-rec-lbl">Total Net Bill</span>
                            <span class="qb-rec-val text-dark" id="qbBalTotalBill">Rs. 0.00</span>
                        </div>
                        <span class="qb-rec-operator">−</span>
                        <div class="qb-rec-item">
                            <span class="qb-rec-lbl">Paid Upfront</span>
                            <span class="qb-rec-val text-success" id="qbBalTotalPaid">Rs. 0.00</span>
                        </div>
                        <span class="qb-rec-operator">=</span>
                        <div class="qb-rec-item">
                            <span class="qb-rec-lbl">Due on Khata</span>
                            <span class="qb-rec-val text-warning-emphasis" id="qbBalDueKhata">Rs. 0.00</span>
                        </div>
                    </div>
                    <div id="qbBalStatusBadge">
                        <span class="badge bg-success text-white fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                            <i class="fas fa-check-circle me-1"></i> Exact Match (100% Balanced)
                        </span>
                    </div>
                </div>
                <div id="qbWalkinKhataWarning" class="alert alert-danger py-1 px-2 mb-0 small d-none" style="font-size: 11px; border-radius: 6px;">
                    <i class="fas fa-exclamation-triangle me-1"></i> <strong>Walk-in Customer:</strong> Unpaid Khata balance is not allowed. Please select a registered customer or pay 100% in Cash/Bank.
                </div>
            </div>

            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center" style="border-top: 1px solid #e2e8f0;">
                <div class="small text-muted" style="font-size: 11.5px;">
                    Press <kbd style="background:#e2e8f0; color:#334155; padding:2px 5px; border-radius:3px;">Esc</kbd> to Cancel &nbsp;|&nbsp; 
                    <kbd style="background:#e2e8f0; color:#334155; padding:2px 5px; border-radius:3px;">Enter</kbd> to Apply
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3 fw-bold" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#modalSelectPaymentAccount').modal('hide')" style="background: #f1f5f9; border-color: #cbd5e1; color: #475569;">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm" id="btnApplySplitPayment" style="background: linear-gradient(180deg, #1e4b85 0%, #12335e 100%); border-color: #0c2445; box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 2px 4px rgba(0,0,0,0.2);">
                        <i class="fas fa-check-circle me-1"></i> Apply Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

{{-- Modal 2: Quick Pick Items --}}
<div class="modal fade" id="modalQuickPickItems" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="fas fa-th me-2"></i> Quick Pick Items Catalog</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 bg-light">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="quickPickCatalogSearch" class="form-control" placeholder="Search catalog by name, code, SKU, or p-code...">
                </div>

                <div class="row g-2 overflow-auto" id="quickPickCatalogGrid" style="max-height: 440px;">
                    @if(isset($recentProducts) && count($recentProducts) > 0)
                        @foreach($recentProducts as $prod)
                            @php
                                $cost = $prod->purchase_price_per_piece > 0 ? $prod->purchase_price_per_piece : ($prod->purchase_price_per_box > 0 ? $prod->purchase_price_per_box : ($prod->sale_price_per_piece ?? 0));
                                $qPCode = $prod->p_code ?: \App\Services\PCodeService::encode($cost);
                            @endphp
                            <div class="col-sm-6 col-md-4 col-lg-3 quick-pick-card-item" data-search="{{ strtolower($prod->item_name . ' ' . $prod->item_code . ' ' . $qPCode) }}">
                                <div class="card h-100 border shadow-sm p-2 cursor-pointer btn-add-direct-catalog" data-product-id="{{ $prod->id }}" style="cursor: pointer; transition: all 0.15s ease;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge bg-secondary-subtle text-dark font-monospace small">#{{ $prod->item_code }}</span>
                                        @if(!empty($qPCode))
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace small" title="P-Code: {{ $qPCode }}">P: {{ $qPCode }}</span>
                                        @endif
                                    </div>
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 13px;" title="{{ $prod->item_name }}">{{ $prod->item_name }}</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top" style="font-size: 12px;">
                                        <span class="text-success fw-bold">Stock: {{ $prod->total_pieces ?? $prod->stock ?? 0 }}</span>
                                        <span class="fw-bold text-primary">Rs. {{ number_format($prod->retail_price ?? $prod->sale_price_per_piece ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 3: Give Overall Discount --}}
<div class="modal fade" id="modalGiveDiscount" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="fas fa-percent me-2"></i> Give Discount</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Discount Type</label>
                    <select id="modalDiscountTypeSelect" class="form-select form-select-sm fw-bold">
                        <option value="percent">Percentage (%)</option>
                        <option value="amount">Fixed Amount (Rs.)</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Discount Value</label>
                    <input type="number" step="any" id="modalDiscountValueInput" class="form-control form-control-sm text-end fw-bold fs-6" value="0" min="0">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalGiveDiscount').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnApplyOverallDiscount">Apply Discount</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 4: Cashier / Associate Selection --}}
<div class="modal fade" id="modalCashierAssociate" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="fas fa-user-tie me-2"></i> Cashier / Associate</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Current Cashier</label>
                    <input type="text" class="form-control form-control-sm fw-bold bg-light" value="{{ auth()->user()?->name ?? 'Administrator' }}" readonly>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Sales Officer / Associate</label>
                    <select id="modalSalesOfficerSelect" class="form-select form-select-sm fw-bold">
                        <option value="">-- No Associate Assigned --</option>
                        @if(isset($salesOfficers))
                            @foreach($salesOfficers as $officer)
                                <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalCashierAssociate').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnApplyAssociate">Save Associate</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 5: Change Date & Due Date --}}
<div class="modal fade" id="modalChangeDate" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="far fa-calendar-alt me-2"></i> Change Date & Credit Days</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Sale Date</label>
                    <input type="date" id="modalSaleDateInput" class="form-control form-control-sm fw-bold" value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Credit Days</label>
                    <input type="number" id="modalCreditDaysInput" class="form-control form-control-sm fw-bold" value="0" min="0">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Remarks / Ref</label>
                    <input type="text" id="modalRemarksInput" class="form-control form-control-sm" placeholder="Optional notes...">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalChangeDate').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnApplyChangeDate">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 6: Edit Line Item (Qty / Price / Discount) --}}
<div class="modal fade" id="modalEditLineItem" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-2" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="fas fa-sliders-h me-2"></i> Edit Line Item</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="editRowUid">
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Product Name</label>
                    <input type="text" id="editRowItemName" class="form-control form-control-sm fw-bold bg-light" readonly>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">Quantity</label>
                        <input type="number" step="any" id="editRowQty" class="form-control form-control-sm text-end fw-bold">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">Price (Rs.)</label>
                        <input type="number" step="any" id="editRowPrice" class="form-control form-control-sm text-end fw-bold">
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">Line Discount (%)</label>
                        <input type="number" step="any" id="editRowDiscount" class="form-control form-control-sm text-end" value="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">Attribute / Color</label>
                        <input type="text" id="editRowAttribute" class="form-control form-control-sm text-center">
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Size / Packing</label>
                        <input type="text" id="editRowSize" class="form-control form-control-sm text-center">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Sales Description</label>
                    <input type="text" id="editRowDesc" class="form-control form-control-sm">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalEditLineItem').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnSaveLineEdit">Save Changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Odoo-Style Customer / Partner Picker --}}
<div class="modal fade" id="modalOdooCustomerPicker" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable modal-odoo-customer-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-2 text-white" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-friends fs-5 text-white"></i>
                    <h6 class="modal-title fw-bold text-white mb-0">Select Customer (Odoo Screen)</h6>
                    <span class="badge bg-white text-dark ms-2 fw-bold" id="odooCustHeaderCount" style="font-size: 11px;">0 Customers</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-success fw-bold px-2 py-1" id="btnOdooCreateNewCust" style="font-size: 11.5px;">
                        <i class="fas fa-user-plus me-1"></i> + Create Customer
                    </button>
                    <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-2 py-1" id="btnOdooSetWalkin" style="font-size: 11.5px;">
                        <i class="fas fa-walking me-1"></i> Set Walk-in
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="bg-light border-bottom p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" id="odooModalCustSearch" class="form-control border-start-0 fs-6" placeholder="Search by customer name, mobile, ID or address... (Live filter)">
                            <button class="btn btn-outline-secondary" type="button" id="btnOdooClearModalSearch" title="Clear Search"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="button" class="btn btn-outline-primary active odoo-filter-btn" data-type="all">All</button>
                            <button type="button" class="btn btn-outline-primary odoo-filter-btn" data-type="Main Customer">Main</button>
                            <button type="button" class="btn btn-outline-danger odoo-filter-btn" data-type="dues"><i class="fas fa-exclamation-circle me-1"></i> With Dues</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-3 bg-light" style="min-height: 380px; max-height: 65vh; overflow-y: auto;">
                <div id="odooCustLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted fw-bold" style="font-size: 13px;">Loading customers...</div>
                </div>
                <div id="odooCustCardsGrid" class="odoo-cust-cards-grid" style="display: none;"></div>
                <div id="odooCustEmpty" class="text-center py-5 text-muted" style="display: none;">
                    <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">No customers found</h6>
                    <p class="small text-secondary mb-3" id="odooCustEmptyMsg">No customer matched your search criteria.</p>
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnOdooEmptyCreate"><i class="fas fa-user-plus me-1"></i> Add New Customer</button>
                </div>
            </div>
            <div class="modal-footer py-2 bg-white d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="fas fa-keyboard text-primary me-1"></i> Tip: Press <kbd>F2</kbd> anywhere to open Customer Screen. Click any card to select for the bill.
                </div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Odoo-Style Quick Walk-in Customer Details --}}
<div class="modal fade" id="modalQuickWalkinCustomer" tabindex="-1" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-2 text-white" style="background: linear-gradient(180deg, #d97706 0%, #b45309 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-walking fs-5 text-white"></i>
                    <h6 class="modal-title fw-bold text-white mb-0">Walk-in Customer Details (Odoo Style)</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-3 bg-light">
                {{-- ERP Notice Banner --}}
                <div class="alert alert-warning border border-warning-subtle py-2 px-3 small mb-3 text-dark d-flex align-items-start gap-2 shadow-sm" style="background-color: #fffbeb; font-size: 11.5px; border-radius: 6px;">
                    <i class="fas fa-info-circle text-warning fs-6 mt-1"></i>
                    <div>
                        <strong>One-Time POS Customer:</strong> Ye details sirf is sale invoice aur reporting ke liye hain. Ye aap ke <u>Customer Master Directory</u> ya <u>Chart of Accounts (Khata)</u> me save <b>nahi</b> hongi.
                    </div>
                </div>

                <form id="formQuickWalkinCustomer" autocomplete="off" onsubmit="return false;">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fas fa-user me-1 text-primary"></i> Customer Name <small class="text-muted fw-normal">(Optional)</small></span>
                            <span class="text-muted" style="font-size: 11px;">Default: Walk-in Customer</span>
                        </label>
                        <input type="text" id="walkinModalName" class="form-control form-control-sm fs-6 fw-bold text-dark" placeholder="Walk-in Customer" value="Walk-in Customer">
                        <div class="d-flex gap-1 mt-1">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 walkin-quick-preset" data-name="Walk-in Customer" style="font-size: 10.5px;">Default Walk-in</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 walkin-quick-preset" data-name="Cash Counter" style="font-size: 10.5px;">Cash Counter</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 walkin-quick-preset" data-name="Wholesale Spot" style="font-size: 10.5px;">Wholesale Spot</button>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="fas fa-phone-alt me-1 text-success"></i> Mobile / Phone <small class="text-muted fw-normal">(Optional)</small>
                            </label>
                            <input type="text" id="walkinModalPhone" class="form-control form-control-sm font-monospace" placeholder="e.g. 0300-1234567 (Optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="fas fa-id-card me-1 text-info"></i> CNIC / NTN <small class="text-muted fw-normal">(Optional)</small>
                            </label>
                            <input type="text" id="walkinModalCnic" class="form-control form-control-sm font-monospace" placeholder="e.g. 42101-xxxxxxx-x (Optional)">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark mb-1 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> Address / Site Delivery / Location <small class="text-muted fw-normal">(Optional)</small></span>
                        </label>
                        <input type="text" id="walkinModalAddress" class="form-control form-control-sm" placeholder="e.g. Shop # 5, Timber Market or Site Delivery... (Optional)">
                    </div>

                    <div class="mb-1">
                        <label class="form-label small fw-bold text-dark mb-1 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-comment-dots me-1 text-secondary"></i> Invoice Note / Vehicle No / Reference <small class="text-muted fw-normal">(Optional)</small></span>
                        </label>
                        <input type="text" id="walkinModalRemarks" class="form-control form-control-sm" placeholder="e.g. Bike # KHI-5542 or Bill Reference... (Optional)">
                    </div>
                </form>
            </div>

            <div class="modal-footer py-2 bg-white d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnQuickResetStandardWalkin" title="Reset directly to default Walk-in Customer">
                    <i class="fas fa-undo me-1"></i> Reset to Default
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm" id="btnSaveWalkinModal">
                        <i class="fas fa-check-circle me-1"></i> Apply to Bill
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 7: Quick Add Customer Modal --}}
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 text-white" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold text-white mb-0"><i class="fas fa-user-plus me-2"></i> Quick Customer</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <form id="ajaxAddCustomerForm" autocomplete="off">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Customer Type <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="customer_type" id="modalCustomerType" required>
                                @foreach(\App\Models\CustomerType::orderBy('name')->get() as $type)
                                    <option value="{{ $type->name }}" {{ $type->name === 'Main Customer' ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="customer_name" id="modalCustomerName" required placeholder="Customer Name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Mobile</label>
                            <input type="text" class="form-control form-control-sm" name="mobile" placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Opening Balance</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" name="opening_balance" value="0">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label small fw-bold">Address</label>
                            <input type="text" class="form-control form-control-sm" name="address" placeholder="Address...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnSaveAjaxCustomer"><i class="fas fa-save me-1"></i> Save Customer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 8: Add Invoice Series --}}
<div class="modal fade" id="modalAddInvoiceSeries" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-2 text-white" style="background: linear-gradient(180deg, #1b4273 0%, #123055 100%);">
                <h6 class="modal-title fw-bold text-white mb-0"><i class="fas fa-barcode me-2"></i> Add Invoice Series</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAddInvoiceSeries">
                @csrf
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary">Prefix (e.g., SQ, POS, INV)</label>
                        <input type="text" name="prefix" class="form-control form-control-sm text-uppercase fw-bold" placeholder="e.g. SQ" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary">Starting Counter</label>
                        <input type="number" name="next_number" class="form-control form-control-sm fw-bold" placeholder="1" min="1" value="1" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary">Padding Length</label>
                        <select name="padding" class="form-select form-select-sm fw-bold">
                            <option value="4">4 Digits (0001)</option>
                            <option value="6" selected>6 Digits (000001)</option>
                            <option value="8">8 Digits (00000001)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalAddInvoiceSeries').modal('hide')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-3" id="btnSaveSeries">Save Series</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 9: Modern Visual Variant Picker & Odoo Enterprise Matrix View --}}
<div class="modal fade" id="modalVariantPicker" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-variant-picker-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden;">
            {{-- Header --}}
            <div class="modal-header py-2 px-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(180deg, #714b67 0%, #53334c 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group text-warning"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" id="pickerModalProductTitle">Select Product Variant</h6>
                        <small class="text-white-50" id="pickerModalProductSubtitle" style="font-size: 11px;"></small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Mode Switcher: Matrix vs Table -->
                    <div class="btn-group btn-group-sm p-0.5 bg-light rounded-pill border" role="group" aria-label="View Mode" style="border-color: #cbd5e1 !important;">
                        <button type="button" class="btn btn-sm px-3 fw-bold active rounded-pill" id="btnPickerModeMatrix" style="font-size: 11px; transition: all 0.2s; background: #714b67; color: #ffffff; padding: 3px 12px; border: none;">
                            <i class="fas fa-th-large me-1"></i> Matrix
                        </button>
                        <button type="button" class="btn btn-sm px-3 fw-bold rounded-pill btn-light text-muted" id="btnPickerModeTable" style="font-size: 11px; transition: all 0.2s; padding: 3px 12px; border: none;">
                            <i class="fas fa-table me-1"></i> Table
                        </button>
                    </div>

                    <button type="button" class="btn btn-link text-white p-0 text-decoration-none shadow-none" id="btnPickerModalCloseHeader" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 20px; line-height: 1; opacity: 0.85; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-3 bg-light">
                <!-- Loading Spinner -->
                <div id="pickerLoadingSpinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                    <p class="text-muted small mt-2 mb-0">Loading variants matrix...</p>
                </div>

                <!-- 1. Odoo Style Matrix View -->
                <div id="pickerMatrixContainer" class="bg-white p-3 rounded border">
                    <!-- KPI Ribbon -->
                    <div class="matrix-kpi-bar mb-3">
                        <div class="matrix-kpi-group">
                            <!-- Numeric Sky Price (Toggleable) -->
                            <div class="matrix-kpi-item picker-matrix-numeric-price d-none">
                                <span class="matrix-kpi-label">Sky Price</span>
                                <div class="matrix-kpi-value text-success" id="picker_matrix_item_price" style="color: #059669 !important;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-kpi-divider picker-matrix-numeric-divider d-none"></div>

                            <!-- Secret Sky P-Code -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Sky P-Code</span>
                                <div class="matrix-kpi-value text-primary" id="picker_matrix_item_pcode" style="color: #2563eb !important;">---</div>
                            </div>
                            <div class="matrix-kpi-divider"></div>

                            <!-- Numeric Rot Price (Toggleable) -->
                            <div class="matrix-kpi-item picker-matrix-numeric-price d-none">
                                <span class="matrix-kpi-label">Rot Price</span>
                                <div class="matrix-kpi-value text-info" id="picker_matrix_item_rot_price" style="color: #0284c7 !important;">Rs. 0.00</div>
                            </div>
                            <div class="matrix-kpi-divider picker-matrix-numeric-divider d-none"></div>

                            <!-- Secret Rot P-Code -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Rot P-Code</span>
                                <div class="matrix-kpi-value text-primary" id="picker_matrix_item_rot_pcode" style="color: #2563eb !important;">---</div>
                            </div>
                            <div class="matrix-kpi-divider"></div>

                            <!-- Free To Use Stock -->
                            <div class="matrix-kpi-item">
                                <span class="matrix-kpi-label">Stock</span>
                                <div class="matrix-kpi-value text-dark" id="picker_matrix_stock_display">0 Units</div>
                            </div>

                            <!-- Eye button to toggle numeric prices -->
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 border rounded-pill ms-2 d-inline-flex align-items-center shadow-sm" id="btnTogglePickerMatrixPriceDigits" title="Show/Hide Numeric Prices" style="background: #ffffff; color: #475569; font-size: 11px; font-weight: 600; cursor: pointer; height: 30px; border-color: #cbd5e1;">
                                <i class="fas fa-eye-slash" id="iconPickerMatrixPriceEye" style="margin-right: 5px;"></i>
                                <span id="textPickerMatrixPriceToggle">Show Prices</span>
                            </button>
                        </div>

                        <!-- Serial & Location Badge -->
                        <div id="picker_matrix_serial_badge_container" class="d-none text-end d-flex flex-column align-items-end justify-content-center">
                            <span class="badge font-monospace px-2.5 py-1" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11.5px; font-weight: 700; border-radius: 6px;" id="picker_matrix_serial_badge"></span>
                            <div id="picker_matrix_location_badge_wrap" class="mt-1 d-none">
                                <span class="badge font-monospace px-2 py-0.5 shadow-sm d-inline-flex align-items-center" id="picker_matrix_location_badge" style="background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; font-size: 10.5px; border-radius: 4px;" title="Variant Location">
                                    <i class="fas fa-map-marker-alt text-danger" style="font-size: 9px; margin-right: 4px;"></i>
                                    <span id="picker_matrix_location_text">---</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Attribute Groups with Radio Pills -->
                    <div id="pickerMatrixAttributeGroups" class="d-flex flex-column gap-2 mb-1">
                        <!-- Injected dynamically -->
                    </div>
                </div>

                <!-- 2. Standard Table View -->
                <div id="pickerTableContainer" class="d-none">
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="variantPickerSearchInput" class="form-control" placeholder="Filter variant by size, color, serial no...">
                    </div>
                    <div class="table-responsive bg-white border rounded" style="max-height: 340px; overflow-y: auto;">
                        <table class="table table-hover table-sm align-middle mb-0" id="variantPickerTable">
                            <thead class="table-light sticky-top" style="font-size: 11px;">
                                <tr>
                                    <th class="text-center" style="width: 100px;">Serial No</th>
                                    <th>Variant / Attribute</th>
                                    <th class="text-center" style="width: 80px;">Size</th>
                                    <th class="text-center" style="width: 80px;">Color</th>
                                    <th class="text-center" style="width: 80px;">Stock</th>
                                    <th class="text-center" style="width: 80px;">P-Code</th>
                                    <th class="text-end" style="width: 90px;">Price</th>
                                    <th class="text-center" style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="variantPickerTableBody">
                                <tr><td colspan="8" class="text-center py-4 text-muted">Loading variants...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
                <div id="pickerSelectedVariantInfo" class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="btnPickerModalCloseFooter" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm fw-bold px-3 shadow-sm d-inline-flex align-items-center text-white" id="btnPickerAddMatrixVariant" style="background: #00a09d; border-color: #008a87;" disabled>
                        <i class="fas fa-check me-1"></i> Add Variant
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Customize Sales Grid Table Columns (Image Replica & Matching Products UI) --}}
<div id="modalCustomizeSalesColumns" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <div class="modal-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-columns text-primary fs-6"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-dark" style="font-size: 14px;">Customize Table Columns</h6>
                        <span class="text-muted" style="font-size: 11px;">Show or hide columns in the Sales Receipt table</span>
                    </div>
                </div>
                <button type="button" class="btn btn-link text-secondary p-0 text-decoration-none shadow-none" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalCustomizeSalesColumns').modal('hide')" style="font-size: 18px; line-height: 1; cursor: pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="fw-semibold text-secondary" style="font-size: 12px;">Available Columns:</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 11px;" id="salesColSelectAll">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;" id="salesColResetDefault">Reset Default</button>
                    </div>
                </div>
                <div class="row g-2" id="salesColumnsChecklist">
                    @php
                        $salesCols = [
                            ['key' => 'col-item-no',     'label' => 'Item #'],
                            ['key' => 'col-item-name',   'label' => 'Item Name'],
                            ['key' => 'col-vendor-desc', 'label' => 'Vendor Description'],
                            ['key' => 'col-attribute',   'label' => 'Attribute'],
                            ['key' => 'col-size',        'label' => 'Size'],
                            ['key' => 'col-qty',         'label' => 'Qty'],
                            ['key' => 'col-price',       'label' => 'Price'],
                            ['key' => 'col-ext-price',   'label' => 'Ext Price'],
                            ['key' => 'col-sales-desc',  'label' => 'Sales Description'],
                        ];
                    @endphp
                    @foreach($salesCols as $sc)
                        <div class="col-6 col-sm-6">
                            <div class="col-toggle-card is-active" data-col="{{ $sc['key'] }}" id="card_sales_{{ $sc['key'] }}" onclick="toggleSalesColumnCard('{{ $sc['key'] }}')">
                                <input type="checkbox" id="chk_sales_{{ $sc['key'] }}" data-col="{{ $sc['key'] }}" class="sales-col-chk d-none" checked>
                                <span class="col-toggle-checkbox">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="col-toggle-label">
                                    {{ $sc['label'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fst-italic" style="font-size: 11px;"><i class="fas fa-info-circle me-1 text-primary"></i> Right-click table header anytime to open</span>
                <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#modalCustomizeSalesColumns').modal('hide')">Apply &amp; Close</button>
            </div>
        </div>
    </div>
</div>

<!-- QUICKBOOKS DESKTOP POS: CHOOSE UNIT OF MEASURE MODAL (ADD SALE) -->
<div class="modal fade" id="qbChooseUomModalAddSale" tabindex="-1" aria-labelledby="qbChooseUomModalAddSaleLabel" aria-hidden="true" style="z-index: 1080;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header py-2 px-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center mb-0" id="qbChooseUomModalAddSaleLabel" style="font-size: 14px;">
                    <i class="fas fa-layer-group text-primary me-2"></i> Choose Unit of Measure
                </h6>
                <button type="button" class="close text-dark" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="border: none; background: transparent; font-size: 22px; line-height: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="text-muted small mb-2">
                    Select the unit of measure to sell <strong id="qbAddSaleModalProductName" class="text-dark"></strong>:
                </div>
                <div class="d-flex flex-column gap-2" id="qbAddSaleModalUnitsContainer" style="max-height: 320px; overflow-y: auto;">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="btnConfirmQbAddSaleUomChoice">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 10: Item Price Entry / Zero Item Price (Image 1 & 2 Replica) --}}
<div class="modal fade" id="modalZeroPriceItem" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden; border: 1.5px solid #1e3a5f;">
            <div class="qb-zero-price-header d-flex justify-content-between align-items-center">
                <span id="zpModalTitle" class="fw-bold" style="font-size: 14px;">Item Price</span>
                <button type="button" class="btn btn-link text-white p-0 text-decoration-none shadow-none" data-dismiss="modal" data-bs-dismiss="modal" id="btnZPHeaderClose" style="font-size: 18px; line-height: 1; opacity: 0.85; cursor: pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body p-3" style="background: #eef2f8;">
                <div class="d-flex align-items-center gap-3 mb-2.5" id="zpPriceTitleRow" style="display: none;">
                    <div class="qb-info-circle-big">i</div>
                    <div class="fs-5 fw-bolder text-dark" id="zpPriceTitle"></div>
                </div>

                <div class="qb-zero-price-box mb-2.5">
                    <table class="qb-modal-spec-table">
                        <tr>
                            <td class="spec-label">Item Name</td>
                            <td class="spec-value" id="zpItemName">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Sales Description</td>
                            <td class="spec-value" id="zpSalesDesc">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Department</td>
                            <td class="spec-value" id="zpDept">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Vendor Name</td>
                            <td class="spec-value" id="zpVendor">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Attribute</td>
                            <td class="spec-value" id="zpAttribute">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Size</td>
                            <td class="spec-value" id="zpSize">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Unit of Measure</td>
                            <td class="spec-value" id="zpUnit">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">P-Code</td>
                            <td class="spec-value font-monospace" id="zpPCode">
                                <span class="badge text-white font-monospace" style="background: #1e40af; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: 4px;">Sky: <span id="zpSkyVal">-</span></span>
                                <span class="badge text-white font-monospace ms-1" style="background: #0f172a; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: 4px;">Root: <span id="zpRotVal">-</span></span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="text-secondary small mb-2 text-center" id="zpDescText" style="line-height: 1.35; font-size: 12.5px;">
                    Enter the price to be charged on the document.
                </div>

                {{-- Price Input Field --}}
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1.5">
                    <label class="fw-bold text-dark mb-0" style="font-size: 13.5px;">New Price</label>
                    <input type="number" step="any" id="zpNewPriceInput" class="form-control form-control-sm text-end fw-bold fs-6" style="max-width: 150px; border: 1.5px solid #1e3a5f; height: 36px; border-radius: 4px;" placeholder="0.00" min="0">
                </div>

                {{-- Root Price & ROT Code Info (Hidden as requested) --}}
                <div class="text-center small mb-2" id="zpRootPriceInfo" style="font-size: 11.5px; font-weight: 600; color: #475569; display: none;">
                    Root Price: <span class="badge bg-secondary font-monospace" id="zpRootPriceBadge">Rs. 0.00</span>
                    <span id="zpRotCodeBadge" class="badge bg-dark font-monospace ms-1" style="display: none;">ROT: <span id="zpRotCodeVal"></span></span>
                </div>

                {{-- Price Below Root Warning Banner --}}
                <div id="zpPriceBelowRootWarning" class="alert alert-warning py-1.5 px-2 mb-2 small d-none" style="font-size: 12px; border-radius: 4px; border-left: 4px solid #d97706; background: #fffbeb; color: #92400e;">
                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                    <strong>Need Admin Permission:</strong> Price <span class="badge bg-danger font-monospace" id="zpWarnEnteredPrice">Rs. 0.00</span> is less than Root Price.
                </div>

                <div class="pt-2 border-top border-secondary-subtle">
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="chkUpdateSalePrice">
                        <label class="form-check-label text-dark small" for="chkUpdateSalePrice">
                            Update my Regular Sales Price in inventory
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="chkDontAskZeroPrice">
                        <label class="form-check-label text-muted small" for="chkDontAskZeroPrice">
                            Don't ask me this question in the future
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between" style="background: #e2e8f0;">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal" id="btnZPCancel" onclick="$('#modalZeroPriceItem').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold px-4" id="btnZPContinue">Continue</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 11: Insufficient Quantity / Enter Quantity (Image 3 Replica) --}}
<div class="modal fade" id="modalQtyEntry" tabindex="-1" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 485px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 6px; overflow: hidden; border: 1.5px solid #1e3a5f;">
            <div id="qeModalHeader" class="qb-insufficient-header">
                <span id="qeModalTitle" class="fw-bold" style="font-size: 14px;">Insufficient Quantity</span>
                <button type="button" class="btn btn-link text-white p-0 text-decoration-none shadow-none" id="btnQEHeaderClose" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 18px; line-height: 1; opacity: 0.9; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-2 px-3" style="background: #f1f5f9;">
                <div id="qeInsufficientAlert" class="mb-2 text-center" style="display: none; background: #fee2e2; border: 1.5px solid #ef4444; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 13.5px; font-weight: 800;">
                    <i class="fas fa-exclamation-triangle me-1 text-danger"></i> You don't have sufficient quantity to sell this item:
                </div>

                <div class="qb-zero-price-box mb-2">
                    <table class="qb-modal-spec-table">
                        <tr>
                            <td class="spec-label">Item Name</td>
                            <td class="spec-value" id="qeItemName">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Sales Description</td>
                            <td class="spec-value" id="qeSalesDesc">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Department</td>
                            <td class="spec-value" id="qeDept">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Vendor Name</td>
                            <td class="spec-value" id="qeVendor">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Attribute</td>
                            <td class="spec-value" id="qeAttribute">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Size</td>
                            <td class="spec-value" id="qeSize">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Unit of Measure</td>
                            <td class="spec-value" id="qeUnit">-</td>
                        </tr>
                        <tr>
                            <td class="spec-label">P-Code</td>
                            <td class="spec-value font-monospace" id="qePCode">
                                <span class="badge text-white font-monospace" style="background: #1e40af; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: 4px;">Sky: <span id="qeSkyVal">-</span></span>
                                <span class="badge text-white font-monospace ms-1" style="background: #0f172a; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: 4px;">Root: <span id="qeRotVal">-</span></span>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Stock Status Breakdown Lines --}}
                <div class="bg-white rounded border px-3 py-1.5 mb-2 shadow-sm" style="border-color: #cbd5e1 !important;">
                    <div class="qb-qty-stat-line">
                        <span style="color: #334155 !important; font-size: 14.5px !important; font-weight: 500 !important;">Quantity on hand:</span>
                        <span class="fw-bold" id="qeQtyOnHand" style="color: #0f172a !important; font-size: 15px !important;">0</span>
                    </div>
                    <div class="qb-qty-stat-line">
                        <span style="color: #334155 !important; font-size: 14.5px !important; font-weight: 500 !important;">Quantity on Customer Orders:</span>
                        <span class="fw-bold" style="color: #64748b !important; font-size: 15px !important;">0</span>
                    </div>
                    <div class="qb-qty-stat-line total-avail" style="border-top: 1.5px solid #cbd5e1 !important; margin-top: 3px; padding-top: 3px;">
                        <span style="color: #0f172a !important; font-size: 15px !important; font-weight: 700 !important;">Quantity Available:</span>
                        <span class="fw-bolder" id="qeQtyAvail" style="color: #1d4ed8 !important; font-size: 17px !important;">0</span>
                    </div>
                </div>

                {{-- Confirmed Price Line (Read-only from Step 1) --}}
                <div class="d-flex align-items-center justify-content-between px-2 py-1 mb-2 bg-white rounded border" style="border-color: #cbd5e1 !important;">
                    <span style="font-size: 13.5px; font-weight: 600; color: #475569;">
                        <i class="fas fa-tag text-primary me-1"></i> Confirmed Unit Price:
                    </span>
                    <span class="badge bg-success font-monospace fw-bold px-2.5 py-1" id="qeConfirmedPriceBadge" style="font-size: 14px;">Rs. 0.00</span>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 mb-2 px-1">
                    <label class="mb-0" style="font-size: 14.5px !important; font-weight: 600 !important; color: #1e293b !important;">Enter sales receipt quantity:</label>
                    <input type="number" step="any" id="qeQtyInput" class="form-control text-center" style="max-width: 90px; height: 34px; border: 1.5px solid #334155; border-radius: 4px; font-size: 16px !important; font-weight: 700 !important; color: #000000 !important;" value="1" min="1" autofocus>
                </div>

                <div class="form-check mt-1 pt-1.5 border-top border-dark-subtle" id="qeDontWarnRow" style="display: none;">
                    <input class="form-check-input" type="checkbox" id="chkDontWarnQty" style="cursor: pointer; width: 17px; height: 17px; border: 1.5px solid #475569;">
                    <label class="form-check-label ms-1" for="chkDontWarnQty" style="font-size: 13.5px !important; font-weight: 500 !important; color: #334155 !important; cursor: pointer; line-height: 1.3;">
                        Don't warn me again about insufficient item quantity
                    </label>
                </div>
            </div>
            <div class="modal-footer py-1.5 px-3 d-flex justify-content-between" style="background: #e2e8f0;">
                <button type="button" class="btn btn-secondary btn-sm px-3" id="btnQESeek" style="font-size: 13.5px; font-weight: 500;">Seek</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger btn-sm px-3" id="btnQERemoveItem" style="font-size: 13.5px; font-weight: 600;">Remove Item</button>
                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnQEContinue" style="font-size: 14px; font-weight: 700;">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 12: Staff Waiting for Price Approval --}}
<div class="modal fade" id="modalStaffWaitingApproval" tabindex="-1" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" aria-hidden="true" style="z-index: 1085;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden; border: 2px solid #2563eb !important;">
            <div class="modal-header py-2.5 px-3 text-white" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border-bottom: 2px solid #3b82f6;">
                <div class="d-flex align-items-center gap-2">
                    <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 13.5px;">Waiting for Admin Permission</h6>
                </div>
                <span class="badge bg-light text-primary font-monospace" id="waitingReqIdBadge">Req #--</span>
            </div>
            <div class="modal-body p-3 text-center" style="background: #f8fafc;">
                <div class="py-2">
                    <div class="mb-3">
                        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Waiting...</span>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" id="waitingItemNameText">-</h6>
                    <p class="text-muted small mb-3" id="waitingItemDescText">-</p>

                    <div class="card border p-2 mb-3 bg-white" style="border-color: #cbd5e1 !important;">
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small">Quantity:</span>
                            <span class="fw-bold text-dark" id="waitingQtyText">1</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small">Root / Floor Price:</span>
                            <span class="fw-bold text-secondary font-monospace" id="waitingRootPriceText">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <span class="text-danger small fw-bold">Requested Price:</span>
                            <span class="fw-bolder text-danger fs-6 font-monospace" id="waitingReqPriceText">Rs. 0.00</span>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 small mb-0 text-start" style="font-size: 11.5px; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                        <i class="fas fa-info-circle me-1"></i>
                        A notification has been sent to Admin / Super Admin. This screen will automatically update as soon as Admin allows or rejects the request.
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 d-flex justify-content-between bg-light" style="border-top: 1px solid #cbd5e1;">
                <span class="text-muted small" style="font-size: 11px;">
                    <i class="fas fa-sync fa-spin me-1 text-primary"></i> Listening for Admin...
                </span>
                <button type="button" class="btn btn-secondary btn-sm px-3 fw-bold" id="btnCancelWaitingApproval">
                    <i class="fas fa-times me-1"></i> Cancel Request
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// ==============================================================
// GLOBAL CATALOG TOGGLE & F4 KEY HANDLER (IMMEDIATE AVAILABILITY)
// ==============================================================
window.openCatalog = function(e) {
    if (e && typeof e.stopPropagation === 'function') e.stopPropagation();
    if (e && typeof e.preventDefault === 'function') e.preventDefault();

    const panel = document.getElementById('qbCatalogPanel');
    const backdrop = document.getElementById('qbCatalogBackdrop');
    if (panel) panel.classList.add('open');
    if (backdrop) backdrop.classList.add('open');
    document.body.classList.add('catalog-open');

    const topBtn = document.getElementById('qbBtnToggleCatalog');
    if (topBtn) {
        topBtn.classList.add('active');
        topBtn.innerHTML = '<i class="fas fa-times me-1"></i> <span>Hide Slider</span> <kbd>F4</kbd>';
    }
    const bannerBtn = document.getElementById('qbBtnToggleCatalogBanner');
    if (bannerBtn) {
        bannerBtn.classList.add('active');
        bannerBtn.innerHTML = '<i class="fas fa-times me-1"></i> <span>Hide Slider</span>';
    }
    const arrow = document.getElementById('qbHandleArrow');
    if (arrow) arrow.className = 'fas fa-chevron-right';

    const sideBtn = document.getElementById('btnQuickPick');
    if (sideBtn) sideBtn.classList.add('active-btn-glow');

    if (typeof window.loadCatalogCategories === 'function' && !window.catalogCategoriesLoaded) {
        window.loadCatalogCategories();
    }
    if (typeof window.loadCatalogProducts === 'function' && !window.catalogProductsLoaded) {
        window.loadCatalogProducts(window.activeCatalogCategory || 'all');
    }
};

window.closeCatalog = function(e) {
    if (e && typeof e.stopPropagation === 'function') e.stopPropagation();
    if (e && typeof e.preventDefault === 'function') e.preventDefault();

    const panel = document.getElementById('qbCatalogPanel');
    const backdrop = document.getElementById('qbCatalogBackdrop');
    if (panel) panel.classList.remove('open');
    if (backdrop) backdrop.classList.remove('open');
    document.body.classList.remove('catalog-open');

    const topBtn = document.getElementById('qbBtnToggleCatalog');
    if (topBtn) {
        topBtn.classList.remove('active');
        topBtn.innerHTML = '<i class="fas fa-th-large me-1"></i> <span>Catalog Slider</span> <kbd>F4</kbd>';
    }
    const bannerBtn = document.getElementById('qbBtnToggleCatalogBanner');
    if (bannerBtn) {
        bannerBtn.classList.remove('active');
        bannerBtn.innerHTML = '<i class="fas fa-th-large me-1 text-primary"></i> <span>Show Products Slider</span>';
    }
    const arrow = document.getElementById('qbHandleArrow');
    if (arrow) arrow.className = 'fas fa-chevron-left';

    const sideBtn = document.getElementById('btnQuickPick');
    if (sideBtn) sideBtn.classList.remove('active-btn-glow');
};

window.toggleCatalog = function(e) {
    if (e && typeof e.stopPropagation === 'function') e.stopPropagation();
    if (e && typeof e.preventDefault === 'function') e.preventDefault();

    const panel = document.getElementById('qbCatalogPanel');
    if (panel && panel.classList.contains('open')) {
        window.closeCatalog(e);
    } else {
        window.openCatalog(e);
    }
};

function toggleCatalog(e) { window.toggleCatalog(e); }
function openCatalog(e) { window.openCatalog(e); }
function closeCatalog(e) { window.closeCatalog(e); }

// Global Keyboard Shortcuts (F4 Toggle Catalog, Escape Hide Catalog / Modals)
window.addEventListener('keydown', function(e) {
    if (e.key === 'F4' || e.keyCode === 115) {
        e.preventDefault();
        e.stopPropagation();
        window.toggleCatalog();
    } else if (e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27) {
        // If an open modal exists, hide it first
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            e.preventDefault();
            e.stopPropagation();
            $(openModal).modal('hide');
            return;
        }
        // If catalog panel is open, close/hide it
        const panel = document.getElementById('qbCatalogPanel');
        if (panel && panel.classList.contains('open')) {
            e.preventDefault();
            e.stopPropagation();
            window.closeCatalog(e);
        }
    }
}, true);

// Auto-reveal Side Catalog Button when cursor connects to the right boundary of the device
(function initSideCatalogEdgeDetection() {
    let hideTimer = null;

    function getHandle() {
        return document.getElementById('qbFloatingToggleCatalog');
    }

    function revealHandle() {
        if (document.body.classList.contains('catalog-open')) return;
        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }
        const handle = getHandle();
        if (handle) handle.classList.add('qb-handle-revealed');
    }

    function scheduleHideHandle(delay = 400) {
        if (document.body.classList.contains('catalog-open')) return;
        if (hideTimer) clearTimeout(hideTimer);
        hideTimer = setTimeout(function() {
            const handle = getHandle();
            if (handle) handle.classList.remove('qb-handle-revealed');
        }, delay);
    }

    // Window-level mouse tracking: check distance from right boundary of screen
    window.addEventListener('mousemove', function(e) {
        if (document.body.classList.contains('catalog-open')) return;

        // When mouse cursor reaches within 18px of the right boundary of device
        const distFromRight = window.innerWidth - e.clientX;
        if (distFromRight <= 18) {
            revealHandle();
        } else if (distFromRight > 85) {
            // Once cursor moves away past the handle width into workspace
            scheduleHideHandle(250);
        }
    }, { passive: true });

    // Direct event bindings on sensor & handle
    function bindListeners() {
        const handle = getHandle();
        const sensor = document.getElementById('qbRightEdgeSensor');

        if (sensor) {
            sensor.addEventListener('mouseenter', revealHandle);
        }
        if (handle) {
            handle.addEventListener('mouseenter', revealHandle);
            handle.addEventListener('mouseleave', function() {
                scheduleHideHandle(400);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindListeners);
    } else {
        bindListeners();
    }
})();

// ==============================================================
// SALES GRID COLUMN VISIBILITY SYSTEM (MATCHING PRODUCT CREATE)
// ==============================================================
window.SALES_GRID_COLUMNS = [
    { key: 'col-item-no',     label: 'Item #',             default: true },
    { key: 'col-item-name',   label: 'Item Name',          default: true },
    { key: 'col-vendor-desc', label: 'Vendor Description', default: true },
    { key: 'col-attribute',   label: 'Attribute',          default: true },
    { key: 'col-size',        label: 'Size',               default: true },
    { key: 'col-qty',         label: 'Qty',                default: true },
    { key: 'col-price',       label: 'Price',              default: true },
    { key: 'col-ext-price',   label: 'Ext Price',          default: true },
    { key: 'col-sales-desc',  label: 'Sales Description',  default: true }
];
window.SALES_GRID_COL_STORAGE_KEY = 'kent_sales_grid_columns_v2';

window.getSalesGridColPrefs = function() {
    const defaults = {};
    window.SALES_GRID_COLUMNS.forEach(function(c) { defaults[c.key] = c.default; });
    try {
        const saved = localStorage.getItem(window.SALES_GRID_COL_STORAGE_KEY);
        if (saved) {
            const parsed = JSON.parse(saved);
            if (parsed && typeof parsed === 'object') {
                return Object.assign({}, defaults, parsed);
            }
        }
    } catch(e) {}
    return defaults;
};

window.saveSalesGridColPrefs = function(prefs) {
    try {
        localStorage.setItem(window.SALES_GRID_COL_STORAGE_KEY, JSON.stringify(prefs));
    } catch(e) {}
};

window.applySalesGridColVisibility = function(prefs) {
    if (!prefs) prefs = window.getSalesGridColPrefs();
    let styleEl = document.getElementById('kentSalesGridColumnsCustomStyle');
    if (!styleEl) {
        styleEl = document.createElement('style');
        styleEl.id = 'kentSalesGridColumnsCustomStyle';
        document.head.appendChild(styleEl);
    }

    const cssRules = [];
    window.SALES_GRID_COLUMNS.forEach(function(col) {
        const isVisible = (prefs[col.key] !== false);
        if (!isVisible) {
            cssRules.push(
                '#qbMainGridTable .' + col.key + ' { display: none !important; }\n' +
                '#qbMainGridTable th.' + col.key + ' { display: none !important; }\n' +
                '#qbMainGridTable td.' + col.key + ' { display: none !important; }'
            );
        }
        document.querySelectorAll('#qbMainGridTable .' + col.key).forEach(function(el) {
            if (isVisible) {
                el.style.removeProperty('display');
            } else {
                el.style.setProperty('display', 'none', 'important');
            }
        });
    });
    styleEl.textContent = cssRules.join('\n');
};

window.updateSalesCardUI = function(colKey, isChecked) {
    const card = document.getElementById('card_sales_' + colKey);
    const chk = document.getElementById('chk_sales_' + colKey);
    if (chk) chk.checked = isChecked;
    if (card) {
        if (isChecked) {
            card.classList.add('is-active');
        } else {
            card.classList.remove('is-active');
        }
    }
};

window.toggleSalesColumnCard = function(colKey) {
    const prefs = window.getSalesGridColPrefs();
    const currentVal = (prefs[colKey] !== false);
    const newVal = !currentVal;

    // Prevent hiding all columns (at least 1 must remain visible)
    if (!newVal) {
        let activeCount = 0;
        window.SALES_GRID_COLUMNS.forEach(function(c) {
            if (c.key === colKey) return;
            if (prefs[c.key] !== false) activeCount++;
        });
        if (activeCount === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'At least one column must remain visible',
                    showConfirmButton: false,
                    timer: 1600
                });
            }
            return;
        }
    }

    prefs[colKey] = newVal;
    window.updateSalesCardUI(colKey, newVal);
    window.saveSalesGridColPrefs(prefs);
    window.applySalesGridColVisibility(prefs);
};

window.syncSalesColumnsModalCards = function() {
    const prefs = window.getSalesGridColPrefs();
    window.SALES_GRID_COLUMNS.forEach(function(col) {
        const isChecked = (prefs[col.key] !== false);
        window.updateSalesCardUI(col.key, isChecked);
    });
};

window.openCustomizeSalesColumnsModal = function(e) {
    if (e && typeof e.preventDefault === 'function') e.preventDefault();
    if (e && typeof e.stopPropagation === 'function') e.stopPropagation();
    if (typeof window.syncSalesColumnsModalCards === 'function') {
        window.syncSalesColumnsModalCards();
    }
    $('#modalCustomizeSalesColumns').modal('show');
};

// Immediate application on parse so columns do not flicker
window.applySalesGridColVisibility();
window.isUserAdmin = {{ auth()->check() && auth()->user()->isSuperAdmin() ? 'true' : 'false' }};
window.currentUserId = {{ auth()->id() ?? 'null' }};
window.currentUserName = "{{ addslashes(auth()->user()->name ?? 'Staff') }}";

$(document).ready(function() {
    // CSRF Token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    let currentActivePaymentMethod = 'cash';
    let selectedRowUid = null;
    let gridRows = []; // State of rows in table

    // Global Payment Tender & Account Allocation State
    window.orderPaymentAllocation = {
        method: 'cash',
        cashAmount: 0,
        bankAmount: 0,
        cashAccountId: "{{ $defaultCashAccountId ?? 2 }}",
        bankAccountId: "{{ $defaultBankAccountId ?? 3 }}",
        khataAmount: 0
    };

    // ==============================================================
    // MULTI-ORDER BILL MANAGEMENT (ODOO POS STYLE)
    // ==============================================================
    let orders = [];
    let activeOrderIndex = 0;

    function createEmptyOrder(num) {
        return {
            id: 'ord_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            number: num,
            gridRows: [],
            selectedRowUid: null,
            paymentMethod: 'cash',
            paymentAllocation: {
                method: 'cash',
                cashAmount: 0,
                bankAmount: 0,
                cashAccountId: "{{ $defaultCashAccountId ?? 2 }}",
                bankAccountId: "{{ $defaultBankAccountId ?? 3 }}",
                khataAmount: 0
            },
            customer: {
                id: '',
                is_walkin: '1',
                walkin_name: 'Walk-in Customer',
                address: '',
                tel: '',
                previousBalance: '0',
                rangeBalance: '0',
                statusLabel: 'Chase up,',
                detailsText: '',
                salesOfficerId: ''
            },
            carpenter: {
                id: '',
                type: 'percentage',
                rate: '0',
                amount: '0',
                name: 'Carpenter'
            },
            notes: ''
        };
    }

    function saveCurrentOrderState() {
        if (!orders || !orders[activeOrderIndex]) return;
        orders[activeOrderIndex].gridRows = JSON.parse(JSON.stringify(gridRows));
        orders[activeOrderIndex].selectedRowUid = selectedRowUid;
        orders[activeOrderIndex].paymentMethod = currentActivePaymentMethod;
        orders[activeOrderIndex].paymentAllocation = JSON.parse(JSON.stringify(orderPaymentAllocation));
        orders[activeOrderIndex].customer = {
            id: $('#customerSelect').val() || '',
            is_walkin: $('#is_walkin').val() || '1',
            walkin_name: $('#walkinNameInput').val() || 'Walk-in Customer',
            walkin_phone: $('#walkinPhoneInput').val() || $('#tel').val() || '',
            walkin_address: $('#walkinAddressInput').val() || $('#address').val() || '',
            walkin_cnic: $('#walkinCnicInput').val() || '',
            address: $('#address').val() || '',
            tel: $('#tel').val() || '',
            previousBalance: $('#previousBalance').val() || '0',
            rangeBalance: $('#rangeBalance').val() || '0',
            statusLabel: $('#qbCustomerStatusLabel').text() || 'Chase up,',
            detailsText: $('#qbCustomerDetailsText').text() || '',
            salesOfficerId: $('#salesOfficerId').val() || ''
        };
        orders[activeOrderIndex].carpenter = {
            id: $('#carpenter_id').val() || '',
            type: $('#carpenter_commission_type').val() || 'percentage',
            rate: $('#carpenter_commission_rate').val() || '0',
            amount: $('#carpenter_commission_amount').val() || '0',
            name: $('#carpenterBtnText').text() || 'Carpenter'
        };
        orders[activeOrderIndex].notes = $('#saleNotes').length ? $('#saleNotes').val() : '';
    }

    function loadOrderState(index) {
        if (index < 0 || index >= orders.length) return;
        activeOrderIndex = index;
        const ord = orders[index];

        // 1. Restore gridRows
        gridRows = JSON.parse(JSON.stringify(ord.gridRows || []));
        selectedRowUid = ord.selectedRowUid || null;

        // 2. Restore customer details
        const c = ord.customer;
        if (c && c.is_walkin === '0' && c.id) {
            setCustomer({
                id: c.id,
                customer_name: c.walkin_name,
                address: c.address,
                mobile: c.tel,
                previous_balance: c.previousBalance,
                balance_range: c.rangeBalance,
                sales_officer_id: c.salesOfficerId
            });
        } else {
            applyWalkinDetails({
                name: (c && c.walkin_name) ? c.walkin_name : 'Walk-in Customer',
                phone: (c && (c.walkin_phone || c.tel)) ? (c.walkin_phone || c.tel) : '',
                address: (c && (c.walkin_address || c.address)) ? (c.walkin_address || c.address) : '',
                cnic: (c && c.walkin_cnic) ? c.walkin_cnic : ''
            });
        }

        // 3. Restore payment method & allocation
        if (ord.paymentAllocation) {
            orderPaymentAllocation = JSON.parse(JSON.stringify(ord.paymentAllocation));
            currentActivePaymentMethod = orderPaymentAllocation.method || 'cash';
        } else {
            currentActivePaymentMethod = ord.paymentMethod || 'cash';
            orderPaymentAllocation = {
                method: currentActivePaymentMethod,
                cashAmount: 0,
                bankAmount: 0,
                cashAccountId: "{{ $defaultCashAccountId ?? 2 }}",
                bankAccountId: "{{ $defaultBankAccountId ?? 3 }}",
                khataAmount: 0
            };
        }
        $('.qb-pay-tab').removeClass('active');
        $(`.qb-pay-tab[data-method="${currentActivePaymentMethod}"]`).addClass('active');

        // 4. Restore notes
        if ($('#saleNotes').length && ord.notes !== undefined) {
            $('#saleNotes').val(ord.notes);
        }

        // 5. Restore Carpenter Commission
        const carp = ord.carpenter || {};
        $('#carpenter_id').val(carp.id || '');
        $('#carpenter_commission_type').val(carp.type || 'percentage');
        $('#carpenter_commission_rate').val(carp.rate || '0');
        $('#carpenter_commission_amount').val(carp.amount || '0');

        if (carp.id && parseInt(carp.id) > 0) {
            const dispName = (carp.name && carp.name !== 'Carpenter') ? carp.name : 'Carpenter';
            $('#carpenterBtnText').text(dispName.length > 10 ? dispName.substring(0, 10) + '...' : dispName);
            const badgeText = carp.type === 'fixed' ? ('Rs.' + carp.rate) : (carp.rate + '%');
            $('#carpenterActiveBadge').removeClass('d-none').text(badgeText);
            $('#btnCarpenterCommission').addClass('border-success text-success');
        } else {
            $('#carpenterBtnText').text('Carpenter');
            $('#carpenterActiveBadge').addClass('d-none');
            $('#btnCarpenterCommission').removeClass('border-success text-success');
        }

        renderGrid();
        renderOrderTabs();
        syncPaymentAccounts();
    }

    function renderOrderTabs() {
        const $container = $('#qbOrderTabsWrapper');
        if (!$container.length) return;
        $container.empty();

        orders.forEach((ord, i) => {
            const isActive = (i === activeOrderIndex);
            const count = (ord.gridRows ? ord.gridRows.length : 0);
            const countBadge = count > 0 
                ? `<span class="qb-order-badge">${count}</span>` 
                : '';

            const $tab = $(`
                <div class="qb-order-tab ${isActive ? 'active' : ''}" data-index="${i}" title="Order #${ord.number} (${count} items)">
                    <i class="fas fa-receipt" style="font-size: 10px; opacity: 0.85;"></i>
                    <span>Order ${ord.number}</span>
                    ${countBadge}
                    <span class="qb-order-tab-close" data-index="${i}" title="Discard Order #${ord.number}">&times;</span>
                </div>
            `);
            $container.append($tab);
        });
    }

    function addNewOrder() {
        saveCurrentOrderState();
        const nextNum = orders.length + 1;
        const newOrd = createEmptyOrder(nextNum);
        orders.push(newOrd);
        activeOrderIndex = orders.length - 1;
        loadOrderState(activeOrderIndex);
    }

    function discardOrder(index, e) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        saveCurrentOrderState();
        const ord = orders[index];
        if (!ord) return;

        const count = ord.gridRows ? ord.gridRows.length : 0;

        function executeDiscard() {
            orders.splice(index, 1);
            if (orders.length === 0) {
                orders = [createEmptyOrder(1)];
                activeOrderIndex = 0;
            } else {
                // Renumber remaining orders sequentially so series stays clean (1, 2, 3...)
                orders.forEach((o, i) => {
                    o.number = i + 1;
                });
                if (activeOrderIndex >= orders.length) {
                    activeOrderIndex = orders.length - 1;
                } else if (activeOrderIndex > index) {
                    activeOrderIndex -= 1;
                }
            }
            loadOrderState(activeOrderIndex);
        }

        if (count > 0) {
            Swal.fire({
                title: `Discard Order ${ord.number}?`,
                text: `This bill has ${count} item(s). All entered items will be discarded.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Discard',
                cancelButtonText: 'Keep Bill'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDiscard();
                }
            });
        } else {
            // Discard empty tab immediately
            executeDiscard();
        }
    }

    // Switch Order Tab on Click
    $(document).on('click', '.qb-order-tab', function(e) {
        if ($(e.target).closest('.qb-order-tab-close').length) return;
        const idx = parseInt($(this).data('index'));
        if (!isNaN(idx) && idx !== activeOrderIndex) {
            saveCurrentOrderState();
            loadOrderState(idx);
        }
    });

    // Discard Tab Cross Button Click
    $(document).on('click', '.qb-order-tab-close', function(e) {
        e.stopPropagation();
        e.preventDefault();
        const idx = parseInt($(this).data('index'));
        if (!isNaN(idx)) {
            discardOrder(idx, e);
        }
    });

    // Add Order (+) Button Click
    $(document).on('click', '#qbBtnAddOrder', function(e) {
        e.preventDefault();
        addNewOrder();
    });

    // Keyboard shortcut: Ctrl + Alt + N to create new order bill tab
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.altKey && (e.key === 'n' || e.key === 'N')) {
            e.preventDefault();
            addNewOrder();
        }
    });

    // Initial order setup
    orders = [createEmptyOrder(1)];
    activeOrderIndex = 0;

    // Audio feedback for barcode scan (subtle beep)
    function playBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            gain.gain.value = 0.08;
            osc.start();
            setTimeout(() => { osc.stop(); ctx.close(); }, 80);
        } catch(e) {}
    }

    // ==============================================================
    // 1. AUTOCOMPLETE & DROPDOWN HELPER FUNCTIONS
    // ==============================================================
    function navigateDropdown($container, direction) {
        if (!$container.is(':visible')) return;
        const items = $container.find('.qb-autocomplete-item');
        if (!items.length) return;
        let index = items.index(items.filter('.selected'));
        index += direction;
        if (index < 0) index = items.length - 1;
        if (index >= items.length) index = 0;
        items.removeClass('selected');
        const $curr = items.eq(index).addClass('selected');
        if ($curr.length && $curr[0].scrollIntoView) {
            $curr[0].scrollIntoView({ block: 'nearest' });
        }
    }

    // ==============================================================
    // 2. AUTOCOMPLETE: SCAN OR ENTER ITEM INFORMATION
    // ==============================================================
    let itemSearchTimeout = null;
    const $itemInput = $('#qbItemSearchInput');
    const $itemResults = $('#qbItemSearchResults');

    function renderItemDropdown(results) {
        if (!results || !results.length) {
            $itemResults.html(`
                <div class="p-3 text-muted text-center" style="font-size:12px;">
                    <i class="fas fa-box-open fa-2x text-secondary mb-2 d-block"></i>
                    No products found
                </div>
            `).show();
            return;
        }

        let html = `
            <div class="px-2 py-1 bg-light border-bottom text-muted d-flex justify-content-between align-items-center" style="font-size: 10.5px; font-weight: 600;">
                <span><i class="fas fa-boxes me-1 text-primary"></i> PRODUCTS (${results.length})</span>
                <span class="text-secondary">Click or Enter to select</span>
            </div>
            <div style="max-height: 270px; overflow-y: auto;">
        `;

        results.forEach((item, index) => {
            const sku = item.sku || item.item_code || item.serial_no || '-';
            const itemNumberBadge = item.item_number ? `<span class="badge bg-primary text-white font-monospace ms-1" style="font-size:10.5px;">Item #: ${item.item_number}</span>` : '';
            const pCodeBadge = item.p_code ? `<span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace ms-1" style="font-size:10px;">P: ${item.p_code}</span>` : '';
            const stockQty = item.stock || item.stock_pieces || 0;
            const stockBadge = stockQty > 0 
                ? `<span class="badge bg-success-subtle text-success border border-success-subtle px-1">Stock: ${stockQty}</span>`
                : `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1">Out of stock</span>`;
            const price = parseFloat(item.price || item.retail_price || item.sale_price || 0).toFixed(2);
            const desc = item.sales_desc || item.name || item.text || '';

            html += `
            <div class="qb-autocomplete-item qb-product-search-item ${index === 0 ? 'selected' : ''}" data-json='${JSON.stringify(item).replace(/'/g, "&apos;")}'>
                <div style="flex: 1; min-width: 0; padding-right: 8px;">
                    <div class="text-truncate">
                        <strong class="text-primary" style="font-size: 13.5px; font-weight: 700;">${desc}</strong> 
                        ${itemNumberBadge}
                        <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace ms-1" style="font-size: 10.5px;">#${sku}</span>
                        ${pCodeBadge}
                    </div>
                    ${item.color || item.size ? `<small class="text-muted" style="font-size:10.5px;">Var: ${item.color || ''} ${item.size ? '/ ' + item.size : ''}</small>` : ''}
                </div>
                <div class="text-end flex-shrink-0">
                    ${stockBadge}
                </div>
            </div>`;
        });

        html += `</div>`;
        $itemResults.html(html).show();
    }

    function fetchAndShowItemDropdown(term = '') {
        $custResults.hide();
        $itemResults.html('<div class="p-3 text-center text-muted" style="font-size:11.5px;"><i class="fas fa-spinner fa-spin me-2 text-primary"></i> Loading products...</div>').show();

        $.ajax({
            url: '{{ route('products.ajax.search', [], false) }}',
            dataType: 'json',
            data: { term: term },
            success: function(data) {
                const results = data.results || [];
                renderItemDropdown(results);
            },
            error: function() {
                $itemResults.html('<div class="p-2 text-danger text-center" style="font-size:11.5px;">Failed to load products. Try again.</div>').show();
            }
        });
    }

    // Click on Item Input -> Open Dropdown only if text has been entered
    $itemInput.on('click', function() {
        const val = $(this).val().trim();
        if (val.length > 0 && !$itemResults.is(':visible')) {
            fetchAndShowItemDropdown(val);
        }
    });

    // Caret toggle button for Item Input
    $(document).on('click', '#btnToggleItemDropdown', function(e) {
        e.stopPropagation();
        if ($itemResults.is(':visible')) {
            $itemResults.hide();
        } else {
            $itemInput.focus();
            fetchAndShowItemDropdown($itemInput.val().trim());
        }
    });

    // Typing in Item Input
    $itemInput.on('input', function() {
        clearTimeout(itemSearchTimeout);
        const term = $(this).val().trim();
        itemSearchTimeout = setTimeout(() => {
            fetchAndShowItemDropdown(term);
        }, 220);
    });

    // Keyboard navigation on Item Input
    $itemInput.on('keydown', function(e) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            navigateDropdown($itemResults, 1);
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            navigateDropdown($itemResults, -1);
            return;
        }
        if (e.key === 'Escape') {
            $itemResults.hide();
            return;
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            const $selected = $itemResults.find('.qb-product-search-item.selected');
            if ($selected.length && $itemResults.is(':visible')) {
                $selected.trigger('click');
                return;
            }
            const term = $(this).val().trim();
            if (!term) return;

            // Immediate search & pick exact match
            $.ajax({
                url: '{{ route('products.ajax.search', [], false) }}',
                dataType: 'json',
                data: { term: term },
                success: function(data) {
                    const results = data.results || [];
                    if (results.length > 0) {
                        $itemInput.val('').focus();
                        $itemResults.hide().empty();
                        proceedToAddItem(results[0]);
                    }
                }
            });
        }
    });

    // Click strictly on Item Result Entry (Never overlaps with Customer Selection)
    $(document).on('click', '#qbItemSearchResults .qb-product-search-item', function(e) {
        e.stopPropagation();
        const item = $(this).data('json');
        if (item) {
            $itemInput.val('').focus();
            $itemResults.hide().empty();
            proceedToAddItem(item);
        }
    });

    // Close autocompletes on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.qb-search-box-wrap').length) {
            $itemResults.hide();
            $custResults.hide();
        }
    });

    // ==============================================================
    // 3. CUSTOMER & PARTNER MANAGEMENT (ODOO POS STYLE)
    // ==============================================================
    function getInitials(name) {
        if (!name) return 'WK';
        const parts = name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    }

    function getAvatarColor(name) {
        const colors = ['#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0891b2', '#4f46e5', '#be185d', '#0d9488'];
        let hash = 0;
        for (let i = 0; i < (name || '').length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    let custSearchTimeout = null;
    const $custInput = $('#qbCustomerSearchInput');
    const $custResults = $('#qbCustomerSearchResults');

    function renderCustDropdown(customers) {
        if (!customers || !customers.length) {
            $custResults.html(`
                <div class="p-3 text-muted text-center" style="font-size:12px;">
                    <i class="fas fa-user-slash fa-2x text-secondary mb-2 d-block"></i>
                    No customers found.<br>
                    <button type="button" class="btn btn-xs btn-primary mt-2" onclick="openOdooCustomerPicker()"><i class="fas fa-plus me-1"></i> Add / Search Full Directory</button>
                </div>
            `).show();
            return;
        }

        let html = `
            <div class="px-2 py-1 bg-light border-bottom text-muted d-flex justify-content-between align-items-center" style="font-size: 10.5px; font-weight: 600;">
                <span><i class="fas fa-user-friends me-1 text-primary"></i> CUSTOMERS (${customers.length})</span>
                <a href="javascript:void(0)" onclick="openOdooCustomerPicker(); $custResults.hide();" class="text-primary text-decoration-none fw-bold"><i class="fas fa-external-link-alt me-1"></i> Full Directory (F2)</a>
            </div>
            <div style="max-height: 270px; overflow-y: auto;">
        `;

        customers.forEach((c, index) => {
            const initials = getInitials(c.customer_name);
            const bgCol = getAvatarColor(c.customer_name);
            const prev = parseFloat(c.previous_balance || 0);

            let balBadge = '';
            if (prev > 0) {
                balBadge = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1">Dues: Rs. ${prev.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
            } else if (prev < 0) {
                balBadge = `<span class="badge bg-success-subtle text-success border border-success-subtle px-1">Adv: Rs. ${Math.abs(prev).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
            } else {
                balBadge = `<span class="badge bg-light text-muted border px-1">Bal: Rs. 0.00</span>`;
            }

            html += `
            <div class="qb-autocomplete-item qb-customer-search-item ${index === 0 ? 'selected' : ''}" data-json='${JSON.stringify(c).replace(/'/g, "&apos;")}'>
                <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
                    <div style="width:26px; height:26px; border-radius:50%; background:${bgCol}; color:#fff; font-size:10px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">${initials}</div>
                    <div style="min-width: 0;">
                        <div class="text-truncate">
                            <strong class="text-dark">${c.customer_name}</strong>
                            <small class="text-muted ms-1 font-monospace">(${c.customer_id || ''})</small>
                        </div>
                        ${c.mobile ? '<small class="text-secondary"><i class="fas fa-phone-alt me-1" style="font-size:9px;"></i>' + c.mobile + '</small>' : ''}
                    </div>
                </div>
                <div class="text-end flex-shrink-0 ms-2">
                    ${balBadge}
                </div>
            </div>`;
        });

        html += `
            </div>
            <div class="p-2 border-top bg-light d-flex justify-content-between align-items-center" style="font-size:11px;">
                <a href="javascript:void(0)" onclick="openQuickWalkinModal(); $custResults.hide();" class="text-warning-emphasis fw-bold text-decoration-none">
                    <i class="fas fa-walking me-1"></i> Quick Walk-in (F3)
                </a>
                <a href="javascript:void(0)" onclick="openOdooCustomerPicker(); $custResults.hide();" class="text-primary fw-bold text-decoration-none">
                    <i class="fas fa-th me-1"></i> Odoo Cards Grid (F2)
                </a>
            </div>
        `;

        $custResults.html(html).show();
    }

    function fetchAndShowCustDropdown(term = '') {
        $itemResults.hide();
        $custResults.html('<div class="p-3 text-center text-muted" style="font-size:11.5px;"><i class="fas fa-spinner fa-spin me-2 text-primary"></i> Loading customers...</div>').show();

        $.ajax({
            url: '{{ route('salecustomers.index', [], false) }}',
            dataType: 'json',
            data: { type: 'all', search: term },
            success: function(data) {
                renderCustDropdown(data || []);
            },
            error: function() {
                $custResults.html('<div class="p-2 text-danger text-center" style="font-size:11.5px;">Failed to load customers. Try again.</div>').show();
            }
        });
    }

    // Click on Customer Input -> Open Dropdown only if text has been entered
    $custInput.on('click', function() {
        const val = $(this).val().trim();
        if (val.length > 0 && !$custResults.is(':visible')) {
            fetchAndShowCustDropdown(val);
        }
    });

    // Caret toggle button for Customer Input
    $(document).on('click', '#btnToggleCustDropdown', function(e) {
        e.stopPropagation();
        if ($custResults.is(':visible')) {
            $custResults.hide();
        } else {
            $custInput.focus();
            fetchAndShowCustDropdown($custInput.val().trim());
        }
    });

    // Typing in Customer Input
    $custInput.on('input', function() {
        clearTimeout(custSearchTimeout);
        const term = $(this).val().trim();
        custSearchTimeout = setTimeout(() => {
            fetchAndShowCustDropdown(term);
        }, 220);
    });

    // Keyboard navigation on Customer Input
    $custInput.on('keydown', function(e) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            navigateDropdown($custResults, 1);
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            navigateDropdown($custResults, -1);
            return;
        }
        if (e.key === 'Escape') {
            $custResults.hide();
            return;
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            const $selected = $custResults.find('.qb-customer-search-item.selected');
            const $target = $selected.length ? $selected : $custResults.find('.qb-customer-search-item').first();
            if ($target.length && $custResults.is(':visible')) {
                $target.trigger('click');
            } else {
                const term = $(this).val().trim();
                openOdooCustomerPicker(term);
            }
        }
    });

    // Click strictly on Customer Dropdown Item (Only sets customer, never calls proceedToAddItem)
    $(document).on('click', '#qbCustomerSearchResults .qb-customer-search-item', function(e) {
        e.stopPropagation();
        const c = $(this).data('json');
        if (c) {
            setCustomer(c);
            $custInput.val('');
            $custResults.hide().empty();
            $itemInput.focus();
        }
    });

    function setCustomer(c) {
        if (!c) return;
        $('#customerSelect').val(c.id);
        $('#is_walkin').val('0');
        $('#walkinNameInput').val(c.customer_name);
        $('#address').val(c.address || '');
        $('#tel').val(c.mobile || '');
        const prev = parseFloat(c.previous_balance || 0);
        $('#previousBalance').val(prev.toFixed(2));
        $('#rangeBalance').val(parseFloat(c.balance_range || 0).toFixed(2));

        const initials = getInitials(c.customer_name);
        const bgCol = getAvatarColor(c.customer_name);

        // Update Odoo-style customer banner
        $('#qbCustomerAvatarBadge').text(initials).css('background', bgCol).show();
        $('#qbCustomerStatusLabel').html(`<span class="text-dark fw-bold">${c.customer_name}</span>`);

        let balBadge = '';
        if (prev > 0) {
            balBadge = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">Khata: Rs. ${prev.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} (Dues)</span>`;
        } else if (prev < 0) {
            balBadge = `<span class="badge bg-success-subtle text-success border border-success-subtle ms-1">Khata: Rs. ${Math.abs(prev).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} (Advance)</span>`;
        } else {
            balBadge = `<span class="badge bg-light text-muted border ms-1">Khata: Rs. 0.00</span>`;
        }

        $('#qbCustomerDetailsText').html(`[Code: ${c.customer_id || '-'}] | Mobile: ${c.mobile || '-'} ${balBadge}`);
        $('#qbBtnResetCustomerBanner').html('<i class="fas fa-times me-1"></i> Walk-in').removeClass('btn-outline-warning text-dark').addClass('btn-outline-danger').show();

        if (c.sales_officer_id) {
            $('#salesOfficerId').val(c.sales_officer_id);
        }
        if (typeof saveCurrentOrderState === 'function') {
            saveCurrentOrderState();
        }
    }

    window.applyWalkinDetails = function(data) {
        data = data || {};
        const name = (data.name || '').trim() || 'Walk-in Customer';
        const phone = (data.phone || '').trim();
        const address = (data.address || '').trim();
        const cnic = (data.cnic || '').trim();

        $('#customerSelect').val('');
        $('#is_walkin').val('1');
        $('#walkinNameInput').val(name);
        $('#walkinPhoneInput').val(phone);
        $('#walkinAddressInput').val(address);
        $('#walkinCnicInput').val(cnic);
        $('#address').val(address);
        $('#tel').val(phone);
        if (data.remarks) {
            $('#remarks').val(data.remarks);
        }
        $('#previousBalance').val('0');
        $('#rangeBalance').val('0');

        const initials = getInitials(name);
        $('#qbCustomerAvatarBadge').text(initials).css('background', '#d97706').show();
        $('#qbCustomerStatusLabel').html(`<span class="text-dark fw-bold">${name === 'Walk-in Customer' ? 'Walk-in Customer' : 'Walk-in: ' + name}</span>`);

        let details = '<span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace">Walk-in</span>';
        if (phone) details += ` <span class="text-secondary ms-1"><i class="fas fa-phone-alt me-1" style="font-size:9.5px;"></i>${phone}</span>`;
        if (address) details += ` <span class="text-secondary ms-1 text-truncate" style="max-width:180px;"><i class="fas fa-map-marker-alt me-1" style="font-size:9.5px;"></i>${address}</span>`;
        $('#qbCustomerDetailsText').html(details);

        $('#qbBtnResetCustomerBanner').html('<i class="fas fa-user-edit me-1"></i> Edit Walk-in').removeClass('btn-outline-danger').addClass('btn-outline-warning text-dark').show();

        // Walk-in customers cannot have unpaid Khata or be on Account
        if (typeof orderPaymentAllocation !== 'undefined') {
            if (orderPaymentAllocation.method === 'account' || orderPaymentAllocation.method === 'credit' || orderPaymentAllocation.khataAmount > 0.009) {
                const grandTotal = parseFloat($('#qbStatGrandTotal').text()) || 0;
                orderPaymentAllocation.method = 'cash';
                orderPaymentAllocation.cashAmount = grandTotal;
                orderPaymentAllocation.bankAmount = 0;
                orderPaymentAllocation.khataAmount = 0;
                currentActivePaymentMethod = 'cash';
                $('.qb-pay-tab').removeClass('active');
                $('.qb-pay-tab[data-method="cash"]').addClass('active');
                $('#qbAmountDueVal').text('0.00');
                if (typeof syncPaymentAccounts === 'function') {
                    syncPaymentAccounts();
                }
            }
        }

        if (typeof saveCurrentOrderState === 'function') {
            saveCurrentOrderState();
        }
    };

    window.resetCustomerToWalkin = function() {
        applyWalkinDetails({ name: 'Walk-in Customer', phone: '', address: '', cnic: '' });
    };

    window.openQuickWalkinModal = function(prefillData) {
        prefillData = prefillData || {};
        $('#modalOdooCustomerPicker').modal('hide');

        const isCurrentWalkin = ($('#is_walkin').val() === '1');
        const currentName = prefillData.name || (isCurrentWalkin ? $('#walkinNameInput').val() : '') || 'Walk-in Customer';
        const currentPhone = prefillData.phone || (isCurrentWalkin ? ($('#walkinPhoneInput').val() || $('#tel').val()) : '') || '';
        const currentAddress = prefillData.address || (isCurrentWalkin ? ($('#walkinAddressInput').val() || $('#address').val()) : '') || '';
        const currentCnic = prefillData.cnic || (isCurrentWalkin ? $('#walkinCnicInput').val() : '') || '';
        const currentRemarks = prefillData.remarks || (isCurrentWalkin ? $('#remarks').val() : '') || '';

        $('#walkinModalName').val(currentName);
        $('#walkinModalPhone').val(currentPhone);
        $('#walkinModalAddress').val(currentAddress);
        $('#walkinModalCnic').val(currentCnic);
        $('#walkinModalRemarks').val(currentRemarks);

        $('#modalQuickWalkinCustomer').modal('show');
        setTimeout(() => {
            $('#walkinModalName').focus().select();
        }, 300);
    };

    // Quick preset chips
    $(document).on('click', '.walkin-quick-preset', function() {
        const name = $(this).data('name');
        $('#walkinModalName').val(name).focus();
    });

    // Reset to Standard Default Walk-in
    $('#btnQuickResetStandardWalkin').on('click', function() {
        applyWalkinDetails({ name: 'Walk-in Customer', phone: '', address: '', cnic: '', remarks: '' });
        $('#modalQuickWalkinCustomer').modal('hide');
        $itemInput.focus();
        showAlert('success', 'Applied standard Walk-in Customer.');
    });

    // Submit Walk-in Details to Bill
    function submitWalkinModal() {
        const name = $('#walkinModalName').val().trim() || 'Walk-in Customer';
        const phone = $('#walkinModalPhone').val().trim();
        const address = $('#walkinModalAddress').val().trim();
        const cnic = $('#walkinModalCnic').val().trim();
        const rem = $('#walkinModalRemarks').val().trim();

        applyWalkinDetails({
            name: name,
            phone: phone,
            address: address,
            cnic: cnic,
            remarks: rem
        });

        $('#modalQuickWalkinCustomer').modal('hide');
        $itemInput.focus();
        showAlert('success', 'Walk-in customer details applied to bill.');
    }

    $('#btnSaveWalkinModal').on('click', function() {
        submitWalkinModal();
    });

    $('#formQuickWalkinCustomer').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitWalkinModal();
        }
    });

    $(document).on('click', '#qbBtnResetCustomerBanner', function(e) {
        e.preventDefault();
        if ($('#is_walkin').val() === '0') {
            resetCustomerToWalkin();
            showAlert('info', 'Switched to Walk-in Customer.');
        } else {
            openQuickWalkinModal();
        }
    });

    $(document).on('click', '#qbBtnClearCustomer', function(e) {
        e.preventDefault();
        openQuickWalkinModal();
    });

    // ==============================================================
    // ODOO POS PARTNER SCREEN / MODAL LOGIC
    // ==============================================================
    let odooCachedCustomers = [];
    let odooActiveFilter = 'all';

    window.openOdooCustomerPicker = function(prefillSearch = '') {
        $('#odooModalCustSearch').val(prefillSearch);
        $('#modalOdooCustomerPicker').modal('show');
        loadOdooCustomers(prefillSearch);
    };

    function loadOdooCustomers(searchTerm = '') {
        $('#odooCustLoading').show();
        $('#odooCustCardsGrid').hide();
        $('#odooCustEmpty').hide();

        $.ajax({
            url: '{{ route('salecustomers.index', [], false) }}',
            dataType: 'json',
            data: {
                type: odooActiveFilter === 'dues' ? 'all' : odooActiveFilter,
                search: searchTerm
            },
            success: function(data) {
                odooCachedCustomers = data || [];
                renderOdooCustomerCards();
            },
            error: function() {
                $('#odooCustLoading').hide();
                $('#odooCustEmpty').show();
                $('#odooCustEmptyMsg').text('Error loading customers from server.');
            }
        });
    }

    function renderOdooCustomerCards() {
        $('#odooCustLoading').hide();
        const currentCustId = $('#customerSelect').val();
        let list = odooCachedCustomers;

        if (odooActiveFilter === 'dues') {
            list = list.filter(c => parseFloat(c.previous_balance || 0) > 0);
        }

        $('#odooCustHeaderCount').text(list.length + ' Customers');

        if (!list.length) {
            $('#odooCustCardsGrid').hide();
            $('#odooCustEmpty').show();
            $('#odooCustEmptyMsg').text('No customers found matching search/filter.');
            return;
        }

        $('#odooCustEmpty').hide();
        let html = '';
        list.forEach(c => {
            const isSelected = (currentCustId && currentCustId == c.id);
            const initials = getInitials(c.customer_name);
            const bgCol = getAvatarColor(c.customer_name);
            const prev = parseFloat(c.previous_balance || 0);

            let balBadge = '';
            if (prev > 0) {
                balBadge = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fas fa-arrow-circle-up me-1"></i> Dues: Rs. ${prev.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
            } else if (prev < 0) {
                balBadge = `<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fas fa-arrow-circle-down me-1"></i> Adv: Rs. ${Math.abs(prev).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
            } else {
                balBadge = `<span class="badge bg-light text-muted border">Bal: Rs. 0.00</span>`;
            }

            html += `
            <div class="odoo-customer-card ${isSelected ? 'is-active-customer' : ''}" data-cust='${JSON.stringify(c).replace(/'/g, "&apos;")}'>
                <div class="d-flex align-items-start gap-2 mb-2">
                    <div class="odoo-card-avatar" style="background:${bgCol};">${initials}</div>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="odoo-card-name" title="${c.customer_name}">${c.customer_name}</span>
                            ${isSelected ? '<span class="badge bg-success text-white px-2 py-0" style="font-size:10px;"><i class="fas fa-check me-1"></i> Active</span>' : ''}
                        </div>
                        <div class="text-secondary small font-monospace mt-1">
                            <span class="badge bg-light text-dark border" style="font-size:10.5px;">${c.customer_id || 'ID: ' + c.id}</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size:10px;">${c.customer_type || 'Main Customer'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="odoo-card-meta">
                    <div><i class="fas fa-phone-alt me-1 text-muted" style="width:14px;"></i> ${c.mobile || '<span class="text-muted fst-italic">No Phone</span>'}</div>
                    <div class="text-truncate mt-1" title="${c.address || ''}"><i class="fas fa-map-marker-alt me-1 text-muted" style="width:14px;"></i> ${c.address || '<span class="text-muted fst-italic">No Address</span>'}</div>
                </div>

                <div class="odoo-card-footer">
                    <div>${balBadge}</div>
                    <button type="button" class="btn btn-sm ${isSelected ? 'btn-success' : 'btn-outline-primary'} py-0 px-2 fw-bold" style="font-size:11px;">
                        ${isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select'}
                    </button>
                </div>
            </div>`;
        });

        $('#odooCustCardsGrid').html(html).show();
    }

    // Modal Live Filter Search
    let odooModalSearchTimer = null;
    $('#odooModalCustSearch').on('input', function() {
        clearTimeout(odooModalSearchTimer);
        const q = $(this).val().trim();
        odooModalSearchTimer = setTimeout(() => {
            loadOdooCustomers(q);
        }, 220);
    });

    $('#btnOdooClearModalSearch').on('click', function() {
        $('#odooModalCustSearch').val('');
        loadOdooCustomers('');
    });

    // Filter Buttons (All, Main, Dues)
    $('.odoo-filter-btn').on('click', function() {
        $('.odoo-filter-btn').removeClass('active');
        $(this).addClass('active');
        odooActiveFilter = $(this).data('type');
        renderOdooCustomerCards();
    });

    // Click Customer Card in Odoo Modal
    $(document).on('click', '.odoo-customer-card', function() {
        const c = $(this).data('cust');
        if (c) {
            setCustomer(c);
            $('#modalOdooCustomerPicker').modal('hide');
            $itemInput.focus();
        }
    });

    // Set Walk-in from Odoo Modal (Opens Quick Walk-in Customer Modal)
    $('#btnOdooSetWalkin').on('click', function(e) {
        e.preventDefault();
        $('#modalOdooCustomerPicker').modal('hide');
        setTimeout(() => {
            openQuickWalkinModal();
        }, 220);
    });

    // Create Customer from Odoo Modal
    $('#btnOdooCreateNewCust, #btnOdooEmptyCreate').on('click', function() {
        const term = $('#odooModalCustSearch').val() || $custInput.val() || '';
        $('#ajaxAddCustomerForm')[0].reset();
        $('#modalCustomerName').val(term);
        $('#addCustomerModal').modal('show');
    });

    // ==============================================================
    // 3. GRID ROW MANAGEMENT: ADD, RENDER, RECALCULATE
    // ==============================================================
    function generateUid() {
        return 'row_' + Math.random().toString(36).substr(2, 9);
    }

    // Clean parsing of item name, attributes, and size so columns stay separated
    window.parseItemDetailsClean = function(rawItem) {
        let rawName = (rawItem.name || rawItem.item_name || rawItem.text || '').trim();
        let productBaseName = (rawItem.product_name || rawItem.product_item_name || '').trim();
        let attr = (rawItem.attribute || rawItem.color || '').trim();
        let size = (rawItem.size || '').trim();
        
        let vData = rawItem.variantData;
        if (!vData && rawItem.variant_data) {
            try {
                vData = JSON.parse(atob(rawItem.variant_data));
            } catch(e) {
                try { vData = typeof rawItem.variant_data === 'object' ? rawItem.variant_data : JSON.parse(rawItem.variant_data); } catch(e2) {}
            }
        }
        if (!vData && rawItem.colorJson) {
            try {
                vData = typeof rawItem.colorJson === 'object' ? rawItem.colorJson : JSON.parse(rawItem.colorJson);
            } catch(e) {}
        }
        if (vData) {
            if (!attr || attr === '-') attr = (vData.color || vData.attribute || '').trim();
            if (!size || size === '-') size = (vData.size || '').trim();
        }

        // If rawName contains parentheses: e.g. "3D Qabza (HG / Large) Large" or "3D Qabza (HG / Large)"
        const match = rawName.match(/^(.*?)\s*\(([^)]+)\)(.*)$/);
        if (match) {
            const basePart = match[1].trim();
            const parenContent = match[2].trim();
            
            if (!productBaseName && basePart) {
                productBaseName = basePart;
            }

            const parts = parenContent.split('/').map(p => p.trim()).filter(Boolean);
            if (parts.length >= 2) {
                if (!attr || attr === '-') attr = parts[0];
                if (!size || size === '-') size = parts[1];
            } else if (parts.length === 1) {
                const singleVal = parts[0];
                const isSizeLike = /^(small|medium|large|xlarge|xxl|\d+\s*(inch|mm|cm|ft|h|gm|kg|m))/i.test(singleVal);
                if (isSizeLike && (!size || size === '-')) {
                    size = singleVal;
                } else if (!attr || attr === '-') {
                    attr = singleVal;
                }
            }
        }

        // Check SKU if attr is still empty: e.g. "ITEM-0009-HG/Large"
        const sku = (rawItem.sku || rawItem.item_code || (vData && vData.sku) || '').trim();
        if ((!attr || attr === '-') && sku.includes('-')) {
            const skuSuffix = sku.substring(sku.lastIndexOf('-') + 1);
            const skuParts = skuSuffix.split('/').map(p => p.trim()).filter(Boolean);
            if (skuParts.length >= 1 && skuParts[0]) {
                attr = skuParts[0];
                if ((!size || size === '-') && skuParts.length >= 2) {
                    size = skuParts[1];
                }
            }
        }

        let cleanItemName = productBaseName || rawName;
        if (cleanItemName.includes('(')) {
            cleanItemName = cleanItemName.substring(0, cleanItemName.indexOf('(')).trim();
        }
        if (size && size !== '-' && cleanItemName.toLowerCase().endsWith(size.toLowerCase())) {
            cleanItemName = cleanItemName.slice(0, -size.length).trim();
        }

        if (!attr) attr = '-';
        if (!size) size = '-';

        // Full sales description
        let salesDesc = rawItem.sales_desc || rawItem.variant_name || '';
        if (!salesDesc || salesDesc === cleanItemName) {
            const descParts = [];
            if (attr !== '-') descParts.push(attr);
            if (size !== '-') descParts.push(size);
            salesDesc = cleanItemName + (descParts.length ? ` (${descParts.join(' / ')})` : '');
        }

        return {
            itemName: cleanItemName || 'Item',
            attribute: attr,
            size: size,
            salesDesc: salesDesc
        };
    };

    window.addItemToGrid = function(item, isReturn = false, origReceiptNo = '', origSaleItemId = null, origSaleId = null) {
        // Check if item contains variant info or base product
        let pId = item.product_id || item.id;
        if (typeof pId === 'string' && pId.includes('|variant|')) {
            const parts = pId.split('|variant|');
            pId = parts[0];
        }

        const rawPrice = parseFloat(item.price || item.retail_price || item.sale_price_per_piece || 0);
        const itemNumber = item.sku || item.item_code || item.serial_no || (pId || '457');
        const parsed = window.parseItemDetailsClean(item);
        const itemName = parsed.itemName;
        const vendorDesc = item.vendor_desc || item.category || item.brand || item.product_name || 'Khunty';
        const attribute = parsed.attribute;
        const size = parsed.size;
        const pCode = item.p_code || '';
        const salesDesc = parsed.salesDesc;
        const rootPrice = parseFloat(item.wholesale_price !== undefined ? item.wholesale_price : (item.rot_price !== undefined ? item.rot_price : (item.rootPrice || 0)));
        const adminApproved = !!item.admin_approved;
        const approvedPrice = item.admin_approved ? rawPrice : null;

        const uid = generateUid();
        const rowData = {
            uid: uid,
            isReturn: isReturn,
            receiptNo: origReceiptNo,
            saleItemId: origSaleItemId,
            originalSaleId: origSaleId || item.original_sale_id || item.sale_id || null,
            isManual: !!item.is_manual,
            productId: pId,
            itemNumber: itemNumber,
            itemName: itemName,
            vendorDesc: vendorDesc,
            attribute: attribute,
            size: size,
            qty: isReturn ? -Math.abs(parseFloat(item.returnQty || item.qty || 1)) : Math.abs(parseFloat(item.qty || 1)),
            price: rawPrice,
            rootPrice: rootPrice,
            adminApproved: adminApproved,
            approvedPrice: approvedPrice,
            approvalId: item.approval_id || null,
            discount: parseFloat(item.discount || 0),
            pCode: pCode,
            salesDesc: salesDesc,
            stock: (item.stock !== undefined ? parseFloat(item.stock) : (item.stock_pieces !== undefined ? parseFloat(item.stock_pieces) : 0)),
            colorJson: item.variantData ? (typeof item.variantData === 'string' ? item.variantData : JSON.stringify(item.variantData)) : '',
            uomUnits: item.uomUnits || item.uom_units || [],
            uomName: item.uomName || item.uom_sell_unit || item.unit || 'Pcs',
            uomRatio: parseFloat(item.uomRatio || 1) || 1
        };

        gridRows.push(rowData);
        if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
            orders[activeOrderIndex].gridRows = gridRows;
        }
        renderGrid();
        selectRow(uid);

        // Auto-hide catalog slider once product is placed on the sales screen
        if (typeof window.closeCatalog === 'function') {
            window.closeCatalog();
        }
    };

    function renderGrid() {
        const $tbody = $('#salesTableBody');
        $tbody.empty();

        if (gridRows.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="100" class="text-center py-5 text-muted" style="font-size: 13px;">
                        <i class="fas fa-barcode fs-2 mb-2 text-secondary d-block"></i>
                        Scan barcode or enter item name in the search box above to add to receipt.
                    </td>
                </tr>
            `);
            selectedRowUid = null;
            syncHiddenInputs();
            recomputeTotals();
            if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
                orders[activeOrderIndex].gridRows = gridRows;
            }
            if (typeof renderOrderTabs === 'function') {
                renderOrderTabs();
            }
            return;
        }

        // Ensure an active row is selected
        if (!selectedRowUid || !gridRows.find(r => r.uid === selectedRowUid)) {
            selectedRowUid = gridRows[gridRows.length - 1].uid;
        }

        gridRows.forEach((row, index) => {
            const extPrice = (row.qty * row.price) - ((row.qty * row.price) * (row.discount / 100));
            const isRet = row.isReturn || row.qty < 0;
            const isSelected = (row.uid === selectedRowUid);
            const rowClass = (isRet ? 'qb-return-row ' : '') + (isSelected ? 'qb-active-row' : '');

            const pCodeHtml = row.pCode ? `<div class="qb-row-subtext qb-pcode-text">P-Code: ${row.pCode}</div>` : '';
            const returnBadgeHtml = isRet ? `<div class="qb-row-subtext qb-return-badge">Return Item - Receipt# ${row.receiptNo || '1883'}</div>` : '';

            const trHtml = `
            <tr class="${rowClass}" data-uid="${row.uid}" style="cursor: pointer;">
                <td class="col-item-no">
                    <strong>${row.itemNumber}</strong>
                    ${returnBadgeHtml}
                </td>
                <td class="col-item-name">
                    <strong>${row.itemName}</strong>
                    <div class="qb-row-subtext" style="font-size:11px;">
                        <span class="text-muted">Unit:</span> <span class="badge bg-light text-primary border">${row.uomName || row.unit || 'Pcs'}</span>
                        ${(row.uomUnits && row.uomUnits.length > 1) ? `
                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1 ms-1 btn-open-choose-uom-addsale" data-uid="${row.uid}" style="font-size: 10px; height: 18px; line-height: 16px;">
                                <i class="fas fa-layer-group me-1"></i>Units
                            </button>
                        ` : ''}
                    </div>
                </td>
                <td class="col-vendor-desc">${row.vendorDesc}</td>
                <td class="col-attribute">${row.attribute}</td>
                <td class="col-size">
                    ${row.size}
                    ${pCodeHtml}
                </td>
                <td class="col-qty text-end fw-bold">${row.qty}</td>
                <td class="col-price text-end">
                    ${row.price.toFixed(2)}
                    ${row.adminApproved ? '<span class="badge bg-success-subtle text-success border border-success-subtle d-inline-block font-monospace ms-1" style="font-size: 8.5px; padding: 1px 3px;" title="Admin Authorized Below Root Price"><i class="fas fa-check-circle"></i> Auth</span>' : ''}
                </td>
                <td class="col-ext-price text-end fw-bold">${extPrice.toFixed(2)}</td>
                <td class="col-sales-desc">${row.salesDesc}</td>
            </tr>`;

            $tbody.append(trHtml);

            // If this is the active row, place the action toolbar directly under it (spanning 100% full width)
            if (isSelected) {
                const toolbarTr = `
                <tr class="qb-active-toolbar-tr" data-toolbar-uid="${row.uid}">
                    <td colspan="100" class="p-0 border-0">
                        <div class="qb-inrow-actionbar" style="background: #e2f4e4; border-bottom: 2px solid #a3d9a9; padding: 4px 10px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="qb-row-btn btn-row-edit" title="Edit row details"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="qb-row-btn btn-row-return-item" title="Toggle item as return"><i class="fas fa-undo"></i> Return Item</button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="qb-row-btn btn-row-qty-price-disc" title="Edit Qty / Price / Discount">Qty/Price/Discount</button>
                                <button type="button" class="qb-row-btn btn-row-qty-plus" style="background: linear-gradient(180deg, #10b981 0%, #059669 100%); border-color: #047857;" title="Increase Quantity (+1)"><i class="fas fa-plus"></i> Qty+</button>
                                <button type="button" class="qb-row-btn btn-row-qty-minus" style="background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%); border-color: #b45309;" title="Decrease Quantity (-1)"><i class="fas fa-minus"></i> Qty-</button>
                                <button type="button" class="qb-row-btn btn-row-remove" style="background: linear-gradient(180deg, #dc3545 0%, #b02a37 100%); border-color: #842029;" title="Remove Item"><i class="fas fa-trash-alt"></i> Remove</button>
                            </div>
                        </div>
                    </td>
                </tr>`;
                $tbody.append(toolbarTr);
            }
        });

        syncHiddenInputs();
        recomputeTotals();
        if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
            orders[activeOrderIndex].gridRows = gridRows;
        }
        if (typeof renderOrderTabs === 'function') {
            renderOrderTabs();
        }
        if (typeof applySalesGridColVisibility === 'function') {
            applySalesGridColVisibility();
        }
    }

    // Row Click Selection (Clicking any row activates it and moves toolbar under it)
    $(document).on('click', '#salesTableBody tr[data-uid]', function(e) {
        if ($(e.target).closest('.qb-inrow-actionbar').length) return;
        const uid = $(this).data('uid');
        if (uid) {
            selectRow(uid);
        }
    });

    function selectRow(uid) {
        selectedRowUid = uid;
        renderGrid();
    }

    // In-Row Actions: Qty+, Qty-, Remove, Edit, Return (Delegated Listeners)
    $(document).on('click', '.btn-row-qty-plus, #btnRowQtyPlus', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = gridRows.find(r => r.uid === selectedRowUid);
        if (row) {
            if (row.isReturn || row.qty < 0) {
                row.qty -= 1; // More negative for return
            } else {
                row.qty += 1;
            }
            renderGrid();
        }
    });

    $(document).on('click', '.btn-row-qty-minus, #btnRowQtyMinus', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = gridRows.find(r => r.uid === selectedRowUid);
        if (row) {
            if (row.isReturn || row.qty < 0) {
                row.qty += 1; // Less negative
                if (row.qty >= 0) row.qty = -1;
            } else {
                row.qty -= 1;
                if (row.qty <= 0) {
                    removeSelectedRow();
                    return;
                }
            }
            renderGrid();
        }
    });

    $(document).on('click', '.btn-row-remove, #btnRowRemove', function(e) {
        e.preventDefault();
        e.stopPropagation();
        removeSelectedRow();
    });

    function removeSelectedRow() {
        if (!selectedRowUid) return;
        gridRows = gridRows.filter(r => r.uid !== selectedRowUid);
        selectedRowUid = gridRows.length ? gridRows[gridRows.length - 1].uid : null;
        renderGrid();
    }

    $(document).on('click', '.btn-row-return-item, #btnRowReturnItem', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = gridRows.find(r => r.uid === selectedRowUid);
        if (row) {
            row.isReturn = !row.isReturn;
            row.qty = row.isReturn ? -Math.abs(row.qty) : Math.abs(row.qty);
            if (row.isReturn && !row.receiptNo) {
                row.receiptNo = '1883';
            }
            renderGrid();
        }
    });

    $(document).on('click', '.btn-row-edit, .btn-row-qty-price-disc, #btnRowEdit, #btnRowQtyPriceDisc', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = gridRows.find(r => r.uid === selectedRowUid);
        if (!row) return;

        $('#editRowUid').val(row.uid);
        $('#editRowItemName').val(row.itemName);
        $('#editRowQty').val(Math.abs(row.qty));
        $('#editRowPrice').val(row.price);
        $('#editRowDiscount').val(row.discount);
        $('#editRowAttribute').val(row.attribute);
        $('#editRowSize').val(row.size);
        $('#editRowDesc').val(row.salesDesc);

        $('#modalEditLineItem').modal('show');
    });

    $('#btnSaveLineEdit').on('click', function() {
        const uid = $('#editRowUid').val();
        const row = gridRows.find(r => r.uid === uid);
        if (row) {
            const rawQty = parseFloat($('#editRowQty').val()) || 1;
            const newPrice = parseFloat($('#editRowPrice').val()) || 0;
            const rootPrice = row.rootPrice || 0;

            if (rootPrice > 0 && newPrice < rootPrice && !window.isUserAdmin && (!row.adminApproved || row.approvedPrice !== newPrice)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Need Admin Permission!',
                        html: `Entered price <b class="text-danger font-monospace">Rs. ${newPrice.toFixed(2)}</b> is lower than the Root Price <b class="font-monospace">Rs. ${rootPrice.toFixed(2)}</b>.<br><br><strong>Need admin permission to sell at this price.</strong><br>Do you want to send an approval request to Admin?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Yes, Request Admin',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#1e40af',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modalEditLineItem').modal('hide');
                            sendPriceApprovalRequest({
                                productId: row.productId,
                                sku: row.itemNumber,
                                name: row.itemName,
                                salesDesc: row.salesDesc,
                                unit: 'Pcs',
                                price: row.price,
                                rootPrice: rootPrice,
                                raw: {
                                    product_id: row.productId,
                                    sku: row.itemNumber,
                                    name: row.itemName,
                                    sales_desc: row.salesDesc,
                                    price: newPrice,
                                    retail_price: newPrice,
                                    qty: rawQty,
                                    wholesale_price: rootPrice
                                }
                            }, rawQty, newPrice, rootPrice);
                        }
                    });
                }
                return;
            }

            row.qty = (row.isReturn || row.qty < 0) ? -Math.abs(rawQty) : Math.abs(rawQty);
            row.price = newPrice;
            row.discount = parseFloat($('#editRowDiscount').val()) || 0;
            row.attribute = $('#editRowAttribute').val() || '-';
            row.size = $('#editRowSize').val() || '-';
            row.salesDesc = $('#editRowDesc').val();
            $('#modalEditLineItem').modal('hide');
            renderGrid();
        }
    });

    // ==============================================================
    // 4. TOTALS RECOMPUTATION (SOLD VS RETURNED BREAKDOWN)
    // ==============================================================
    function recomputeTotals() {
        let itemsSold = 0;
        let qtySold = 0;
        let itemsReturned = 0;
        let qtyReturned = 0;
        let soldTotal = 0;
        let returnTotal = 0;

        gridRows.forEach(row => {
            const isRet = row.isReturn || row.qty < 0;
            const lineExt = (Math.abs(row.qty) * row.price) * (1 - (row.discount / 100));

            if (isRet) {
                itemsReturned += 1;
                qtyReturned += Math.abs(row.qty);
                returnTotal += lineExt;
            } else {
                itemsSold += 1;
                qtySold += Math.abs(row.qty);
                soldTotal += lineExt;
            }
        });

        const subTotal = Math.max(0, soldTotal - returnTotal);

        // Overall discount
        let overallDisc = 0;
        const discVal = parseFloat($('#overallDiscountVal').val()) || 0;
        const discType = $('#overallDiscountType').val();
        if (discType === 'percent') {
            overallDisc = (subTotal * discVal) / 100;
        } else {
            overallDisc = Math.min(subTotal, discVal);
        }

        const grandTotal = Math.max(0, subTotal - overallDisc);

        // Update statistics display
        $('#qbStatItemsSold').text(itemsSold);
        $('#qbStatQtySold').text(qtySold);
        $('#qbStatItemsReturned').text(itemsReturned);
        $('#qbStatQtyReturned').text(qtyReturned);
        $('#qbStatSubTotal').text(subTotal.toFixed(2));
        $('#qbStatDiscount').text(overallDisc.toFixed(2));
        $('#qbStatGrandTotal').text(grandTotal.toFixed(2));

        const isWalkin = ($('#is_walkin').val() === '1');

        // Payment allocation adjustment on total change
        if (isWalkin && (orderPaymentAllocation.method === 'account' || orderPaymentAllocation.method === 'credit' || orderPaymentAllocation.khataAmount > 0)) {
            orderPaymentAllocation.method = 'cash';
            orderPaymentAllocation.cashAmount = grandTotal;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = 0;
            currentActivePaymentMethod = 'cash';
            $('.qb-pay-tab').removeClass('active');
            $('.qb-pay-tab[data-method="cash"]').addClass('active');
        } else if (orderPaymentAllocation.method === 'cash') {
            orderPaymentAllocation.cashAmount = grandTotal;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = 0;
        } else if (orderPaymentAllocation.method === 'account') {
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = grandTotal;
        } else if (orderPaymentAllocation.method === 'debit' || orderPaymentAllocation.method === 'check') {
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = grandTotal;
            orderPaymentAllocation.khataAmount = 0;
        } else if (orderPaymentAllocation.method === 'gift') {
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = grandTotal;
            orderPaymentAllocation.khataAmount = 0;
        } else if (orderPaymentAllocation.method === 'credit') {
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = grandTotal;
        } else if (orderPaymentAllocation.method === 'split') {
            const upfront = orderPaymentAllocation.cashAmount + orderPaymentAllocation.bankAmount;
            orderPaymentAllocation.khataAmount = Math.max(0, grandTotal - upfront);
        }

        $('#inputCashReceived').val(orderPaymentAllocation.cashAmount.toFixed(2));
        $('#inputCardAmount').val(orderPaymentAllocation.bankAmount.toFixed(2));
        $('#inputPaymentMethod').val(orderPaymentAllocation.method);
        $('#qbAmountDueVal').text(orderPaymentAllocation.khataAmount.toFixed(2));
        $('#totalBalance').val(grandTotal.toFixed(2));

        if (typeof syncPaymentAccounts === 'function') {
            syncPaymentAccounts();
        }
    }

    // ==============================================================
    // 5. HIDDEN INPUTS SYNC FOR SALECONTROLLER::PROCESSSALE
    // ==============================================================
    function syncHiddenInputs() {
        // Clear all previous item hidden inputs cleanly via container
        const $itemContainer = $('#dynamicSaleItemsContainer');
        $itemContainer.empty();

        // Also purge any legacy loose inputs directly inside #saleForm
        $('#saleForm > input[name="product_id[]"], #saleForm > input[name="qty[]"], #saleForm > input[name="price_per_piece[]"], #saleForm > input[name="total[]"], #saleForm > input[name="gross_amount[]"], #saleForm > input[name="carton_qty[]"], #saleForm > input[name="total_pieces[]"], #saleForm > input[name="item_disc[]"], #saleForm > input[name="color[]"], #saleForm > input[name="size_display[]"], #saleForm > input[name="p_code[]"], #saleForm > input[name="warehouse_id[]"], #saleForm > input[name="visible_price[]"], #saleForm > input[name="discount_type[]"]').remove();
        $('#saleForm > input[name="return_product_id[]"], #saleForm > input[name="return_qty[]"], #saleForm > input[name="return_price[]"], #saleForm > input[name="return_color[]"], #saleForm > input[name="return_is_manual[]"], #saleForm > input[name="return_sale_item_id[]"], #saleForm > input[name="original_sale_id"]').remove();

        let origSaleIdSet = false;

        gridRows.forEach(row => {
            const isRet = row.isReturn || row.qty < 0;
            const lineExt = (Math.abs(row.qty) * row.price) * (1 - (row.discount / 100));

            if (isRet) {
                // Return Item Fields
                const origSaleIdVal = row.originalSaleId || row.receiptNo || '';
                $itemContainer.append(`
                    <input type="hidden" name="return_product_id[]" value="${row.productId}">
                    <input type="hidden" name="return_qty[]" value="${Math.abs(row.qty)}">
                    <input type="hidden" name="return_price[]" value="${row.price}">
                    <input type="hidden" name="return_color[]" value="${row.colorJson || ''}">
                    <input type="hidden" name="return_is_manual[]" value="${row.isManual ? 1 : 0}">
                    <input type="hidden" name="return_sale_item_id[]" value="${row.saleItemId || ''}">
                    <input type="hidden" name="original_sale_id[]" value="${origSaleIdVal}">
                    <input type="hidden" name="return_uom_name[]" value="${row.uomName || ''}">
                    <input type="hidden" name="return_uom_ratio[]" value="${row.uomRatio || 1}">
                `);
            } else {
                // Normal Sale Item Fields
                const totalPiecesVal = (row.uomRatio && row.uomRatio > 0) ? (Math.abs(row.qty) * row.uomRatio) : Math.abs(row.qty);
                $itemContainer.append(`
                    <input type="hidden" name="product_id[]" value="${row.productId}">
                    <input type="hidden" name="qty[]" value="${Math.abs(row.qty)}">
                    <input type="hidden" name="carton_qty[]" value="${Math.abs(row.qty)}">
                    <input type="hidden" name="total_pieces[]" value="${totalPiecesVal}">
                    <input type="hidden" name="price_per_piece[]" value="${row.price}">
                    <input type="hidden" name="visible_price[]" value="${row.price}">
                    <input type="hidden" name="item_disc[]" value="${row.discount}">
                    <input type="hidden" name="discount_type[]" value="percent">
                    <input type="hidden" name="gross_amount[]" value="${(Math.abs(row.qty) * row.price).toFixed(2)}">
                    <input type="hidden" name="total[]" value="${lineExt.toFixed(2)}">
                    <input type="hidden" name="color[]" value="${row.colorJson || ''}">
                    <input type="hidden" name="size_display[]" value="${row.size}">
                    <input type="hidden" name="stock[]" value="${row.stock !== undefined ? row.stock : ''}">
                    <input type="hidden" name="p_code[]" value="${row.pCode || ''}">
                    <input type="hidden" name="warehouse_id[]" value="{{ auth()->user()->warehouse_id ?? 1 }}">
                    <input type="hidden" name="uom_name[]" value="${row.uomName || ''}">
                    <input type="hidden" name="uom_ratio[]" value="${row.uomRatio || 1}">
                `);
            }
        });

        // Sync Payment Voucher Accounts
        syncPaymentAccounts();
    }

    let selectedPaymentAccountId = null;

    function syncPaymentAccounts() {
        const $payContainer = $('#paymentAccountsContainer').empty();
        const total = parseFloat($('#qbStatGrandTotal').text()) || 0;

        const defaultCashId = "{{ $defaultCashAccountId ?? 2 }}";
        const defaultBankId = "{{ $defaultBankAccountId ?? 3 }}";

        const cashAccId = orderPaymentAllocation.cashAccountId || defaultCashId;
        const bankAccId = orderPaymentAllocation.bankAccountId || defaultBankId;

        // Append receipt accounts if amounts > 0
        if (orderPaymentAllocation.cashAmount > 0.009) {
            $payContainer.append(`
                <input type="hidden" name="receipt_account_id[]" value="${cashAccId}">
                <input type="hidden" name="receipt_amount[]" value="${orderPaymentAllocation.cashAmount.toFixed(2)}">
            `);
        }
        if (orderPaymentAllocation.bankAmount > 0.009) {
            $payContainer.append(`
                <input type="hidden" name="receipt_account_id[]" value="${bankAccId}">
                <input type="hidden" name="receipt_amount[]" value="${orderPaymentAllocation.bankAmount.toFixed(2)}">
            `);
        }

        // Get Account Titles for Badge
        let bankTitle = $(`#qbSplitBankAccountId option[value="${bankAccId}"]`).text().trim();
        if (!bankTitle) bankTitle = 'Meezan Bank';
        bankTitle = bankTitle.split('(')[0].trim();

        let cashTitle = $(`#qbSplitCashAccountId option[value="${cashAccId}"]`).text().trim();
        if (!cashTitle) cashTitle = 'Shop Cash';
        cashTitle = cashTitle.split('(')[0].trim();

        const isWalkin = ($('#is_walkin').val() === '1');
        const custName = $('#walkinNameInput').val() || 'Customer';

        let badgeHtml = '';
        if (orderPaymentAllocation.method === 'split') {
            badgeHtml = `<i class="fas fa-random text-primary me-1"></i> Split: <strong>Cash Rs. ${orderPaymentAllocation.cashAmount.toFixed(0)} | ${bankTitle} Rs. ${orderPaymentAllocation.bankAmount.toFixed(0)} | Due Rs. ${orderPaymentAllocation.khataAmount.toFixed(0)}</strong>`;
        } else if (orderPaymentAllocation.method === 'cash') {
            badgeHtml = `<i class="fas fa-money-bill-wave text-success me-1"></i> Cash: <strong>${cashTitle} (Rs. ${orderPaymentAllocation.cashAmount.toFixed(2)})</strong>`;
        } else if (orderPaymentAllocation.method === 'debit' || orderPaymentAllocation.method === 'check') {
            badgeHtml = `<i class="fas fa-university text-primary me-1"></i> Bank: <strong>${bankTitle} (Rs. ${orderPaymentAllocation.bankAmount.toFixed(2)})</strong>`;
        } else if (orderPaymentAllocation.method === 'credit') {
            if (isWalkin) {
                badgeHtml = `<i class="fas fa-file-invoice text-warning me-1"></i> Credit (Khata): <strong>Select Customer (Rs. ${orderPaymentAllocation.khataAmount.toFixed(2)})</strong>`;
            } else {
                badgeHtml = `<i class="fas fa-file-invoice text-warning me-1"></i> Credit: <strong>${custName} Khata (Rs. ${orderPaymentAllocation.khataAmount.toFixed(2)})</strong>`;
            }
        } else if (orderPaymentAllocation.method === 'account') {
            badgeHtml = `<i class="fas fa-book text-warning me-1"></i> Account: <strong>${custName} Khata (Rs. ${orderPaymentAllocation.khataAmount.toFixed(2)})</strong>`;
        } else if (orderPaymentAllocation.method === 'credit') {
            if (isWalkin) {
                badgeHtml = `<i class="fas fa-file-invoice text-warning me-1"></i> Credit (Khata): <strong>Select Customer (Rs. ${orderPaymentAllocation.khataAmount.toFixed(2)})</strong>`;
            } else {
                badgeHtml = `<i class="fas fa-file-invoice text-warning me-1"></i> Credit: <strong>${custName} Khata (Rs. ${orderPaymentAllocation.khataAmount.toFixed(2)})</strong>`;
            }
        } else {
            badgeHtml = `<i class="fas fa-book text-secondary me-1"></i> Account: <strong>${cashTitle}</strong>`;
        }

        $('#qbActiveAccountBadge').html(badgeHtml);
        $('#selectedPaymentAccountId').val(bankAccId);
        $('#inputCashReceived').val(orderPaymentAllocation.cashAmount.toFixed(2));
        $('#inputCardAmount').val(orderPaymentAllocation.bankAmount.toFixed(2));
        $('#inputPaymentMethod').val(orderPaymentAllocation.method);
    }

    // Modal Payment Account Selector
    window.openPaymentAccountSelectorModal = function(initialMode) {
        const grandTotal = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const isWalkin = ($('#is_walkin').val() === '1');
        const custName = $('#walkinNameInput').val() || 'Walk-in Customer';
        const prevBal = parseFloat($('#previousBalance').val() || 0);

        $('#qbModalGrandTotalDisplay').text(grandTotal.toFixed(2));
        $('#qbModalCustName').text(custName);
        $('#qbModalCustPrevBal').text('Rs. ' + prevBal.toFixed(2));
        $('#qbModalCustKhataTarget').text(isWalkin ? '⚠️ No Khata (Walk-in Customer)' : (custName + ' Khata'));

        // Show/Hide Khata card and preset button for walk-in customers
        if (isWalkin) {
            $('#qbKhataCardCol').hide();
            $('#qbPresetKhataBtn').hide();
            $('#qbSplitKhataInput').val('0.00');
        } else {
            $('#qbKhataCardCol').show();
            $('#qbPresetKhataBtn').show();
        }

        // Pre-select account dropdowns
        if (orderPaymentAllocation.cashAccountId) {
            $('#qbSplitCashAccountId').val(orderPaymentAllocation.cashAccountId);
        }
        if (orderPaymentAllocation.bankAccountId) {
            $('#qbSplitBankAccountId').val(orderPaymentAllocation.bankAccountId);
        }

        // Set inputs based on initialMode or current allocation
        if (initialMode === 'account') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val(grandTotal.toFixed(2));
        } else if (initialMode === 'credit') {
            if (isWalkin) {
                $('#qbSplitCashInput').val(grandTotal.toFixed(2));
                $('#qbSplitBankInput').val('0.00');
                $('#qbSplitKhataInput').val('0.00');
            } else {
                if (orderPaymentAllocation.khataAmount > 0.009 || orderPaymentAllocation.cashAmount > 0.009 || orderPaymentAllocation.bankAmount > 0.009) {
                    $('#qbSplitCashInput').val(orderPaymentAllocation.cashAmount.toFixed(2));
                    $('#qbSplitBankInput').val(orderPaymentAllocation.bankAmount.toFixed(2));
                    $('#qbSplitKhataInput').val(orderPaymentAllocation.khataAmount.toFixed(2));
                } else {
                    $('#qbSplitCashInput').val('0.00');
                    $('#qbSplitBankInput').val('0.00');
                    $('#qbSplitKhataInput').val(grandTotal.toFixed(2));
                }
            }
        } else if (initialMode === 'bank') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val(grandTotal.toFixed(2));
            $('#qbSplitKhataInput').val('0.00');
        } else if (orderPaymentAllocation.method === 'split') {
            $('#qbSplitCashInput').val(orderPaymentAllocation.cashAmount.toFixed(2));
            $('#qbSplitBankInput').val(orderPaymentAllocation.bankAmount.toFixed(2));
            $('#qbSplitKhataInput').val(orderPaymentAllocation.khataAmount.toFixed(2));
        } else if (orderPaymentAllocation.method === 'credit' || orderPaymentAllocation.method === 'account') {
            $('#qbSplitCashInput').val(orderPaymentAllocation.cashAmount.toFixed(2));
            $('#qbSplitBankInput').val(orderPaymentAllocation.bankAmount.toFixed(2));
            $('#qbSplitKhataInput').val(orderPaymentAllocation.khataAmount > 0.009 ? orderPaymentAllocation.khataAmount.toFixed(2) : grandTotal.toFixed(2));
        } else if (orderPaymentAllocation.method === 'debit' || orderPaymentAllocation.method === 'check') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val(grandTotal.toFixed(2));
            $('#qbSplitKhataInput').val('0.00');
        } else {
            // Default cash
            $('#qbSplitCashInput').val(grandTotal.toFixed(2));
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val('0.00');
        }

        reconcileModalAllocation();
        $('#modalSelectPaymentAccount').modal('show');
        setTimeout(() => {
            if (initialMode === 'credit') {
                $('#qbSplitKhataInput').focus().select();
            } else if (initialMode === 'bank') {
                $('#qbSplitBankInput').focus().select();
            } else {
                $('#qbSplitCashInput').focus().select();
            }
        }, 300);
    };

    function reconcileModalAllocation() {
        const totalBill = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const cash = parseFloat($('#qbSplitCashInput').val()) || 0;
        const bank = parseFloat($('#qbSplitBankInput').val()) || 0;
        const khata = parseFloat($('#qbSplitKhataInput').val()) || 0;
        const totalPaid = cash + bank;
        const totalAllocated = totalPaid + khata;
        const diff = totalBill - totalAllocated;

        $('#qbBalTotalBill').text('Rs. ' + totalBill.toFixed(2));
        $('#qbBalTotalPaid').text('Rs. ' + totalPaid.toFixed(2));
        $('#qbBalDueKhata').text('Rs. ' + khata.toFixed(2));

        const isWalkin = ($('#is_walkin').val() === '1');
        const hasKhataDue = (khata > 0.009);

        if (isWalkin && hasKhataDue) {
            $('#qbWalkinKhataWarning').removeClass('d-none');
            $('#btnApplySplitPayment').prop('disabled', true);
            $('#qbBalStatusBadge').html(`
                <span class="badge bg-danger text-white fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                    <i class="fas fa-times-circle me-1"></i> Walk-in cannot have Khata Due
                </span>
            `);
            return false;
        } else {
            $('#qbWalkinKhataWarning').addClass('d-none');
        }

        if (Math.abs(diff) < 0.01) {
            $('#btnApplySplitPayment').prop('disabled', false);
            $('#qbBalStatusBadge').html(`
                <span class="badge bg-success text-white fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                    <i class="fas fa-check-circle me-1"></i> Exact Match (100% Balanced)
                </span>
            `);
            return true;
        } else if (diff > 0.009) {
            $('#btnApplySplitPayment').prop('disabled', false);
            let fillBtns = '';
            if (!isWalkin) {
                fillBtns += `<button type="button" class="btn btn-xs btn-outline-warning text-dark py-0 px-2 fw-bold" onclick="autoFillRemainder('khata')" title="Allocate remainder to Khata">+ Khata Rs. ${diff.toFixed(2)}</button>`;
            }
            fillBtns += `<button type="button" class="btn btn-xs btn-outline-success py-0 px-2 fw-bold ms-1" onclick="autoFillRemainder('cash')" title="Allocate remainder to Cash">+ Cash Rs. ${diff.toFixed(2)}</button>`;
            fillBtns += `<button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-bold ms-1" onclick="autoFillRemainder('bank')" title="Allocate remainder to Bank">+ Bank Rs. ${diff.toFixed(2)}</button>`;

            $('#qbBalStatusBadge').html(`
                <div class="d-flex align-items-center flex-wrap gap-1">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                        <i class="fas fa-exclamation-circle me-1"></i> Unallocated: Rs. ${diff.toFixed(2)}
                    </span>
                    ${fillBtns}
                </div>
            `);
            return true;
        } else {
            $('#btnApplySplitPayment').prop('disabled', false);
            const change = Math.abs(diff);
            $('#qbBalStatusBadge').html(`
                <span class="badge bg-info text-dark fw-bold px-2 py-1 shadow-sm" style="font-size: 11px;">
                    <i class="fas fa-info-circle me-1"></i> Overpaid: Cash Change Rs. ${change.toFixed(2)}
                </span>
            `);
            return true;
        }
    }

    // Modal Input Listeners - user types freely in any field without clobbering other fields!
    $('#qbSplitCashInput').on('input', function() {
        reconcileModalAllocation();
    });

    $('#qbSplitBankInput').on('input', function() {
        reconcileModalAllocation();
    });

    $('#qbSplitKhataInput').on('input', function() {
        reconcileModalAllocation();
    });

    window.autoFillRemainder = function(target) {
        const totalBill = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const cash = parseFloat($('#qbSplitCashInput').val()) || 0;
        const bank = parseFloat($('#qbSplitBankInput').val()) || 0;
        const khata = parseFloat($('#qbSplitKhataInput').val()) || 0;
        const isWalkin = ($('#is_walkin').val() === '1');

        if (target === 'khata') {
            if (isWalkin) {
                Swal.fire('Customer Required', 'Walk-in customers cannot have unpaid Khata. Please select a registered customer first.', 'warning');
                return;
            }
            const rem = Math.max(0, totalBill - cash - bank);
            $('#qbSplitKhataInput').val(rem.toFixed(2));
        } else if (target === 'cash') {
            const rem = Math.max(0, totalBill - bank - khata);
            $('#qbSplitCashInput').val(rem.toFixed(2));
        } else if (target === 'bank') {
            const rem = Math.max(0, totalBill - cash - khata);
            $('#qbSplitBankInput').val(rem.toFixed(2));
        }
        reconcileModalAllocation();
    };

    // Quick 1-Click Presets
    $(document).on('click', '.qb-preset-btn', function() {
        const p = $(this).data('preset');
        const totalBill = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const isWalkin = ($('#is_walkin').val() === '1');

        if (p === 'full_cash') {
            $('#qbSplitCashInput').val(totalBill.toFixed(2));
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val('0.00');
        } else if (p === 'full_bank') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val(totalBill.toFixed(2));
            $('#qbSplitKhataInput').val('0.00');
        } else if (p === 'full_khata') {
            if (isWalkin) {
                Swal.fire('Customer Required', 'Walk-in customers cannot have unpaid Khata. Please select a registered customer first.', 'warning');
                return;
            }
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val(totalBill.toFixed(2));
        } else if (p === 'split_cash_bank') {
            const half = (totalBill / 2).toFixed(2);
            const rem = (totalBill - parseFloat(half)).toFixed(2);
            $('#qbSplitCashInput').val(half);
            $('#qbSplitBankInput').val(rem);
            $('#qbSplitKhataInput').val('0.00');
        } else if (p === 'clear') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val('0.00');
        }
        reconcileModalAllocation();
    });

    window.allocateFullTo = function(dest) {
        const totalBill = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const isWalkin = ($('#is_walkin').val() === '1');
        if (dest === 'cash') {
            $('#qbSplitCashInput').val(totalBill.toFixed(2));
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val('0.00');
        } else if (dest === 'bank') {
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val(totalBill.toFixed(2));
            $('#qbSplitKhataInput').val('0.00');
        } else if (dest === 'khata') {
            if (isWalkin) {
                Swal.fire('Customer Required', 'Walk-in customers cannot have unpaid Khata. Please select a registered customer first.', 'warning');
                return;
            }
            $('#qbSplitCashInput').val('0.00');
            $('#qbSplitBankInput').val('0.00');
            $('#qbSplitKhataInput').val(totalBill.toFixed(2));
        }
        reconcileModalAllocation();
    };

    $('#btnApplySplitPayment').on('click', function() {
        const isWalkin = ($('#is_walkin').val() === '1');
        const cash = parseFloat($('#qbSplitCashInput').val()) || 0;
        const bank = parseFloat($('#qbSplitBankInput').val()) || 0;
        const khata = parseFloat($('#qbSplitKhataInput').val()) || 0;
        const cashAccId = $('#qbSplitCashAccountId').val();
        const bankAccId = $('#qbSplitBankAccountId').val();

        if (isWalkin && khata > 0.009) {
            Swal.fire('Customer Required', 'Walk-in customers cannot have unpaid Khata. Please select a registered customer or pay 100% in Cash/Bank.', 'warning');
            return;
        }

        orderPaymentAllocation.cashAmount = cash;
        orderPaymentAllocation.bankAmount = bank;
        orderPaymentAllocation.khataAmount = khata;
        orderPaymentAllocation.cashAccountId = cashAccId;
        orderPaymentAllocation.bankAccountId = bankAccId;

        if (khata > 0.009 && (cash > 0.009 || bank > 0.009)) {
            orderPaymentAllocation.method = 'split';
        } else if (khata > 0.009) {
            orderPaymentAllocation.method = 'account';
        } else if (bank > 0.009 && cash > 0.009) {
            orderPaymentAllocation.method = 'split';
        } else if (bank > 0.009) {
            orderPaymentAllocation.method = 'debit';
        } else {
            orderPaymentAllocation.method = 'cash';
        }

        currentActivePaymentMethod = orderPaymentAllocation.method;
        $('.qb-pay-tab').removeClass('active');
        $(`.qb-pay-tab[data-method="${currentActivePaymentMethod}"]`).addClass('active');

        syncPaymentAccounts();
        $('#qbAmountDueVal').text(khata.toFixed(2));

        if (typeof saveCurrentOrderState === 'function') {
            saveCurrentOrderState();
        }

        $('#modalSelectPaymentAccount').modal('hide');
    });

    // ==============================================================
    // 6. PAYMENT TABS INTERACTION
    // ==============================================================
    $('.qb-pay-tab').on('click', function() {
        const m = $(this).data('method');
        const grandTotal = parseFloat($('#qbStatGrandTotal').text()) || 0;
        const isWalkin = ($('#is_walkin').val() === '1');

        if (m === 'cash') {
            $('.qb-pay-tab').removeClass('active');
            $(this).addClass('active');
            currentActivePaymentMethod = 'cash';

            orderPaymentAllocation.method = 'cash';
            orderPaymentAllocation.cashAmount = grandTotal;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = 0;

            $('#qbAmountDueVal').text('0.00');
            $('#inputCashReceived').val(grandTotal.toFixed(2));
            $('#inputCardAmount').val('0.00');
            $('#inputPaymentMethod').val('cash');

            syncPaymentAccounts();
            if (typeof saveCurrentOrderState === 'function') {
                saveCurrentOrderState();
            }
            return;
        }

        if (m === 'account') {
            if (isWalkin) {
                Swal.fire({
                    title: 'Customer Required',
                    text: 'Account (Khata) sale sirf registered / main customer ke liye hai. Walk-in customer ko pori payment karni hogi. Barah-e-karam pehle customer select karein.',
                    icon: 'warning',
                    confirmButtonText: '<i class="fas fa-user-plus me-1"></i> Select Customer',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    $('#qbCustomerSearchInput').focus();
                });
                return;
            }

            $('.qb-pay-tab').removeClass('active');
            $(this).addClass('active');
            currentActivePaymentMethod = 'account';

            orderPaymentAllocation.method = 'account';
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = 0;
            orderPaymentAllocation.khataAmount = grandTotal;

            $('#qbAmountDueVal').text(grandTotal.toFixed(2));
            $('#inputCashReceived').val('0.00');
            $('#inputCardAmount').val('0.00');
            $('#inputPaymentMethod').val('account');

            syncPaymentAccounts();
            if (typeof saveCurrentOrderState === 'function') {
                saveCurrentOrderState();
            }
            return;
        }

        if (m === 'credit') {
            if (isWalkin) {
                Swal.fire({
                    title: 'Customer Required for Credit',
                    text: 'Credit / Khata sale sirf registered / main customer ke liye hai. Walk-in customer ko pori payment karni hogi.',
                    icon: 'warning',
                    confirmButtonText: '<i class="fas fa-user-plus me-1"></i> Select Customer',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    $('#qbCustomerSearchInput').focus();
                });
                return;
            }

            openPaymentAccountSelectorModal('credit');
            return;
        }

        if (m === 'split') {
            openPaymentAccountSelectorModal('split');
            return;
        }

        $('.qb-pay-tab').removeClass('active');
        $(this).addClass('active');
        currentActivePaymentMethod = m;

        if (m === 'debit' || m === 'check') {
            orderPaymentAllocation.method = m;
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = grandTotal;
            orderPaymentAllocation.khataAmount = 0;
        } else if (m === 'gift') {
            orderPaymentAllocation.method = 'gift';
            orderPaymentAllocation.cashAmount = 0;
            orderPaymentAllocation.bankAmount = grandTotal;
            orderPaymentAllocation.khataAmount = 0;
        }

        $('#qbAmountDueVal').text('0.00');
        $('#inputCashReceived').val(orderPaymentAllocation.cashAmount.toFixed(2));
        $('#inputCardAmount').val(orderPaymentAllocation.bankAmount.toFixed(2));
        $('#inputPaymentMethod').val(m);

        syncPaymentAccounts();
        if (typeof saveCurrentOrderState === 'function') {
            saveCurrentOrderState();
        }
    });

    // ==============================================================
    // 7. PRODUCT CATALOG SLIDER & CATEGORIES (IMAGE 1)
    // ==============================================================
    window.catalogCategoriesLoaded = false;
    let catalogProductsCache = [];
    let activeCatalogCategory = 'all';

    window.loadCatalogCategories = function() {
        $.ajax({
            url: '{{ route('get-categories', [], false) }}',
            type: 'GET',
            dataType: 'json',
            success: function(cats) {
                window.catalogCategoriesLoaded = true;
                let html = '<div class="qb-cat-tile all-tile active" data-cat-id="all"><span>All Items</span></div>';
                if (cats && cats.length) {
                    cats.forEach(c => {
                        html += `<div class="qb-cat-tile" data-cat-id="${c.id}" title="${c.name}"><span>${c.name}</span></div>`;
                    });
                }
                $('#qbCatTilesGrid').html(html);
            },
            error: function(xhr, status, err) {
                console.error('Failed to load categories:', status, err);
                $('#qbCatTilesGrid').html('<div class="qb-cat-tile all-tile active" data-cat-id="all"><span>All Items</span></div>');
            }
        });
    };

    window.loadCatalogProducts = function(catId) {
        activeCatalogCategory = catId || 'all';
        window.activeCatalogCategory = activeCatalogCategory;
        $('#qbCatalogProdGrid').html(`
            <div class="text-center text-muted py-5" style="grid-column: 1 / -1; font-size: 12px;">
                <i class="fas fa-spinner fa-spin me-2"></i> Loading items...
            </div>
        `);

        $.ajax({
            url: '{{ route('sale.products_by_category', [], false) }}',
            type: 'GET',
            data: { category_id: activeCatalogCategory },
            dataType: 'json',
            success: function(res) {
                window.catalogProductsLoaded = true;
                catalogProductsCache = res.products || [];
                renderCatalogProducts(catalogProductsCache);
            },
            error: function(xhr, status, err) {
                console.error('Failed to load catalog products:', status, err, xhr);
                window.catalogProductsLoaded = false;
                $('#qbCatalogProdGrid').html(`
                    <div class="text-center text-danger py-4" style="grid-column: 1 / -1; font-size: 12px;">
                        <i class="fas fa-exclamation-triangle fs-4 mb-2 d-block"></i>
                        Failed to load products.
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2 d-block mx-auto" onclick="window.loadCatalogProducts('${activeCatalogCategory}')">
                            <i class="fas fa-redo me-1"></i> Retry
                        </button>
                    </div>
                `);
            }
        });
    };

    // Preload catalog categories & products in background
    setTimeout(function() {
        if (!window.catalogCategoriesLoaded) window.loadCatalogCategories();
        if (!window.catalogProductsLoaded) window.loadCatalogProducts('all');
    }, 200);

    function renderCatalogProducts(prods) {
        const $grid = $('#qbCatalogProdGrid').empty();
        $('#qbCatalogCountBadge').text(prods ? (prods.length + ' items') : '0 items');
        if (!prods || !prods.length) {
            $grid.html(`
                <div class="text-center text-muted py-5" style="grid-column: 1 / -1; font-size: 12px;">
                    <i class="fas fa-box-open fs-3 mb-2 d-block text-secondary"></i>
                    No products found in this category.
                </div>
            `);
            return;
        }

        prods.forEach(p => {
            const hasVar = p.has_variants ? 1 : 0;
            const stockQty = parseFloat(p.stock || p.stock_pieces || 0);
            const isStock = stockQty > 0;
            const badgeClass = isStock ? 'in-stock' : 'out-stock';
            const badgeText = isStock ? `${stockQty} in stock` : 'Out of stock';
            const price = parseFloat(p.price || 0).toFixed(2);
            const varBadge = hasVar ? `<span class="badge" style="background:#f3e8ff; color:#714b67; border:1px solid #d8b4fe; font-size:10px; font-weight:700;">▦ Matrix</span>` : '';
            const itemName = p.name || p.item_name || ('Item #' + p.id);
            const safeName = escapeHtml(itemName);
            const skuText = p.sku || p.item_code || ('#' + p.id);
            const safeSku = escapeHtml(skuText);
            const pCode = (p.p_code || '').trim();
            const pCodeBadge = pCode ? `<span class="qb-card-pcode" title="P-Code: ${escapeHtml(pCode)}">P: <strong>${escapeHtml(pCode)}</strong></span>` : '';

            const card = `
                <div class="qb-prod-card" data-product-id="${p.id}" data-has-variants="${hasVar}" data-json='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
                    <div class="d-flex align-items-center justify-content-between mb-1" style="gap: 4px;">
                        <span class="qb-card-top-badge ${badgeClass}">
                            <i class="fas ${isStock ? 'fa-check-circle' : 'fa-exclamation-circle'} me-1"></i> ${badgeText}
                        </span>
                        ${varBadge}
                    </div>
                    <div class="qb-card-title" title="${safeName}">${safeName}</div>
                    <div class="d-flex align-items-center justify-content-between mt-auto mb-1" style="gap: 4px;">
                        <span class="qb-card-sku">#${safeSku}</span>
                        ${pCodeBadge}
                    </div>
                    <div class="qb-card-price">Rs. ${price}</div>
                </div>
            `;
            $grid.append(card);
        });
    }

    // Price Visibility Toggle Switch Handler (Slide Switch)
    const $priceSwitch = $('#qbToggleCatalogPrice');
    const $catalogPanel = $('#qbCatalogPanel');

    function applyCatalogPriceVisibility(show) {
        if (show) {
            $catalogPanel.removeClass('hide-prices');
            $('#qbTogglePriceIcon').removeClass('fa-eye-slash text-secondary').addClass('fa-eye text-primary');
            $('#qbTogglePriceText').text('Show Prices');
            $priceSwitch.prop('checked', true);
            try { localStorage.setItem('qb_catalog_show_prices', 'true'); } catch (e) {}
        } else {
            $catalogPanel.addClass('hide-prices');
            $('#qbTogglePriceIcon').removeClass('fa-eye text-primary').addClass('fa-eye-slash text-secondary');
            $('#qbTogglePriceText').text('Prices Hidden');
            $priceSwitch.prop('checked', false);
            try { localStorage.setItem('qb_catalog_show_prices', 'false'); } catch (e) {}
        }
    }

    // Initialize state from localStorage (defaults to true)
    let savedPriceToggle = 'true';
    try { savedPriceToggle = localStorage.getItem('qb_catalog_show_prices') || 'true'; } catch (e) {}
    if (savedPriceToggle === 'false') {
        applyCatalogPriceVisibility(false);
    } else {
        applyCatalogPriceVisibility(true);
    }

    $priceSwitch.on('change', function() {
        applyCatalogPriceVisibility($(this).is(':checked'));
    });

    $('#qbTogglePriceLabel').on('click', function(e) {
        e.preventDefault();
        $priceSwitch.prop('checked', !$priceSwitch.is(':checked')).trigger('change');
    });

    // Category Tile Click Handler
    $(document).on('click', '.qb-cat-tile', function() {
        $('.qb-cat-tile').removeClass('active');
        $(this).addClass('active');
        const catId = $(this).data('cat-id');
        $('#qbCatalogSearchInput').val('');
        loadCatalogProducts(catId);
    });

    // Catalog Search Filter Handler
    $('#qbCatalogSearchInput').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        if (!q) {
            renderCatalogProducts(catalogProductsCache);
            return;
        }
        const filtered = catalogProductsCache.filter(p => {
            const name = (p.name || p.item_name || '').toLowerCase();
            const sku = (p.sku || p.item_code || '').toLowerCase();
            const pcode = (p.p_code || '').toLowerCase();
            return name.includes(q) || sku.includes(q) || pcode.includes(q);
        });
        renderCatalogProducts(filtered);
    });

    // Catalog Product Card Click Handler
    $(document).on('click', '.qb-prod-card', function() {
        const hasVariants = $(this).data('has-variants') == '1';
        const pId = $(this).data('product-id');
        const pJson = $(this).data('json');

        if (hasVariants) {
            openVariantMatrixPicker(pId, pJson);
        } else {
            // Load complete product details via ajax search and pass to pipeline
            $.ajax({
                url: '{{ route('products.ajax.search', [], false) }}',
                data: { product_id: pId },
                dataType: 'json',
                success: function(res) {
                    if (res && res.results && res.results.length) {
                        proceedToAddItem(res.results[0]);
                    } else {
                        proceedToAddItem(pJson);
                    }
                },
                error: function() {
                    proceedToAddItem(pJson);
                }
            });
        }
    });

    // ==============================================================
    // 7b. VARIANT MATRIX PICKER MODAL (ODOO ENTERPRISE MATRIX VIEW)
    // ==============================================================
    let currentMatrixVariants = [];
    let currentMatrixProduct = null;
    let currentSelectedMatrixAttrs = {};
    let currentMatchedMatrixVariant = null;
    let currentPickerMode = 'matrix';
    let pickerPricesVisible = false;

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Mode Switcher: Matrix vs Table
    $(document).on('click', '#btnPickerModeMatrix', function() {
        currentPickerMode = 'matrix';
        $(this).addClass('active').css({ 'background': '#714b67', 'color': '#ffffff' });
        $('#btnPickerModeTable').removeClass('active').css({ 'background': 'transparent', 'color': '#64748b' });
        $('#pickerMatrixContainer').removeClass('d-none');
        $('#pickerTableContainer').addClass('d-none');
        $('#btnPickerAddMatrixVariant').removeClass('d-none');
    });

    $(document).on('click', '#btnPickerModeTable', function() {
        currentPickerMode = 'table';
        $(this).addClass('active').css({ 'background': '#714b67', 'color': '#ffffff' });
        $('#btnPickerModeMatrix').removeClass('active').css({ 'background': 'transparent', 'color': '#64748b' });
        $('#pickerTableContainer').removeClass('d-none');
        $('#pickerMatrixContainer').addClass('d-none');
        $('#btnPickerAddMatrixVariant').addClass('d-none');
    });

    // Eye button to reveal/hide numeric prices
    $(document).on('click', '#btnTogglePickerMatrixPriceDigits', function() {
        pickerPricesVisible = !pickerPricesVisible;
        if (pickerPricesVisible) {
            $('.picker-matrix-numeric-price, .picker-matrix-numeric-divider').removeClass('d-none');
            $('#iconPickerMatrixPriceEye').removeClass('fa-eye-slash').addClass('fa-eye');
            $('#textPickerMatrixPriceToggle').text('Hide Prices');
        } else {
            $('.picker-matrix-numeric-price, .picker-matrix-numeric-divider').addClass('d-none');
            $('#iconPickerMatrixPriceEye').removeClass('fa-eye').addClass('fa-eye-slash');
            $('#textPickerMatrixPriceToggle').text('Show Prices');
        }
    });

    function openVariantMatrixPicker(productId, productBase) {
        currentMatrixProduct = null;
        currentMatrixVariants = [];
        currentSelectedMatrixAttrs = {};
        currentMatchedMatrixVariant = null;

        const prodTitle = (productBase && (productBase.name || productBase.item_name)) ? (productBase.name || productBase.item_name) : 'Select Product Variant';
        const prodSku = (productBase && (productBase.sku || productBase.item_code)) ? `#${productBase.sku || productBase.item_code}` : '';

        $('#pickerModalProductTitle').text(prodTitle);
        $('#pickerModalProductSubtitle').text(prodSku);

        $('#pickerLoadingSpinner').removeClass('d-none');
        $('#pickerMatrixContainer').addClass('d-none');
        $('#pickerTableContainer').addClass('d-none');
        $('#btnPickerAddMatrixVariant').prop('disabled', true).html('<i class="fas fa-check me-1"></i> Add Variant');
        $('#pickerSelectedVariantInfo').html('<span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>');
        $('#variantPickerSearchInput').val('');

        $('#modalVariantPicker').modal('show');

        // Fetch flattened variants and product details
        $.when(
            $.ajax({ url: '{{ route('products.ajax.search', [], false) }}', data: { product_id: productId }, dataType: 'json' }),
            $.ajax({ url: '/productview/' + productId, dataType: 'json' }).catch(function() { return null; })
        ).done(function(searchRes, prodRes) {
            $('#pickerLoadingSpinner').addClass('d-none');

            const results = (searchRes && searchRes[0] && searchRes[0].results) ? searchRes[0].results : (searchRes && searchRes.results ? searchRes.results : []);
            const fullProduct = (prodRes && prodRes[0] && prodRes[0].id) ? prodRes[0] : (productBase || {});

            currentMatrixVariants = results;
            currentMatrixProduct = fullProduct;

            // Normalize variants attributes
            currentMatrixVariants.forEach(v => {
                let vData = null;
                if (v.variant_data) {
                    try { vData = JSON.parse(atob(v.variant_data)); } catch(e) {
                        try { vData = typeof v.variant_data === 'object' ? v.variant_data : JSON.parse(v.variant_data); } catch(e2) {}
                    }
                }
                v._vData = vData;
                v.size = (vData && vData.size) ? vData.size : (v.size || '-');
                v.color = (vData && vData.color) ? vData.color : (v.color || '-');
                v.serial_no = v.serial_no || (vData && vData.serial_no) || '';
                v.location = v.location || (vData && vData.location) || '';
                v.sky_p_code = v.sky_p_code || v.p_code || (vData && (vData.sky_p_code || vData.p_code)) || '';
                v.rot_p_code = v.rot_p_code || (vData && vData.rot_p_code) || '';
                v.retail_price = v.retail_price !== undefined ? v.retail_price : (v.price || 0);
                v.wholesale_price = v.wholesale_price !== undefined ? v.wholesale_price : (vData && vData.wholesale_price ? vData.wholesale_price : 0);
                v.stock_pieces = parseFloat(v.stock_pieces !== undefined ? v.stock_pieces : (v.stock || 0));
            });

            // Render Table View
            renderVariantRows(currentMatrixVariants, fullProduct);

            // Render Odoo Matrix View
            renderMatrixPickerView(fullProduct, currentMatrixVariants);

            if (currentPickerMode === 'matrix') {
                $('#pickerMatrixContainer').removeClass('d-none');
                $('#pickerTableContainer').addClass('d-none');
                $('#btnPickerAddMatrixVariant').removeClass('d-none');
            } else {
                $('#pickerTableContainer').removeClass('d-none');
                $('#pickerMatrixContainer').addClass('d-none');
                $('#btnPickerAddMatrixVariant').addClass('d-none');
            }
        }).fail(function() {
            $('#pickerLoadingSpinner').addClass('d-none');
            $('#pickerMatrixContainer').removeClass('d-none');
            $('#pickerMatrixAttributeGroups').html(`
                <div class="alert alert-danger py-3 text-center">
                    Failed to load product variants. Please try again.
                </div>
            `);
        });
    }

    function renderMatrixPickerView(product, variants) {
        let basePrice = product.size_mode === 'by_size' ? product.price_per_m2 : (product.sale_price_per_piece || product.sale_price_per_box || 0);
        let baseRotPrice = product.wholesale_price || 0;
        $('#picker_matrix_item_price').text('Rs. ' + parseFloat(basePrice).toFixed(2));
        let defaultSkyPCode = (window.encodeToPCode ? window.encodeToPCode(basePrice) : '') || product.p_code;
        $('#picker_matrix_item_pcode').text(defaultSkyPCode || '---');
        $('#picker_matrix_item_rot_price').text('Rs. ' + parseFloat(baseRotPrice).toFixed(2));
        let defaultRotPCode = (window.encodeToPCode ? window.encodeToPCode(baseRotPrice) : '') || product.rot_p_code;
        $('#picker_matrix_item_rot_pcode').text(defaultRotPCode || '---');

        let totalStock = (product.warehouseStocks && Array.isArray(product.warehouseStocks))
            ? product.warehouseStocks.reduce((sum, ws) => sum + (parseFloat(ws.total_pieces) || 0), 0)
            : (parseFloat(product.stock || product.stock_pieces || 0));
        let baseUnit = product.unit ? product.unit.name : 'Units';
        $('#picker_matrix_stock_display').text(totalStock + ' ' + baseUnit);

        $('#picker_matrix_serial_badge_container').addClass('d-none');
        $('#picker_matrix_location_badge_wrap').addClass('d-none');
        $('#picker_matrix_location_text').text('---');
        $('#btnPickerAddMatrixVariant').prop('disabled', true);
        $('#pickerSelectedVariantInfo').html('<span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>');

        currentSelectedMatrixAttrs = {};
        currentMatchedMatrixVariant = null;

        const $attrGroups = $('#pickerMatrixAttributeGroups');
        $attrGroups.empty();

        if (!variants || variants.length === 0) {
            $attrGroups.html(`
                <div class="alert alert-light border text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">This product has no variants configured.</p>
                </div>
            `);
            return;
        }

        const attrMap = extractProductAttributesForPicker(product, variants);
        const attrKeys = Object.keys(attrMap);

        if (attrKeys.length === 0) {
            $attrGroups.html(`
                <div class="alert alert-light border text-center py-4">
                    <p class="text-muted mb-0">No distinct attributes found.</p>
                </div>
            `);
            return;
        }

        attrKeys.forEach((attrKey, aIdx) => {
            const values = attrMap[attrKey];
            let radioItemsHtml = '';

            values.forEach((val, vIdx) => {
                const radioId = `picker_radio_${aIdx}_${vIdx}`;
                radioItemsHtml += `
                    <label class="matrix-radio-pill" for="${radioId}">
                        <input type="radio" 
                               name="picker_attr_${aIdx}" 
                               id="${radioId}" 
                               value="${escapeHtml(val)}" 
                               data-attr="${escapeHtml(attrKey)}" 
                               class="matrix-attr-input" 
                               style="position: static !important; width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer; margin: 0; vertical-align: middle;">
                        <span class="matrix-attr-val" style="font-size: 13px; color: #1e293b; font-weight: 600; margin-left: 7px; user-select: none;">
                            ${escapeHtml(val)}
                        </span>
                    </label>
                `;
            });

            const groupHtml = `
                <div class="matrix-attr-row mb-2" data-attr="${escapeHtml(attrKey)}">
                    <div class="fw-bold text-dark mb-1" style="font-size: 13px; letter-spacing: -0.2px;">
                        ${escapeHtml(attrKey)}
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 matrix-values-wrap">
                        ${radioItemsHtml}
                    </div>
                </div>
            `;
            $attrGroups.append(groupHtml);
        });

        // Pill change event handler
        $attrGroups.find('.matrix-attr-input').on('change', function() {
            const changedAttr = $(this).data('attr');
            const changedVal = $(this).val();
            currentSelectedMatrixAttrs[changedAttr] = changedVal;

            $(this).closest('.matrix-attr-row').find('.matrix-radio-pill').removeClass('selected-pill');
            $(this).closest('.matrix-radio-pill').addClass('selected-pill');

            updateMatrixPickerCombinations(product, variants, attrKeys);
        });
    }

    function extractProductAttributesForPicker(product, variants) {
        let attrMap = {};
        let nameParts = [];
        let hasNamePattern = false;

        variants.forEach(v => {
            let vName = v.name || v.variant_name || '';
            let match = vName.match(/\((.*?)\)/);
            if (match && match[1]) {
                let parts = match[1].split(/\s+\/\s+/).map(s => s.trim()).filter(Boolean);
                if (parts.length >= 2) {
                    hasNamePattern = true;
                    nameParts.push(parts);
                }
            }
        });

        if (hasNamePattern && nameParts.length === variants.length) {
            let numParts = nameParts[0].length;
            let partDefs = [];

            for (let i = 0; i < numParts; i++) {
                let distinctVals = [...new Set(nameParts.map(p => p[i]))];
                let isFractionOrMeasurement = distinctVals.some(v => v.includes('/') || /\d+["']|\d+mm|\d+cm/i.test(v));
                let isAllNumbers = distinctVals.every(v => /^\d+(\.\d+)?$/.test(v));
                let matchesSize = variants.every((v, idx) => (v.size && v.size !== '-' && v.size === nameParts[idx][i]));
                let matchesColor = variants.every((v, idx) => (v.color && v.color !== '-' && v.color === nameParts[idx][i]));

                partDefs.push({
                    partIndex: i,
                    distinctVals: distinctVals,
                    isFractionOrMeasurement: isFractionOrMeasurement,
                    isAllNumbers: isAllNumbers,
                    matchesSize: matchesSize,
                    matchesColor: matchesColor,
                    count: distinctVals.length
                });
            }

            let pNameLower = (product.item_name || '').toLowerCase();
            let isScrewOrFastener = pNameLower.includes('screw') || pNameLower.includes('bolt') || pNameLower.includes('nut') || pNameLower.includes('nail');

            partDefs.forEach((pd, i) => {
                let label = '';
                if (pd.matchesSize || pd.isFractionOrMeasurement) {
                    label = isScrewOrFastener ? 'Length' : 'Size';
                } else if (pd.matchesColor || (isScrewOrFastener && pd.isAllNumbers)) {
                    label = isScrewOrFastener ? 'Gauge' : 'Color';
                } else {
                    label = `Option ${i + 1}`;
                }

                let finalLabel = label;
                let counter = 2;
                while (partDefs.some((other, oIdx) => oIdx < i && other.label === finalLabel)) {
                    finalLabel = `${label} ${counter++}`;
                }
                pd.label = finalLabel;
            });

            let varyingCount = partDefs.filter(p => p.count > 1).length;
            let activeDefs = varyingCount > 0 ? partDefs.filter(p => p.count > 1) : partDefs;

            activeDefs.sort((a, b) => {
                if (a.label === 'Gauge' && b.label === 'Length') return -1;
                if (a.label === 'Length' && b.label === 'Gauge') return 1;
                return 0;
            });

            activeDefs.forEach(pd => {
                attrMap[pd.label] = pd.distinctVals;
            });

            variants.forEach((v, vIdx) => {
                v._attrs = {};
                partDefs.forEach(pd => {
                    v._attrs[pd.label] = nameParts[vIdx][pd.partIndex];
                });
            });
        } else {
            let sizes = [...new Set(variants.map(v => v.size || v.variant_size).filter(s => s && s !== '-'))];
            let colors = [...new Set(variants.map(v => v.color || v.variant_color).filter(c => c && c !== '-'))];

            let pNameLower = (product.item_name || '').toLowerCase();
            let isScrewOrFastener = pNameLower.includes('screw') || pNameLower.includes('bolt') || pNameLower.includes('nut') || pNameLower.includes('nail');
            let sizeLabel = isScrewOrFastener ? 'Length' : 'Size';
            let colorLabel = isScrewOrFastener ? 'Gauge' : 'Color';

            if (colors.length > 0) attrMap[colorLabel] = colors;
            if (sizes.length > 0) attrMap[sizeLabel] = sizes;

            variants.forEach(v => {
                v._attrs = {};
                if (colors.length > 0) v._attrs[colorLabel] = v.color || v.variant_color || '-';
                if (sizes.length > 0) v._attrs[sizeLabel] = v.size || v.variant_size || '-';
            });
        }

        return attrMap;
    }

    function updateMatrixPickerCombinations(product, variants, attrKeys) {
        attrKeys.forEach((attrKey) => {
            const $row = $(`.matrix-attr-row[data-attr="${attrKey}"]`);
            $row.find('.matrix-attr-input').each(function() {
                const $radio = $(this);
                const val = $radio.val();
                const $pill = $radio.closest('.matrix-radio-pill');

                const testAttrs = Object.assign({}, currentSelectedMatrixAttrs);
                testAttrs[attrKey] = val;

                const hasValidVariant = variants.some(v => {
                    return Object.entries(testAttrs).every(([k, vVal]) => {
                        return !vVal || v._attrs[k] === vVal;
                    });
                });

                if (hasValidVariant) {
                    $radio.prop('disabled', false);
                    $pill.removeClass('disabled-pill');
                } else {
                    $radio.prop('disabled', true);
                    if ($radio.is(':checked')) {
                        $radio.prop('checked', false);
                        delete currentSelectedMatrixAttrs[attrKey];
                        $pill.removeClass('selected-pill');
                    }
                    $pill.addClass('disabled-pill');
                }
            });
        });

        const allSelected = attrKeys.every(k => !!currentSelectedMatrixAttrs[k]);

        if (allSelected) {
            const matched = variants.find(v => {
                return attrKeys.every(k => v._attrs[k] === currentSelectedMatrixAttrs[k]);
            });

            if (matched) {
                currentMatchedMatrixVariant = matched;

                const salePrice = matched.retail_price !== undefined ? matched.retail_price : (matched.price !== undefined ? matched.price : (product.sale_price_per_piece || 0));
                const rotPrice = matched.wholesale_price !== undefined ? matched.wholesale_price : (product.wholesale_price || 0);

                $('#picker_matrix_item_price').text('Rs. ' + parseFloat(salePrice).toFixed(2));
                $('#picker_matrix_item_rot_price').text('Rs. ' + parseFloat(rotPrice).toFixed(2));

                const vSkyPCode = matched.sky_p_code || matched.p_code || (window.encodeToPCode ? window.encodeToPCode(salePrice) : '');
                const vRotPCode = matched.rot_p_code || (window.encodeToPCode ? window.encodeToPCode(rotPrice) : '');
                $('#picker_matrix_item_pcode').text(vSkyPCode || '---');
                $('#picker_matrix_item_rot_pcode').text(vRotPCode || '---');

                const stockQty = matched.stock_pieces !== undefined ? matched.stock_pieces : (matched.stock || 0);
                const unit = matched.unit || product.unit?.name || 'Units';
                $('#picker_matrix_stock_display').text(`${stockQty} ${unit}`);

                const vSerial = matched.serial_no ? matched.serial_no.trim() : '';
                const vLocation = matched.location ? matched.location.trim() : '';

                if (vSerial || vLocation) {
                    if (vSerial) {
                        $('#picker_matrix_serial_badge').text(vSerial).removeClass('d-none');
                    } else {
                        $('#picker_matrix_serial_badge').addClass('d-none');
                    }

                    if (vLocation) {
                        $('#picker_matrix_location_text').text(vLocation);
                        $('#picker_matrix_location_badge_wrap').removeClass('d-none');
                    } else {
                        $('#picker_matrix_location_badge_wrap').addClass('d-none');
                        $('#picker_matrix_location_text').text('---');
                    }
                    $('#picker_matrix_serial_badge_container').removeClass('d-none');
                } else {
                    $('#picker_matrix_serial_badge_container').addClass('d-none');
                    $('#picker_matrix_location_badge_wrap').addClass('d-none');
                    $('#picker_matrix_location_text').text('---');
                }

                $('#pickerSelectedVariantInfo').html(`
                    <span class="badge font-monospace" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px; padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center;">
                        <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Ready
                    </span>
                    <strong class="text-dark text-capitalize font-monospace" style="font-size: 12px; margin-left: 4px;">${escapeHtml(matched.name || product.item_name)}</strong>
                    ${vSerial ? `<span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">[${escapeHtml(vSerial)}]</span>` : ''}
                    ${vLocation ? `<span class="badge font-monospace" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;"><i class="fas fa-map-marker-alt text-danger" style="margin-right: 4px;"></i>${escapeHtml(vLocation)}</span>` : ''}
                    <span class="badge font-monospace" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">Sky: ${escapeHtml(vSkyPCode)}</span>
                    <span class="badge font-monospace" style="background: #f0fdf4; color: #065f46; border: 1px solid #bbf7d0; font-size: 11px; padding: 3px 7px; border-radius: 4px; margin-left: 4px;">Rot: ${escapeHtml(vRotPCode)}</span>
                    <span class="text-muted small" style="margin-left: 6px;">(Stock: <strong>${stockQty}</strong> ${unit})</span>
                `);

                $('#btnPickerAddMatrixVariant').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Add Variant');
            } else {
                currentMatchedMatrixVariant = null;
                $('#pickerSelectedVariantInfo').html('<span class="text-warning small"><i class="fas fa-exclamation-triangle me-1"></i> Combination not available in stock</span>');
                $('#btnPickerAddMatrixVariant').prop('disabled', true).html('<i class="fas fa-check me-1"></i> Add Variant');
            }
        } else {
            currentMatchedMatrixVariant = null;
            $('#pickerSelectedVariantInfo').html('<span class="text-muted small"><i class="fas fa-info-circle me-1 text-primary"></i> Select options above to view availability</span>');
            $('#btnPickerAddMatrixVariant').prop('disabled', true).html('<i class="fas fa-check me-1"></i> Add Variant');
        }
    }

    // Add variant button from Matrix view
    $(document).on('click', '#btnPickerAddMatrixVariant', function() {
        if (currentMatchedMatrixVariant) {
            $('#modalVariantPicker').modal('hide');
            proceedToAddItem(currentMatchedMatrixVariant);
        }
    });

    // Explicit Close Handlers for Variant Picker Modal (Header X & Footer Close)
    $(document).on('click', '#btnPickerModalCloseHeader, #btnPickerModalCloseFooter', function(e) {
        e.preventDefault();
        $('#modalVariantPicker').modal('hide');
    });

    function renderVariantRows(variants, productBase) {
        const $tbody = $('#variantPickerTableBody').empty();
        if (!variants || !variants.length) {
            $tbody.html(`
                <tr><td colspan="8" class="text-center py-4 text-muted">No variants available for this item.</td></tr>
            `);
            return;
        }

        variants.forEach(v => {
            let vData = null;
            if (v.variant_data) {
                try {
                    vData = JSON.parse(atob(v.variant_data));
                } catch(e) {
                    try { vData = typeof v.variant_data === 'object' ? v.variant_data : JSON.parse(v.variant_data); } catch(e2) {}
                }
            }

            const size = (vData && vData.size) ? vData.size : (v.size || '-');
            const color = (vData && vData.color) ? vData.color : (v.color || '-');
            const serialNo = v.serial_no || (vData && vData.serial_no) || '-';
            const stockPieces = parseFloat(v.stock_pieces !== undefined ? v.stock_pieces : (v.stock || 0));
            const pCode = v.sky_p_code || v.p_code || (vData && (vData.sky_p_code || vData.p_code)) || '-';
            const price = parseFloat(v.retail_price !== undefined ? v.retail_price : (v.price || 0)).toFixed(2);
            const isStock = stockPieces > 0;

            const tr = `
                <tr>
                    <td class="text-center font-monospace">${escapeHtml(serialNo)}</td>
                    <td><strong>${escapeHtml(v.name || (productBase ? productBase.item_name || productBase.name : 'Variant'))}</strong></td>
                    <td class="text-center">${escapeHtml(size)}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border">${escapeHtml(color)}</span></td>
                    <td class="text-center">
                        <span class="badge ${isStock ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'}">
                            ${stockPieces}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace">${escapeHtml(pCode)}</span>
                    </td>
                    <td class="text-end fw-bold text-dark">Rs. ${price}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary fw-bold px-2 py-1 btn-select-matrix-variant"
                                data-variant='${JSON.stringify(v).replace(/'/g, "&apos;")}'>
                            <i class="fas fa-check me-1"></i> Select
                        </button>
                    </td>
                </tr>
            `;
            $tbody.append(tr);
        });
    }

    // Variant Filter Search
    $('#variantPickerSearchInput').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        if (!q) {
            renderVariantRows(currentMatrixVariants);
            return;
        }
        const filtered = currentMatrixVariants.filter(v => {
            const str = JSON.stringify(v).toLowerCase();
            return str.includes(q);
        });
        renderVariantRows(filtered);
    });

    // Variant Selection Trigger (from Table View)
    $(document).on('click', '.btn-select-matrix-variant', function() {
        const v = $(this).data('variant');
        $('#modalVariantPicker').modal('hide');
        proceedToAddItem(v);
    });

    // ==============================================================
    // 7c. ITEM ADDITION PIPELINE: ZERO PRICE (IMAGE 2) & QTY CHECK (IMAGE 3)
    // ==============================================================
    let pendingSaleItem = null;
    let userDontAskZeroPrice = false;
    let userDontWarnInsufficientQty = false;

    function extractItemMetadata(rawItem) {
        let vData = null;
        if (rawItem.variant_data) {
            try {
                vData = JSON.parse(atob(rawItem.variant_data));
            } catch(e) {
                try { vData = typeof rawItem.variant_data === 'object' ? rawItem.variant_data : JSON.parse(rawItem.variant_data); } catch(e2) {}
            }
        }
        if (rawItem.colorJson && !vData) {
            try { vData = typeof rawItem.colorJson === 'object' ? rawItem.colorJson : JSON.parse(rawItem.colorJson); } catch(e) {}
        }

        const parsed = (typeof window.parseItemDetailsClean === 'function') ? window.parseItemDetailsClean(rawItem) : {
            itemName: rawItem.name || rawItem.item_name || rawItem.text || 'Item',
            attribute: rawItem.attribute || rawItem.color || (vData && vData.color) || '-',
            size: rawItem.size || (vData && vData.size) || '-',
            salesDesc: rawItem.sales_desc || (vData && vData.sales_desc) || (rawItem.name || 'Item')
        };

        const itemName = parsed.itemName;
        const salesDesc = parsed.salesDesc;
        const dept = rawItem.dept || rawItem.category || (vData && vData.dept) || 'General';
        const vendor = rawItem.vendor_desc || rawItem.brand || (vData && vData.vendor) || 'Khunty';
        const attr = parsed.attribute;
        const size = parsed.size;
        const unit = rawItem.unit_name || rawItem.unit || (vData && vData.unit) || 'Pcs';
        const price = parseFloat(rawItem.price !== undefined ? rawItem.price : (rawItem.retail_price !== undefined ? rawItem.retail_price : (rawItem.sale_price_per_piece || 0)));
        const rootPrice = parseFloat(rawItem.wholesale_price !== undefined ? rawItem.wholesale_price : (rawItem.rot_price !== undefined ? rawItem.rot_price : ((vData && (vData.wholesale_price !== undefined ? vData.wholesale_price : vData.rot_price)) || 0)));
        const stock = parseFloat(rawItem.stock_pieces !== undefined ? rawItem.stock_pieces : (rawItem.stock || 0));
        let pCode = rawItem.sky_p_code || rawItem.p_code || (vData && (vData.sky_p_code || vData.p_code)) || '';
        if (!pCode && price > 0 && typeof window.encodeToPCode === 'function') {
            pCode = window.encodeToPCode(price);
        }

        let rotCode = rawItem.rot_p_code || (vData && vData.rot_p_code) || '';
        if (!rotCode && rootPrice > 0 && typeof window.encodeToPCode === 'function') {
            rotCode = window.encodeToPCode(rootPrice);
        }

        let pId = rawItem.product_id;
        if (!pId && rawItem.id) {
            if (typeof rawItem.id === 'string' && rawItem.id.indexOf('|') !== -1) {
                pId = parseInt(rawItem.id.split('|')[0], 10);
            } else if (!isNaN(parseInt(rawItem.id, 10))) {
                pId = parseInt(rawItem.id, 10);
            }
        }

        let uomUnits = rawItem.uom_units || (vData && vData.uom_units) || [];
        let uomSellUnit = rawItem.uom_sell_unit || (vData && vData.uom_sell_unit) || '';
        let baseUnitName = rawItem.base_unit_name || unit;
        let selectedUnit = baseUnitName;
        let uomRatio = 1;

        if (Array.isArray(uomUnits) && uomUnits.length > 0) {
            let defaultUnit = null;
            if (uomSellUnit) {
                defaultUnit = uomUnits.find(u => u.name && u.name.toLowerCase() === uomSellUnit.toLowerCase());
            }
            if (!defaultUnit) {
                defaultUnit = uomUnits.find(u => parseFloat(u.ratio) === 1) || uomUnits[0];
            }
            if (defaultUnit) {
                selectedUnit = defaultUnit.name;
                uomRatio = parseFloat(defaultUnit.ratio) || 1;
            }
        }

        return {
            raw: rawItem,
            productId: pId || null,
            sku: rawItem.sku || rawItem.item_code || rawItem.serial_no || '',
            name: itemName,
            salesDesc: salesDesc,
            dept: dept,
            vendor: vendor,
            attribute: attr,
            size: size,
            unit: unit,
            price: price,
            rootPrice: rootPrice,
            stock: stock,
            pCode: pCode,
            rotCode: rotCode,
            variantData: vData || rawItem.variantData || null,
            qty: parseFloat(rawItem.qty || 1),
            uomUnits: uomUnits,
            uomName: selectedUnit,
            uomRatio: uomRatio
        };
    }

    window.proceedToAddItem = function(item) {
        if (!item) return;
        const meta = extractItemMetadata(item);
        pendingSaleItem = meta;

        // Step 1: Price Entry Modal (Modal 10) - Always ask / confirm price first (even if price is in DB)
        showZeroPriceModal(meta);
    };

    function showZeroPriceModal(meta) {
        const rootPrice = parseFloat(meta.rootPrice) || 0;

        $('#zpModalTitle').text('Item Price');
        // Hide Image 3 price line completely as requested
        $('#zpPriceTitle').text('');
        $('#zpPriceTitleRow').hide();
        $('#zpDescText').text('Enter the price to be charged on the document.');

        // Empty field & cursor as requested ("new price ke field empty rakho cursor field ma ho. or price show nai ho.")
        $('#zpNewPriceInput').val('');

        $('#zpItemName').text(meta.name);
        $('#zpSalesDesc').text(meta.salesDesc || '-');
        $('#zpDept').text(meta.dept || '-');
        $('#zpVendor').text(meta.vendor || '-');
        $('#zpAttribute').text(meta.attribute || '-');
        $('#zpSize').text(meta.size || '-');
        $('#zpUnit').text(meta.unit || '-');
        // P-Code in Image 2 box table: Show both Sky and Root P-Codes
        $('#zpSkyVal').text(meta.pCode || '-');
        $('#zpRotVal').text(meta.rotCode || '-');

        // Root Price & ROT Code Info hidden from modal as requested
        $('#zpRootPriceInfo').hide();

        checkZpPriceBelowRootWarning();

        $('#modalZeroPriceItem').modal('show');
        $('#modalZeroPriceItem').one('shown.bs.modal', function() {
            $('#zpNewPriceInput').focus();
        });
        setTimeout(() => {
            $('#zpNewPriceInput').focus();
        }, 320);
    }

    function checkZpPriceBelowRootWarning() {
        if (!pendingSaleItem) return;
        const valStr = $('#zpNewPriceInput').val();
        if (!valStr || valStr.trim() === '') {
            $('#zpPriceBelowRootWarning').addClass('d-none');
            return;
        }
        const p = parseFloat(valStr);
        const root = pendingSaleItem.rootPrice || 0;
        if (!isNaN(p) && root > 0 && p < root) {
            $('#zpWarnEnteredPrice').text('Rs. ' + p.toFixed(2));
            $('#zpPriceBelowRootWarning').removeClass('d-none');
        } else {
            $('#zpPriceBelowRootWarning').addClass('d-none');
        }
    }

    $('#zpNewPriceInput').on('input change', function() {
        checkZpPriceBelowRootWarning();
    });

    $('#zpNewPriceInput').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#btnZPContinue').click();
        }
    });

    $('#btnZPContinue').on('click', function() {
        if (!pendingSaleItem) return;
        const inputVal = $('#zpNewPriceInput').val();
        const newPrice = parseFloat(inputVal);

        if (inputVal === '' || isNaN(newPrice) || newPrice < 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Price Required',
                    text: 'Please enter a valid price before continuing.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Please enter a valid price.');
            }
            $('#zpNewPriceInput').focus();
            return;
        }

        pendingSaleItem.price = newPrice;
        pendingSaleItem.raw.price = newPrice;
        pendingSaleItem.raw.retail_price = newPrice;

        if ($('#chkUpdateSalePrice').is(':checked')) {
            pendingSaleItem.raw.update_sale_price = true;
        }

        const rootPrice = pendingSaleItem.rootPrice || 0;

        // Check if price is below root price
        if (rootPrice > 0 && newPrice < rootPrice) {
            // If already approved by admin for this price, proceed to Quantity Modal
            if (pendingSaleItem.approved_by_admin && pendingSaleItem.approved_price === newPrice) {
                transitionToQtyModal(pendingSaleItem);
                return;
            }

            // If user is Admin / Super Admin, allow price override directly -> proceed to Quantity Modal
            if (window.isUserAdmin) {
                transitionToQtyModal(pendingSaleItem);
                return;
            }

            // Otherwise, Staff requires Admin permission!
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Need Admin Permission!',
                    html: `Entered price <b class="text-danger font-monospace">Rs. ${newPrice.toFixed(2)}</b> is lower than the Root Price.<br><br><strong>Need admin permission to sell at this price.</strong><br>Do you want to send an approval request to Admin?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Yes, Request Admin',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#1e40af',
                    cancelButtonColor: '#64748b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendPriceApprovalRequest(pendingSaleItem, pendingSaleItem.qty || 1, newPrice, rootPrice);
                    }
                });
            } else {
                if (confirm(`Entered price Rs. ${newPrice.toFixed(2)} is lower than Root Price. Need admin permission to sell at this price. Request Admin Approval?`)) {
                    sendPriceApprovalRequest(pendingSaleItem, pendingSaleItem.qty || 1, newPrice, rootPrice);
                }
            }
            return;
        }

        // Price is valid and >= root price -> Proceed to Step 2 (Quantity Modal)
        transitionToQtyModal(pendingSaleItem);
    });

    function transitionToQtyModal(item) {
        let fired = false;
        function openQty() {
            if (fired) return;
            fired = true;
            showQuantityEntryModal(item);
        }

        $('#modalZeroPriceItem').one('hidden.bs.modal', function() {
            setTimeout(openQty, 40);
        });
        $('#modalZeroPriceItem').modal('hide');
        setTimeout(openQty, 220); // Fallback
    }

    $('#btnZPCancel, #btnZPHeaderClose').on('click', function() {
        $('#modalZeroPriceItem').modal('hide');
        pendingSaleItem = null;
        $itemInput.focus();
    });

    function showQuantityEntryModal(meta) {
        const avail = meta.stock;
        const reqQty = meta.qty || 1;

        $('#qeItemName').text(meta.name);
        $('#qeSalesDesc').text(meta.salesDesc || '-');
        $('#qeDept').text(meta.dept || '-');
        $('#qeVendor').text(meta.vendor || '-');
        $('#qeAttribute').text(meta.attribute || '-');
        $('#qeSize').text(meta.size || '-');
        $('#qeUnit').text(meta.unit || '-');
        // P-Code in Quantity Modal: Show both Sky and Root P-Codes
        $('#qeSkyVal').text(meta.pCode || '-');
        $('#qeRotVal').text(meta.rotCode || '-');
        $('#qeQtyOnHand').text(avail);
        $('#qeQtyAvail').text(avail);
        $('#qeQtyInput').val(reqQty);

        // Display confirmed unit price from Step 1
        const priceToShow = (meta.price || 0).toFixed(2);
        $('#qeConfirmedPriceBadge').text('Rs. ' + priceToShow);

        const isLow = (avail <= 0 || reqQty > avail);
        updateQtyModalDisplay(isLow);

        $('#modalQtyEntry').modal('show');

        const focusAndSelectQty = function() {
            const el = document.getElementById('qeQtyInput');
            if (el) {
                el.focus();
                try { el.select(); } catch(e) {}
            }
        };

        focusAndSelectQty();
        setTimeout(focusAndSelectQty, 60);
        setTimeout(focusAndSelectQty, 180);
        setTimeout(focusAndSelectQty, 350);
        setTimeout(focusAndSelectQty, 550);
    }

    $('#modalQtyEntry').on('shown.bs.modal', function() {
        const el = document.getElementById('qeQtyInput');
        if (el) {
            el.focus();
            try { el.select(); } catch(e) {}
        }
        setTimeout(function() {
            if (el) {
                el.focus();
                try { el.select(); } catch(e) {}
            }
        }, 80);
    });

    function updateQtyModalDisplay(isLow) {
        if (isLow) {
            $('#qeModalHeader').removeClass('bg-primary').addClass('qb-insufficient-header');
            $('#qeModalTitle').text('Insufficient Quantity');
            $('#qeInsufficientAlert').show();
            $('#qeDontWarnRow').show();
            $('#btnQERemoveItem').show();
        } else {
            $('#qeModalHeader').removeClass('qb-insufficient-header').addClass('bg-primary');
            $('#qeModalTitle').text('Enter Item Quantity');
            $('#qeInsufficientAlert').hide();
            $('#qeDontWarnRow').hide();
            $('#btnQERemoveItem').hide();
        }
    }

    $('#qeQtyInput').on('input', function() {
        if (!pendingSaleItem) return;
        const q = parseFloat($(this).val()) || 0;
        const avail = pendingSaleItem.stock;
        updateQtyModalDisplay(avail <= 0 || q > avail);
    });

    $('#qeQtyInput').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#btnQEContinue').click();
        }
    });

    $('#btnQEContinue').on('click', function() {
        if (!pendingSaleItem) return;
        const finalQty = parseFloat($('#qeQtyInput').val());

        if (isNaN(finalQty) || finalQty <= 0) {
            if (typeof Swal !== 'undefined') Swal.fire('Invalid Quantity', 'Please enter a valid quantity greater than 0.', 'error');
            return;
        }

        pendingSaleItem.qty = finalQty;
        pendingSaleItem.raw.qty = finalQty;

        commitAddItemToGrid();
    });

    function commitAddItemToGrid() {
        if (!pendingSaleItem) return;

        if ($('#chkDontWarnQty').is(':checked')) {
            userDontWarnInsufficientQty = true;
        }

        $('#modalQtyEntry').modal('hide');

        addItemToGrid(pendingSaleItem.raw);
        playBeep();
        pendingSaleItem = null;
        $itemInput.focus();
    }

    let staffApprovalPollingTimer = null;
    let currentWaitingRequestId = null;

    function sendPriceApprovalRequest(meta, qty, reqPrice, rootPrice) {
        $('#modalQtyEntry').modal('hide');

        $('#waitingReqIdBadge').text('Sending...');
        $('#waitingItemNameText').text(meta.name);
        $('#waitingItemDescText').text(meta.salesDesc || '');
        $('#waitingQtyText').text(qty + ' ' + (meta.unit || 'Pcs'));
        $('#waitingRootPriceText').text('Rs. ' + rootPrice.toFixed(2));
        $('#waitingReqPriceText').text('Rs. ' + reqPrice.toFixed(2));

        $('#modalStaffWaitingApproval').modal('show');

        let safeProductId = meta.productId;
        if (typeof safeProductId === 'string' && safeProductId.indexOf('|') !== -1) {
            safeProductId = parseInt(safeProductId.split('|')[0], 10);
        } else if (safeProductId) {
            safeProductId = parseInt(safeProductId, 10);
        }
        if (isNaN(safeProductId)) safeProductId = null;

        let safeVariantKey = meta.sku || (meta.variantData ? meta.variantData.serial_no : null);
        if (!safeVariantKey && meta.raw && typeof meta.raw.id === 'string' && meta.raw.id.indexOf('|variant|') !== -1) {
            safeVariantKey = meta.serial_no || meta.attribute || null;
        }

        $.ajax({
            url: "{{ route('price_approval.request') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: safeProductId,
                variant_key: safeVariantKey,
                item_name: meta.name,
                sales_desc: meta.salesDesc,
                qty: qty,
                regular_price: meta.raw.trade_price || meta.price || rootPrice,
                root_price: rootPrice,
                requested_price: reqPrice
            },
            dataType: "json",
            success: function(resp) {
                if (resp.success && resp.status === 'pending') {
                    currentWaitingRequestId = resp.request_id;
                    $('#waitingReqIdBadge').text('Req #' + resp.request_id);
                    startStaffApprovalPolling(resp.request_id, meta, qty, reqPrice);
                } else if (resp.success && resp.is_admin) {
                    $('#modalStaffWaitingApproval').modal('hide');
                    meta.approved_by_admin = true;
                    meta.approved_price = reqPrice;
                    pendingSaleItem = meta;
                    showQuantityEntryModal(pendingSaleItem);
                } else {
                    $('#modalStaffWaitingApproval').modal('hide');
                    if (typeof Swal !== 'undefined') Swal.fire('Error', resp.message || 'Could not send request', 'error');
                }
            },
            error: function(xhr) {
                $('#modalStaffWaitingApproval').modal('hide');
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to connect to Admin approval service';
                if (typeof Swal !== 'undefined') Swal.fire('Error', msg, 'error');
            }
        });
    }

    function startStaffApprovalPolling(reqId, meta, qty, reqPrice) {
        if (staffApprovalPollingTimer) clearInterval(staffApprovalPollingTimer);

        staffApprovalPollingTimer = setInterval(function() {
            $.ajax({
                url: "/price-approval/check-status/" + reqId,
                type: "GET",
                dataType: "json",
                success: function(resp) {
                    if (!resp || !resp.success) return;

                    if (resp.status === 'approved') {
                        clearInterval(staffApprovalPollingTimer);
                        staffApprovalPollingTimer = null;
                        currentWaitingRequestId = null;
                        $('#modalStaffWaitingApproval').modal('hide');

                        playBeep();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Admin Allowed!',
                                html: `Admin <b>${resp.approver_name || 'Super Admin'}</b> allowed selling <b>${meta.name}</b> at <b class="text-success font-monospace">Rs. ${parseFloat(reqPrice).toFixed(2)}</b>.`,
                                timer: 3000,
                                showConfirmButton: true
                            });
                        }

                        meta.approved_by_admin = true;
                        meta.approved_price = reqPrice;
                        meta.approval_id = reqId;
                        meta.price = reqPrice;
                        meta.raw.price = reqPrice;
                        meta.raw.retail_price = reqPrice;
                        meta.raw.qty = qty;
                        meta.raw.admin_approved = true;

                        pendingSaleItem = meta;
                        // Open Quantity Entry Modal (Step 2)
                        showQuantityEntryModal(pendingSaleItem);
                    } else if (resp.status === 'rejected') {
                        clearInterval(staffApprovalPollingTimer);
                        staffApprovalPollingTimer = null;
                        currentWaitingRequestId = null;
                        $('#modalStaffWaitingApproval').modal('hide');

                        const reasonText = resp.rejection_reason ? `<br><small class="text-muted">Reason: ${resp.rejection_reason}</small>` : '';

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Admin Rejected!',
                                html: `Admin <b>${resp.approver_name || 'Super Admin'}</b> rejected selling below root price.${reasonText}`,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc2626'
                            });
                        }

                        meta.price = meta.rootPrice;
                        meta.raw.price = meta.rootPrice;
                        meta.raw.retail_price = meta.rootPrice;
                        pendingSaleItem = meta;
                        // Re-open Price Modal (Step 1)
                        showZeroPriceModal(pendingSaleItem);
                    }
                }
            });
        }, 2000);
    }

    $('#btnCancelWaitingApproval').on('click', function() {
        if (staffApprovalPollingTimer) {
            clearInterval(staffApprovalPollingTimer);
            staffApprovalPollingTimer = null;
        }
        if (currentWaitingRequestId) {
            $.post("/price-approval/cancel/" + currentWaitingRequestId, { _token: "{{ csrf_token() }}" });
            currentWaitingRequestId = null;
        }
        $('#modalStaffWaitingApproval').modal('hide');
        $itemInput.focus();
    });

    $('#btnQERemoveItem, #btnQEHeaderClose').on('click', function() {
        $('#modalQtyEntry').modal('hide');
        pendingSaleItem = null;
        $itemInput.focus();
    });

    $('#btnQESeek').on('click', function() {
        $('#qeQtyInput').focus().select();
    });

    // ==============================================================
    // 8. LEFT SIDEBAR ACTIONS & MODALS TRIGGER
    // ==============================================================

    $('#btnAddNewItem').on('click', function() {
        // Focus search box or open customer quick add
        $itemInput.focus();
    });

    $('#btnGiveDiscount').on('click', function() {
        $('#modalGiveDiscount').modal('show');
    });

    $('#btnApplyOverallDiscount').on('click', function() {
        const type = $('#modalDiscountTypeSelect').val();
        const val = parseFloat($('#modalDiscountValueInput').val()) || 0;
        $('#overallDiscountType').val(type);
        $('#overallDiscountVal').val(val);
        $('#modalGiveDiscount').modal('hide');
        recomputeTotals();
    });

    $('#btnCashierAssociate, #menuCashier').on('click', function(e) {
        e.preventDefault();
        $('#modalCashierAssociate').modal('show');
    });

    $('#btnApplyAssociate').on('click', function() {
        const offId = $('#modalSalesOfficerSelect').val();
        $('#salesOfficerId').val(offId);
        $('#modalCashierAssociate').modal('hide');
        if (offId) {
            showAlert('success', 'Sales Associate selected.');
        }
    });

    $('#btnChangeDate, #menuChangeDate').on('click', function(e) {
        e.preventDefault();
        $('#modalSaleDateInput').val($('#displayDateInput').val());
        $('#modalCreditDaysInput').val($('#creditDaysInput').val());
        $('#modalRemarksInput').val($('#remarks').val());
        $('#modalChangeDate').modal('show');
    });

    $('#btnApplyChangeDate').on('click', function() {
        const d = $('#modalSaleDateInput').val();
        const cd = $('#modalCreditDaysInput').val();
        const rem = $('#modalRemarksInput').val();

        $('#displayDateInput').val(d);
        $('#creditDaysInput').val(cd);
        $('#remarks').val(rem);
        $('#qbDisplayDateText').text(d);
        $('#modalChangeDate').modal('hide');
    });

    $('#btnShowMessages').on('click', function() {
        Swal.fire({
            title: 'System Messages',
            html: '<div class="text-start" style="font-size:12.5px;"><i class="fas fa-info-circle text-primary me-2"></i> QuickBooks POS mode active. All entries are synced live to Inventory & Financial Ledgers.</div>',
            icon: 'info'
        });
    });

    $('#btnShipItems, #menuPrintDC').on('click', function(e) {
        e.preventDefault();
        const existing = $('#booking_id').val();
        if (existing) {
            window.open('{{ url('sales') }}/' + existing + '/dc-thermal', '_blank');
        } else {
            Swal.fire('Ship Items', 'Please save the sale receipt first to generate Delivery Challan.', 'info');
        }
    });

    // ==============================================================
    // 8. ACCEPT RETURN MODAL (PAST INVOICE SEARCH & INJECTION)
    // ==============================================================
    $('#btnAcceptReturn').on('click', function() {
        $('#modalAcceptReturn').modal('show');
        setTimeout(() => $('#returnSearchInvoiceNo').focus(), 300);
    });

    $('#btnSearchReturnReceipt').on('click', function() {
        const term = $('#returnSearchInvoiceNo').val().trim();
        if (!term) return;

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Searching...');

        $.ajax({
            url: '{{ route('pos.search_invoice', [], false) }}',
            type: 'POST',
            data: { invoice_no: term },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i> Find Receipt');
                $('#returnReceiptMeta').text(`Invoice #${res.invoice_no} | Customer: ${res.customer_name || 'Walk-in'} | Total Items: ${res.items.length}`);

                if (!res.items || !res.items.length) {
                    $('#returnItemsContainer').html('<div class="text-danger text-center py-3">No returnable items found in this invoice.</div>');
                    return;
                }

                let html = '';
                const hasComm = res.carpenter_commission_percent && parseFloat(res.carpenter_commission_percent) > 0;
                if (hasComm) {
                    html += `
                    <div class="alert alert-info py-2 px-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2 shadow-sm" style="font-size:12px; border-left: 4px solid #0284c7; background-color: #f0f9ff; border-radius: 6px;">
                        <div>
                            <i class="fas fa-hammer text-primary me-1 fs-6"></i>
                            <strong class="text-dark">Carpenter Commission (${res.carpenter_commission_percent}%):</strong>
                            <span class="text-muted">Carpenter: <strong class="text-dark">${res.carpenter_name || 'Assigned'}</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="text-muted small me-1">Policy:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check return-comm-mode" name="commRefundMode" id="modeClawback" value="clawback" checked>
                                <label class="btn btn-outline-primary py-1 px-2 fw-bold" for="modeClawback" style="font-size:11px;" title="Customer ko receipt ka pura rate wapas karein aur commission carpenter se auto-deduct karein (ERP International Standard)">
                                    <i class="fas fa-check-circle me-1"></i> Full Refund (Deduct from Carpenter)
                                </label>

                                <input type="radio" class="btn-check return-comm-mode" name="commRefundMode" id="modeCustomerDeduct" value="cust_deduct">
                                <label class="btn btn-outline-secondary py-1 px-2" for="modeCustomerDeduct" style="font-size:11px;" title="Customer se commission kaat kar net rate wapas karein">
                                    Deduct from Customer
                                </label>
                            </div>
                        </div>
                    </div>`;
                }

                html += '<table class="table table-sm table-bordered bg-white align-middle" id="returnItemsTable" style="font-size:11.5px;">';
                html += '<thead class="table-light"><tr><th>Product</th><th>Sold Qty</th><th>Max Return</th><th>Refund Price</th><th>Return Qty</th><th>Action</th></tr></thead><tbody>';

                res.items.forEach(it => {
                    const fullPrice = parseFloat(it.raw_net_unit_price || it.price_sold || 0);
                    const netPrice = parseFloat(it.net_unit_price || it.price_sold || 0);
                    const deductAmt = parseFloat(it.commission_deduction_per_piece || 0);

                    html += `
                    <tr data-full-price="${fullPrice}" data-net-price="${netPrice}" data-deduct-amt="${deductAmt}">
                        <td>
                            <strong>${it.product_name}</strong>
                            <small class="text-muted d-block">${it.variant_data || ''}</small>
                        </td>
                        <td>${it.sold_qty}</td>
                        <td><span class="badge bg-success-subtle text-success border">${it.max_returnable}</span></td>
                        <td class="ret-price-col">
                            ${hasComm ? `
                                <strong class="text-primary ret-display-price">Rs. ${fullPrice.toFixed(2)}</strong>
                                <small class="text-success d-block ret-comm-note" style="font-size: 10px;"><i class="fas fa-shield-alt"></i> Carpenter se -Rs. ${deductAmt.toFixed(2)} katenge</small>
                            ` : `
                                <strong>Rs. ${netPrice.toFixed(2)}</strong>
                            `}
                        </td>
                        <td style="width: 80px;">
                            <input type="number" class="form-control form-control-sm text-center ret-qty-box" value="1" min="1" max="${it.max_returnable}">
                        </td>
                        <td style="width: 90px;">
                            <button type="button" class="btn btn-sm btn-danger fw-bold btn-add-ret-item"
                                    data-item='${JSON.stringify(it).replace(/'/g, "&apos;")}'
                                    data-receipt-no="${res.invoice_no}"
                                    data-sale-id="${res.sale_id}">
                                <i class="fas fa-undo"></i> Return
                            </button>
                        </td>
                    </tr>`;
                });
                html += '</tbody></table>';
                $('#returnItemsContainer').html(html);
            },
            error: function(err) {
                $btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i> Find Receipt');
                const msg = err.responseJSON && err.responseJSON.error ? err.responseJSON.error : 'Failed to find sales receipt.';
                $('#returnItemsContainer').html(`<div class="text-danger text-center py-3">${msg}</div>`);
            }
        });
    });

    // Toggle between Full Refund (Deduct from Carpenter) vs Deduct from Customer
    $(document).on('change', '.return-comm-mode', function() {
        const mode = $(this).val();
        $('#returnItemsTable tbody tr').each(function() {
            const $row = $(this);
            const fullPrice = parseFloat($row.data('full-price')) || 0;
            const netPrice = parseFloat($row.data('net-price')) || 0;
            const deductAmt = parseFloat($row.data('deduct-amt')) || 0;

            if (mode === 'clawback') {
                $row.find('.ret-display-price').text('Rs. ' + fullPrice.toFixed(2)).removeClass('text-dark').addClass('text-primary');
                $row.find('.ret-comm-note').html('<i class="fas fa-shield-alt"></i> Carpenter se -Rs. ' + deductAmt.toFixed(2) + ' katenge').removeClass('text-danger').addClass('text-success');
            } else {
                $row.find('.ret-display-price').text('Rs. ' + netPrice.toFixed(2)).removeClass('text-primary').addClass('text-dark');
                $row.find('.ret-comm-note').html('<i class="fas fa-cut"></i> Customer se -Rs. ' + deductAmt.toFixed(2) + ' deducted').removeClass('text-success').addClass('text-danger');
            }
        });
    });

    $(document).on('click', '.btn-add-ret-item', function() {
        const item = $(this).data('item');
        const receiptNo = $(this).data('receipt-no');
        const saleId = $(this).data('sale-id');
        const $tr = $(this).closest('tr');
        const qty = parseFloat($tr.find('.ret-qty-box').val()) || 1;
        const commMode = $('input[name="commRefundMode"]:checked').val() || 'clawback';

        item.returnQty = qty;
        // If clawback mode, customer gets full price (raw_net_unit_price). If cust_deduct, customer gets net_unit_price.
        if (commMode === 'clawback' && item.raw_net_unit_price) {
            item.price = parseFloat(item.raw_net_unit_price);
        } else {
            item.price = parseFloat(item.net_unit_price || item.price || 0);
        }
        item.name = item.product_name;

        addItemToGrid(item, true, receiptNo, item.id, saleId);
        $('#modalAcceptReturn').modal('hide');
        showAlert('success', `Added return item for Receipt #${receiptNo}`);
    });

    // Direct Add from Quick Pick Catalog Modal
    $(document).on('click', '.btn-add-direct-catalog', function() {
        const pId = $(this).data('product-id');
        $.ajax({
            url: '{{ route('products.ajax.search', [], false) }}',
            dataType: 'json',
            data: { product_id: pId },
            success: function(data) {
                if (data.results && data.results.length) {
                    $('#modalQuickPickItems').modal('hide');
                    proceedToAddItem(data.results[0]);
                }
            }
        });
    });

    // Catalog Filter Search
    $('#quickPickCatalogSearch').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        $('.quick-pick-card-item').each(function() {
            const s = $(this).data('search');
            $(this).toggle(s.includes(q));
        });
    });

    // ==============================================================
    // 9. SUBMIT ACTIONS: PUT ON HOLD, SAVE ONLY, SAVE & PRINT
    // ==============================================================
    function processSubmission(actionType, callback) {
        if (gridRows.length === 0) {
            Swal.fire('Empty Receipt', 'Please add at least one item or return to the receipt before saving.', 'warning');
            return;
        }

        // Validate credit / unpaid Khata balance with walk-in customer
        const isWalkin = ($('#is_walkin').val() === '1');
        const unpaidKhata = (typeof orderPaymentAllocation !== 'undefined' && orderPaymentAllocation.khataAmount) ? orderPaymentAllocation.khataAmount : (currentActivePaymentMethod === 'account' || currentActivePaymentMethod === 'credit' ? (parseFloat($('#qbStatGrandTotal').text()) || 0) : 0);
        if (actionType === 'sale' && isWalkin && (unpaidKhata > 0.05 || currentActivePaymentMethod === 'account')) {
            Swal.fire({
                title: 'Customer Required',
                text: 'Account / Khata sale sirf registered / main customer ke liye hai. Walk-in customer ko pori payment karni hogi. Barah-e-karam pehle customer select karein.',
                icon: 'warning',
                confirmButtonText: '<i class="fas fa-user-plus me-1"></i> Select Customer',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                $('#qbCustomerSearchInput').focus();
            });
            return;
        }

        $('#action').val(actionType);
        syncHiddenInputs();
        if (typeof syncPaymentAccounts === 'function') {
            syncPaymentAccounts();
        }

        const $btns = $('#btnSaveOnly, #btnSaveAndPrint, #btnPutOnHold');
        $btns.prop('disabled', true);

        $.ajax({
            url: '{{ route('sales.store', [], false) }}',
            type: 'POST',
            data: $('#saleForm').serialize(),
            success: function(res) {
                $btns.prop('disabled', false);
                $('#allow_insufficient_stock').val('0');
                if (res && (res.ok || res.success || res.booking_id || res.sale_id)) {
                    const savedId = res.sale_id || res.booking_id || $('#booking_id').val();
                    if (callback) {
                        callback(savedId, res);
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Sales Receipt saved successfully.',
                            icon: 'success',
                            timer: 1400,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('sale.index', [], false) }}";
                        });
                    }
                } else {
                    Swal.fire('Notice', res.message || 'Saved successfully.', 'success').then(() => {
                        window.location.href = "{{ route('sale.index', [], false) }}";
                    });
                }
            },
            error: function(xhr) {
                $btns.prop('disabled', false);
                let msg = 'Failed to save sales receipt. Please review form entries.';
                let isInsufficientStock = false;

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    if (xhr.responseJSON.insufficient_stock || msg.toLowerCase().includes('insufficient stock')) {
                        isInsufficientStock = true;
                    }
                }

                if (isInsufficientStock) {
                    Swal.fire({
                        title: 'Insufficient Stock Warning',
                        html: `
                            <div class="text-center py-1">
                                <div class="alert alert-warning py-2 px-3 text-dark fw-bold mb-3 text-start" style="font-size: 13px; border-radius: 6px; background-color: #fff3cd; border: 1px solid #ffeeba;">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i> ${msg}
                                </div>
                                <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">
                                    Aapke paas inventory me stock mojood nahi hai.
                                </div>
                                <div style="font-size: 14px; font-weight: 700; color: #dc2626;">
                                    Do you still want to sell?
                                </div>
                                <div class="text-muted small mt-2" style="font-size: 11px;">
                                    Agar aap <strong>Yes, Sell Anyway</strong> karenge to negative stock record ho jayega aur sale confirm create ho jayegi.
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Yes, Sell Anyway',
                        cancelButtonText: '<i class="fas fa-times-circle me-1"></i> No, Cancel',
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#64748b',
                        reverseButtons: true,
                        focusConfirm: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#allow_insufficient_stock').val('1');
                            processSubmission(actionType, callback);
                        } else {
                            $('#allow_insufficient_stock').val('0');
                        }
                    });
                } else {
                    Swal.fire('Submission Error', msg, 'error');
                }
            }
        });
    }

    // "Put on Hold" (Booking Draft)
    $('#btnPutOnHold').on('click', function() {
        processSubmission('booking', function(savedId) {
            Swal.fire({
                title: 'Put on Hold',
                text: `Sales Receipt placed on hold / booking (ID #${savedId}).`,
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                if (orders.length > 1) {
                    orders.splice(activeOrderIndex, 1);
                    orders.forEach((o, i) => o.number = i + 1);
                    if (activeOrderIndex >= orders.length) activeOrderIndex = orders.length - 1;
                    loadOrderState(activeOrderIndex);
                } else {
                    orders[0] = createEmptyOrder(1);
                    loadOrderState(0);
                    refreshInvoiceNumber();
                }
            });
        });
    });

    // Universal Modal Dismiss Handler (Supports Bootstrap 4, Bootstrap 5, & direct jQuery)
    $(document).on('click', '[data-bs-dismiss="modal"], [data-dismiss="modal"], .btn-close', function(e) {
        const modal = $(this).closest('.modal');
        if (modal.length) {
            e.preventDefault();
            modal.modal('hide');
        }
    });

    // "Cancel" Receipt / Bill
    window.handleCancelReceipt = function(e) {
        if (e && typeof e.preventDefault === 'function') e.preventDefault();
        if (e && typeof e.stopPropagation === 'function') e.stopPropagation();

        if (gridRows.length > 0) {
            Swal.fire({
                title: 'Cancel Receipt?',
                text: `Current bill has ${gridRows.length} item(s). What would you like to do?`,
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonColor: '#dc2626',
                denyButtonColor: '#1e3a8a',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Discard / Clear Bill',
                denyButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Exit to Sales List',
                cancelButtonText: 'Keep Bill'
            }).then((res) => {
                if (res.isConfirmed) {
                    if (typeof orders !== 'undefined' && orders.length > 1) {
                        orders.splice(activeOrderIndex, 1);
                        orders.forEach((o, i) => o.number = i + 1);
                        if (activeOrderIndex >= orders.length) activeOrderIndex = orders.length - 1;
                        loadOrderState(activeOrderIndex);
                    } else {
                        gridRows = [];
                        if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
                            orders[activeOrderIndex].gridRows = [];
                        }
                        renderGrid();
                        recomputeTotals();
                        resetCustomerToWalkin();
                    }
                    showAlert('success', 'Current receipt cleared.');
                } else if (res.isDenied) {
                    window.location.href = "{{ route('sale.index', [], false) }}";
                }
            });
        } else {
            if (typeof orders !== 'undefined' && orders.length > 1) {
                orders.splice(activeOrderIndex, 1);
                orders.forEach((o, i) => o.number = i + 1);
                if (activeOrderIndex >= orders.length) activeOrderIndex = orders.length - 1;
                loadOrderState(activeOrderIndex);
                showAlert('info', 'Empty order tab removed.');
            } else {
                Swal.fire({
                    title: 'Exit Sales Screen?',
                    text: 'Return to Sales List?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a8a',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Exit',
                    cancelButtonText: 'Stay'
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = "{{ route('sale.index', [], false) }}";
                    }
                });
            }
        }
    };

    $(document).on('click', '#btnCancelReceipt', function(e) {
        handleCancelReceipt(e);
    });

    // "Save Only"
    $('#btnSaveOnly').on('click', function() {
        processSubmission('sale', function(id, res) {
            const proceedNext = () => {
                if (orders.length > 1) {
                    orders.splice(activeOrderIndex, 1);
                    orders.forEach((o, i) => o.number = i + 1);
                    if (activeOrderIndex >= orders.length) activeOrderIndex = orders.length - 1;
                    loadOrderState(activeOrderIndex);
                } else {
                    orders[0] = createEmptyOrder(1);
                    loadOrderState(0);
                    refreshInvoiceNumber();
                }
            };

            if (res && res.has_market_items && res.market_slip_url) {
                Swal.fire({
                    title: 'Sale Saved!',
                    html: `
                        <div class="text-start">
                            <p class="mb-2 text-success"><i class="fas fa-check-circle me-1"></i> Sales receipt posted to ledger and inventory deducted.</p>
                            <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 13px;">
                                <i class="fas fa-shopping-basket me-1"></i> <strong>Local Market Items:</strong><br>
                                Is sale mein shortage / out-of-stock items hain jo local market se mangwane hain. Kya aap <strong>Local Market Purchase Slip</strong> print karna chahte hain?
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-print me-1"></i> Print Market Slip',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Don\'t Print',
                    confirmButtonColor: '#0284c7',
                    cancelButtonColor: '#64748b'
                }).then((marketRes) => {
                    if (marketRes.isConfirmed) {
                        window.open(res.market_slip_url, '_blank');
                    }
                    proceedNext();
                });
            } else {
                Swal.fire({
                    title: 'Receipt Saved!',
                    text: 'Sales receipt posted to ledger and inventory deducted.',
                    icon: 'success',
                    timer: 1400,
                    showConfirmButton: false
                }).then(() => {
                    proceedNext();
                });
            }
        });
    });

    // "Save & Print"
    $('#btnSaveAndPrint').on('click', function() {
        processSubmission('sale', function(id, res) {
            const printUrl = (res && res.receipt_url) ? res.receipt_url : ('{{ url('sales') }}/' + id + '/recepit');
            window.open(printUrl, '_blank');

            const proceedNext = () => {
                if (orders.length > 1) {
                    orders.splice(activeOrderIndex, 1);
                    orders.forEach((o, i) => o.number = i + 1);
                    if (activeOrderIndex >= orders.length) activeOrderIndex = orders.length - 1;
                    loadOrderState(activeOrderIndex);
                } else {
                    orders[0] = createEmptyOrder(1);
                    loadOrderState(0);
                    refreshInvoiceNumber();
                }
            };

            if (res && res.has_market_items && res.market_slip_url) {
                setTimeout(() => {
                    Swal.fire({
                        title: 'Local Market Slip',
                        html: `
                            <div class="text-start">
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 13px;">
                                    <i class="fas fa-shopping-basket me-1"></i> <strong>Local Market Items:</strong><br>
                                    Is sale mein shortage items hain. Kya aap <strong>Local Market Purchase Slip</strong> bhi print karna chahte hain?
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-print me-1"></i> Print Market Slip',
                        cancelButtonText: '<i class="fas fa-times me-1"></i> Don\'t Print',
                        confirmButtonColor: '#0284c7',
                        cancelButtonColor: '#64748b'
                    }).then((marketRes) => {
                        if (marketRes.isConfirmed) {
                            window.open(res.market_slip_url, '_blank');
                        }
                        proceedNext();
                    });
                }, 500);
            } else {
                setTimeout(proceedNext, 600);
            }
        });
    });

    function refreshInvoiceNumber() {
        $.ajax({
            url: '{{ route('invoice_series.generate_no', [], false) }}',
            type: 'GET',
            data: { prefix: '{{ $activePrefix ?? "INV" }}' },
            success: function(res) {
                if (res && res.invoice_no) {
                    $('#qbInvoiceNoText').text(res.invoice_no);
                    $('#inputInvoiceNo').val(res.invoice_no);
                }
            }
        });
    }

    // Top Print Dropdowns
    $('#menuPrintA4').on('click', function(e) {
        e.preventDefault();
        processSubmission('sale', function(id) {
            window.open('{{ url('sales') }}/' + id + '/invoice', '_blank');
        });
    });

    $('#menuPrintThermal').on('click', function(e) {
        e.preventDefault();
        processSubmission('sale', function(id) {
            window.open('{{ url('sales') }}/' + id + '/recepit', '_blank');
        });
    });

    $('#menuQuotation').on('click', function(e) {
        e.preventDefault();
        processSubmission('quotation', function(id) {
            window.open('{{ url('sales') }}/' + id + '/invoice?type=quotation', '_blank');
        });
    });

    // ==============================================================
    // 10. QUICK CUSTOMER MODAL AJAX SUBMISSION & ODOO INTEGRATION
    // ==============================================================
    $('#menuQuickAddCustomer, #linkQuickCustModal').on('click', function(e) {
        e.preventDefault();
        openOdooCustomerPicker($custInput.val());
    });

    $('#btnSaveAjaxCustomer').on('click', function() {
        const form = $('#ajaxAddCustomerForm');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '{{ route('customers.store', [], false) }}',
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Customer');
                if (res.success && res.customer) {
                    $('#addCustomerModal').modal('hide');
                    $('#modalOdooCustomerPicker').modal('hide');
                    setCustomer(res.customer);
                    showAlert('success', 'Customer added successfully!');
                } else {
                    showAlert('error', res.message || 'Failed to save customer.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Customer');
                showAlert('error', 'Error adding customer.');
            }
        });
    });

    // ==============================================================
    // 11. INVOICE SERIES AJAX SUBMISSION
    // ==============================================================
    $('#menuAddSeries').on('click', function(e) {
        e.preventDefault();
        $('#modalAddInvoiceSeries').modal('show');
    });

    $('#formAddInvoiceSeries').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSaveSeries');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: "{{ route('invoice_series.store', [], false) }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                $btn.prop('disabled', false).html('Save Series');
                if (res.success) {
                    $('#modalAddInvoiceSeries').modal('hide');
                    $('#inputInvoiceNo').val(res.invoice_no);
                    $('#qbInvoiceNoBadge').text(res.invoice_no);
                    showAlert('success', `Invoice series ${res.prefix} active.`);
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('Save Series');
                showAlert('error', 'Failed to save series.');
            }
        });
    });

    // Keyboard Shortcuts (F2 Odoo Customer Screen, F3 Quick Walk-in, F9 Save) - Note: F4 is handled globally
    $(document).on('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            openOdooCustomerPicker();
        } else if (e.key === 'F3') {
            e.preventDefault();
            openQuickWalkinModal();
        } else if (e.key === 'F9') {
            e.preventDefault();
            $('#btnSaveOnly').trigger('click');
        }
    });

    function showAlert(type, msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Success' : 'Notice',
                text: msg,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            alert(msg);
        }
    }

    // Toggle Fullscreen (hides top ERP navbar and maximizes screen real estate)
    $('#btnToggleFullscreen').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('qb-fullscreen-mode');
        const isFs = $('body').hasClass('qb-fullscreen-mode');
        $(this).find('i').toggleClass('fa-expand', !isFs).toggleClass('fa-compress', isFs);
        $(this).attr('title', isFs ? 'Restore Window' : 'Toggle Fullscreen');
    });

    // Auto focus search box on start
    $itemInput.focus();

    // Check if initial sample row or existing sale items exist
    @if(isset($sale) && $sale->items && count($sale->items) > 0)
        // Edit mode pre-fill
        @foreach($sale->items as $it)
            @php
                $vData = [];
                if (!empty($it->color)) {
                    $b64 = base64_decode($it->color, true);
                    $vData = ($b64 !== false) ? json_decode($b64, true) : json_decode($it->color, true);
                }
            @endphp
            addItemToGrid({
                product_id: "{{ $it->product_id }}",
                sku: "{{ $it->product->item_code ?? $it->product_id }}",
                name: "{{ $it->product_name ?? ($it->product->item_name ?? 'Item') }}",
                vendor_desc: "{{ $it->product->brand->name ?? 'Khunty' }}",
                color: "{{ $vData['color'] ?? '-' }}",
                size: "{{ $it->size_display ?? ($vData['size'] ?? '-') }}",
                qty: {{ $it->qty ?? 1 }},
                price: {{ $it->price ?? 0 }},
                discount: {{ $it->discount_percent ?? 0 }},
                p_code: "{{ $it->p_code ?? '' }}",
                sales_desc: "{{ $it->product_name ?? 'Khunty' }}"
            });
        @endforeach
    @endif

    // Setup Column Visibility Modal Events
    $('#modalCustomizeSalesColumns').on('show.bs.modal shown.bs.modal', function() {
        if (typeof window.syncSalesColumnsModalCards === 'function') {
            window.syncSalesColumnsModalCards();
        }
    });

    $('#salesColSelectAll').on('click', function(e) {
        e.preventDefault();
        const prefs = {};
        window.SALES_GRID_COLUMNS.forEach(function(c) {
            prefs[c.key] = true;
            window.updateSalesCardUI(c.key, true);
        });
        window.saveSalesGridColPrefs(prefs);
        window.applySalesGridColVisibility(prefs);
    });

    $('#salesColResetDefault').on('click', function(e) {
        e.preventDefault();
        const prefs = {};
        window.SALES_GRID_COLUMNS.forEach(function(c) {
            prefs[c.key] = c.default;
            window.updateSalesCardUI(c.key, c.default);
        });
        window.saveSalesGridColPrefs(prefs);
        window.applySalesGridColVisibility(prefs);
    });

    // Right-click table header anytime to open customize columns modal
    $('#qbMainGridTable thead').on('contextmenu', function(e) {
        e.preventDefault();
        $('#modalCustomizeSalesColumns').modal('show');
    });

    // Menu link from I Want To dropdown
    $('#menuCustomizeColumns').on('click', function(e) {
        e.preventDefault();
        window.openCustomizeSalesColumnsModal(e);
    });

    // Button click in banner
    $(document).on('click', '#btnCustomizeSalesColumns', function(e) {
        e.preventDefault();
        e.stopPropagation();
        window.openCustomizeSalesColumnsModal(e);
    });

    // Initial sync of cards & column styles
    if (typeof window.syncSalesColumnsModalCards === 'function') {
        window.syncSalesColumnsModalCards();
    }
    if (typeof window.applySalesGridColVisibility === 'function') {
        window.applySalesGridColVisibility();
    }

    // ==============================================================
    // 14. CARPENTER COMMISSION MODAL & ACTIONS
    // ==============================================================
    window.openCarpenterModal = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        const currentCId = $('#carpenter_id').val() || '';
        const currentType = $('#carpenter_commission_type').val() || 'percentage';
        const currentRate = parseFloat($('#carpenter_commission_rate').val()) || 0;

        $('#modal_carpenter_id').val(currentCId);
        $('#modal_carpenter_comm_type').val(currentType);
        $('#modal_carpenter_comm_rate').val(currentRate);
        $('#modal_comm_unit_symbol').text(currentType === 'fixed' ? 'Rs.' : '%');

        updateCarpenterPreview();
        $('#modalCarpenterCommission').modal('show');
    };

    function updateCarpenterPreview() {
        let total = 0;
        if (typeof gridRows !== 'undefined' && Array.isArray(gridRows)) {
            total = gridRows.reduce((sum, r) => sum + (parseFloat(r.total) || 0), 0);
        } else {
            total = parseFloat($('#qbAmountDueVal').text().replace(/,/g, '')) || 0;
        }

        const type = $('#modal_carpenter_comm_type').val();
        const val = parseFloat($('#modal_carpenter_comm_rate').val()) || 0;

        let estComm = 0;
        if (type === 'percentage') {
            estComm = (total * val) / 100;
        } else {
            estComm = val;
        }

        $('#modal_carp_sale_total').text('Rs. ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#modal_carp_est_commission').text('Rs. ' + estComm.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    $('#modal_carpenter_id').on('change', function() {
        const $opt = $(this).find('option:selected');
        if ($opt.val()) {
            const defType = $opt.data('type') || 'percentage';
            const defRate = parseFloat($opt.data('rate')) || 0;
            $('#modal_carpenter_comm_type').val(defType);
            $('#modal_carpenter_comm_rate').val(defRate);
            $('#modal_comm_unit_symbol').text(defType === 'fixed' ? 'Rs.' : '%');
        }
        updateCarpenterPreview();
    });

    $('#modal_carpenter_comm_type').on('change', function() {
        const type = $(this).val();
        $('#modal_comm_unit_symbol').text(type === 'fixed' ? 'Rs.' : '%');
        updateCarpenterPreview();
    });

    $('#modal_carpenter_comm_rate').on('input', function() {
        updateCarpenterPreview();
    });

    $('#btnApplyCarpenterCommission').on('click', function() {
        const cId = $('#modal_carpenter_id').val();
        const cType = $('#modal_carpenter_comm_type').val();
        const cRate = parseFloat($('#modal_carpenter_comm_rate').val()) || 0;
        const $opt = $('#modal_carpenter_id').find('option:selected');
        const cName = $opt.val() ? ($opt.data('name') || $opt.text().trim().split('(')[0].trim()) : '';

        let total = 0;
        if (typeof gridRows !== 'undefined' && Array.isArray(gridRows)) {
            total = gridRows.reduce((sum, r) => sum + (parseFloat(r.total) || 0), 0);
        } else {
            total = parseFloat($('#qbAmountDueVal').text().replace(/,/g, '')) || 0;
        }

        let cAmount = 0;
        if (cType === 'percentage') {
            cAmount = (total * cRate) / 100;
        } else {
            cAmount = cRate;
        }

        if (cId && parseInt(cId) > 0) {
            $('#carpenter_id').val(cId);
            $('#carpenter_commission_type').val(cType);
            $('#carpenter_commission_rate').val(cRate);
            $('#carpenter_commission_amount').val(cAmount.toFixed(2));

            // Update button UI
            $('#carpenterBtnText').text(cName.length > 10 ? cName.substring(0, 10) + '...' : cName);
            const badgeText = cType === 'fixed' ? ('Rs.' + cRate) : (cRate + '%');
            $('#carpenterActiveBadge').removeClass('d-none').text(badgeText);
            $('#btnCarpenterCommission').addClass('border-success text-success');

            // Save in active order tab state
            if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
                orders[activeOrderIndex].carpenter = {
                    id: cId,
                    type: cType,
                    rate: cRate,
                    amount: cAmount.toFixed(2),
                    name: cName
                };
            }

            showAlert('success', `Carpenter ${cName} commission set (${badgeText})`);
        } else {
            // Cleared
            $('#carpenter_id').val('');
            $('#carpenter_commission_type').val('percentage');
            $('#carpenter_commission_rate').val('0');
            $('#carpenter_commission_amount').val('0');

            $('#carpenterBtnText').text('Carpenter');
            $('#carpenterActiveBadge').addClass('d-none');
            $('#btnCarpenterCommission').removeClass('border-success text-success');

            if (typeof orders !== 'undefined' && orders[activeOrderIndex]) {
                orders[activeOrderIndex].carpenter = {
                    id: '',
                    type: 'percentage',
                    rate: '0',
                    amount: '0',
                    name: 'Carpenter'
                };
            }
        }

        $('#modalCarpenterCommission').modal('hide');
    });

    $('#btnClearCarpenterCommission').on('click', function() {
        $('#modal_carpenter_id').val('');
        $('#modal_carpenter_comm_rate').val('0');
        $('#btnApplyCarpenterCommission').trigger('click');
    });

    // Quick Add Carpenter Modal Handlers
    $('#btnOpenAddCarpenterModal').on('click', function() {
        $('#modalCarpenterCommission').modal('hide');
        $('#formQuickAddCarpenter')[0].reset();
        $('#modalQuickAddCarpenter').modal('show');
    });

    $('#btnCancelQuickCarpenter').on('click', function() {
        $('#modalQuickAddCarpenter').modal('hide');
        $('#modalCarpenterCommission').modal('show');
    });

    $('#btnSaveQuickCarpenter').on('click', function() {
        const name = $('#quick_carp_name').val().trim();
        if (!name) {
            Swal.fire('Required', 'Please enter Carpenter Name.', 'warning');
            return;
        }

        const phone = $('#quick_carp_phone').val().trim();
        const cnic = $('#quick_carp_cnic').val().trim();
        const type = $('#quick_carp_type').val();
        const rate = parseFloat($('#quick_carp_rate').val()) || 0;
        const address = $('#quick_carp_address').val().trim();

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: "{{ route('carpenters.ajax_store') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: name,
                phone: phone,
                cnic: cnic,
                default_commission_type: type,
                default_commission_rate: rate,
                address: address
            },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Carpenter');
                if (res.success && res.carpenter) {
                    const c = res.carpenter;
                    const phoneText = c.phone ? ` (${c.phone})` : '';
                    const newOpt = `<option value="${c.id}" data-type="${c.default_commission_type}" data-rate="${c.default_commission_rate}" data-name="${c.name}" data-phone="${c.phone || ''}" selected>${c.name}${phoneText}</option>`;
                    $('#modal_carpenter_id').append(newOpt);
                    $('#modal_carpenter_id').val(c.id).trigger('change');

                    $('#modalQuickAddCarpenter').modal('hide');
                    $('#modalCarpenterCommission').modal('show');
                    showAlert('success', `Carpenter ${c.name} added successfully!`);
                } else {
                    Swal.fire('Error', res.message || 'Failed to save carpenter.', 'error');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Carpenter');
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save carpenter.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // ==============================================================
    // QUICKBOOKS DESKTOP POS: CHOOSE UNIT OF MEASURE MODAL (ADD SALE)
    // ==============================================================
    let activeUomAddSaleRowUid = null;
    let selectedAddSaleUomChoice = null;

    $(document).on('click', '.btn-open-choose-uom-addsale', function(e) {
        e.stopPropagation();
        const uid = $(this).data('uid');
        const row = gridRows.find(r => r.uid === uid);
        if (!row || !row.uomUnits || row.uomUnits.length === 0) return;

        activeUomAddSaleRowUid = uid;
        selectedAddSaleUomChoice = null;

        $('#qbAddSaleModalProductName').text(row.itemName);
        const $container = $('#qbAddSaleModalUnitsContainer');
        $container.empty();

        row.uomUnits.forEach(u => {
            const isSelected = (row.uomName && row.uomName.toLowerCase() === u.name.toLowerCase());
            if (isSelected) {
                selectedAddSaleUomChoice = u;
            }
            const ratioVal = parseFloat(u.ratio) || 1;
            const ratioText = (ratioVal === 1) ? 'Base Unit' : `${ratioVal} Base Units`;
            const priceText = (parseFloat(u.price) > 0) ? `Rs ${parseFloat(u.price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'Default Price';

            const cardHtml = `
                <button type="button" class="btn btn-outline-secondary text-start p-2.5 qb-addsale-uom-card-btn ${isSelected ? 'active border-primary bg-primary text-white' : ''}" 
                        data-unit-name="${u.name}" 
                        data-ratio="${ratioVal}" 
                        data-price="${u.price || 0}"
                        style="border-radius: 6px; transition: all 0.15s ease;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold fs-6">${u.name}</span>
                            <span class="badge ${isSelected ? 'bg-light text-dark' : 'bg-light text-secondary border'} ms-2" style="font-size: 11px;">
                                ${ratioText}
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold fs-6 ${isSelected ? 'text-white' : 'text-primary'}">${priceText}</span>
                            ${u.barcode ? `<div class="${isSelected ? 'text-white-50' : 'text-muted'}" style="font-size: 10px;">UPC: ${u.barcode}</div>` : ''}
                        </div>
                    </div>
                </button>
            `;
            $container.append(cardHtml);
        });

        $('#qbChooseUomModalAddSale').modal('show');
    });

    $(document).on('click', '.qb-addsale-uom-card-btn', function() {
        $('.qb-addsale-uom-card-btn').removeClass('active border-primary bg-primary text-white');
        $('.qb-addsale-uom-card-btn .badge').removeClass('bg-light text-dark').addClass('bg-light text-secondary border');
        $('.qb-addsale-uom-card-btn .text-end span').removeClass('text-white').addClass('text-primary');

        $(this).addClass('active border-primary bg-primary text-white');
        $(this).find('.badge').removeClass('bg-light text-secondary border').addClass('bg-light text-dark');
        $(this).find('.text-end span').removeClass('text-primary').addClass('text-white');

        selectedAddSaleUomChoice = {
            name: $(this).data('unit-name'),
            ratio: parseFloat($(this).data('ratio')) || 1,
            price: parseFloat($(this).data('price')) || 0
        };
    });

    $('#btnConfirmQbAddSaleUomChoice').on('click', function() {
        if (activeUomAddSaleRowUid && selectedAddSaleUomChoice) {
            const row = gridRows.find(r => r.uid === activeUomAddSaleRowUid);
            if (row) {
                row.uomName = selectedAddSaleUomChoice.name;
                row.uomRatio = selectedAddSaleUomChoice.ratio;
                if (selectedAddSaleUomChoice.price > 0) {
                    row.price = selectedAddSaleUomChoice.price;
                }
                renderGrid();
                syncHiddenInputs();
                recomputeTotals();
            }
        }
        $('#qbChooseUomModalAddSale').modal('hide');
    });

    // Initial render of Odoo POS order tabs
    renderOrderTabs();

    // Auto-open Accept Return modal if ?return_invoice=xxx is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const retInv = urlParams.get('return_invoice');
    if (retInv) {
        setTimeout(() => {
            $('#returnSearchInvoiceNo').val(retInv);
            $('#modalAcceptReturn').modal('show');
            setTimeout(() => {
                $('#btnSearchReturnReceipt').click();
            }, 300);
        }, 500);
    }
});
</script>
@endsection
