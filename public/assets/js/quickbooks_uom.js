/**
 * QuickBooks POS Style Multi-tier Unit of Measure (UOM) Engine
 * Kent Hardware ERP / POS System
 */

(function($) {
    'use strict';

    window.QuickbooksUom = {
        currentUomData: null,
        baseStock: 0,
        baseUnitName: '',

        init: function(options) {
            const self = this;
            self.options = options || {};

            // Bind click on [Units] button next to Base Unit selector
            $(document).on('click', '#btnOpenUomModal', function(e) {
                self.openModal();
            });

            // Also re-render whenever modal is opened via Bootstrap data-toggle
            $(document).on('show.bs.modal', '#quickbooksUomModal', function() {
                self.loadInitialData();
                self.renderModal();
            });

            // Re-render when General Unit select changes on main page
            $(document).on('change', '#general_unit_id', function() {
                const selectedText = $(this).find('option:selected').text().trim();
                if (selectedText && selectedText !== 'Select Unit...' && selectedText !== 'Select Base Unit...') {
                    self.baseUnitName = selectedText;
                    self.addMasterUnit(selectedText);
                    if ($('#qb_base_unit_select').length && $('#qb_base_unit_select').val() !== selectedText) {
                        $('#qb_base_unit_select').val(selectedText);
                    }
                    self.updateDropdowns();
                    self.updateSummaryBadge();
                }
            });

            // Change Base Unit directly inside QuickBooks modal
            $(document).on('change', '#qb_base_unit_select', function() {
                const val = $(this).val();
                if (val) {
                    self.onBaseUnitChanged(val);
                }
            });

            // Apply preset UOM Template loader
            $(document).on('change', '#qb_preset_uom_set', function() {
                const uomId = $(this).val();
                if (uomId) {
                    self.applyPresetUomSet(uomId);
                }
            });

            // Quick Add Unit button (inside QuickBooks UOM modal)
            $(document).on('click', '.btn-open-quick-unit-modal', function(e) {
                e.preventDefault();
                const targetField = $(this).data('target-field') || null;
                self.openQuickAddUnitModal(targetField);
            });

            // Multi-modal fix: when unitModal closes, keep quickbooksUomModal active
            $(document).on('hidden.bs.modal', '#unitModal', function() {
                if ($('#quickbooksUomModal').hasClass('show') || $('#quickbooksUomModal').is(':visible')) {
                    $('body').addClass('modal-open');
                    self.updateDropdowns();
                }
            });

            // Add Unit button inside modal
            $(document).on('click', '#btnAddQbUnit', function() {
                self.addUnitCard();
                self.updateDropdowns();
            });

            // Remove Unit button inside modal
            $(document).on('click', '.btn-remove-qb-unit', function() {
                $(this).closest('.qb-related-unit-card').remove();
                self.reindexUnits();
                self.updateDropdowns();
            });

            // Live recalculate QOH & title when ratio or name changes
            $(document).on('input', '.qb-uom-ratio', function() {
                const $card = $(this).closest('.qb-unit-card');
                self.recalculateCardQoh($card);
                self.updateCardHeader($card);
            });

            $(document).on('input', '.qb-uom-name', function() {
                const $card = $(this).closest('.qb-unit-card');
                self.updateCardHeader($card);
                self.updateDropdowns();
            });

            // Reset button
            $(document).on('click', '#btnResetQbUom', function() {
                if (confirm('Reset Units of Measure back to base unit only?')) {
                    $('#product_uom_data').val('');
                    self.currentUomData = null;
                    self.loadInitialData();
                    self.renderModal();
                }
            });

            // OK / Save button
            $(document).on('click', '#btnApplyQbUom', function() {
                self.saveFromModal();
            });

            // Load existing data on page load
            self.loadInitialData();
            self.updateSummaryBadge();
        },

        getBaseUnitName: function() {
            // 1. If currently chosen in #qb_base_unit_select inside modal
            const $modalSelect = $('#qb_base_unit_select');
            if ($modalSelect.length && $modalSelect.val()) {
                return $modalSelect.val().trim();
            }

            // 2. If saved in currentUomData
            if (this.currentUomData && this.currentUomData.base_unit_name) {
                return this.currentUomData.base_unit_name;
            }

            // 3. From main form #general_unit_id dropdown
            const $select = $('#general_unit_id');
            if ($select.length && $select.val()) {
                const txt = $select.find('option:selected').text().trim();
                if (txt && txt !== 'Select Unit...' && txt !== 'Select Base Unit...') {
                    return txt;
                }
            }

            // 4. From first available master unit
            const masters = this.getAllMasterUnits();
            if (masters.length > 0) {
                return masters[0];
            }

            return 'Pcs';
        },

        onBaseUnitChanged: function(newBase) {
            const self = this;
            if (!newBase) return;
            self.baseUnitName = newBase;

            $('#qbHeaderBaseUnitName').text(newBase);
            $('.qb-card-base-title').text(newBase);
            $('#qb_base_unit_input').val(newBase);
            $('.qb-base-unit-suffix').text(newBase);

            // Synchronize modal select if changed externally/via preset
            const $modalSelect = $('#qb_base_unit_select');
            if ($modalSelect.length && $modalSelect.val() !== newBase) {
                if ($modalSelect.find(`option[value="${newBase}"]`).length === 0) {
                    $modalSelect.append(new Option(newBase, newBase, true, true));
                } else {
                    $modalSelect.val(newBase);
                }
            }

            // Re-render related units card headers (e.g. "(16 Pcs)")
            $('#qbRelatedUnitsContainer .qb-unit-card').each(function() {
                self.updateCardHeader($(this));
            });

            // Re-render Ordering and Selling dropdowns
            self.updateDropdowns();
            self.updateSummaryBadge();

            // Synchronize with main form #general_unit_id
            const $gen = $('#general_unit_id');
            if ($gen.length) {
                let matched = false;
                $gen.find('option').each(function() {
                    if ($(this).text().trim().toLowerCase() === newBase.toLowerCase()) {
                        $gen.val($(this).val());
                        matched = true;
                        return false;
                    }
                });
                if (!matched) {
                    $gen.append(new Option(newBase, newBase, true, true));
                }
            }
        },

        getBaseStock: function() {
            // Check warehouse stock sum or input fields
            if (typeof window.initialProductStock !== 'undefined' && window.initialProductStock !== null) {
                return parseFloat(window.initialProductStock) || 0;
            }
            const pieceQty = $('#piece_quantity').val() || $('#total_stock_qty').val() || $('#initial_stock').val();
            if (pieceQty !== undefined && pieceQty !== '') {
                return parseFloat(pieceQty) || 0;
            }
            return 0;
        },

        getBasePrice: function() {
            return parseFloat($('#sale_price_per_piece').val() || $('#sale_price_per_box').val() || 0) || 0;
        },

        getBaseCost: function() {
            return parseFloat($('#purchase_price_per_piece').val() || 0) || 0;
        },

        loadInitialData: function() {
            const raw = $('#product_uom_data').val();
            if (raw) {
                try {
                    this.currentUomData = typeof raw === 'string' ? JSON.parse(raw) : raw;
                } catch(e) {
                    this.currentUomData = null;
                }
            }
            this.baseUnitName = this.getBaseUnitName();
            this.baseStock = this.getBaseStock();
        },

        openModal: function() {
            try {
                this.loadInitialData();
                this.renderModal();
            } catch (err) {
                console.error('[QuickbooksUom] Error preparing modal data:', err);
            }

            const modalEl = document.getElementById('quickbooksUomModal');
            if (!modalEl) {
                console.error('[QuickbooksUom] Modal element #quickbooksUomModal not found in DOM');
                return;
            }

            // Move to body if nested to prevent overflow / stacking context clipping
            if (modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }

            let opened = false;
            // 1. Try jQuery Bootstrap Modal (Standard for Bootstrap 3 & 4)
            if (typeof $ !== 'undefined' && typeof $(modalEl).modal === 'function') {
                try {
                    $(modalEl).modal('show');
                    opened = true;
                } catch (e) {
                    console.warn('[QuickbooksUom] $(modalEl).modal("show") failed:', e);
                }
            }

            // 2. Try native Bootstrap 5 if present
            if (!opened && typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                try {
                    const inst = (typeof bootstrap.Modal.getInstance === 'function')
                        ? bootstrap.Modal.getInstance(modalEl)
                        : null;
                    const bsModal = inst || new bootstrap.Modal(modalEl);
                    if (bsModal && typeof bsModal.show === 'function') {
                        bsModal.show();
                        opened = true;
                    }
                } catch (e) {
                    console.warn('[QuickbooksUom] bootstrap.Modal show failed:', e);
                }
            }

            // 3. Fallback CSS display if neither modal library responded
            if (!opened) {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.removeAttribute('aria-hidden');
                modalEl.setAttribute('aria-modal', 'true');
                document.body.classList.add('modal-open');
            }
        },

        renderModal: function() {
            const self = this;
            self.baseUnitName = self.getBaseUnitName();
            self.baseStock = self.getBaseStock();

            $('#qbHeaderBaseUnitName').text(self.baseUnitName);
            $('.qb-card-base-title').text(self.baseUnitName);
            $('#qb_base_unit_input').val(self.baseUnitName);
            $('.qb-base-unit-suffix').text(self.baseUnitName);
            $('#qb_base_qoh').val(self.formatNumber(self.baseStock));

            // Dynamically populate Base Unit dropdown with all master units from system
            const allMasters = self.getAllMasterUnits();
            let baseSelectHtml = '';
            allMasters.forEach(u => {
                const isSel = (u.toLowerCase() === (self.baseUnitName || '').toLowerCase()) ? 'selected' : '';
                baseSelectHtml += `<option value="${u}" ${isSel}>${u}</option>`;
            });
            $('#qb_base_unit_select').html(baseSelectHtml);
            if (self.baseUnitName) {
                $('#qb_base_unit_select').val(self.baseUnitName);
            }

            const uom = self.currentUomData;

            // Pre-fill Base Unit inputs
            if (uom && uom.base_unit) {
                $('#qb_base_upc').val(uom.base_unit.upc || '');
                $('#qb_base_alu').val(uom.base_unit.alu || '');
                $('#qb_base_price').val(uom.base_unit.price !== undefined ? uom.base_unit.price : self.getBasePrice());
                $('#qb_base_cost').val(uom.base_unit.cost !== undefined ? uom.base_unit.cost : self.getBaseCost());
            } else {
                $('#qb_base_upc').val($('#item_code').val() || '');
                $('#qb_base_alu').val('');
                $('#qb_base_price').val(self.getBasePrice());
                $('#qb_base_cost').val(self.getBaseCost());
            }

            // Render Related Units (Unit 1, Unit 2, Unit 3...)
            const $container = $('#qbRelatedUnitsContainer');
            $container.empty();

            if (uom && Array.isArray(uom.units) && uom.units.length > 0) {
                uom.units.forEach(function(unit, idx) {
                    self.addUnitCard(unit, idx + 1);
                });
            } else {
                // Default QuickBooks POS slots (Unit 1 and Unit 2 ready for entry)
                self.addUnitCard({ name: '', ratio: '', price: '', cost: '', upc: '', alu: '' }, 1);
                self.addUnitCard({ name: '', ratio: '', price: '', cost: '', upc: '', alu: '' }, 2);
            }

            self.updateDropdowns();

            // Set selected order and sell units if existing
            if (uom && uom.order_unit) {
                $('#qb_order_unit').val(uom.order_unit);
            }
            if (uom && uom.sell_unit) {
                $('#qb_sell_unit').val(uom.sell_unit);
            }

            // Populate preset UOM sets dropdown if available
            if (window.allUomSets && Array.isArray(window.allUomSets)) {
                let presetHtml = '<option value="">-- Apply from Pre-defined UOM Set --</option>';
                window.allUomSets.forEach(function(s) {
                    const base = s.base_unit || s.name;
                    const uData = (typeof s.uom_data === 'string') ? JSON.parse(s.uom_data) : (s.uom_data || {});
                    const uCount = (uData && uData.units) ? uData.units.length : 0;
                    const extra = uCount > 0 ? ` (${uCount} related units)` : '';
                    presetHtml += `<option value="${s.id}">${s.name} [Base: ${base}]${extra}</option>`;
                });
                $('#qb_preset_uom_set').html(presetHtml);
            }
        },

        applyPresetUomSet: function(uomId) {
            const self = this;
            if (!window.allUomSets || !Array.isArray(window.allUomSets)) return;
            const found = window.allUomSets.find(u => u.id == uomId);
            if (!found) return;

            const base = found.base_unit || found.name;
            const uData = (typeof found.uom_data === 'string') ? JSON.parse(found.uom_data) : (found.uom_data || {});

            self.onBaseUnitChanged(base);

            const $container = $('#qbRelatedUnitsContainer');
            $container.empty();

            if (uData && Array.isArray(uData.units) && uData.units.length > 0) {
                uData.units.forEach(function(u, idx) {
                    self.addUnitCard({
                        name: u.name,
                        ratio: u.ratio,
                        price: '',
                        cost: '',
                        upc: '',
                        alu: ''
                    }, idx + 1);
                });
            } else {
                self.addUnitCard({ name: '', ratio: '', price: '', cost: '', upc: '', alu: '' }, 1);
            }

            self.updateDropdowns();

            if (uData.order_unit) $('#qb_order_unit').val(uData.order_unit);
            if (uData.sell_unit) $('#qb_sell_unit').val(uData.sell_unit);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'UOM Set Applied',
                    text: `Loaded '${found.name}' with base unit '${base}'.`,
                    timer: 1200,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        },

        addUnitCard: function(data, unitNum) {
            const self = this;
            const $container = $('#qbRelatedUnitsContainer');
            const num = unitNum || ($container.find('.qb-related-unit-card').length + 1);
            data = data || { name: '', ratio: '', price: '', cost: '', upc: '', alu: '' };

            const ratio = parseFloat(data.ratio) || 0;
            let qohDisplay = '0';
            if (ratio > 0) {
                qohDisplay = self.formatNumber(self.baseStock / ratio);
            }

            const titleRatio = ratio > 0 ? ` (${ratio} base units)` : '';
            const titleName = data.name ? ` - ${data.name}` : '';

            const cardHtml = `
                <div class="card mb-3 border shadow-none qb-unit-card qb-related-unit-card" data-is-base="false" style="background: #ffffff; border-color: #cbd5e1 !important; border-radius: 6px;">
                    <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center bg-light border-bottom">
                        <span class="fw-bold text-dark qb-unit-header-title" style="font-size: 13px;">
                            <i class="fas fa-boxes text-secondary me-1"></i> Unit of Measure <span class="qb-unit-num">${num}</span><span class="qb-unit-header-extra">${titleName}${titleRatio}</span>
                        </span>
                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-qb-unit" title="Remove this unit" style="font-size: 13px; text-decoration: none;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-0 fw-semibold">Unit of Measure ${num}:</label>
                                <input type="text" class="form-control form-control-sm fw-bold qb-uom-name" placeholder="e.g. Pipe-16ft, Pipe-20ft, Box" value="${data.name || ''}" list="qbMasterUnitsList">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0 fw-semibold"># Base Units:</label>
                                <input type="number" step="any" min="0.0001" class="form-control form-control-sm text-end fw-bold qb-uom-ratio" placeholder="e.g. 16" value="${data.ratio || ''}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-0 fw-semibold">Quantity On Hand:</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm text-end fw-bold bg-light qb-uom-qoh" readonly value="${qohDisplay}">
                                    <span class="input-group-text small qb-unit-suffix">${data.name || 'units'}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">UPC / Barcode:</label>
                                <input type="text" class="form-control form-control-sm qb-uom-upc" placeholder="UPC" value="${data.upc || ''}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Alternate Lookup:</label>
                                <input type="text" class="form-control form-control-sm qb-uom-alu" placeholder="ALU" value="${data.alu || ''}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Regular Price:</label>
                                <input type="number" step="any" class="form-control form-control-sm text-end qb-uom-price" placeholder="0.00" value="${data.price !== undefined ? data.price : ''}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-0">Average Cost:</label>
                                <input type="number" step="any" class="form-control form-control-sm text-end qb-uom-cost" placeholder="0.00" value="${data.cost !== undefined ? data.cost : ''}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(cardHtml);
        },

        reindexUnits: function() {
            $('#qbRelatedUnitsContainer .qb-related-unit-card').each(function(idx) {
                const num = idx + 1;
                $(this).find('.qb-unit-num').text(num);
                $(this).find('label:first').text(`Unit of Measure ${num}:`);
            });
        },

        recalculateCardQoh: function($card) {
            const ratio = parseFloat($card.find('.qb-uom-ratio').val()) || 0;
            const $qoh = $card.find('.qb-uom-qoh');
            if (ratio > 0) {
                const qoh = this.baseStock / ratio;
                $qoh.val(this.formatNumber(qoh));
            } else {
                $qoh.val('0');
            }
        },

        updateCardHeader: function($card) {
            const num = $card.find('.qb-unit-num').text() || '1';
            const name = $card.find('.qb-uom-name').val().trim();
            const ratio = parseFloat($card.find('.qb-uom-ratio').val()) || 0;
            $card.find('.qb-unit-suffix').text(name || 'units');

            let extra = '';
            if (name) extra += ` - ${name}`;
            if (ratio > 0) extra += ` (${ratio} ${this.baseUnitName || 'base units'})`;

            $card.find('.qb-unit-header-extra').text(extra);
        },

        getAllMasterUnits: function() {
            const self = this;
            const set = new Set();

            // 1. From window.allUnitsList (passed from controller / blade)
            if (window.allUnitsList && Array.isArray(window.allUnitsList)) {
                window.allUnitsList.forEach(u => {
                    const name = typeof u === 'string' ? u.trim() : (u && u.name ? u.name.trim() : '');
                    if (name) set.add(name);
                });
            }

            // 2. From #general_unit_id dropdown options in DOM
            $('#general_unit_id option').each(function() {
                const name = $(this).text().trim();
                const val = $(this).val();
                if (val && name && name !== 'Select Unit...' && name !== 'Select Base Unit...') {
                    set.add(name);
                }
            });

            // 3. Defaults if none found
            if (set.size === 0) {
                ['Pcs', 'Carton', 'Box', 'Dozen', 'Kg', 'Gm', 'Meter', 'Ft', 'Roll', 'Set', 'Feet', 'mm'].forEach(u => set.add(u));
            }

            return Array.from(set);
        },

        addMasterUnit: function(unitName) {
            if (!unitName) return;
            unitName = unitName.trim();
            if (!window.allUnitsList) window.allUnitsList = [];
            if (!window.allUnitsList.includes(unitName)) {
                window.allUnitsList.push(unitName);
            }
        },

        updateDropdowns: function() {
            const self = this;
            const currentOrder = $('#qb_order_unit').val();
            const currentSell = $('#qb_sell_unit').val();

            const baseUnit = self.baseUnitName || 'Feet';

            // 1. Product-specific units: Base unit + related units currently typed in cards
            const productUnits = [baseUnit];
            $('#qbRelatedUnitsContainer .qb-related-unit-card').each(function() {
                const name = $(this).find('.qb-uom-name').val().trim();
                if (name && !productUnits.includes(name)) {
                    productUnits.push(name);
                }
            });

            // 2. All Master Units from database/system (filter out product units already listed)
            const masterUnits = self.getAllMasterUnits().filter(u => !productUnits.includes(u));

            const buildOptions = function(selectedVal) {
                let html = '';

                // Group 1: Product Units (Base & Configured Related Units)
                html += '<optgroup label="Product Units (Base & Multi-tier)">';
                productUnits.forEach(u => {
                    const isBase = (u === baseUnit);
                    const label = isBase ? `${u} (Base Unit)` : u;
                    const isSelected = (u === selectedVal) ? 'selected' : '';
                    html += `<option value="${u}" ${isSelected}>${label}</option>`;
                });
                html += '</optgroup>';

                // Group 2: System Master Units (All units added in Units master)
                if (masterUnits.length > 0) {
                    html += '<optgroup label="System Master Units (All Added Units)">';
                    masterUnits.forEach(u => {
                        const isSelected = (u === selectedVal) ? 'selected' : '';
                        html += `<option value="${u}" ${isSelected}>${u}</option>`;
                    });
                    html += '</optgroup>';
                }

                return html;
            };

            const targetOrder = currentOrder || (self.currentUomData && self.currentUomData.order_unit) || baseUnit;
            const targetSell = currentSell || (self.currentUomData && self.currentUomData.sell_unit) || baseUnit;

            $('#qb_order_unit').html(buildOptions(targetOrder));
            $('#qb_sell_unit').html(buildOptions(targetSell));

            if (targetOrder) $('#qb_order_unit').val(targetOrder);
            if (targetSell) $('#qb_sell_unit').val(targetSell);

            // Update autocomplete datalist for Related Unit inputs
            let datalistHtml = '';
            self.getAllMasterUnits().forEach(u => {
                datalistHtml += `<option value="${u}">`;
            });
            $('#qbMasterUnitsList').html(datalistHtml);
        },

        openQuickAddUnitModal: function(targetField) {
            const self = this;
            self.lastQuickAddTarget = targetField || null;

            const unitModalEl = document.getElementById('unitModal');
            if (unitModalEl) {
                if (unitModalEl.parentElement !== document.body) {
                    document.body.appendChild(unitModalEl);
                }
                $(unitModalEl).css({
                    'z-index': 1070
                });

                // Show modal
                if (typeof $ !== 'undefined' && typeof $(unitModalEl).modal === 'function') {
                    $(unitModalEl).modal('show');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const inst = (typeof bootstrap.Modal.getInstance === 'function') ? bootstrap.Modal.getInstance(unitModalEl) : null;
                    (inst || new bootstrap.Modal(unitModalEl)).show();
                }

                setTimeout(function() {
                    $('.modal-backdrop:last').css('z-index', 1065);
                    $('#modalUnitNameInput').focus();
                }, 150);
            } else if (typeof Swal !== 'undefined') {
                // Fallback SweetAlert prompt if unitModal is not in DOM
                Swal.fire({
                    title: 'New Unit of Measure',
                    input: 'text',
                    inputLabel: 'Unit Name',
                    inputPlaceholder: 'e.g. Bundle, Box, Meter',
                    showCancelButton: true,
                    confirmButtonText: 'Create Unit',
                    showLoaderOnConfirm: true,
                    preConfirm: (name) => {
                        if (!name || !name.trim()) {
                            Swal.showValidationMessage('Unit name is required');
                            return false;
                        }
                        return $.ajax({
                            url: '/Unit/store',
                            method: 'POST',
                            data: {
                                name: name.trim(),
                                page: 'product_page',
                                _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                            }
                        }).then(response => {
                            if (response.errors) {
                                throw new Error(Object.values(response.errors).flat().join(' '));
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error.message || error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const unitName = result.value.name || (result.value.unit && result.value.unit.name) || '';
                        const unitId = result.value.id || (result.value.unit && result.value.unit.id) || '';
                        if (unitName) {
                            self.handleNewUnitCreated(unitName, unitId, targetField);
                        }
                    }
                });
            }
        },

        handleNewUnitCreated: function(unitName, unitId, targetField) {
            const self = this;
            if (!unitName) return;

            self.addMasterUnit(unitName);

            // 1. Add to main page #general_unit_id if missing
            const $gen = $('#general_unit_id');
            if ($gen.length) {
                const exists = $gen.find('option').filter(function() { return $(this).text().trim().toLowerCase() === unitName.toLowerCase(); }).length > 0;
                if (!exists) {
                    $gen.append(new Option(unitName, unitId || unitName, false, false));
                }
            }

            // 2. Add to Base Unit dropdown inside modal if missing
            const $baseSel = $('#qb_base_unit_select');
            if ($baseSel.length) {
                const exists = $baseSel.find('option').filter(function() { return $(this).text().trim().toLowerCase() === unitName.toLowerCase(); }).length > 0;
                if (!exists) {
                    $baseSel.append(new Option(unitName, unitName, false, false));
                }
            }

            self.updateDropdowns();

            // 3. Set value in target field if specified
            if (targetField && $(targetField).length) {
                $(targetField).val(unitName).trigger('change');
            }

            $('body').addClass('modal-open');
        },

        saveFromModal: function() {
            const self = this;
            self.baseUnitName = $('#qb_base_unit_select').val() || self.getBaseUnitName();

            const orderUnit = $('#qb_order_unit').val() || self.baseUnitName;
            const sellUnit = $('#qb_sell_unit').val() || self.baseUnitName;

            const baseUnitObj = {
                name: self.baseUnitName,
                ratio: 1.0,
                upc: $('#qb_base_upc').val().trim(),
                alu: $('#qb_base_alu').val().trim(),
                price: parseFloat($('#qb_base_price').val()) || 0,
                cost: parseFloat($('#qb_base_cost').val()) || 0
            };

            const unitsList = [];
            let hasError = false;

            $('#qbRelatedUnitsContainer .qb-related-unit-card').each(function() {
                const name = $(this).find('.qb-uom-name').val().trim();
                const ratio = parseFloat($(this).find('.qb-uom-ratio').val()) || 0;
                const upc = $(this).find('.qb-uom-upc').val().trim();
                const alu = $(this).find('.qb-uom-alu').val().trim();
                const price = $(this).find('.qb-uom-price').val() !== '' ? (parseFloat($(this).find('.qb-uom-price').val()) || 0) : (baseUnitObj.price * ratio);
                const cost = $(this).find('.qb-uom-cost').val() !== '' ? (parseFloat($(this).find('.qb-uom-cost').val()) || 0) : (baseUnitObj.cost * ratio);

                if (name) {
                    if (ratio <= 0) {
                        alert(`Unit "${name}" must have a valid # of Base Units greater than 0.`);
                        hasError = true;
                        return false;
                    }
                    unitsList.push({
                        name: name,
                        ratio: ratio,
                        upc: upc,
                        alu: alu,
                        price: price,
                        cost: cost
                    });
                }
            });

            if (hasError) return;

            const uomPayload = {
                base_unit_name: self.baseUnitName,
                order_unit: orderUnit,
                sell_unit: sellUnit,
                base_unit: baseUnitObj,
                units: unitsList
            };

            self.currentUomData = uomPayload;
            $('#product_uom_data').val(JSON.stringify(uomPayload)).trigger('change');

            // Synchronize with main form #general_unit_id
            const $gen = $('#general_unit_id');
            if ($gen.length) {
                let matched = false;
                $gen.find('option').each(function() {
                    if ($(this).text().trim().toLowerCase() === self.baseUnitName.toLowerCase()) {
                        $gen.val($(this).val()).trigger('change');
                        matched = true;
                        return false;
                    }
                });
                if (!matched) {
                    $gen.append(new Option(self.baseUnitName, self.baseUnitName, true, true)).trigger('change');
                }
            }

            self.updateSummaryBadge();

            // Close modal
            const modalEl = document.getElementById('quickbooksUomModal');
            if (modalEl) {
                let closed = false;
                if (typeof $ !== 'undefined' && typeof $(modalEl).modal === 'function') {
                    try {
                        $(modalEl).modal('hide');
                        closed = true;
                    } catch (e) {}
                }
                if (!closed && typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        const inst = (typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(modalEl)
                            : null;
                        if (inst && typeof inst.hide === 'function') {
                            inst.hide();
                            closed = true;
                        }
                    } catch (e) {}
                }
                if (!closed) {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    modalEl.setAttribute('aria-hidden', 'true');
                    modalEl.removeAttribute('aria-modal');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'UOM Updated',
                    text: `Configured ${unitsList.length + 1} unit(s) of measure.`,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        },

        updateSummaryBadge: function() {
            const self = this;
            const uom = self.currentUomData;
            const $badge = $('#uomSummaryBadge');
            const $text = $('#uomSummaryText');

            if (!$badge.length) return;

            if (uom && Array.isArray(uom.units) && uom.units.length > 0) {
                const unitSummaries = uom.units.map(u => `${u.name} (${u.ratio}x)`).join(', ');
                const baseName = uom.base_unit_name || self.getBaseUnitName();
                $badge.html(`
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1">
                        <i class="fas fa-layer-group me-1"></i> <strong>QuickBooks UOM Set:</strong> ${baseName} (Base), ${unitSummaries}
                    </span>
                    <span class="text-muted ms-2" style="font-size: 11px;">(Sell: <b>${uom.sell_unit || baseName}</b>, Order: <b>${uom.order_unit || baseName}</b>)</span>
                `);
            } else {
                $badge.html(`
                    <i class="fas fa-info-circle text-primary me-1"></i> <span id="uomSummaryText">Base Unit: <b>${self.getBaseUnitName()}</b>. Click [Units] to define QuickBooks multi-unit measurements (e.g. Pipe-16ft = 16 Feet).</span>
                `);
            }
        },

        formatNumber: function(num) {
            num = parseFloat(num) || 0;
            // Format cleanly without trailing zeroes if integer, or up to 4 decimal places
            return (Math.round(num * 10000) / 10000).toString();
        }
    };

    function initQuickbooksUom() {
        if (window.QuickbooksUom && !window.QuickbooksUom._initialized) {
            window.QuickbooksUom._initialized = true;
            window.QuickbooksUom.init();
        }
    }

    $(document).ready(initQuickbooksUom);
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initQuickbooksUom, 50);
    }

})(jQuery);
