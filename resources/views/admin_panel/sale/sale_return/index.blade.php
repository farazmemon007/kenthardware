@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* ==========================================================
           QUICKBOOKS POS "SALE RETURNS" PIXEL-PERFECT THEME
           Matching Sales Screen & add_sale222.blade.php Style
           ========================================================== */

        /* Universal Space Reset to Eliminate Scrolling */
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
        .odoo-page-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            padding: 0 4px !important;
        }

        /* 1. Top Window Title Bar (Navy Blue Gradient - Exact POS Title Bar) */
        .qb-win-header {
            height: 36px !important;
            min-height: 36px !important;
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
            font-size: 13.5px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #ffffff;
        }
        .qb-win-title i {
            color: #f87171;
            font-size: 14px;
        }
        .qb-win-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* QuickBooks Glossy Action Buttons */
        .qb-btn-blue {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border: 1px solid #15457a !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 12px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
        }
        .qb-btn-blue:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        .qb-btn-green {
            background: linear-gradient(180deg, #53af41 0%, #35832a 100%) !important;
            border: 1px solid #286c1f !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            padding: 3px 12px !important;
            height: 26px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: filter 0.1s ease;
        }
        .qb-btn-green:hover {
            filter: brightness(1.1);
            color: #ffffff !important;
        }

        /* 2. Sub-Header Ribbon: Financial KPIs & Totals */
        .qb-sub-ribbon {
            background: linear-gradient(180deg, #edf3fa 0%, #d4e0ee 100%) !important;
            border: 1px solid #b2c5dc;
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 5px !important;
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
            gap: 6px;
            flex-wrap: wrap;
        }
        .qb-kpi-chip {
            background: #ffffff;
            border: 1px solid #8ca4c3;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            line-height: 1.2;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .qb-kpi-chip .chip-label {
            font-weight: 700;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
        }
        .qb-kpi-chip .chip-val {
            font-weight: 800;
            font-family: Consolas, monospace;
        }

        /* 3. Table Card (Exact Replica of Sales Screen Table) */
        .qb-table-card {
            background: #ffffff;
            border: 1px solid #aebfd4;
            border-radius: 4px;
            padding: 4px 6px 6px 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .table-responsive {
            border: 1px solid #aebfd4 !important;
            border-radius: 3px !important;
            background-color: #ffffff;
            overflow-x: auto;
            min-height: 0 !important;
        }
        .qb-table {
            margin-bottom: 0 !important;
            font-size: 11.5px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Table Headers (Soft Blue-Gray Gradient with Dividers) */
        .qb-table thead th {
            background: linear-gradient(180deg, #f7f9fc 0%, #e2eaf3 100%) !important;
            color: #193452 !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 10.5px !important;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #aebfd4 !important;
            border-right: 1px solid #c9d7e5 !important;
            border-top: none !important;
            padding: 6px 8px !important;
            white-space: nowrap !important;
        }
        .qb-table thead th:last-child {
            border-right: none !important;
        }

        /* Remove Broken DataTables Sort Glyphs [][ ] */
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:before,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc_disabled:after,
        .dataTables_wrapper .dataTable thead .sorting:before,
        .dataTables_wrapper .dataTable thead .sorting:after,
        .dataTables_wrapper .dataTable thead .sorting_asc:before,
        .dataTables_wrapper .dataTable thead .sorting_asc:after,
        .dataTables_wrapper .dataTable thead .sorting_desc:before,
        .dataTables_wrapper .dataTable thead .sorting_desc:after,
        .qb-table thead th:before,
        .qb-table thead th:after {
            display: none !important;
            content: "" !important;
        }
        table.dataTable thead th,
        table.dataTable thead td {
            background-image: none !important;
        }

        /* Table Body Cells */
        .qb-table tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #edf2f7 !important;
            border-top: none !important;
            padding: 5px 8px !important;
            font-size: 11.5px !important;
            color: #1a202c !important;
            vertical-align: middle;
        }
        .qb-table tbody td:last-child {
            border-right: none !important;
        }

        /* ==========================================================
           ROW CURSOR HOVER: PASTEL MINT GREEN (Exact Sale Screen Replica)
           ========================================================== */
        .qb-table tbody tr {
            cursor: pointer;
            transition: background-color 0.1s ease-in-out;
        }
        .qb-table tbody tr:hover td,
        .qb-table tbody tr:hover th {
            background-color: #d5eed7 !important;
            color: #111827 !important;
        }
        .qb-table tbody tr:hover td small,
        .qb-table tbody tr:hover td .text-muted {
            color: #2e4a32 !important;
        }
        .qb-table tbody tr.qb-active-row td {
            background-color: #d5eed7 !important;
            color: #111827 !important;
        }

        .qb-table td.text-nowrap,
        .qb-table th.text-nowrap,
        .qb-table .font-monospace {
            white-space: nowrap !important;
        }

        /* Action View Button (Mini Tool Button) */
        .odoo-action-btn {
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%) !important;
            border: 1px solid #8ca4c3 !important;
            color: #18385e !important;
            font-weight: 700 !important;
            border-radius: 3px !important;
            height: 22px !important;
            padding: 0 8px !important;
            font-size: 11px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
            transition: all 0.12s ease;
            text-decoration: none !important;
        }
        .odoo-action-btn:hover {
            background: #dbeafe !important;
            border-color: #2563eb !important;
            color: #1d4ed8 !important;
        }

        /* Customer Initials Avatar */
        .avatar-circle {
            width: 22px !important;
            height: 22px !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            border-radius: 50% !important;
        }

        /* DataTables Controls (Clean QuickBooks Style) */
        .dataTables_wrapper .dataTables_length {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 22px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 10.5px !important;
            padding: 0 4px !important;
            margin: 0 4px !important;
        }
        .dataTables_wrapper .dataTables_filter {
            font-size: 11px !important;
            color: #475569 !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 22px !important;
            border: 1px solid #8ca4c3 !important;
            border-radius: 3px !important;
            font-size: 10.5px !important;
            padding: 0 6px !important;
            margin-left: 4px !important;
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 11px !important;
            color: #475569 !important;
            padding-top: 4px !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 4px !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            color: #193452 !important;
            border: 1px solid #b2c5dc !important;
            padding: 2px 7px !important;
            font-size: 11px !important;
            border-radius: 3px !important;
            margin: 0 1px !important;
            background: linear-gradient(180deg, #ffffff 0%, #edf3fa 100%);
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background: linear-gradient(180deg, #5ea5ee 0%, #3077c1 48%, #2061a9 52%, #195293 100%) !important;
            border-color: #15457a !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid odoo-page-wrapper">

                {{-- Row 1: Top Window Title Bar (Navy Blue POS Replica) --}}
                <div class="qb-win-header">
                    <div class="qb-win-title">
                        <i class="fas fa-undo-alt"></i>
                        <span>Sale Returns</span>
                        <span class="qb-win-sub d-none d-sm-inline">/ Return History</span>
                    </div>

                    {{-- Top Action Buttons --}}
                    <div class="d-flex align-items-center gap-1">
                        <a class="qb-btn-blue" href="{{ route('sale.index') }}" title="Back to Sales">
                            <i class="fas fa-arrow-left"></i> Back to Sales
                        </a>
                        @can('sales.create')
                            <a class="qb-btn-green" href="{{ route('sale.add') }}" title="New Sale">
                                <i class="fas fa-plus"></i> + New Sale (F1)
                            </a>
                        @endcan
                    </div>
                </div>

                {{-- Row 2: Financial KPI Summary Ribbon --}}
                <div class="qb-sub-ribbon">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-dark font-monospace" style="font-size: 11px;">
                            <i class="fas fa-list text-muted me-1"></i> Total Records: <strong>{{ $returns->count() }}</strong>
                        </span>
                    </div>

                    <div class="qb-kpi-group">
                        <div class="qb-kpi-chip" title="Total Returns Count">
                            <span class="chip-label">Returns:</span>
                            <span class="chip-val text-danger">{{ $returns->count() }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Total Net Refund Amount">
                            <span class="chip-label">Total Refunded:</span>
                            <span class="chip-val text-danger">Rs. {{ number_format($returns->sum('net_amount'), 2) }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Full Returns">
                            <span class="chip-label">Full:</span>
                            <span class="chip-val text-danger">{{ $returns->filter(fn($r) => $r->sale && $r->total_returned >= $r->original_net_amount)->count() }}</span>
                        </div>
                        <div class="qb-kpi-chip" title="Partial Returns">
                            <span class="chip-label">Partial:</span>
                            <span class="chip-val text-warning">{{ $returns->filter(fn($r) => $r->sale && $r->total_returned < $r->original_net_amount)->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Return Table Card --}}
                <div class="qb-table-card">
                    <div class="table-responsive">
                        <table id="return-table" class="table table-hover align-middle qb-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="ps-2">ID</th>
                                    <th>Return Inv#</th>
                                    <th>Customer</th>
                                    <th>Warehouse</th>
                                    <th>Return Date</th>
                                    <th class="text-end">Return Amount</th>
                                    <th class="text-end">Original Net</th>
                                    <th class="text-end">Total Returned</th>
                                    <th class="text-end">New Net</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pe-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returns as $return)
                                    @php
                                        $isPartialReturn =
                                            $return->sale &&
                                            $return->total_returned < $return->original_net_amount;
                                        $isFullReturn =
                                            $return->sale &&
                                            $return->total_returned >= $return->original_net_amount;

                                        $isWalkin = empty($return->customer_id) && !empty($return->sale->walkin_name);
                                        $custName = optional($return->customer)->customer_name ?: (optional($return->sale)->walkin_name ?: 'Walk-in Customer');
                                        $initial = strtoupper(substr($custName ?: 'W', 0, 1));
                                    @endphp
                                    <tr>
                                        <td class="ps-2 font-monospace text-muted">#{{ $return->id }}</td>
                                        <td class="font-monospace text-nowrap">
                                            <span class="text-danger fw-bold">{{ $return->return_invoice ?? '#'.$return->id }}</span>
                                            @if ($return->sale)
                                                <div class="text-muted small" style="font-size: 10px;">
                                                    Orig: <a href="{{ route('sales.invoice', $return->sale->id) }}" target="_blank" class="text-primary text-decoration-none fw-bold">{{ $return->sale->invoice_no }}</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-nowrap" style="min-width: 150px;">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle {{ $isWalkin ? 'bg-secondary-subtle text-secondary border' : 'bg-primary-subtle text-primary border border-primary-subtle' }} me-2 fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                                    {{ $initial }}
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark d-block" style="font-size: 12px; line-height: 1.2;">{{ $custName }}</span>
                                                    @if($isWalkin)
                                                        <span class="badge bg-light text-secondary border font-monospace py-0 px-1" style="font-size: 9px;">Walk-in</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <span class="badge bg-light text-dark border font-monospace">{{ $return->warehouse->warehouse_name ?? 'Default' }}</span>
                                        </td>
                                        <td class="text-nowrap font-monospace text-muted small">
                                            {{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}
                                        </td>

                                        {{-- Return Amount --}}
                                        <td class="text-end fw-bold text-danger font-monospace text-nowrap">
                                            -Rs. {{ number_format($return->net_amount, 2) }}
                                        </td>

                                        {{-- Original Purchase Amount --}}
                                        <td class="text-end text-dark font-monospace text-nowrap">
                                            @if ($return->sale)
                                                Rs. {{ number_format($return->original_net_amount, 2) }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        {{-- Total Returned --}}
                                        <td class="text-end text-danger font-monospace text-nowrap">
                                            @if ($return->sale)
                                                Rs. {{ number_format($return->total_returned, 2) }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        {{-- New Net Amount --}}
                                        <td class="text-end text-success fw-bold font-monospace text-nowrap">
                                            @if ($return->sale)
                                                Rs. {{ number_format($return->new_net_amount, 2) }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        {{-- Status Badge --}}
                                        <td class="text-center text-nowrap">
                                            @if ($isFullReturn)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="fas fa-undo-alt me-1"></i>Full Return</span>
                                            @elseif($isPartialReturn)
                                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1"><i class="fas fa-adjust me-1"></i>Partial Return</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">Standalone</span>
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td class="text-center pe-2 text-nowrap">
                                            <a href="{{ route('sale.return.view', $return->id) }}"
                                                class="odoo-action-btn" title="View Return Details">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize DataTable in QuickBooks Style
            if ($.fn.DataTable) {
                $('#return-table').DataTable({
                    "pageLength": 10,
                    "order": [[0, 'desc']],
                    "columnDefs": [
                        { "orderable": false, "targets": [10] } // Action column not sortable
                    ],
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search returns...",
                        "lengthMenu": "Show _MENU_"
                    },
                    "dom": "<'d-flex justify-content-between align-items-center mb-1'<'d-flex align-items-center gap-1 font-monospace'l><'d-flex align-items-center'f>>" +
                        "<'row'<'col-12'tr>>" +
                        "<'d-flex justify-content-between align-items-center mt-1'<'text-muted small'i><'d-flex justify-content-end'p>>",
                });
            }

            // Click row to activate green highlight (like POS / Sale Screen)
            $(document).on('click', '#return-table tbody tr', function(e) {
                if ($(e.target).closest('a, button').length > 0) return;
                $('#return-table tbody tr').removeClass('qb-active-row');
                $(this).addClass('qb-active-row');
            });
        });
    </script>
@endsection
