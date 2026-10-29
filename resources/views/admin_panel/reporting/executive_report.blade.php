@extends('admin_panel.layout.app')

@section('content')
<style>
    /* ==========================================================
       QUICKBOOKS / ODOO ERP "EXECUTIVE OVERVIEW REPORT" THEME
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

    /* 2. Sub-Header Ribbon: Executive Financial Totals */
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

    /* 3. Table Card & High-Density QuickBooks Table */
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
        max-height: calc(100vh - 350px);
        min-height: 250px;
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

    .account-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-bottom: 1px solid #e2ecf7;
        font-size: 13px;
    }
    .account-row:nth-child(even) {
        background-color: #f7fafd;
    }
    .account-row:last-child { border-bottom: none; }
    .account-row:hover {
        background-color: #e2ecf7;
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
        .no-print, header, .sidebar, .navbar, footer, .qb-win-header { display: none !important; }
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
            <i class="fa-solid fa-crown text-warning"></i>
            <span>Executive Overview Report</span>
            <span class="qb-win-sub d-none d-sm-inline">| Executive Summary & Vitals</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="qb-btn-blue btnFetchTrigger" title="Refresh Live Data">
                <i class="fas fa-sync-alt me-1"></i>
                <span class="d-none d-sm-inline">Refresh</span>
            </button>
            <button type="button" class="qb-btn-gray btnPrintReport" title="Print Report">
                <i class="fa-solid fa-print text-primary"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- 2. Sub-Header Ribbon: Executive KPIs (Desktop) --}}
    <div class="qb-sub-ribbon no-print d-none d-md-flex">
        <div class="qb-kpi-group">
            <div class="qb-kpi-chip">
                <span class="chip-label text-info"><i class="fas fa-calendar-day me-1"></i>Sales Today:</span>
                <span class="chip-val text-primary" id="salesToday">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-primary"><i class="fas fa-chart-line me-1"></i>Sales (Month):</span>
                <span class="chip-val text-primary" id="salesMonth">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label" style="color: #b45309;"><i class="fas fa-truck-loading me-1"></i>Purchases (Month):</span>
                <span class="chip-val" style="color: #d97706;" id="purchasesMonth">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fas fa-file-invoice-dollar me-1"></i>Expenses:</span>
                <span class="chip-val text-danger" id="expensesMonth">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-danger"><i class="fas fa-arrow-down me-1"></i>Receivables:</span>
                <span class="chip-val text-danger" id="totalReceivables">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label" style="color: #b45309;"><i class="fas fa-arrow-up me-1"></i>Payables:</span>
                <span class="chip-val" style="color: #d97706;" id="totalPayables">Rs 0.00</span>
            </div>
            <div class="qb-kpi-chip">
                <span class="chip-label text-success"><i class="fas fa-shield-halved me-1"></i>Liquidity:</span>
                <span class="chip-val text-success" id="liquidityRatio">1.0+</span>
            </div>
        </div>
        <div class="text-muted small fw-bold">
            <i class="fa-solid fa-gauge-high me-1 text-primary"></i> Live Vitals
        </div>
    </div>

    {{-- Mobile Summary Cards --}}
    <div class="row g-2 mb-1 d-md-none no-print px-1">
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-primary">Sales (Month)</span>
                <div class="mob-metric-val text-primary mt-1" id="mobSalesMonth">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-warning">Purchases</span>
                <div class="mob-metric-val text-warning mt-1" id="mobPurchasesMonth">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-danger">Receivables</span>
                <div class="mob-metric-val text-danger mt-1" id="mobTotalReceivables">Rs 0.00</div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-metric-card">
                <span class="mob-metric-label text-warning">Payables</span>
                <div class="mob-metric-val text-warning mt-1" id="mobTotalPayables">Rs 0.00</div>
            </div>
        </div>
    </div>

    {{-- Loader --}}
    <div id="loader" style="display:none; text-align:center; padding: 25px;">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="small fw-bold text-muted mt-2">Syncing latest executive figures…</div>
    </div>

    {{-- 3. Content Layout --}}
    <div class="row g-2 mb-2">
        
        {{-- Cash in Hand --}}
        <div class="col-md-6">
            <div class="qb-table-card h-100">
                <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-wallet text-success me-2"></i>Cash in Hand
                    </span>
                    <span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:10px;">Liquid Cash</span>
                </div>
                <div class="p-0" id="cashList">
                    <div class="text-center py-3 text-muted small">Loading cash accounts…</div>
                </div>
            </div>
        </div>

        {{-- Bank Balances --}}
        <div class="col-md-6">
            <div class="qb-table-card h-100">
                <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-university text-info me-2"></i>Bank Balances
                    </span>
                    <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; font-size:10px;">Deposit Accounts</span>
                </div>
                <div class="p-0" id="bankList">
                    <div class="text-center py-3 text-muted small">Loading bank accounts…</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Top 10 Customers --}}
    <div class="qb-table-card mb-3">
        <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-crown text-warning me-2"></i>Top 10 Customers by Profit
            </span>
            <span class="badge bg-secondary text-white small" id="customerCount">0 customers</span>
        </div>
        
        {{-- DESKTOP TABLE VIEW (d-none d-md-block) --}}
        <div class="d-none d-md-block">
            <div class="sale-table-wrap">
                <table class="qb-table table-bordered align-middle" id="topCustomersTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">#</th>
                            <th>CUSTOMER NAME</th>
                            <th class="text-end" style="width: 160px;">REVENUE</th>
                            <th class="text-end" style="width: 160px;">BALANCE</th>
                            <th class="text-end" style="width: 160px;">NET PROFIT</th>
                        </tr>
                    </thead>
                    <tbody id="topCustomersList">
                        <tr><td colspan="5" class="text-center text-muted py-4 small">Fetch data to see top customers</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE CARDS CONTAINER (d-md-none) --}}
        <div class="d-md-none p-2" id="mobTopCustomersContainer"></div>
    </div>

