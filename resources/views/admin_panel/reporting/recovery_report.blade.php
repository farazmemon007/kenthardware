@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "CUSTOMER RECOVERY REPORT" THEME
       Pixel-Perfect High-Density Layout Matching Sales & Accounts
       With Normal QuickBooks Fonts & Alternating Zebra Strips
       ========================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');

    /* Universal Space Reset to Eliminate Scrolling & Extra Spaces */
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

    /* 1. Top Window Title Bar (Navy Blue Gradient) */
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

    /* Action Buttons */
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
    .qb-btn-blue:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

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
    .qb-btn-gray:hover {
        background: #cbd5e1 !important;
        color: #0f172a !important;
    }

    /* 2. Sub-Header Ribbon: Financial KPIs */
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

    /* 3. Filter Bar (Single-Line Compact Strip) */
    .qb-filter-bar {
        background: linear-gradient(180deg, #f7fafd 0%, #e8f0f8 100%) !important;
        border: 1px solid #b9cce2;
        border-radius: 4px;
        padding: 5px 8px;
        margin-bottom: 4px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    .qb-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
    }
    .qb-filter-col {
        display: flex;
        flex-direction: column;
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
    .qb-btn-search:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
    }

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
    .qb-btn-reset:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
    }

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
        max-height: calc(100vh - 215px);
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

    /* QuickBooks Distinct Alternating Stripes */
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

    #tfootTotals tr td {
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
    }

    @media print {
        body { background: #ffffff !important; font-size: 11px; }
        .no-print, header, .sidebar, .navbar, footer, .qb-filter-bar, .qb-win-header { display: none !important; }
        .odoo-page-wrapper { padding: 0 !important; background: #fff !important; }
        .qb-table-card { border: none !important; box-shadow: none !important; }
        .sale-table-wrap { max-height: none !important; overflow: visible !important; }
        .qb-table th, .qb-table td { border: 1px solid #000 !important; }
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- 1. Top Window Title Bar --}}
            <div class="qb-win-header no-print">
                <div class="qb-win-title">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Customer Recovery Report</span>
                    <span class="qb-win-sub d-none d-md-inline">/ Cash Inflow &amp; Receivables Audit</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            {{-- 2. Sub-Header Ribbon: Financial KPIs --}}
            <div class="qb-sub-ribbon no-print">
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip">
                        <i class="fas fa-wallet text-muted"></i>
                        <span class="chip-label text-muted">Total Opening:</span>
                        <span class="chip-val font-monospace text-dark" id="pillOpening">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <span class="chip-label text-primary">Total Sales:</span>
                        <span class="chip-val font-monospace text-primary" id="pillSales">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-money-bill-wave text-success"></i>
                        <span class="chip-label text-success">Cash Received:</span>
                        <span class="chip-val font-monospace text-success" id="pillReceived">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-balance-scale text-danger"></i>
                        <span class="chip-label text-danger">Total Final Balance:</span>
                        <span class="chip-val font-monospace text-danger" id="pillFinal">Rs 0.00</span>
                    </div>
                </div>
            </div>

            {{-- 3. Filter Bar (Single-Line Compact Strip) --}}
            <div class="qb-filter-bar no-print">
                <form id="recoveryFormDesk" class="qb-filter-form" onsubmit="return false;">
                    
                    {{-- Start Date --}}
                    <div class="qb-filter-col" style="min-width: 140px;">
                        <label class="qb-flabel">Start Date</label>
                        <input type="date" name="start_date" id="start_date_desk" value="{{ date('Y-m-01') }}" class="qb-input startDateInput">
                    </div>

                    {{-- End Date --}}
                    <div class="qb-filter-col" style="min-width: 140px;">
                        <label class="qb-flabel">End Date</label>
                        <input type="date" name="end_date" id="end_date_desk" value="{{ date('Y-m-d') }}" class="qb-input endDateInput">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="qb-btn-search btnSearchTrigger" title="Generate Recovery Report">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" class="qb-btn-reset btnResetTrigger" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="qb-btn-gray btnPrintReport" title="Print Statement">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </form>
            </div>

            {{-- Loader --}}
            <div id="loader" style="display:none; text-align:center; padding: 18px; background:#f8fafc;" class="qb-table-card mb-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="ms-2 text-dark font-monospace fw-bold" style="font-size:12px;">Fetching Recovery Data…</span>
            </div>

            <div id="reportBox">
                {{-- 4. Table Card (Desktop) --}}
                <div class="qb-table-card d-none d-md-block">
                    <div class="sale-table-wrap">
                        <table class="qb-table nowrap" id="recoveryTable" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>PARTY / CUSTOMER NAME</th>
                                    <th class="text-end" style="width: 150px;">OPENING</th>
                                    <th class="text-end" style="width: 150px;">SALES</th>
                                    <th class="text-end" style="width: 160px;">CASH RECEIVED</th>
                                    <th class="text-end" style="width: 170px;">FINAL BALANCE</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRows"></tbody>
                            <tfoot id="tfootTotals"></tfoot>
                        </table>
                    </div>
                </div>

                {{-- Mobile Cards Container (d-md-none) --}}
                <div class="d-md-none no-print" id="mobRecoveryContainer"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        
        // Sync Inputs between Desktop & Mobile
        $('.startDateInput').on('change', function() { $('.startDateInput').val($(this).val()); });
        $('.endDateInput').on('change', function() { $('.endDateInput').val($(this).val()); });

        function fmt(amount) {
            let val = parseFloat(amount || 0);
            return 'Rs ' + val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function loadReport() {
            let start = $(".startDateInput").val() || '2000-01-01';
            let end = $(".endDateInput").val() || '{{ date("Y-m-d") }}';
            
            $("#loader").show();
            $("#reportBox").hide();

            $.get("{{ route('report.recovery.fetch') }}", { start_date: start, end_date: end }, function(res) {
                $("#loader").hide();
                $("#reportBox").show();

                let rowsHtml = "";
                let mobHtml = "";

                if (res.rows && res.rows.length > 0) {
                    res.rows.forEach(function(row) {
                        rowsHtml += `
                            <tr>
                                <td class="text-center text-muted font-monospace fw-semibold" style="font-size:12px;">${row.sr}</td>
                                <td class="fw-bold text-dark text-nowrap" style="font-size:13.5px;">${row.party}</td>
                                <td class="text-end font-monospace text-muted" style="font-size:13px;">${row.opening != 0 ? fmt(row.opening) : '—'}</td>
                                <td class="text-end font-monospace text-primary fw-bold" style="font-size:13px;">${row.sales != 0 ? fmt(row.sales) : '—'}</td>
                                <td class="text-end font-monospace text-success fw-bold" style="font-size:13px;">${row.received != 0 ? fmt(row.received) : '—'}</td>
                                <td class="text-end font-monospace fw-bold ${row.final >= 0 ? 'text-dark' : 'text-danger'}" style="font-size:13.5px;">${fmt(row.final)}</td>
                            </tr>
                        `;

                        mobHtml += `
                            <div class="mob-card p-2 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark" style="font-size: 13px;">${row.party}</strong>
                                    <strong class="font-monospace fw-bold ${row.final >= 0 ? 'text-dark' : 'text-danger'}" style="font-size: 12.5px;">Final: ${fmt(row.final)}</strong>
                                </div>
                                <div class="border-top pt-2 mt-1">
                                    <div class="row g-1 text-center font-monospace" style="font-size: 11.5px;">
                                        <div class="col-4 border-end">
                                            <span class="text-muted d-block" style="font-size: 10px;">Opening</span>
                                            <strong class="text-dark">${row.opening != 0 ? fmt(row.opening) : '—'}</strong>
                                        </div>
                                        <div class="col-4 border-end">
                                            <span class="text-muted d-block" style="font-size: 10px;">Sales</span>
                                            <strong class="text-primary">${row.sales != 0 ? fmt(row.sales) : '—'}</strong>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-muted d-block" style="font-size: 10px;">Received</span>
                                            <strong class="text-success">${row.received != 0 ? fmt(row.received) : '—'}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $("#tbodyRows").html(rowsHtml);
                    $("#mobRecoveryContainer").html(mobHtml);

                    // Totals
                    if (res.totals) {
                        let t = res.totals;
                        let totalsHtml = `
                            <tr>
                                <td colspan="2" class="text-end text-dark" style="letter-spacing:0.3px;">TOTALS:</td>
                                <td class="text-end font-monospace text-dark" style="font-size:13px;">${fmt(t.opening)}</td>
                                <td class="text-end font-monospace text-primary" style="font-size:13px;">${fmt(t.sales)}</td>
                                <td class="text-end font-monospace text-success" style="font-size:13px;">${fmt(t.received)}</td>
                                <td class="text-end font-monospace text-dark" style="font-size:13.5px;">${fmt(t.final)}</td>
                            </tr>
                        `;
                        $("#tfootTotals").html(totalsHtml);

                        // Update Stat Pills
                        $('#pillOpening, #mobPillOpening').text(fmt(t.opening));
                        $('#pillSales, #mobPillSales').text(fmt(t.sales));
                        $('#pillReceived, #mobPillReceived').text(fmt(t.received));
                        $('#pillFinal, #mobPillFinal').text(fmt(t.final));
                    }
                } else {
                    $("#tbodyRows").html(`<tr><td colspan="6" class="text-center text-muted py-5 fw-bold" style="font-size:13px;"><i class="fas fa-folder-open fa-2x d-block mb-2 text-secondary"></i>No recovery records found in this period.</td></tr>`);
                    $("#mobRecoveryContainer").html('<div class="qb-table-card text-center py-4 text-muted"><p class="small fw-bold mb-0">No Recovery Data Found</p></div>');
                    $("#tfootTotals").empty();

                    $('#pillOpening, #mobPillOpening').text('Rs 0.00');
                    $('#pillSales, #mobPillSales').text('Rs 0.00');
                    $('#pillReceived, #mobPillReceived').text('Rs 0.00');
                    $('#pillFinal, #mobPillFinal').text('Rs 0.00');
                }
            });
        }

        $(".btnSearchTrigger").click(function() {
            loadReport();
        });

        $(".btnResetTrigger").click(function() {
            $(".startDateInput").val('{{ date("Y-m-01") }}');
            $(".endDateInput").val('{{ date("Y-m-d") }}');
            loadReport();
        });

        $(".btnPrintReport").click(function() {
            window.print();
        });

        // Load initially
        loadReport();
    });
</script>
@endsection
