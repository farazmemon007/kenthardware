{{-- QuickBooks POS Style: Multi-tier Unit of Measure Modal --}}
<div id="quickbooksUomModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-bs-backdrop="static" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 820px;" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            
            <!-- QuickBooks POS Header (Classic blue theme) -->
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #204d74 0%, #15324b 100%); padding: 10px 16px; border-bottom: 2px solid #0f2538;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-cubes text-info" style="font-size: 16px;"></i>
                    <h6 class="modal-title fw-bold mb-0 text-white" id="qbModalTitle" style="font-size: 15px; letter-spacing: 0.3px;">
                        Base Unit - <span id="qbHeaderBaseUnitName">--</span>
                    </h6>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; font-size: 26px; font-weight: 300; line-height: 1; border: none; background: transparent; cursor: pointer; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-3" style="background-color: #eef2f6; max-height: 75vh; overflow-y: auto;">
                
                <!-- Section 1: Ordering and Selling -->
                <div class="card mb-3 border shadow-none" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 6px;">
                    <div class="card-header py-1 px-3 bg-light fw-bold text-dark border-bottom d-flex align-items-center justify-content-between" style="font-size: 12.5px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-exchange-alt text-primary"></i> Ordering and Selling
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 btn-open-quick-unit-modal" style="font-size: 11px; height: 25px; border-radius: 4px;" title="Add a new Unit of Measure to System">
                            <i class="fas fa-plus me-1"></i> Quick Add Unit
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold small mb-1 d-flex justify-content-between align-items-center">
                                    <span>The unit that I usually order by:</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <select id="qb_order_unit" class="form-control form-control-sm custom-select custom-select-sm fw-bold">
                                        <!-- Populated dynamically -->
                                    </select>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-open-quick-unit-modal px-2" data-target-field="#qb_order_unit" title="Quick Add New Unit" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-semibold small mb-1 d-flex justify-content-between align-items-center">
                                    <span>The unit that I usually sell by:</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <select id="qb_sell_unit" class="form-control form-control-sm custom-select custom-select-sm fw-bold">
                                        <!-- Populated dynamically -->
                                    </select>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-open-quick-unit-modal px-2" data-target-field="#qb_sell_unit" title="Quick Add New Unit" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Autocomplete Datalist for Master Units in Related Unit cards --}}
                <datalist id="qbMasterUnitsList"></datalist>

                <!-- Section 2: Base Unit Card -->
                <div class="card mb-3 border shadow-none qb-unit-card" data-is-base="true" style="background: #ffffff; border-color: #93c5fd !important; border-radius: 6px;">
                    <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center" style="background: #e0f2fe; border-bottom: 1px solid #bfdbfe;">
                        <span class="fw-bold text-primary" style="font-size: 13px;">
                            <i class="fas fa-anchor me-1"></i> Base Unit - <span class="qb-card-base-title">--</span>
                        </span>
                        <span class="badge bg-primary text-white px-2 py-1" style="font-size: 11px;">1 Base Unit</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0 fw-semibold d-flex justify-content-between align-items-center">
                                    <span>Unit of Measure (Base Unit):</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <select id="qb_base_unit_select" class="form-control form-control-sm custom-select custom-select-sm fw-bold border-primary">
                                        <!-- Dynamically populated with all units from DB -->
                                    </select>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-open-quick-unit-modal px-2" data-target-field="#qb_base_unit_select" title="Quick Add New Unit" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="qb_base_unit_input">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-0 fw-semibold">Quantity On Hand:</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="qb_base_qoh" class="form-control form-control-sm text-end fw-bold bg-light" readonly value="0">
                                    <span class="input-group-text small qb-base-unit-suffix">units</span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">UPC / Barcode:</label>
                                <input type="text" id="qb_base_upc" class="form-control form-control-sm" placeholder="UPC">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Alternate Lookup:</label>
                                <input type="text" id="qb_base_alu" class="form-control form-control-sm" placeholder="ALU">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Regular Price:</label>
                                <input type="number" step="any" id="qb_base_price" class="form-control form-control-sm text-end" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Average Cost:</label>
                                <input type="number" step="any" id="qb_base_cost" class="form-control form-control-sm text-end" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Dynamic Related Units Container -->
                <div id="qbRelatedUnitsContainer">
                    <!-- Unit 1, Unit 2, Unit 3 injected via JS -->
                </div>

                <!-- Add More Units Button -->
                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddQbUnit">
                        <i class="fas fa-plus-circle me-1"></i> Add Another Unit of Measure
                    </button>
                    <span class="text-muted small" style="font-size: 11px;">
                        <i class="fas fa-info-circle me-1"></i> QuickBooks Desktop Point of Sale UOM Structure
                    </span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer py-2 px-3 bg-light border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetQbUom">
                    <i class="fas fa-undo me-1"></i> Reset to Default
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-dark px-3" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary px-4 fw-bold" id="btnApplyQbUom">
                        <i class="fas fa-check me-1"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
