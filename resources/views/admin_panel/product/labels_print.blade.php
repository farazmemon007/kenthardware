<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->item_name }} - {{ $layout === 'a4' ? 'A4 Sheet Labels' : '38x26mm Labels' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════════════════════
           BASE RESET
           ══════════════════════════════════════════════════════════ */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            background: #f1f5f9;
            color: #000000;
            padding: {{ $layout === 'thermal' ? '15px' : '20px' }};
        }

        /* ══════════════════════════════════════════════════════════
           ON-SCREEN TOOLBAR & GUIDE
           ══════════════════════════════════════════════════════════ */
        .screen-toolbar {
            max-width: 780px;
            margin: 0 auto 16px auto;
            background: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .toolbar-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .toolbar-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }

        .toolbar-title-wrap h2 {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1px;
        }

        .toolbar-title-wrap p {
            font-size: 11px;
            color: #64748b;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-print-main {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-print-main:hover {
            background: #1d4ed8;
            color: #ffffff;
        }

        .btn-outline-cancel {
            background: #ffffff;
            color: #475569;
            border-color: #cbd5e1;
        }
        .btn-outline-cancel:hover {
            background: #f8fafc;
        }

        .toolbar-modes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .mode-btn-group {
            display: inline-flex;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 3px;
            border: 1px solid #e2e8f0;
        }

        .mode-btn {
            border: none;
            background: transparent;
            padding: 5px 12px;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 6px;
            color: #475569;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .mode-btn.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .mode-guide-note {
            font-size: 11px;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 6px;
            flex: 1;
            min-width: 260px;
        }

        /* ══════════════════════════════════════════════════════════
           PREVIEW & PRINT WRAPPER
           ══════════════════════════════════════════════════════════ */
        .labels-preview-wrap {
            @if($layout === 'a4')
                display: flex;
                flex-wrap: wrap;
                gap: 2mm;
                justify-content: flex-start;
                align-items: flex-start;
                max-width: 194mm;
                margin: 0 auto;
                background: #ffffff;
                padding: 10mm;
                box-shadow: 0 4px 18px rgba(0,0,0,0.08);
                border-radius: 6px;
            @else
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: center;
                align-items: flex-start;
                max-width: 900px;
                margin: 0 auto;
            @endif
        }

        /* ══════════════════════════════════════════════════════════
           EXACT 38mm x 26mm LABEL BOX (MATCHING PHOTO EXACTLY)
           ══════════════════════════════════════════════════════════ */
        .label-sticker {
            width: 38mm;
            height: 26mm;
            max-width: 38mm;
            max-height: 26mm;
            min-width: 38mm;
            min-height: 26mm;
            background: #ffffff;
            border: {{ $layout === 'a4' ? '0.5px dashed #94a3b8' : '1px dashed #cbd5e1' }};
            border-radius: 2px;
            padding: 1.4mm 2.2mm 1mm 2.2mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            overflow: hidden;
            box-sizing: border-box;
            position: relative;
            user-select: none;
            text-rendering: geometricPrecision;
            -webkit-font-smoothing: antialiased;
        }

        /* Line 1: Product Name & Variant */
        .sticker-line-1 {
            font-size: 8.5pt;
            font-weight: 700;
            color: #000000;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            line-height: 1.15;
            letter-spacing: -0.1px;
        }

        /* Serial No: directly below Product Name in larger bold font */
        .sticker-line-serial {
            font-size: 11pt;
            font-weight: 900;
            color: #000000;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            line-height: 1.15;
            letter-spacing: 0.3px;
        }

        /* Line 2: PCODE - ROOT */
        .sticker-line-2 {
            font-size: 9.8pt;
            font-weight: 800;
            color: #000000;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            line-height: 1.15;
            letter-spacing: 0.2px;
        }

        /* Line 3: LOCATION */
        .sticker-line-3 {
            font-size: 8.5pt;
            font-weight: 700;
            color: #000000;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            line-height: 1.15;
        }

        /* Line 4: Store Name in script font (Kent Hardware) */
        .sticker-line-4 {
            text-align: center;
            width: 100%;
            font-family: 'Caveat', 'Segoe Script', 'Brush Script MT', cursive;
            font-size: 11pt;
            font-weight: 700;
            color: #000000;
            line-height: 1;
            letter-spacing: 0.3px;
        }

        /* ══════════════════════════════════════════════════════════
           PRINT MEDIA QUERIES (SEPARATED FOR THERMAL VS A4)
           ══════════════════════════════════════════════════════════ */
        @if($layout === 'a4')
            @page {
                size: A4 portrait;
                margin: 8mm !important;
            }

            @media print {
                html, body {
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                .screen-toolbar, .no-print {
                    display: none !important;
                }

                .labels-preview-wrap {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 1.5mm !important;
                    justify-content: flex-start !important;
                    align-items: flex-start !important;
                    max-width: 194mm !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    border: none !important;
                }

                .label-sticker {
                    width: 38mm !important;
                    height: 26mm !important;
                    max-width: 38mm !important;
                    max-height: 26mm !important;
                    min-width: 38mm !important;
                    min-height: 26mm !important;
                    border: 0.4px dashed #94a3b8 !important;
                    page-break-after: auto !important;
                    break-after: auto !important;
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                    margin: 0 !important;
                    overflow: hidden !important;
                    padding: 1.4mm 2.2mm 1mm 2.2mm !important;
                }
            }
        @else
            @page {
                size: 38mm 26mm;
                margin: 0mm !important;
            }

            @media print {
                html, body {
                    width: 38mm !important;
                    height: 26mm !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
                    overflow: hidden !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                .screen-toolbar, .no-print {
                    display: none !important;
                }

                .labels-preview-wrap {
                    display: block !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 38mm !important;
                }

                .label-sticker {
                    width: 38mm !important;
                    height: 26mm !important;
                    max-width: 38mm !important;
                    max-height: 26mm !important;
                    min-width: 38mm !important;
                    min-height: 26mm !important;
                    border: none !important;
                    page-break-after: always !important;
                    page-break-inside: avoid !important;
                    break-after: page !important;
                    break-inside: avoid !important;
                    margin: 0 !important;
                    overflow: hidden !important;
                    padding: 1.4mm 2.2mm 1mm 2.2mm !important;
                }
            }
        @endif
    </style>
</head>
<body>

    <!-- On-screen top bar with Print button and Layout Switcher -->
    <div class="screen-toolbar no-print">
        <div class="toolbar-top">
            <div class="toolbar-title-wrap">
                <div class="toolbar-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div>
                    <h2>{{ $product->item_name }}</h2>
                    <p>Total Labels: <strong>{{ count($labelsToPrint) }}</strong> | Label Size: <strong>38mm &times; 26mm</strong></p>
                </div>
            </div>
            <div class="toolbar-actions">
                <button type="button" class="btn-action btn-outline-cancel" onclick="window.close();">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn-action btn-print-main" onclick="window.print();">
                    <i class="fas fa-print"></i> Print Labels Now
                </button>
            </div>
        </div>

        <div class="toolbar-modes">
            <div class="mode-btn-group">
                @php
                    $queryParams = request()->query();
                    $thermalParams = array_merge($queryParams, ['layout' => 'thermal']);
                    unset($thermalParams['autoprint']);
                    $a4Params = array_merge($queryParams, ['layout' => 'a4']);
                    unset($a4Params['autoprint']);
                @endphp
                <a href="{{ url()->current() }}?{{ http_build_query($thermalParams) }}" class="mode-btn {{ $layout === 'thermal' ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Thermal Roll (38&times;26mm)
                </a>
                <a href="{{ url()->current() }}?{{ http_build_query($a4Params) }}" class="mode-btn {{ $layout === 'a4' ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> A4 Sheet Grid (LaserJet)
                </a>
            </div>

            <div class="mode-guide-note">
                @if($layout === 'a4')
                    <i class="fas fa-info-circle text-primary me-1"></i> <strong>A4 Sheet Mode</strong>: HP LaserJet / Desktop printers par tamam labels ek hi A4 page par grid mein print honge.
                @else
                    <i class="fas fa-info-circle text-primary me-1"></i> <strong>Thermal Roll Mode</strong>: Print dialog mein apna Thermal printer select karein aur <em>Margins: None</em> rakhein.
                @endif
            </div>
        </div>
    </div>

    <!-- Labels Container -->
    <div class="labels-preview-wrap">
        @forelse($labelsToPrint as $label)
            <div class="label-sticker">
                <!-- Line 1: Product Name & Variant -->
                <div class="sticker-line-1">
                    {{ $label['line_1'] }}
                </div>

                <!-- Serial No (larger font directly below Line 1) -->
                @if(!empty($label['serial_no']))
                    <div class="sticker-line-serial">
                        {{ $label['serial_no'] }}
                    </div>
                @endif

                <!-- Line 2: PCODE - ROOT -->
                <div class="sticker-line-2">
                    {{ $label['line_2'] }}
                </div>

                <!-- Line 3: LOCATION -->
                <div class="sticker-line-3">
                    {{ $label['line_3'] ?: '—' }}
                </div>

                <!-- Line 4: Kent Hardware (Cursive) -->
                <div class="sticker-line-4">
                    {{ $companyName }}
                </div>
            </div>
        @empty
            <div class="no-print" style="text-align: center; padding: 40px; color: #64748b;">
                <p>No labels selected or available to print.</p>
            </div>
        @endforelse
    </div>

    <script>
        // Auto trigger print if requested with ?autoprint=1
        if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    window.print();
                }, 300);
            });
        }
    </script>
</body>
</html>
