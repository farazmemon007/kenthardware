@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Exact UI Styling Matching Reference Image 3 (Staff Blind Count Sheet) */
    .img3-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        padding: 12px;
        min-height: 100vh;
    }
    .img3-container {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Top Dark Bar */
    .img3-top-bar {
        background: #0b1329;
        color: #ffffff;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .img3-top-bar .title-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .img3-top-bar .main-title {
        font-weight: 700;
        font-size: 0.98rem;
    }
    .img3-top-bar .badge-pill-role {
        background: #10b981;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .img3-top-bar .concept-note {
        color: #94a3b8;
        font-size: 0.72rem;
    }

    /* Sub-header Bar */
    .img3-sub-bar {
        padding: 10px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.8rem;
    }
    .img3-sub-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        color: #475569;
    }
    .img3-sub-meta strong {
        color: #0f172a;
    }
    .img3-sub-entered {
        color: #10b981;
        font-weight: 700;
    }
    .blind-yellow-badge {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Timer Controls in Sub-bar */
    .timer-strip-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 4px 10px;
        border-radius: 4px;
    }
    .stopwatch-digital {
        font-family: 'JetBrains Mono', monospace, 'Courier New';
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: 0.5px;
    }
    .stopwatch-digital.running {
        color: #16a34a;
        animation: pulseText 1.5s infinite;
    }
    @keyframes pulseText {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.75; }
    }
    .btn-start-timer {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }
    .btn-start-timer:hover {
        background: #059669;
    }

    /* 2-Column Side-by-Side Split Grid */
    .img3-split-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-bottom: 1px solid #e2e8f0;
    }
    @media (max-width: 1100px) {
        .img3-split-grid {
            grid-template-columns: 1fr;
        }
    }
    .img3-half-col {
        border-right: 1px solid #e2e8f0;
        overflow-x: auto;
    }
    .img3-half-col:last-child {
        border-right: none;
    }

    /* Blind Count Table */
    .blind-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
    }
    .blind-table th {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        padding: 6px 8px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .blind-table td {
        padding: 5px 8px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
        height: 38px;
    }
    .blind-table tbody tr.row-active {
        background: #e0edff !important;
    }
    .blind-table tbody tr:hover {
        background: #f8fafc;
    }

    .col-idx {
        color: #94a3b8;
        font-weight: 600;
        width: 26px;
        text-align: right;
        padding-right: 4px !important;
    }
    .col-unique {
        font-family: monospace;
        font-weight: 700;
        color: #1e293b;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
        white-space: nowrap;
        display: inline-block;
    }
    .col-img-wrap {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        overflow: hidden;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #94a3b8;
    }
    .col-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .col-name-box {
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .col-name-title {
        color: #0f172a;
        font-weight: 600;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .col-cat-badge {
        font-size: 0.65rem;
        background: #f3e8ff;
        color: #7e22ce;
        padding: 1px 5px;
        border-radius: 3px;
        font-weight: 600;
        display: inline-block;
    }
    .col-barcode {
        font-size: 0.7rem;
        color: #475569;
        font-family: monospace;
        white-space: nowrap;
    }
    .col-unit {
        color: #64748b;
        width: 30px;
    }

    /* Count Input Box */
    .count-input-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        width: 78px;
    }
    .count-box-input {
        width: 52px;
        height: 26px;
        border: 1px solid #3b82f6;
        border-radius: 4px;
        background: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        padding: 0 4px;
    }
    .count-box-input:focus {
        border-color: #1d4ed8;
        outline: none;
        background: #f0fdf4;
    }
    .count-check-icon {
        color: #10b981;
        font-weight: 700;
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .count-check-icon.visible {
        opacity: 1;
    }

    /* Bottom Toolbar */
    .img3-bottom-bar {
        padding: 10px 18px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .search-scanner-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        color: #475569;
    }
    .search-scanner-input {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 0.8rem;
        width: 220px;
    }
    .search-scanner-input:focus {
        border-color: #2563eb;
        outline: none;
    }

    .btn-sheet-draft {
        background: #64748b;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-sheet-draft:hover {
        background: #475569;
    }
    .btn-sheet-submit {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-sheet-submit:hover {
        background: #059669;
    }

    /* Bottom 4 Explanation Boxes */
    .img3-bottom-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }
    @media (max-width: 768px) {
        .img3-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
    .img3-box {
        padding: 12px 16px;
        border-right: 1px solid #e2e8f0;
    }
    .img3-box:last-child {
        border-right: none;
    }
    .img3-box h6 {
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .img3-box p {
        font-size: 0.74rem;
        color: #475569;
        margin: 0;
        line-height: 1.35;
    }
</style>

<div class="img3-wrapper">
    <div class="img3-container">
        {{-- Top Dark Bar --}}
        <div class="img3-top-bar">
            <div class="title-section">
                <span class="main-title">3 · Count sheet {{ $cycleCount->count_number }} — Location {{ $cycleCount->location ? $cycleCount->location->name : ($cycleCount->location_name ?: 'General') }}</span>
                <span class="badge-pill-role">Staff screen · blind</span>
            </div>
            <div class="concept-note">
                BLIND AUDIT • System quantities hidden • Real-time duration tracked
            </div>
        </div>

        {{-- Sub-header Bar --}}
        <div class="img3-sub-bar">
            <div class="img3-sub-meta">
                <span>Location <strong>{{ $cycleCount->location ? $cycleCount->location->name : ($cycleCount->location_name ?: 'General') }}</strong></span>
                <span>Assigned to <strong>[{{ $cycleCount->counter ? $cycleCount->counter->name : 'Staff' }}]</strong></span>
                <span>Date <strong>{{ $cycleCount->count_date ? $cycleCount->count_date->format('d M Y') : date('d M Y') }}</strong></span>
                <span>Items <strong>{{ $cycleCount->total_items }}</strong></span>
                <span class="img3-sub-entered" id="enteredProgressText">Entered {{ $cycleCount->entered_items }} / {{ $cycleCount->total_items }}</span>
            </div>

            {{-- Timer Section --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="timer-strip-box">
                    <span style="font-size:0.75rem; color:#64748b; font-weight:600;"><i class="fas fa-stopwatch text-primary"></i> DURATION:</span>
                    <span class="stopwatch-digital {{ $cycleCount->started_at ? 'running' : '' }}" id="stopwatchDisplay">00:00:00</span>
                    @if(!$cycleCount->started_at && $cycleCount->status === 'not_started')
                        <button type="button" class="btn-start-timer" id="btnStartCountTimer" onclick="startTimerNow()">
                            <i class="fas fa-play"></i> Start Count
                        </button>
                    @endif
                </div>

                <div class="blind-yellow-badge">
                    <i class="fas fa-shield-alt text-warning"></i> Count what you see physically. System quantity is hidden.
                </div>
            </div>
        </div>

        @php
            $totalCount = $items->count();
            $midPoint = ceil($totalCount / 2);
            $leftItems = $items->slice(0, $midPoint);
            $rightItems = $items->slice($midPoint);
        @endphp

        {{-- 2-Column Side-by-Side Split Grid --}}
        <div class="img3-split-grid">
            {{-- Left Column (Items 1 to Mid) --}}
            <div class="img3-half-col">
                <table class="blind-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th style="width: 34px;">IMG</th>
                            <th>ITEM UNIQUE</th>
                            <th>PRODUCT &amp; VARIANT</th>
                            <th>BARCODE</th>
                            <th>UNIT</th>
                            <th>COUNTED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leftItems as $idx => $it)
                            <tr id="tr-item-{{ $it->id }}" data-barcode="{{ strtolower($it->barcode_path ?? '') }}" data-code="{{ strtolower($it->p_code ?: $it->item_code) }}" data-name="{{ strtolower($it->item_name) }}">
                                <td class="col-idx">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="col-img-wrap">
                                        @if($it->image_path)
                                            <img src="/uploads/products/{{ $it->image_path }}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'far fa-image\'></i>';">
                                        @else
                                            <i class="far fa-image"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="col-unique">{{ $it->p_code ?: ($it->item_code ?: 'ITEM-' . ($idx + 1)) }}</span>
                                </td>
                                <td>
                                    <div class="col-name-box">
                                        <span class="col-name-title" title="{{ $it->item_name }}">{{ $it->item_name }}</span>
                                        @if($it->category_name)
                                            <span class="col-cat-badge">{{ $it->category_name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-barcode">
                                    @if($it->barcode_path)
                                        <i class="fas fa-barcode text-muted me-1"></i>{{ $it->barcode_path }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="col-unit">{{ $it->unit ?: 'Pcs' }}</td>
                                <td>
                                    <div class="count-input-wrap">
                                        <input type="number" step="any" min="0"
                                               class="count-box-input"
                                               data-item-id="{{ $it->id }}"
                                               value="{{ $it->counted_qty !== null ? (float)$it->counted_qty : '' }}"
                                               placeholder="—"
                                               onfocus="highlightRow({{ $it->id }})"
                                               onblur="unhighlightRow({{ $it->id }}); saveCountedQty(this);">
                                        <span class="count-check-icon {{ $it->is_entered ? 'visible' : '' }}" id="check-icon-{{ $it->id }}">✓</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No items in this column.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Right Column (Items Mid+1 to End) --}}
            <div class="img3-half-col">
                <table class="blind-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th style="width: 34px;">IMG</th>
                            <th>ITEM UNIQUE</th>
                            <th>PRODUCT &amp; VARIANT</th>
                            <th>BARCODE</th>
                            <th>UNIT</th>
                            <th>COUNTED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rightItems as $idx => $it)
                            <tr id="tr-item-{{ $it->id }}" data-barcode="{{ strtolower($it->barcode_path ?? '') }}" data-code="{{ strtolower($it->p_code ?: $it->item_code) }}" data-name="{{ strtolower($it->item_name) }}">
                                <td class="col-idx">{{ $midPoint + $idx + 1 }}</td>
                                <td>
                                    <div class="col-img-wrap">
                                        @if($it->image_path)
                                            <img src="/uploads/products/{{ $it->image_path }}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'far fa-image\'></i>';">
                                        @else
                                            <i class="far fa-image"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="col-unique">{{ $it->p_code ?: ($it->item_code ?: 'ITEM-' . ($midPoint + $idx + 1)) }}</span>
                                </td>
                                <td>
                                    <div class="col-name-box">
                                        <span class="col-name-title" title="{{ $it->item_name }}">{{ $it->item_name }}</span>
                                        @if($it->category_name)
                                            <span class="col-cat-badge">{{ $it->category_name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-barcode">
                                    @if($it->barcode_path)
                                        <i class="fas fa-barcode text-muted me-1"></i>{{ $it->barcode_path }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="col-unit">{{ $it->unit ?: 'Pcs' }}</td>
                                <td>
                                    <div class="count-input-wrap">
                                        <input type="number" step="any" min="0"
                                               class="count-box-input"
                                               data-item-id="{{ $it->id }}"
                                               value="{{ $it->counted_qty !== null ? (float)$it->counted_qty : '' }}"
                                               placeholder="—"
                                               onfocus="highlightRow({{ $it->id }})"
                                               onblur="unhighlightRow({{ $it->id }}); saveCountedQty(this);">
                                        <span class="count-check-icon {{ $it->is_entered ? 'visible' : '' }}" id="check-icon-{{ $it->id }}">✓</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No items in this column.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Action Toolbar --}}
        <div class="img3-bottom-bar">
            <div class="search-scanner-box">
                <i class="fas fa-barcode text-secondary"></i>
                <span>Search / Scan Barcode:</span>
                <input type="text" id="quickScannerInput" class="search-scanner-input" placeholder="Type barcode, code or name...">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-sheet-draft" onclick="alert('Progress is automatically saved as you enter each count.')">Save draft</button>
                <button type="button" class="btn-sheet-submit" id="btnSubmitSheetCount">
                    Submit count (<span id="uncountedNumber">{{ max(0, $cycleCount->total_items - $cycleCount->entered_items) }}</span> left)
                </button>
            </div>
        </div>

        {{-- Bottom 4 Explanatory Boxes --}}
        <div class="img3-bottom-grid">
            <div class="img3-box">
                <h6>No system quantity</h6>
                <p>No "System qty" column, tooltip or total anywhere on this screen — before, during or after typing.</p>
            </div>
            <div class="img3-box">
                <h6>After Submit</h6>
                <p>Counter sees only "Submitted". No match / mismatch result is shown to the counter.</p>
            </div>
            <div class="img3-box">
                <h6>Live Timer &amp; Autosave</h6>
                <p>Each entered quantity is saved at once; the timer tracks exact duration for owner review.</p>
            </div>
            <div class="img3-box">
                <h6>Barcode Scanning</h6>
                <p>Scan barcode sticker on bin/shelf to jump directly to the item and enter physical count.</p>
            </div>
        </div>
    </div>
</div>

<script>
const countId = {{ $cycleCount->id }};
const totalItems = {{ $cycleCount->total_items }};
let enteredItems = {{ $cycleCount->entered_items }};
const csrfToken = '{{ csrf_token() }}';

// ── Stopwatch Timer Implementation ──
let stopwatchInterval = null;
let startedAtTimestamp = {{ $cycleCount->started_at ? $cycleCount->started_at->timestamp * 1000 : 'null' }};
const isStarted = {{ $cycleCount->started_at ? 'true' : 'false' }};

function formatStopwatch(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
}

function updateStopwatchDisplay() {
    if (!startedAtTimestamp) return;
    const now = Date.now();
    const elapsedSeconds = Math.max(0, Math.floor((now - startedAtTimestamp) / 1000));
    const el = document.getElementById('stopwatchDisplay');
    if (el) {
        el.textContent = formatStopwatch(elapsedSeconds);
        el.classList.add('running');
    }
}

if (isStarted && startedAtTimestamp) {
    updateStopwatchDisplay();
    stopwatchInterval = setInterval(updateStopwatchDisplay, 1000);
}

function startTimerNow() {
    const btn = document.getElementById('btnStartCountTimer');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
    }

    fetch('{{ route("cycle_count.start_timer", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            startedAtTimestamp = new Date(data.started_at).getTime();
            if (btn) btn.remove();
            updateStopwatchDisplay();
            if (!stopwatchInterval) {
                stopwatchInterval = setInterval(updateStopwatchDisplay, 1000);
            }
        } else {
            alert('Could not start timer: ' + (data.message || 'Error'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Start Count';
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play"></i> Start Count';
        }
    });
}

function highlightRow(id) {
    document.querySelectorAll('.blind-table tbody tr').forEach(r => r.classList.remove('row-active'));
    const tr = document.getElementById('tr-item-' + id);
    if (tr) tr.classList.add('row-active');
}

function unhighlightRow(id) {
    // Keep clean
}

function saveCountedQty(inputEl) {
    const itemId = inputEl.getAttribute('data-item-id');
    const val = inputEl.value.trim();
    const checkIcon = document.getElementById('check-icon-' + itemId);

    // If timer not started yet, automatically start timer on first input
    if (!startedAtTimestamp) {
        startTimerNow();
    }

    fetch('{{ route("cycle_count.save_item", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            item_id: itemId,
            counted_qty: val
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.is_entered) {
                checkIcon.classList.add('visible');
            } else {
                checkIcon.classList.remove('visible');
            }
            enteredItems = data.entered_items;
            document.getElementById('enteredProgressText').textContent = 'Entered ' + enteredItems + ' / ' + totalItems;
            document.getElementById('uncountedNumber').textContent = Math.max(0, totalItems - enteredItems);
        }
    })
    .catch(err => console.error(err));
}

// Quick Search / Barcode Scanner input
document.getElementById('quickScannerInput').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    if (q.length < 2) return;

    const rows = document.querySelectorAll('.blind-table tbody tr');
    for (let r of rows) {
        const barcode = r.getAttribute('data-barcode') || '';
        const code = r.getAttribute('data-code') || '';
        const name = r.getAttribute('data-name') || '';

        if (barcode === q || code === q || barcode.includes(q) || code.includes(q) || name.includes(q) || r.innerText.toLowerCase().includes(q)) {
            r.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelectorAll('.blind-table tbody tr').forEach(row => row.classList.remove('row-active'));
            r.classList.add('row-active');
            const inp = r.querySelector('.count-box-input');
            if (inp) {
                inp.focus();
                inp.select();
            }
            break;
        }
    }
});

// Final Submit
document.getElementById('btnSubmitSheetCount').addEventListener('click', function () {
    const left = Math.max(0, totalItems - enteredItems);
    let msg = 'Submit count sheet for approval?';
    if (left > 0) {
        msg = 'You have ' + left + ' unentered item(s). Submit count sheet anyway?';
    }
    if (!confirm(msg)) return;

    this.disabled = true;
    this.textContent = 'Submitting...';

    if (stopwatchInterval) {
        clearInterval(stopwatchInterval);
    }

    fetch('{{ route("cycle_count.submit", $cycleCount->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + data.message);
            this.disabled = false;
        }
    })
    .catch(err => {
        alert('Connection error submitting count.');
        this.disabled = false;
    });
});
</script>
@endsection
