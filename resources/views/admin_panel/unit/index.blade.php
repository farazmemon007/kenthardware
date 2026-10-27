@extends('admin_panel.layout.app')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-lg-12">
                    
                    {{-- Header Banner --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-cubes text-primary"></i> Unit of Measure (UOM) Sets
                            </h4>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle text-info me-1"></i> QuickBooks Desktop Point of Sale Multi-tier Unit of Measure System: Define Base Units & multiple related conversion units per element.
                            </p>
                        </div>
                        @can('units.create')
                            <button type="button" class="btn btn-primary px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" id="btnCreateUomSet">
                                <i class="fas fa-plus-circle"></i> Create Unit of Measure Set
                            </button>
                        @endcan
                    </div>

                    {{-- Table Card --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden; background: #fff;">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="default-datatable" class="table table-hover align-middle mb-0" style="width:100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;" class="text-center">#</th>
                                            <th style="min-width: 160px;">UOM Set / Name</th>
                                            <th style="width: 110px;" class="text-center">Type</th>
                                            <th style="width: 120px;" class="text-center">Base Unit</th>
                                            <th style="min-width: 250px;">Multiple Units of Measure (Conversions)</th>
                                            <th style="min-width: 160px;">Defaults (Order / Sell)</th>
                                            <th style="width: 110px;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($unit as $company)
                                            @php
                                                $baseUnit = $company->getBaseUnit();
                                                $related = $company->getRelatedUnits();
                                                $uData = $company->uom_data ?? [];
                                                $orderUnit = $uData['order_unit'] ?? $baseUnit;
                                                $sellUnit = $uData['sell_unit'] ?? $baseUnit;
                                            @endphp
                                            <tr>
                                                <td class="text-center text-muted fw-bold">{{ $company->id }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $company->name }}</div>
                                                    @if(!empty($company->base_unit) && $company->base_unit !== $company->name)
                                                        <small class="text-muted"><i class="fas fa-link text-primary me-1"></i> Base: {{ $company->base_unit }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                                        {{ $company->unit_type ?? 'Count' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary text-white px-2 py-1 fw-bold" style="font-size: 11.5px;">
                                                        {{ $baseUnit }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(!empty($related) && count($related) > 0)
                                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                                            @foreach ($related as $rel)
                                                                <span class="badge bg-info bg-opacity-10 text-dark border border-info px-2 py-1" style="font-size: 11px;">
                                                                    <b>1 {{ $rel['name'] ?? '' }}</b> = {{ $rel['ratio'] ?? 1 }} {{ $baseUnit }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-muted border px-2 py-1" style="font-size: 11px;">
                                                            Single Unit (1 {{ $baseUnit }})
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-shopping-cart text-primary me-1"></i> Buy: <b class="text-dark">{{ $orderUnit }}</b>
                                                    </small>
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-cash-register text-success me-1"></i> Sell: <b class="text-dark">{{ $sellUnit }}</b>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        @can('units.edit')
                                                            <button type="button" class="btn btn-outline-primary btn-edit-uom" data-id="{{ $company->id }}" title="Edit UOM Set">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        @endcan
                                                        @can('units.delete')
                                                            <button type="button" class="btn btn-outline-danger btn-delete-uom" data-id="{{ $company->id }}" data-url="{{ route('delete.Unit', $company->id) }}" title="Delete UOM Set">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
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
    </div>
</div>

{{-- QuickBooks POS Multi-tier UOM Set Create / Edit Modal --}}
<div class="modal fade" id="uomSetModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 820px;" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            
            {{-- Modal Header --}}
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #204d74 0%, #15324b 100%); padding: 12px 18px; border-bottom: 2px solid #0f2538;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-cubes text-info" style="font-size: 18px;"></i>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="uomModalHeaderTitle" style="font-size: 16px;">
                        QuickBooks POS Unit of Measure Set
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; font-size: 26px; border: none; background: transparent; cursor: pointer;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Form --}}
            <form id="uomSetForm" action="{{ route('store.Unit') }}" method="POST">
                @csrf
                <input type="hidden" name="edit_id" id="uom_edit_id" value="">
                
                <div class="modal-body p-3" style="background: #f8fafc; max-height: 75vh; overflow-y: auto;">
                    
                    {{-- Section 1: Set Identification --}}
                    <div class="card mb-3 border shadow-none" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 6px;">
                        <div class="card-header py-1 px-3 bg-light fw-bold text-dark border-bottom" style="font-size: 12.5px;">
                            <i class="fas fa-tag text-primary me-1"></i> Step 1: Unit of Measure Set Name & Type
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small text-muted mb-1">
                                        Unit of Measure Set Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="uom_set_name" class="form-control form-control-sm fw-bold" required placeholder="e.g. Pipe (Feet), Hardware Count, Cable Wire, Nails (Kg)">
                                    <small class="text-muted" style="font-size: 11px;">The title of this UOM set as it appears on products and invoices</small>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small text-muted mb-1">
                                        Unit Type
                                    </label>
                                    <select name="unit_type" id="uom_unit_type" class="form-control form-control-sm custom-select custom-select-sm fw-bold">
                                        <option value="Count">Count / Each</option>
                                        <option value="Length">Length / Distance</option>
                                        <option value="Weight">Weight / Mass</option>
                                        <option value="Volume">Volume / Liquid</option>
                                        <option value="Area">Area / Surface</option>
                                    </select>
                                    <small class="text-muted" style="font-size: 11px;">Physical property of measurement</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Base Unit (The Smallest Unit) --}}
                    <div class="card mb-3 border shadow-none" style="background: #ffffff; border-color: #93c5fd !important; border-radius: 6px;">
                        <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center" style="background: #e0f2fe; border-bottom: 1px solid #bfdbfe;">
                            <span class="fw-bold text-primary" style="font-size: 12.5px;">
                                <i class="fas fa-anchor me-1"></i> Step 2: Define Base Unit (Smallest Unit)
                            </span>
                            <span class="badge bg-primary text-white px-2 py-0" style="font-size: 10.5px;">1 Base Unit = Baseline</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="alert alert-info py-2 px-3 mb-2 small d-flex align-items-center gap-2" style="font-size: 11.5px; border-radius: 4px;">
                                <i class="fas fa-lightbulb text-warning" style="font-size: 14px;"></i>
                                <span><b>QuickBooks Golden Rule:</b> The Base Unit MUST be the absolute smallest unit in which items can be tracked or sold (e.g. <i>Feet</i>, <i>Pcs</i>, <i>Kg</i>, <i>Meter</i>).</span>
                            </div>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small text-muted mb-1">Base Unit Name <span class="text-danger">*</span></label>
                                    <input type="text" name="base_unit" id="uom_base_unit_input" class="form-control form-control-sm fw-bold" required placeholder="e.g. Feet, Pcs, Kg, Meter">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small text-muted mb-1">Ratio to Baseline</label>
                                    <input type="text" class="form-control form-control-sm bg-light text-muted fw-bold" readonly value="1.0 (Fixed Base)">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Multiple Units of Measure (Related Multi-tier Units) --}}
                    <div class="card mb-3 border shadow-none" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 6px;">
                        <div class="card-header py-1 px-3 bg-light fw-bold text-dark border-bottom d-flex justify-content-between align-items-center" style="font-size: 12.5px;">
                            <div>
                                <i class="fas fa-boxes text-primary me-1"></i> Step 3: Define Related Units (Multiple Units on this Element)
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fw-bold" id="btnAddRelatedRow" style="font-size: 11px; height: 24px; border-radius: 4px;">
                                <i class="fas fa-plus me-1"></i> Add Unit
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-muted small mb-2" style="font-size: 11.5px;">
                                Define larger packages or lengths (e.g. 1 <b>Pipe-16ft</b> = 16 Feet, 1 <b>Pipe-20ft</b> = 20 Feet, 1 <b>Box</b> = 10 Pcs, 1 <b>Carton</b> = 100 Pcs).
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-1" id="tblRelatedUnits">
                                    <thead class="table-light text-center small">
                                        <tr>
                                            <th style="width: 45%;">Related Unit Name</th>
                                            <th style="width: 25%;"># of Base Units</th>
                                            <th style="width: 20%;">Conversion Relationship</th>
                                            <th style="width: 10%;">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblRelatedUnitsBody">
                                        <!-- Injected via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Default Purchasing and Selling Preferences --}}
                    <div class="card mb-2 border shadow-none" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 6px;">
                        <div class="card-header py-1 px-3 bg-light fw-bold text-dark border-bottom" style="font-size: 12.5px;">
                            <i class="fas fa-exchange-alt text-primary me-1"></i> Step 4: Default Ordering and Selling Preferences
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold small mb-1">The unit that I usually order by (Purchases):</label>
                                    <select name="order_unit" id="uom_order_unit" class="form-control form-control-sm custom-select custom-select-sm fw-bold">
                                        <!-- Populated via JS -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold small mb-1">The unit that I usually sell by (Sales):</label>
                                    <select name="sell_unit" id="uom_sell_unit" class="form-control form-control-sm custom-select custom-select-sm fw-bold">
                                        <!-- Populated via JS -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold" id="btnSubmitUomSet">
                        <i class="fas fa-check me-1"></i> Save Unit of Measure Set
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    'use strict';

    // Initialize DataTable
    if ($.fn.DataTable.isDataTable('#default-datatable')) {
        $('#default-datatable').DataTable().destroy();
    }
    $('#default-datatable').DataTable({
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],
        language: {
            search: "Search UOM Sets:",
            lengthMenu: "Show _MENU_ sets"
        }
    });

    // Helper to get current base unit name from input
    function getModalBaseUnit() {
        return $('#uom_base_unit_input').val().trim() || 'Base Unit';
    }

    // Helper to refresh the live conversion label in related rows
    function refreshRowConversions() {
        const base = getModalBaseUnit();
        $('#tblRelatedUnitsBody tr').each(function() {
            const name = $(this).find('.rel-name-input').val().trim() || 'Unit';
            const ratio = parseFloat($(this).find('.rel-ratio-input').val()) || 1;
            $(this).find('.conversion-preview').html(`1 <b>${name}</b> = <b>${ratio}</b> ${base}`);
        });
        refreshDefaultDropdowns();
    }

    // Helper to build default Order and Sell dropdown options
    function refreshDefaultDropdowns() {
        const base = getModalBaseUnit();
        const units = [base];

        $('#tblRelatedUnitsBody tr').each(function() {
            const name = $(this).find('.rel-name-input').val().trim();
            if (name && !units.includes(name)) {
                units.push(name);
            }
        });

        const currentOrder = $('#uom_order_unit').val();
        const currentSell = $('#uom_sell_unit').val();

        let opts = '';
        units.forEach(u => {
            const label = (u === base) ? `${u} (Base Unit)` : u;
            opts += `<option value="${u}">${label}</option>`;
        });

        $('#uom_order_unit').html(opts);
        $('#uom_sell_unit').html(opts);

        if (currentOrder && units.includes(currentOrder)) $('#uom_order_unit').val(currentOrder);
        if (currentSell && units.includes(currentSell)) $('#uom_sell_unit').val(currentSell);
    }

    // Append a related unit row
    function appendRelatedRow(name, ratio) {
        name = name || '';
        ratio = ratio !== undefined && ratio !== null && ratio !== '' ? ratio : '';
        const base = getModalBaseUnit();

        const tr = `
            <tr>
                <td>
                    <input type="text" name="related_names[]" class="form-control form-control-sm fw-bold rel-name-input" placeholder="e.g. Pipe-16ft, Pipe-20ft, Box, Carton" value="${name}">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="any" min="0.0001" name="related_ratios[]" class="form-control form-control-sm text-end fw-bold rel-ratio-input" placeholder="e.g. 16" value="${ratio}">
                        <span class="input-group-text small bg-light base-suffix-span">${base}</span>
                    </div>
                </td>
                <td class="text-center small text-muted conversion-preview">
                    1 <b>${name || 'Unit'}</b> = <b>${ratio || 1}</b> ${base}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0 btn-remove-rel-row" title="Remove unit" style="font-size: 13px;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tblRelatedUnitsBody').append(tr);
        refreshRowConversions();
    }

    // Events on Base Unit input
    $('#uom_base_unit_input').on('input change', function() {
        const base = getModalBaseUnit();
        $('.base-suffix-span').text(base);
        refreshRowConversions();
    });

    // Events on Related Unit rows
    $(document).on('input change', '.rel-name-input, .rel-ratio-input', function() {
        refreshRowConversions();
    });

    // Add Related Row button
    $('#btnAddRelatedRow').on('click', function() {
        appendRelatedRow('', '');
    });

    // Remove Related Row button
    $(document).on('click', '.btn-remove-rel-row', function() {
        $(this).closest('tr').remove();
        refreshRowConversions();
    });

    // Reset Form for New Creation
    function resetUomForm() {
        $('#uom_edit_id').val('');
        $('#uomModalHeaderTitle').text('New QuickBooks POS Unit of Measure Set');
        $('#uom_set_name').val('');
        $('#uom_unit_type').val('Count');
        $('#uom_base_unit_input').val('Pcs');
        $('#tblRelatedUnitsBody').empty();
        
        // Add 2 default suggested slots (Unit 1 e.g. Box, Unit 2 e.g. Carton)
        appendRelatedRow('Box', 12);
        appendRelatedRow('Carton', 144);

        refreshRowConversions();
    }

    // Open Modal for Create
    $('#btnCreateUomSet').on('click', function() {
        resetUomForm();
        $('#uomSetModal').modal('show');
    });

    // Open Modal for Edit
    $(document).on('click', '.btn-edit-uom', function() {
        const id = $(this).data('id');
        resetUomForm();
        $('#uomModalHeaderTitle').text('Edit QuickBooks POS Unit of Measure Set #' + id);
        $('#uom_edit_id').val(id);

        $.ajax({
            url: '/Unit/show/' + id,
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(unit) {
                if (!unit) return;
                $('#uom_set_name').val(unit.name || '');
                $('#uom_unit_type').val(unit.unit_type || 'Count');
                $('#uom_base_unit_input').val(unit.base_unit || unit.name || 'Pcs');

                $('#tblRelatedUnitsBody').empty();

                const uData = (typeof unit.uom_data === 'string') ? JSON.parse(unit.uom_data) : (unit.uom_data || {});
                if (uData && Array.isArray(uData.units) && uData.units.length > 0) {
                    uData.units.forEach(function(u) {
                        appendRelatedRow(u.name, u.ratio);
                    });
                }

                refreshRowConversions();

                if (uData.order_unit) $('#uom_order_unit').val(uData.order_unit);
                if (uData.sell_unit) $('#uom_sell_unit').val(uData.sell_unit);

                $('#uomSetModal').modal('show');
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load Unit of Measure details' });
            }
        });
    });

    // Handle Form Submit via AJAX
    $('#uomSetForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#btnSubmitUomSet');
        const origText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                if (res.status === 'success' || res.success) {
                    $('#uomSetModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.success || 'Unit of Measure Set saved successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (res.errors) {
                    const msg = Object.values(res.errors).flat().join('<br>');
                    Swal.fire({ icon: 'warning', title: 'Validation Warning', html: msg });
                }
            },
            error: function(err) {
                let msg = 'Failed to save Unit of Measure Set!';
                if (err.responseJSON && err.responseJSON.errors) {
                    msg = Object.values(err.responseJSON.errors).flat().join('<br>');
                } else if (err.responseJSON && err.responseJSON.message) {
                    msg = err.responseJSON.message;
                }
                Swal.fire({ icon: 'error', title: 'Error', html: msg });
            },
            complete: function() {
                $btn.html(origText).prop('disabled', false);
            }
        });
    });

    // Handle Delete
    $(document).on('click', '.btn-delete-uom', function() {
        const url = $(this).data('url');
        Swal.fire({
            title: 'Delete Unit of Measure Set?',
            text: "Products using this unit of measure will retain their existing data, but this set will be removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.success || 'Unit deleted successfully',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete unit' });
                    }
                });
            }
        });
    });

});
</script>
@endsection
