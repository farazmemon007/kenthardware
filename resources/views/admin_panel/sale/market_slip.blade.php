<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Purchase Slip - {{ $sale->invoice_no }}</title>
    <style>
        /* Base Print Styles */
        @media print {
            @page {
                margin: 2mm 3mm 2mm 2mm !important;
            }
            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #000 !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            tr.item-excluded {
                display: none !important;
            }
            .screen-only {
                display: none !important;
            }
            .print-checkbox {
                display: inline-block !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 15px 0;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
        }

        .receipt-container {
            width: 80mm; /* Standard 80mm thermal paper width */
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Typography */
        h1, h2, h3, p {
            margin: 0;
            padding: 0;
        }

        .company-name {
            font-size: 17px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            color: #000;
        }

        .company-info {
            font-size: 10.5px;
            text-align: center;
            color: #333;
            line-height: 1.35;
        }

        .slip-title-box {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-size: 12px;
            font-weight: 800;
            padding: 4px 6px;
            margin: 6px 0 2px 0;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .slip-subtitle {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            color: #444;
            margin-bottom: 4px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .divider-thick {
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        /* Header Details */
        .meta-info {
            font-size: 10.5px;
            margin-bottom: 2px;
            display: flex;
            justify-content: space-between;
        }

        .remarks-box {
            font-size: 10px;
            background: #f8fafc;
            border-left: 2px solid #000;
            padding: 3px 6px;
            margin: 4px 0;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 4px;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            padding: 4px 2px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 5px 2px;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
        }

        .item-name {
            font-weight: bold;
            font-size: 11px;
            line-height: 1.25;
        }

        .item-meta {
            font-size: 10px;
            color: #333;
            margin-top: 1px;
        }

        .text-end { text-align: right !important; }
        .text-center { text-align: center !important; }

        .print-box-tick {
            width: 13px;
            height: 13px;
            border: 1.5px solid #000;
            display: inline-block;
            margin-right: 2px;
            vertical-align: middle;
        }

        /* Summary */
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin: 6px 0;
        }

        /* Signatures */
        .sign-box {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            font-size: 9.5px;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #000;
            width: 90px;
            padding-top: 2px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 14px;
            padding-top: 6px;
            border-top: 1px dashed #bbb;
            line-height: 1.35;
        }

        /* Controls */
        .print-controls {
            width: 80mm;
            margin: 0 auto 12px auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-row {
            display: flex;
            gap: 8px;
        }

        .btn {
            flex: 1;
            padding: 8px 10px;
            text-align: center;
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-primary { background: #000; color: #fff; }
        .btn-primary:hover { background: #222; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        .btn-info { background: #0284c7; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }

        .filter-controls {
            background: #fff;
            padding: 8px 10px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-btn-group {
            display: inline-flex;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
        }

        .filter-btn {
            padding: 3px 8px;
            font-size: 10.5px;
            border: none;
            background: #f8fafc;
            color: #475569;
            cursor: pointer;
            text-decoration: none;
        }

        .filter-btn.active {
            background: #0284c7;
            color: #fff;
            font-weight: bold;
        }

        .alert-box {
            padding: 8px;
            border-radius: 4px;
            font-size: 10.5px;
            margin: 6px 0;
            text-align: center;
        }
        .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    </style>
</head>

<body>

    <!-- On-screen Controls (hidden in print) -->
    <div class="print-controls no-print">
        <div class="btn-row">
            <button type="button" onclick="printReceipt()" class="btn btn-primary">
                🖨️ Print Slip
            </button>
            <a href="{{ route('sales.dc_thermal', $sale->id) }}" class="btn btn-info" title="View Delivery Challan (DC Thermal)">
                🚚 DC Thermal
            </a>
            <button type="button" onclick="handleGoBack()" class="btn btn-secondary">
                ← Back
            </button>
        </div>

        <div class="filter-controls">
            <span><strong>Filter View:</strong></span>
            <div class="filter-btn-group">
                <button type="button" id="btnFilterShortage" class="filter-btn active" onclick="setFilter('shortage')">
                    Shortage ({{ $items->where('is_shortage', true)->count() }})
                </button>
                <button type="button" id="btnFilterAll" class="filter-btn" onclick="setFilter('all')">
                    All Items ({{ $items->count() }})
                </button>
            </div>
        </div>

        <div style="font-size: 10.5px; color: #64748b; text-align: center; background: #fff; padding: 4px; border-radius: 4px;">
            💡 Uncheck items you don't need before printing.
        </div>
    </div>

    <!-- 80mm Thermal Receipt Container -->
    <div class="receipt-container">
        <!-- Header -->
        <div class="company-name">{{ \App\Models\Setting::get('company_name', 'KENT HARDWARE') }}</div>
        <div class="company-info">
            <div>{{ \App\Models\Setting::get('company_address', 'Main Market') }}</div>
            <div>Ph: {{ \App\Models\Setting::get('company_phone', '0300-0000000') }}</div>
        </div>

        <div class="slip-title-box">LOCAL MARKET PURCHASE SLIP</div>
        <div class="slip-subtitle">لوکل مارکیٹ پرچیز / ڈیمانڈ سلپ</div>

        <div class="divider"></div>

        <!-- Meta Details -->
        <div class="meta-info">
            <span><strong>Slip #:</strong> MS-{{ $sale->invoice_no }}</span>
            <span><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y h:i A') }}</span>
        </div>
        <div class="meta-info">
            <span><strong>Inv Ref:</strong> {{ $sale->invoice_no }}</span>
            <span><strong>Total Items:</strong> <span id="summaryItemCount">0</span></span>
        </div>
        <div class="meta-info">
            <span><strong>Customer:</strong> {{ Str::limit($sale->walkin_name ?? ($sale->customer_relation->customer_name ?? 'Walk-in Customer'), 20) }}</span>
        </div>

        @if($sale->reference)
        <div class="remarks-box">
            <strong>Ref/Note:</strong> {{ $sale->reference }}
        </div>
        @endif

        <div class="divider"></div>

        @php
            $shortageCount = $items->where('is_shortage', true)->count();
        @endphp

        <!-- Items Table -->
        <table class="items-table" id="itemsTable">
            <thead>
                <tr>
                    <th style="width: 8%;" class="screen-only text-center">
                        <input type="checkbox" id="checkAllRows" checked title="Select/Deselect All" onchange="toggleSelectAll(this)">
                    </th>
                    <th style="width: 8%;" class="text-center">#</th>
                    <th style="width: 58%;">Item & Variant Details</th>
                    <th style="width: 26%;" class="text-end">Qty To Buy</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $idx => $item)
                    @php
                        $isShortage = $item->is_shortage;
                        $shortageQty = $item->shortage_qty;
                    @endphp
                    <tr class="item-row {{ $isShortage ? 'is-shortage-row' : 'is-normal-row' }}" data-is-shortage="{{ $isShortage ? '1' : '0' }}" data-shortage-qty="{{ $shortageQty }}" data-sold-qty="{{ $item->sold_pieces }}">
                        <td class="screen-only text-center" style="vertical-align: middle;">
                            <input type="checkbox" class="row-checkbox" checked onchange="updateSummaryCounts()">
                        </td>
                        <td class="text-center" style="vertical-align: top;">
                            <span class="row-num">{{ $loop->iteration }}</span>
                            <span class="print-box-tick print-checkbox" style="display: none;"></span>
                        </td>
                        <td class="item-name">
                            <div>{{ $item->item_name }}</div>
                            @if (!empty($item->variant_display))
                                <div class="item-meta">
                                    {{ $item->variant_display }}
                                </div>
                            @endif
                            @if (!empty($item->item_code))
                                <div class="item-meta" style="color: #666; font-size: 9px;">
                                    Code: {{ $item->item_code }}
                                </div>
                            @endif
                        </td>
                        <td class="text-end font-weight-bold" style="vertical-align: top; font-size: 11.5px; font-weight: 700;">
                            <span class="item-qty-text">{{ $isShortage ? $item->shortage_display : $item->sold_display }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">
                            No items found in this sale.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Notice if no shortage items -->
        <div id="noShortageAlert" class="alert-box alert-info" style="{{ $shortageCount > 0 ? 'display: none;' : '' }}">
            <i class="fas fa-check-circle me-1"></i> All items have sufficient inventory. None required from market.
        </div>

        <!-- Summary -->
        <div class="summary-row">
            <span>TOTAL ITEMS TO PURCHASE:</span>
            <span id="summaryTotalUnits">0 Units</span>
        </div>

        <!-- Signatures -->
        <div class="sign-box">
            <div>
                <div class="sign-line">Purchased By</div>
            </div>
            <div>
                <div class="sign-line">Received / Checked By</div>
            </div>
        </div>

        <!-- Footer Instructions -->
        <div class="footer">
            <div>⚠️ <strong>نوٹ:</strong> مارکیٹ سے سامان لاتے وقت سائز اور ویریئنٹ ضرور چیک کریں۔</div>
            <div style="margin-top: 3px;">Powered by <strong>Prowave Technologies</strong> | 📞 +92 317 3836 223</div>
        </div>
    </div>

    <script>
        let currentFilter = "{{ $shortageCount > 0 ? 'shortage' : 'all' }}";

        function setFilter(filterType) {
            currentFilter = filterType;
            const btnShortage = document.getElementById('btnFilterShortage');
            const btnAll = document.getElementById('btnFilterAll');
            const rows = document.querySelectorAll('.item-row');
            const noShortageAlert = document.getElementById('noShortageAlert');

            if (filterType === 'shortage') {
                btnShortage.classList.add('active');
                btnAll.classList.remove('active');

                let visibleCount = 0;
                rows.forEach(r => {
                    if (r.dataset.isShortage === '1') {
                        r.style.display = '';
                        visibleCount++;
                    } else {
                        r.style.display = 'none';
                    }
                });

                if (noShortageAlert) {
                    noShortageAlert.style.display = (visibleCount === 0) ? 'block' : 'none';
                }
            } else {
                btnAll.classList.add('active');
                btnShortage.classList.remove('active');

                rows.forEach(r => {
                    r.style.display = '';
                });

                if (noShortageAlert) {
                    noShortageAlert.style.display = 'none';
                }
            }

            renumberRows();
            updateSummaryCounts();
        }

        function toggleSelectAll(masterCb) {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(r => {
                if (r.style.display !== 'none') {
                    const cb = r.querySelector('.row-checkbox');
                    if (cb) cb.checked = masterCb.checked;
                }
            });
            updateSummaryCounts();
        }

        function renumberRows() {
            let num = 1;
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(r => {
                if (r.style.display !== 'none') {
                    const numSpan = r.querySelector('.row-num');
                    if (numSpan) numSpan.textContent = num++;
                }
            });
        }

        function updateSummaryCounts() {
            const rows = document.querySelectorAll('.item-row');
            let selectedItemsCount = 0;
            let totalUnits = 0;

            rows.forEach(r => {
                const cb = r.querySelector('.row-checkbox');
                const isVisible = r.style.display !== 'none';
                if (isVisible && cb && cb.checked) {
                    selectedItemsCount++;
                    const qtyVal = parseFloat(currentFilter === 'shortage' ? r.dataset.shortageQty : r.dataset.soldQty) || 0;
                    totalUnits += qtyVal;
                    r.classList.remove('item-excluded');
                } else {
                    r.classList.add('item-excluded');
                }
            });

            const summaryItemCount = document.getElementById('summaryItemCount');
            const summaryTotalUnits = document.getElementById('summaryTotalUnits');
            if (summaryItemCount) summaryItemCount.textContent = selectedItemsCount;
            if (summaryTotalUnits) summaryTotalUnits.textContent = totalUnits + ' Units';
        }

        function printReceipt() {
            updateSummaryCounts();
            window.print();
        }

        function handleGoBack() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('from') === 'pos' || (document.referrer && document.referrer.indexOf('/pos') !== -1)) {
                window.location.href = "{{ route('pos.index') }}";
                return;
            }

            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }

            if (window.history.length > 1 && document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                window.history.back();
                return;
            }

            window.location.href = "{{ route('sale.index') }}";
        }

        // Initialize display
        document.addEventListener('DOMContentLoaded', function() {
            setFilter(currentFilter);
        });
    </script>
</body>

</html>
