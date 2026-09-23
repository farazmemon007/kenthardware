@extends('admin_panel.layout.app')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid">

                <div class="page-header row align-items-center mb-3">
                    <div class="page-title col-lg-6">
                        <h4 class="font-weight-bold text-dark mb-1">Warehouse List</h4>
                        <h6 class="text-muted">Manage Warehouses</h6>
                    </div>
                    <div class="page-btn d-flex justify-content-end col-lg-6">
                        @can('warehouse.create')
                            <button type="button" class="btn btn-primary mb-2 shadow-sm" id="btnAddWarehouse"
                                data-toggle="modal" data-target="#warehouseModal"
                                data-bs-toggle="modal" data-bs-target="#warehouseModal"
                                onclick="openAddWarehouse()">
                                <i class="fa fa-plus mr-1"></i> Add Warehouse
                            </button>
                        @endcan
                    </div>
                </div>

                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover datanew">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Created By</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $key => $w)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ optional($w->user)->name ?? 'System' }}</td>
                                            <td class="font-weight-bold text-dark">{{ $w->warehouse_name }}</td>
                                            <td>{{ $w->location ?: '-' }}</td>
                                            <td>{{ $w->remarks ?: '-' }}</td>
                                            <td>
                                                @can('warehouse.edit')
                                                    <button type="button" class="btn btn-primary btn-sm edit-warehouse-btn"
                                                        data-id="{{ $w->id }}" data-name="{{ $w->warehouse_name }}"
                                                        data-location="{{ $w->location }}" data-remarks="{{ $w->remarks }}"
                                                        data-toggle="modal" data-target="#warehouseModal"
                                                        data-bs-toggle="modal" data-bs-target="#warehouseModal">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                @endcan
                                                @can('warehouse.delete')
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        data-url="{{ url('warehouse/delete/' . $w->id) }}"
                                                        data-msg="Are you sure you want to delete this warehouse?" data-method="get"
                                                        onclick="logoutAndDeleteFunction(this)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                @endcan
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
    </div>

    <!-- Warehouse Add/Edit Modal -->
    <div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="warehouseModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ url('warehouse/store') }}" method="POST" id="warehouseForm" style="width: 100%;">
                @csrf
                <input type="hidden" name="id" id="warehouse_id">
                <input type="hidden" name="creater_id" id="creater_id" value="{{ auth()->check() ? auth()->id() : '' }}">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-dark" id="warehouseModalTitle">Add/Edit Warehouse</h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark" for="warehouse_name">Warehouse Name <span class="text-danger">*</span></label>
                            <input class="form-control" name="warehouse_name" id="warehouse_name"
                                placeholder="e.g. Main Central Warehouse" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark" for="location">Location / Address</label>
                            <input class="form-control" name="location" id="location"
                                placeholder="e.g. Plot 45, Industrial Area">
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-dark" for="remarks">Remarks / Description</label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="3" placeholder="Optional notes or instructions"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                        @canany(['warehouse.create', 'warehouse.edit'])
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold">Save Warehouse</button>
                        @endcanany
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    <script>
        function openAddWarehouse() {
            clearWarehouse();
            $('#warehouseModalTitle').text('Add New Warehouse');
            $('#warehouseModal').modal('show');
        }

        function clearWarehouse() {
            $('#warehouse_id').val('');
            $('#warehouse_name').val('');
            $('#location').val('');
            $('#remarks').val('');
        }

        $(document).ready(function() {
            // Add Warehouse Button Click
            $('#btnAddWarehouse').on('click', function(e) {
                e.preventDefault();
                openAddWarehouse();
            });

            // Edit Warehouse Button Click
            $(document).on('click', '.edit-warehouse-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                var location = $(this).data('location');
                var remarks = $(this).data('remarks');

                $('#warehouse_id').val(id);
                $('#warehouse_name').val(name);
                $('#location').val(location);
                $('#remarks').val(remarks);
                $('#warehouseModalTitle').text('Edit Warehouse');
                $('#warehouseModal').modal('show');
            });

            // Initialize DataTable if not already initialized
            if (!$.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable();
            }
        });
    </script>
@endsection
