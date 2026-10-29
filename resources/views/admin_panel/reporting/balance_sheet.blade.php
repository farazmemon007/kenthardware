@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "BALANCE SHEET STATEMENT" THEME
       Pixel-Perfect High-Density Layout Matching Sales & Accounts
       With Normal QuickBooks Fonts & Alternating Zebra Strips
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

    /* Universal Space Reset */
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

    .odoo-page-wrapper {
        padding: 0 4px !important;
    }

    .font-monospace, .qb-mono {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
    }

    /* 1. Top Window Title Bar */
    .qb-win-header {
        height: 38px !important;
        min-height: 38px !important;
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
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
    }
    .qb-win-title i {
        color: #38bdf8;
        font-size: 15px;
    }
    .qb-win-sub {
        font-size: 11px;
        font-weight: 600;
        color: #93c5fd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .qb-btn-blue {
        background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
        border: 1px solid #15457a !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        padding: 3px 12px !important;
        height: 27px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        cursor: pointer;
        transition: filter 0.1s ease;
        white-space: nowrap;
    }
    .qb-btn-blue:hover { filter: brightness(1.1); color: #ffffff !important; }

    .qb-btn-gray {
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
        border: 1px solid #94a3b8 !important;
        border-radius: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        padding: 3px 12px !important;
        height: 27px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
        cursor: pointer;
        transition: filter 0.1s ease;
        white-space: nowrap;
    }
    .qb-btn-gray:hover { background: #cbd5e1 !important; color: #0f172a !important; }

    /* 2. Sub-Header Ribbon: Financial Totals */
    .qb-sub-ribbon {
        background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
        border: 1px solid #b2c5dc;
        border-radius: 4px;
        padding: 4px 10px;
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
        gap: 8px;
        flex-wrap: wrap;
    }
    .qb-kpi-chip {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1.25;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
    }
    .qb-kpi-chip .chip-label {
        font-weight: 800;
        color: #334155;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .qb-kpi-chip .chip-val {
        font-weight: 800;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 13px;
    }

    /* 3. Filter Bar */
    .qb-filter-bar {
        background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
        border: 1px solid #b9cce2;
        border-radius: 4px;
        padding: 6px 10px;
        margin-bottom: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    .qb-flabel {
        font-size: 10.5px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #1e3a5f !important;
        margin-bottom: 2px !important;
        white-space: nowrap;
    }
    .qb-input {
        height: 30px !important;
        border: 1px solid #8ca4c3 !important;
        border-radius: 3px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #0f2744 !important;
        padding: 2px 8px !important;
        background-color: #ffffff;
        outline: none;
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
        height: 30px !important;
        padding: 0 13px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        cursor: pointer;
    }
    .qb-btn-search:hover { filter: brightness(1.1); color: #ffffff !important; }

    .qb-btn-reset {
        background: linear-gradient(180deg, #ffffff 0%, #e8eff6 100%) !important;
        border: 1px solid #8ca4c3 !important;
        color: #334155 !important;
        font-weight: 600 !important;
        border-radius: 3px !important;
        height: 30px !important;
        padding: 0 11px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        cursor: pointer;
    }
    .qb-btn-reset:hover { background: #e2e8f0 !important; color: #0f172a !important; }

    /* Statement Dual Cards */
    .qb-statement-card {
        background: #ffffff;
        border: 1px solid #94a3b8;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .qb-statement-header {
        height: 36px;
        min-height: 36px;
        background: linear-gradient(180deg, #1e3a5f 0%, #152d4a 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 12px;
        border-bottom: 2px solid #0f2744;
    }
    .qb-statement-header h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.4px;
        color: #ffffff;
    }

    .qb-statement-body {
        padding: 10px 12px;
        flex-grow: 1;
    }

    /* Statement Tables */
    .bs-table {
        width: 100%;
        margin-bottom: 0;
        font-size: 13px;
        border-collapse: collapse;
    }
    .bs-table tr td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2ecf7;
        vertical-align: middle;
    }
    .bs-table tr:nth-child(even) td {
        background-color: #f7fafd;
    }
    .bs-table tr:hover td {
        background-color: #e2ecf7;
    }

    .bs-section-title {
        font-weight: 800;
        font-size: 11.5px;
        text-transform: uppercase;
        color: #0f2744;
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%);
        padding: 5px 10px;
        border-radius: 3px;
        border: 1px solid #b2c5dc;
        margin-top: 8px;
        margin-bottom: 4px;
        letter-spacing: 0.4px;
    }
    .bs-sub-row {
        padding-left: 18px !important;
        color: #1e293b;
        font-weight: 500;
    }
    .bs-amount {
        text-align: right;
        font-weight: 700;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 13px;
    }
    .bs-subtotal-row td {
        font-weight: 800 !important;
        background: #e2ecf7 !important;
        border-top: 1px solid #8ca4c3 !important;
        border-bottom: 1px solid #8ca4c3 !important;
        color: #0f2744 !important;
    }

    .qb-statement-footer {
        padding: 8px 12px;
        background: linear-gradient(180deg, #18385e 0%, #102641 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 800;
        font-size: 13px;
        border-top: 2px solid #0a192b;
    }

    /* Mobile Cards */
    .mob-card {
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .mob-metric-card {
        border-radius: 6px;
        border: 1px solid #b2c5dc;
        background: #ffffff;
        padding: 6px 8px;
        text-align: center;
        height: 100%;
    }
    .mob-metric-label {
        font-size: 10px;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
    }
    .mob-metric-val {
        font-size: 13px;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
    }

    @media print {
        body { background: #ffffff !important; font-size: 11px; }
        .no-print, header, .sidebar, .navbar, footer, .qb-win-header, .qb-filter-bar { display: none !important; }
        .odoo-page-wrapper { padding: 0 !important; background: #fff !important; }
        .qb-statement-card { border: 1px solid #94a3b8 !important; box-shadow: none !important; margin-bottom: 15px !important; }
        .qb-statement-header, .qb-statement-footer { background: #18385e !important; color: #fff !important; -webkit-print-color-adjust: exact; }
    }
</style>

<div class="odoo-page-wrapper">

    {{-- 1. Top Window Title Bar --}}
    <div class="qb-win-header no-print">
        <div class="qb-win-title">
            <i class="fa-solid fa-scale-balanced"></i>
            <span>Balance Sheet Statement</span>
            <span class="qb-win-sub d-none d-sm-inline">| Assets, Liabilities & Equity</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Financial Totals (Desktop) --}}
    <div class="qb-sub-ribbon no-print d-none d-md-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-info"><i class="fa-solid fa-building-columns me-1"></i>Total Assets:</span>
                <span class="chip-val text-primary" id="pillAssets">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fa-solid fa-hand-holding-dollar me-1"></i>Total Liabilities:</span>
                <span class="chip-val text-danger" id="pillLiabilities">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-success"><i class="fa-solid fa-chart-pie me-1"></i>Owner's Equity:</span>
                <span class="chip-val text-success" id="pillEquity">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-dark"><i class="fa-solid fa-check-double me-1"></i>Status:</span>
                <span id="pillStatus"><span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:11px; font-weight:700;"><i class="fas fa-check-circle me-1"></i>Balanced</span></span>
            </div>
        </div>
        <div class="text-muted small fw-bold">
            <i class="fa-solid fa-coins me-1 text-primary"></i> Financial Position (PKR)
        </div>
    </div>

    {{-- Mobile Summary Cards --}}
    <div class="row g-2 mb-1 d-md-none no-print px-1">
        <div class="col-6">
            <div class="mob-metric-card" style="background: #f0f9ff; border-color: #bae6fd;">
                <span class="mob-metric-label text-info">Total Assets</span>
                <div class="mob-metric-val text-primary mt-1" id="mobPillAssets">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card" style="background: #fef2f2; border-color: #fca5a5;">
                <span class="mob-metric-label text-danger">Total Liabilities</span>
                <div class="mob-metric-val text-danger mt-1" id="mobPillLiabilities">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card" style="background: #f0fdf4; border-color: #86efac;">
                <span class="mob-metric-label text-success">Owner's Equity</span>
                <div class="mob-metric-val text-success mt-1" id="mobPillEquity">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card" style="background: #f8fafc; border-color: #cbd5e1;">
                <span class="mob-metric-label text-secondary">Status</span>
                <div class="mob-metric-val mt-1" id="mobPillStatus"><span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:10px;"><i class="fas fa-check-circle me-1"></i>Balanced</span></div>
            </div>
        </div>
    </div>

    {{-- 3. Filter Bar (Desktop) --}}
    <div class="qb-filter-bar no-print d-none d-md-block">
        <form id="bsFormDesk">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">As-of Date:</span>
                        <input type="date" id="bs_date_desk" class="qb-input fw-bold bsDateInput" value="{{ date('Y-m-d') }}" style="width: 140px;">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-search btnGenerateTrigger">
                        <i class="fas fa-arrows-rotate"></i> Generate Statement
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left"></i> Today
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Filter Bar (Mobile) --}}
    <div class="card border-0 shadow-sm mb-2 no-print d-md-none p-2" style="border-radius: 6px; background: #e8f0f8;">
        <form id="bsFormMob">
            <div class="row g-2">
                <div class="col-12">
                    <span class="qb-flabel">As-of Date</span>
                    <input type="date" id="bs_date_mob" class="form-control form-control-sm bsDateInput" value="{{ date('Y-m-d') }}" style="font-size: 11.5px;">
                </div>
                <div class="col-12 d-flex gap-1 mt-1">
                    <button type="button" class="qb-btn-search flex-fill justify-content-center btnGenerateTrigger">
                        <i class="fas fa-arrows-rotate me-1"></i> Generate
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left me-1"></i> Today
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Loader --}}
    <div id="loader" style="display:none; text-align:center; padding: 25px;">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="small fw-bold text-muted mt-2">Computing Financial Balance Sheet…</div>
    </div>

    {{-- 4. Main Statement Container (Dual Cards) --}}
    <div id="bsContent">
        <div class="d-flex align-items-center justify-content-between mb-2 px-1">
            <span class="fw-bold text-secondary small">
                <i class="far fa-calendar-alt text-primary me-1"></i><span id="reportDateBadge">As of {{ date('d M, Y') }}</span>
            </span>
            <span class="badge" style="background:#e2e8f0; color:#0f2744; border:1px solid #cbd5e1; font-weight:700;">
                Currency: PKR (Rs)
            </span>
        </div>

        <div class="row g-2">
            
            {{-- LEFT COLUMN: ASSETS --}}
            <div class="col-12 col-md-6">
                <div class="qb-statement-card">
                    <div class="qb-statement-header" style="border-bottom: 2px solid #0284c7;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-coins me-2" style="color: #38bdf8;"></i>
                            <h6>1. ASSETS</h6>
                        </div>
                        <span class="badge" style="background:rgba(56, 189, 248, 0.2); color:#38bdf8; border:1px solid #38bdf8; font-size:10px;">Resource Holdings</span>
                    </div>

                    <div class="qb-statement-body">
                        
                        {{-- CURRENT ASSETS --}}
                        <div class="bs-section-title d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-wallet text-primary me-1"></i>Current Assets</span>
                            <span class="text-muted" style="font-size: 10px;">Liquid & Inventories</span>
                        </div>

                        <table class="bs-table">
                            <tbody id="cashBankRows"></tbody>
                            <tbody>
                                <tr>
                                    <td class="bs-sub-row"><i class="fas fa-users-line text-info me-2"></i>Accounts Receivable</td>
                                    <td class="bs-amount text-primary" id="lblReceivables">Rs 0.00</td>
                                </tr>
                                <tr>
                                    <td class="bs-sub-row"><i class="fas fa-boxes-stacked text-warning me-2"></i>Inventory on Hand</td>
                                    <td class="bs-amount text-dark" id="lblInventory">Rs 0.00</td>
                                </tr>
                                <tr class="bs-subtotal-row">
                                    <td class="fw-bold">Total Current Assets</td>
                                    <td class="bs-amount text-primary" id="lblCurrentAssetsTotal">Rs 0.00</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- FIXED ASSETS --}}
                        <div class="bs-section-title d-flex justify-content-between align-items-center mt-3">
                            <span><i class="fas fa-building text-secondary me-1"></i>Fixed Assets</span>
                            <span class="text-muted" style="font-size: 10px;">Property & Equipment</span>
                        </div>

                        <table class="bs-table">
                            <tbody>
                                <tr>
                                    <td class="bs-sub-row text-muted italic" style="font-size: 11.5px;">Property, Plant & Equipment</td>
                                    <td class="bs-amount text-muted">Rs 0.00</td>
                                </tr>
                                <tr class="bs-subtotal-row">
                                    <td class="fw-bold">Total Fixed Assets</td>
                                    <td class="bs-amount text-secondary" id="lblFixedAssetsTotal">Rs 0.00</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    {{-- ASSETS GRAND TOTAL BANNER --}}
                    <div class="qb-statement-footer">
                        <span>TOTAL ASSETS</span>
                        <span class="text-info fs-6 qb-mono" id="lblAssetsGrandTotal">Rs 0.00</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: LIABILITIES & EQUITY --}}
            <div class="col-12 col-md-6">
                <div class="qb-statement-card">
                    <div class="qb-statement-header" style="border-bottom: 2px solid #e11d48;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-scale-balanced me-2" style="color: #fb7185;"></i>
                            <h6>2. LIABILITIES & EQUITY</h6>
                        </div>
                        <span class="badge" style="background:rgba(251, 113, 133, 0.2); color:#fb7185; border:1px solid #fb7185; font-size:10px;">Claims & Ownership</span>
                    </div>

                    <div class="qb-statement-body">
                        
                        {{-- CURRENT LIABILITIES --}}
                        <div class="bs-section-title d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-invoice-dollar text-danger me-1"></i>Current Liabilities</span>
                            <span class="text-muted" style="font-size: 10px;">Short-Term Payables</span>
                        </div>

                        <table class="bs-table">
                            <tbody>
                                <tr>
                                    <td class="bs-sub-row"><i class="fas fa-truck-field text-danger me-2"></i>Accounts Payable (Vendors)</td>
                                    <td class="bs-amount text-danger" id="lblPayables">Rs 0.00</td>
                                </tr>
                                <tr class="bs-subtotal-row">
                                    <td class="fw-bold">Total Current Liabilities</td>
                                    <td class="bs-amount text-danger" id="lblLiabTotal">Rs 0.00</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- OWNER'S EQUITY --}}
                        <div class="bs-section-title d-flex justify-content-between align-items-center mt-3">
                            <span><i class="fas fa-chart-pie text-success me-1"></i>Owner's Equity</span>
                            <span class="text-muted" style="font-size: 10px;">Net Capital Base</span>
                        </div>

                        <table class="bs-table">
                            <tbody>
                                <tr>
                                    <td class="bs-sub-row"><i class="fas fa-user-shield text-success me-2"></i>Owner's Capital & Retained Earnings</td>
                                    <td class="bs-amount text-success" id="lblEquity">Rs 0.00</td>
                                </tr>
                                <tr class="bs-subtotal-row">
                                    <td class="fw-bold">Total Owner's Equity</td>
                                    <td class="bs-amount text-success" id="lblEquityTotal">Rs 0.00</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    {{-- LIABILITIES & EQUITY GRAND TOTAL BANNER --}}
                    <div class="qb-statement-footer">
                        <span>TOTAL LIABILITIES & EQUITY</span>
                        <span class="text-success fs-6 qb-mono" id="lblLiabEquityGrandTotal">Rs 0.00</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        
        // Sync Date Inputs between Desktop & Mobile
        $('.bsDateInput').on('change', function() {
            $('.bsDateInput').val($(this).val());
        });

        function fmt(n) {
            let val = parseFloat(n || 0);
            return 'Rs ' + val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function loadData() {
            let date = $(".bsDateInput").val() || '{{ date("Y-m-d") }}';
            
            $("#loader").show();
            $("#bsContent").hide();

            $.get("{{ route('report.balance_sheet.fetch') }}", { date: date }, function(res) {
                $("#loader").hide();
                $("#bsContent").show();
                
                if (res.date) {
                    $("#reportDateBadge").text("As of " + res.date);
                }

                // 1. Assets Calculation & Render
                let cbHtml = '';
                if (res.assets && res.assets.cash_bank && res.assets.cash_bank.length > 0) {
                    res.assets.cash_bank.forEach(a => {
                        let icon = a.name.toLowerCase().includes('bank') ? 'fa-building-columns text-primary' : 'fa-money-bill-1 text-success';
                        cbHtml += `<tr>
                            <td class="bs-sub-row"><i class="fa-solid ${icon} me-2"></i>${a.name}</td>
                            <td class="bs-amount text-dark">${fmt(a.balance)}</td>
                        </tr>`;
                    });
                } else {
                    cbHtml = `<tr><td class="bs-sub-row text-muted italic" colspan="2">No cash/bank accounts found.</td></tr>`;
                }
                $("#cashBankRows").html(cbHtml);

                let receivables = res.assets ? (res.assets.receivables || 0) : 0;
                let inventory = res.assets ? (res.assets.inventory || 0) : 0;
                let currentAssetsTotal = res.assets ? (res.assets.current_total || 0) : 0;
                let fixedAssetsTotal = res.assets ? (res.assets.fixed_total || 0) : 0;
                let assetsTotal = res.assets ? (res.assets.total || 0) : 0;

                $("#lblReceivables").text(fmt(receivables));
                $("#lblInventory").text(fmt(inventory));
                $("#lblCurrentAssetsTotal").text(fmt(currentAssetsTotal));
                $("#lblFixedAssetsTotal").text(fmt(fixedAssetsTotal));
                $("#lblAssetsGrandTotal").text(fmt(assetsTotal));

                // 2. Liabilities & Equity Calculation & Render
                let payables = res.liabilities ? (res.liabilities.payables || 0) : 0;
                let liabTotal = res.liabilities ? (res.liabilities.current_total || 0) : 0;
                let equity = res.liabilities ? (res.liabilities.equity || 0) : 0;
                let liabEquityTotal = res.liabilities ? (res.liabilities.total || 0) : 0;

                $("#lblPayables").text(fmt(payables));
                $("#lblLiabTotal").text(fmt(liabTotal));
                $("#lblEquity").text(fmt(equity));
                $("#lblEquityTotal").text(fmt(equity));
                $("#lblLiabEquityGrandTotal").text(fmt(liabEquityTotal));

                // 3. Update Stat Pills (Desktop & Mobile)
                $("#pillAssets, #mobPillAssets").text(fmt(assetsTotal));
                $("#pillLiabilities, #mobPillLiabilities").text(fmt(liabTotal));
                $("#pillEquity, #mobPillEquity").text(fmt(equity));

                // Equation Status Check (Assets = Liabilities + Equity)
                let diff = Math.abs(assetsTotal - liabEquityTotal);
                if (diff < 0.01) {
                    $("#pillStatus, #mobPillStatus").html('<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:11px; font-weight:700;"><i class="fas fa-check-circle me-1"></i>Balanced</span>');
                } else {
                    $("#pillStatus, #mobPillStatus").html('<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #f87171; font-size:11px; font-weight:700;"><i class="fas fa-exclamation-triangle me-1"></i>Unbalanced</span>');
                }
            });
        }

        $(".btnGenerateTrigger").click(function() {
            loadData();
        });

        $(".btnResetTrigger").click(function() {
            let today = '{{ date("Y-m-d") }}';
            $(".bsDateInput").val(today);
            loadData();
        });

        $(".btnPrintReport").click(function() {
            window.print();
        });

        // Load statement on page load
        loadData();
    });
</script>
@endsection
