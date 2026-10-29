@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "PRODUCT WISE CUSTOMER SALE REPORT"
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
        min-width: 0;
    }
    .qb-filter-col .select2-container {
        width: 100% !important;
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

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #8ca4c3 !important;
        min-height: 30px !important;
        border-radius: 3px !important;
        padding: 1px 4px !important;
        font-size: 12px !important;
        background: #ffffff !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        color: #0f2744 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 0 5px !important;
        margin-top: 2px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-search__field {
        margin-top: 3px !important;
        font-size: 12px !important;
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
    }
    .qb-table {
        margin-bottom: 0 !important;
        font-size: 13px !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }
    .qb-table thead th {
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

    .qb-table tfoot tr td {
        background: linear-gradient(180deg, #edf3fa 0%, #dbe5f1 100%) !important;
        color: #0f2744 !important;
        font-weight: 800 !important;
        font-size: 13px !important;
        border-top: 2px solid #8ca4c3 !important;
        border-right: 1px solid #cbd5e1 !important;
        padding: 8px 10px !important;
    }

    /* Customer Sub-ledger header */
    .qb-customer-header {
        background: linear-gradient(180deg, #1e3a5f 0%, #152943 100%) !important;
        color: #ffffff !important;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
    }

    @media print {
        body { background: #ffffff !important; font-size: 11px; }
        .no-print, header, .sidebar, .navbar, footer, .qb-filter-bar, .qb-win-header { display: none !important; }
        .odoo-page-wrapper { padding: 0 !important; background: #fff !important; }
        .qb-table-card { border: none !important; box-shadow: none !important; margin-bottom: 12px !important; }
        .qb-table th, .qb-table td { border: 1px solid #000 !important; }
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid odoo-page-wrapper">

            {{-- 1. Top Window Title Bar --}}
            <div class="qb-win-header no-print">
                <div class="qb-win-title">
                    <i class="fas fa-users"></i>
                    <span>Product Wise Customer Sale Report</span>
                    <span class="qb-win-sub d-none d-md-inline">/ Item Breakdown by Client</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="qb-btn-blue btnExportExcel" title="Export to Excel">
                        <i class="fas fa-file-excel"></i> Export Excel
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
                        <i class="fas fa-users text-primary"></i>
                        <span class="chip-label text-primary">Customers:</span>
                        <span class="chip-val font-monospace text-dark" id="pillCustomers">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-file-invoice text-muted"></i>
                        <span class="chip-label text-muted">Invoices:</span>
                        <span class="chip-val font-monospace text-dark" id="pillInvoices">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-box text-info"></i>
                        <span class="chip-label text-info">Sold Qty:</span>
                        <span class="chip-val font-monospace text-primary" id="pillSoldQty">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-undo text-warning"></i>
                        <span class="chip-label text-warning">Ret Qty:</span>
                        <span class="chip-val font-monospace text-warning" id="pillReturnQty">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-cubes text-info"></i>
                        <span class="chip-label text-info">Net Qty:</span>
                        <span class="chip-val font-monospace text-info" id="pillNetQty">0</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-wallet text-secondary"></i>
                        <span class="chip-label text-secondary">Gross Sales:</span>
                        <span class="chip-val font-monospace text-dark" id="pillGrossSales">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-minus-circle text-danger"></i>
                        <span class="chip-label text-danger">Ret Sales:</span>
                        <span class="chip-val font-monospace text-danger" id="pillReturnSales">Rs 0.00</span>
                    </div>

                    <div class="qb-kpi-chip">
                        <i class="fas fa-check-circle text-success"></i>
                        <span class="chip-label text-success">Net Sales:</span>
                        <span class="chip-val font-monospace text-success" id="pillNetSales">Rs 0.00</span>
                    </div>
                </div>
            </div>

            {{-- 3. Filter Bar (Single-Line Compact Strip) --}}
            <div class="qb-filter-bar no-print">
                <form id="reportFilterFormDesktop" class="qb-filter-form" onsubmit="return false;">
                    
                    {{-- From Date --}}
                    <div class="qb-filter-col" style="flex: 0 0 auto; width: 130px;">
                        <label class="qb-flabel">From Date</label>
                        <input type="date" name="from_date" id="filterFromDateDesk" class="qb-input" value="{{ date('Y-m-01') }}">
                    </div>

                    {{-- To Date --}}
                    <div class="qb-filter-col" style="flex: 0 0 auto; width: 130px;">
                        <label class="qb-flabel">To Date</label>
                        <input type="date" name="to_date" id="filterToDateDesk" class="qb-input" value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Customer (Multi) --}}
                    <div class="qb-filter-col" style="flex: 1.4 1 180px; min-width: 160px;">
                        <label class="qb-flabel">Customer (Multi)</label>
                        <select name="customer_id[]" id="filterCustomerDesk" class="form-select select2-multi filterCustomerSelect" multiple data-placeholder="-- All Customers --">
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->customer_id ?? 'CUST' }} - {{ $c->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Product (Multi) --}}
                    <div class="qb-filter-col" style="flex: 1.4 1 180px; min-width: 160px;">
                        <label class="qb-flabel">Product (Multi)</label>
                        <select name="product_id[]" id="filterProductDesk" class="form-select select2-multi filterProductSelect" multiple data-placeholder="-- All Products --">
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->item_code }} - {{ $p->item_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category (Multi) --}}
                    <div class="qb-filter-col" style="flex: 1 1 140px; min-width: 130px;">
                        <label class="qb-flabel">Category (Multi)</label>
                        <select name="category_id[]" id="filterCategoryDesk" class="form-select select2-multi filterCategorySelect" multiple data-placeholder="-- All Categories --">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand (Multi) --}}
                    <div class="qb-filter-col" style="flex: 1 1 140px; min-width: 130px;">
                        <label class="qb-flabel">Brand (Multi)</label>
                        <select name="brand_id[]" id="filterBrandDesk" class="form-select select2-multi filterBrandSelect" multiple data-placeholder="-- All Brands --">
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-1" style="flex: 0 0 auto;">
                        <button type="submit" class="qb-btn-search btnFilterTrigger" title="Generate Report">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>
                        <button type="button" class="qb-btn-reset btnResetTrigger" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="qb-btn-blue btnExportExcel" title="Export Excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>

                </form>
            </div>

            {{-- 4. Dynamic Customer Content Container --}}
            <div id="reportDataContainer" class="mt-1">
                <div class="text-center py-4 text-muted qb-table-card p-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 font-monospace fw-bold" style="font-size:12px;">Loading Customer Sales Data…</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-multi').select2({
                    placeholder: "Select (All)",
                    allowClear: true,
                    width: '100%'
                });
            }

            function loadReportData() {
                $('#reportDataContainer').html(`
                    <div class="text-center py-4 text-muted qb-table-card p-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="ms-2 font-monospace fw-bold" style="font-size:12px;">Fetching Customer Sales Data…</span>
                    </div>
                `);

                let formData = $('#reportFilterFormDesktop').serialize();

                $.ajax({
                    url: "{{ route('report.product_sale_customer_wise.fetch') }}",
                    type: "GET",
                    data: formData,
                    success: function(res) {
                        if (res.summary) {
                            let custVal = res.summary.total_customers || 0;
                            let invVal  = res.summary.total_invoices || 0;
                            let soldVal = res.summary.total_qty || '0.00';
                            let retQtyVal = res.summary.total_return_qty || '0.00';
                            let netQtyVal = res.summary.net_qty || '0.00';
                            let grossVal = res.summary.gross_amount || 'Rs 0.00';
                            let retSalesVal = res.summary.return_amount || 'Rs 0.00';
                            let netSalesVal = res.summary.net_sale_amount || 'Rs 0.00';

                            $('#pillCustomers').text(custVal);
                            $('#pillInvoices').text(invVal);
                            $('#pillSoldQty').text(soldVal);
                            $('#pillReturnQty').text(retQtyVal);
                            $('#pillNetQty').text(netQtyVal);
                            $('#pillGrossSales').text(grossVal);
                            $('#pillReturnSales').text(retSalesVal);
                            $('#pillNetSales').text(netSalesVal);
                        }

                        if (res.customers && res.customers.length) {
                            let html = '';
                            res.customers.forEach((cust) => {
                                html += `
                                    <div class="qb-table-card mb-2">
                                        
                                        {{-- Customer Header Ribbon --}}
                                        <div class="qb-customer-header">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="acc-badge-code" style="background:#ffffff; color:#0f2744;">${cust.customer_code}</span>
                                                <strong style="font-size: 13.5px; color:#ffffff;">${cust.customer_name}</strong>
                                                <small style="font-size: 11.5px; color:#93c5fd;"><i class="fas fa-phone-alt me-1"></i>${cust.customer_mobile || '-'} | <i class="fas fa-map-marker-alt me-1"></i>${cust.customer_city || '-'}</small>
                                            </div>
                                            <div>
                                                <small style="color:#cbd5e1; font-size:11px;">Customer Net Total:</small>
                                                <strong class="font-monospace ms-1" style="font-size: 13.5px; color:#38bdf8;">Rs ${parseFloat(cust.net_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
                                            </div>
                                        </div>

                                        {{-- Desktop Table View --}}
                                        <div class="sale-table-wrap">
                                            <table class="qb-table nowrap" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 36px;">#</th>
                                                        <th style="width: 100px;">CODE</th>
                                                        <th>PRODUCT NAME</th>
                                                        <th style="width: 150px;">BRAND / CATEGORY</th>
                                                        <th class="text-center" style="width: 80px;">SOLD QTY</th>
                                                        <th class="text-end" style="width: 100px;">AVG PRICE</th>
                                                        <th class="text-end" style="width: 110px;">GROSS AMT</th>
                                                        <th class="text-center" style="width: 75px;">RET QTY</th>
                                                        <th class="text-end" style="width: 100px;">RET AMT</th>
                                                        <th class="text-center" style="width: 75px;">NET QTY</th>
                                                        <th class="text-end" style="width: 120px;">NET AMT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;
                                cust.products.forEach((p, idx) => {
                                    html += `
                                        <tr>
                                            <td class="text-center text-muted font-monospace fw-semibold" style="font-size:12px;">${idx + 1}</td>
                                            <td><span class="acc-badge-code">${p.product_code}</span></td>
                                            <td class="fw-bold text-dark text-nowrap" style="font-size:13.5px;">${p.product_name}</td>
                                            <td class="text-muted text-nowrap" style="font-size:12px;">${p.brand_name} / ${p.category_name}</td>
                                            <td class="text-center font-monospace fw-bold" style="font-size:13px;">${parseFloat(p.sold_qty).toLocaleString()}</td>
                                            <td class="text-end font-monospace">Rs ${parseFloat(p.avg_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:13px;">Rs ${parseFloat(p.gross_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                            <td class="text-center font-monospace text-secondary">${p.return_qty > 0 ? parseFloat(p.return_qty).toLocaleString() : '—'}</td>
                                            <td class="text-end font-monospace text-danger">${p.return_amount > 0 ? 'Rs ' + parseFloat(p.return_amount).toLocaleString('en-US', {minimumFractionDigits: 2}) : '—'}</td>
                                            <td class="text-center font-monospace fw-bold text-dark" style="font-size:13px;">${parseFloat(p.net_qty).toLocaleString()}</td>
                                            <td class="text-end font-monospace fw-bold text-success" style="font-size:13px;">Rs ${parseFloat(p.net_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        </tr>`;
                                });
                                html += `
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-dark" style="letter-spacing:0.3px;">SUBTOTAL (${cust.customer_name}):</td>
                                                        <td class="text-center font-monospace" style="font-size:13px;">${parseFloat(cust.sold_qty).toLocaleString()}</td>
                                                        <td class="text-center">—</td>
                                                        <td class="text-end font-monospace" style="font-size:13px;">Rs ${parseFloat(cust.gross_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                        <td class="text-center font-monospace text-secondary">${parseFloat(cust.return_qty).toLocaleString()}</td>
                                                        <td class="text-end font-monospace text-danger">Rs ${parseFloat(cust.return_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                        <td class="text-center font-monospace" style="font-size:13px;">${parseFloat(cust.net_qty).toLocaleString()}</td>
                                                        <td class="text-end font-monospace text-success" style="font-size:13.5px;">Rs ${parseFloat(cust.net_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>`;
                            });

                            $('#reportDataContainer').html(html);
                        } else {
                            $('#reportDataContainer').html(`
                                <div class="text-center py-5 text-muted qb-table-card p-4">
                                    <i class="fas fa-folder-open fa-2x d-block mb-2 text-secondary"></i>
                                    <h6 class="fw-bold mb-0">No customer sales data found for the selected criteria.</h6>
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#reportDataContainer').html(`
                            <div class="text-center py-4 text-danger qb-table-card p-3">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <p class="mb-0 fw-bold">Failed to fetch customer sales data. Please try again.</p>
                            </div>
                        `);
                    }
                });
            }

            // Form Submit Trigger
            $('#reportFilterFormDesktop').on('submit', function(e) {
                e.preventDefault();
                loadReportData();
            });

            // Reset Action
            $('.btnResetTrigger').on('click', function() {
                $('#filterFromDateDesk').val('{{ date("Y-m-01") }}');
                $('#filterToDateDesk').val('{{ date("Y-m-d") }}');
                $('.select2-multi').val(null).trigger('change');
                loadReportData();
            });

            // Print
            $('.btnPrintReport').on('click', function() {
                window.print();
            });

            // Excel Export
            $('.btnExportExcel').on('click', function() {
                let params = $('#reportFilterFormDesktop').serialize();
                window.location.href = "{{ route('report.product_sale_customer_wise.export') }}?" + params;
            });

            // Initial Load
            loadReportData();
        });
    </script>
@endsection
