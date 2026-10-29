@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "PARTIES BALANCE REPORT" THEME
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
    .qb-input, select.qb-input {
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

    /* 4. Table Card & High-Density QuickBooks Table */
    .qb-table-card {
        background: #ffffff;
        border: 1px solid #94a3b8;
        border-radius: 4px;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .sale-table-wrap {
        border: none !important;
        overflow-x: auto;
        max-height: calc(100vh - 225px);
        min-height: 380px;
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 13px !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }
    .qb-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 11.5px !important;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-top: none !important;
        padding: 7px 10px !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }
    .qb-table thead th:last-child {
        border-right: none !important;
    }
    .qb-table tbody td {
        border-bottom: 1px solid #c8d7e6 !important;
        border-right: 1px solid #cbd8e7 !important;
        border-top: none !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        color: #0f2744 !important;
        vertical-align: middle;
    }
    .qb-table tbody td:last-child {
        border-right: none !important;
    }

    /* QuickBooks Distinct Alternating Darker Stripes */
    .qb-table tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    .qb-table tbody tr:nth-child(even) td {
        background-color: #e2ecf7 !important; /* Distinct Darker Cool Blue Stripe */
    }
    .qb-table tbody tr:hover td {
        background-color: #bedaff !important; /* Deep Sky-Blue Hover Highlight */
        color: #0f172a !important;
    }
    .qb-table tbody tr:hover td:first-child {
        box-shadow: inset 5px 0 0 #1d4ed8 !important;
    }
    .qb-table tbody tr:hover td .acc-badge-code {
        background-color: #d5e2f1 !important;
        border-color: #7b9bbd !important;
    }

    .acc-badge-code {
        font-family: 'JetBrains Mono', Consolas, monospace !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #0f2744 !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 3px !important;
        padding: 2px 7px !important;
        display: inline-block;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .qb-table tfoot tr td {
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        font-size: 13px !important;
        border-top: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        padding: 8px 10px !important;
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
        .qb-table-card { border: none !important; box-shadow: none !important; }
        .sale-table-wrap { max-height: none !important; overflow: visible !important; }
        .qb-table thead th { background: #e2eaf3 !important; color: #000 !important; }
        .qb-table td, .qb-table th { border: 1px solid #94a3b8 !important; }
    }
</style>

<div class="odoo-page-wrapper">

    {{-- 1. Top Window Title Bar --}}
    <div class="qb-win-header no-print">
        <div class="qb-win-title">
            <i class="fa-solid fa-users-viewfinder"></i>
            <span>Parties Balance Report</span>
            <span class="qb-win-sub d-none d-sm-inline">| Accounts Receivable & Payable</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-gray btnExcelTrigger" title="Export to CSV / Excel">
                <i class="fa-solid fa-file-excel text-success"></i>
                <span class="d-none d-sm-inline">Excel</span>
            </button>
            <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Financial KPIs (Desktop) --}}
    <div class="qb-sub-ribbon no-print d-none d-md-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-info"><i class="fa-solid fa-arrow-down me-1"></i>Total Receivables:</span>
                <span class="chip-val text-primary" id="pillReceivable">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fa-solid fa-arrow-up me-1"></i>Total Payables:</span>
                <span class="chip-val text-danger" id="pillPayable">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-dark"><i class="fa-solid fa-scale-balanced me-1"></i>Net Position:</span>
                <span class="chip-val text-dark" id="pillNet">Rs 0.00</span>
            </div>
        </div>
        <div class="text-muted small fw-bold">
            <i class="fa-solid fa-layer-group me-1 text-primary"></i> Live Balances
        </div>
    </div>

    {{-- Mobile Summary Cards --}}
    <div class="row g-2 mb-1 d-md-none no-print px-1">
        <div class="col-4">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-primary">Rec</span>
                <div class="mob-metric-val text-primary mt-1" id="mobPillReceivable">Rs 0.00</div>
            </div>
        </div>
        <div class="col-4">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-danger">Pay</span>
                <div class="mob-metric-val text-danger mt-1" id="mobPillPayable">Rs 0.00</div>
            </div>
        </div>
        <div class="col-4">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-dark">Net</span>
                <div class="mob-metric-val text-dark mt-1" id="mobPillNet">Rs 0.00</div>
            </div>
        </div>
    </div>

    {{-- 3. Filter Bar (Desktop) --}}
    <div class="qb-filter-bar no-print d-none d-md-block">
        <form id="partiesFormDesk">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">Type:</span>
                        <select id="report_type_desk" class="qb-input fw-bold reportTypeInput" style="width: 140px;">
                            <option value="BOTH">BOTH (All)</option>
                            <option value="RECEIVABLE">RECEIVABLE</option>
                            <option value="PAYABLE">PAYABLE</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">Party:</span>
                        <input type="text" id="party_name_desk" class="qb-input fw-bold partyNameInput" placeholder="Name search..." style="width: 160px;">
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <span class="qb-flabel">Mobile:</span>
                        <input type="text" id="mobile_desk" class="qb-input fw-bold mobileInput" placeholder="Mobile..." style="width: 130px;">
                    </div>

                    <div class="form-check form-check-inline ms-1 mb-0 d-flex align-items-center gap-1">
                        <input class="form-check-input showZeroInput" type="checkbox" id="show_zero_desk">
                        <label class="form-check-label fw-bold text-secondary" for="show_zero_desk" style="font-size: 11.5px; cursor: pointer;">Show Zero</label>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-search btnSearchTrigger">
                        <i class="fa-solid fa-filter"></i> Search
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Filter Bar (Mobile) --}}
    <div class="card border-0 shadow-sm mb-2 no-print d-md-none p-2" style="border-radius: 6px; background: #e8f0f8;">
        <form id="partiesFormMob">
            <div class="row g-2">
                <div class="col-7">
                    <span class="qb-flabel">Report Type</span>
                    <select id="report_type_mob" class="form-select form-select-sm reportTypeInput fw-bold" style="font-size: 11.5px;">
                        <option value="BOTH">BOTH</option>
                        <option value="RECEIVABLE">RECEIVABLE</option>
                        <option value="PAYABLE">PAYABLE</option>
                    </select>
                </div>
                <div class="col-5 d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input class="form-check-input showZeroInput" type="checkbox" id="show_zero_mob">
                        <label class="form-check-label fw-bold text-secondary" for="show_zero_mob" style="font-size: 11px;">Show Zero</label>
                    </div>
                </div>
                <div class="col-6">
                    <span class="qb-flabel">Party</span>
                    <input type="text" id="party_name_mob" class="form-control form-control-sm partyNameInput" placeholder="Name..." style="font-size: 11.5px;">
                </div>
                <div class="col-6">
                    <span class="qb-flabel">Mobile</span>
                    <input type="text" id="mobile_mob" class="form-control form-control-sm mobileInput" placeholder="Mobile..." style="font-size: 11.5px;">
                </div>
                <div class="col-12 d-flex gap-1 mt-1">
                    <button type="button" class="qb-btn-search flex-fill justify-content-center btnSearchTrigger">
                        <i class="fa-solid fa-filter me-1"></i> Search
                    </button>
                    <button type="button" class="qb-btn-reset btnResetTrigger">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Loader --}}
    <div id="loader" style="display:none; text-align:center; padding: 25px;">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="small fw-bold text-muted mt-2">Loading Parties Balance Data…</div>
    </div>

    {{-- 4. Main Data Table Card --}}
    <div id="reportBox">
        <div class="qb-table-card d-none d-md-block">
            <div class="sale-table-wrap">
                <table class="qb-table table-bordered align-middle" id="partiesTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">#</th>
                            <th style="width: 100px;">CODE</th>
                            <th>PARTY / TITLE</th>
                            <th class="text-center" style="width: 110px;">TYPE</th>
                            <th style="width: 140px;">MOBILE</th>
                            <th class="text-end col-receivable" style="width: 160px;">RECEIVABLE</th>
                            <th class="text-end col-payable" style="width: 160px;">PAYABLE</th>
                            <th style="width: 150px;">NOTES</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRows"></tbody>
                    <tfoot id="tfootTotals"></tfoot>
                </table>
            </div>
        </div>

        {{-- Mobile Cards Container --}}
        <div class="d-md-none" id="mobPartiesContainer"></div>
    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        
        // Sync Inputs between Desktop & Mobile
        $('.reportTypeInput').on('change', function() { $('.reportTypeInput').val($(this).val()); });
        $('.partyNameInput').on('keyup change', function() { $('.partyNameInput').val($(this).val()); });
        $('.mobileInput').on('keyup change', function() { $('.mobileInput').val($(this).val()); });
        $('.showZeroInput').on('change', function() { $('.showZeroInput').prop('checked', $(this).is(':checked')); });

        function fmt(amount) {
            let val = parseFloat(amount || 0);
            return 'Rs ' + val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function loadReport() {
            let reportType = $(".reportTypeInput").val() || 'BOTH';
            let showZero = $(".showZeroInput").is(":checked");
            let partyName = $(".partyNameInput").val();
            let mobile = $(".mobileInput").val();
            
            $("#loader").show();
            $("#reportBox").hide();

            $.get("{{ route('report.parties_balance.fetch') }}", {
                report_type: reportType,
                show_zero: showZero,
                party_name: partyName,
                mobile: mobile
            }, function(res) {
                $("#loader").hide();
                $("#reportBox").show();

                // Column visibility logic for Desktop
                if (reportType === 'RECEIVABLE') {
                    $(".col-payable").hide();
                    $(".col-receivable").show();
                } else if (reportType === 'PAYABLE') {
                    $(".col-receivable").hide();
                    $(".col-payable").show();
                } else {
                    $(".col-receivable").show();
                    $(".col-payable").show();
                }

                let rowsHtml = "";
                let mobHtml = "";

                if (res.rows && res.rows.length > 0) {
                    res.rows.forEach(function(row) {
                        let isVendor = row.code && row.code.startsWith('V');
                        let typeBadge = isVendor 
                            ? `<span class="badge" style="background:#fee2e2; color:#991b1b; border:1px solid #f87171; font-size:11px; font-weight:700;">Vendor</span>`
                            : `<span class="badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; font-size:11px; font-weight:700;">Customer</span>`;

                        rowsHtml += `
                            <tr>
                                <td class="text-center text-muted fw-bold">${row.sr}</td>
                                <td><span class="acc-badge-code">${row.code}</span></td>
                                <td class="fw-bold text-dark">${row.title}</td>
                                <td class="text-center">${typeBadge}</td>
                                <td class="text-muted fw-semibold" style="font-size: 12px;">${row.mobile || '-'}</td>
                                ${reportType !== 'PAYABLE' ? `<td class="text-end text-primary fw-bold qb-mono">${row.receivable != 0 ? fmt(row.receivable) : '-'}</td>` : ''}
                                ${reportType !== 'RECEIVABLE' ? `<td class="text-end text-danger fw-bold qb-mono">${row.payable != 0 ? fmt(row.payable) : '-'}</td>` : ''}
                                <td class="text-muted small">${row.notes || '-'}</td>
                            </tr>
                        `;

                        mobHtml += `
                            <div class="mob-card p-2 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="acc-badge-code" style="font-size: 11px;">${row.code}</span>
                                        <strong class="text-dark" style="font-size: 13px;">${row.title}</strong>
                                    </div>
                                    ${typeBadge}
                                </div>
                                <div class="border-top pt-2 mt-1">
                                    <div class="row g-1 align-items-center" style="font-size: 11.5px;">
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 11px;"><i class="fas fa-phone me-1"></i>${row.mobile || 'No Mobile'}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            ${row.receivable > 0 ? `<strong class="text-primary qb-mono" style="font-size: 12.5px;">Rec: ${fmt(row.receivable)}</strong>` : ''}
                                            ${row.payable > 0 ? `<strong class="text-danger qb-mono" style="font-size: 12.5px;">Pay: ${fmt(row.payable)}</strong>` : ''}
                                            ${row.receivable == 0 && row.payable == 0 ? `<span class="text-muted fw-bold">Rs 0.00</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $("#tbodyRows").html(rowsHtml);
                    $("#mobPartiesContainer").html(mobHtml);

                    // Totals
                    if (res.totals) {
                        let t = res.totals;
                        let totalsHtml = `
                            <tr>
                                <td colspan="5" class="text-end text-dark fw-bold">TOTAL:</td>
                                ${reportType !== 'PAYABLE' ? `<td class="text-end text-primary fw-bold qb-mono">${fmt(t.receivable)}</td>` : ''}
                                ${reportType !== 'RECEIVABLE' ? `<td class="text-end text-danger fw-bold qb-mono">${fmt(t.payable)}</td>` : ''}
                                <td></td>
                            </tr>
                        `;
                        $("#tfootTotals").html(totalsHtml);

                        // Update Stat Pills
                        let net = t.receivable - t.payable;
                        $('#pillReceivable, #mobPillReceivable').text(fmt(t.receivable));
                        $('#pillPayable, #mobPillPayable').text(fmt(t.payable));
                        
                        $('#pillNet, #mobPillNet').text(fmt(net));
                        if(net > 0) {
                            $('#pillNet, #mobPillNet').removeClass('text-danger text-dark').addClass('text-primary');
                        } else if(net < 0) {
                            $('#pillNet, #mobPillNet').removeClass('text-primary text-dark').addClass('text-danger');
                        } else {
                            $('#pillNet, #mobPillNet').removeClass('text-primary text-danger').addClass('text-dark');
                        }
                    }
                } else {
                    let colCount = reportType === 'BOTH' ? 8 : 7;
                    $("#tbodyRows").html(`<tr><td colspan="${colCount}" class="text-center text-muted py-4">No data matching filters.</td></tr>`);
                    $("#mobPartiesContainer").html('<div class="card border-0 shadow-sm rounded-3 text-center py-4 bg-white"><div class="card-body py-4 text-muted"><i class="fas fa-folder-open fa-2x mb-2 text-secondary"></i><p class="small fw-bold mb-0">No Parties Balance Found</p></div></div>');
                    $("#tfootTotals").empty();

                    $('#pillReceivable, #mobPillReceivable').text('Rs 0.00');
                    $('#pillPayable, #mobPillPayable').text('Rs 0.00');
                    $('#pillNet, #mobPillNet').text('Rs 0.00');
                }
            });
        }

        // Export to Excel function
        function exportToExcel() {
            let table = document.getElementById("partiesTable");
            let rows = Array.from(table.rows);
            let csvContent = "data:text/csv;charset=utf-8,";

            rows.forEach(function(row) {
                let rowData = [];
                Array.from(row.cells).forEach(function(cell) {
                    if (window.getComputedStyle(cell).display !== 'none') {
                        let text = cell.innerText.replace(/,/g, '').replace(/\n/g, ' ');
                        rowData.push('"' + text + '"');
                    }
                });
                csvContent += rowData.join(",") + "\r\n";
            });

            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            let filename = "parties_balance_report_" + new Date().toISOString().slice(0,10) + ".csv";
            
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        $(".btnSearchTrigger").click(function() {
            loadReport();
        });

        $(".btnResetTrigger").click(function() {
            $(".reportTypeInput").val('BOTH');
            $(".partyNameInput").val('');
            $(".mobileInput").val('');
            $(".showZeroInput").prop('checked', false);
            loadReport();
        });

        $(".btnPrintReport").click(function() {
            window.print();
        });

        $(".btnExcelTrigger").click(function() {
            exportToExcel();
        });

        // Load report on initial page load
        loadReport();
    });
</script>
@endsection
