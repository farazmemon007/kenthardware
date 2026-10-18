@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 1 */
    .img1-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: 100vh;
    }
    .img1-container {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Top Dark Bar */
    .img1-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img1-top-bar .title-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .img1-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
    }
    .img1-top-bar .badge-pill-role {
        background: #2563eb;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img1-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* 3-Column Layout */
    .img1-main-grid {
        display: grid;
        grid-template-columns: 240px 1fr 340px;
        border-bottom: 1px solid #e2e8f0;
    }
    @media (max-width: 1200px) {
        .img1-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Column 1: Left Form */
    .col-form {
        padding: 14px 16px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.8rem;
    }
    .form-step-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #334155;
        text-transform: uppercase;
        margin: 12px 0 6px 0;
        letter-spacing: 0.2px;
    }
    .form-step-title:first-child {
        margin-top: 0;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        color: #1e293b;
        font-size: 0.79rem;
        cursor: pointer;
    }
    .radio-option input {
        cursor: pointer;
    }
    .form-input-clean {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 5px 8px;
        font-size: 0.78rem;
        color: #0f172a;
    }
    .form-input-clean:focus {
        border-color: #2563eb;
        outline: none;
    }

    /* Workload bars */
    .workload-section {
        margin-top: 6px;
        font-size: 0.74rem;
    }
    .workload-title {
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
    }
    .workload-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .workload-name {
        width: 60px;
        font-weight: 500;
        color: #334155;
        white-space: nowrap;
    }
    .workload-bar-track {
        flex: 1;
        height: 6px;
        background: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
    }
    .workload-bar-fill {
        height: 100%;
        border-radius: 3px;
    }
    .workload-count {
        font-size: 0.7rem;
        color: #64748b;
        white-space: nowrap;
    }

    /* Column 2: Middle Location Picker Table */
    .col-middle-table {
        padding: 14px 16px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
    }
    .middle-header-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.2px;
    }
    .loc-picker-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .loc-picker-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .loc-picker-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .loc-picker-table tbody tr.row-selected {
        background: #e0edff !important;
    }
    .loc-picker-table tbody tr.row-disabled {
        color: #94a3b8;
    }
    .loc-picker-table tbody tr.row-disabled td {
        color: #94a3b8;
    }

    /* Priority Pills */
    .priority-pill {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 0.68rem;
        font-weight: 700;
        color: #ffffff;
        text-align: center;
        min-width: 36px;
    }
    .priority-pill.high { background: #ef4444; }
    .priority-pill.med { background: #f97316; }
    .priority-pill.low { background: #64748b; }

    .open-count-text {
        font-size: 0.74rem;
        color: #d97706;
    }

    .middle-footer-count {
        margin-top: 8px;
        font-size: 0.74rem;
        color: #475569;
        font-weight: 600;
    }

    /* Column 3: Right Preview & Checks */
    .col-right-preview {
        padding: 14px 16px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .preview-header-title {
        font-weight: 700;
        font-size: 0.74rem;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.2px;
    }
    .preview-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.76rem;
        margin-bottom: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .preview-table th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 4px 6px;
        border-bottom: 1px solid #e2e8f0;
    }
    .preview-table td {
        padding: 4px 6px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    /* Checks Before Saving */
    .checks-list {
        font-size: 0.74rem;
        margin-bottom: 12px;
    }
    .check-line {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .check-icon-pass {
        color: #10b981;
        font-weight: 700;
    }
    .check-icon-warn {
        color: #f59e0b;
        font-weight: 700;
    }

    /* Blue explanation box: What happens on assign */
    .blue-info-box {
        background: #eff6ff;
        border-left: 3px solid #2563eb;
        padding: 8px 10px;
        border-radius: 2px;
        font-size: 0.73rem;
        margin-bottom: 10px;
    }
    .blue-info-box-title {
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 4px;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    .blue-info-box ol {
        margin: 0;
        padding-left: 14px;
        color: #1e3a8a;
    }
    .blue-info-box li {
        margin-bottom: 2px;
    }

    /* Priority explanation */
    .priority-info-box {
        font-size: 0.72rem;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.35;
    }

    /* Action Buttons */
    .preview-actions-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        margin-top: 10px;
    }
    .btn-act-cancel {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-act-cancel:hover {
        background: #475569;
        color: #ffffff;
    }
    .btn-act-draft {
        background: #475569;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-act-draft:hover {
        background: #334155;
    }
    .btn-act-assign {
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-act-assign:hover {
        background: #1d4ed8;
    }

    /* Bottom 4 Explanation Boxes */
    .img1-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #ffffff;
    }
    @media (max-width: 768px) {
        .img1-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
    .img1-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img1-box:last-child {
        border-right: none;
    }
    .img1-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img1-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }
</style>

<div class="img1-wrapper">
    <div class="img1-container">
        {{-- Top Dark Bar --}}
        <div class="img1-top-bar">
            <div class="title-section">
                <span class="main-title">1 · Assign Cycle Count</span>
                <span class="badge-pill-role">Owner / In-charge only</span>
            </div>
            <div class="concept-note">
                CONCEPT for approval · locations, dates and staff are illustrative
            </div>
        </div>

        {{-- Main 3-Column Section --}}
        <form id="assignFormExact" method="POST" action="{{ route('cycle_count.store') }}">
            @csrf
            <input type="hidden" name="action" id="actionHidden" value="assign">

            <div class="img1-main-grid">
                {{-- Column 1: Left Form --}}
                <div class="col-form">
                    <div class="form-step-title">1 · COUNT TYPE (types per D-03 — still open)</div>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="fast_item" checked>
                        <span>Fast-item list — weekly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="salepoint_shelves">
                        <span>Sale-point shelves — quarterly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="godown_racks">
                        <span>Godown racks — half-yearly</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="count_type" value="adhoc">
                        <span>Ad hoc — owner's request</span>
                    </label>

                    <div class="form-step-title">2 · DATE</div>
                    <div style="display: flex; gap: 4px; align-items: center;">
                        <input type="date" name="count_date" id="dateInput" class="form-input-clean" value="{{ $todayDate }}" required style="width: 50%;">
                        <input type="text" name="time_window" class="form-input-clean" placeholder="time window: [from] - [to]" style="width: 50%;">
                    </div>

                    <div class="form-step-title">3 · ASSIGN TO</div>
                    <select name="assigned_to" id="assignedToSelect" class="form-input-clean" required>
                        @foreach($staffUsers as $st)
                            <option value="{{ $st->id }}" data-name="{{ $st->name }}">
                                [{{ $st->name }}] ▼
                            </option>
                        @endforeach
                    </select>

                    {{-- Staff workload this week --}}
                    <div class="workload-section">
                        <div class="workload-title">Staff workload this week</div>
                        @php
                            $colors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6'];
                            $i = 0;
                        @endphp
                        @foreach($staffUsers as $st)
                            @php
                                $wCount = $staffWorkload[$st->id] ?? 0;
                                $barColor = $colors[$i % count($colors)];
                                $barWidth = min(100, max(15, $wCount * 20));
                                $i++;
                            @endphp
                            <div class="workload-row">
                                <span class="workload-name">[{{ $st->name }}]</span>
                                <div class="workload-bar-track">
                                    <div class="workload-bar-fill" style="width: {{ $barWidth }}%; background: {{ $barColor }};"></div>
                                </div>
                                <span class="workload-count">{{ $wCount }} counts this week</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-step-title">4 · APPROVER (NEVER THE COUNTER)</div>
                    <select name="approver_id" id="approverSelect" class="form-input-clean" required>
                        @foreach($staffUsers as $st)
                            <option value="{{ $st->id }}" {{ $st->usertype === 'admin' ? 'selected' : '' }} data-name="{{ $st->name }}">
                                {{ $st->usertype === 'admin' ? 'Owner' : $st->name }} ▼ / In-charge
                            </option>
                        @endforeach
                    </select>

                    <div class="form-step-title">5 · NOTE TO STAFF (optional)</div>
                    <input type="text" name="notes" class="form-input-clean" placeholder='e.g. "check the back row too"'>
                </div>

                {{-- Column 2: Middle Location Picker Table --}}
                <div class="col-middle-table">
                    <div class="middle-header-title">
                        PICK LOCATION(S) — SORTED BY PRIORITY (SALES VELOCITY, DAYS SINCE LAST COUNT) · EACH LOCATION BECOMES ITS OWN COUNT
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="loc-picker-table" id="locPickerTable">
                            <thead>
                                <tr>
                                    <th style="width: 25px;"></th>
                                    <th>LOCATION</th>
                                    <th>ZONE</th>
                                    <th>ITEMS</th>
                                    <th>LAST COUNTED</th>
                                    <th>PRIORITY</th>
                                    <th>OPEN COUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $loc)
                                    <tr class="{{ $loc['has_open_count'] ? 'row-disabled' : '' }}"
                                        data-id="{{ $loc['id'] }}"
                                        data-name="{{ $loc['name'] }}"
                                        data-items="{{ $loc['items_count'] }}"
                                        data-priority="{{ $loc['priority'] }}"
                                        data-open="{{ $loc['has_open_count'] ? 'yes' : 'no' }}">
                                        <td>
                                            <input type="radio" name="location_id" value="{{ $loc['id'] }}"
                                                   id="loc_r_{{ $loc['id'] }}"
                                                   {{ $loc['has_open_count'] ? 'disabled' : '' }}
                                                   {{ ((request('location_id') && request('location_id') == $loc['id']) || (!request('location_id') && $loop->first)) && !$loc['has_open_count'] ? 'checked' : '' }}>
                                        </td>
                                        <td style="font-weight: 700;">
                                            <label for="loc_r_{{ $loc['id'] }}" style="margin:0; cursor:pointer;">
                                                {{ $loc['name'] }}
                                            </label>
                                        </td>
                                        <td>
                                            {{ $loc['zone'] ?: 'Godown rack' }}
                                        </td>
                                        <td>
                                            {{ $loc['items_count'] }}
                                        </td>
                                        <td>
                                            {{ $loc['last_counted_text'] }}
                                        </td>
                                        <td>
                                            @if($loc['priority'] === 'High')
                                                <span class="priority-pill high">High</span>
                                            @elseif($loc['priority'] === 'Med')
                                                <span class="priority-pill med">Med</span>
                                            @else
                                                <span class="priority-pill low">Low</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loc['has_open_count'])
                                                <span class="open-count-text">{{ $loc['open_count_number'] ?: 'C-0412' }} {{ strtolower($loc['open_count_status']) }}</span>
                                            @else
                                                <span style="color: #94a3b8;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="middle-footer-count" id="selectedSummaryLine">
                        1 location selected · <span id="totalItemsSelected">54</span> items in total
                    </div>
                </div>

                {{-- Column 3: Right Preview & Checks --}}
                <div class="col-right-preview">
                    <div>
                        <div class="preview-header-title">PREVIEW — COUNTS THAT WILL BE CREATED (1)</div>
                        <table class="preview-table">
                            <thead>
                                <tr>
                                    <th>COUNT #</th>
                                    <th>LOCATION</th>
                                    <th>ITEMS</th>
                                    <th>DATE</th>
                                    <th>STAFF</th>
                                </tr>
                            </thead>
                            <tbody id="previewTbody">
                                <tr>
                                    <td>{{ $nextCountNumber }}</td>
                                    <td id="prevLocCell" style="font-weight: 700;">SA3</td>
                                    <td id="prevItemsCell">54</td>
                                    <td id="prevDateCell">[date]</td>
                                    <td id="prevStaffCell">[Staff A]</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="preview-header-title">CHECKS BEFORE SAVING</div>
                        <div class="checks-list">
                            <div class="check-line" id="checkTargetItems">
                                <span class="check-icon-pass">✓</span>
                                <span>Items per sheet <span id="checkItemsText">54</span> — inside the 50–70 target</span>
                            </div>
                            <div class="check-line">
                                <span class="check-icon-pass">✓</span>
                                <span>No open count on selected location</span>
                            </div>
                            <div class="check-line" id="checkApproverFourEyes">
                                <span class="check-icon-pass">✓</span>
                                <span>Approver is not the assigned counter</span>
                            </div>
                            <div class="check-line">
                                <span class="check-icon-pass">✓</span>
                                <span>One location per count, one count per day</span>
                            </div>
                            <div class="check-line" style="color: #475569;">
                                <span class="check-icon-warn">!</span>
                                <span id="warnStaffLimit">[Staff A] has counts scheduled this week — within limit</span>
                            </div>
                            <div class="check-line" style="color: #94a3b8;">
                                <span>·</span>
                                <span>Estimated time per count: [measure the first counts]</span>
                            </div>
                        </div>

                        {{-- What happens on assign --}}
                        <div class="blue-info-box">
                            <div class="blue-info-box-title">WHAT HAPPENS ON "ASSIGN"</div>
                            <ol>
                                <li>Both counts appear on screen 2 (Schedule) as "Not started".</li>
                                <li>They appear on [<span id="boxStaffName">Staff A</span>]'s own "My counts" list only.</li>
                                <li>The item list for each location loads by itself — nobody types items.</li>
                                <li>Reassign or cancel is possible until the staff member opens the count.</li>
                            </ol>
                        </div>

                        {{-- Priority Column Note --}}
                        <div class="priority-info-box">
                            <strong style="color: #334155;">PRIORITY COLUMN:</strong>
                            <span class="priority-pill high" style="padding:0 4px;">High</span>
                            <span class="priority-pill med" style="padding:0 4px;">Med</span>
                            <span class="priority-pill low" style="padding:0 4px;">Low</span>
                            system suggestion from sales velocity and days since last count. The exact formula and the days limits are [BLANK] — the owner decides (D-03). Greyed rows already have a count on the Schedule and cannot be picked twice.
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="preview-actions-bar">
                        <a href="{{ route('cycle_count.index') }}" class="btn-act-cancel">Cancel</a>
                        <button type="button" class="btn-act-draft" id="btnSaveDraft">Save as draft</button>
                        <button type="button" class="btn-act-assign" id="btnAssignNotify">Assign & notify staff</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Bottom 4 Explanatory Boxes (Exact copy of Reference Image 1) --}}
        <div class="img1-bottom-grid">
            <div class="img1-box">
                <h6>Who</h6>
                <p>Only Owner / In-charge sees this screen. Staff cannot assign or see other staff's counts.</p>
            </div>
            <div class="img1-box">
                <h6>One location per count</h6>
                <p>Picking several locations creates several counts, one per day (24 Sep rule: one location at a time).</p>
            </div>
            <div class="img1-box">
                <h6>Auto item list</h6>
                <p>The sheet's items come from the location master — about 50–70 per sheet. No manual item entry.</p>
            </div>
            <div class="img1-box">
                <h6>Link to schedule</h6>
                <p>Every saved assignment is a row on the Schedule with status, progress and approver.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('locPickerTable');
    const rows = table.querySelectorAll('tbody tr');
    const assignedSelect = document.getElementById('assignedToSelect');
    const approverSelect = document.getElementById('approverSelect');
    const dateInput = document.getElementById('dateInput');

    function syncState() {
        // Checked location
        const radio = document.querySelector('input[name="location_id"]:checked');
        let locName = 'SA3';
        let itemsCount = 54;

        rows.forEach(tr => tr.classList.remove('row-selected'));

        if (radio) {
            const tr = radio.closest('tr');
            tr.classList.add('row-selected');
            locName = tr.getAttribute('data-name');
            itemsCount = parseInt(tr.getAttribute('data-items')) || 0;
        }

        document.getElementById('prevLocCell').textContent = locName;
        document.getElementById('prevItemsCell').textContent = itemsCount;
        document.getElementById('totalItemsSelected').textContent = itemsCount;
        document.getElementById('checkItemsText').textContent = itemsCount;

        // Staff name
        const staffOpt = assignedSelect.options[assignedSelect.selectedIndex];
        const staffName = staffOpt ? staffOpt.getAttribute('data-name') : 'Staff A';
        document.getElementById('prevStaffCell').textContent = '[' + staffName + ']';
        document.getElementById('boxStaffName').textContent = staffName;

        // Date
        document.getElementById('prevDateCell').textContent = dateInput.value || '[date]';

        // Four eyes check
        const approverOpt = approverSelect.options[approverSelect.selectedIndex];
        const checkApprover = document.getElementById('checkApproverFourEyes');
        if (staffOpt && approverOpt && staffOpt.value === approverOpt.value) {
            checkApprover.querySelector('span:first-child').className = 'check-icon-warn';
            checkApprover.querySelector('span:first-child').textContent = '!';
            checkApprover.querySelector('span:last-child').textContent = 'Warning: Approver cannot be the same as counter';
        } else {
            checkApprover.querySelector('span:first-child').className = 'check-icon-pass';
            checkApprover.querySelector('span:first-child').textContent = '✓';
            checkApprover.querySelector('span:last-child').textContent = 'Approver is not the assigned counter';
        }
    }

    rows.forEach(tr => {
        if (!tr.classList.contains('row-disabled')) {
            tr.addEventListener('click', function (e) {
                if (e.target.tagName !== 'INPUT') {
                    const r = tr.querySelector('input[type="radio"]');
                    if (r && !r.disabled) r.checked = true;
                }
                syncState();
            });
        }
    });

    assignedSelect.addEventListener('change', syncState);
    approverSelect.addEventListener('change', syncState);
    dateInput.addEventListener('change', syncState);

    document.getElementById('btnAssignNotify').addEventListener('click', function () {
        if (!validate()) return;
        document.getElementById('actionHidden').value = 'assign';
        document.getElementById('assignFormExact').submit();
    });

    document.getElementById('btnSaveDraft').addEventListener('click', function () {
        if (!validate()) return;
        document.getElementById('actionHidden').value = 'draft';
        document.getElementById('assignFormExact').submit();
    });

    function validate() {
        const radio = document.querySelector('input[name="location_id"]:checked');
        if (!radio) {
            alert('Please select a location to count.');
            return false;
        }
        if (assignedSelect.value === approverSelect.value) {
            alert('Four-eyes violation: The approver cannot be the assigned counter.');
            return false;
        }
        return true;
    }

    syncState();
});
</script>
@endsection
