@if(auth()->check() && auth()->user()->isSuperAdmin())
{{-- ========================================================= --}}
{{-- ADMIN PRICE APPROVAL POPUP MODAL (REAL-TIME NOTIFICATION) --}}
{{-- ========================================================= --}}
<div class="modal fade" id="modalAdminPriceApproval" tabindex="-1" aria-hidden="true" style="z-index: 1090;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 530px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px; overflow: hidden; border: 2px solid #b45309 !important;">
            {{-- Header --}}
            <div class="modal-header py-2.5 px-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border-bottom: 2px solid #f59e0b;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-2 py-1 font-monospace fw-bold" style="font-size: 11px;">
                        <i class="fas fa-bell me-1"></i> APPROVAL REQUIRED
                    </span>
                    <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 14px;">Price Override Request</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- Multi-request navigation arrows --}}
                    <div id="adminModalNavContainer" class="d-flex align-items-center gap-1" style="display: none !important;">
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2" id="btnAdminModalPrevReq" title="Previous Request" style="font-size: 11px; border-color: rgba(255,255,255,0.4);">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="badge bg-light text-dark font-monospace" id="adminModalReqCounter" style="font-size: 10.5px;">1 / 1</span>
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2" id="btnAdminModalNextReq" title="Next Request" style="font-size: 11px; border-color: rgba(255,255,255,0.4);">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <span class="badge bg-light text-dark font-monospace" id="adminModalTimeAgo" style="font-size: 10.5px;">Just now</span>
                    {{-- Header close X button --}}
                    <button type="button" class="btn-close text-white ms-1" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.4rem; line-height: 1; color: #fff; opacity: 0.85; padding: 0; cursor: pointer;">
                        &times;
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3" style="background: #f8fafc;">
                {{-- Status Alert banner (Pending vs Already Decided) --}}
                <div id="adminModalStatusBanner">
                    <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-center gap-2" style="border-left: 4px solid #f59e0b; background: #fffbeb; color: #92400e; font-size: 12.5px;">
                        <i class="fas fa-exclamation-triangle fs-5 text-warning flex-shrink-0"></i>
                        <div>
                            An employee is attempting to sell an item <strong>below Root Price</strong>. Your authorization is required.
                        </div>
                    </div>
                </div>

                {{-- Request Details Card --}}
                <div class="bg-white rounded border p-3 mb-3 shadow-sm" style="border-color: #cbd5e1 !important;">
                    <input type="hidden" id="adminModalRequestId" value="">

                    {{-- Staff Info Line --}}
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px;">Requesting Employee:</span>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold" id="adminModalStaffName" style="font-size: 12.5px;">
                                <i class="fas fa-user-tag me-1"></i> Staff Employee
                            </span>
                        </div>
                    </div>

                    {{-- Product Info Line --}}
                    <div class="mb-2">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">Product / Item:</span>
                        <div class="fw-bold text-dark fs-6" id="adminModalItemName">-</div>
                        <div class="text-muted small" id="adminModalSalesDesc" style="font-size: 12px;">-</div>
                    </div>

                    {{-- Quantity Line --}}
                    <div class="d-flex justify-content-between align-items-center py-1 border-top">
                        <span class="text-muted small fw-bold" style="font-size: 12px;">Quantity to Sell:</span>
                        <span class="fw-bold text-dark fs-6" id="adminModalQty">1 Pcs</span>
                    </div>

                    {{-- Price Breakdown Comparison Grid --}}
                    <div class="row g-2 mt-2 pt-2 border-top text-center">
                        <div class="col-4">
                            <div class="p-2 rounded bg-light border">
                                <span class="d-block text-muted small fw-bold" style="font-size: 10px;">REGULAR PRICE</span>
                                <span class="fw-bold text-secondary fs-6" id="adminModalRegularPrice">Rs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded border" style="background: #f1f5f9; border-color: #94a3b8 !important;">
                                <span class="d-block text-muted small fw-bold" style="font-size: 10px;">ROOT / FLOOR</span>
                                <span class="fw-bold text-dark fs-6" id="adminModalRootPrice">Rs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded border" style="background: #fee2e2; border-color: #ef4444 !important;">
                                <span class="d-block text-danger small fw-bold" style="font-size: 10px;">REQUESTED</span>
                                <span class="fw-bolder text-danger fs-6" id="adminModalReqPrice">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Loss / Difference Alert --}}
                    <div class="mt-2 text-center">
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 font-monospace fw-bold" style="font-size: 12px;" id="adminModalDiffBadge">
                            Discount below root: Rs. 0.00 / piece
                        </span>
                    </div>
                </div>

                {{-- Optional Admin Note / Rejection Reason --}}
                <div id="adminModalDecisionReasonContainer" class="mb-1">
                    <input type="text" id="adminModalDecisionReason" class="form-control form-control-sm" placeholder="Optional note or reason (e.g. Cleared for bulk customer)" style="font-size: 12px; border-color: #cbd5e1;">
                </div>
            </div>

            {{-- Footer Decision Buttons --}}
            <div class="modal-footer py-2 px-3 d-flex justify-content-between align-items-center" style="background: #e2e8f0; border-top: 1px solid #cbd5e1;">
                <button type="button" class="btn btn-outline-secondary btn-sm px-2.5" data-bs-dismiss="modal" data-dismiss="modal" id="btnAdminModalCloseLater" style="font-size: 12px;">
                    <i class="fas fa-times me-1"></i> Close / Decide Later
                </button>
                <div class="d-flex gap-2" id="adminModalActionButtons">
                    <button type="button" class="btn btn-danger btn-sm px-3 fw-bold shadow-sm" id="btnAdminRejectPrice">
                        <i class="fas fa-times-circle me-1"></i> Reject
                    </button>
                    <button type="button" class="btn btn-success btn-sm px-3 fw-bold shadow-sm" id="btnAdminAllowPrice" style="background: linear-gradient(180deg, #10b981 0%, #059669 100%); border-color: #047857;">
                        <i class="fas fa-check-circle me-1"></i> Allow / Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Floating Pill Trigger for Re-Opening Pending Approvals --}}
