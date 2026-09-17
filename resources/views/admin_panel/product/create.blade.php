@extends('admin_panel.layout.app')

@section('content')
    {{-- External Resources --}}
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-section-header: #f8fafc;
            --bg-section-body: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-strong: #cbd5e1;
            --border-light: #e2e8f0;
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            font-size: 13px;
        }

        .page-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 16px 16px 60px 16px;
        }

        /* Top Bar */
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .back-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--border-strong);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: all 0.15s;
            text-decoration: none;
            font-size: 12px;
        }

        .back-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            background: var(--primary-light);
        }

        /* Top Product Identity Section */
        .product-identity-card {
            background: #ffffff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-lg);
            padding: 14px 18px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            margin-bottom: 14px;
        }

        .title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .star-fav-btn {
            background: transparent;
            border: 1px solid var(--border-strong);
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #cbd5e1;
            cursor: pointer;
            line-height: 1;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .star-fav-btn:hover {
            color: #f59e0b;
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .star-fav-btn.active {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #f59e0b;
        }

        .product-name-input {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-md);
            outline: none;
            width: 100%;
            height: 38px;
            background: #ffffff;
            padding: 4px 12px;
            line-height: 1.2;
            transition: all 0.15s ease-in-out;
        }

        .product-name-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
            font-size: 1.2rem;
        }

        .product-name-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        /* Operational Checkboxes */
        .flags-row {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .odoo-check-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            user-select: none;
            margin: 0;
        }

        .odoo-check-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            cursor: pointer;
            accent-color: #0d9488;
        }

        .tooltip-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            cursor: help;
            margin-left: 2px;
        }

        .tooltip-icon:hover {
            color: var(--primary);
        }

        /* Product Image Upload Box */
        .product-avatar-box {
            width: 95px;
            height: 85px;
            border: 1.5px dashed #94a3b8;
            border-radius: var(--radius-md);
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.15s ease;
        }

        .product-avatar-box:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .product-avatar-box .cam-icon-wrap {
            position: relative;
            display: inline-block;
        }

        .product-avatar-box .cam-icon {
            font-size: 1.8rem;
            color: #64748b;
        }

        .product-avatar-box .cam-plus {
            position: absolute;
            bottom: -2px;
            right: -5px;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            border: 1.5px solid #fff;
            font-weight: bold;
        }

        .product-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #fff;
        }

        .clear-img-btn {
            position: absolute;
            top: 3px;
            right: 3px;
            background: rgba(239, 68, 68, 0.95);
            color: #fff;
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 12px;
            cursor: pointer;
            z-index: 5;
        }

        /* Tabs Nav - Clean Odoo Style */
        .odoo-tabs-container {
            margin-bottom: 16px;
        }

        .odoo-tabs-nav {
            display: flex;
            align-items: center;
            gap: 2px;
            background: #edf2f7;
            border: 1px solid var(--border-strong);
            border-bottom: 1.5px solid var(--border-strong);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            padding: 6px 8px 0 8px;
            overflow-x: auto;
            scrollbar-width: none;
            margin: 0;
            list-style: none;
        }

        .odoo-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .odoo-tab-item {
            white-space: nowrap;
        }

        .odoo-tab-link {
            display: block;
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            border: 1px solid transparent;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            transition: all 0.12s ease;
            cursor: pointer;
            background: transparent;
        }

        .odoo-tab-link:hover {
            color: var(--primary);
            background: rgba(255,255,255,0.7);
        }

        .odoo-tab-link.active {
            color: var(--primary);
            background: #ffffff;
            font-weight: 700;
            border-color: var(--border-strong);
            border-top: 2.5px solid var(--primary);
        }

        .odoo-tabs-content-wrap {
            border: 1px solid var(--border-strong);
            border-top: none;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            background: #ffffff;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .odoo-tab-pane {
            display: none;
        }

        .odoo-tab-pane.active {
            display: block;
            animation: fadeIn 0.15s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(2px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Sleek ERP Section Card */
        .erp-section-card {
            background: #ffffff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            overflow: hidden;
        }

        .erp-section-header {
            background: var(--bg-section-header);
            padding: 7px 12px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .erp-section-title {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }

        .erp-section-body {
            padding: 10px 14px;
            background: #ffffff;
        }

        /* Compact Form Row Item */
        .form-row-item {
            display: flex;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px dashed var(--border-light);
            margin-bottom: 0;
            min-height: 33px;
        }

        .form-row-item:last-child {
            border-bottom: none;
        }

        .field-label {
            width: 125px;
            flex-shrink: 0;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 0;
        }

        .field-value {
            flex: 1;
            min-width: 0;
        }

        /* Compact Sleek Inputs & Selects */
        .odoo-input {
            width: 100%;
            height: 30px;
            padding: 3px 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            background: #ffffff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            outline: none;
            transition: all 0.12s ease-in-out;
        }

        .odoo-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        .odoo-select {
            width: 100%;
            height: 30px;
            padding: 3px 26px 3px 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") no-repeat right 8px center;
            background-size: 10px;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: all 0.12s ease-in-out;
        }

        .odoo-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        /* Radio Options */
        .radio-group-odoo {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .odoo-radio {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            margin: 0;
        }

        .odoo-radio input[type="radio"] {
            width: 14px;
            height: 14px;
            accent-color: #0d9488;
            cursor: pointer;
        }

        /* Compact Switch Toggle */
        .odoo-switch {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            margin: 0;
            vertical-align: middle;
        }

        .odoo-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider-round {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .2s;
            border-radius: 20px;
            border: 1px solid #94a3b8;
        }

        .slider-round:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .2s;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .odoo-switch input:checked + .slider-round {
            background-color: #10b981;
            border-color: #059669;
        }

        .odoo-switch input:checked + .slider-round:before {
            transform: translateX(16px);
        }

        /* Currency & Units */
        .currency-input-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .currency-prefix {
            font-size: 12.5px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
        }

        .unit-suffix {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            white-space: nowrap;
        }

        /* Tax Tag Badge */
        .tax-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid var(--border-strong);
            padding: 2px 8px;
            border-radius: 14px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            height: 24px;
        }

        .tax-pill-badge:hover {
            background: #e2e8f0;
        }

        .tax-pill-badge .close-tax {
            font-size: 12px;
            color: #64748b;
            cursor: pointer;
            font-weight: bold;
        }

        .tax-pill-badge .close-tax:hover {
            color: #ef4444;
        }

        /* Quick Add Modal Button */
        .btn-quick-add {
            background: #f8fafc;
            border: 1px solid var(--border-strong);
            color: var(--primary);
            font-weight: 700;
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            font-size: 14px;
            transition: all 0.12s;
            flex-shrink: 0;
            padding: 0;
        }

        .btn-quick-add:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Internal Notes Textarea */
        .notes-textarea {
            width: 100%;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-md);
            padding: 8px 12px;
            font-size: 12.5px;
            color: var(--text-main);
            background: #ffffff;
            outline: none;
            resize: vertical;
            min-height: 65px;
            transition: border-color 0.12s;
        }

        .notes-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        /* =========================================================
           ATTRIBUTES & VARIANTS STYLING (MATCHING ODOO SCREENSHOT)
           ========================================================= */
        .attr-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .attr-table th {
            font-size: 12.5px !important;
            font-weight: 700 !important;
            color: #334155 !important;
            background: #f8fafc !important;
            padding: 8px 10px !important;
            border-bottom: 1.5px solid var(--border-strong) !important;
        }

        .attr-table td {
            padding: 6px 8px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid var(--border-light) !important;
        }

        .attr-grip-handle {
            cursor: grab;
            color: #94a3b8;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .attr-grip-handle:hover {
            color: var(--primary);
        }

        .attr-input-wrap {
            position: relative;
        }

        .attr-name-input {
            width: 100%;
            height: 30px;
            padding: 3px 26px 3px 10px;
            font-size: 13px;
            font-weight: 500;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            outline: none;
        }

        .attr-name-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        .attr-caret-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #00a09d;
            font-size: 11px;
            cursor: pointer;
            padding: 2px;
        }

        .attr-dropdown-menu {
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            min-width: 280px;
            width: 100%;
            max-height: 260px;
            overflow-y: auto;
            background: #222736;
            border: 1px solid #384252;
            border-radius: 4px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.45);
            z-index: 1060;
            padding: 4px 0;
            display: none;
            list-style: none;
            margin: 0;
        }

        .attr-dropdown-menu.show {
            display: block;
        }

        .attr-dropdown-item {
            padding: 6px 14px;
            font-size: 13px;
            color: #e2e8f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.1s, color 0.1s;
        }

        .attr-dropdown-item:hover,
        .attr-dropdown-item.highlighted {
            background: #2b3346;
            color: #38bdf8;
        }

        .attr-dropdown-search-more {
            padding: 8px 14px 7px 14px;
            font-size: 12.5px;
            color: #00a09d !important;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            border-top: 1px solid #2e3748;
            background: #1f2432;
            transition: background 0.1s, color 0.1s;
        }

        .attr-dropdown-search-more:hover {
            background: #262d3e;
            color: #14b8a6 !important;
            text-decoration: underline;
        }

        /* Search: Attribute Modal Custom Styles (Matching Image 2 exactly) */
        #searchAttributeModal .modal-content {
            background-color: #212530 !important;
            border: 1px solid #333d4e !important;
            border-radius: 6px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75) !important;
            overflow: hidden !important;
            color: #e2e8f0 !important;
        }

        #searchAttributeModal .modal-header {
            background-color: #212530 !important;
            border-bottom: 1px solid #2e3544 !important;
            color: #ffffff !important;
            padding: 10px 18px !important;
        }

        #searchAttributeModal .modal-header .modal-title {
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            letter-spacing: 0.01em;
        }

        #searchAttributeModal .modal-header .btn-close-attr-modal {
            color: #94a3b8 !important;
            font-size: 18px !important;
            background: transparent !important;
            border: none !important;
            cursor: pointer;
            line-height: 1;
            padding: 2px 6px;
            transition: color 0.15s ease;
        }

        #searchAttributeModal .modal-header .btn-close-attr-modal:hover {
            color: #ffffff !important;
        }

        #searchAttributeModal .attr-modal-subbar {
            background-color: #252a37 !important;
            border-bottom: 1px solid #2e3544 !important;
            padding: 8px 18px !important;
        }

        #searchAttributeModal .attr-modal-search-box {
            position: relative;
            width: 380px;
            max-width: 100%;
            margin: 0 auto;
        }

        #searchAttributeModal .attr-modal-search-box input {
            background-color: #171b23 !important;
            border: 1px solid #00a09d !important;
            border-radius: 4px !important;
            color: #ffffff !important;
            height: 31px !important;
            padding: 4px 28px 4px 30px !important;
            font-size: 12.5px !important;
            box-shadow: none !important;
        }

        #searchAttributeModal .attr-modal-search-box input::placeholder {
            color: #94a3b8 !important;
            opacity: 0.85;
        }

        #searchAttributeModal .modal-body {
            background-color: #212530 !important;
            color: #e2e8f0 !important;
            max-height: 440px;
            overflow-y: auto;
        }

        /* Custom Scrollbar for Modal Body */
        #searchAttributeModal .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        #searchAttributeModal .modal-body::-webkit-scrollbar-track {
            background: #171b23;
        }
        #searchAttributeModal .modal-body::-webkit-scrollbar-thumb {
            background: #3c4456;
            border-radius: 4px;
        }
        #searchAttributeModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: #505a72;
        }

        /* Modal Table & Cells (Force full dark background overriding Bootstrap defaults) */
        #searchAttributeModal table,
        #searchAttributeModal .attr-modal-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            background-color: #212530 !important;
            color: #e2e8f0 !important;
            margin: 0 !important;
        }

        #searchAttributeModal .attr-modal-table thead {
            background-color: #212530 !important;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        #searchAttributeModal .attr-modal-table thead tr {
            background-color: #212530 !important;
        }

        #searchAttributeModal .attr-modal-table thead th {
            background-color: #212530 !important;
            color: #cbd5e1 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 9px 14px !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1.5px solid #2e3544 !important;
            letter-spacing: 0.01em;
        }

        #searchAttributeModal .attr-modal-table tbody {
            background-color: #212530 !important;
        }

        #searchAttributeModal .attr-modal-table tbody tr {
            background-color: #212530 !important;
            cursor: pointer;
            transition: background-color 0.12s ease;
        }

        #searchAttributeModal .attr-modal-table tbody tr:hover,
        #searchAttributeModal .attr-modal-table tbody tr:hover td {
            background-color: #2b3342 !important;
        }

        #searchAttributeModal .attr-modal-table tbody tr.active-row,
        #searchAttributeModal .attr-modal-table tbody tr.active-row td {
            background-color: #373e52 !important;
        }

        #searchAttributeModal .attr-modal-table tbody td {
            background-color: #212530 !important;
            color: #cbd5e1 !important;
            font-size: 13px !important;
            padding: 9px 14px !important;
            vertical-align: middle !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1px solid #2b3240 !important;
        }

        #searchAttributeModal .attr-col-name {
            color: #ffffff !important;
            font-weight: 500 !important;
            font-size: 13px !important;
        }

        #searchAttributeModal .attr-col-meta {
            color: #94a3b8 !important;
            font-size: 13px !important;
        }

        #searchAttributeModal .attr-modal-grip {
            color: #64748b !important;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #searchAttributeModal .modal-footer {
            background-color: #212530 !important;
            border-top: 1px solid #2e3544 !important;
            padding: 10px 18px !important;
        }

        /* ========================================================= */
        /* Create Attribute Modal (Image 3 exact replica) */
        /* ========================================================= */
        #createAttributeModal .modal-content {
            background-color: #212530 !important;
            border: 1px solid #333d4e !important;
            border-radius: 6px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75) !important;
            overflow: hidden !important;
            color: #e2e8f0 !important;
        }

        #createAttributeModal .modal-header {
            background-color: #212530 !important;
            border-bottom: 1px solid #2e3544 !important;
            padding: 10px 18px !important;
        }

        #createAttributeModal .modal-header .modal-title {
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 600 !important;
        }

        #createAttributeModal .modal-body {
            background-color: #212530 !important;
            color: #e2e8f0 !important;
            padding: 22px 24px !important;
        }

        #createAttributeModal .create-attr-title-input {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid transparent !important;
            color: #ffffff !important;
            font-size: 24px !important;
            font-weight: 400 !important;
            width: 75% !important;
            padding: 4px 0 8px 0 !important;
            outline: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        #createAttributeModal .create-attr-title-input::placeholder {
            color: #64748b !important;
            opacity: 0.85;
        }

        #createAttributeModal .create-attr-title-input:focus {
            border-bottom: 1.5px solid #00a09d !important;
        }

        #createAttributeModal .create-attr-select {
            background-color: transparent !important;
            border: none !important;
            color: #ffffff !important;
            border-radius: 0 !important;
            padding-left: 0 !important;
            padding-right: 22px !important;
            font-size: 13.5px !important;
            cursor: pointer;
            box-shadow: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right center !important;
            background-size: 12px 10px !important;
        }

        #createAttributeModal .create-attr-select option {
            background-color: #212530 !important;
            color: #ffffff !important;
        }

        #createAttributeModal .create-attr-radio {
            accent-color: #00a09d;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        #createAttributeModal .attr-help-q {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 11px;
            margin-left: 3px;
            font-weight: 700;
            cursor: help;
        }

        #createAttributeModal .create-attr-tab {
            background: transparent !important;
            border: none !important;
            border-top: 2px solid #b854a6 !important;
            color: #d946ef !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            padding: 8px 16px !important;
            border-radius: 0 !important;
            letter-spacing: 0.01em;
        }

        #createAttributeModal .create-attr-table {
            width: 100% !important;
            border-collapse: collapse !important;
            background-color: #212530 !important;
            margin: 0 !important;
        }

        #createAttributeModal .create-attr-table thead th {
            background-color: #212530 !important;
            color: #e2e8f0 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 9px 14px !important;
            border-top: 1px solid #2e3544 !important;
            border-bottom: 1px solid #2e3544 !important;
            border-left: none !important;
            border-right: none !important;
        }

        #createAttributeModal .create-attr-table tbody tr {
            background-color: #212530 !important;
            border-bottom: 1px solid #2a313e !important;
        }

        #createAttributeModal .create-attr-table tbody td {
            background-color: #212530 !important;
            color: #e2e8f0 !important;
            padding: 6px 14px !important;
            vertical-align: middle !important;
            border: none !important;
            border-bottom: 1px solid #2a313e !important;
        }

        #createAttributeModal .create-attr-val-field {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid #4a5568 !important;
            color: #ffffff !important;
            border-radius: 0 !important;
            padding: 3px 0 5px 0 !important;
            font-size: 13px !important;
            width: 100% !important;
            box-shadow: none !important;
            outline: none !important;
        }

        #createAttributeModal .create-attr-val-field:focus {
            border-bottom-color: #ffffff !important;
        }

        #createAttributeModal .create-attr-val-field::placeholder {
            color: #64748b !important;
            opacity: 0.6;
        }

        #createAttributeModal .create-attr-price-field {
            background: transparent !important;
            border: none !important;
            color: #cbd5e1 !important;
            text-align: right !important;
            font-size: 13px !important;
            width: 70px !important;
            margin-left: auto !important;
            padding: 3px 0 !important;
            box-shadow: none !important;
            outline: none !important;
        }

        #createAttributeModal .create-attr-price-field:focus {
            border-bottom: 1px solid #00a09d !important;
            color: #ffffff !important;
        }

        #createAttributeModal .delete-val-row-btn {
            color: #64748b !important;
            transition: color 0.15s ease;
        }

        #createAttributeModal .delete-val-row-btn:hover {
            color: #ef4444 !important;
        }

        #createAttributeModal .modal-footer {
            background-color: #212530 !important;
            border-top: 1px solid #2e3544 !important;
            padding: 10px 18px !important;
            justify-content: flex-start !important;
        }

        /* Values Multi-Tag Container */
        .values-tag-box {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            min-height: 30px;
            padding: 2px 8px;
            background: #ffffff;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            cursor: text;
            transition: border-color 0.12s;
        }

        .values-tag-box:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
        }

        .tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #eef2ff;
            color: var(--primary);
            border: 1px solid #c7d2fe;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }

        .tag-pill .remove-tag {
            cursor: pointer;
            font-size: 13px;
            color: #818cf8;
            font-weight: bold;
        }

        .tag-pill .remove-tag:hover {
            color: #ef4444;
        }

        .tag-input-field {
            border: none;
            outline: none;
            font-size: 13px;
            padding: 2px 4px;
            flex: 1;
            min-width: 160px;
            background: transparent;
            color: #0f172a;
        }

        .tag-input-field::placeholder {
            color: #94a3b8;
            font-size: 12.5px;
        }

        .btn-configure-attr {
            background: #334155;
            color: #ffffff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.12s;
            cursor: pointer;
        }

        .btn-configure-attr:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .btn-delete-attr {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 13px;
            padding: 3px 5px;
            cursor: pointer;
            transition: color 0.12s;
        }

        .btn-delete-attr:hover {
            color: #ef4444;
        }

        .add-line-btn {
            color: #00a09d;
            background: transparent;
            border: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.12s;
        }

        .add-line-btn:hover {
            color: #008784;
            text-decoration: underline;
        }

        /* ========================================================= */
        /* Top Smart Stat Buttons (Odoo Style)                       */
        /* ========================================================= */
        .odoo-smart-buttons-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .odoo-smart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            text-decoration: none;
        }

        .odoo-smart-btn:hover {
            border-color: #00a09d;
            background: #f0fdfa;
        }

        .odoo-smart-btn .smart-btn-icon {
            font-size: 15px;
            color: #00a09d;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .odoo-smart-btn .smart-btn-meta {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.1;
        }

        .odoo-smart-btn .smart-btn-val {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .odoo-smart-btn .smart-btn-lbl {
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        /* ========================================================= */
        /* Configure Attribute Values Modal (Odoo Exclude For)       */
        /* ========================================================= */
        #configureAttributeModal .modal-content {
            background-color: #212530 !important;
            border: 1px solid #333d4e !important;
            border-radius: 6px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75) !important;
            overflow: hidden !important;
            color: #e2e8f0 !important;
        }

        #configureAttributeModal .config-attr-table thead th {
            background-color: #212530 !important;
            color: #cbd5e1 !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            padding: 9px 14px !important;
            border-bottom: 1px solid #2e3544 !important;
            border-top: none !important;
        }

        #configureAttributeModal .config-attr-table tbody tr {
            background-color: #212530 !important;
            border-bottom: 1px solid #2a313e !important;
            transition: background-color 0.12s ease;
        }

        #configureAttributeModal .config-attr-table tbody tr:hover {
            background-color: #282e3c !important;
        }

        #configureAttributeModal .config-attr-table tbody td {
            padding: 8px 14px !important;
            vertical-align: middle !important;
            color: #e2e8f0 !important;
            font-size: 13px !important;
            border: none !important;
            border-bottom: 1px solid #2a313e !important;
        }

        /* Exclude For Pills & Dropdown */
        .exclude-tag-box {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            min-height: 32px;
            padding: 3px 6px;
            background: #171b23;
            border: 1px solid #333d4e;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
        }

        .exclude-tag-box:focus-within,
        .exclude-tag-box.active-focus {
            border-color: #00a09d;
        }

        .exclude-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-radius: 12px;
            padding: 1px 8px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.4;
        }

        .exclude-pill .exclude-remove {
            cursor: pointer;
            color: #fca5a5;
            font-size: 12px;
            font-weight: bold;
            line-height: 1;
        }

        .exclude-pill .exclude-remove:hover {
            color: #ffffff;
        }

        .exclude-input-field {
            border: none;
            outline: none;
            background: transparent;
            color: #ffffff;
            font-size: 12px;
            padding: 2px;
            flex: 1;
            min-width: 80px;
        }

        .exclude-input-field::placeholder {
            color: #64748b;
            font-size: 11.5px;
        }

        /* Active Checkbox Styling in Config Modal */
        .config-active-check {
            accent-color: #00a09d;
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        /* Extra Price Input in Config Modal */
        .config-extra-price-input {
            background: transparent !important;
            border: 1px solid #333d4e !important;
            color: #ffffff !important;
            border-radius: 4px !important;
            padding: 3px 8px !important;
            font-size: 12.5px !important;
            text-align: right !important;
            width: 100px !important;
            outline: none !important;
        }

        .config-extra-price-input:focus {
            border-color: #00a09d !important;
        }

        /* Floating Exclude Dropdown */
        .exclude-dropdown-menu {
            position: fixed !important;
            z-index: 10000015 !important;
            background: #1e2430 !important;
            border: 1px solid #384252 !important;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.7) !important;
            max-height: 220px;
            overflow-y: auto;
            display: none;
            margin: 0;
            padding: 4px 0;
            list-style: none;
        }

        .exclude-dropdown-item {
            padding: 6px 12px;
            font-size: 12px;
            color: #cbd5e1;
            cursor: pointer;
            transition: background 0.1s;
        }

        .exclude-dropdown-item:hover {
            background: #2b3345;
            color: #ffffff;
        }

        /* Floating Inline Values Suggestion Dropdown */
        .values-suggest-dropdown {
            position: fixed !important;
            z-index: 999999 !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
            max-height: 240px;
            overflow-y: auto;
            display: none;
            margin: 0;
            padding: 4px 0;
            list-style: none;
        }

        .values-suggest-item {
            padding: 6px 12px;
            font-size: 12.5px;
            color: #1e293b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .values-suggest-item:hover,
        .values-suggest-item.active {
            background: #f1f5f9;
            color: #0f172a;
        }

        .values-suggest-create {
            color: #00a09d !important;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
        }

        .values-suggest-create:hover {
            background: #f0fdfa !important;
            color: #008784 !important;
        }

        /* Odoo-style Variant Table Combination Badge */
        .variant-combo-pill {
            display: inline-block;
            background: #e2e8f0;
            color: #334155;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            margin: 1px 2px;
            border: 1px solid #cbd5e1;
        }

        /* Matrix Table */
        #variantsTable {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
        }

        #variantsTable th {
            background: #f8fafc !important;
            color: #334155;
            font-size: 11px !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 6px 6px !important;
            border-bottom: 1.5px solid var(--border-strong);
            white-space: nowrap;
        }

        #variantsTable td {
            padding: 4px 4px !important;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
        }

        #variantsTable .form-control-sm, 
        #variantsTable .form-select-sm {
            height: 28px;
            font-size: 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-strong);
            padding: 2px 6px;
        }

        /* Action Buttons */
        .btn-primary-odoo {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 7px 20px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.25);
            transition: all 0.15s;
        }

        .btn-primary-odoo:hover {
            background: var(--primary-hover);
            color: #fff;
        }

        .btn-cancel-odoo {
            background: #fff;
            color: #475569;
            border: 1px solid var(--border-strong);
            padding: 7px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.12s;
        }

        .btn-cancel-odoo:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .page-container {
                padding: 10px 8px 70px 8px;
            }
            .product-identity-card {
                padding: 12px;
            }
            .title-row {
                flex-wrap: wrap;
            }
            .image-box-container {
                margin-top: 10px;
            }
            .odoo-tabs-content-wrap {
                padding: 12px;
            }
            .form-row-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
                min-height: auto;
                padding: 8px 0;
            }
            .field-label {
                width: 100%;
            }
            .desktop-action-bar {
                display: none !important;
            }
            .mobile-action-bar {
                display: flex !important;
            }
        }

        .mobile-action-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border-top: 1.5px solid var(--border-strong);
            padding: 10px 14px;
            box-shadow: 0 -3px 15px rgba(0,0,0,0.08);
            gap: 10px;
        }
    </style>

    <div class="page-container">

        {{-- Page Top Header --}}
        <div class="page-header-bar">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('product') }}" class="back-btn" title="Back to Products">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">New Product</h5>
                    <small class="text-muted" style="font-size: 0.78rem;">Configure product catalog details</small>
                </div>
            </div>

            {{-- Action Buttons (Top Right Desktop) --}}
            <div class="d-none d-md-flex align-items-center gap-2">
                <a href="{{ route('product') }}" class="btn-cancel-odoo">Cancel</a>
                <button type="button" class="btn-primary-odoo" onclick="document.getElementById('productForm').requestSubmit()">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </div>
        </div>

        {{-- Main Form --}}
        <form id="productForm" action="{{ route('store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. TOP PRODUCT IDENTITY CARD --}}
            <div class="product-identity-card">
                <div class="row align-items-center g-2">
                    
                    {{-- Left / Middle: Star + Title + Flags --}}
                    <div class="col-lg-9 col-md-8">
                        <div class="title-row">
                            <button type="button" class="star-fav-btn" id="starToggle" title="Toggle Favorite">
                                <i class="far fa-star"></i>
                            </button>
                            <input type="hidden" name="is_favorite" id="is_favorite_input" value="0">

                            <div class="flex-grow-1">
                                <input type="text" 
                                       class="product-name-input" 
                                       name="product_name" 
                                       id="product_name" 
                                       placeholder="e.g. Cheese Burger" 
                                       autocomplete="off" 
                                       required>
                            </div>
                        </div>

                        {{-- Operational Checkboxes --}}
                        <div class="flags-row">
                            <label class="odoo-check-label">
                                <input type="checkbox" name="can_be_sold" id="can_be_sold" value="1" checked>
                                <span>Sales</span>
                            </label>

                            <label class="odoo-check-label">
                                <input type="checkbox" name="can_be_purchased" id="can_be_purchased" value="1" checked>
                                <span>Purchase</span>
                            </label>

                            <label class="odoo-check-label">
                                <input type="checkbox" name="point_of_sale" id="point_of_sale" value="1">
                                <span>Point of Sale</span>
                                <span class="tooltip-icon" data-bs-toggle="tooltip" title="Available in Point of Sale (POS) register"><i class="fas fa-question-circle"></i></span>
                            </label>
                        </div>
                    </div>

                    {{-- Right: Product Image Box & Smart Stat Buttons --}}
                    <div class="col-lg-3 col-md-4">
                        <div class="d-flex align-items-center justify-content-md-end gap-3">
                            <div class="odoo-smart-buttons-wrap">
                                <button type="button" class="odoo-smart-btn" id="topVariantsStatBtn" title="View Product Variants">
                                    <div class="smart-btn-icon"><i class="fas fa-layer-group"></i></div>
                                    <div class="smart-btn-meta">
                                        <span class="smart-btn-val" id="topVariantsCount">0</span>
                                        <span class="smart-btn-lbl">Variants</span>
                                    </div>
                                </button>
                            </div>
                            <div class="image-box-container">
                                <input type="file" id="imageInput" name="image" class="d-none" accept="image/*">
                                <div class="product-avatar-box" id="avatarBox" onclick="document.getElementById('imageInput').click()" title="Click to upload image">
                                    <button type="button" id="clearImageBtn" class="clear-img-btn d-none" title="Remove image">&times;</button>
                                    <img id="preview" class="d-none" alt="Product Preview">
                                    <div id="uploadPlaceholder" class="text-center p-1">
                                        <div class="cam-icon-wrap">
                                            <i class="fas fa-camera cam-icon"></i>
                                            <span class="cam-plus">+</span>
                                        </div>
                                        <div class="fw-bold text-muted mt-1" style="font-size: 10px;">Upload</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 2. TABS NAVIGATION BAR & CONTENT --}}
            <div class="odoo-tabs-container">
                <ul class="odoo-tabs-nav" id="odooTabsNav">
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link active" data-tab="tab-general">General Information</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-variants">Attributes & Variants</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-sales">Sales</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-prices">Prices</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-purchase">Purchase</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-inventory">Inventory</button>
                    </li>
                    <li class="odoo-tab-item">
                        <button type="button" class="odoo-tab-link" data-tab="tab-accounting">Accounting</button>
                    </li>
                </ul>

                <div class="odoo-tabs-content-wrap">

                    {{-- TAB 1: GENERAL INFORMATION (Sleek Odoo Layout) --}}
                    <div class="odoo-tab-pane active" id="tab-general">
                        <div class="row g-3">
                            
                            {{-- Left Column: Classification & Invoicing --}}
                            <div class="col-lg-5 col-md-12">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title">
                                            <i class="fas fa-sliders-h text-primary"></i> Product Type & Logistics
                                        </h6>
                                    </div>
                                    <div class="erp-section-body">
                                        
                                        {{-- Product Type ? --}}
                                        <div class="form-row-item">
                                            <label class="field-label">
                                                Product Type
                                                <span class="tooltip-icon" title="Goods: Storable inventory product. Service: Non-physical service. Combo: Bundle of items."><i class="fas fa-question-circle"></i></span>
                                            </label>
                                            <div class="field-value">
                                                <div class="radio-group-odoo">
                                                    <label class="odoo-radio">
                                                        <input type="radio" name="product_type" value="goods" checked>
                                                        <span>Goods</span>
                                                    </label>
                                                    <label class="odoo-radio">
                                                        <input type="radio" name="product_type" value="service">
                                                        <span>Service</span>
                                                    </label>
                                                    <label class="odoo-radio">
                                                        <input type="radio" name="product_type" value="combo">
                                                        <span>Combo</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Invoicing Policy ? --}}
                                        <div class="form-row-item">
                                            <label class="field-label">
                                                Invoicing Policy
                                                <span class="tooltip-icon" title="Ordered quantities: invoice upon purchase/sale order confirmation. Delivered quantities: invoice upon shipment."><i class="fas fa-question-circle"></i></span>
                                            </label>
                                            <div class="field-value">
                                                <select class="odoo-select" name="invoicing_policy" id="invoicing_policy">
                                                    <option value="ordered" selected>Ordered quantities</option>
                                                    <option value="delivered">Delivered quantities</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Track Inventory ? --}}
                                        <div class="form-row-item">
                                            <label class="field-label">
                                                Track Inventory
                                                <span class="tooltip-icon" title="Enable real-time stock tracking and inventory valuation"><i class="fas fa-question-circle"></i></span>
                                            </label>
                                            <div class="field-value d-flex align-items-center gap-2">
                                                <label class="odoo-switch">
                                                    <input type="checkbox" name="track_inventory" id="track_inventory" value="1" checked>
                                                    <span class="slider-round"></span>
                                                </label>
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-0" style="font-size:10px;" id="trackInventoryBadge">Active</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Pricing & Identification --}}
                            <div class="col-lg-7 col-md-12">
                                
                                {{-- Card 1: Pricing & Taxes --}}
                                <div class="erp-section-card mb-2">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title">
                                            <i class="fas fa-calculator text-primary"></i> Pricing & Taxation
                                        </h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                {{-- Sales Price ? --}}
                                                <div class="form-row-item">
                                                    <label class="field-label">
                                                        Sales Price
                                                        <span class="tooltip-icon" title="Standard customer selling price per unit"><i class="fas fa-question-circle"></i></span>
                                                    </label>
                                                    <div class="field-value">
                                                        <div class="currency-input-wrap">
                                                            <span class="currency-prefix">Rs.</span>
                                                            <input type="number" 
                                                                   step="any" 
                                                                   class="odoo-input text-end fw-bold text-primary" 
                                                                   style="max-width: 95px;" 
                                                                   name="general_sale_price" 
                                                                   id="general_sale_price" 
                                                                   placeholder="1.00" 
                                                                   value="1.00">
                                                            <span class="unit-suffix" id="unitSuffixSale">per Units</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {{-- Sales Taxes ? --}}
                                                <div class="form-row-item">
                                                    <label class="field-label">
                                                        Sales Taxes
                                                        <span class="tooltip-icon" title="Default tax applied on customer sales invoices"><i class="fas fa-question-circle"></i></span>
                                                    </label>
                                                    <div class="field-value">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="tax-pill-badge" id="salesTaxBadge">
                                                                <span id="salesTaxText">0%</span>
                                                                <span class="close-tax" onclick="removeTax('sales')">&times;</span>
                                                            </span>
                                                            <input type="hidden" name="sales_tax_percent" id="sales_tax_percent" value="0">
                                                            <select class="form-select form-select-sm d-none" id="salesTaxSelect" style="max-width:90px;height:28px;font-size:12px;" onchange="applyTax('sales', this.value)">
                                                                <option value="0">0%</option>
                                                                <option value="5">5%</option>
                                                                <option value="10">10%</option>
                                                                <option value="18">18%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {{-- Cost ? --}}
                                                <div class="form-row-item">
                                                    <label class="field-label">
                                                        Cost
                                                        <span class="tooltip-icon" title="Product purchase / manufacturing cost per unit"><i class="fas fa-question-circle"></i></span>
                                                    </label>
                                                    <div class="field-value">
                                                        <div class="currency-input-wrap">
                                                            <span class="currency-prefix">Rs.</span>
                                                            <input type="number" 
                                                                   step="any" 
                                                                   class="odoo-input text-end fw-bold" 
                                                                   style="max-width: 95px;" 
                                                                   name="general_cost_price" 
                                                                   id="general_cost_price" 
                                                                   placeholder="0.00" 
                                                                   value="0.00">
                                                            <span class="unit-suffix" id="unitSuffixCost">per Units</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {{-- Purchase Taxes ? --}}
                                                <div class="form-row-item">
                                                    <label class="field-label">
                                                        Purchase Taxes
                                                        <span class="tooltip-icon" title="Default tax applied on vendor purchase bills"><i class="fas fa-question-circle"></i></span>
                                                    </label>
                                                    <div class="field-value">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="tax-pill-badge" id="purchaseTaxBadge">
                                                                <span id="purchaseTaxText">0%</span>
                                                                <span class="close-tax" onclick="removeTax('purchase')">&times;</span>
                                                            </span>
                                                            <input type="hidden" name="purchase_tax_percent" id="purchase_tax_percent" value="0">
                                                            <select class="form-select form-select-sm d-none" id="purchaseTaxSelect" style="max-width:90px;height:28px;font-size:12px;" onchange="applyTax('purchase', this.value)">
                                                                <option value="0">0%</option>
                                                                <option value="5">5%</option>
                                                                <option value="10">10%</option>
                                                                <option value="18">18%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 2: Categorization & Reference --}}
                                <div class="erp-section-card">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title">
                                            <i class="fas fa-sitemap text-primary"></i> Categorization & Identity
                                        </h6>
                                    </div>
                                    <div class="erp-section-body">
                                        
                                        {{-- Category --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Category <span class="text-danger ms-1">*</span></label>
                                            <div class="field-value">
                                                <div class="d-flex gap-1">
                                                    <select class="odoo-select" id="category-dropdown" name="category_id" required>
                                                        <option value="">Select category...</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn-quick-add" data-bs-toggle="modal" data-bs-target="#categoryModal" data-toggle="modal" data-target="#categoryModal" title="Add Category">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sub Category --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Sub Category</label>
                                            <div class="field-value">
                                                <div class="d-flex gap-1">
                                                    <select class="odoo-select" id="subcategory-dropdown" name="sub_category_id">
                                                        <option value="">Select subcategory...</option>
                                                    </select>
                                                    <button type="button" class="btn-quick-add" data-bs-toggle="modal" data-bs-target="#subcategoryModal" data-toggle="modal" data-target="#subcategoryModal" title="Add Subcategory">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Brand --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Brand <span class="text-danger ms-1">*</span></label>
                                            <div class="field-value">
                                                <div class="d-flex gap-1">
                                                    <select class="odoo-select" name="brand_id" id="brand_id" required>
                                                        <option value="">Select brand...</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn-quick-add" data-bs-toggle="modal" data-bs-target="#brandModal" data-toggle="modal" data-target="#brandModal" title="Add Brand">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Reference --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Reference</label>
                                            <div class="field-value">
                                                <input type="text" class="odoo-input" name="reference" id="reference" placeholder="e.g. ITEM-0001 or SKU">
                                            </div>
                                        </div>

                                        {{-- Barcode --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Barcode</label>
                                            <div class="field-value">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="odoo-input" style="border-top-right-radius:0; border-bottom-right-radius:0;" name="barcode" id="barcode" placeholder="Scan or type barcode...">
                                                    <button type="button" class="btn btn-light border px-2" id="genBarcodeBtn" title="Generate Barcode" style="border-color: var(--border-strong); height:30px; font-size:12px;">
                                                        <i class="fas fa-barcode text-primary"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tags --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Tags</label>
                                            <div class="field-value">
                                                <input type="text" class="odoo-input" name="tags" id="tags" placeholder="e.g. Popular, Featured, Sale">
                                            </div>
                                        </div>

                                        {{-- Company --}}
                                        <div class="form-row-item">
                                            <label class="field-label">Company</label>
                                            <div class="field-value">
                                                <select class="odoo-select" name="company" id="company">
                                                    <option value="all" selected>Visible to all</option>
                                                    <option value="kent_hardware">Kent Hardware</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            {{-- Bottom Row: Internal Notes Card --}}
                            <div class="col-12">
                                <div class="erp-section-card">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title">
                                            <i class="fas fa-sticky-note text-primary"></i> Internal Notes
                                        </h6>
                                        <small class="text-muted" style="font-size:11px;">Private staff notes</small>
                                    </div>
                                    <div class="erp-section-body">
                                        <textarea class="notes-textarea" name="internal_notes" id="internal_notes" rows="2" placeholder="This note is only for internal purposes."></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- TAB 2: ATTRIBUTES & VARIANTS (EXACT FORM AS IN USER IMAGE) --}}
                    <div class="odoo-tab-pane" id="tab-variants">
                        
                        {{-- 1. ATTRIBUTES & VALUES CONFIGURATION TABLE (IMAGE LAYOUT) --}}
                        <div class="erp-section-card mb-3" style="overflow: visible !important;">
                            <div class="erp-section-header">
                                <h6 class="erp-section-title">
                                    <i class="fas fa-sliders-h text-primary"></i> Attributes & Values Configuration
                                </h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" id="matrixVariantCountBadge">0 Variants</span>
                            </div>
                            <div class="erp-section-body p-0" style="overflow: visible !important;">
                                <div style="overflow: visible !important; width: 100%;">
                                    <table class="attr-table" id="attributesConfigTable" style="overflow: visible !important;">
                                        <thead>
                                            <tr>
                                                <th style="width: 32px;" class="text-center"></th>
                                                <th style="width: 280px;">Attribute</th>
                                                <th>Values</th>
                                                <th style="width: 130px;" class="text-end pe-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attributesConfigBody">
                                            {{-- Attribute rows injected dynamically via JS --}}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-2 px-3 border-top bg-light d-flex align-items-center justify-content-between">
                                    <button type="button" class="add-line-btn" id="addAttributeLineBtn">
                                        <i class="fas fa-plus-circle"></i> Add a line
                                    </button>
                                    <small class="text-muted" style="font-size:11.5px;">Type value and press <b>Enter</b> or <b>comma</b> to add tags</small>
                                </div>
                            </div>
                        </div>

                        {{-- 2. GENERATED VARIANTS MATRIX (COLLAPSIBLE / EXPANDABLE FOR FINE-TUNING) --}}
                        <div class="erp-section-card" id="generatedVariantsCard">
                            <div class="erp-section-header">
                                <h6 class="erp-section-title">
                                    <i class="fas fa-layer-group text-primary"></i> Generated Variants Matrix & Pricing
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" id="toggleMatrixBtn" style="font-size: 11px;">
                                        <i class="fas fa-eye me-1"></i> <span id="toggleMatrixText">Hide Matrix</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary py-1 px-2" id="enableVariantsBtn" style="font-size: 11px;">
                                        <i class="fas fa-plus me-1"></i> Add Custom Row
                                    </button>
                                </div>
                            </div>
                            <div class="erp-section-body" id="matrixBodyContainer">
                                <div id="variantsContainer">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle mb-1" id="variantsTable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 32px;" class="text-center"><input type="checkbox" class="form-check-input" id="selectAllVariantsCheck" title="Select All"></th>
                                                    <th style="width: 130px;">Internal Ref</th>
                                                    <th style="min-width: 120px;">Product Name</th>
                                                    <th style="min-width: 180px;">Attributes</th>
                                                    <th style="width: 80px;">Unit</th>
                                                    <th style="width: 90px;" class="text-center">On Hand (Stock)</th>
                                                    <th style="width: 95px;" class="text-center conv-col" id="convFactorHeader">Pcs / Carton</th>
                                                    <th style="width: 90px;" class="text-center piece-wt-only-col">Piece Wt (g)</th>
                                                    <th style="width: 95px;">Sales Price</th>
                                                    <th style="width: 95px;">Cost (Purch)</th>
                                                    <th style="width: 85px;">Wholesale</th>
                                                    <th style="width: 110px;">Barcode</th>
                                                    <th style="width: 45px;" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantsBody">
                                                {{-- JS Injected Rows --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Mobile Accordion Cards View --}}
                                <div id="mobileVariantsContainer" style="display: none;">
                                    <div id="mobileVariantsBody" class="d-flex flex-column gap-2 mb-2"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 py-1" onclick="mobileAddVariant()">
                                        <i class="fas fa-plus me-1"></i> Add Variant
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- TAB 3: SALES --}}
                    <div class="odoo-tab-pane" id="tab-sales">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-shopping-cart text-primary"></i> E-Commerce & Online Store</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_web_visible" id="is_web_visible" value="1" checked>
                                            <label class="form-check-label fw-semibold" for="is_web_visible">Visible in Online Store</label>
                                        </div>

                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="show_on_homepage" id="show_on_homepage" value="1">
                                            <label class="form-check-label fw-semibold" for="show_on_homepage">Show on Homepage</label>
                                        </div>

                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="auto_hide_out_of_stock" id="auto_hide_out_of_stock" value="1">
                                            <label class="form-check-label fw-semibold" for="auto_hide_out_of_stock">Auto Hide When Out of Stock</label>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Website Sale Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" step="any" class="form-control form-control-sm" name="web_sale_price" id="web_sale_price" placeholder="Leave empty to use standard price">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Website Gallery Images</label>
                                            <input type="file" class="form-control form-control-sm" name="web_images[]" multiple accept="image/*">
                                            <small class="text-muted" style="font-size:11px;">You can select multiple photos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-search text-primary"></i> SEO & Quotation Notes</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Meta Title</label>
                                            <input type="text" class="form-control form-control-sm" name="meta_title" placeholder="SEO title">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Meta Description</label>
                                            <textarea class="form-control form-control-sm" name="meta_description" rows="2" placeholder="Brief SEO description..."></textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Sales Description (Quote / Invoice)</label>
                                            <textarea class="form-control form-control-sm" name="sales_description" rows="2" placeholder="Note shown on invoices."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 4: PRICES --}}
                    <div class="odoo-tab-pane" id="tab-prices">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-tags text-primary"></i> Price Tiers & Discounts</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Wholesale Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" step="any" class="form-control form-control-sm" name="wholesale_price" id="wholesale_price" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Sale Discount (%)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm" name="sale_discount_percent" id="sale_discount_percent" placeholder="0.00">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Purchase Discount (%)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm" name="purchase_discount_percent" id="purchase_discount_percent" placeholder="0.00">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-ruler-combined text-primary"></i> Tile / Area Pricing (M² Mode)</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <p class="text-muted mb-2" style="font-size:11.5px;">Configure length and width for m² items:</p>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold text-muted" style="font-size:12px;">Height (cm)</label>
                                                <input type="number" step="any" class="form-control form-control-sm" name="height_display" id="height_display" placeholder="e.g. 60">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold text-muted" style="font-size:12px;">Width (cm)</label>
                                                <input type="number" step="any" class="form-control form-control-sm" name="width_display" id="width_display" placeholder="e.g. 60">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Price per M²</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" step="any" class="form-control form-control-sm" name="price_per_m2_display" id="price_per_m2_display" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 5: PURCHASE --}}
                    <div class="odoo-tab-pane" id="tab-purchase">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-truck text-primary"></i> Vendor & Supply Chain</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Preferred Vendor</label>
                                            <input type="text" class="form-control form-control-sm" name="vendor_name" placeholder="Supplier name">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Vendor Lead Time (Days)</label>
                                            <input type="number" class="form-control form-control-sm" name="lead_time_days" placeholder="e.g. 7" value="7">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-file-invoice text-primary"></i> Purchase Description</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Purchase Order Notes</label>
                                            <textarea class="form-control form-control-sm" name="purchase_description" rows="3" placeholder="Notes shown on vendor purchase orders."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 6: INVENTORY --}}
                    <div class="odoo-tab-pane" id="tab-inventory">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-warehouse text-primary"></i> Warehouse & Unit Configuration</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Primary Unit / Mode</label>
                                            <select class="odoo-select fw-bold" name="size_mode" id="unit-dropdown">
                                                <option value="by_pieces">Pcs (Pieces)</option>
                                                <option value="by_cartons">Carton</option>
                                                <option value="by_meter">Meter</option>
                                                <option value="by_feet">Ft (Feet)</option>
                                                <option value="by_kg">Kg</option>
                                                <option value="by_gm">Gm</option>
                                                <option value="by_ton">Ton</option>
                                                <option value="by_size">By Size (m² Tiles)</option>
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Target Warehouse</label>
                                            <select class="odoo-select" name="warehouse_id" id="warehouse_id">
                                                @if(isset($warehouses) && count($warehouses) > 0)
                                                    @foreach($warehouses as $wh)
                                                        <option value="{{ $wh->id }}">{{ $wh->warehouse_name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Main Warehouse</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-bell text-primary"></i> Reordering Rules & Stock Alerts</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Minimum Stock Alert (Pieces/Units)</label>
                                            <input type="number" class="form-control form-control-sm" name="alert_quantity" id="alert_quantity" placeholder="0" value="0">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Minimum Stock Alert (Cartons)</label>
                                            <input type="number" class="form-control form-control-sm" name="alert_carton_quantity" id="alert_carton_quantity" placeholder="0" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 7: ACCOUNTING --}}
                    <div class="odoo-tab-pane" id="tab-accounting">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-book text-primary"></i> General Ledger Accounts</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Income Account (Sales)</label>
                                            <input type="text" class="form-control form-control-sm" name="income_account" placeholder="Default: 4000 Sales Revenue" readonly style="background:#f8fafc;">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Expense Account (COGS)</label>
                                            <input type="text" class="form-control form-control-sm" name="expense_account" placeholder="Default: 5000 Cost of Goods Sold" readonly style="background:#f8fafc;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="erp-section-card h-100">
                                    <div class="erp-section-header">
                                        <h6 class="erp-section-title"><i class="fas fa-calculator text-primary"></i> Valuation Accounts</h6>
                                    </div>
                                    <div class="erp-section-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted" style="font-size:12px;">Price Difference Account</label>
                                            <input type="text" class="form-control form-control-sm" name="price_diff_account" placeholder="Default: 5100 Price Variance" readonly style="background:#f8fafc;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end .odoo-tabs-content-wrap --}}

            </div>{{-- end .odoo-tabs-container --}}

            {{-- DESKTOP ACTION BAR (Bottom) --}}
            <div class="d-flex justify-content-end align-items-center bg-white p-2 rounded shadow-sm border mb-3 gap-2 desktop-action-bar" style="border: 1px solid var(--border-strong) !important;">
                <a href="{{ route('product') }}" class="btn-cancel-odoo">Cancel</a>
                <button type="submit" id="desktopSubmitBtn" class="btn-primary-odoo">
                    <i class="fas fa-check-circle"></i> SAVE PRODUCT
                </button>
            </div>

            {{-- HIDDEN FORM CONTROLS FOR STRICT BACKEND COMPATIBILITY --}}
            <div style="display:none !important;">
                <input type="number" name="height" id="height" step="0.01" value="0">
                <input type="number" name="width" id="width" step="0.01" value="0">
                <input type="number" name="price_per_m2" id="price_per_m2" step="0.01" value="0">
                <input type="number" name="purchase_price_per_m2" id="purchase_price_per_m2" step="0.01" value="0">

                <input type="number" name="piece_quantity" id="piece_quantity" value="0">
                <input type="number" name="pieces_per_box" id="pieces_per_box" value="1">
                <input type="number" name="boxes_quantity" id="boxes_quantity" value="0">
                <input type="number" name="loose_pieces" id="loose_pieces" value="0">
                <input type="number" name="sale_price_per_box" id="sale_price_per_box" step="0.01" value="1">
                <input type="number" name="weight_per_piece" id="weight_per_piece" step="0.0001" value="0">
                <input type="number" name="purchase_price_per_piece" id="purchase_price_per_piece" step="0.01" value="0">
            </div>

        </form>

        {{-- Category Quick Add Modal --}}
        <div id="categoryModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.category') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold" style="font-size:13px;">New Category</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size:12px;">Category Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Ceramics">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">Create Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Subcategory Quick Add Modal --}}
        <div id="subcategoryModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.subcategory') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold" style="font-size:13px;">New Subcategory</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size:12px;">Parent Category</label>
                                <select name="category_id" class="form-select form-select-sm">
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size:12px;">Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Floor Tiles">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">Create Subcategory</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Brand Quick Add Modal --}}
        <div id="brandModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                    <form action="{{ route('store.Brand') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold" style="font-size:13px;">New Brand</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="page" value="product_page">
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size:12px;">Brand Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Master">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">Create Brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Search: Attribute Modal (Matching Image 2 Exactly) --}}
        <div id="searchAttributeModal" class="modal fade" tabindex="-1" aria-hidden="true" style="z-index: 9999999 !important;">
            <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 820px; z-index: 10000000 !important;">
                <div class="modal-content border-0 shadow-lg" style="background: #212530 !important; border-radius: 6px; overflow: hidden; border: 1px solid #333d4e !important;">
                    {{-- Header --}}
                    <div class="modal-header py-2 px-3 border-0 d-flex align-items-center justify-content-between" style="background: #212530 !important; border-bottom: 1px solid #2e3544 !important;">
                        <h6 class="modal-title fw-bold mb-0" style="color: #ffffff !important; font-size: 15px; letter-spacing: 0.01em;">Search: Attribute</h6>
                        <button type="button" class="btn-close-attr-modal" onclick="hideSearchAttrModal()" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 17px; cursor: pointer; line-height: 1; padding: 2px 6px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Search bar & Pagination counter (Centered search matching Image 2) --}}
                    <div class="attr-modal-subbar d-flex align-items-center justify-content-between" style="background: #252a37 !important; border-bottom: 1px solid #2e3544 !important; padding: 8px 16px;">
                        <div style="width: 140px;" class="d-none d-md-block"></div>
                        <div class="attr-modal-search-box">
                            <i class="fas fa-search position-absolute" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 11.5px; color: #94a3b8;"></i>
                            <input type="text" id="modalAttrSearchInput" class="form-control form-control-sm" placeholder="Search..." autocomplete="off">
                            <i class="fas fa-caret-down position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8;"></i>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size: 12.5px; min-width: 140px; justify-content: flex-end;">
                            <span id="attrPaginationInfo" style="color: #cbd5e1; font-weight: 500; font-size: 12.5px;">1-29 / 29</span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-sm py-0 px-2" id="attrPrevPageBtn" style="background: #171b23; border: 1px solid #333d4e; color: #94a3b8; height: 26px;"><i class="fas fa-chevron-left" style="font-size: 10px;"></i></button>
                                <button type="button" class="btn btn-sm py-0 px-2" id="attrNextPageBtn" style="background: #171b23; border: 1px solid #333d4e; color: #94a3b8; height: 26px;"><i class="fas fa-chevron-right" style="font-size: 10px;"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- Table Body (Guaranteed 100% dark background) --}}
                    <div class="modal-body p-0" style="background: #212530 !important; max-height: 440px; overflow-y: auto;">
                        <table class="attr-modal-table mb-0" style="width: 100%; border-collapse: separate; border-spacing: 0; background: #212530 !important;">
                            <thead>
                                <tr style="background: #212530 !important; position: sticky; top: 0; z-index: 10;">
                                    <th style="width: 42px; padding: 9px 14px; border-bottom: 1.5px solid #2e3544 !important; background: #212530 !important; color: #94a3b8; font-size: 12px; font-weight: 600;"></th>
                                    <th style="min-width: 200px; padding: 9px 14px; border-bottom: 1.5px solid #2e3544 !important; background: #212530 !important; color: #cbd5e1; font-size: 13px; font-weight: 600;">Attribute</th>
                                    <th style="width: 200px; padding: 9px 14px; border-bottom: 1.5px solid #2e3544 !important; background: #212530 !important; color: #cbd5e1; font-size: 13px; font-weight: 600;">Display Type</th>
                                    <th style="width: 200px; padding: 9px 14px; border-bottom: 1.5px solid #2e3544 !important; background: #212530 !important; color: #cbd5e1; font-size: 13px; font-weight: 600;">Variant Creation</th>
                                </tr>
                            </thead>
                            <tbody id="modalAttrTableBody" style="background: #212530 !important;">
                                {{-- Injected dynamically --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer with Create New & Close --}}
                    <div class="modal-footer py-2 px-3 border-0 d-flex align-items-center justify-content-between" style="background: #212530 !important; border-top: 1px solid #2e3544 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn" id="modalCreateNewAttrBtn" style="background: #714b67 !important; color: #ffffff !important; font-size: 12.5px; font-weight: 600; padding: 6px 16px; border-radius: 4px; border: none; transition: opacity 0.15s;">Create New</button>
                            <button type="button" class="btn" id="modalCloseAttrBtn" onclick="hideSearchAttrModal()" data-bs-dismiss="modal" data-dismiss="modal" style="background: #353a47 !important; color: #e2e8f0 !important; font-size: 12.5px; font-weight: 600; padding: 6px 16px; border-radius: 4px; border: none; transition: opacity 0.15s;">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Create Attribute Modal (Matching Image 3 Exactly) --}}
        <div id="createAttributeModal" class="modal fade" tabindex="-1" aria-hidden="true" style="z-index: 10000000 !important;">
            <div class="modal-dialog modal-dialog-centered modal-lg" id="createAttrModalDialog" style="max-width: 860px; z-index: 10000001 !important; transition: max-width 0.2s ease;">
                <div class="modal-content border-0 shadow-lg" style="background: #212530 !important; border-radius: 6px; overflow: hidden; border: 1px solid #333d4e !important;">
                    {{-- Header --}}
                    <div class="modal-header py-2 px-3 border-0 d-flex align-items-center justify-content-between" style="background: #212530 !important; border-bottom: 1px solid #2e3544 !important;">
                        <h6 class="modal-title fw-bold mb-0" style="color: #ffffff !important; font-size: 15px; letter-spacing: 0.01em;">Create Attribute</h6>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-toggle-create-fullscreen" id="createAttrFullscreenBtn" title="Toggle Fullscreen" style="background: transparent; border: none; color: #94a3b8; font-size: 13px; cursor: pointer; padding: 2px 6px;">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </button>
                            <button type="button" class="btn-close-create-modal" onclick="hideCreateAttributeModal()" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 17px; cursor: pointer; line-height: 1; padding: 2px 6px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body p-4" style="background: #212530 !important;">
                        {{-- Attribute Name (Large input with cyan bottom border) --}}
                        <div class="mb-4">
                            <input type="text" id="createAttrNameInput" class="create-attr-title-input" placeholder="Attribute Name" autocomplete="off">
                        </div>

                        {{-- Display Type --}}
                        <div class="row align-items-center mb-3">
                            <div class="col-sm-3" style="color: #cbd5e1; font-size: 13.5px; font-weight: 500;">
                                Display Type <span class="attr-help-q" title="The display type used in the eCommerce product page">?</span>
                            </div>
                            <div class="col-sm-4">
                                <select id="createAttrDisplayType" onchange="toggleCreateAttrColumns()" class="form-select form-select-sm create-attr-select">
                                    <option value="Radio" selected>Radio</option>
                                    <option value="Pills">Pills</option>
                                    <option value="Select">Select</option>
                                    <option value="Color">Color</option>
                                    <option value="Image">Image</option>
                                    <option value="Multi-checkbox">Multi-checkbox</option>
                                </select>
                            </div>
                        </div>

                        {{-- Variant Creation --}}
                        <div class="row align-items-center mb-4">
                            <div class="col-sm-3" style="color: #cbd5e1; font-size: 13.5px; font-weight: 500;">
                                Variant Creation <span class="attr-help-q" title="Instantly creates variants, Dynamically only creates upon selection in sales, Never doesn't generate stockable variants">?</span>
                            </div>
                            <div class="col-sm-9 d-flex align-items-center gap-4">
                                <label class="d-flex align-items-center gap-2 mb-0" style="cursor: pointer; color: #ffffff; font-size: 13px;">
                                    <input type="radio" name="createAttrVariantCreation" value="Instantly" checked class="create-attr-radio">
                                    <span>Instantly</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 mb-0" style="cursor: pointer; color: #cbd5e1; font-size: 13px;">
                                    <input type="radio" name="createAttrVariantCreation" value="Dynamically" class="create-attr-radio">
                                    <span>Dynamically</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 mb-0" style="cursor: pointer; color: #cbd5e1; font-size: 13px;">
                                    <input type="radio" name="createAttrVariantCreation" value="Never" class="create-attr-radio">
                                    <span>Never</span>
                                </label>
                            </div>
                        </div>

                        {{-- Tabs --}}
                        <div class="create-attr-tabs d-flex align-items-center" style="border-bottom: 1px solid #2e3544; margin-top: 10px;">
                            <button type="button" class="btn create-attr-tab" style="background: transparent; border: none; border-top: 2px solid #b854a6; color: #d946ef; font-weight: 600; font-size: 13px; padding: 7px 16px; border-radius: 0;">
                                Attribute Values
                            </button>
                        </div>

                        {{-- Values Table --}}
                        <div class="create-attr-table-wrap" style="background: #212530; min-height: 120px;">
                            <table class="create-attr-table w-100" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #2e3544;">
                                        <th style="width: 34px; padding: 10px 8px; text-align: center;"></th>
                                        <th style="padding: 10px 14px; color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: left;" class="col-val-header">Value</th>
                                        <th style="padding: 10px 14px; color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: left;" class="create-attr-freetext-col">Free text</th>
                                        <th style="padding: 10px 14px; color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: left; display: none;" class="create-attr-color-col">Color</th>
                                        <th style="padding: 10px 14px; color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: left; display: none;" class="create-attr-image-col">Image</th>
                                        <th style="padding: 10px 14px; color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: right;" class="col-price-header">Default Extra Price</th>
                                        <th style="width: 36px; text-align: center;"></th>
                                    </tr>
                                </thead>
                                <tbody id="createAttrValuesTableBody">
                                    {{-- Dynamically added lines --}}
                                </tbody>
                            </table>
                            <div class="py-2 px-3">
                                <a href="javascript:void(0)" id="createAttrAddLineBtn" style="color: #00a09d; font-size: 13px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                                    Add a line
                                </a>
                            </div>
                            {{-- Dark empty space below table matching Image 3 --}}
                            <div style="height: 44px; background: #242936; border-top: 1px solid #2e3544; border-bottom: 1px solid #2e3544;"></div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer py-2 px-3 border-0 d-flex align-items-center justify-content-start gap-2" style="background: #212530 !important; border-top: 1px solid #2e3544 !important;">
                        <button type="button" class="btn" id="saveCreateAttrBtn" style="background: #714b67 !important; color: #ffffff !important; font-size: 12.5px; font-weight: 600; padding: 6px 18px; border-radius: 4px; border: none;">Save</button>
                        <button type="button" class="btn" id="discardCreateAttrBtn" onclick="hideCreateAttributeModal()" data-bs-dismiss="modal" data-dismiss="modal" style="background: #353a47 !important; color: #e2e8f0 !important; font-size: 12.5px; font-weight: 600; padding: 6px 18px; border-radius: 4px; border: none;">Discard</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Configure Attribute Values Modal (Odoo "Exclude For" & Values Config matching Video 05:28 - 06:35) --}}
        <div id="configureAttributeModal" class="modal fade" tabindex="-1" aria-hidden="true" style="z-index: 10000000 !important;">
            <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 960px; z-index: 10000001 !important;">
                <div class="modal-content border-0 shadow-lg" style="background: #212530 !important; border-radius: 6px; overflow: hidden; border: 1px solid #333d4e !important; color: #e2e8f0;">
                    {{-- Header with Breadcrumbs & Close --}}
                    <div class="modal-header py-2 px-3 border-0 d-flex align-items-center justify-content-between" style="background: #212530 !important; border-bottom: 1px solid #2e3544 !important;">
                        <div class="d-flex align-items-center gap-2" style="font-size: 13.5px;">
                            <span style="color: #94a3b8;">Products</span>
                            <span style="color: #64748b;">/</span>
                            <span id="configModalProductBreadcrumb" style="color: #94a3b8; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Product</span>
                            <span style="color: #64748b;">/</span>
                            <span id="configModalAttrTitle" class="fw-bold" style="color: #ffffff; font-size: 14.5px;">Size Screw</span>
                        </div>
                        <button type="button" class="btn-close-config-modal" onclick="hideConfigureAttributeModal()" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 17px; cursor: pointer; line-height: 1; padding: 2px 6px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Action buttons bar in modal (Save / Discard) --}}
                    <div class="d-flex align-items-center justify-content-between px-3 py-2" style="background: #252a37 !important; border-bottom: 1px solid #2e3544 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn" id="saveConfigAttrBtn" style="background: #714b67 !important; color: #ffffff !important; font-size: 12.5px; font-weight: 600; padding: 5px 16px; border-radius: 4px; border: none;">Save</button>
                            <button type="button" class="btn" id="discardConfigAttrBtn" onclick="hideConfigureAttributeModal()" data-bs-dismiss="modal" data-dismiss="modal" style="background: #353a47 !important; color: #e2e8f0 !important; font-size: 12.5px; font-weight: 600; padding: 5px 16px; border-radius: 4px; border: none;">Discard</button>
                        </div>
                        <div class="text-muted" style="font-size: 12px;">
                            <span id="configModalStatusTip">Values configuration & combination exclusions</span>
                        </div>
                    </div>

                    {{-- Table Body --}}
                    <div class="modal-body p-0" style="background: #212530 !important; max-height: 480px; overflow-y: auto;">
                        <table class="config-attr-table w-100" id="configAttrValuesTable" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: #212530; position: sticky; top: 0; z-index: 5; border-bottom: 1px solid #2e3544;">
                                    <th style="width: 38px; padding: 9px 10px; border-bottom: 1px solid #2e3544; color: #94a3b8; font-size: 12px; text-align: center;"></th>
                                    <th style="width: 140px; padding: 9px 14px; border-bottom: 1px solid #2e3544; color: #cbd5e1; font-size: 13px; font-weight: 600;">Value</th>
                                    <th style="padding: 9px 14px; border-bottom: 1px solid #2e3544; color: #cbd5e1; font-size: 13px; font-weight: 600;">
                                        Exclude For <span style="font-size: 11px; color: #38bdf8; font-weight: normal; margin-left: 4px;">(Never pair with these values)</span>
                                    </th>
                                    <th style="width: 90px; text-align: center; padding: 9px 10px; border-bottom: 1px solid #2e3544; color: #cbd5e1; font-size: 13px; font-weight: 600;">Active</th>
                                    <th style="width: 130px; text-align: right; padding: 9px 16px; border-bottom: 1px solid #2e3544; color: #cbd5e1; font-size: 13px; font-weight: 600;">Extra Price</th>
                                </tr>
                            </thead>
                            <tbody id="configAttrValuesBody">
                                {{-- Injected dynamically --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer py-2 px-3 border-0 d-flex align-items-center justify-content-between" style="background: #212530 !important; border-top: 1px solid #2e3544 !important;">
                        <small style="color: #94a3b8; font-size: 11.5px;">Excluded combinations will not generate variants in Kent Hardware.</small>
                        <button type="button" class="btn btn-sm" onclick="hideConfigureAttributeModal()" style="background: #353a47; color: #fff; font-size: 12px; padding: 4px 14px; border: none; border-radius: 4px;">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end .page-container --}}

    {{-- MOBILE STICKY ACTION BAR --}}
    <div class="mobile-action-bar">
        <a href="{{ route('product') }}" class="btn btn-outline-secondary fw-semibold" style="flex:1;border-radius:8px;height:40px;display:flex;align-items:center;justify-content:center;font-size:13px;">
            <i class="fas fa-times me-1"></i>Cancel
        </a>
        <button type="button" id="mobileSubmitBtn" onclick="document.getElementById('productForm').requestSubmit()" style="flex:2;background:linear-gradient(135deg,#4f46e5,#4338ca);border:none;border-radius:8px;height:40px;color:#fff;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;gap:6px;">
            <i class="fas fa-check-circle"></i> Save Product
        </button>
    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            const unitDropdown = document.getElementById('unit-dropdown');

            // 1. Tab Switching System
            const tabLinks = document.querySelectorAll('.odoo-tab-link');
            const tabPanes = document.querySelectorAll('.odoo-tab-pane');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    this.classList.add('active');
                    const targetPaneId = this.getAttribute('data-tab');
                    const targetPane = document.getElementById(targetPaneId);
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });

            // 2. Favorite Star Toggle
            const starBtn = document.getElementById('starToggle');
            const favInput = document.getElementById('is_favorite_input');
            if (starBtn && favInput) {
                starBtn.addEventListener('click', function() {
                    const isStarred = this.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (isStarred) {
                        icon.className = 'fas fa-star';
                        favInput.value = '1';
                    } else {
                        icon.className = 'far fa-star';
                        favInput.value = '0';
                    }
                });
            }

            // 3. Track inventory toggle badge
            const trackInv = document.getElementById('track_inventory');
            const trackBadge = document.getElementById('trackInventoryBadge');
            if (trackInv && trackBadge) {
                trackInv.addEventListener('change', function() {
                    if (this.checked) {
                        trackBadge.textContent = 'Active';
                        trackBadge.className = 'badge bg-success bg-opacity-10 text-success fw-bold px-2 py-0';
                    } else {
                        trackBadge.textContent = 'Inactive';
                        trackBadge.className = 'badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-0';
                    }
                });
            }

            // 4. Product Image Handler with live preview and clear
            const imgInput = document.getElementById('imageInput');
            const preview = document.getElementById('preview');
            const ph = document.getElementById('uploadPlaceholder');
            const clr = document.getElementById('clearImageBtn');

            if (imgInput) {
                imgInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const r = new FileReader();
                        r.onload = (e) => {
                            preview.src = e.target.result;
                            preview.classList.remove('d-none');
                            ph.classList.add('d-none');
                            clr.classList.remove('d-none');
                        };
                        r.readAsDataURL(this.files[0]);
                    }
                });
            }

            if (clr) {
                clr.addEventListener('click', (e) => {
                    e.stopPropagation();
                    imgInput.value = '';
                    preview.classList.add('d-none');
                    ph.classList.remove('d-none');
                    clr.classList.add('d-none');
                });
            }

            // 5. Barcode Generator Helper
            function generateRandomBarcode() {
                return Math.floor(100000000000 + Math.random() * 900000000000).toString();
            }

            const genBarcodeBtn = document.getElementById('genBarcodeBtn');
            const barcodeInput = document.getElementById('barcode');
            if (genBarcodeBtn && barcodeInput) {
                genBarcodeBtn.addEventListener('click', function() {
                    barcodeInput.value = generateRandomBarcode();
                });
            }

            // 6. Units suffix synchronization
            function updateUnitSuffixes() {
                const sel = unitDropdown ? unitDropdown.options[unitDropdown.selectedIndex].text : 'Units';
                const sSale = document.getElementById('unitSuffixSale');
                const sCost = document.getElementById('unitSuffixCost');
                if (sSale) sSale.textContent = 'per ' + sel.split(' ')[0];
                if (sCost) sCost.textContent = 'per ' + sel.split(' ')[0];
            }

            if (unitDropdown) {
                unitDropdown.addEventListener('change', updateUnitSuffixes);
                updateUnitSuffixes();
            }

            // 7. Tax Helpers (Sales & Purchase)
            window.removeTax = function(type) {
                if (type === 'sales') {
                    document.getElementById('salesTaxBadge').classList.add('d-none');
                    document.getElementById('salesTaxSelect').classList.remove('d-none');
                } else {
                    document.getElementById('purchaseTaxBadge').classList.add('d-none');
                    document.getElementById('purchaseTaxSelect').classList.remove('d-none');
                }
            };

            window.applyTax = function(type, val) {
                if (type === 'sales') {
                    document.getElementById('sales_tax_percent').value = val;
                    document.getElementById('salesTaxText').textContent = val + '%';
                    document.getElementById('salesTaxBadge').classList.remove('d-none');
                    document.getElementById('salesTaxSelect').classList.add('d-none');
                } else {
                    document.getElementById('purchase_tax_percent').value = val;
                    document.getElementById('purchaseTaxText').textContent = val + '%';
                    document.getElementById('purchaseTaxBadge').classList.remove('d-none');
                    document.getElementById('purchaseTaxSelect').classList.add('d-none');
                }
            };

            // 8. Dependent Subcategories AJAX
            $('#category-dropdown').on('change', function() {
                var cid = $(this).val();
                if (cid) {
                    $.get('/get-subcategories/' + cid, function(d) {
                        $('#subcategory-dropdown').empty().append('<option value="">Select subcategory...</option>');
                        $.each(d, function(_, v) {
                            $('#subcategory-dropdown').append('<option value="' + v.id + '">' + v.name + '</option>');
                        });
                    });
                } else {
                    $('#subcategory-dropdown').empty().append('<option value="">Select subcategory...</option>');
                }
            });

            // 9. Quick Add Modals
            function handleQuickAdd(modalId, selectSelector) {
                $('#' + modalId + ' form').on('submit', function(e) {
                    e.preventDefault();
                    let modalForm = $(this);
                    let btn = modalForm.find('button[type="submit"]');
                    let originalText = btn.text();
                    btn.text('Saving...').prop('disabled', true);

                    $.ajax({
                        url: modalForm.attr('action'),
                        method: 'POST',
                        data: modalForm.serialize(),
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(res) {
                            if (res.success) {
                                $(selectSelector).append(new Option(res.name, res.id, true, true)).trigger('change');
                                $('#' + modalId).modal('hide');
                                modalForm[0].reset();
                                Swal.fire({
                                    icon: 'success', 
                                    title: 'Added successfully', 
                                    toast: true, 
                                    position: 'top-end', 
                                    showConfirmButton: false, 
                                    timer: 1500
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({icon: 'error', title: 'Error', text: 'Failed to create item!'});
                        },
                        complete: function() {
                            btn.text(originalText).prop('disabled', false);
                        }
                    });
                });
            }

            handleQuickAdd('categoryModal', '#category-dropdown, #subcategoryModal select[name="category_id"]');
            handleQuickAdd('subcategoryModal', '#subcategory-dropdown');
            handleQuickAdd('brandModal', '#brand_id');

            // =========================================================
            // 10. ATTRIBUTES & VALUES CONFIGURATION ENGINE (MATCHING ODOO SCREENSHOTS)
            // =========================================================
            const attributesConfigBody = document.getElementById('attributesConfigBody');
            const addAttributeLineBtn = document.getElementById('addAttributeLineBtn');
            const variantsBody = document.getElementById('variantsBody');
            const productNameInput = document.getElementById('product_name');
            const genSaleInput = document.getElementById('general_sale_price');
            const genCostInput = document.getElementById('general_cost_price');
            const matrixBadge = document.getElementById('matrixVariantCountBadge');

            // 29 Master Attributes matching Image 2 screenshot exactly
            let masterAttributes = [
                { name: 'Brand', display_type: 'Radio', creation: 'Instantly' },
                { name: 'gender', display_type: 'Radio', creation: 'Instantly' },
                { name: 'manufacturer', display_type: 'Radio', creation: 'Instantly' },
                { name: 'age group', display_type: 'Radio', creation: 'Instantly' },
                { name: 'Wictor Blue', display_type: 'Multi-checkbox', creation: 'Never' },
                { name: 'Shoes size', display_type: 'Pills', creation: 'Never' },
                { name: 'Sale Description', display_type: 'Radio', creation: 'Dynamically' },
                { name: 'Attribute', display_type: 'Radio', creation: 'Dynamically' },
                { name: 'Color', display_type: 'Color', creation: 'Instantly' },
                { name: 'Size', display_type: 'Pills', creation: 'Instantly' },
                { name: 'Material', display_type: 'Select', creation: 'Instantly' },
                { name: 'Finish', display_type: 'Radio', creation: 'Dynamically' },
                { name: 'Weight', display_type: 'Radio', creation: 'Never' },
                { name: 'Dimensions', display_type: 'Radio', creation: 'Never' },
                { name: 'Style', display_type: 'Radio', creation: 'Dynamically' },
                { name: 'Pattern', display_type: 'Radio', creation: 'Instantly' },
                { name: 'Capacity', display_type: 'Radio', creation: 'Never' },
                { name: 'Grade', display_type: 'Radio', creation: 'Instantly' },
                { name: 'Warranty', display_type: 'Radio', creation: 'Never' },
                { name: 'Power', display_type: 'Radio', creation: 'Never' },
                { name: 'Voltage', display_type: 'Radio', creation: 'Never' },
                { name: 'Packaging', display_type: 'Radio', creation: 'Never' },
                { name: 'Series', display_type: 'Radio', creation: 'Dynamically' },
                { name: 'Thickness', display_type: 'Radio', creation: 'Instantly' },
                { name: 'Length', display_type: 'Radio', creation: 'Never' },
                { name: 'Model Year', display_type: 'Radio', creation: 'Never' },
                { name: 'Fabric', display_type: 'Radio', creation: 'Instantly' },
                { name: 'Fit Type', display_type: 'Pills', creation: 'Instantly' },
                { name: 'Origin Country', display_type: 'Radio', creation: 'Never' }
            ];

            let activeTargetRow = null; // Currently targeted attribute line for search / selection

            function renderModalAttributes(filterQuery = '') {
                const tbody = document.getElementById('modalAttrTableBody');
                const pagInfo = document.getElementById('attrPaginationInfo');
                if (!tbody) return;
                tbody.innerHTML = '';

                const q = (filterQuery || '').toLowerCase().trim();
                const filtered = masterAttributes.filter(a =>
                    !q || a.name.toLowerCase().includes(q) || a.display_type.toLowerCase().includes(q) || a.creation.toLowerCase().includes(q)
                );

                if (pagInfo) {
                    pagInfo.textContent = `1-${filtered.length} / ${masterAttributes.length}`;
                }

                if (filtered.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4" style="font-size: 13px; color: #94a3b8; background-color: #212530 !important;">
                                No attribute found matching "<b>${filterQuery}</b>". Click <b>Create New</b> to add it.
                            </td>
                        </tr>
                    `;
                    return;
                }

                filtered.forEach(attr => {
                    const tr = document.createElement('tr');
                    tr.style.cursor = 'pointer';
                    const currentVal = activeTargetRow?.querySelector('.attr-name-input')?.value?.trim() || '';
                    if (currentVal && currentVal.toLowerCase() === attr.name.toLowerCase()) {
                        tr.className = 'active-row';
                    }

                    tr.innerHTML = `
                        <td class="text-center" style="width: 42px; background: inherit !important; border-bottom: 1px solid #2a313e !important;">
                            <span class="attr-modal-grip" style="color: #64748b !important;"><i class="fas fa-grip-vertical"></i></span>
                        </td>
                        <td class="attr-col-name" style="background: inherit !important; color: #ffffff !important; font-weight: 500; font-size: 13px; border-bottom: 1px solid #2a313e !important;">${attr.name}</td>
                        <td class="attr-col-meta" style="background: inherit !important; color: #94a3b8 !important; font-size: 13px; border-bottom: 1px solid #2a313e !important;">${attr.display_type}</td>
                        <td class="attr-col-meta" style="background: inherit !important; color: #94a3b8 !important; font-size: 13px; border-bottom: 1px solid #2a313e !important;">${attr.creation}</td>
                    `;

                    tr.addEventListener('click', function() {
                        selectAttributeForActiveRow(attr.name);
                    });

                    tbody.appendChild(tr);
                });
            }

            window.showSearchAttrModal = function(targetRow) {
                if (targetRow) activeTargetRow = targetRow;
                const modalEl = document.getElementById('searchAttributeModal');
                if (!modalEl) {
                    console.error('searchAttributeModal not found!');
                    return;
                }

                // Move modal to body to prevent stacking context or transform clipping
                if (modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }

                const searchInp = document.getElementById('modalAttrSearchInput');
                const panel = document.getElementById('modalCreateAttrPanel');
                if (searchInp) searchInp.value = '';
                if (panel) panel.style.display = 'none';

                renderModalAttributes();

                let opened = false;

                // 1. Try jQuery modal (standard AdminLTE / Bootstrap 4 in Kent Hardware)
                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try {
                        jQuery(modalEl).modal('show');
                        opened = true;
                    } catch (err) {
                        console.warn('jQuery modal show failed:', err);
                    }
                }

                // 2. Try Bootstrap 5
                if (!opened && typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        let inst = (typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(modalEl)
                            : null;
                        if (!inst) inst = new bootstrap.Modal(modalEl);
                        inst.show();
                        opened = true;
                    } catch (err) {
                        console.warn('Bootstrap 5 modal show failed:', err);
                    }
                }

                // 3. Fallback: Pure CSS/JS Guaranteed Display (Never fails)
                if (!opened) {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.style.opacity = '1';
                    modalEl.removeAttribute('aria-hidden');
                    modalEl.setAttribute('aria-modal', 'true');
                    document.body.classList.add('modal-open');

                    let backdrop = document.querySelector('.attr-custom-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show attr-custom-backdrop';
                        backdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.65); z-index: 9999990 !important;';
                        document.body.appendChild(backdrop);
                        backdrop.addEventListener('click', window.hideSearchAttrModal);
                    }
                }

                setTimeout(() => {
                    const inp = document.getElementById('modalAttrSearchInput');
                    if (inp) inp.focus();
                }, 200);
            };

            window.hideSearchAttrModal = function() {
                const modalEl = document.getElementById('searchAttributeModal');
                if (!modalEl) return;

                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try { jQuery(modalEl).modal('hide'); } catch (e) {}
                }

                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        const inst = (typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(modalEl)
                            : null;
                        if (inst) inst.hide();
                    } catch (e) {}
                }

                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');

                document.querySelectorAll('.attr-custom-backdrop').forEach(b => b.remove());
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                    document.body.classList.remove('modal-open');
                }, 200);
            };

            if (modalAttrSearchInput) {
                modalAttrSearchInput.addEventListener('input', function() {
                    renderModalAttributes(this.value);
                });
            }

            function selectAttributeForActiveRow(attrName) {
                if (!activeTargetRow) {
                    activeTargetRow = attributesConfigBody.querySelector('.attr-row') || createAttributeRow('', []);
                }
                if (activeTargetRow) {
                    const attrInput = activeTargetRow.querySelector('.attr-name-input');
                    const tagInput = activeTargetRow.querySelector('.tag-input-field');
                    if (attrInput) {
                        attrInput.value = attrName;
                    }
                    hideSearchAttrModal();
                    if (tagInput) {
                        setTimeout(() => tagInput.focus(), 150);
                    }
                    syncAttributesToVariants();
                }
            }

            // -------------------------------------------------------------
            // Create Attribute Modal Handlers (Image 3 exact replica)
            // -------------------------------------------------------------
            const createAttrModalEl = document.getElementById('createAttributeModal');
            const createAttrNameInput = document.getElementById('createAttrNameInput');
            const createAttrDisplayType = document.getElementById('createAttrDisplayType');
            const createAttrAddLineBtn = document.getElementById('createAttrAddLineBtn');
            const createAttrValuesTableBody = document.getElementById('createAttrValuesTableBody');
            const saveCreateAttrBtn = document.getElementById('saveCreateAttrBtn');
            const discardCreateAttrBtn = document.getElementById('discardCreateAttrBtn');
            const createAttrFullscreenBtn = document.getElementById('createAttrFullscreenBtn');

            function toggleCreateAttrColumns() {
                const mode = createAttrDisplayType ? createAttrDisplayType.value : 'Radio';
                const isColor = (mode === 'Color');
                const isImage = (mode === 'Image');
                const isMultiCheckbox = (mode === 'Multi-checkbox');

                // If Multi-checkbox or Image is chosen, auto-select "Never" variant creation matching Images 5 & 6
                if (isMultiCheckbox || isImage) {
                    const neverRadio = document.querySelector('input[name="createAttrVariantCreation"][value="Never"]');
                    if (neverRadio) neverRadio.checked = true;
                }

                // Header & row columns
                const freetextCols = document.querySelectorAll('#createAttributeModal .create-attr-freetext-col');
                const colorCols = document.querySelectorAll('#createAttributeModal .create-attr-color-col');
                const imageCols = document.querySelectorAll('#createAttributeModal .create-attr-image-col');

                freetextCols.forEach(col => {
                    col.style.display = isMultiCheckbox ? 'none' : 'table-cell';
                });
                colorCols.forEach(col => {
                    col.style.display = isColor ? 'table-cell' : 'none';
                });
                imageCols.forEach(col => {
                    col.style.display = (isColor || isImage) ? 'table-cell' : 'none';
                });

                // If switching to Multi-checkbox or Image and table is empty, auto-add 1 line like Images 5 & 6
                if ((isMultiCheckbox || isImage) && createAttrValuesTableBody && createAttrValuesTableBody.children.length === 0) {
                    addCreateAttrValLine();
                }
            }
            window.toggleCreateAttrColumns = toggleCreateAttrColumns;

            if (createAttrDisplayType) {
                createAttrDisplayType.addEventListener('change', function() {
                    toggleCreateAttrColumns();
                });
            }

            function addCreateAttrValLine(valName = '', isFreeText = false, colorVal = '#3b82f6', extraPrice = '0.00') {
                if (!createAttrValuesTableBody) return;
                const mode = createAttrDisplayType ? createAttrDisplayType.value : 'Radio';
                const isColor = (mode === 'Color');
                const isImage = (mode === 'Image');
                const isMultiCheckbox = (mode === 'Multi-checkbox');
                const tr = document.createElement('tr');
                tr.className = 'create-val-row';
                tr.innerHTML = `
                    <td style="width: 34px; padding: 6px 8px; text-align: center; color: #64748b;">
                        <span class="attr-modal-grip" style="cursor: grab; display: inline-flex; align-items: center; justify-content: center;">
                            <svg width="10" height="15" viewBox="0 0 10 15" fill="#64748b" style="display:inline-block; vertical-align:middle;">
                                <circle cx="2" cy="2.5" r="1.4"/>
                                <circle cx="8" cy="2.5" r="1.4"/>
                                <circle cx="2" cy="7.5" r="1.4"/>
                                <circle cx="8" cy="7.5" r="1.4"/>
                                <circle cx="2" cy="12.5" r="1.4"/>
                                <circle cx="8" cy="12.5" r="1.4"/>
                            </svg>
                        </span>
                    </td>
                    <td class="create-attr-val-col" style="padding: 6px 14px;">
                        <input type="text" class="form-control form-control-sm val-name-input create-attr-val-field" placeholder="" value="${valName}" autocomplete="off">
                    </td>
                    <td class="create-attr-freetext-col" style="padding: 6px 14px; display: ${isMultiCheckbox ? 'none' : 'table-cell'};">
                        <input type="checkbox" class="val-freetext-check" ${isFreeText ? 'checked' : ''} style="accent-color: #00a09d; width: 16px; height: 16px; cursor: pointer; border-radius: 3px;">
                    </td>
                    <td class="create-attr-color-col" style="padding: 6px 14px; display: ${isColor ? 'table-cell' : 'none'};">
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="val-color-input" value="${colorVal}" style="width: 26px; height: 26px; padding: 0; border: none; border-radius: 50%; cursor: pointer; background: transparent;">
                            <span class="val-color-hex" style="font-size: 11.5px; color: #94a3b8; font-family: monospace;">${colorVal}</span>
                        </div>
                    </td>
                    <td class="create-attr-image-col" style="padding: 6px 14px; display: ${(isColor || isImage) ? 'table-cell' : 'none'};">
                        <label class="val-image-tile-btn mb-0" title="Upload Image" style="width: 34px; height: 30px; background: #ffffff; border-radius: 3px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; position: relative; border: 1px solid #cbd5e1; box-shadow: 0 1px 2px rgba(0,0,0,0.15);">
                            <svg width="22" height="18" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                                <path d="M4 6H7L8.5 4H15.5L17 6H20C21.1 6 22 6.9 22 8V16C22 17.1 21.1 18 20 18H4C2.9 18 2 17.1 2 16V8C2 6.9 2.9 6 4 6Z" fill="#b0b8c4"/>
                                <circle cx="12" cy="12" r="3.5" fill="#ffffff"/>
                                <circle cx="12" cy="12" r="2.2" fill="#b0b8c4"/>
                                <circle cx="18" cy="14" r="4.5" fill="#ffffff"/>
                                <circle cx="18" cy="14" r="3.8" fill="#788292"/>
                                <path d="M18 12V16M16 14H20" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                            <input type="file" class="val-image-input d-none" accept="image/*">
                        </label>
                    </td>
                    <td class="create-attr-price-col" style="padding: 6px 14px; text-align: right;">
                        <input type="number" step="0.01" class="form-control form-control-sm text-end val-price-input create-attr-price-field" value="${extraPrice}">
                    </td>
                    <td style="text-align: center; width: 36px; padding: 6px 8px;">
                        <button type="button" class="btn btn-sm p-0 delete-val-row-btn" style="background: transparent; border: none; font-size: 13px; cursor: pointer; color: #64748b;" title="Delete line"><i class="fas fa-trash-alt"></i></button>
                    </td>
                `;

                // Live color hex update
                const colorInp = tr.querySelector('.val-color-input');
                const colorHex = tr.querySelector('.val-color-hex');
                if (colorInp && colorHex) {
                    colorInp.addEventListener('input', function() {
                        colorHex.textContent = this.value;
                    });
                }

                // Image upload feedback with thumbnail preview
                const fileInp = tr.querySelector('.val-image-input');
                const tileBtn = tr.querySelector('.val-image-tile-btn');
                if (fileInp && tileBtn) {
                    fileInp.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                tileBtn.style.backgroundImage = `url('${e.target.result}')`;
                                tileBtn.style.backgroundSize = 'cover';
                                tileBtn.style.backgroundPosition = 'center';
                                tileBtn.innerHTML = `<input type="file" class="val-image-input d-none" accept="image/*">`;
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                }

                tr.querySelector('.delete-val-row-btn').addEventListener('click', function() {
                    tr.remove();
                });

                createAttrValuesTableBody.appendChild(tr);
                setTimeout(() => {
                    const inp = tr.querySelector('.val-name-input');
                    if (inp) inp.focus();
                }, 50);
            }
            window.addCreateAttrValLine = addCreateAttrValLine;

            if (createAttrAddLineBtn) {
                createAttrAddLineBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addCreateAttrValLine();
                });
            }

            window.showCreateAttributeModal = function(targetRow) {
                if (targetRow) activeTargetRow = targetRow;
                const modalEl = document.getElementById('createAttributeModal');
                if (!modalEl) return;

                if (modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }

                // Reset inputs
                if (createAttrNameInput) createAttrNameInput.value = '';
                if (createAttrDisplayType) createAttrDisplayType.value = 'Radio';
                toggleCreateAttrColumns();
                const instRadio = document.querySelector('input[name="createAttrVariantCreation"][value="Instantly"]');
                if (instRadio) instRadio.checked = true;
                if (createAttrValuesTableBody) createAttrValuesTableBody.innerHTML = '';

                let opened = false;
                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try {
                        jQuery(modalEl).modal('show');
                        opened = true;
                    } catch (err) {}
                }
                if (!opened && typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        let inst = (typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (!inst) inst = new bootstrap.Modal(modalEl);
                        inst.show();
                        opened = true;
                    } catch (err) {}
                }
                if (!opened) {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.style.opacity = '1';
                    modalEl.removeAttribute('aria-hidden');
                    modalEl.setAttribute('aria-modal', 'true');
                    document.body.classList.add('modal-open');

                    let backdrop = document.querySelector('.attr-create-custom-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show attr-create-custom-backdrop';
                        backdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.7); z-index: 9999995 !important;';
                        document.body.appendChild(backdrop);
                        backdrop.addEventListener('click', window.hideCreateAttributeModal);
                    }
                }

                setTimeout(() => {
                    if (createAttrNameInput) createAttrNameInput.focus();
                }, 150);
            };

            window.hideCreateAttributeModal = function() {
                const modalEl = document.getElementById('createAttributeModal');
                if (!modalEl) return;

                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try { jQuery(modalEl).modal('hide'); } catch (e) {}
                }
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        const inst = (typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (inst) inst.hide();
                    } catch (e) {}
                }

                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');

                document.querySelectorAll('.attr-create-custom-backdrop').forEach(b => b.remove());
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                    document.body.classList.remove('modal-open');
                }, 200);
            };

            // Toggle Fullscreen in Create Attribute Modal
            if (createAttrFullscreenBtn) {
                createAttrFullscreenBtn.addEventListener('click', function() {
                    const dlg = document.getElementById('createAttrModalDialog');
                    if (dlg) {
                        dlg.classList.toggle('modal-fullscreen');
                    }
                });
            }

            // Click "Create New" in Search: Attribute Modal -> opens Create Attribute Modal
            const modalCreateNewAttrBtn = document.getElementById('modalCreateNewAttrBtn');
            if (modalCreateNewAttrBtn) {
                modalCreateNewAttrBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.hideSearchAttrModal();
                    setTimeout(() => {
                        window.showCreateAttributeModal(activeTargetRow);
                    }, 120);
                });
            }

            // Click "Discard" in Create Attribute Modal -> returns to Search: Attribute Modal
            if (discardCreateAttrBtn) {
                discardCreateAttrBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.hideCreateAttributeModal();
                    setTimeout(() => {
                        window.showSearchAttrModal(activeTargetRow);
                    }, 120);
                });
            }

            // Click "Save" in Create Attribute Modal
            if (saveCreateAttrBtn) {
                saveCreateAttrBtn.addEventListener('click', function() {
                    const name = createAttrNameInput ? createAttrNameInput.value.trim() : '';
                    if (!name) {
                        Swal.fire({icon: 'warning', title: 'Attribute Name Required', text: 'Please enter attribute name!'});
                        if (createAttrNameInput) createAttrNameInput.focus();
                        return;
                    }

                    const displayType = createAttrDisplayType ? createAttrDisplayType.value : 'Radio';
                    const creation = document.querySelector('input[name="createAttrVariantCreation"]:checked')?.value || 'Instantly';

                    // Collect any values entered in the table
                    const enteredVals = [];
                    document.querySelectorAll('#createAttrValuesTableBody .create-val-row').forEach(tr => {
                        const v = tr.querySelector('.val-name-input')?.value?.trim();
                        if (v) enteredVals.push(v);
                    });

                    // Add to masterAttributes list if not exists
                    const existing = masterAttributes.find(a => a.name.toLowerCase() === name.toLowerCase());
                    if (!existing) {
                        masterAttributes.unshift({ name, display_type: displayType, creation });
                    }

                    // Assign to active row
                    if (!activeTargetRow) {
                        activeTargetRow = attributesConfigBody.querySelector('.attr-row') || createAttributeRow('', []);
                    }

                    if (activeTargetRow) {
                        const attrInput = activeTargetRow.querySelector('.attr-name-input');
                        const valuesBox = activeTargetRow.querySelector('.values-tag-box');
                        const tagInput = activeTargetRow.querySelector('.tag-input-field');

                        if (attrInput) attrInput.value = name;

                        // Insert values pills into values tag box
                        if (enteredVals.length > 0 && valuesBox && tagInput) {
                            enteredVals.forEach(v => {
                                const currentPills = Array.from(valuesBox.querySelectorAll('.tag-pill')).map(p => p.dataset.val.toLowerCase());
                                if (!currentPills.includes(v.toLowerCase())) {
                                    const pill = document.createElement('span');
                                    pill.className = 'tag-pill';
                                    pill.dataset.val = v;
                                    pill.innerHTML = `<span>${v}</span><span class="remove-tag">&times;</span>`;
                                    pill.querySelector('.remove-tag').addEventListener('click', function(e) {
                                        e.stopPropagation();
                                        pill.remove();
                                        syncAttributesToVariants();
                                    });
                                    valuesBox.insertBefore(pill, tagInput);
                                    tagInput.placeholder = '';
                                }
                            });
                        }
                        syncAttributesToVariants();
                    }

                    window.hideCreateAttributeModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Attribute Created',
                        text: `"${name}" added successfully!`,
                        timer: 1200,
                        showConfirmButton: false
                    });
                });
            }

            // Dismiss modal handlers
            document.querySelectorAll('.btn-close-attr-modal, #modalCloseAttrBtn').forEach(btn => {
                btn.addEventListener('click', hideSearchAttrModal);
            });

            // Click on modal backdrop / dark background to dismiss
            const searchAttrModalEl = document.getElementById('searchAttributeModal');
            if (searchAttrModalEl) {
                searchAttrModalEl.addEventListener('click', function(e) {
                    if (e.target === searchAttrModalEl) {
                        window.hideSearchAttrModal();
                    }
                });
            }

            if (createAttrModalEl) {
                createAttrModalEl.addEventListener('click', function(e) {
                    if (e.target === createAttrModalEl) {
                        window.hideCreateAttributeModal();
                    }
                });
            }

            // Escape key dismisses modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const c = document.getElementById('createAttributeModal');
                    if (c && (c.classList.contains('show') || c.style.display === 'block')) {
                        window.hideCreateAttributeModal();
                        return;
                    }
                    const m = document.getElementById('searchAttributeModal');
                    if (m && (m.classList.contains('show') || m.style.display === 'block')) {
                        window.hideSearchAttrModal();
                    }
                }
            });

            // Global floating dropdown attached directly to body to avoid ANY table clipping
            let globalAttrDropdown = document.getElementById('globalAttrDropdownMenu');
            if (!globalAttrDropdown) {
                globalAttrDropdown = document.createElement('ul');
                globalAttrDropdown.id = 'globalAttrDropdownMenu';
                globalAttrDropdown.className = 'attr-dropdown-menu';
                globalAttrDropdown.style.cssText = 'position: fixed !important; z-index: 999999 !important; display: none; max-height: 280px; overflow-y: auto; background: #222736 !important; border: 1px solid #384252 !important; border-radius: 4px; box-shadow: 0 14px 35px rgba(0,0,0,0.6) !important; margin: 0; padding: 4px 0; list-style: none;';
                document.body.appendChild(globalAttrDropdown);
            }

            function positionGlobalDropdown(inputEl) {
                if (!inputEl || !globalAttrDropdown) return;
                const rect = inputEl.getBoundingClientRect();
                globalAttrDropdown.style.top = (rect.bottom + 2) + 'px';
                globalAttrDropdown.style.left = rect.left + 'px';
                globalAttrDropdown.style.width = Math.max(rect.width, 280) + 'px';
            }

            function openAttrDropdown(inputEl, rowEl) {
                activeTargetRow = rowEl;
                positionGlobalDropdown(inputEl);
                buildDropdownItems(inputEl.value);
                globalAttrDropdown.style.display = 'block';
                globalAttrDropdown.classList.add('show');
            }

            function closeAttrDropdown() {
                if (globalAttrDropdown) {
                    globalAttrDropdown.style.display = 'none';
                    globalAttrDropdown.classList.remove('show');
                }
            }

            // Helper to build dropdown items in the global floating dropdown
            function buildDropdownItems(filterText = '') {
                if (!globalAttrDropdown) return;
                globalAttrDropdown.innerHTML = '';
                const q = (filterText || '').toLowerCase().trim();

                // If no filter, show top 8 popular attributes from image 1
                let listToShow = [];
                if (!q) {
                    listToShow = masterAttributes.slice(0, 8);
                } else {
                    listToShow = masterAttributes.filter(a => a.name.toLowerCase().includes(q)).slice(0, 10);
                }

                if (listToShow.length === 0) {
                    const noLi = document.createElement('li');
                    noLi.className = 'attr-dropdown-item text-muted';
                    noLi.style.cssText = 'padding: 6px 14px; font-size: 12.5px; cursor: default; color: #94a3b8;';
                    noLi.textContent = 'No matching attribute';
                    globalAttrDropdown.appendChild(noLi);
                } else {
                    listToShow.forEach(attr => {
                        const li = document.createElement('li');
                        li.className = 'attr-dropdown-item';
                        li.dataset.value = attr.name;
                        li.textContent = attr.name;
                        li.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            if (activeTargetRow) {
                                const attrInput = activeTargetRow.querySelector('.attr-name-input');
                                const tagInput = activeTargetRow.querySelector('.tag-input-field');
                                if (attrInput) attrInput.value = attr.name;
                                closeAttrDropdown();
                                if (tagInput) setTimeout(() => tagInput.focus(), 60);
                                syncAttributesToVariants();
                            }
                        });
                        globalAttrDropdown.appendChild(li);
                    });
                }

                // Bottom link: Search more...
                const searchMoreLi = document.createElement('li');
                searchMoreLi.className = 'attr-dropdown-search-more';
                searchMoreLi.setAttribute('role', 'button');
                searchMoreLi.setAttribute('tabindex', '0');
                searchMoreLi.innerHTML = `<i class="fas fa-search me-1" style="font-size:11px;"></i> Search more...`;

                function handleSearchMoreClick(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    const target = activeTargetRow;
                    closeAttrDropdown();
                    window.showSearchAttrModal(target);
                }

                searchMoreLi.addEventListener('mousedown', handleSearchMoreClick);
                searchMoreLi.addEventListener('click', handleSearchMoreClick);
                globalAttrDropdown.appendChild(searchMoreLi);
            }

            // Close global dropdown on outside click
            document.addEventListener('mousedown', function(e) {
                if (!e.target.closest('.attr-input-wrap') && !e.target.closest('#globalAttrDropdownMenu')) {
                    closeAttrDropdown();
                }
            });

            window.addEventListener('scroll', function() {
                if (globalAttrDropdown && globalAttrDropdown.style.display === 'block' && activeTargetRow) {
                    const attrInput = activeTargetRow.querySelector('.attr-name-input');
                    if (attrInput) positionGlobalDropdown(attrInput);
                }
            }, true);

            window.addEventListener('resize', function() {
                if (globalAttrDropdown && globalAttrDropdown.style.display === 'block' && activeTargetRow) {
                    const attrInput = activeTargetRow.querySelector('.attr-name-input');
                    if (attrInput) positionGlobalDropdown(attrInput);
                }
            });

            let attributeRowCount = 0;

            function createAttributeRow(defaultAttr = '', defaultVals = []) {
                attributeRowCount++;
                const rowId = 'attr_row_' + Date.now() + '_' + attributeRowCount;
                const tr = document.createElement('tr');
                tr.className = 'attr-row';
                tr.id = rowId;

                tr.innerHTML = `
                    <td class="text-center" style="width: 32px;">
                        <span class="attr-grip-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></span>
                    </td>
                    <td style="width: 280px;">
                        <div class="attr-input-wrap">
                            <input type="text" class="attr-name-input" placeholder="Attribute name (e.g. Color, Size, ... )" value="${defaultAttr}" autocomplete="off">
                            <button type="button" class="attr-caret-btn"><i class="fas fa-caret-down"></i></button>
                        </div>
                    </td>
                    <td>
                        <div class="values-tag-box">
                            <input type="text" class="tag-input-field" placeholder="List of possible values (e.g. Blue, Green, White, ... )">
                        </div>
                    </td>
                    <td class="text-end pe-3" style="width: 130px;">
                        <button type="button" class="btn-configure-attr me-1" title="Configure variant values & prices">Configure</button>
                        <button type="button" class="btn-delete-attr" title="Delete attribute line"><i class="fas fa-trash-alt"></i></button>
                    </td>
                `;

                attributesConfigBody.appendChild(tr);

                const attrInput = tr.querySelector('.attr-name-input');
                const caretBtn = tr.querySelector('.attr-caret-btn');
                const valuesBox = tr.querySelector('.values-tag-box');
                const tagInput = tr.querySelector('.tag-input-field');
                const configBtn = tr.querySelector('.btn-configure-attr');
                const deleteBtn = tr.querySelector('.btn-delete-attr');

                attrInput.addEventListener('click', (e) => { e.stopPropagation(); openAttrDropdown(attrInput, tr); });
                attrInput.addEventListener('focus', (e) => { openAttrDropdown(attrInput, tr); });
                caretBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (globalAttrDropdown && globalAttrDropdown.style.display === 'block' && activeTargetRow === tr) {
                        closeAttrDropdown();
                    } else {
                        openAttrDropdown(attrInput, tr);
                    }
                });

                attrInput.addEventListener('input', function() {
                    openAttrDropdown(attrInput, tr);
                    syncAttributesToVariants();
                });

                // Add tag pill helper
                function addTagPill(val) {
                    val = val.trim();
                    if (!val) return;
                    // Prevent duplicate tags within the same row
                    const existing = Array.from(valuesBox.querySelectorAll('.tag-pill')).map(p => p.dataset.val.toLowerCase());
                    if (existing.includes(val.toLowerCase())) return;

                    const pill = document.createElement('span');
                    pill.className = 'tag-pill';
                    pill.dataset.val = val;
                    pill.innerHTML = `<span>${val}</span><span class="remove-tag">&times;</span>`;

                    pill.querySelector('.remove-tag').addEventListener('click', function(e) {
                        e.stopPropagation();
                        pill.remove();
                        syncAttributesToVariants();
                    });

                    valuesBox.insertBefore(pill, tagInput);
                    tagInput.value = '';
                    tagInput.placeholder = '';
                    closeValueSuggestDropdown();
                    syncAttributesToVariants();
                }

                // Initial default values if provided
                if (defaultVals && defaultVals.length > 0) {
                    defaultVals.forEach(v => addTagPill(v));
                }

                // Inline value autocomplete & Create "<value>" matching Video 03:08 - 05:10
                function showValueSuggestions() {
                    const attrName = attrInput.value.trim();
                    const typed = tagInput.value.trim();
                    const existingPills = Array.from(valuesBox.querySelectorAll('.tag-pill')).map(p => p.dataset.val.toLowerCase());

                    let presetVals = [];
                    const masterMatch = masterAttributes.find(m => m.name.toLowerCase() === attrName.toLowerCase());
                    if (masterMatch && masterMatch.default_values) {
                        presetVals = masterMatch.default_values;
                    } else if (attrName.toLowerCase().includes('size')) {
                        presetVals = ['1/2', '3/4', '3/8', '1', '1 1/4', '1 1/2', '2', '2 1/2', '3'];
                    } else if (attrName.toLowerCase().includes('length')) {
                        presetVals = ['1', '1/2', '3/4', '1 1/4', '1 1/2', '2', '3', '4'];
                    } else if (attrName.toLowerCase().includes('color') || attrName.toLowerCase().includes('colour')) {
                        presetVals = ['White', 'Black', 'Blue', 'Red', 'Green', 'Yellow', 'Silver', 'Gold'];
                    }

                    const rect = tagInput.getBoundingClientRect();
                    globalValueSuggestDropdown.style.top = (rect.bottom + 4) + 'px';
                    globalValueSuggestDropdown.style.left = rect.left + 'px';
                    globalValueSuggestDropdown.style.width = Math.max(rect.width + 50, 210) + 'px';
                    globalValueSuggestDropdown.innerHTML = '';

                    let hasItems = false;

                    // 1. Create "<typed>" option if user typed something new
                    if (typed && !existingPills.includes(typed.toLowerCase())) {
                        const createLi = document.createElement('li');
                        createLi.className = 'values-suggest-item values-suggest-create';
                        createLi.innerHTML = `<span class="d-flex align-items-center gap-1 text-truncate"><i class="fas fa-plus-circle me-1 text-teal" style="color:#00a09d;"></i> Create "<strong>${typed}</strong>"</span>`;
                        createLi.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            addTagPill(typed);
                        });
                        globalValueSuggestDropdown.appendChild(createLi);
                        hasItems = true;
                    }

                    // 2. Preset suggestions matching typed filter
                    presetVals.forEach(pv => {
                        if (existingPills.includes(pv.toLowerCase())) return;
                        if (typed && !pv.toLowerCase().includes(typed.toLowerCase())) return;

                        const li = document.createElement('li');
                        li.className = 'values-suggest-item';
                        li.textContent = pv;
                        li.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            addTagPill(pv);
                        });
                        globalValueSuggestDropdown.appendChild(li);
                        hasItems = true;
                    });

                    if (hasItems) {
                        globalValueSuggestDropdown.style.display = 'block';
                    } else {
                        closeValueSuggestDropdown();
                    }
                }

                tagInput.addEventListener('focus', showValueSuggestions);
                tagInput.addEventListener('input', showValueSuggestions);

                // Enter or Comma key adds tag
                tagInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ',') {
                        e.preventDefault();
                        if (this.value.trim()) {
                            addTagPill(this.value);
                        }
                    } else if (e.key === 'Backspace' && !this.value) {
                        const pills = valuesBox.querySelectorAll('.tag-pill');
                        if (pills.length > 0) {
                            pills[pills.length - 1].remove();
                            syncAttributesToVariants();
                        }
                    }
                });

                tagInput.addEventListener('blur', function() {
                    setTimeout(() => closeValueSuggestDropdown(), 150);
                });

                valuesBox.addEventListener('click', () => {
                    tagInput.focus();
                });

                // Configure button: opens Configure Attribute Values Modal (Video 05:27 - 07:06)
                configBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.openConfigureAttributeModal(tr);
                });

                // Delete attribute row
                deleteBtn.addEventListener('click', function() {
                    tr.remove();
                    closeValueSuggestDropdown();
                    syncAttributesToVariants();
                });

                return tr;
            }

            // Close dropdowns on window click
            document.addEventListener('click', () => {
                document.querySelectorAll('.attr-dropdown-menu.show').forEach(m => m.classList.remove('show'));
            });

            // Add a line button
            if (addAttributeLineBtn) {
                addAttributeLineBtn.addEventListener('click', function() {
                    createAttributeRow('', []);
                });
            }

            // Toggle Matrix Display
            const toggleMatrixBtn = document.getElementById('toggleMatrixBtn');
            const matrixBodyContainer = document.getElementById('matrixBodyContainer');
            const toggleMatrixText = document.getElementById('toggleMatrixText');
            if (toggleMatrixBtn && matrixBodyContainer) {
                toggleMatrixBtn.addEventListener('click', function() {
                    if (matrixBodyContainer.style.display === 'none') {
                        matrixBodyContainer.style.display = 'block';
                        toggleMatrixText.textContent = 'Hide Matrix';
                    } else {
                        matrixBodyContainer.style.display = 'none';
                        toggleMatrixText.textContent = 'Show Matrix';
                    }
                });
            }

            // =========================================================
            // Top Smart Stat Button Handlers (Odoo Variants X)
            // =========================================================
            const topVariantsStatBtn = document.getElementById('topVariantsStatBtn');
            if (topVariantsStatBtn) {
                topVariantsStatBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabVariantsLink = document.querySelector('.odoo-tab-link[data-tab="tab-variants"]');
                    if (tabVariantsLink) tabVariantsLink.click();
                    setTimeout(() => {
                        const card = document.getElementById('generatedVariantsCard');
                        if (card) {
                            card.scrollIntoView({ behavior: 'smooth' });
                            card.style.transition = 'box-shadow 0.3s ease';
                            card.style.boxShadow = '0 0 0 3px rgba(0, 160, 157, 0.4)';
                            setTimeout(() => { card.style.boxShadow = ''; }, 1200);
                        }
                    }, 120);
                });
            }

            // =========================================================
            // Attribute Values Configuration & Exclusions (Exclude For)
            // =========================================================
            let attributeConfigurations = {}; // { [attrName]: { [val]: { exclude_for: [], active: true, extra_price: 0 } } }
            let currentConfigAttrName = '';
            let currentConfigRow = null;

            // Global floating dropdown for Exclude For suggestions
            let globalExcludeDropdown = document.getElementById('globalExcludeDropdownMenu');
            if (!globalExcludeDropdown) {
                globalExcludeDropdown = document.createElement('ul');
                globalExcludeDropdown.id = 'globalExcludeDropdownMenu';
                globalExcludeDropdown.className = 'exclude-dropdown-menu';
                document.body.appendChild(globalExcludeDropdown);
            }

            function closeExcludeDropdown() {
                if (globalExcludeDropdown) {
                    globalExcludeDropdown.style.display = 'none';
                    globalExcludeDropdown.innerHTML = '';
                }
            }

            document.addEventListener('mousedown', function(e) {
                if (!e.target.closest('.exclude-tag-box') && !e.target.closest('#globalExcludeDropdownMenu')) {
                    closeExcludeDropdown();
                }
            });

            // Global floating dropdown for Inline Tag Values suggestion & Create "<val>"
            let globalValueSuggestDropdown = document.getElementById('globalValueSuggestDropdownMenu');
            if (!globalValueSuggestDropdown) {
                globalValueSuggestDropdown = document.createElement('ul');
                globalValueSuggestDropdown.id = 'globalValueSuggestDropdownMenu';
                globalValueSuggestDropdown.className = 'values-suggest-dropdown';
                document.body.appendChild(globalValueSuggestDropdown);
            }

            function closeValueSuggestDropdown() {
                if (globalValueSuggestDropdown) {
                    globalValueSuggestDropdown.style.display = 'none';
                    globalValueSuggestDropdown.innerHTML = '';
                }
            }

            document.addEventListener('mousedown', function(e) {
                if (!e.target.closest('.values-tag-box') && !e.target.closest('#globalValueSuggestDropdownMenu')) {
                    closeValueSuggestDropdown();
                }
            });

            window.openConfigureAttributeModal = function(rowEl) {
                if (!rowEl) return;
                currentConfigRow = rowEl;
                const attrName = rowEl.querySelector('.attr-name-input')?.value.trim();
                if (!attrName) {
                    Swal.fire({ icon: 'info', title: 'Attribute Name Missing', text: 'Please enter or select an attribute name first.' });
                    return;
                }

                const currentVals = Array.from(rowEl.querySelectorAll('.tag-pill')).map(p => p.dataset.val.trim()).filter(v => v);
                if (currentVals.length === 0) {
                    Swal.fire({ icon: 'info', title: 'Values Missing', text: 'Please add at least one value for this attribute first.' });
                    return;
                }

                currentConfigAttrName = attrName;

                // Update modal breadcrumbs
                const pName = productNameInput ? (productNameInput.value.trim() || 'Product') : 'Product';
                const bcProd = document.getElementById('configModalProductBreadcrumb');
                const bcAttr = document.getElementById('configModalAttrTitle');
                if (bcProd) bcProd.textContent = pName;
                if (bcAttr) bcAttr.textContent = attrName;

                // Gather other attributes on page for Exclude For candidates
                const otherAttributes = [];
                document.querySelectorAll('#attributesConfigBody .attr-row').forEach(otherRow => {
                    if (otherRow === rowEl) return;
                    const oName = otherRow.querySelector('.attr-name-input')?.value.trim();
                    const oVals = Array.from(otherRow.querySelectorAll('.tag-pill')).map(p => p.dataset.val.trim()).filter(v => v);
                    if (oName && oVals.length > 0) {
                        otherAttributes.push({ name: oName, values: oVals });
                    }
                });

                // Candidates list: "AttrName: ValName"
                const candidateExclusions = [];
                otherAttributes.forEach(oa => {
                    oa.values.forEach(ov => {
                        candidateExclusions.push(`${oa.name}: ${ov}`);
                    });
                });

                if (!attributeConfigurations[attrName]) {
                    attributeConfigurations[attrName] = {};
                }

                const tbody = document.getElementById('configAttrValuesBody');
                if (!tbody) return;
                tbody.innerHTML = '';

                currentVals.forEach(val => {
                    if (!attributeConfigurations[attrName][val]) {
                        attributeConfigurations[attrName][val] = {
                            exclude_for: [],
                            active: true,
                            extra_price: 0.00
                        };
                    }
                    const cfg = attributeConfigurations[attrName][val];

                    const tr = document.createElement('tr');
                    tr.dataset.val = val;
                    tr.innerHTML = `
                        <td style="width: 38px; padding: 8px 10px; text-align: center; color: #64748b;">
                            <span style="cursor: grab; display: inline-flex; align-items: center; justify-content: center;">
                                <svg width="10" height="15" viewBox="0 0 10 15" fill="#64748b">
                                    <circle cx="2" cy="2.5" r="1.4"/><circle cx="8" cy="2.5" r="1.4"/>
                                    <circle cx="2" cy="7.5" r="1.4"/><circle cx="8" cy="7.5" r="1.4"/>
                                    <circle cx="2" cy="12.5" r="1.4"/><circle cx="8" cy="12.5" r="1.4"/>
                                </svg>
                            </span>
                        </td>
                        <td style="width: 140px; padding: 8px 14px; font-weight: 600; color: #ffffff;">
                            ${val}
                        </td>
                        <td style="padding: 8px 14px;">
                            <div class="exclude-tag-box" data-val="${val}">
                                <input type="text" class="exclude-input-field" placeholder="${candidateExclusions.length > 0 ? 'Click to exclude...' : 'No other attributes'}">
                            </div>
                        </td>
                        <td style="width: 90px; text-align: center; padding: 8px 10px;">
                            <input type="checkbox" class="config-active-check" ${cfg.active !== false ? 'checked' : ''} title="Toggle active">
                        </td>
                        <td style="width: 130px; text-align: right; padding: 8px 16px;">
                            <input type="number" step="0.01" class="form-control form-control-sm config-extra-price-input" value="${parseFloat(cfg.extra_price || 0).toFixed(2)}" placeholder="0.00">
                        </td>
                    `;

                    const exBox = tr.querySelector('.exclude-tag-box');
                    const exInput = tr.querySelector('.exclude-input-field');

                    // Helper to add exclude pill
                    function addExcludePill(exText) {
                        exText = exText.trim();
                        if (!exText) return;
                        const existingPills = Array.from(exBox.querySelectorAll('.exclude-pill')).map(p => p.dataset.ex);
                        if (existingPills.includes(exText)) return;

                        const pill = document.createElement('span');
                        pill.className = 'exclude-pill';
                        pill.dataset.ex = exText;
                        pill.innerHTML = `<span>${exText}</span><span class="exclude-remove">&times;</span>`;
                        pill.querySelector('.exclude-remove').addEventListener('click', function(e) {
                            e.stopPropagation();
                            pill.remove();
                        });
                        exBox.insertBefore(pill, exInput);
                        exInput.value = '';
                    }

                    // Render existing exclusions
                    if (Array.isArray(cfg.exclude_for)) {
                        cfg.exclude_for.forEach(ef => addExcludePill(ef));
                    }

                    // Open exclude dropdown on click / focus
                    function openExDropdown() {
                        if (candidateExclusions.length === 0) return;
                        const rect = exBox.getBoundingClientRect();
                        globalExcludeDropdown.style.top = (rect.bottom + 2) + 'px';
                        globalExcludeDropdown.style.left = rect.left + 'px';
                        globalExcludeDropdown.style.width = Math.max(rect.width, 240) + 'px';
                        globalExcludeDropdown.innerHTML = '';

                        const currentSelected = Array.from(exBox.querySelectorAll('.exclude-pill')).map(p => p.dataset.ex);
                        const q = exInput.value.toLowerCase().trim();

                        const available = candidateExclusions.filter(c => {
                            if (currentSelected.includes(c)) return false;
                            if (q && !c.toLowerCase().includes(q)) return false;
                            return true;
                        });

                        if (available.length === 0) {
                            const li = document.createElement('li');
                            li.className = 'exclude-dropdown-item text-muted';
                            li.style.cursor = 'default';
                            li.textContent = 'No exclusions available';
                            globalExcludeDropdown.appendChild(li);
                        } else {
                            available.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'exclude-dropdown-item';
                                li.textContent = item;
                                li.addEventListener('mousedown', function(e) {
                                    e.preventDefault();
                                    addExcludePill(item);
                                    closeExcludeDropdown();
                                    exInput.focus();
                                });
                                globalExcludeDropdown.appendChild(li);
                            });
                        }
                        globalExcludeDropdown.style.display = 'block';
                    }

                    exInput.addEventListener('focus', openExDropdown);
                    exInput.addEventListener('input', openExDropdown);
                    exBox.addEventListener('click', () => exInput.focus());

                    tbody.appendChild(tr);
                });

                // Show modal
                const modalEl = document.getElementById('configureAttributeModal');
                if (!modalEl) return;
                if (modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }

                let opened = false;
                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try { jQuery(modalEl).modal('show'); opened = true; } catch (e) {}
                }
                if (!opened && typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        let inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (!inst) inst = new bootstrap.Modal(modalEl);
                        inst.show();
                        opened = true;
                    } catch (e) {}
                }
                if (!opened) {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.style.opacity = '1';
                    modalEl.removeAttribute('aria-hidden');
                    modalEl.setAttribute('aria-modal', 'true');
                    document.body.classList.add('modal-open');

                    let backdrop = document.querySelector('.attr-config-custom-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show attr-config-custom-backdrop';
                        backdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.7); z-index: 9999995 !important;';
                        document.body.appendChild(backdrop);
                        backdrop.addEventListener('click', window.hideConfigureAttributeModal);
                    }
                }
            };

            window.hideConfigureAttributeModal = function() {
                closeExcludeDropdown();
                const modalEl = document.getElementById('configureAttributeModal');
                if (!modalEl) return;

                if (typeof jQuery !== 'undefined' && typeof jQuery(modalEl).modal === 'function') {
                    try { jQuery(modalEl).modal('hide'); } catch (e) {}
                }
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal === 'function') {
                    try {
                        const inst = bootstrap.Modal.getInstance ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (inst) inst.hide();
                    } catch (e) {}
                }

                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.attr-config-custom-backdrop').forEach(b => b.remove());
            };

            // Save Configure Attribute Modal
            const saveConfigAttrBtn = document.getElementById('saveConfigAttrBtn');
            if (saveConfigAttrBtn) {
                saveConfigAttrBtn.addEventListener('click', function() {
                    if (!currentConfigAttrName) {
                        window.hideConfigureAttributeModal();
                        return;
                    }

                    if (!attributeConfigurations[currentConfigAttrName]) {
                        attributeConfigurations[currentConfigAttrName] = {};
                    }

                    document.querySelectorAll('#configAttrValuesBody tr').forEach(tr => {
                        const val = tr.dataset.val;
                        if (!val) return;
                        const excludePills = Array.from(tr.querySelectorAll('.exclude-pill')).map(p => p.dataset.ex);
                        const isActive = tr.querySelector('.config-active-check')?.checked ?? true;
                        const extraPrice = parseFloat(tr.querySelector('.config-extra-price-input')?.value || 0) || 0;

                        attributeConfigurations[currentConfigAttrName][val] = {
                            exclude_for: excludePills,
                            active: isActive,
                            extra_price: extraPrice
                        };
                    });

                    window.hideConfigureAttributeModal();
                    syncAttributesToVariants();

                    Swal.fire({
                        icon: 'success',
                        title: 'Configuration Saved',
                        text: `Values & exclusions for "${currentConfigAttrName}" updated!`,
                        timer: 1200,
                        showConfirmButton: false
                    });
                });
            }

            // Select all variants checkbox in matrix table
            const selectAllVariantsCheck = document.getElementById('selectAllVariantsCheck');
            if (selectAllVariantsCheck) {
                selectAllVariantsCheck.addEventListener('change', function() {
                    document.querySelectorAll('.variant-row-check').forEach(chk => {
                        chk.checked = selectAllVariantsCheck.checked;
                    });
                });
            }

            // =========================================================
            // 11. DYNAMIC COMBINATIONS & VARIANTS MATRIX SYNCHRONIZER
            // =========================================================
            function getDefinedAttributes() {
                const rows = attributesConfigBody.querySelectorAll('.attr-row');
                const attrs = [];
                rows.forEach(r => {
                    const name = r.querySelector('.attr-name-input')?.value.trim();
                    const valPills = Array.from(r.querySelectorAll('.tag-pill')).map(p => p.dataset.val.trim()).filter(v => v);
                    if (name && valPills.length > 0) {
                        attrs.push({ name: name, values: valPills });
                    }
                });
                return attrs;
            }

            function cartesianProduct(arrays) {
                return arrays.reduce((acc, curr) => {
                    const res = [];
                    acc.forEach(a => {
                        curr.forEach(c => {
                            res.push(a.concat([c]));
                        });
                    });
                    return res;
                }, [[]]);
            }

            // Helper for case-insensitive and trimmed attribute configuration lookup
            function getAttrConfig(aName) {
                if (!aName) return null;
                const clean = aName.trim().toLowerCase();
                for (const key in attributeConfigurations) {
                    if (key.trim().toLowerCase() === clean) return attributeConfigurations[key];
                }
                return null;
            }

            function getValConfig(aCfg, vName) {
                if (!aCfg || !vName) return null;
                const clean = vName.trim().toLowerCase();
                for (const key in aCfg) {
                    if (key.trim().toLowerCase() === clean) return aCfg[key];
                }
                return null;
            }

            function syncAttributesToVariants() {
                const attrs = getDefinedAttributes();
                const productName = productNameInput ? (productNameInput.value.trim() || 'Product') : 'Product';
                const baseSale = genSaleInput ? (parseFloat(genSaleInput.value) || 1.00) : 1.00;
                const baseCost = genCostInput ? (parseFloat(genCostInput.value) || 0.00) : 0.00;
                const baseUnitName = unitDropdown ? unitDropdown.options[unitDropdown.selectedIndex].text.split(' ')[0] : 'Pcs';
                const isCartonMode = unitDropdown && unitDropdown.value === 'by_cartons';
                const itemCodeVal = document.getElementById('reference')?.value.trim() || document.getElementById('item_code')?.value.trim() || '';

                // Preserve existing prices / barcodes if previously modified
                const existingData = {};
                variantsBody.querySelectorAll('tr').forEach(tr => {
                    const vName = tr.querySelector('[name="variant_name[]"]')?.value;
                    if (vName) {
                        existingData[vName] = {
                            ref: tr.querySelector('[name="variant_ref[]"]')?.value,
                            stock: tr.querySelector('[name="variant_stock[]"]')?.value,
                            sale: tr.querySelector('[name="variant_sale_price[]"]')?.value,
                            purch: tr.querySelector('[name="variant_purchase_price[]"]')?.value,
                            wholesale: tr.querySelector('[name="variant_wholesale_price[]"]')?.value,
                            barcode: tr.querySelector('[name="variant_barcode[]"]')?.value,
                            conv: tr.querySelector('[name="variant_conv_factor[]"]')?.value,
                            unit: tr.querySelector('[name="variant_unit[]"]')?.value,
                        };
                    }
                });

                variantsBody.innerHTML = '';

                if (attrs.length === 0) {
                    // Standard product with single Base Variant row
                    matrixBadge.textContent = '0 Variants';
                    const topCountEl = document.getElementById('topVariantsCount');
                    if (topCountEl) topCountEl.textContent = '0';
                    addBaseVariantRow();
                    return;
                }

                // Compute Cartesian combinations
                const valueArrays = attrs.map(a => a.values.map(v => ({ attr: a.name, val: v })));
                let combos = cartesianProduct(valueArrays);

                // Apply Attribute Configurations & Exclusions (Exclude For) matching Video 05:27 - 07:06
                combos = combos.filter(combo => {
                    // 1. Check if any value is inactive
                    for (const item of combo) {
                        const aCfg = getAttrConfig(item.attr);
                        const vCfg = getValConfig(aCfg, item.val);
                        if (vCfg && vCfg.active === false) {
                            return false;
                        }
                    }

                    // 2. Check if any value has an exclude_for rule that matches another item in this combo
                    for (const item of combo) {
                        const aCfg = getAttrConfig(item.attr);
                        const vCfg = getValConfig(aCfg, item.val);
                        if (vCfg && Array.isArray(vCfg.exclude_for)) {
                            for (const exRule of vCfg.exclude_for) {
                                const rule = exRule.toLowerCase().trim();
                                for (const other of combo) {
                                    if (other === item) continue;
                                    const withAttr = `${other.attr}: ${other.val}`.toLowerCase().trim();
                                    const valOnly = other.val.toLowerCase().trim();
                                    if (rule === withAttr || rule === valOnly) {
                                        return false; // Excluded!
                                    }
                                }
                            }
                        }
                    }

                    return true;
                });

                // Update badge and top smart stat button
                matrixBadge.textContent = `${combos.length} Variants`;
                const topCountEl = document.getElementById('topVariantsCount');
                if (topCountEl) topCountEl.textContent = combos.length;

                combos.forEach((combo, idx) => {
                    const isBase = (idx === 0) ? 1 : 0;
                    const comboLabels = combo.map(c => c.val).join(' / ');
                    const comboName = `${productName} (${comboLabels})`;

                    let colorVal = '-';
                    let sizeVal = '-';

                    combo.forEach(c => {
                        const an = c.attr.toLowerCase();
                        if (an.includes('color') || an.includes('colour') || an.includes('paint')) {
                            colorVal = c.val;
                        } else if (an.includes('size') || an.includes('dimension') || an.includes('measurement')) {
                            sizeVal = c.val;
                        } else {
                            if (sizeVal === '-') sizeVal = c.val;
                            else if (colorVal === '-') colorVal = c.val;
                        }
                    });

                    // Extra price calculation
                    let extraPriceTotal = 0;
                    combo.forEach(c => {
                        const aCfg = getAttrConfig(c.attr);
                        const vCfg = getValConfig(aCfg, c.val);
                        if (vCfg && vCfg.extra_price) {
                            extraPriceTotal += parseFloat(vCfg.extra_price) || 0;
                        }
                    });

                    const prev = existingData[comboName] || {};
                    const vRef = prev.ref !== undefined ? prev.ref : (itemCodeVal ? `${itemCodeVal}-${comboLabels.replace(/\s+/g, '')}` : '');
                    const vStock = prev.stock !== undefined ? prev.stock : '0';
                    const vSale = prev.sale !== undefined ? prev.sale : (baseSale + extraPriceTotal).toFixed(2);
                    const vPurch = prev.purch !== undefined ? prev.purch : baseCost.toFixed(2);
                    const vWholesale = prev.wholesale !== undefined ? prev.wholesale : '0.00';
                    const vBarcode = prev.barcode || generateRandomBarcode();
                    const vConv = prev.conv !== undefined ? prev.conv : (isCartonMode ? '' : '1');
                    const vUnit = prev.unit || (isCartonMode ? 'Carton' : baseUnitName);

                    const comboPillsHtml = combo.map(c => `<span class="variant-combo-pill" title="${c.attr}">${c.val}</span>`).join(' ');

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center p-1">
                            <input type="checkbox" class="form-check-input variant-row-check">
                            <input type="hidden" name="variant_is_base[]" value="${isBase}">
                            <input type="hidden" name="variant_name[]" value="${comboName}">
                            <input type="hidden" name="variant_size[]" value="${sizeVal}">
                            <input type="hidden" name="variant_color[]" value="${colorVal}">
                            <input type="hidden" name="variant_alert_qty[]" value="0">
                        </td>
                        <td class="p-1">
                            <input type="text" class="form-control form-control-sm text-muted font-monospace" name="variant_ref[]" value="${vRef}" placeholder="Ref">
                        </td>
                        <td class="p-1 fw-semibold text-dark" style="font-size: 12.5px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${comboName}">
                            ${productName}
                        </td>
                        <td class="p-1">
                            ${comboPillsHtml}
                        </td>
                        <td class="p-1">
                            <select class="form-select form-select-sm px-1 fw-bold text-dark" name="variant_unit[]" style="font-size:11px;">
                                <option value="Carton" ${vUnit==='Carton'?'selected':''}>Carton</option>
                                <option value="Pcs" ${vUnit==='Pcs'?'selected':''}>Pcs</option>
                                <option value="Kg" ${vUnit==='Kg'?'selected':''}>Kg</option>
                                <option value="Gm" ${vUnit==='Gm'?'selected':''}>Gm</option>
                                <option value="Ft" ${vUnit==='Ft'?'selected':''}>Ft</option>
                                <option value="Meter" ${vUnit==='Meter'?'selected':''}>Mtr</option>
                                <option value="Box" ${vUnit==='Box'?'selected':''}>Box</option>
                                <option value="Dozen" ${vUnit==='Dozen'?'selected':''}>Dzn</option>
                            </select>
                        </td>
                        <td class="p-1">
                            <input type="number" class="form-control form-control-sm text-center fw-bold text-primary stock-input" name="variant_stock[]" step="any" value="${vStock}">
                        </td>
                        <td class="p-0 conv-col">
                            <input type="text" inputmode="decimal" class="form-control form-control-sm conv-factor-input text-center fw-bold text-success" name="variant_conv_factor[]" value="${vConv}" placeholder="1" style="border-radius:0; border:1px solid #198754; height:28px;">
                        </td>
                        <td class="p-0 piece-wt-only-col">
                            <div style="position:relative;">
                                <input type="number" class="form-control form-control-sm piece-wt-display" name="variant_weight_per_piece[]" step="any" value="" placeholder="—" readonly style="padding-right:16px; border-radius:0; border:1px solid #dee2e6; height:28px; background:#f0fff4; color:#198754; font-weight:600;">
                                <span style="position:absolute;right:4px;top:50%;transform:translateY(-50%);font-size:9px;color:#198754;pointer-events:none;font-weight:700;">g</span>
                            </div>
                        </td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm sale-price-input" name="variant_sale_price[]" step="any" value="${vSale}" required></td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm purch-price-input" name="variant_purchase_price[]" step="any" value="${vPurch}" required></td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm" name="variant_wholesale_price[]" step="any" value="${vWholesale}"></td>
                        <td class="p-1"><input type="text" class="form-control form-control-sm font-monospace" name="variant_barcode[]" value="${vBarcode}" placeholder="Barcode"></td>
                        <td class="p-1 text-center">
                            ${isBase ? '<span class="badge bg-primary px-1" style="font-size:9px;">Base</span>' : '<button type="button" class="btn btn-sm btn-outline-danger remove-var-btn p-0 px-1" style="height:24px;width:24px;"><i class="fas fa-times" style="font-size:11px;"></i></button>'}
                        </td>
                    `;
                    variantsBody.appendChild(tr);
                });

                toggleFactorColumns();
            }

            // Sync product title with combinations
            if (productNameInput) {
                productNameInput.addEventListener('input', function() {
                    const attrs = getDefinedAttributes();
                    if (attrs.length === 0) {
                        const baseInput = document.querySelector('input.base-name-input');
                        if (baseInput) baseInput.value = this.value;
                    } else {
                        syncAttributesToVariants();
                    }
                });
            }

            // Sync general price inputs
            if (genSaleInput) {
                genSaleInput.addEventListener('input', function() {
                    const attrs = getDefinedAttributes();
                    if (attrs.length === 0) {
                        const baseSale = document.querySelector('.base-sale-input');
                        if (baseSale) baseSale.value = this.value;
                    }
                });
            }
            if (genCostInput) {
                genCostInput.addEventListener('input', function() {
                    const attrs = getDefinedAttributes();
                    if (attrs.length === 0) {
                        const baseCost = document.querySelector('.base-purch-input');
                        if (baseCost) baseCost.value = this.value;
                    }
                });
            }

            function addBaseVariantRow() {
                const tr = document.createElement('tr');
                const productName = productNameInput ? (productNameInput.value || '') : '';
                const baseUnitName = unitDropdown ? unitDropdown.options[unitDropdown.selectedIndex].text.split(' ')[0] : 'Pcs';
                const isCartonMode = unitDropdown && unitDropdown.value === 'by_cartons';
                const initSale = genSaleInput ? (genSaleInput.value || '1.00') : '1.00';
                const initCost = genCostInput ? (genCostInput.value || '0.00') : '0.00';

                tr.innerHTML = `
                    <td class="text-center p-1">
                        <input type="checkbox" class="form-check-input" checked disabled>
                        <input type="hidden" name="variant_is_base[]" value="1">
                        <input type="hidden" name="variant_size[]" value="-">
                        <input type="hidden" name="variant_color[]" value="-">
                        <input type="hidden" name="variant_alert_qty[]" value="0">
                    </td>
                    <td class="p-1">
                        <input type="text" class="form-control form-control-sm text-muted font-monospace" name="variant_ref[]" value="" placeholder="Ref">
                    </td>
                    <td class="p-1">
                        <input type="text" class="form-control form-control-sm base-name-input fw-bold" name="variant_name[]" value="${productName}" placeholder="Product Name">
                    </td>
                    <td class="p-1 text-muted fst-italic" style="font-size:11px;">
                        Standard (No Attributes)
                    </td>
                    <td class="p-1">
                        <select class="form-select form-select-sm fw-bold text-primary px-1" name="variant_unit[]" style="font-size:11px;">
                            <option value="Carton" ${isCartonMode||baseUnitName.includes('Carton')?'selected':''}>Carton</option>
                            <option value="Pcs" ${(!isCartonMode && (baseUnitName.includes('Pcs')||baseUnitName.includes('Pieces')))?'selected':''}>Pcs</option>
                            <option value="Kg" ${baseUnitName.includes('Kg')?'selected':''}>Kg</option>
                            <option value="Gm" ${baseUnitName.includes('Gm')?'selected':''}>Gm</option>
                            <option value="Ft" ${baseUnitName.includes('Ft')?'selected':''}>Ft</option>
                            <option value="Meter" ${baseUnitName.includes('Meter')?'selected':''}>Mtr</option>
                            <option value="Box">Box</option>
                            <option value="Dozen">Dzn</option>
                        </select>
                    </td>
                    <td class="p-1">
                        <input type="number" class="form-control form-control-sm text-center fw-bold text-primary stock-input" name="variant_stock[]" step="any" value="0" placeholder="0">
                    </td>
                    <td class="p-0 conv-col">
                        <input type="number" class="form-control form-control-sm conv-factor-input text-center fw-bold ${isCartonMode ? 'text-primary' : ''}" name="variant_conv_factor[]" step="any" value="${isCartonMode ? '' : '1'}" ${isCartonMode ? '' : 'readonly'} placeholder="${isCartonMode ? 'e.g. 6' : '1'}" style="border-radius:0; border:1px solid #dee2e6; height:28px; ${isCartonMode ? 'background:#fff;' : 'background:#f8f8f8;'}">
                    </td>
                    <td class="p-0 piece-wt-only-col">
                        <div style="position:relative;">
                            <input type="number" class="form-control form-control-sm" name="variant_weight_per_piece[]" step="any" value="1000" readonly style="padding-right:16px; border-radius:0; border:1px solid #dee2e6; height:28px; background:#f8f8f8;">
                            <span style="position:absolute;right:4px;top:50%;transform:translateY(-50%);font-size:9px;color:#999;pointer-events:none;font-weight:600;">g</span>
                        </div>
                    </td>
                    <td class="p-1"><input type="number" class="form-control form-control-sm base-sale-input" name="variant_sale_price[]" step="any" value="${initSale}" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control form-control-sm base-purch-input" name="variant_purchase_price[]" step="any" value="${initCost}" placeholder="0.00" required></td>
                    <td class="p-1"><input type="number" class="form-control form-control-sm" name="variant_wholesale_price[]" step="any" placeholder="0.00" value="0"></td>
                    <td class="p-1"><input type="text" class="form-control form-control-sm font-monospace" name="variant_barcode[]" value="${generateRandomBarcode()}" placeholder="Barcode"></td>
                    <td class="p-1 text-center">
                        <span class="badge bg-primary px-2 py-1" style="font-size:10px;">Base</span>
                    </td>
                `;
                variantsBody.appendChild(tr);

                const baseSaleInp = tr.querySelector('.base-sale-input');
                const basePurchInp = tr.querySelector('.base-purch-input');
                if (baseSaleInp) {
                    baseSaleInp.addEventListener('input', function() {
                        if (genSaleInput) genSaleInput.value = this.value;
                    });
                }
                if (basePurchInp) {
                    basePurchInp.addEventListener('input', function() {
                        if (genCostInput) genCostInput.value = this.value;
                    });
                }
                toggleFactorColumns();
            }

            function toggleFactorColumns() {
                if (!unitDropdown) return;
                const mode = unitDropdown.value;
                const isWeight = (mode === 'by_kg' || mode === 'by_gm' || mode === 'by_ton');
                const isCarton = (mode === 'by_cartons');

                const headerEl = document.getElementById('convFactorHeader');
                if (headerEl) {
                    if (isCarton) headerEl.textContent = 'Pcs / Carton';
                    else if (isWeight) headerEl.textContent = 'Conv Factor';
                    else headerEl.textContent = 'Conv / Pack';
                }

                document.querySelectorAll('.conv-col').forEach(el => {
                    el.style.display = (isCarton || isWeight) ? '' : 'none';
                });
                document.querySelectorAll('.piece-wt-only-col').forEach(el => {
                    el.style.display = isWeight ? '' : 'none';
                });
            }

            if (unitDropdown) {
                $(unitDropdown).on('change', function() {
                    toggleFactorColumns();
                });
                toggleFactorColumns();
            }

            // Remove variant button from matrix
            variantsBody.addEventListener('click', function(e) {
                const remBtn = e.target.closest('.remove-var-btn');
                if (remBtn) {
                    const row = remBtn.closest('tr');
                    if (row) {
                        row.remove();
                        const remaining = variantsBody.querySelectorAll('tr').length;
                        matrixBadge.textContent = `${remaining} Variants`;
                        const topCountEl = document.getElementById('topVariantsCount');
                        if (topCountEl) topCountEl.textContent = remaining;
                    }
                }
            });

            // Add Custom Row button in Matrix
            const enableVariantsBtn = document.getElementById('enableVariantsBtn');
            if (enableVariantsBtn) {
                enableVariantsBtn.addEventListener('click', function() {
                    const tr = document.createElement('tr');
                    const pName = productNameInput ? (productNameInput.value.trim() || 'Product') : 'Product';
                    const isCarton = unitDropdown && unitDropdown.value === 'by_cartons';
                    tr.innerHTML = `
                        <td class="text-center p-1">
                            <input type="checkbox" class="form-check-input variant-row-check">
                            <input type="hidden" name="variant_is_base[]" value="0">
                            <input type="hidden" name="variant_alert_qty[]" value="0">
                        </td>
                        <td class="p-1">
                            <input type="text" class="form-control form-control-sm text-muted font-monospace" name="variant_ref[]" value="" placeholder="Ref">
                        </td>
                        <td class="p-1">
                            <input type="text" class="form-control form-control-sm" name="variant_name[]" value="${pName} - New Variant">
                        </td>
                        <td class="p-1">
                            <div class="d-flex gap-1">
                                <input type="text" class="form-control form-control-sm" name="variant_size[]" placeholder="Size" style="width:50%;font-size:11px;">
                                <input type="text" class="form-control form-control-sm" name="variant_color[]" placeholder="Color" style="width:50%;font-size:11px;">
                            </div>
                        </td>
                        <td class="p-1">
                            <select class="form-select form-select-sm px-1 fw-bold text-dark" name="variant_unit[]" style="font-size:11px;">
                                <option value="Carton" ${isCarton?'selected':''}>Carton</option>
                                <option value="Pcs" ${!isCarton?'selected':''}>Pcs</option>
                                <option value="Kg">Kg</option>
                                <option value="Gm">Gm</option>
                                <option value="Ft">Ft</option>
                                <option value="Meter">Mtr</option>
                                <option value="Box">Box</option>
                                <option value="Dozen">Dzn</option>
                            </select>
                        </td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm text-center fw-bold text-primary stock-input" name="variant_stock[]" value="0"></td>
                        <td class="p-0 conv-col"><input type="text" class="form-control form-control-sm text-center fw-bold text-success" name="variant_conv_factor[]" value="${isCarton?'6':'1'}" style="border-radius:0; border:1px solid #198754; height:28px;"></td>
                        <td class="p-0 piece-wt-only-col"><input type="number" class="form-control form-control-sm" name="variant_weight_per_piece[]" value="1000" readonly style="border-radius:0; border:1px solid #dee2e6; height:28px;"></td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm sale-price-input" name="variant_sale_price[]" value="${genSaleInput?.value || '1.00'}" required></td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm purch-price-input" name="variant_purchase_price[]" value="${genCostInput?.value || '0.00'}" required></td>
                        <td class="p-1"><input type="number" class="form-control form-control-sm" name="variant_wholesale_price[]" value="0.00"></td>
                        <td class="p-1"><input type="text" class="form-control form-control-sm font-monospace" name="variant_barcode[]" value="${generateRandomBarcode()}" placeholder="Barcode"></td>
                        <td class="p-1 text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-var-btn p-0 px-1" style="height:24px;width:24px;"><i class="fas fa-times" style="font-size:11px;"></i></button></td>
                    `;
                    variantsBody.appendChild(tr);
                    toggleFactorColumns();
                });
            }

            // Mobile Add Variant helper
            window.mobileAddVariant = function() {
                if (enableVariantsBtn) enableVariantsBtn.click();
            };

            // Close floating dropdowns on window scroll
            window.addEventListener('scroll', function() {
                closeValueSuggestDropdown();
                closeExcludeDropdown();
            }, { passive: true });

            // Initialize 1 default attribute row on page load matching user's image
            createAttributeRow('', []);
            addBaseVariantRow();

            // =========================================================
            // 12. FORM SUBMISSION & BACKEND SYNCHRONIZATION
            // =========================================================
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate Brand & Category
                const catVal = document.getElementById('category-dropdown').value;
                const brandVal = document.getElementById('brand_id').value;
                if (!catVal || !brandVal) {
                    Swal.fire({icon: 'warning', title: 'Required Fields', text: 'Please select both Category and Brand!'});
                    return;
                }

                const gSale = parseFloat(genSaleInput ? genSaleInput.value : 0) || 0;
                const gCost = parseFloat(genCostInput ? genCostInput.value : 0) || 0;

                // Sync Variants data to hidden main fields for backend validation compatibility
                const vStocks = document.querySelectorAll('input[name="variant_stock[]"]');
                const vSale = document.querySelectorAll('input[name="variant_sale_price[]"]');
                const vWholesale = document.querySelectorAll('input[name="variant_wholesale_price[]"]');
                const vWeight = document.querySelectorAll('input[name="variant_weight_per_piece[]"]');
                const vPurch = document.querySelectorAll('input[name="variant_purchase_price[]"]');
                const vAlert = document.querySelectorAll('input[name="variant_alert_qty[]"]');
                const vConvFactors = document.querySelectorAll('input[name="variant_conv_factor[]"]');

                let totalStock = 0;
                vStocks.forEach(el => totalStock += (parseFloat(el.value) || 0));

                let firstSale = vSale.length > 0 ? (parseFloat(vSale[0].value) || gSale) : gSale;
                let firstWholesale = vWholesale.length > 0 ? (parseFloat(vWholesale[0].value) || 0) : 0;
                let firstWeight = vWeight.length > 0 ? (parseFloat(vWeight[0].value) || 0) : 0;
                let firstPurch = vPurch.length > 0 ? (parseFloat(vPurch[0].value) || gCost) : gCost;
                let firstAlert = vAlert.length > 0 ? (parseFloat(vAlert[0].value) || 0) : 0;
                let firstConv = vConvFactors.length > 0 ? (parseFloat(vConvFactors[0].value) || 0) : 0;

                const mode = unitDropdown ? unitDropdown.value : 'by_pieces';
                if (mode === 'by_cartons') {
                    let ppb = firstConv > 0 ? firstConv : 1;
                    document.getElementById('boxes_quantity').value = totalStock;
                    document.getElementById('pieces_per_box').value = ppb;
                    document.getElementById('loose_pieces').value = 0;
                    document.getElementById('piece_quantity').value = 0;
                } else {
                    document.getElementById('piece_quantity').value = totalStock;
                    document.getElementById('boxes_quantity').value = 0;
                    document.getElementById('pieces_per_box').value = 1;
                }
                document.getElementById('sale_price_per_box').value = firstSale;
                document.getElementById('wholesale_price').value = firstWholesale;
                document.getElementById('weight_per_piece').value = firstWeight;
                document.getElementById('purchase_price_per_piece').value = firstPurch;
                document.getElementById('alert_carton_quantity').value = firstAlert;

                // Submit Button Spinner
                const submitButtons = document.querySelectorAll('button[type="submit"], #mobileSubmitBtn');
                submitButtons.forEach(btn => {
                    btn.dataset.origHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                    btn.disabled = true;
                });

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
                    body: formData
                })
                .then(r => r.json().then(data => ({status: r.status, body: data})))
                .then(({status, body}) => {
                    if (status === 200 || body.status === 'success') {
                        Swal.fire({
                            icon: 'success', 
                            title: 'Saved!',
                            text: 'Product created successfully', 
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => window.location.href = "{{ route('product') }}");
                    } else {
                        const msg = body.errors ? Object.values(body.errors).flat().join('<br>') : (body.message || 'Error occurred while saving product');
                        Swal.fire({icon: 'error', title: 'Validation Error', html: msg});
                    }
                })
                .catch(err => Swal.fire({icon: 'error', title: 'Error', text: 'Server Error occurred!'}))
                .finally(() => {
                    submitButtons.forEach(btn => {
                        btn.innerHTML = btn.dataset.origHtml || '<i class="fas fa-save"></i> Save Product';
                        btn.disabled = false;
                    });
                });
            });
        });
    </script>
@endsection