</div>
@endsection

@section('js')
<script>
function fmt(n) {
    let val = parseFloat(n || 0);
    let sign = val < 0 ? '-' : '';
    return sign + 'Rs ' + Math.abs(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function fetchData() {
    $('#loader').show();

    $.ajax({
        url: "{{ route('report.executive.fetch') }}",
        type: "GET",
        success: function(res) {
            $('#loader').hide();

            // Sales
            $('#salesToday').text(fmt(res.sales.today));
            $('#salesMonth, #mobSalesMonth').text(fmt(res.sales.month));

            // Purchases
            if (res.purchases) {
                $('#purchasesToday').text(fmt(res.purchases.today));
                $('#purchasesMonth, #mobPurchasesMonth').text(fmt(res.purchases.month));
            }

            // Expenses
            if (res.expenses) {
                $('#expensesToday').text(fmt(res.expenses.today));
                $('#expensesMonth').text(fmt(res.expenses.month));
            }

            // Vitals
            $('#totalReceivables, #mobTotalReceivables').text(fmt(res.receivables));
            $('#totalPayables, #mobTotalPayables').text(fmt(res.payables));

            // Accounts
            let cashHtml = '';
            if(res.accounts && res.accounts.cash && res.accounts.cash.length > 0) {
                res.accounts.cash.forEach(acc => {
                    cashHtml += `
                    <div class="account-row">
                        <span class="fw-bold text-dark"><i class="fas fa-money-bill-wave text-success me-2"></i>${acc.title}</span>
                        <span class="fw-bold text-success qb-mono">${fmt(acc.current_balance)}</span>
                    </div>`;
                });
            } else {
                cashHtml = '<div class="text-center py-3 text-muted small">No cash accounts found</div>';
            }
            $('#cashList').html(cashHtml);

            let bankHtml = '';
            if(res.accounts && res.accounts.bank && res.accounts.bank.length > 0) {
                res.accounts.bank.forEach(acc => {
                    bankHtml += `
                    <div class="account-row">
                        <span class="fw-bold text-dark"><i class="fas fa-university text-info me-2"></i>${acc.title}</span>
                        <span class="fw-bold text-info qb-mono">${fmt(acc.current_balance)}</span>
                    </div>`;
                });
            } else {
                bankHtml = '<div class="text-center py-3 text-muted small">No bank accounts found</div>';
            }
            $('#bankList').html(bankHtml);

            // Top Customers
            let topCustHtml = '';
            let mobCustHtml = '';
            let custCount = res.top_customers ? res.top_customers.length : 0;
            $('#customerCount').text(custCount + ' customers');

            if(res.top_customers && res.top_customers.length > 0) {
                res.top_customers.forEach((c, i) => {
                    let profitColor = c.profit >= 0 ? '#15803d' : '#b91c1c';
                    let profitSign = c.profit < 0 ? '-' : '';

                    topCustHtml += `
                    <tr>
                        <td class="text-center fw-bold text-muted" style="font-size:12px;">${i+1}</td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;">${c.name}</div>
                            <div class="text-muted small" style="font-size:10.5px;">ID: CUST-${c.id.toString().padStart(4, '0')}</div>
                        </td>
                        <td class="text-end fw-semibold qb-mono" style="font-size:13px;">Rs ${fmt(c.revenue)}</td>
                        <td class="text-end text-danger fw-semibold qb-mono" style="font-size:13px;">Rs ${fmt(c.balance)}</td>
                        <td class="text-end">
                            <span class="badge p-1.5 px-2 qb-mono" style="background: ${c.profit >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'}; color: ${profitColor}; font-size:12.5px; font-weight:800;">
                                ${profitSign}${fmt(Math.abs(c.profit))}
                            </span>
                        </td>
                    </tr>`;

                    mobCustHtml += `
                    <div class="mob-card p-2 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-light text-muted border" style="font-size:10px;">#${i+1}</span>
                                <strong class="text-dark" style="font-size:13px;">${c.name}</strong>
                            </div>
                            <span class="badge p-1 px-2 qb-mono" style="background:${c.profit >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'}; color:${profitColor}; font-size:11.5px; font-weight:700;">
                                ${profitSign}${fmt(Math.abs(c.profit))}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-muted" style="font-size:11px;">
                            <span>Revenue: <strong class="qb-mono text-dark">Rs ${fmt(c.revenue)}</strong></span>
                            <span class="text-danger">Balance: <strong class="qb-mono text-danger">Rs ${fmt(c.balance)}</strong></span>
                        </div>
                    </div>`;
                });
                $('#topCustomersList').html(topCustHtml);
                $('#mobTopCustomersContainer').html(mobCustHtml);
            } else {
                $('#topCustomersList').html('<tr><td colspan="5" class="text-center text-muted py-4 small">No customer profit data found</td></tr>');
                $('#mobTopCustomersContainer').html('<div class="text-center text-muted py-3 small">No customer profit data found</div>');
            }

            // Liquidity Index
            let totalAvailable = 0;
            if (res.accounts && res.accounts.cash) res.accounts.cash.forEach(a => totalAvailable += parseFloat(a.current_balance));
            if (res.accounts && res.accounts.bank) res.accounts.bank.forEach(a => totalAvailable += parseFloat(a.current_balance));
            
            let ratio = res.payables > 0 ? (totalAvailable / res.payables).toFixed(2) : '1.0+';
            $('#liquidityRatio').text(ratio);

        },
        error: function() {
            $('#loader').hide();
            alert('Failed to fetch executive data');
        }
    });
}

$(document).ready(function() {
    $('.btnFetchTrigger').click(fetchData);
    $('.btnPrintReport').click(() => window.print());
    fetchData();
});
</script>
@endsection
