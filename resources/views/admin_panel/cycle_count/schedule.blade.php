@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 2 */
    .img2-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: 100vh;
    }
    .img2-container {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Top Dark Bar */
    .img2-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img2-top-bar .title-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .img2-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
        letter-spacing: -0.2px;
    }
    .img2-top-bar .badge-pill-role {
        background: #2563eb;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img2-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* Sub-header Filter Toolbar */
    .img2-toolbar {
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .img2-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-assign-count {
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-assign-count:hover {
        background: #1d4ed8;
        color: #ffffff;
    }
    .filter-inline-select {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8rem;
        color: #475569;
    }
    .filter-select-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 0.8rem;
        color: #0f172a;
        cursor: pointer;
    }
    .toolbar-right-note {
        font-size: 0.76rem;
        color: #64748b;
    }

    /* Schedule Data Table */
    .img2-table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .img2-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.82rem;
    }
    .img2-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 8px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .img2-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }
    .img2-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Columns specific */
    .col-count-num {
        font-family: inherit;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
    }
    .col-loc-name {
        font-weight: 700;
        color: #0f172a;
    }
    .col-items-qty {
        font-weight: 500;
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-pill.started {
        background: #10b981;
        color: #ffffff;
    }
    .status-pill.not-started {
        background: #64748b;
        color: #ffffff;
    }
    .status-pill.submitted {
        background: #2563eb;
        color: #ffffff;
    }
    .status-pill.approved {
        background: #0f172a;
        color: #ffffff;
    }
    .status-waiting-note {
        font-size: 0.74rem;
        color: #2563eb;
        margin-left: 6px;
        font-weight: 500;
    }

    /* Progress Live Bar */
    .img2-progress-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 140px;
        max-width: 170px;
    }
    .img2-progress-track {
        flex: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }
    .img2-progress-fill {
        height: 100%;
        background: #10b981;
        border-radius: 3px;
    }
    .img2-progress-fraction {
        font-size: 0.72rem;
        color: #64748b;
        white-space: nowrap;
        min-width: 36px;
        text-align: right;
    }

    /* Action Links */
    .action-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .action-link:hover {
        text-decoration: underline;
    }
    .action-sep {
        color: #cbd5e1;
        margin: 0 4px;
    }

    /* Table Footer Summary */
    .img2-table-footer {
        padding: 8px 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.76rem;
        color: #475569;
        flex-wrap: wrap;
        gap: 8px;
    }
    .img2-table-footer-left {
        font-weight: 600;
    }
    .img2-table-footer-right {
        color: #94a3b8;
    }

    /* Bottom 4 Explanation Boxes */
    .img2-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }
    @media (max-width: 768px) {
        .img2-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
    .img2-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img2-box:last-child {
        border-right: none;
    }
    .img2-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img2-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }
</style>

