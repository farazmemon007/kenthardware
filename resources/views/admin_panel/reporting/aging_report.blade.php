@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "AGING ANALYSIS REPORT" THEME
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

    /* 2. Sub-Header Ribbon: Aging Buckets Summary */
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

    /* Aging bucket column accents */
    .col-total { font-weight: 800 !important; }
    .col-90 { font-weight: 700 !important; color: #b91c1c !important; }

    .qb-table tfoot tr td {
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        font-size: 13px !important;
        border-top: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        padding: 8px 10px !important;
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
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span id="reportTitle">Account Receivable Aging Report</span>
            <span class="qb-win-sub d-none d-sm-inline">| Overdue Analysis</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-gray" id="btnExcelExport" title="Export to CSV / Excel">
                <i class="fa-solid fa-file-excel text-success"></i>
                <span class="d-none d-sm-inline">Excel</span>
            </button>
            <button type="button" class="qb-btn-gray" onclick="window.print()" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Aging Overview --}}
    <div class="qb-sub-ribbon no-print d-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-dark"><i class="fa-solid fa-calendar-day me-1 text-primary"></i>As of Date:</span>
                <span class="chip-val text-primary" id="displayDate">{{ date('d/m/Y') }}</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-secondary"><i class="fa-solid fa-wallet me-1"></i>Total Outstanding:</span>
                <span class="chip-val text-danger" id="pillTotalBalance">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip d-none d-md-inline-flex">
                <span class="chip-label text-success"><i class="fa-solid fa-circle-check me-1"></i>Current:</span>
                <span class="chip-val text-success" id="pillCurrent">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip d-none d-md-inline-flex">
                <span class="chip-label text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>90+ Days:</span>
                <span class="chip-val text-danger" id="pill90Plus">Rs 0.00</span>
            </div>
        </div>
        <div class="text-muted small fw-bold d-none d-md-block">
            <i class="fa-solid fa-chart-line me-1 text-primary"></i> Aging Buckets (15-Day Intervals)
        </div>
    </div>

    {{-- 3. Filter Bar --}}
    <div class="qb-filter-bar no-print">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-1">
                    <span class="qb-flabel">Report Type:</span>
                    <select id="rpt_type" class="qb-input fw-bold" style="width: 230px;">
                        <option value="receivable">Account Receivable (Customer)</option>
                        <option value="payable">Account Payable (Vendor)</option>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="qb-flabel">As of Date:</span>
                    <input type="date" id="as_of_date" class="qb-input fw-bold" value="{{ date('Y-m-d') }}" style="width: 140px;">
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button type="button" class="qb-btn-search" id="btnGenerate">
                    <i class="fa-solid fa-filter"></i> Generate
                </button>
            </div>
        </div>
    </div>

    {{-- Loader --}}
    <div id="loader" style="display:none; text-align:center; padding: 25px;">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="small fw-bold text-muted mt-2">Calculating aging buckets…</div>
    </div>

    {{-- 4. Main Data Table Card --}}
    <div id="reportContent">
        <div class="qb-table-card">
            <div class="sale-table-wrap" id="printArea">
                <table class="qb-table table-bordered align-middle" id="agingTable">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">Customer / Vendor Name</th>
                            <th class="text-center" style="width: 130px;">Mobile</th>
                            <th class="text-end col-total" style="width: 140px; background: #e0eaf6 !important; color: #0a2540 !important;">Total Balance</th>
                            <th class="text-end col-current" id="hdrCurrent" style="width: 120px;">Current</th>
                            <th class="text-end col-15" style="width: 110px;">≤ 15 Days</th>
                            <th class="text-end col-30" style="width: 110px;">≤ 30 Days</th>
                            <th class="text-end col-45" style="width: 110px;">≤ 45 Days</th>
                            <th class="text-end col-60" style="width: 110px;">≤ 60 Days</th>
                            <th class="text-end col-75" style="width: 110px;">≤ 75 Days</th>
                            <th class="text-end col-90" style="width: 120px; background: #fde8e8 !important; color: #991b1b !important;">90 & Above</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRows">
                        <tr><td colspan="10" class="text-center text-muted py-4">Click Generate to load report.</td></tr>
                    </tbody>
                    <tfoot id="tfootTotals"></tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    function fmt(v) {
        let n = parseFloat(v || 0);
        if (n === 0) return '<span class="text-muted">—</span>';
        return n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    function fmtTotal(v) {
        let n = parseFloat(v || 0);
        return n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function loadReport() {
        let type    = $('#rpt_type').val();
        let asOf    = $('#as_of_date').val();

        // Update title
        if (type === 'payable') {
            $('#reportTitle').text('Account Payable Aging Report');
        } else {
            $('#reportTitle').text('Account Receivable Aging Report');
        }

        $('#loader').show();
        $('#reportContent').hide();

        $.get("{{ route('report.aging.fetch') }}", {type: type, as_of_date: asOf}, function(res) {
            $('#loader').hide();
            $('#displayDate').text(res.as_of_date);
            $('#hdrCurrent').text(res.as_of_date);

            let html = '';
            if (!res.rows || res.rows.length === 0) {
                html = `<tr><td colspan="10" class="text-center text-muted py-4">No outstanding balances found.</td></tr>`;
                $('#pillTotalBalance').text('Rs 0.00');
                $('#pillCurrent').text('Rs 0.00');
                $('#pill90Plus').text('Rs 0.00');
            } else {
                res.rows.forEach(r => {
                    html += `<tr>
                        <td class="fw-bold text-dark">${r.name}</td>
                        <td class="text-center text-muted fw-semibold" style="font-size: 12px;">${r.mobile || '—'}</td>
                        <td class="text-end col-total qb-mono text-primary">${fmtTotal(r.total)}</td>
                        <td class="text-end col-current qb-mono text-success fw-bold">${fmt(r.current)}</td>
                        <td class="text-end col-15 qb-mono">${fmt(r['15d'])}</td>
                        <td class="text-end col-30 qb-mono">${fmt(r['30d'])}</td>
                        <td class="text-end col-45 qb-mono">${fmt(r['45d'])}</td>
                        <td class="text-end col-60 qb-mono">${fmt(r['60d'])}</td>
                        <td class="text-end col-75 qb-mono">${fmt(r['75d'])}</td>
                        <td class="text-end col-90 qb-mono fw-bold text-danger">${fmt(r['90plus'])}</td>
                    </tr>`;
                });
            }
            $('#tbodyRows').html(html);

            // Totals footer & Ribbon
            if (res.totals) {
                let t = res.totals;
                $('#tfootTotals').html(`
                    <tr>
                        <td colspan="2" class="text-end text-dark fw-bold">TOTALS:</td>
                        <td class="text-end col-total qb-mono text-primary fw-bold">${fmtTotal(t.total)}</td>
                        <td class="text-end col-current qb-mono text-success fw-bold">${fmtTotal(t.current)}</td>
                        <td class="text-end col-15 qb-mono fw-bold">${fmtTotal(t['15d'])}</td>
                        <td class="text-end col-30 qb-mono fw-bold">${fmtTotal(t['30d'])}</td>
                        <td class="text-end col-45 qb-mono fw-bold">${fmtTotal(t['45d'])}</td>
                        <td class="text-end col-60 qb-mono fw-bold">${fmtTotal(t['60d'])}</td>
                        <td class="text-end col-75 qb-mono fw-bold">${fmtTotal(t['75d'])}</td>
                        <td class="text-end col-90 qb-mono text-danger fw-bold">${fmtTotal(t['90plus'])}</td>
                    </tr>
                `);

                $('#pillTotalBalance').text('Rs ' + fmtTotal(t.total));
                $('#pillCurrent').text('Rs ' + fmtTotal(t.current));
                $('#pill90Plus').text('Rs ' + fmtTotal(t['90plus']));
            }

            $('#reportContent').show();
        });
    }

    // Export to Excel function
    function exportToExcel() {
        let table = document.getElementById("agingTable");
        let rows = Array.from(table.rows);
        let csvContent = "data:text/csv;charset=utf-8,";

        rows.forEach(function(row) {
            let rowData = [];
            Array.from(row.cells).forEach(function(cell) {
                if (window.getComputedStyle(cell).display !== 'none') {
                    let text = cell.innerText.replace(/,/g, '').replace(/\n/g, ' ').replace(/—/g, '0.00');
                    rowData.push('"' + text + '"');
                }
            });
            csvContent += rowData.join(",") + "\r\n";
        });

        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");
        let filename = "aging_analysis_report_" + new Date().toISOString().slice(0,10) + ".csv";
        
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    $('#btnGenerate').click(loadReport);
    $('#btnExcelExport').click(exportToExcel);
    loadReport(); // auto-load
});
</script>
@endsection
