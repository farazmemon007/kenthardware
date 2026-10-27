@extends('admin_panel.layout.app')
@section('content')
    <style>
        .btn-sm i.fa-toggle-on {
            color: green;
            font-size: 20px;
        }

        .btn-sm i.fa-toggle-off {
            color: gray;
            font-size: 20px;
        }

        /* ── Customers Page Responsive ── */
        .customers-page-wrap {
            padding: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .customers-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 16px;
        }

        .customers-page-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .customers-btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .customers-table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
        }

        .customers-table-wrap table {
            min-width: 700px;
            width: 100%;
        }

        @media (max-width: 576px) {
            .customers-page-wrap {
                padding: 10px 8px;
            }
            .customers-page-header h3 {
                font-size: 1.05rem;
            }
            .customers-btn-group .btn {
                font-size: 11.5px;
                padding: 4px 8px;
            }
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="customers-page-wrap">
                <div class="customers-page-header">
                    <h3>Customer List</h3>
                    <div class="customers-btn-group">
                        <a href="{{ route('customers.inactive') }}" class="btn btn-secondary btn-sm">View Inactive Customers</a>
                        @can('customers.create')
                            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">+ Add New Customer</a>
                        @endcan
                        @can('customers.view')
                            <a href="{{ route('customers.ledger') }}" class="btn btn-primary btn-sm">Ledger</a>
                            <a href="{{ route('customer.payments') }}" class="btn btn-primary btn-sm">Payment</a>
                        @endcan
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="customers-table-wrap">
                <table id="customers-datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Credit Limit</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->customer_id }}</td>
                                <td>{{ $customer->customer_name }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->balance_range == 0 ? 'Unlimited' : number_format($customer->balance_range, 0) }}</td>
                                <td>{{ $customer->status }}</td>
                                <td>
                                    @php
                                        $source = $customer->source ?? 'Manual';
                                    @endphp
                                    @if($source === 'Website')
                                        <span class="badge bg-success">Website</span>
                                    @elseif($source === 'Both')
                                        <span class="badge bg-info text-dark">Both</span>
                                    @else
                                        <span class="badge bg-secondary">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('customers.edit', $customer->id),
                                        'deleteRoute' => route('customers.destroy', $customer->id),
                                        'editIsLink' => true,
                                        'permissions' => [
                                            'edit' => 'customers.edit',
                                            'delete' => 'customers.delete',
                                        ],
                                        'dataId' => $customer->id,
                                    ])

                                    @can('customers.edit')
                                        <a href="{{ route('customers.toggleStatus', $customer->id) }}"
                                            class="btn btn-sm {{ $customer->status === 'active' ? 'btn-dark' : 'btn-secondary' }}"
                                            title="Toggle Status">
                                            <i
                                                class="fa-solid {{ $customer->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </a>
                                    @endcan
                                    @can('customers.view')
                                        <a href="{{ route('customer.payments') }}" class="btn btn-sm btn-info">Payments</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>{{-- /.customers-table-wrap --}}
            </div>{{-- /.customers-page-wrap --}}
        </div>
    </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#customers-datatable').DataTable({
            "pageLength": 25,
            "lengthMenu": [10, 25, 50, 100],
            "order": [[0, 'desc']],
            "responsive": true,
            "scrollX": true,
            "language": {
                "search": "Search Customer:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