<div class="img2-wrapper">
    <div class="img2-container">
        {{-- Top Dark Bar --}}
        <div class="img2-top-bar">
            <div class="title-section">
                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                    <span class="main-title">My Assigned Cycle Counts</span>
                    <span class="badge-pill-role" style="background: #2563eb; color: #ffffff;">Staff Portal · Physical Counting</span>
                @else
                    <span class="main-title">2 · Cycle Count — Schedule</span>
                    <span class="badge-pill-role">Owner / In-charge only</span>
                @endif
            </div>
            <div class="concept-note">
                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                    Physical count tasks assigned to you. Click <strong>Start Count</strong> to open the blind count sheet.
                @else
                    CONCEPT for approval · item names, dates and staff are illustrative
                @endif
            </div>
        </div>

        {{-- Sub-header Toolbar --}}
        <div class="img2-toolbar">
            <div class="img2-toolbar-left">
                @if(Auth::check() && Auth::user()->usertype === 'admin')
                    <a href="{{ route('cycle_count.create') }}" class="btn-assign-count">
                        + Assign cycle count
                    </a>

                    <a href="{{ route('product') }}?open_pi=1" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem; font-weight: 600; padding: 6px 12px; border-radius: 4px; text-decoration: none; border-color: #cbd5e1; color: #334155; background: #ffffff;">
                        <i class="fas fa-clipboard-check text-primary"></i> Open Audit Sheet (PI-101)
                    </a>
                @endif

                <form method="GET" action="{{ route('cycle_count.index') }}" id="filterFormSchedule" style="display: inline-flex; align-items: center; gap: 10px; margin: 0;">
                    <div class="filter-inline-select">
                        <span>Week</span>
                        <select name="week" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                            <option value="this_week" {{ request('week', 'this_week') == 'this_week' ? 'selected' : '' }}>[this week] ▼</option>
                            <option value="all" {{ request('week') == 'all' ? 'selected' : '' }}>[all weeks] ▼</option>
                            <option value="last_week" {{ request('week') == 'last_week' ? 'selected' : '' }}>[last week] ▼</option>
                            <option value="next_week" {{ request('week') == 'next_week' ? 'selected' : '' }}>[next week] ▼</option>
                        </select>
                    </div>

                    @if(Auth::check() && Auth::user()->usertype === 'admin')
                        <div class="filter-inline-select">
                            <span>Staff</span>
                            <select name="staff_id" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                                <option value="all" {{ request('staff_id') == 'all' ? 'selected' : '' }}>All ▼</option>
                                @foreach($allStaff as $st)
                                    <option value="{{ $st->id }}" {{ request('staff_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }} ▼
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="filter-inline-select">
                        <span>Status</span>
                        <select name="status" class="filter-select-input" onchange="document.getElementById('filterFormSchedule').submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All ▼</option>
                            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not started</option>
                            <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Started</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="toolbar-right-note">
                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                    Open your assigned rack/shelf, click <strong>Start Count</strong>, count items blindly, and submit.
                @else
                    Counts are created on screen 1 <strong>Assign</strong> and appear here at once as "Not started".
                @endif
            </div>
        </div>

        {{-- Main Schedule Table --}}
        <div class="img2-table-wrap">
            <table class="img2-table">
                <thead>
                    <tr>
                        <th>COUNT #</th>
                        <th>LOCATION</th>
                        <th>ITEMS</th>
                        <th>DATE</th>
                        <th>ASSIGNED TO</th>
                        <th>STATUS</th>
                        <th>PROGRESS (LIVE)</th>
                        <th>SUBMITTED</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counts as $count)
                        <tr>
                            <td>
                                <a href="{{ route('cycle_count.sheet', $count->id) }}" class="col-count-num">
                                    {{ $count->count_number }}
                                </a>
                            </td>
                            <td>
                                <span class="col-loc-name">
                                    {{ $count->location ? $count->location->name : ($count->location_name ?: 'SA3') }}
                                </span>
                            </td>
                            <td>
                                <span class="col-items-qty">{{ $count->total_items }}</span>
                            </td>
                            <td>
                                {{ $count->count_date ? $count->count_date->format('d M') : '[date]' }}
                            </td>
                            <td>
                                [{{ $count->counter ? $count->counter->name : 'Staff A' }}]
                            </td>
                            <td>
                                @if($count->status === 'started')
                                    <span class="status-pill started">
                                        Started {{ $count->started_at ? $count->started_at->format('H:i') : '' }}
                                    </span>
                                @elseif($count->status === 'submitted')
                                    <span class="status-pill submitted">Submitted</span>
                                    <span class="status-waiting-note">-- waiting for you</span>
                                @elseif($count->status === 'approved')
                                    <span class="status-pill approved">Approved</span>
                                @else
                                    <span class="status-pill not-started">Not started</span>
                                @endif
                            </td>
                            <td>
                                <div class="img2-progress-cell">
                                    <div class="img2-progress-track">
                                        <div class="img2-progress-fill" style="width: {{ $count->progress_percent }}%;"></div>
                                    </div>
                                    <span class="img2-progress-fraction">{{ $count->entered_items }}/{{ $count->total_items }}</span>
                                </div>
                            </td>
                            <td>
                                @if($count->submitted_at)
                                    {{ $count->submitted_at->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if(Auth::check() && Auth::user()->usertype !== 'admin')
                                    {{-- STAFF VIEW: Never sees Open Review or Admin Approval Screen --}}
                                    @if($count->status === 'not_started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="btn btn-sm btn-primary text-white" style="font-size: 0.78rem; padding: 4px 12px; font-weight: 700; border-radius: 4px;">
                                            <i class="fas fa-play me-1"></i> Start Count
                                        </a>
                                    @elseif($count->status === 'started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="btn btn-sm btn-success text-white" style="font-size: 0.78rem; padding: 4px 12px; font-weight: 700; border-radius: 4px;">
                                            <i class="fas fa-stopwatch me-1"></i> Continue Count
                                        </a>
                                    @elseif($count->status === 'submitted')
                                        <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.78rem; padding: 5px 10px; font-weight: 600;">
                                            <i class="fas fa-clock me-1"></i> Submitted (Waiting for Approval)
                                        </span>
                                    @elseif($count->status === 'approved')
                                        <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.78rem; padding: 5px 10px; font-weight: 600;">
                                            <i class="fas fa-check-circle me-1"></i> Completed &amp; Approved
                                        </span>
                                    @endif
                                @else
                                    {{-- ADMIN / IN-CHARGE VIEW --}}
                                    @if($count->status === 'submitted')
                                        <a href="{{ route('cycle_count.approve', $count->id) }}" class="btn btn-sm btn-primary text-white font-weight-bold" style="font-size: 0.78rem; padding: 4px 12px; border-radius: 4px;">
                                            <i class="fas fa-tasks me-1"></i> Open Review
                                        </a>
                                    @elseif($count->status === 'approved')
                                        <a href="{{ route('cycle_count.approve', $count->id) }}" class="action-link font-weight-bold">View Approval</a>
                                    @elseif($count->status === 'started')
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="action-link text-success font-weight-bold">Inspect Sheet</a>
                                    @else
                                        <a href="{{ route('cycle_count.sheet', $count->id) }}" class="action-link text-primary font-weight-bold">Count Sheet</a>
                                        <span class="action-sep ms-1">|</span>
                                        <a href="#" class="action-link" data-toggle="modal" data-target="#reassignModal{{ $count->id }}">Reassign</a>
                                        <span class="action-sep">|</span>
                                        <form action="{{ route('cycle_count.cancel', $count->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this count?');">
                                            @csrf
                                            <button type="submit" class="action-link text-danger" style="border:none; background:none; padding:0; cursor:pointer;">Cancel</button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Reassign Modal --}}
                                <div class="modal fade text-left" id="reassignModal{{ $count->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 8px;">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold">Reassign Count {{ $count->count_number }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{ route('cycle_count.reassign', $count->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Assign to Staff</label>
                                                        <select name="staff_id" class="form-control" required>
                                                            @foreach($allStaff as $st)
                                                                <option value="{{ $st->id }}" {{ $count->assigned_to == $st->id ? 'selected' : '' }}>
                                                                    {{ $st->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Confirm Reassign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">
                                No counts scheduled. Click <strong>+ Assign cycle count</strong> to schedule a physical count.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer Summary --}}
        <div class="img2-table-footer">
            <div class="img2-table-footer-left">
                {{ $totalCountsCount }} counts this week · {{ $inProgressCount }} started · {{ $pendingApproval }} waiting for approval · {{ $completedThisWeek }} approved
            </div>
            <div class="img2-table-footer-right">
                Status and progress change by themselves — no refresh, no asking.
            </div>
        </div>

        {{-- Bottom 4 Explanatory Boxes (Image 2) --}}
        <div class="img2-bottom-grid">
            @if(Auth::check() && Auth::user()->usertype !== 'admin')
                <div class="img2-box">
                    <h6>Your Task List</h6>
                    <p>Locations (racks and shelves) assigned to you for physical counting. Click <strong>Start Count</strong> to open your sheet.</p>
                </div>
                <div class="img2-box">
                    <h6>Blind Counting</h6>
                    <p>Computer stock is hidden so you count objectively what is physically on the shelf without guessing.</p>
                </div>
                <div class="img2-box">
                    <h6>Live Timer</h6>
                    <p>Click "Start Count Timer" before counting. Each counted quantity auto-saves instantly as you type.</p>
                </div>
                <div class="img2-box">
                    <h6>Manager Approval</h6>
                    <p>After you submit, the manager / in-charge reviews any discrepancies and approves stock adjustments.</p>
                </div>
            @else
                <div class="img2-box">
                    <h6>Assignment</h6>
                    <p>Done on screen 1 (Assign). Each saved assignment becomes one row here. Not-started counts can be reassigned or cancelled.</p>
                </div>
                <div class="img2-box">
                    <h6>Staff view</h6>
                    <p>A staff member sees only their own assigned counts — not this table.</p>
                </div>
                <div class="img2-box">
                    <h6>Live status</h6>
                    <p>"Started" the moment the staff opens the count. Progress counter updates as each quantity is entered. "Submitted" the moment they submit.</p>
                </div>
                <div class="img2-box">
                    <h6>Approval</h6>
                    <p>Only Owner / In-charge approves. A counter never approves their own count.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
