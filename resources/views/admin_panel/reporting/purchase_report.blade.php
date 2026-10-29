@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "PURCHASE REPORT" THEME
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
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Purchase Report</span>
                    <span class="qb-win-sub d-none d-md-inline">/ Inward Invoices &amp; Vendor Due Audit</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-blue btnCsvTrigger" title="Export to CSV">
                        <i class="fas fa-file-excel"></i> Export CSV
                    </button>
                    <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            {{-- 2. Sub-Header Ribbon: Financial KPIs --}}
            <div class="qb-sub-ribbon no-print">
                <div class="qb-kpi-group">
                    <div class="qb-kpi-chip">
                        <i class="fas fa-receipt text-primary"></i>
                        <span class="chip-label text-primary">Total Purchases Net:</span>
                        <span class="chip-val font-monospace text-primary" id="pillNet">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-money-bill-wave text-success"></i>
                        <span class="chip-label text-success">Total Paid:</span>
                        <span class="chip-val font-monospace text-success" id="pillPaid">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-hand-holding-dollar text-danger"></i>
                        <span class="chip-label text-danger">Total Due / Payable:</span>
                        <span class="chip-val font-monospace text-danger" id="pillDue">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-boxes-stacked text-secondary"></i>
                        <span class="chip-label text-secondary">Total Purchased Qty:</span>
                        <span class="chip-val font-monospace text-dark" id="pillQty">0.00</span>
                    </div>
                </div>
            </div>

            {{-- 3. Filter Bar (Single-Line Compact Strip) --}}
            <div class="qb-filter-bar no-print">
                <form id="purchaseFormDesk" class="qb-filter-form" onsubmit="return false;">
                    
                    {{-- Start Date --}}
                    <div class="qb-filter-col" style="min-width: 140px;">
                        <label class="qb-flabel">Start Date</label>
                        <input type="date" id="start_date_desk" value="{{ date('Y-m-01') }}" class="qb-input startDateInput">
                    </div>

                    {{-- End Date --}}
                    <div class="qb-filter-col" style="min-width: 140px;">
                        <label class="qb-flabel">End Date</label>
                        <input type="date" id="end_date_desk" value="{{ date('Y-m-d') }}" class="qb-input endDateInput">
                    </div>

                    {{-- Vendor Filter --}}
                    <div class="qb-filter-col" style="min-width: 180px; flex: 1;">
                        <label class="qb-flabel">Vendor</label>
                        <select id="vendor_id_desk" class="qb-input vendorInput">
                            <option value="all">-- All Vendors --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Product Filter --}}
                    <div class="qb-filter-col" style="min-width: 200px; flex: 1.2;">
                        <label class="qb-flabel">Product</label>
                        <select id="product_id_desk" class="qb-input productInput">
                            <option value="all">-- All Products --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->item_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="qb-btn-search btnSearchTrigger" title="Search Purchases">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" class="qb-btn-reset btnResetTrigger" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="qb-btn-blue btnCsvTrigger" title="Export CSV">
                            <i class="fas fa-file-excel"></i> CSV
                        </button>
                    </div>
                </form>
            </div>

            {{-- Loader --}}
            <div id="loader" style="display:none; text-align:center; padding: 18px; background:#f8fafc;" class="qb-table-card mb-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="ms-2 text-dark font-monospace fw-bold" style="font-size:12px;">Fetching Purchase Data…</span>
            </div>

            <div id="reportBox">
                {{-- 4. Table Card (Desktop) --}}
                <div class="qb-table-card d-none d-md-block">
                    <div class="sale-table-wrap">
                        <table class="qb-table nowrap" id="purchaseTable" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 38px;">#</th>
                                    <th style="width: 95px;">DATE</th>
                                    <th style="width: 110px;">INVOICE</th>
                                    <th>VENDOR</th>
                                    <th style="width: 105px;">CODE</th>
                                    <th>ITEM NAME</th>
                                    <th class="text-center" style="width: 80px;">QTY</th>
                                    <th class="text-center" style="width: 65px;">UNIT</th>
                                    <th class="text-end" style="width: 95px;">PRICE</th>
                                    <th class="text-end" style="width: 110px;">LINE TOTAL</th>
                                    <th class="text-center" style="width: 110px;">RETURNS</th>
                                    <th class="text-end" style="width: 120px;">NET AMOUNT</th>
                                    <th class="text-end" style="width: 105px;">PAID</th>
                                    <th class="text-end" style="width: 105px;">DUE</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRows"></tbody>
                            <tfoot id="tfootTotals"></tfoot>
                        </table>
                    </div>
                </div>

                {{-- Mobile Cards Container (d-md-none) --}}
                <div class="d-md-none no-print" id="mobPurchaseContainer"></div>
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
        $('.vendorInput').on('change', function() { $('.vendorInput').val($(this).val()); });
        $('.productInput').on('change', function() { $('.productInput').val($(this).val()); });

        function fmt(amount) {
            let val = parseFloat(amount || 0);
            return 'Rs ' + val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        let rawRows = [];

        function loadReport() {
            let start = $(".startDateInput").val();
            let end = $(".endDateInput").val();
            let vendor = $(".vendorInput").val();
            let product = $(".productInput").val();
            
            $("#loader").show();
            $("#reportBox").hide();

            $.ajax({
                url: "{{ route('report.purchase.fetch') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    start_date: start,
                    end_date: end,
                    vendor_id: vendor,
                    product_id: product
                },
                success: function(res) {
                    $("#loader").hide();
                    $("#reportBox").show();

                    let rows = res.data || [];
                    rawRows = rows;

                    let rowsHtml = "";
                    let mobHtml = "";

                    let totalQty = 0;
                    let totalNet = 0;
                    let totalPaid = 0;
                    let totalDue = 0;

                    if (rows.length > 0) {
                        rows.forEach(function(row, idx) {
                            let qtyVal = parseFloat(row.qty || 0);
                            let netVal = parseFloat(row.net_amount || 0);
                            let paidVal = parseFloat(row.paid_amount || 0);
                            let dueVal = parseFloat(row.due_amount || 0);
                            let priceVal = parseFloat(row.price || 0);
                            let lineTotalVal = parseFloat(row.line_total || 0);

                            totalQty += qtyVal;
                            totalNet += netVal;
                            totalPaid += paidVal;
                            totalDue += dueVal;

                            let returnHtml = '<span class="text-muted">—</span>';
                            let mobReturnHtml = '';
                            if (row.returns && row.returns.length > 0) {
                                let retParts = row.returns.map(ret => `${ret.qty} (${fmt(ret.line_total)})`);
                                returnHtml = `<span class="text-danger fw-semibold font-monospace" style="font-size:12px;">${retParts.join('<br>')}</span>`;
                                mobReturnHtml = `<div class="mt-1 text-danger small"><i class="fas fa-rotate-left me-1"></i>Returned: ${retParts.join(', ')}</div>`;
                            }

                            rowsHtml += `
                                <tr>
                                    <td class="text-center text-muted font-monospace fw-semibold" style="font-size:12px;">${idx + 1}</td>
                                    <td class="text-nowrap font-monospace" style="font-size: 12px;">${row.purchase_date}</td>
                                    <td><span class="acc-badge-code">${row.invoice_no}</span></td>
                                    <td class="fw-bold text-dark text-nowrap" style="font-size:13px;">${row.vendor_name}</td>
                                    <td><span class="acc-badge-code">${row.item_code}</span></td>
                                    <td class="fw-bold text-dark" style="font-size:13px;">${row.item_name}</td>
                                    <td class="text-center font-monospace fw-bold" style="font-size:13px;">${qtyVal.toFixed(2)}</td>
                                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace" style="font-size:11px;">${row.unit || 'pc'}</span></td>
                                    <td class="text-end font-monospace">${fmt(priceVal)}</td>
                                    <td class="text-end font-monospace fw-bold text-dark" style="font-size:13px;">${fmt(lineTotalVal)}</td>
                                    <td class="text-center">${returnHtml}</td>
                                    <td class="text-end font-monospace fw-bold text-primary" style="font-size:13px;">${fmt(netVal)}</td>
                                    <td class="text-end font-monospace text-success fw-bold" style="font-size:13px;">${fmt(paidVal)}</td>
                                    <td class="text-end font-monospace text-danger fw-bold" style="font-size:13px;">${fmt(dueVal)}</td>
                                </tr>
                            `;

                            mobHtml += `
                                <div class="mob-card p-2 mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="acc-badge-code">${row.invoice_no}</span>
                                        <span class="text-muted font-monospace" style="font-size: 11px;">${row.purchase_date}</span>
                                    </div>
                                    <div class="mb-1">
                                        <strong class="text-dark d-block" style="font-size: 13px;">${row.vendor_name}</strong>
                                    </div>
                                    <div class="p-1 mb-1" style="background:#e2ecf7; border-radius:3px; font-size:12px;">
                                        <strong>${row.item_name}</strong> <span class="badge bg-white text-dark ms-1">${row.item_code}</span>
                                    </div>
                                    <div class="d-flex justify-content-between font-monospace fw-bold" style="font-size: 12px;">
                                        <span>Qty: ${qtyVal.toFixed(2)} ${row.unit || 'pc'}</span>
                                        <span class="text-primary">Net: ${fmt(netVal)}</span>
                                    </div>
                                    ${mobReturnHtml}
                                    <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1 font-monospace" style="font-size: 11.5px;">
                                        <span class="text-success fw-bold">Paid: ${fmt(paidVal)}</span>
                                        <span class="text-danger fw-bold">Due: ${fmt(dueVal)}</span>
                                    </div>
                                </div>
                            `;
                        });

                        $("#tbodyRows").html(rowsHtml);
                        $("#mobPurchaseContainer").html(mobHtml);

                        // Totals
                        let totalsHtml = `
                            <tr>
                                <td colspan="6" class="text-end text-dark" style="letter-spacing:0.3px;">TOTALS:</td>
                                <td class="text-center text-dark font-monospace" style="font-size:13px;">${totalQty.toFixed(2)}</td>
                                <td colspan="4" class="text-center">—</td>
                                <td class="text-end font-monospace text-primary" style="font-size:13.5px;">${fmt(totalNet)}</td>
                                <td class="text-end font-monospace text-success" style="font-size:13px;">${fmt(totalPaid)}</td>
                                <td class="text-end font-monospace text-danger" style="font-size:13px;">${fmt(totalDue)}</td>
                            </tr>
                        `;
                        $("#tfootTotals").html(totalsHtml);

                        // Stat Pills
                        $('#pillNet, #mobPillNet').text(fmt(totalNet));
                        $('#pillPaid, #mobPillPaid').text(fmt(totalPaid));
                        $('#pillDue, #mobPillDue').text(fmt(totalDue));
                        $('#pillQty, #mobPillQty').text(totalQty.toFixed(2));
                    } else {
                        $("#tbodyRows").html(`<tr><td colspan="14" class="text-center text-muted py-5 fw-bold" style="font-size:13px;"><i class="fas fa-folder-open fa-2x d-block mb-2 text-secondary"></i>No purchase records found in selected filters.</td></tr>`);
                        $("#mobPurchaseContainer").html('<div class="qb-table-card text-center py-4 text-muted"><p class="small fw-bold mb-0">No Purchase Data Found</p></div>');
                        $("#tfootTotals").empty();

                        $('#pillNet, #mobPillNet').text('Rs 0.00');
                        $('#pillPaid, #mobPillPaid').text('Rs 0.00');
                        $('#pillDue, #mobPillDue').text('Rs 0.00');
                        $('#pillQty, #mobPillQty').text('0.00');
                    }
                },
                error: function() {
                    $("#loader").hide();
                    alert("Error fetching purchase report data.");
                }
            });
        }

        // Export to CSV Function
        function exportToCSV() {
            if (!rawRows || rawRows.length === 0) {
                alert("No data available to export.");
                return;
            }

            let csv = "Purchase Date,Invoice No,Vendor Name,Item Code,Item Name,Qty,Unit,Price,Line Total,Returns,Net Amount,Paid Amount,Due Amount\n";
            rawRows.forEach(function(r) {
                let returnStr = r.returns && r.returns.length ? r.returns.map(ret => `${ret.qty} (${ret.line_total})`).join(';') : '';
                csv += `"${r.purchase_date}","${r.invoice_no}","${r.vendor_name}","${r.item_code}","${r.item_name}",${r.qty},"${r.unit || 'pc'}",${r.price},${r.line_total},"${returnStr}",${r.net_amount},${r.paid_amount},${r.due_amount}\n`;
            });

            let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'purchase_report_' + new Date().toISOString().slice(0, 10) + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        $(".btnSearchTrigger").click(function() {
            loadReport();
        });

        $(".btnResetTrigger").click(function() {
            let startOfMonth = '{{ date("Y-m-01") }}';
            let today = '{{ date("Y-m-d") }}';
            $(".startDateInput").val(startOfMonth);
            $(".endDateInput").val(today);
            $(".vendorInput").val('all');
            $(".productInput").val('all');
            loadReport();
        });

        $(".btnPrintReport").click(function() {
            window.print();
        });

        $(".btnCsvTrigger").click(function() {
            exportToCSV();
        });

        // Load report on page ready
        loadReport();
    });
</script>
@endsection
