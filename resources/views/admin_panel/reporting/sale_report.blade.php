@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "SALE REPORT" THEME
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
        padding: 3px 6px;
        margin-bottom: 4px !important;
        display: flex;
        align-items: center;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        scrollbar-width: thin;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }
    .qb-kpi-group {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap !important;
        width: 100%;
        justify-content: space-between;
    }
    .qb-kpi-chip {
        background: #ffffff;
        border: 1px solid #8ca4c3;
        border-radius: 3px;
        padding: 2.5px 6px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        line-height: 1.2;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
        white-space: nowrap;
        flex: 1 1 auto;
        justify-content: center;
    }
    .qb-kpi-chip i {
        font-size: 11px;
    }
    .qb-kpi-chip .chip-label {
        font-weight: 800;
        color: #334155;
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
    .qb-kpi-chip .chip-val {
        font-weight: 800;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 11.5px;
        white-space: nowrap;
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

    #grandTotalRow td {
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
                    <i class="fas fa-chart-line"></i>
                    <span>Sale Report</span>
                    <span class="qb-win-sub d-none d-md-inline">/ Invoices &amp; Financial Sales Audit</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-blue btnExportCsvTrigger" title="Export to CSV">
                        <i class="fas fa-file-excel"></i> Export CSV
                    </button>
                    <button type="button" class="qb-btn-gray" onclick="window.print()" title="Print Report">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            {{-- 2. Sub-Header Ribbon: Financial KPIs --}}
            <div class="qb-sub-ribbon no-print">
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip">
                        <i class="fas fa-file-invoice text-muted"></i>
                        <span class="chip-label">Invoices:</span>
                        <span class="chip-val font-monospace text-dark" id="pillTotalInvoices">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-box text-info"></i>
                        <span class="chip-label text-info">Total Qty:</span>
                        <span class="chip-val font-monospace text-info" id="pillTotalQty">0 Pcs</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-wallet text-secondary"></i>
                        <span class="chip-label text-secondary">Gross Sale:</span>
                        <span class="chip-val font-monospace text-secondary" id="pillGrossSale">Rs 0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-undo text-danger"></i>
                        <span class="chip-label text-danger">Returns:</span>
                        <span class="chip-val font-monospace text-danger" id="pillTotalReturns">Rs 0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-check-circle text-success"></i>
                        <span class="chip-label text-success">Net Sale:</span>
                        <span class="chip-val font-monospace text-success" id="pillNetSale">Rs 0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-chart-pie text-primary"></i>
                        <span class="chip-label text-primary">Gross Profit:</span>
                        <span class="chip-val font-monospace text-primary" id="pillGrossProfit">Rs 0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-minus-circle text-warning"></i>
                        <span class="chip-label text-warning">Expense:</span>
                        <span class="chip-val font-monospace text-warning" id="pillExpenses">Rs 0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-coins text-success"></i>
                        <span class="chip-label text-success">Current Profit:</span>
                        <span class="chip-val font-monospace text-success" id="pillCurrentProfit">Rs 0</span>
                    </div>
                </div>
            </div>

            {{-- 3. Filter Bar (Single-Line Compact Strip) --}}
            <div class="qb-filter-bar no-print">
                <form id="SaleFilterFormDesk" class="qb-filter-form" onsubmit="return false;">
                    
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

                    {{-- Customer Filter --}}
                    <div class="qb-filter-col" style="min-width: 180px; flex: 1;">
                        <label class="qb-flabel">Customer</label>
                        <select id="customer_id_desk" class="qb-input customerInput">
                            <option value="all">-- All Customers --</option>
                            @if(isset($customers) && count($customers) > 0)
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div class="qb-filter-col" style="min-width: 220px; flex: 1.5;">
                        <label class="qb-flabel">Search Item / Invoice / Customer</label>
                        <div class="position-relative">
                            <input type="text" class="qb-input searchProductInput w-100" placeholder="Filter by text…" style="padding-left: 26px;">
                            <i class="fas fa-search position-absolute text-muted" style="left: 8px; top: 50%; transform: translateY(-50%); font-size: 11px; pointer-events: none;"></i>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="qb-btn-search btnSearchTrigger" title="Apply Date & Customer Filter">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" class="qb-btn-blue btnExportCsvTrigger" title="Download Excel CSV">
                            <i class="fas fa-file-excel"></i> CSV
                        </button>
                    </div>
                </form>
            </div>

            {{-- 4. Table Card (Desktop) --}}
            <div class="qb-table-card d-none d-md-block">
                <div class="loader text-center py-4" style="display:none; background:#f8fafc;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 text-dark font-monospace fw-bold" style="font-size:12px;">Loading sales records…</span>
                </div>

                <div class="sale-table-wrap">
                    <table class="qb-table nowrap" id="saleReport" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:38px;" class="text-center">#</th>
                                <th style="width:130px;">Date &amp; Time</th>
                                <th style="width:115px;">Invoice</th>
                                <th style="width:140px;">Customer</th>
                                <th style="width:95px;">Ref / M.Bill</th>
                                <th>Products</th>
                                <th style="width:80px;" class="text-center">Qty</th>
                                <th style="width:95px;" class="text-end">Price</th>
                                <th style="width:105px;" class="text-end">Total</th>
                                <th style="width:110px;" class="text-end">Net</th>
                                <th style="width:140px;">Returns</th>
                            </tr>
                        </thead>
                        <tbody id="saleBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Sale Cards Container (d-md-none) --}}
            <div class="d-md-none no-print" id="saleMobileContainer">
                <div class="text-center py-4 text-muted qb-table-card p-3">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                    <p class="mb-0 small fw-bold">Loading sales data…</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {

        let currentExpenses = 0;
        let currentCogs = 0;

        // Sync Filter Inputs between Desktop & Mobile
        $('.startDateInput').on('change', function() { $('.startDateInput').val($(this).val()); });
        $('.endDateInput').on('change', function() { $('.endDateInput').val($(this).val()); });
        $('.customerInput').on('change', function() { $('.customerInput').val($(this).val()); });
        $('.searchProductInput').on('input', function() { $('.searchProductInput').val($(this).val()); });

        // Core Load Report Function
        function loadSaleReport() {
            let start = $('#start_date_desk').val();
            let end   = $('#end_date_desk').val();
            let customer = $('#customer_id_desk').val() || 'all';

            $(".loader").show();
            $(".sale-table-wrap").hide();
            $('#saleMobileContainer').html('<div class="text-center py-4 text-muted qb-table-card p-3"><i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i><p class="mb-0 small fw-bold">Loading sales data…</p></div>');

            $.ajax({
                url: "{{ route('report.sale.fetch') }}",
                type: "GET",
                data: {
                    start_date: start,
                    end_date: end,
                    customer_id: customer
                },
                success: function(res) {
                    $(".loader").hide();
                    $(".sale-table-wrap").show();

                    let salesData = Array.isArray(res) ? res : (res.sales || []);
                    let summary   = res.summary || {};
                    currentExpenses = parseFloat(summary.expenses) || 0;
                    currentCogs     = parseFloat(summary.cogs) || 0;

                    let html = "";
                    let mobHtml = "";
                    let grandQty = 0,
                        grandTotal = 0,
                        grandNet = 0,
                        grandReturn = 0;

                    if (salesData.length === 0) {
                        html = `<tr><td colspan="11" class="text-center text-muted py-5 fw-bold" style="font-size:13px;"><i class="fas fa-folder-open fa-2x d-block mb-2 text-secondary"></i>No sales records found for the selected date range.</td></tr>`;
                        mobHtml = `<div class="qb-table-card text-center py-4 text-muted"><p class="small fw-bold mb-0">No Sales Data Found</p></div>`;
                        $('#saleBody').html(html);
                        $('#saleMobileContainer').html(mobHtml);
                        updateSingleLineSummary(0, 0, 0, 0, 0);
                        return;
                    }

                    salesData.forEach((s, i) => {
                        let products = s.product ? s.product.split(',').join('<br>') : '-';
                        let qtyArr = s.qty ? s.qty.split(',') : ['0'];
                        let qtyPiecesArr = s.total_pieces ? s.total_pieces.split(',') : (s.qty_decimal ? s.qty_decimal.split(',') : qtyArr);
                        let price = s.per_price ? s.per_price.split(',').join('<br>') : '-';
                        let total = s.per_total ? s.per_total.split(',').join('<br>') : '-';

                        let rowQty = qtyPiecesArr.reduce((a, b) => parseFloat(a) + parseFloat(b || 0), 0);
                        grandQty += rowQty;

                        let rowTotal = s.per_total ? s.per_total.split(',').reduce((a, b) => parseFloat(a) + parseFloat(b || 0), 0) : 0;
                        grandTotal += parseFloat(rowTotal);
                        grandNet += parseFloat(s.total_net || 0);

                        let returnHtml = "";
                        let returnTotal = 0;
                        if (s.returns && s.returns.length > 0) {
                            s.returns.forEach(r => {
                                returnHtml += `<span class="text-danger fw-semibold font-monospace" style="font-size:12px;">${r.product} (${r.qty}) - ${r.per_total}</span><br>`;
                                returnTotal += parseFloat(r.per_total || 0);
                            });
                        }
                        grandReturn += returnTotal;

                        let invDisplay = s.invoice_no || ('INVSLE-' + s.id);

                        // Desktop Row with QuickBooks font & badges
                        html += `<tr data-qty="${rowQty}" data-total="${rowTotal}" data-net="${s.total_net}" data-return="${returnTotal}">
                            <td class="text-center text-muted font-monospace fw-semibold" style="font-size:12px;">${i+1}</td>
                            <td class="font-monospace text-nowrap" style="font-size:12px;">${s.created_at}</td>
                            <td><span class="acc-badge-code">${invDisplay}</span></td>
                            <td class="fw-bold text-dark text-nowrap" style="font-size:13px;">${s.customer_name ?? '-'}</td>
                            <td class="text-muted text-nowrap font-monospace" style="font-size:12px;">${s.reference ?? '-'}</td>
                            <td class="fw-semibold text-dark" style="font-size:12.5px;">${products}</td>
                            <td class="fw-bold text-center font-monospace" style="font-size:13px;">${qtyArr.join('<br>')}</td>
                            <td class="text-end font-monospace">${price}</td>
                            <td class="text-end font-monospace fw-bold">Rs ${parseFloat(rowTotal).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                            <td class="fw-bold text-success font-monospace text-end" style="font-size:13px;">Rs ${parseFloat(s.total_net || 0).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                            <td>${returnHtml || '<span class="text-muted">—</span>'}</td>
                        </tr>`;

                        // Mobile Card
                        mobHtml += `
                        <div class="mob-card p-2 mb-2 mob-sale-card" data-search="${(s.product + ' ' + invDisplay + ' ' + (s.customer_name||'') + ' ' + (s.reference||'')).toLowerCase()}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="acc-badge-code">${invDisplay}</span>
                                <small class="text-muted font-monospace">${s.created_at}</small>
                            </div>
                            <div class="mb-1">
                                <strong class="text-dark d-block" style="font-size: 13px;">${s.customer_name ?? 'Walking Customer'}</strong>
                                <small class="text-muted">Ref: ${s.reference ?? '-'}</small>
                            </div>
                            <div class="p-1 mb-1" style="font-size: 11.5px; background: #e2ecf7; border-radius: 3px;">
                                <strong>Products:</strong> ${products}
                            </div>
                            <div class="d-flex justify-content-between font-monospace fw-bold" style="font-size: 12px;">
                                <span>Qty: ${rowQty}</span>
                                <span class="text-success">Net: Rs ${parseFloat(s.total_net).toLocaleString(undefined, {minimumFractionDigits:2})}</span>
                            </div>
                        </div>`;
                    });

                    // Grand total row inside desktop table
                    html += `<tr class="fw-bold" id="grandTotalRow">
                        <td colspan="6" class="text-end" style="letter-spacing:0.3px;">GRAND TOTAL:</td>
                        <td id="grandQty" class="text-center font-monospace" style="font-size:13px;">${grandQty.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td class="text-center">—</td>
                        <td id="grandTotal" class="text-end font-monospace" style="font-size:13px;">Rs ${grandTotal.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td id="grandNet" class="text-end font-monospace text-success" style="font-size:13.5px;">Rs ${grandNet.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td id="grandReturn" class="font-monospace text-danger" style="font-size:13px;">Rs ${grandReturn.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    </tr>`;

                    $('#saleBody').html(html);
                    $('#saleMobileContainer').html(mobHtml);

                    updateSingleLineSummary(salesData.length, grandQty, grandTotal, grandReturn, grandNet);
                },
                error: function(xhr) {
                    $(".loader").hide();
                    $(".sale-table-wrap").show();
                    $('#saleBody').html(`<tr><td colspan="11" class="text-center text-danger py-4 fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> Failed to load sales data. Please check connection and try again.</td></tr>`);
                }
            });
        }

        // Trigger on Filter Click
        $(document).on('click', '.btnSearchTrigger', function() {
            $('.searchProductInput').val('');
            loadSaleReport();
        });

        // Trigger on Date or Customer Change
        $('.startDateInput, .endDateInput, .customerInput').on('change', function() {
            loadSaleReport();
        });

        // INITIAL LOAD ON PAGE READY
        loadSaleReport();

        // Function to update Summary Metrics
        function updateSingleLineSummary(count, qty, gross, returns, net) {
            let grossProfit   = net - currentCogs;
            let currentProfit = grossProfit - currentExpenses;

            let formattedCount = count.toLocaleString();
            let formattedQty   = qty.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + ' Pcs';
            let formattedGross = 'Rs ' + gross.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            let formattedRet   = 'Rs ' + returns.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            let formattedNet   = 'Rs ' + net.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            let formattedGP    = 'Rs ' + grossProfit.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            let formattedExp   = 'Rs ' + currentExpenses.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            let formattedNP    = 'Rs ' + currentProfit.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});

            $('#pillTotalInvoices').text(formattedCount);
            $('#pillTotalQty').text(formattedQty);
            $('#pillGrossSale').text(formattedGross);
            $('#pillTotalReturns').text(formattedRet);
            $('#pillNetSale').text(formattedNet);
            $('#pillGrossProfit').text(formattedGP);
            $('#pillExpenses').text(formattedExp);
            $('#pillCurrentProfit').text(formattedNP);
        }

        // Real-time Search Filter Handler
        $(document).on('input', '.searchProductInput', function() {
            let val = $(this).val().toLowerCase();

            $('#saleBody tr').each(function() {
                if ($(this).attr('id') === 'grandTotalRow') return;
                let text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(val) > -1);
            });

            $('.mob-sale-card').each(function() {
                let searchStr = $(this).attr('data-search') || $(this).text().toLowerCase();
                $(this).toggle(searchStr.indexOf(val) > -1);
            });
        });

        // Export CSV Handler
        $(document).on('click', '.btnExportCsvTrigger', function() {
            let csv = [];
            csv.push(['#', 'Date & Time', 'Invoice', 'Customer', 'Reference', 'Products', 'Qty', 'Price', 'Total', 'Net Amount', 'Returns'].join(','));
            $('#saleBody tr').each(function() {
                if ($(this).attr('id') === 'grandTotalRow') return;
                let row = [];
                $(this).find('td').each(function() {
                    let text = $(this).text().trim().replace(/,/g, '').replace(/\n/g, ' ');
                    row.push('"' + text + '"');
                });
                if (row.length) csv.push(row.join(','));
            });
            let blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Sale_Report_' + new Date().toISOString().slice(0,10) + '.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

    });
</script>
@endsection
