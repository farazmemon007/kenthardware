@foreach ($sales as $sale)
    @php
        $pNames = 'N/A';
        if ($sale->items && $sale->items->count() > 0) {
            $pNames = $sale->items
                ->map(fn($item) => optional($item->product)->item_name ?? '?')
                ->implode(', ');
        } elseif ($sale->product) {
            $pNames = $sale->product;
        }

        $statusBadge = '<span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">Draft</span>';
        $isExchange = \Illuminate\Support\Str::startsWith($sale->reference ?? '', 'Exchange for');
        
        if ($sale->sale_status === 'posted') {
            if ($sale->is_booking) {
                $statusBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;"><i class="fas fa-check-circle me-1 mr-1"></i>Confirmed Booking</span>';
            } elseif ($isExchange) {
                $statusBadge = '<span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;"><i class="fas fa-exchange-alt me-1 mr-1"></i>Exchange</span>';
            } else {
                $statusBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;"><i class="fas fa-check-circle me-1 mr-1"></i>Posted</span>';
            }
        } elseif ($sale->sale_status === 'booked') {
            if ($sale->is_booking) {
                $statusBadge = '<span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;"><i class="fas fa-bookmark me-1 mr-1"></i>Booked</span>';
            } else {
                $statusBadge = '<span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;"><i class="fas fa-file-invoice me-1 mr-1"></i>Quotation</span>';
            }
        } elseif ($sale->sale_status === 'returned') {
            $statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">Returned</span>';
        } elseif ($sale->sale_status == 1) {
            $statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">Return</span>';
        } elseif ($sale->sale_status === null) {
            $statusBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">Sale</span>';
        }

        if ($sale->returns && $sale->returns->count() > 0) {
            $statusBadge .= '<br><small class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 mt-1 fw-bold" style="font-size: 10px;"><i class="fas fa-undo-alt me-1 mr-1"></i> Partial Return</small>';
        }

        $inline_val = $sale->items ? $sale->items->sum('discount_amount') : 0;
        $bill_amount = $sale->total_bill_amount > 0 ? $sale->total_bill_amount : (float) $sale->per_total;
        $gross_subtotal = $bill_amount + $inline_val;
        $inline_pct = $gross_subtotal > 0 ? ($inline_val / $gross_subtotal) * 100 : 0;

        $collected = $sale->cash - $sale->change;
        $refunded = 0;
        if (isset($isExchange) && $isExchange && $collected <= 0) {
            $refundPayment = \App\Models\CustomerPayment::where('note', 'Refund Paid for POS Exchange #'.$sale->invoice_no)->first();
            if ($refundPayment) {
                $refunded = $refundPayment->amount;
            }
        }
    @endphp

    {{-- Table Row --}}
    <tr>
        <td class="fw-bold font-monospace text-nowrap">
            @if ($sale->invoice_no)
                <span class="text-primary fw-bold" style="font-size: 13.5px;">{{ $sale->invoice_no }}</span>
                <small class="text-secondary d-block fw-semibold" style="font-size: 11px;">#{{ $sale->id }}</small>
            @else
                <span class="text-secondary fw-semibold">#{{ $sale->id }}</span>
            @endif
        </td>
        @php
            $isWalkin = empty($sale->customer_id);
            $custName = optional($sale->customer_relation)->customer_name ?: ($sale->walkin_name ?: 'Walk-in Customer');
            $custPhone = optional($sale->customer_relation)->mobile ?: ($sale->walkin_phone ?: '');
            $initial = strtoupper(substr($custName ?: 'W', 0, 1));
        @endphp
        <td class="text-nowrap" style="min-width: 175px;">
            <div class="d-flex align-items-center">
                <div class="avatar-circle {{ $isWalkin ? 'bg-secondary text-white' : 'bg-primary text-white' }} fw-bold d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                    {{ $initial }}
                </div>
                <div>
                    <span class="fw-bold text-dark d-block" style="font-size: 13px; line-height: 1.25;">{{ $custName }}</span>
                    @if($isWalkin)
                        <div class="d-flex align-items-center gap-1 mt-0.5">
                            <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace py-0 px-1" style="font-size: 10px; font-weight: 600;">Walk-in</span>
                            @if($custPhone)
                                <small class="text-secondary fw-semibold ms-1" style="font-size: 11px;">{{ $custPhone }}</small>
                            @endif
                        </div>
                    @else
                        @if($custPhone)
                            <small class="text-secondary fw-semibold d-block" style="font-size: 11px;">{{ $custPhone }}</small>
                        @endif
                    @endif
                </div>
            </div>
        </td>
        <td class="font-monospace text-dark fw-bold text-nowrap" style="font-size: 13px;">{{ $sale->reference ?: '—' }}</td>
        @php
            $itemPCodes = $sale->items ? $sale->items->pluck('p_code')->filter()->unique()->implode(', ') : '';
        @endphp
        <td title="{{ $pNames }}" style="font-size: 13px; max-width: 220px;">
            <div class="fw-bold text-dark" style="line-height: 1.3;">{{ \Illuminate\Support\Str::limit($pNames, 45) }}</div>
            @if(!empty($itemPCodes))
                <span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace py-0 px-1.5 mt-1" style="font-size: 10.5px; font-weight: 700;" title="P-Code: {{ $itemPCodes }}">P: {{ \Illuminate\Support\Str::limit($itemPCodes, 25) }}</span>
            @endif
        </td>
        <td class="text-center font-monospace fw-bold text-dark text-nowrap" style="font-size: 13.5px;">
            {{ $sale->total_items > 0 ? $sale->total_items : $sale->qty }}
        </td>
        <td class="text-end fw-bold text-dark font-monospace text-nowrap" style="font-size: 13px;">
            Rs. {{ number_format($gross_subtotal, 2) }}
        </td>
        <td class="text-end text-dark font-monospace text-nowrap" style="font-size: 13px;">
            @if ($inline_val > 0)
                <div class="fw-bold text-danger">Rs. {{ number_format($inline_val, 2) }}</div>
                <div class="text-secondary small mt-0.5 fw-semibold" style="font-size: 10.5px;">({{ number_format($inline_pct, 1) }}%)</div>
            @else
                <span class="text-muted text-nowrap">Rs. 0.00</span>
            @endif
        </td>
        <td class="text-end text-dark font-monospace text-nowrap" style="font-size: 13px;">
            @if ($sale->total_extradiscount > 0)
                @php
                    $add_val = $sale->total_extradiscount;
                    $add_pct = $bill_amount > 0 ? ($add_val / $bill_amount) * 100 : 0;
                @endphp
                <span class="badge rounded-pill border px-2 py-0.5 text-nowrap" style="background-color: #fff8e1; color: #b78103; border-color: #ffe082 !important; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fas fa-tag" style="font-size: 10px;"></i> Rs. {{ number_format($add_val, 2) }}
                </span>
                <div class="text-secondary small mt-0.5 fw-semibold" style="font-size: 10.5px;">({{ number_format($add_pct, 1) }}%)</div>
            @else
                <span class="text-muted text-nowrap">Rs. 0.00</span>
            @endif
        </td>
        <td class="text-end text-success fw-bold font-monospace text-nowrap" style="font-size: 14px; font-weight: 800 !important;">
            @if (isset($isExchange) && $isExchange)
                @if ($collected > 0)
                    Rs. {{ number_format($collected, 2) }}
                @elseif ($refunded > 0)
                    <span class="text-danger">-Rs. {{ number_format($refunded, 2) }}</span>
                @else
                    Rs. 0.00
                @endif
                <br><span class="badge bg-info text-white border border-info px-1.5 py-0 mt-0.5" style="font-size: 10px; font-weight: 700;"><i class="fas fa-exchange-alt me-1 mr-1"></i>Exchange</span>
            @else
                Rs. {{ number_format($sale->total_net, 2) }}
            @endif
        </td>
        <td class="text-nowrap text-dark fw-bold" style="font-size: 12.5px;">
            {{ $sale->created_at->format('d/m/Y') }}
        </td>
        <td class="text-nowrap">{!! $statusBadge !!}</td>
        <td class="pe-3 text-center text-nowrap">
            <div class="dropdown">
                <button class="btn btn-sm odoo-action-btn btn-premium-action dropdown-toggle" type="button" data-bs-toggle="dropdown" data-toggle="dropdown" data-display="static" aria-expanded="false">
                    <i class="fas fa-ellipsis-v small me-1 mr-1"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-right border shadow">
                    @can('sales.edit')
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.edit', $sale->id) }}">
                                <i class="fas fa-edit text-primary fa-fw"></i> Edit (Simple)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('pos.index') }}?edit_id={{ $sale->id }}">
                                <i class="fas fa-cash-register text-success fa-fw"></i> Edit (POS Sale)
                            </a>
                        </li>
                    @endcan

                    @if ($sale->sale_status === 'draft' || $sale->sale_status === 'booked')
                        @can('sales.create')
                            <li>
                                <form action="{{ route('sales.confirm', $sale->id) }}" method="POST" class="confirm-booking-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-success d-flex align-items-center gap-2 py-2 fw-bold">
                                        <i class="fas fa-check-circle fa-fw text-success"></i> 
                                        @if($sale->sale_status === 'booked' && !$sale->is_booking)
                                            Convert to Sale
                                        @else
                                            Confirm Booking
                                        @endif
                                    </button>
                                </form>
                            </li>
                        @endcan
                    @endif

                    <li><hr class="dropdown-divider"></li>

                    @can('sales.view')
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.invoice', $sale->id) }}" target="_blank">
                                <i class="fas fa-file-invoice text-info fa-fw"></i> View Invoice
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.invoice', ['id' => $sale->id, 'type' => 'estimate']) }}" target="_blank">
                                <i class="fas fa-calculator text-secondary fa-fw"></i> View Estimate
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.dc', $sale->id) }}" target="_blank">
                                <i class="fas fa-shipping-fast text-warning fa-fw"></i> Delivery Challan (DC)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.dc_thermal', $sale->id) }}" target="_blank">
                                <i class="fas fa-truck text-muted fa-fw"></i> DC Thermal
                            </a>
                        </li>
                        @if ($sale->hasMarketShortage())
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.market_slip', $sale->id) }}" target="_blank">
                                    <i class="fas fa-shopping-basket text-warning fa-fw"></i> Market Purchase Slip
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.receipt', $sale->id) }}" target="_blank">
                                <i class="fas fa-receipt text-success fa-fw"></i> Receipt
                            </a>
                        </li>
                    @endcan

                    @if ($sale->sale_status !== 'returned')
                        @can('sales.create')
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="{{ route('sale.add') }}?return_invoice={{ $sale->invoice_no }}">
                                    <i class="fas fa-undo fa-fw"></i> Return Sale
                                </a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
