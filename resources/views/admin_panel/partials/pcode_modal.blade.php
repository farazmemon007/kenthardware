@php
    $currentPCodeMapping = \App\Services\PCodeService::getMapping();
    $digits = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
@endphp

<!-- P-Code Cipher Configuration Modal -->
<div class="modal fade" id="pcodeModal" tabindex="-1" role="dialog" aria-labelledby="pcodeModalLabel" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #38bdf8;">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="pcodeModalLabel" style="font-size: 1.15rem; letter-spacing: -0.2px;">
                            P-Code Cipher Configuration
                        </h5>
                        <small class="text-light opacity-75" style="font-size: 0.8rem;">
                            Secret cost pricing cipher: Map digits (1 to 9 &amp; 0) to confidential alphabet letters.
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white btn-close btn-close-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" onclick="window.closePCodeModal();" style="background: none; border: none; font-size: 1.6rem; line-height: 1; color: #fff; opacity: 0.85; cursor: pointer;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4 bg-light">
                <form id="pcodeMappingForm" autocomplete="off">
                    @csrf
                    
                    {{-- Instructions Card --}}
                    <div class="p-3 mb-4 rounded-3 border bg-white shadow-sm" style="border-left: 4px solid #38bdf8 !important;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;"><i class="fas fa-info-circle text-primary me-1"></i> How P-Code Works:</span>
                                <p class="text-muted mb-0 small mt-1">
                                    Each digit is substituted with its assigned letter. Example: If <b>1=A, 2=B, 5=E, 0=J</b>, an amount of <b>Rs. 250</b> will be encoded as <b>BEJ</b>.
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1 rounded-2" id="btnPCodeResetDefault" title="Reset to standard A-J mapping">
                                    <i class="fas fa-redo me-1"></i> Standard (A-J)
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" id="btnPCodeClearAll" title="Clear all input cells">
                                    <i class="fas fa-eraser me-1"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mapping Grid (Digits on top, Empty/Letter inputs below) --}}
                    <div class="p-3 bg-white rounded-3 border shadow-sm mb-4">
                        <label class="form-label fw-bold text-dark text-uppercase mb-3 d-block" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                            <i class="fas fa-th me-1 text-primary"></i> Digit to Alphabet Mapping Grid
                        </label>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle mb-0" style="table-layout: fixed;">
                                <thead>
                                    <tr style="background: #0f172a; color: #ffffff;">
                                        @foreach($digits as $d)
                                            <th style="padding: 10px 4px; font-size: 1.15rem; font-weight: 800; font-family: monospace; width: 10%; border-color: #334155;">
                                                {{ $d }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background: #f8fafc;">
                                        @foreach($digits as $index => $d)
                                            <td style="padding: 10px 4px; border-color: #cbd5e1;">
                                                <input type="text" 
                                                       name="mapping[{{ $d }}]" 
                                                       id="pcode_digit_{{ $d }}" 
                                                       data-index="{{ $index }}"
                                                       class="form-control text-center pcode-digit-input fw-bold text-uppercase font-monospace"
                                                       maxlength="1" 
                                                       value="{{ $currentPCodeMapping[$d] ?? '' }}" 
                                                       placeholder="-"
                                                       style="font-size: 1.2rem; height: 46px; border: 2px solid #cbd5e1; border-radius: 8px; color: #0f172a; background: #ffffff; transition: all 0.2s;"
                                                       required>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Live Simulator / Test Box --}}
                    <div class="p-3 rounded-3 border bg-white shadow-sm">
                        <label class="form-label fw-bold text-dark text-uppercase mb-2 d-block" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                            <i class="fas fa-vial me-1 text-success"></i> Live Test / Simulator
                        </label>
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted fw-bold" style="font-size: 0.85rem;">Test Number:</span>
                                    <input type="text" id="pcodeSimInput" class="form-control fw-bold font-monospace" placeholder="e.g. 1250 or 250.50" value="1250">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small fw-semibold">Resulting P-Code:</span>
                                    <span class="badge bg-success font-monospace px-3 py-2 text-uppercase fw-bold shadow-sm" id="pcodeSimOutput" style="font-size: 1.1rem; letter-spacing: 1px;">
                                        ABEJ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pcodeAlertBox" class="alert d-none mt-3 mb-0 py-2 px-3 small" role="alert"></div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer py-2.5 px-4 bg-white border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small font-monospace">
                    <i class="fas fa-lock text-secondary me-1"></i> Saved securely in system settings
                </span>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" data-bs-dismiss="modal" data-dismiss="modal" onclick="window.closePCodeModal();">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-sm px-4 fw-bold rounded-pill text-white shadow-sm" id="btnSavePCodeMapping" style="background: #2563eb; min-width: 140px;">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Global P-Code Mapping Cache & Helpers
    window.pcodeMapping = @json($currentPCodeMapping);

    /**
     * Convert any numeric amount or string into P-Code.
     * E.g. 250 -> 'BEJ', 1234567890 -> 'ABCDEFGHIJ'
     */
    window.encodeToPCode = function(amount) {
        if (amount === undefined || amount === null || amount === '') return '';
        let str = String(amount).replace(/[^0-9.]/g, '');
        if (!str) return '';

        let num = parseFloat(str);
        if (isNaN(num)) return '';

        // If integer, strip trailing zeros
        if (Math.floor(num) === num) {
            str = String(Math.round(num));
        } else {
            str = String(parseFloat(num.toFixed(2)));
        }

        let map = window.pcodeMapping || {};
        let output = '';
        for (let i = 0; i < str.length; i++) {
            let ch = str[i];
            if (map[ch] !== undefined && map[ch] !== '') {
                output += map[ch];
            } else {
                output += ch;
            }
        }
        return output.toUpperCase();
    };

    /**
     * Decode a P-Code string back to numbers.
     */
    window.decodePCode = function(pcode) {
        if (!pcode) return '';
        let map = window.pcodeMapping || {};
        let reversed = {};
        for (let k in map) {
            reversed[String(map[k]).toUpperCase()] = k;
        }
        let str = String(pcode).toUpperCase();
        let output = '';
        for (let i = 0; i < str.length; i++) {
            let ch = str[i];
            output += (reversed[ch] !== undefined) ? reversed[ch] : ch;
        }
        return output;
    };

    $(document).ready(function() {
        // Auto-advance cursor to next input on typing
        $(document).on('input', '.pcode-digit-input', function(e) {
            let val = $(this).val().toUpperCase().trim();
            // allow only letter A-Z
            val = val.replace(/[^A-Z]/g, '');
            $(this).val(val);

            // Auto-advance
            if (val.length === 1) {
                let currentIndex = parseInt($(this).data('index'), 10);
                let nextInput = $(`.pcode-digit-input[data-index="${currentIndex + 1}"]`);
                if (nextInput.length) {
                    nextInput.focus().select();
                }
            }
            updateSimulator();
        });

        // Handle backspace to focus previous
        $(document).on('keydown', '.pcode-digit-input', function(e) {
            if (e.key === 'Backspace' && !$(this).val()) {
                let currentIndex = parseInt($(this).data('index'), 10);
                let prevInput = $(`.pcode-digit-input[data-index="${currentIndex - 1}"]`);
                if (prevInput.length) {
                    prevInput.focus().select();
                }
            }
        });

        // Test simulator input
        $('#pcodeSimInput').on('input', function() {
            updateSimulator();
        });

        function getActiveModalMapping() {
            let map = {};
            let digits = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            digits.forEach(function(d) {
                let val = $(`#pcode_digit_${d}`).val().toUpperCase().trim();
                map[d] = val || '';
            });
            return map;
        }

        function updateSimulator() {
            let currentMap = getActiveModalMapping();
            let testVal = $('#pcodeSimInput').val() || '';
            let str = String(testVal).replace(/[^0-9.]/g, '');
            let out = '';
            for (let i = 0; i < str.length; i++) {
                let ch = str[i];
                out += (currentMap[ch] !== undefined && currentMap[ch] !== '') ? currentMap[ch] : ch;
            }
            $('#pcodeSimOutput').text(out || '---');
        }

        // Standard Preset button
        $('#btnPCodeResetDefault').on('click', function() {
            let defaults = {'1':'A','2':'B','3':'C','4':'D','5':'E','6':'F','7':'G','8':'H','9':'I','0':'J'};
            for (let d in defaults) {
                $(`#pcode_digit_${d}`).val(defaults[d]);
            }
            updateSimulator();
        });

        // Clear All button
        $('#btnPCodeClearAll').on('click', function() {
            $('.pcode-digit-input').val('');
            $('#pcode_digit_1').focus();
            updateSimulator();
        });

        // Save Mapping via AJAX
        $('#btnSavePCodeMapping').on('click', function() {
            let $btn = $(this);
            let mapping = getActiveModalMapping();

            // Validate that all digits have a letter
            let hasEmpty = false;
            $('.pcode-digit-input').each(function() {
                if (!$(this).val()) {
                    hasEmpty = true;
                    $(this).css('border-color', '#ef4444');
                } else {
                    $(this).css('border-color', '#cbd5e1');
                }
            });

            if (hasEmpty) {
                $('#pcodeAlertBox')
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .html('<i class="fas fa-exclamation-circle me-1"></i> Please provide an alphabet letter for all digits (1 to 9 & 0).');
                return;
            }

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            $('#pcodeAlertBox').addClass('d-none');

            $.ajax({
                url: '{{ route("pcode.mapping.update") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    mapping: mapping
                },
                success: function(res) {
                    $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                    if (res.status === 'success') {
                        window.pcodeMapping = res.mapping;
                        $('#pcodeAlertBox')
                            .removeClass('d-none alert-danger')
                            .addClass('alert-success')
                            .html('<i class="fas fa-check-circle me-1"></i> ' + (res.message || 'P-Code mapping saved successfully!'));

                        // Trigger global event so views can update live
                        $(document).trigger('pcode-mapping-updated', [window.pcodeMapping]);

                        setTimeout(function() {
                            window.closePCodeModal();
                            $('#pcodeAlertBox').addClass('d-none');
                        }, 1200);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                    let errMsg = 'Failed to save P-Code mapping. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    $('#pcodeAlertBox')
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-1"></i> ' + errMsg);
                }
            });
        });

        // Initialize simulator on modal show
        $('#pcodeModal').on('shown.bs.modal show.bs.modal', function() {
            updateSimulator();
            setTimeout(function() {
                $('#pcode_digit_1').focus().select();
            }, 200);
        });

        // Explicit jQuery click handler for open-pcode-modal-btn
        $(document).on('click', '.open-pcode-modal-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.openPCodeModal(e);
        });
    });
</script>
