<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpenter Slip - #CC-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
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
                padding: 0 4px 0 0 !important;
                width: 96% !important;
                max-width: 96% !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 8px 0;
            color: #000;
            font-size: 11px;
            font-weight: 400;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
        }

        .receipt-container {
            width: 100%;
            max-width: 76mm;
            margin: 0 auto;
            background: #fff;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .print-controls {
            width: 76mm;
            margin: 0 auto 10px auto;
            display: flex;
            gap: 6px;
        }

        .btn {
            flex: 1;
            padding: 8px 4px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary { background: #000; color: #fff; }
        .btn-secondary { background: #2563eb; color: #fff; }

        .company-name {
            font-size: 18px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            color: #000;
        }

        .company-info {
            font-size: 10px;
            text-align: center;
            color: #333;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .slip-banner {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-size: 10.5px;
            font-weight: bold;
            padding: 4px 6px;
            margin: 4px 0 6px 0;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 6px;
        }

        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .double-divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            padding: 3px 0;
            text-align: left;
            font-size: 9.5px;
        }

        .items-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 4px;
        }

        .summary-table td {
            padding: 2px 0;
        }

        .grand-total {
            font-size: 13.5px;
            font-weight: 800;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
        }

        .ack-text {
            font-size: 9px;
            text-align: justify;
            margin-top: 8px;
            line-height: 1.3;
        }

        .sign-row {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 4px;
            font-size: 9.5px;
        }

        .sign-box {
            width: 45%;
            border-top: 1px dotted #000;
            text-align: center;
            padding-top: 2px;
        }
    </style>
</head>
<body>

    {{-- Screen controls --}}
    <div class="print-controls no-print">
        <a href="javascript:void(0)" onclick="window.print()" class="btn btn-primary">🖨️ Print Thermal</a>
        <a href="{{ route('carpenter_commissions.receipt', $commission->id) }}" class="btn btn-secondary">📄 Standard Slip</a>
    </div>

    <div class="receipt-container">

        <!-- Company Header -->
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-info">
            @if(!empty($company['address']))
                <div>{{ $company['address'] }}</div>
            @endif
            @if(!empty($company['phone']))
                <div>Phone: {{ $company['phone'] }}</div>
            @endif
        </div>

        <div class="slip-banner">Carpenter Commission Slip</div>

        <!-- Meta info -->
        <table class="meta-table">
            <tr>
                <td style="width: 45%;"><strong>Slip #:</strong> #CC-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td style="text-align: right;"><strong>Date:</strong> {{ $commission->created_at ? $commission->created_at->format('d-m-Y') : date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Sale Inv:</strong> {{ $commission->sale->invoice_no ?? $commission->sale_id }}</td>
                <td style="text-align: right;"><strong>Status:</strong> {{ strtoupper($commission->status) }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Carpenter:</strong> {{ $commission->carpenter->name ?? 'N/A' }}
                    @if($commission->carpenter && $commission->carpenter->phone)
                        ({{ $commission->carpenter->phone }})
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Customer:</strong> {{ $commission->sale->walkin_name ?: ($commission->sale->customer_relation->customer_name ?? 'Walk-in') }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Comm Rate:</strong> 
                    @if($commission->commission_type === 'percentage')
                        {{ number_format($commission->commission_rate, 1) }}%
                    @else
                        Rs. {{ number_format($commission->commission_rate, 2) }}
                    @endif
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Line items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th style="text-align: center; width: 15%;">Qty</th>
                    <th style="text-align: right; width: 17%;">Price</th>
                    <th style="text-align: right; width: 18%;">Comm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsBreakdown as $it)
                    <tr>
                        <td colspan="4" style="padding-top: 3px; font-weight: bold;">
                            {{ $it['product_name'] }}
                            @if(!empty($it['variant_info']))
                                <div style="font-weight: normal; font-size: 8.5px; color: #444;">{{ $it['variant_info'] }}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 8.5px; color: #555;">
                            {{ number_format($it['commission_rate'], 1) }}% comm
                        </td>
                        <td style="text-align: center;">{{ number_format($it['qty'], 0) }}</td>
                        <td style="text-align: right;">{{ number_format($it['unit_price'], 0) }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($it['commission_amount'], 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Summary -->
        <table class="summary-table">
            <tr>
                <td>Total Items / Qty:</td>
                <td style="text-align: right; font-weight: bold;">{{ count($itemsBreakdown) }} / {{ number_format($totalSoldQty, 0) }} Pcs</td>
            </tr>
            <tr>
                <td>Sale Invoice Net:</td>
                <td style="text-align: right; font-weight: bold;">Rs. {{ number_format($commission->sale_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Comm. Earned:</td>
                <td style="text-align: right; font-weight: bold;">Rs. {{ number_format($commission->commission_amount, 2) }}</td>
            </tr>
            @if($totalReturnDeduction > 0)
                <tr style="color: #b91c1c;">
                    <td>Less Return Ded.:</td>
                    <td style="text-align: right; font-weight: bold;">-Rs. {{ number_format($totalReturnDeduction, 2) }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>NET COMM. PAYABLE:</td>
                <td style="text-align: right;">Rs. {{ number_format($adjustedCommission, 2) }}</td>
            </tr>
            @if($commission->status === 'paid')
                <tr>
                    <td>Paid Through:</td>
                    <td style="text-align: right; font-weight: bold;">{{ $commission->paymentAccount->title ?? 'Cash' }}</td>
                </tr>
            @endif
        </table>

        <!-- Acknowledgment -->
        <div class="ack-text">
            Received full commission of Rs. {{ number_format($adjustedCommission, 2) }} for Invoice #{{ $commission->sale->invoice_no ?? $commission->sale_id }}.
        </div>

        <!-- Signatures -->
        <div class="sign-row">
            <div class="sign-box">Shop / Cashier</div>
            <div class="sign-box">Carpenter Sign</div>
        </div>

        <div style="text-align: center; font-size: 8px; color: #777; margin-top: 10px;">
            Kent Hardware Commission Voucher
        </div>

    </div>

</body>
</html>
