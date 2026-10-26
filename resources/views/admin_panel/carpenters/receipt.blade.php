<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpenter Commission Voucher - #CC-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --voucher-primary: #1e3a8a;
            --voucher-accent: #2563eb;
            --voucher-light: #eff6ff;
            --voucher-border: #cbd5e1;
            --voucher-dark: #0f172a;
        }

        body {
            background-color: #f1f5f9;
            color: #0f172a;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .action-bar {
            background: #ffffff;
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .voucher-page {
            max-width: 210mm;
            min-height: 297mm;
            margin: 24px auto;
            background: #ffffff;
            padding: 28px 32px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            position: relative;
            border-radius: 6px;
        }

        .voucher-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--voucher-primary);
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .company-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--voucher-primary);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .company-subtitle {
            font-size: 12px;
            color: #475569;
            line-height: 1.4;
        }

        .voucher-badge-box {
            text-align: right;
        }

        .voucher-title-pill {
            display: inline-block;
            background: var(--voucher-primary);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 6px 14px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .voucher-id-text {
            font-size: 14px;
            font-weight: 800;
            font-family: monospace;
            color: #0f172a;
        }

        .meta-card {
            background: #f8fafc;
            border: 1px solid var(--voucher-border);
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }

        .meta-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .table-voucher {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 18px;
        }

        .table-voucher thead th {
            background-color: var(--voucher-light);
            color: var(--voucher-primary);
            border-top: 1px solid var(--voucher-border);
            border-bottom: 2px solid var(--voucher-border);
            font-weight: 700;
            font-size: 11.5px;
            padding: 8px 10px;
            text-transform: uppercase;
        }

        .table-voucher tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .table-voucher tbody tr:nth-child(even) {
            background-color: #fafbfd;
        }

        .table-voucher tfoot td {
            padding: 10px;
            border-top: 2px solid var(--voucher-primary);
            font-weight: 700;
        }

        .summary-card {
            background: #f8fafc;
            border: 1px solid var(--voucher-border);
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 22px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 12.5px;
        }

        .summary-row.total-row {
            border-top: 2px dashed #cbd5e1;
            margin-top: 6px;
            padding-top: 8px;
            font-size: 15px;
            font-weight: 800;
            color: var(--voucher-primary);
        }

        .signatures-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            border-top: 1px solid #475569;
            padding-top: 6px;
            font-size: 11.5px;
            color: #334155;
            font-weight: 600;
        }

        .carpenter-ack-box {
            background: #fefce8;
            border: 1px dashed #ca8a04;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 12px;
            color: #713f12;
            margin-top: 18px;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .action-bar, .no-print {
                display: none !important;
            }
            .voucher-page {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 12mm 15mm !important;
                max-width: 100% !important;
                min-height: auto !important;
                border: none !important;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Bar (Screen Only) --}}
    <div class="action-bar no-print">
        <div class="d-flex justify-content-between align-items-center max-w-container" style="max-width: 210mm; margin: 0 auto;">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('carpenter_commissions.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Commissions
                </a>
                <span class="text-muted">|</span>
                <span class="fw-bold text-dark">
                    <i class="fas fa-hammer text-primary me-1"></i> {{ $commission->carpenter->name ?? 'Carpenter' }}
                </span>
                <span class="badge bg-secondary font-monospace">Slip #CC-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('carpenter_commissions.thermal', $commission->id) }}" class="btn btn-sm btn-outline-dark" target="_blank" title="Switch to 80mm POS Thermal Slip">
                    <i class="fas fa-receipt me-1"></i> Thermal (80mm) Slip
                </a>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-primary fw-bold px-3">
                    <i class="fas fa-print me-1"></i> Print Voucher (A4/A5)
                </button>
            </div>
        </div>
    </div>

    {{-- Voucher Main Printable Page --}}
    <div class="voucher-page">

        {{-- Header --}}
        <div class="voucher-header">
            <div>
                <div class="company-title">{{ $company['name'] }}</div>
                <div class="company-subtitle">
                    @if(!empty($company['address']))
                        <div><i class="fas fa-map-marker-alt me-1 text-secondary"></i> {{ $company['address'] }}</div>
                    @endif
                    @if(!empty($company['phone']))
                        <div><i class="fas fa-phone me-1 text-secondary"></i> {{ $company['phone'] }}</div>
                    @endif
                    <div><i class="fas fa-file-invoice-dollar me-1 text-secondary"></i> Carpenter Commission & Sales Record</div>
                </div>
            </div>
            <div class="voucher-badge-box">
                <div class="voucher-title-pill">Commission Voucher</div>
                <div class="voucher-id-text">#CC-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="mt-1">
                    @if($commission->status === 'paid')
                        <span class="badge bg-success px-2 py-1"><i class="fas fa-check-double me-1"></i> PAID</span>
                    @elseif($commission->status === 'approved')
                        <span class="badge bg-info text-white px-2 py-1"><i class="fas fa-check me-1"></i> APPROVED</span>
                    @elseif($commission->status === 'pending')
                        <span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-hourglass-half me-1"></i> PENDING APPROVAL</span>
                    @else
                        <span class="badge bg-secondary px-2 py-1">{{ strtoupper($commission->status) }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Metadata Row --}}
        <div class="meta-card">
            <div class="row g-3">
                <div class="col-sm-3 border-end">
                    <div class="meta-label">Carpenter Details</div>
                    <div class="meta-value">{{ $commission->carpenter->name ?? 'N/A' }}</div>
                    <small class="text-muted d-block">{{ $commission->carpenter->phone ?? 'No Phone' }}</small>
                    @if(!empty($commission->carpenter->cnic))
                        <small class="text-muted font-monospace">CNIC: {{ $commission->carpenter->cnic }}</small>
                    @endif
                </div>
                <div class="col-sm-3 border-end">
                    <div class="meta-label">Sales Invoice</div>
                    <div class="meta-value font-monospace text-primary">
                        {{ $commission->sale->invoice_no ?? ('Sale #' . $commission->sale_id) }}
                    </div>
                    <small class="text-muted d-block">
                        {{ $commission->sale && $commission->sale->created_at ? $commission->sale->created_at->format('d-M-Y h:i A') : '-' }}
                    </small>
                </div>
                <div class="col-sm-3 border-end">
                    <div class="meta-label">Customer</div>
                    <div class="meta-value">
                        {{ $commission->sale->walkin_name ?: ($commission->sale->customer_relation->customer_name ?? 'Walk-in Customer') }}
                    </div>
                    <small class="text-muted d-block">
                        {{ $commission->sale->walkin_phone ?: ($commission->sale->customer_relation->phone ?? '-') }}
                    </small>
                </div>
                <div class="col-sm-3">
                    <div class="meta-label">Commission Rate</div>
                    <div class="meta-value text-success font-monospace">
                        @if($commission->commission_type === 'percentage')
                            {{ number_format($commission->commission_rate, 1) }}% of Sales
                        @else
                            Rs. {{ number_format($commission->commission_rate, 2) }} Fixed
                        @endif
                    </div>
                    <small class="text-muted d-block">
                        Voucher Date: {{ $commission->created_at ? $commission->created_at->format('d-M-Y') : date('d-M-Y') }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Product-by-Product Commission Table --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 13px;">
                <i class="fas fa-boxes text-primary me-1"></i> Product-Wise Breakdown & Commission Details
            </h6>
            <small class="text-muted">Item sold price & commission breakdown</small>
        </div>

        <table class="table-voucher">
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">#</th>
                    <th>Product & Description</th>
                    <th class="text-center" style="width: 80px;">Qty Sold</th>
                    <th class="text-end" style="width: 110px;">Unit Price (Rs.)</th>
                    <th class="text-end" style="width: 110px;">Line Total (Rs.)</th>
                    <th class="text-center" style="width: 90px;">Comm. Rate</th>
                    <th class="text-end" style="width: 120px;">Commission (Rs.)</th>
                    <th class="text-end" style="width: 110px;">Shop Net (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemsBreakdown as $idx => $it)
                    <tr>
                        <td class="text-center text-muted font-monospace">{{ $idx + 1 }}</td>
                        <td>
                            <strong class="text-dark">{{ $it['product_name'] }}</strong>
                            @if(!empty($it['brand']))
                                <span class="badge bg-light text-secondary border ms-1">{{ $it['brand'] }}</span>
                            @endif
                            @if(!empty($it['product_code']))
                                <small class="text-muted font-monospace d-block">Code: {{ $it['product_code'] }}</small>
                            @endif
                            @if(!empty($it['variant_info']))
                                <small class="text-primary d-block">{{ $it['variant_info'] }}</small>
                            @endif
                        </td>
                        <td class="text-center font-monospace">
                            {{ number_format($it['qty'], ($it['qty'] == (int)$it['qty'] ? 0 : 2)) }} {{ $it['unit'] }}
                        </td>
                        <td class="text-end font-monospace text-dark">
                            {{ number_format($it['unit_price'], 2) }}
                        </td>
                        <td class="text-end font-monospace text-dark fw-semibold">
                            {{ number_format($it['line_total'], 2) }}
                        </td>
                        <td class="text-center font-monospace">
                            <span class="badge bg-light text-dark border">
                                {{ number_format($it['commission_rate'], 1) }}%
                            </span>
                        </td>
                        <td class="text-end font-monospace fw-bold text-success">
                            Rs. {{ number_format($it['commission_amount'], 2) }}
                        </td>
                        <td class="text-end font-monospace text-muted">
                            {{ number_format($it['net_amount'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-3 text-muted">No line items recorded for this sale.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="2" class="fw-bold">Total Sold Items: {{ count($itemsBreakdown) }}</td>
                    <td class="text-center font-monospace fw-bold">{{ number_format($totalSoldQty, 0) }} Pcs</td>
                    <td class="text-end text-muted">-</td>
                    <td class="text-end font-monospace fw-bold">Rs. {{ number_format($commission->sale_amount, 2) }}</td>
                    <td class="text-center font-monospace text-muted">-</td>
                    <td class="text-end font-monospace fw-bold text-success" style="font-size: 13.5px;">
                        Rs. {{ number_format($commission->commission_amount, 2) }}
                    </td>
                    <td class="text-end font-monospace fw-bold text-dark">
                        Rs. {{ number_format(max(0, (float)$commission->sale_amount - (float)$commission->commission_amount), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Return Adjustments Section (If any returns exist for this invoice) --}}
        @if(count($returnsBreakdown) > 0)
            <div class="mb-3 p-3 rounded border border-warning" style="background-color: #fffbeb;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 12.5px;">
                        <i class="fas fa-undo-alt text-warning me-1"></i> Sale Return Deductions / Adjustments
                    </h6>
                    <span class="badge bg-warning text-dark font-monospace">
                        -{{ number_format($commission->commission_rate, 1) }}% Commission Withheld
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white mb-1" style="font-size: 11.5px;">
                        <thead class="table-light">
                            <tr>
                                <th>Return Invoice #</th>
                                <th>Return Date</th>
                                <th class="text-center">Returned Qty</th>
                                <th class="text-end">Customer Refund Paid (Rs.)</th>
                                <th class="text-center">Deduction %</th>
                                <th class="text-end text-danger">Commission Reversed (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returnsBreakdown as $r)
                                <tr>
                                    <td class="font-monospace fw-bold">{{ $r['return_invoice'] }}</td>
                                    <td>{{ $r['return_date'] ? \Carbon\Carbon::parse($r['return_date'])->format('d-M-Y') : '-' }}</td>
                                    <td class="text-center font-monospace">{{ number_format($r['returned_qty'], 0) }} Pcs</td>
                                    <td class="text-end font-monospace">{{ number_format($r['net_amount'], 2) }}</td>
                                    <td class="text-center font-monospace">{{ number_format($r['rate'], 1) }}%</td>
                                    <td class="text-end font-monospace fw-bold text-danger">-Rs. {{ number_format($r['deduction_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted d-block mt-1 fst-italic">
                    <i class="fas fa-info-circle me-1"></i> Notice: When customer returned items, the commission percentage was deducted from their refund. That reversed amount is subtracted from the carpenter's payable commission below.
                </small>
            </div>
        @endif

        {{-- Summary & Net Payable Section --}}
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="meta-card h-100">
                    <div class="meta-label">Payment & Approval Status</div>
                    <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                        <tr>
                            <td class="text-muted ps-0">Status:</td>
                            <td class="fw-bold text-end">
                                @if($commission->status === 'paid')
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i> Paid in Full</span>
                                @elseif($commission->status === 'approved')
                                    <span class="text-primary"><i class="fas fa-check me-1"></i> Approved (Pending Cash/Bank Release)</span>
                                @elseif($commission->status === 'pending')
                                    <span class="text-warning"><i class="fas fa-clock me-1"></i> Awaiting Admin Approval</span>
                                @else
                                    <span>{{ ucfirst($commission->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($commission->approver)
                            <tr>
                                <td class="text-muted ps-0">Approved By:</td>
                                <td class="fw-semibold text-end">{{ $commission->approver->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Approved At:</td>
                                <td class="text-end text-muted font-monospace">{{ $commission->approved_at ? $commission->approved_at->format('d-M-Y h:i A') : '-' }}</td>
                            </tr>
                        @endif
                        @if($commission->status === 'paid')
                            <tr>
                                <td class="text-muted ps-0">Paid Through:</td>
                                <td class="fw-bold text-end text-success">{{ $commission->paymentAccount->title ?? 'Cash/Bank' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Paid By:</td>
                                <td class="fw-semibold text-end">{{ $commission->payer->name ?? 'Staff' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Paid At:</td>
                                <td class="text-end text-muted font-monospace">{{ $commission->paid_at ? $commission->paid_at->format('d-M-Y h:i A') : '-' }}</td>
                            </tr>
                        @endif
                        @if(!empty($commission->notes))
                            <tr>
                                <td class="text-muted ps-0 align-top">Notes:</td>
                                <td class="text-end text-muted fst-italic">{{ $commission->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="summary-card h-100">
                    <div class="summary-row">
                        <span class="text-muted">Total Sales Invoice Net:</span>
                        <strong class="font-monospace text-dark">Rs. {{ number_format($commission->sale_amount, 2) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted">Original Commission Earned:</span>
                        <strong class="font-monospace text-success">Rs. {{ number_format($commission->commission_amount, 2) }}</strong>
                    </div>
                    @if($totalReturnDeduction > 0)
                        <div class="summary-row">
                            <span class="text-danger">Less Return Reversal:</span>
                            <strong class="font-monospace text-danger">-Rs. {{ number_format($totalReturnDeduction, 2) }}</strong>
                        </div>
                    @endif
                    <div class="summary-row total-row">
                        <span>NET COMMISSION PAYABLE:</span>
                        <span class="font-monospace">Rs. {{ number_format($adjustedCommission, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Carpenter Acknowledgment Box --}}
        <div class="carpenter-ack-box">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <strong><i class="fas fa-hand-holding-usd me-1"></i> Carpenter Payment Acknowledgment:</strong>
                    <div>
                        I, <b>{{ $commission->carpenter->name ?? 'Carpenter' }}</b>, confirm receipt of 
                        <b class="text-dark font-monospace">Rs. {{ number_format($adjustedCommission, 2) }}</b> 
                        as full and final commission for Sale Invoice #{{ $commission->sale->invoice_no ?? $commission->sale_id }}.
                    </div>
                </div>
            </div>
        </div>

        {{-- Signatures Grid --}}
        <div class="signatures-grid">
            <div class="signature-box">
                Prepared By (Cashier)<br>
                <small class="text-muted font-monospace">{{ auth()->user()->name ?? 'System' }}</small>
            </div>
            <div class="signature-box">
                Approved By (Admin / Manager)<br>
                <small class="text-muted font-monospace">{{ $commission->approver->name ?? 'Pending' }}</small>
            </div>
            <div class="signature-box">
                Carpenter Signature / Thumb<br>
                <small class="text-muted font-monospace">{{ $commission->carpenter->name ?? 'Recipient' }}</small>
            </div>
        </div>

        {{-- System Footer --}}
        <div class="text-center text-muted mt-4 pt-2 border-top" style="font-size: 10px;">
            <span>Generated on {{ date('d-M-Y h:i A') }} | Kent Hardware POS System | Hidden Backend Commission Record</span>
        </div>

    </div>

</body>
</html>