<div id="floatingPendingApprovalsBtn" class="shadow-lg" style="display: none; position: fixed; bottom: 25px; right: 25px; z-index: 1070; background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border: 2px solid #f59e0b; border-radius: 50px; padding: 9px 16px; cursor: pointer; color: #fff; font-weight: bold; box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45); transition: all 0.25s ease;" onclick="if(window.openPriceApprovalModal){ window.openPriceApprovalModal(); }">
    <i class="fas fa-bell text-warning me-1.5 fa-shake"></i>
    <span id="floatingPendingCountText">1 Price Approval Pending</span>
    <span class="badge bg-danger ms-1.5" id="floatingPendingBadge" style="font-size: 11px;">1</span>
</div>

<script>
(function() {
    let pendingApprovalRequests = [];
    let currentAdminRequestId = null;
    let currentReqIndex = 0;
    let adminPollerTimer = null;
    let isModalOpen = false;
    let snoozedRequestIds = {}; // Tracks requests closed with "Decide Later"

    // Web Audio alert chime for admin notification
    function playAdminAlertSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;
            
            // Beep 1
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(880, now);
            gain1.gain.setValueAtTime(0.2, now);
            gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.18);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.18);

            // Beep 2
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(1174.66, now + 0.12);
            gain2.gain.setValueAtTime(0.25, now + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.35);
        } catch (e) {}
    }

    function checkAdminPendingApprovals() {
        $.ajax({
            url: "{{ route('price_approval.pending') }}",
            type: "GET",
            dataType: "json",
            success: function(resp) {
                if (resp && resp.success) {
                    pendingApprovalRequests = resp.requests || [];
                    updateFloatingPill(resp.count || 0);

                    if (resp.count > 0 && !isModalOpen) {
                        // Check if there is at least one unsnoozed request
                        const unsnoozed = pendingApprovalRequests.filter(r => !snoozedRequestIds[r.id]);
                        if (unsnoozed.length > 0) {
                            currentReqIndex = 0;
                            displayRequestObj(unsnoozed[0], true);
                        }
                    }
                } else {
                    updateFloatingPill(0);
                }
            },
            error: function() {
                // Silently ignore network poll glitches
            }
        });
    }

    function updateFloatingPill(count) {
        if (count > 0) {
            $('#floatingPendingBadge').text(count);
            $('#floatingPendingCountText').text(count + (count === 1 ? ' Approval Pending' : ' Approvals Pending'));
            $('#floatingPendingApprovalsBtn').fadeIn(200);
        } else {
            $('#floatingPendingApprovalsBtn').fadeOut(200);
        }
    }

    function displayRequestObj(req, playSound = false) {
        if (!req) return;

        currentAdminRequestId = req.id;
        $('#adminModalRequestId').val(req.id);
        $('#adminModalStaffName').html('<i class="fas fa-user-tag me-1"></i> ' + (req.staff_name || 'Staff Employee'));
        $('#adminModalItemName').text(req.item_name || 'Item');
        $('#adminModalSalesDesc').text(req.sales_desc || '');
        $('#adminModalQty').text(parseFloat(req.qty) + ' Pcs');
        $('#adminModalRegularPrice').text('Rs. ' + parseFloat(req.regular_price).toFixed(2));
        $('#adminModalRootPrice').text('Rs. ' + parseFloat(req.root_price).toFixed(2));
        $('#adminModalReqPrice').text('Rs. ' + parseFloat(req.requested_price).toFixed(2));
        $('#adminModalDiffBadge').text('Below root price by: Rs. ' + parseFloat(req.discount_below_root).toFixed(2) + ' per piece');
        $('#adminModalTimeAgo').text(req.created_at_human || req.created_at_time || 'Just now');
        $('#adminModalDecisionReason').val('');

        // Navigation indicator if multiple pending
        if (pendingApprovalRequests && pendingApprovalRequests.length > 1) {
            $('#adminModalNavContainer').attr('style', 'display: flex !important;');
            $('#adminModalReqCounter').text((currentReqIndex + 1) + ' / ' + pendingApprovalRequests.length);
            $('#btnAdminModalPrevReq').prop('disabled', currentReqIndex === 0);
            $('#btnAdminModalNextReq').prop('disabled', currentReqIndex >= pendingApprovalRequests.length - 1);
        } else {
            $('#adminModalNavContainer').attr('style', 'display: none !important;');
        }

        // Status banner handling
        if (req.status === 'approved') {
            $('#adminModalStatusBanner').html(`
                <div class="alert alert-success py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 12.5px;">
                    <i class="fas fa-check-circle fs-5 text-success flex-shrink-0"></i>
                    <div><strong>Already Approved</strong> by ${req.approver_name || 'Admin'}. ${req.admin_notes ? '<br><small class="text-muted">' + req.admin_notes + '</small>' : ''}</div>
                </div>
            `);
            $('#adminModalActionButtons').hide();
            $('#adminModalDecisionReasonContainer').hide();
        } else if (req.status === 'rejected') {
            $('#adminModalStatusBanner').html(`
                <div class="alert alert-danger py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 12.5px;">
                    <i class="fas fa-times-circle fs-5 text-danger flex-shrink-0"></i>
                    <div><strong>Already Rejected</strong>. Reason: ${req.rejection_reason || 'Declined'}.</div>
                </div>
            `);
            $('#adminModalActionButtons').hide();
            $('#adminModalDecisionReasonContainer').hide();
        } else {
            $('#adminModalStatusBanner').html(`
                <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-center gap-2" style="border-left: 4px solid #f59e0b; background: #fffbeb; color: #92400e; font-size: 12.5px;">
                    <i class="fas fa-exclamation-triangle fs-5 text-warning flex-shrink-0"></i>
                    <div>An employee is attempting to sell an item <strong>below Root Price</strong>. Your authorization is required.</div>
                </div>
            `);
            $('#adminModalActionButtons').show();
            $('#adminModalDecisionReasonContainer').show();
        }

        if (playSound) playAdminAlertSound();

        isModalOpen = true;
        $('#modalAdminPriceApproval').modal('show');
    }

    // Prev / Next button handlers inside modal
    $('#btnAdminModalPrevReq').on('click', function() {
        if (currentReqIndex > 0) {
            currentReqIndex--;
            displayRequestObj(pendingApprovalRequests[currentReqIndex], false);
        }
    });

    $('#btnAdminModalNextReq').on('click', function() {
        if (currentReqIndex < pendingApprovalRequests.length - 1) {
            currentReqIndex++;
            displayRequestObj(pendingApprovalRequests[currentReqIndex], false);
        }
    });

    // Public API: open modal anytime by request ID or top pending
    window.openPriceApprovalModal = function(requestId) {
        if (requestId) {
            // Load specific request details from backend
            $.ajax({
                url: "/price-approval/details/" + requestId,
                type: "GET",
                dataType: "json",
                success: function(resp) {
                    if (resp && resp.success && resp.request) {
                        displayRequestObj(resp.request, false);
                    } else {
                        if (typeof Swal !== 'undefined') Swal.fire('Notice', 'Request details not found', 'info');
                    }
                },
                error: function() {
                    if (typeof Swal !== 'undefined') Swal.fire('Error', 'Could not load approval request details', 'error');
                }
            });
        } else {
            // Open top pending or refresh list
            $.ajax({
                url: "{{ route('price_approval.pending') }}",
                type: "GET",
                dataType: "json",
                success: function(resp) {
                    if (resp && resp.success && resp.count > 0) {
                        pendingApprovalRequests = resp.requests;
                        currentReqIndex = 0;
                        displayRequestObj(pendingApprovalRequests[0], false);
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Pending Approvals',
                                text: 'There are currently no price approval requests waiting for review.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }
                }
            });
        }
    };

    // Admin clicks Allow / Approve
    $('#btnAdminAllowPrice').on('click', function() {
        if (!currentAdminRequestId) return;
        const $btn = $(this);
        const origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Allowing...');

        const reason = $('#adminModalDecisionReason').val();

        $.ajax({
            url: "/price-approval/respond/" + currentAdminRequestId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                action: "approve",
                notes: reason
            },
            dataType: "json",
            success: function(resp) {
                $btn.prop('disabled', false).html(origText);
                delete snoozedRequestIds[currentAdminRequestId];

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Price Override Allowed!',
                        text: 'Employee permission has been granted.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }

                // Remove resolved request from local pending list
                pendingApprovalRequests = pendingApprovalRequests.filter(r => r.id != currentAdminRequestId);
                updateFloatingPill(pendingApprovalRequests.length);

                // If more requests exist, load next one immediately!
                if (pendingApprovalRequests.length > 0) {
                    if (currentReqIndex >= pendingApprovalRequests.length) currentReqIndex = 0;
                    displayRequestObj(pendingApprovalRequests[currentReqIndex], false);
                } else {
                    $('#modalAdminPriceApproval').modal('hide');
                    isModalOpen = false;
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(origText);
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error approving request';
                if (typeof Swal !== 'undefined') Swal.fire('Error', msg, 'error');
            }
        });
    });

    // Admin clicks Reject
    $('#btnAdminRejectPrice').on('click', function() {
        if (!currentAdminRequestId) return;
        const $btn = $(this);
        const origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Rejecting...');

        const reason = $('#adminModalDecisionReason').val();

        $.ajax({
            url: "/price-approval/respond/" + currentAdminRequestId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                action: "reject",
                reason: reason
            },
            dataType: "json",
            success: function(resp) {
                $btn.prop('disabled', false).html(origText);
                delete snoozedRequestIds[currentAdminRequestId];

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Price Request Rejected',
                        text: 'Sale below root price was declined.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }

                pendingApprovalRequests = pendingApprovalRequests.filter(r => r.id != currentAdminRequestId);
                updateFloatingPill(pendingApprovalRequests.length);

                if (pendingApprovalRequests.length > 0) {
                    if (currentReqIndex >= pendingApprovalRequests.length) currentReqIndex = 0;
                    displayRequestObj(pendingApprovalRequests[currentReqIndex], false);
                } else {
                    $('#modalAdminPriceApproval').modal('hide');
                    isModalOpen = false;
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(origText);
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error rejecting request';
                if (typeof Swal !== 'undefined') Swal.fire('Error', msg, 'error');
            }
        });
    });

    // When modal is manually closed / dismissed
    $('#modalAdminPriceApproval').on('hidden.bs.modal', function() {
        isModalOpen = false;
        // Snooze current request from auto-popping on next 4s cycle
        if (currentAdminRequestId) {
            snoozedRequestIds[currentAdminRequestId] = true;
        }
    });

    // Poller setup
    $(document).ready(function() {
        adminPollerTimer = setInterval(checkAdminPendingApprovals, 4500);
        setTimeout(checkAdminPendingApprovals, 1500);
    });
})();
</script>
@endif
