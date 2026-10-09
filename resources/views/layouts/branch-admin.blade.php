<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Branch Staff - Vape Expo')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        /* ============================================================ */
        /* BASE STYLES                                                  */
        /* ============================================================ */
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #0d6efd;
            --secondary-bg: #f8f9fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-light: rgba(0, 0, 0, 0.03);
            --navbar-height: 56px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            width: 100%;
            height: calc(100% - var(--navbar-height));
            align-items: stretch;
        }

        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            background: linear-gradient(145deg, #f5f7fa 0%, #e9ecef 100%);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.03);
            transition: margin-left 0.3s ease, width 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.active {
            margin-left: calc(var(--sidebar-width) * -1);
        }

        .sidebar-sticky {
            flex: 1;
            padding: 0.5rem 0;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f0f2f5;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            transition: background 0.2s;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f0f2f5;
        }

        /* Navigation */
        .sidebar .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
            margin: 2px 12px;
            border-radius: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.05);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.1);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0.75rem 1.25rem 0.25rem;
            margin-top: 0.5rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Badge Counts */
        .badge-count,
        .badge-count-cyan,
        .badge-count-green,
        .badge-count-gray,
        .badge-count-red {
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.55rem;
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 600;
            display: inline-block;
            min-width: 24px;
            text-align: center;
        }

        .badge-count {
            background: #0d6efd;
        }

        .badge-count-cyan {
            background: #0dcaf0;
        }

        .badge-count-green {
            background: #198754;
        }

        .badge-count-gray {
            background: #6c757d;
        }

        .badge-count-red {
            background: #dc3545;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px 25px;
            background: var(--secondary-bg);
            overflow-y: auto;
            width: 100%;
            box-sizing: border-box;
            min-width: 0;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: #0b5ed7;
            transform: scale(1.05);
        }

        .toggle-btn i {
            transition: transform 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .main-content .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            box-sizing: border-box;
        }

        .main-content .card {
            width: 100%;
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            background: white;
            box-sizing: border-box;
        }

        .main-content .card-header {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 1.25rem;
        }

        .main-content .card-body {
            padding: 1.25rem;
            box-sizing: border-box;
        }

        .main-content .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .main-content .table {
            width: 100%;
            margin-bottom: 0;
            background-color: transparent;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        .main-content .table th,
        .main-content .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            white-space: nowrap;
        }

        .main-content .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        /* Stock info grid */
        .stock-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-light);
        }

        .stock-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .stock-info-label {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Branch Info Card */
        .branch-info-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            word-break: break-word;
        }

        .branch-info-card i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            flex-shrink: 0;
        }

        .branch-info-card div {
            color: var(--text-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        /* Footer Info */
        .footer-info {
            margin: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            color: var(--text-dark);
            font-size: 0.9rem;
            word-break: break-word;
        }

        .footer-info i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            flex-shrink: 0;
        }

        .footer-info div {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        /* Top Navigation */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .top-navbar>div:first-child {
            flex: 1;
            min-width: 200px;
        }

        .top-navbar>div:last-child {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .pending-badge {
            animation: pulse 2s infinite;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            max-height: 400px;
            overflow-y: auto;
        }

        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            padding: 1rem;
            margin-bottom: 1rem;
            word-break: break-word;
        }

        .btn-outline-primary {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 20px;
            padding: 0.3rem 1rem;
            white-space: nowrap;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Print Styles */
        @media print {

            .sidebar,
            .top-navbar,
            .footer-info,
            .branch-info-card,
            .btn,
            .toggle-btn {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            .card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        /* Glassmorphism Navigation */
        .navbar-glass {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            backdrop-filter: blur(10px);
        }

        /* ============================================================ */
        /* GLOBAL MOBILE RESPONSIVE (Android + iPhone)                  */
        /* ============================================================ */
        html {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        body {
            -webkit-tap-highlight-color: transparent;
            -webkit-font-smoothing: antialiased;
        }

        img {
            max-width: 100%;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1040;
        }

        /* ============================================================ */
        /* DESKTOP RESPONSIVE — large monitors                          */
        /* ============================================================ */
        @media (min-width: 1400px) {
            .main-content {
                padding: 24px 32px;
            }
        }

        @media (min-width: 1600px) {
            .main-content {
                padding: 28px 40px;
            }
        }

        @media (min-width: 1800px) {
            :root {
                --sidebar-width: 300px;
            }
        }

        /* ============================================================ */
        /* TABLET — 769px to 1024px                                     */
        /* ============================================================ */
        @media (min-width: 769px) and (max-width: 1024px) {
            .main-content {
                padding: 18px 20px;
            }

            .stock-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ============================================================ */
        /* MOBILE — 768px and below                                     */
        /* ============================================================ */
        @media (max-width: 768px) {

            html,
            body {
                height: auto;
                min-height: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }

            .wrapper {
                height: auto;
                min-height: calc(100dvh - var(--navbar-height));
            }

            .main-content {
                overflow-y: visible;
                padding: 14px max(12px, env(safe-area-inset-right)) calc(80px + env(safe-area-inset-bottom)) max(12px, env(safe-area-inset-left));
            }

            /* Navbar */
            .navbar-glass {
                padding-top: env(safe-area-inset-top);
            }

            .navbar-glass .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
                flex-wrap: nowrap;
            }

            .navbar-glass .navbar-brand {
                font-size: 1rem !important;
                margin-right: 0;
            }

            .navbar-glass .navbar-brand small {
                display: none;
            }

            .navbar-glass .navbar-brand img {
                height: 26px;
            }

            #sidebarCollapse {
                padding: 0.35rem 0.6rem;
                font-size: 0.85rem;
                margin-right: 0.5rem !important;
            }

            .navbar-glass .navbar-text {
                display: none !important;
            }

            .navbar-glass form button {
                font-size: 0;
                padding: 0.4rem 0.65rem;
            }

            .navbar-glass form button i {
                font-size: 1rem;
                margin: 0 !important;
            }

            /* Sidebar drawer */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                margin-left: 0 !important;
                transform: translateX(-105%);
                transition: transform 0.3s ease;
                width: min(82vw, 300px);
                height: 100vh;
                height: 100dvh;
                z-index: 1050;
                padding-top: env(safe-area-inset-top);
                padding-bottom: env(safe-area-inset-bottom);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.18);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .sidebar .nav-link {
                padding: 0.85rem 1.1rem;
            }

            .toggle-btn {
                bottom: calc(16px + env(safe-area-inset-bottom));
                left: calc(16px + env(safe-area-inset-left));
            }

            /* Top navbar */
            .top-navbar {
                padding: 0.85rem 1rem !important;
                margin-bottom: 1rem !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.6rem !important;
            }

            .top-navbar>div:first-child {
                min-width: 0 !important;
                width: 100% !important;
            }

            .top-navbar h5 {
                font-size: 1.05rem !important;
                line-height: 1.3 !important;
                margin-bottom: 0.15rem !important;
                word-break: break-word;
                overflow-wrap: anywhere;
            }

            .top-navbar small.text-muted {
                font-size: 0.72rem !important;
                display: block;
            }

            .top-navbar>div:last-child {
                width: 100% !important;
                justify-content: flex-start !important;
                flex-wrap: wrap !important;
                gap: 0.4rem !important;
            }

            .top-navbar>div:last-child>span {
                margin-right: 0 !important;
                font-size: 0.72rem !important;
                padding: 0.25rem 0.6rem;
                background: #f8f9fa;
                border-radius: 20px;
            }

            .top-navbar .dropdown,
            .top-navbar .btn-warning,
            .top-navbar .pending-badge,
            .top-navbar .btn-outline-primary {
                width: auto !important;
                font-size: 0.78rem !important;
                padding: 0.4rem 0.7rem !important;
            }

            /* Cards */
            .main-content .card {
                border-radius: 14px;
            }

            .main-content .card-header {
                padding: 0.8rem 1rem;
            }

            .main-content .card-body {
                padding: 1rem;
            }

            /* Tables */
            .main-content .table-responsive {
                -webkit-overflow-scrolling: touch;
            }

            .main-content .table th,
            .main-content .table td {
                padding: 0.6rem 0.65rem;
                font-size: 0.8rem;
            }

            /* Forms: 16px stops iOS auto-zoom */
            input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="file"]),
            select,
            textarea {
                font-size: 16px !important;
            }

            .form-control,
            .form-select {
                min-height: 44px;
            }

            textarea.form-control {
                min-height: 88px;
            }

            .btn:not(.btn-sm) {
                min-height: 40px;
            }

            .btn-sm {
                min-height: 34px;
            }

            .form-label,
            label {
                font-size: 0.85rem;
            }

            /* Modals */
            .modal-dialog {
                margin: 0.6rem;
                max-width: none;
            }

            .modal-dialog-centered {
                min-height: calc(100% - 1.2rem);
            }

            .modal-content {
                border-radius: 16px;
                max-height: calc(100dvh - 1.2rem);
                overflow: hidden;
            }

            .modal-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .modal-footer {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .modal-footer .btn {
                flex: 1;
            }

            /* Dropdowns, pagination, alerts */
            .dropdown-menu {
                max-width: calc(100vw - 24px);
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.25rem;
            }

            .alert {
                font-size: 0.85rem;
                border-radius: 12px;
            }

            .admin-notification-container {
                top: calc(12px + env(safe-area-inset-top)) !important;
                right: 12px !important;
                left: 12px !important;
            }

            .admin-notification {
                width: auto !important;
            }

            /* WIDE TABLES → CARDS */
            .table-stack:not(.no-stack) thead {
                display: none;
            }

            .table-stack:not(.no-stack),
            .table-stack:not(.no-stack) tbody {
                display: block;
                width: 100%;
            }

            .table-stack:not(.no-stack) tbody tr {
                display: block;
                background: #fff;
                border: 1px solid #e5e9f0;
                border-radius: 14px;
                margin: 0 0 0.85rem;
                padding: 0;
                overflow: hidden;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            }

            .table-stack:not(.no-stack) td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                width: auto !important;
                padding: 0.45rem 0.9rem !important;
                border: none !important;
                border-bottom: 1px solid #f1f5f9 !important;
                white-space: normal !important;
                text-align: right;
            }

            .table-stack:not(.no-stack) td:first-child {
                padding-top: 0.8rem !important;
            }

            .table-stack:not(.no-stack) td:last-child {
                border-bottom: none !important;
                padding-bottom: 0.8rem !important;
            }

            .table-stack:not(.no-stack) td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                color: inherit;
                opacity: 0.7;
                text-align: left;
                flex-shrink: 0;
            }

            .table-stack:not(.no-stack) td:not([data-label])::before {
                display: none;
            }

            .table-stack:not(.no-stack) td[colspan] {
                display: block;
                text-align: center;
            }

            .table-stack:not(.no-stack) td[colspan]::before {
                display: none;
            }

            .table-stack:not(.no-stack) td.stack-media {
                justify-content: flex-start;
            }

            .table-stack:not(.no-stack) td.stack-media::before {
                display: none;
            }

            .table-stack:not(.no-stack) td.stack-actions {
                flex-wrap: wrap;
                justify-content: flex-start;
                text-align: left;
                gap: 0.45rem;
            }

            .table-stack:not(.no-stack) td.stack-actions::before {
                flex-basis: 100%;
            }

            .table-stack:not(.no-stack) td.stack-actions .btn-group {
                display: flex;
                flex-wrap: wrap;
                gap: 0.4rem;
                width: 100%;
            }

            .table-stack:not(.no-stack) td.stack-actions .btn-group>.btn,
            .table-stack:not(.no-stack) td.stack-actions>.btn {
                flex: 1 1 auto;
                margin-left: 0 !important;
                border-radius: 0.5rem !important;
                min-height: 38px;
            }

            .main-content .container-fluid.px-4 {
                padding-left: 2px !important;
                padding-right: 2px !important;
            }

            /* Page header buttons wrap */
            .main-content .d-flex.flex-wrap.justify-content-between {
                gap: 0.6rem;
            }

            .main-content .d-flex.flex-wrap.justify-content-between>.d-flex.gap-2 {
                width: 100% !important;
                flex-wrap: wrap !important;
                gap: 0.4rem !important;
                margin-top: 0.25rem !important;
            }

            .main-content .d-flex.flex-wrap.justify-content-between>.d-flex.gap-2 .btn {
                flex: 1 1 auto;
                font-size: 0.78rem !important;
                padding: 0.45rem 0.7rem !important;
                white-space: nowrap;
            }

            .main-content .d-flex.align-items-center>img {
                height: 38px !important;
                margin-right: 0.6rem !important;
            }

            .main-content .h3,
            .main-content h1.h3 {
                font-size: 1.15rem !important;
                line-height: 1.25 !important;
            }

            /* Stat cards (used on dashboard + other pages) */
            .stat-card-modern {
                padding: 0.8rem !important;
                gap: 0.55rem !important;
                border-radius: 16px !important;
                align-items: center !important;
            }

            .stat-icon-wrapper {
                width: 38px !important;
                height: 38px !important;
                font-size: 1.1rem !important;
                border-radius: 11px !important;
                flex-shrink: 0;
            }

            .stat-content {
                min-width: 0;
                flex: 1;
                overflow: hidden;
            }

            .stat-label {
                font-size: 0.6rem !important;
                letter-spacing: 0.3px !important;
                margin-bottom: 0.15rem !important;
                line-height: 1.2;
            }

            .stat-value {
                font-size: clamp(0.95rem, 4.2vw, 1.35rem) !important;
                line-height: 1.1 !important;
                white-space: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
                font-variant-numeric: tabular-nums;
                letter-spacing: -0.02em;
            }

            .row.g-4:has(.stat-card-modern) {
                --bs-gutter-x: 0.6rem;
                --bs-gutter-y: 0.6rem;
            }

            .row:has(.stat-card-modern)>.col-md-3:not([class*="col-"]) {
                width: 50% !important;
                flex: 0 0 auto !important;
            }

            .main-content .d-flex.gap-2 {
                flex-wrap: wrap;
            }

            .card-footer .d-flex.justify-content-between {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        /* ============================================================ */
        /* SMALL PHONES — 480px and below                               */
        /* ============================================================ */
        @media (max-width: 480px) {
            .stock-info-grid {
                grid-template-columns: 1fr;
            }

            .stock-info-item {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .main-content .card-body {
                padding: 0.85rem;
            }

            .row:has(.stat-card-modern)>[class*="col-"] {
                flex: 0 0 100% !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .stat-card-modern {
                padding: 0.85rem 1rem !important;
                gap: 0.75rem !important;
            }

            .stat-icon-wrapper {
                width: 42px !important;
                height: 42px !important;
                font-size: 1.2rem !important;
                border-radius: 12px !important;
            }

            .stat-value {
                font-size: 1.5rem !important;
                white-space: nowrap !important;
            }

            .stat-label {
                font-size: 0.68rem !important;
                letter-spacing: 0.4px !important;
            }
        }

        /* ============================================================ */
        /* TINY PHONES — 380px and below                                */
        /* ============================================================ */
        @media (max-width: 380px) {
            .main-content {
                padding-left: 10px;
                padding-right: 10px;
            }

            .main-content .card-body {
                padding: 0.75rem;
            }

            .top-navbar h5 {
                font-size: 0.95rem !important;
            }

            .top-navbar>div:last-child>span {
                font-size: 0.68rem !important;
            }

            .stat-card-modern {
                padding: 0.75rem 0.85rem !important;
                gap: 0.6rem !important;
            }

            .stat-value {
                font-size: 1.3rem !important;
            }

            .stat-icon-wrapper {
                width: 38px !important;
                height: 38px !important;
                font-size: 1.1rem !important;
                border-radius: 11px !important;
            }

            .stat-label {
                font-size: 0.62rem !important;
            }

            .main-content .d-flex.flex-wrap.justify-content-between>.d-flex.gap-2 .btn {
                flex: 1 1 100%;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-light me-3">
                    <i class="bi bi-list"></i> Menu
                </button>
                <a class="navbar-brand text-white fw-bold fs-4 d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                        class="d-inline-block me-2">
                    <span
                        style="background: linear-gradient(135deg, #fff 0%, #a0aec0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Vape
                        Expo</span>
                    <small
                        class="text-white-50 fs-6 ms-2 fw-normal">{{ Auth::user()->branch->name ?? 'Branch' }}</small>
                </a>
            </div>

            <div class="d-flex align-items-center ms-auto">
                <span class="navbar-text text-white me-3 d-flex align-items-center">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} <span
                        class="badge bg-light text-dark ms-2 rounded-pill px-3 py-1 fw-normal"
                        style="background: rgba(255,255,255,0.15) !important; color: white !important;">Staff</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3"
                        style="border-color: rgba(255,255,255,0.3);">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-sticky">
                <div class="branch-info-card">
                    <div><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</div>
                    <div class="small mt-1"><i class="bi bi-envelope"></i> {{ Auth::user()->email }}</div>
                    <div class="small mt-1"><i class="bi bi-shield-check"></i> Branch Staff</div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('branch-admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-heading">INVENTORY</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.index') }}">
                            <i class="bi bi-box-seam"></i> Inventory
                            @php
                                $inventoryCount = \App\Models\BranchInventory::where(
                                    'branch_id',
                                    Auth::user()->branch_id,
                                )->count();
                            @endphp
                            @if ($inventoryCount > 0)
                                <span class="badge-count-cyan float-end">{{ $inventoryCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.stock-history') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.stock-history') }}">
                            <i class="bi bi-clock-history"></i> Stock History
                        </a>
                    </li>

                    <li class="sidebar-heading">TRANSFERS</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfer.form') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfer.form') }}">
                            <i class="bi bi-send"></i> Request Transfer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.inventory.transfers') ? 'active' : '' }}"
                            href="{{ route('branch-admin.inventory.transfers') }}">
                            <i class="bi bi-arrow-left-right"></i> All Transfers
                            @php
                                $pendingTransfersNav = \App\Models\StockTransfer::where(function ($q) {
                                    $q->where('from_branch_id', Auth::user()->branch_id)->orWhere(
                                        'to_branch_id',
                                        Auth::user()->branch_id,
                                    );
                                })
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if ($pendingTransfersNav > 0)
                                <span class="badge-count-red float-end">{{ $pendingTransfersNav }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="sidebar-heading">PRODUCTS</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.products.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.products.index') }}">
                            <i class="bi bi-tags"></i> Catalog
                            @php
                                $catalogCount = \App\Models\Product::count();
                            @endphp
                            @if ($catalogCount > 0)
                                <span class="badge-count-green float-end">{{ $catalogCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.products.create') ? 'active' : '' }}"
                            href="{{ route('branch-admin.products.create') }}">
                            <i class="bi bi-plus-lg"></i> New Product
                        </a>
                    </li>

                    <li class="sidebar-heading">WAREHOUSE</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.warehouse.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.warehouse.index') }}">
                            <i class="bi bi-house-door"></i> Warehouse Stock
                        </a>
                    </li>

                    <li class="sidebar-heading">SALES</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.online-orders*') ? 'active' : '' }}"
                            href="{{ route('branch-admin.online-orders.index') }}">
                            <i class="bi bi-cart"></i> Online Orders
                            @php
                                $onlineOrdersCount = \App\Models\Order::where('branch_id', Auth::user()->branch_id)
                                    ->where('order_number', 'NOT LIKE', 'POS-%')
                                    ->whereIn('order_status', [
                                        'pending',
                                        'confirmed',
                                        'processing',
                                        'ready',
                                        'out_for_delivery',
                                    ])
                                    ->count();
                            @endphp
                            @if ($onlineOrdersCount > 0)
                                <span class="badge-count-green float-end">{{ $onlineOrdersCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.pos.index') ? 'active' : '' }}"
                            href="{{ route('branch-admin.pos.index') }}">
                            <i class="bi bi-cash-coin"></i> Point of Sale
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branch-admin.pos.history') ? 'active' : '' }}"
                            href="{{ route('branch-admin.pos.history') }}">
                            <i class="bi bi-clock-history"></i> Sales History
                        </a>
                    </li>

                    <li class="sidebar-heading">ACCOUNT</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>

                <div class="footer-info">
                    <div><i class="bi bi-clock"></i> 9AM - 10PM</div>
                    <div><i class="bi bi-telephone"></i> 0960 328 0432</div>
                    <div><i class="bi bi-person-circle"></i> Carlo Caranto</div>
                    <div class="mt-2"><i class="bi bi-shop"></i> 5 Branches</div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">{{ Auth::user()->branch->name }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
                    </span>
                    <span class="me-3 text-muted small">
                        <i class="bi bi-clock me-1"></i> {{ now()->format('h:i A') }}
                    </span>

                    @php
                        $lowStockCount = \App\Models\BranchInventory::where('branch_id', Auth::user()->branch_id)
                            ->whereColumn('quantity', '<=', 'low_stock_threshold')
                            ->where('is_disposed', false)
                            ->where('is_archived', false)
                            ->count();
                    @endphp
                    <div class="ms-2">
                        <a href="{{ route('branch-admin.inventory.low-stock') }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-exclamation-triangle"></i> Low Stock
                            <span
                                class="{{ $lowStockCount > 0 ? 'badge-count-red' : 'badge-count-gray' }}">{{ $lowStockCount }}</span>
                        </a>
                    </div>

                    @php
                        $totalPending = \App\Models\StockTransfer::where(function ($q) {
                            $q->where('from_branch_id', Auth::user()->branch_id)->orWhere(
                                'to_branch_id',
                                Auth::user()->branch_id,
                            );
                        })
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if ($totalPending > 0)
                        <div class="ms-2">
                            <a href="{{ route('branch-admin.inventory.transfers', ['filter' => 'all', 'status' => 'pending']) }}"
                                class="btn btn-warning btn-sm pending-badge">
                                <i class="bi bi-hourglass"></i> {{ $totalPending }} Pending
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please check the form:</strong>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <div class="toggle-btn" id="toggleBtn">
        <i class="bi bi-chevron-left"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ============================================================ -->
    <!-- NOTIFICATION SYSTEM                                          -->
    <!-- ============================================================ -->
    <script>
        (function() {
            if (!document.querySelector('#admin-notification-styles')) {
                const style = document.createElement('style');
                style.id = 'admin-notification-styles';
                style.textContent = `
                .admin-notification-container { position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none; }
                .admin-notification { pointer-events: auto; position: relative; width: 380px; background: white; border-radius: 16px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); overflow: hidden; animation: notificationSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
                .admin-notification-hide { animation: notificationSlideOut 0.3s ease forwards; }
                @keyframes notificationSlideIn { 0% { transform: translateX(100%) scale(0.8); opacity: 0; } 100% { transform: translateX(0) scale(1); opacity: 1; } }
                @keyframes notificationSlideOut { 0% { transform: translateX(0) scale(1); opacity: 1; } 100% { transform: translateX(100%) scale(0.8); opacity: 0; } }
                @keyframes progressShrink { from { width: 100%; } to { width: 0%; } }
                .admin-notification-inner { display: flex; align-items: center; gap: 14px; padding: 16px 18px; }
                .admin-notification-icon-wrapper { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
                .admin-notification-icon-wrapper i { font-size: 1.4rem; }
                .admin-notification-icon-wrapper.success { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
                .admin-notification-icon-wrapper.success i { color: #059669; }
                .admin-notification-icon-wrapper.error { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
                .admin-notification-icon-wrapper.error i { color: #dc2626; }
                .admin-notification-icon-wrapper.warning { background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%); }
                .admin-notification-icon-wrapper.warning i { color: #ea580c; }
                .admin-notification-icon-wrapper.info { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
                .admin-notification-icon-wrapper.info i { color: #2563eb; }
                .admin-notification-content { flex: 1; }
                .admin-notification-title { font-size: 0.875rem; font-weight: 700; margin-bottom: 4px; }
                .admin-notification.success .admin-notification-title { color: #059669; }
                .admin-notification.error .admin-notification-title { color: #dc2626; }
                .admin-notification.warning .admin-notification-title { color: #ea580c; }
                .admin-notification.info .admin-notification-title { color: #2563eb; }
                .admin-notification-message { font-size: 0.8rem; color: #475569; line-height: 1.4; }
                .admin-notification-close { background: transparent; border: none; cursor: pointer; padding: 4px; border-radius: 8px; color: #94a3b8; flex-shrink: 0; }
                .admin-notification-close:hover { background: #f1f5f9; color: #475569; }
                .admin-notification-close i { font-size: 0.9rem; }
                .admin-notification-progress { height: 3px; width: 100%; animation: progressShrink 4s linear forwards; }
                .admin-notification-progress.success { background: linear-gradient(90deg, #10b981, #34d399); }
                .admin-notification-progress.error { background: linear-gradient(90deg, #ef4444, #f87171); }
                .admin-notification-progress.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
                .admin-notification-progress.info { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
                .is-invalid { border-color: #dc2626 !important; }
                .invalid-feedback { display: block; width: 100%; margin-top: 0.25rem; font-size: 0.75rem; color: #dc2626; }
                @media (max-width: 480px) {
                    .admin-notification-container { top: 16px; right: 16px; left: 16px; }
                    .admin-notification { width: auto; }
                    .admin-notification-inner { padding: 12px 14px; gap: 10px; }
                    .admin-notification-icon-wrapper { width: 34px; height: 34px; }
                    .admin-notification-icon-wrapper i { font-size: 1.1rem; }
                }
            `;
                document.head.appendChild(style);
            }

            window.showNotification = function(message, type = 'success') {
                let container = document.querySelector('.admin-notification-container');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'admin-notification-container';
                    document.body.appendChild(container);
                }

                const notification = document.createElement('div');
                notification.className = `admin-notification admin-notification-${type}`;

                let icon = '';
                let title = '';

                switch (type) {
                    case 'success':
                        icon = 'bi-check-circle-fill';
                        title = 'Success';
                        break;
                    case 'error':
                        icon = 'bi-x-circle-fill';
                        title = 'Error';
                        break;
                    case 'warning':
                        icon = 'bi-exclamation-triangle-fill';
                        title = 'Warning';
                        break;
                    case 'info':
                        icon = 'bi-info-circle-fill';
                        title = 'Info';
                        break;
                    default:
                        icon = 'bi-info-circle-fill';
                        title = 'Notice';
                        type = 'info';
                }

                notification.innerHTML = `
                <div class="admin-notification-inner">
                    <div class="admin-notification-icon-wrapper ${type}">
                        <i class="bi ${icon}"></i>
                    </div>
                    <div class="admin-notification-content">
                        <div class="admin-notification-title">${title}</div>
                        <div class="admin-notification-message">${message}</div>
                    </div>
                    <button class="admin-notification-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="admin-notification-progress ${type}"></div>
            `;

                container.appendChild(notification);

                const progressBar = notification.querySelector('.admin-notification-progress');
                if (progressBar) {
                    progressBar.style.animation = 'progressShrink 4s linear forwards';
                }

                const dismissNotification = (notif) => {
                    notif.classList.add('admin-notification-hide');
                    setTimeout(() => {
                        if (notif && notif.parentElement) {
                            notif.remove();
                        }
                    }, 300);
                };

                const timeoutId = setTimeout(() => {
                    dismissNotification(notification);
                }, 4000);

                const closeBtn = notification.querySelector('.admin-notification-close');
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    clearTimeout(timeoutId);
                    dismissNotification(notification);
                });

                notification.addEventListener('click', (e) => {
                    if (e.target === notification || e.target.closest('.admin-notification-content')) {
                        clearTimeout(timeoutId);
                        dismissNotification(notification);
                    }
                });

                notification.addEventListener('mouseenter', () => {
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'paused';
                    }
                    clearTimeout(timeoutId);
                });

                notification.addEventListener('mouseleave', () => {
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'running';
                    }
                    const newTimeoutId = setTimeout(() => {
                        dismissNotification(notification);
                    }, 2000);
                    notification._timeoutId = newTimeoutId;
                });
            };

            console.log('✅ Admin Notification System embedded successfully!');
        })();
    </script>

    <!-- Global Functions for Modals -->
    <script>
        window.submitEditForm = function(id) {
            console.log('submitEditForm called with id:', id);
            const form = document.getElementById('editForm' + id);
            if (!form) {
                console.error('Form not found with id: editForm' + id);
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Form not found. Please refresh and try again.', 'error');
                } else {
                    alert('Form not found. Please refresh and try again.');
                }
                return;
            }

            const submitBtn = form.querySelector('.btn-update');
            if (!submitBtn) {
                console.error('Submit button not found in form');
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Submit button not found.', 'error');
                } else {
                    alert('Submit button not found.');
                }
                return;
            }

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

            if (typeof window.showNotification === 'function') {
                window.showNotification('Updating inventory settings...', 'info');
            }

            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response. Please check your controller.');
                    }
                    return response.json();
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Inventory updated successfully!',
                                'success');
                        } else {
                            alert('Success: ' + (data.message || 'Inventory updated successfully!'));
                        }

                        const modalElement = document.querySelector('.modal.show');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        if (data.errors) {
                            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                                'is-invalid'));
                            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                            let errorMsg = '';
                            for (const [field, errors] of Object.entries(data.errors)) {
                                errorMsg += errors[0] + '\n';
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    feedback.innerText = errors[0];
                                    input.parentNode.insertBefore(feedback, input.nextSibling);
                                }
                            }
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(errorMsg || data.message || 'Validation failed',
                                    'error');
                            } else {
                                alert('Validation Error: ' + errorMsg);
                            }
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Update failed', 'error');
                            } else {
                                alert('Error: ' + (data.message || 'Update failed'));
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    if (typeof window.showNotification === 'function') {
                        let errorMessage = 'Network error. Please try again.';
                        if (error.message.includes('non-JSON')) {
                            errorMessage = 'Server error. Please check your controller.';
                        }
                        window.showNotification(errorMessage, 'error');
                    } else {
                        alert('Error: ' + error.message);
                    }
                });
        };

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-update[data-inventory-id]');
            if (btn) {
                e.preventDefault();
                const id = btn.getAttribute('data-inventory-id');
                if (typeof window.submitEditForm === 'function') {
                    window.submitEditForm(id);
                }
            }
        });

        console.log('Modal edit script loaded successfully');
    </script>

    <!-- SIDEBAR TOGGLE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapse');

            if (!sidebar || !toggleBtn || !sidebarCollapseBtn) return;

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                const icon = toggleBtn.querySelector('i');
                if (sidebar.classList.contains('active')) {
                    icon.className = 'bi bi-chevron-right';
                } else {
                    icon.className = 'bi bi-chevron-left';
                }
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('active'));
            }

            toggleBtn.addEventListener('click', toggleSidebar);
            sidebarCollapseBtn.addEventListener('click', toggleSidebar);

            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('active');
                toggleBtn.querySelector('i').className = 'bi bi-chevron-right';
            }

            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.classList.contains('active')) {
                            sidebar.classList.add('active');
                            toggleBtn.querySelector('i').className = 'bi bi-chevron-right';
                            localStorage.setItem('sidebarCollapsed', 'true');
                        }
                    }
                });
            }

            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        if (sidebar.classList.contains('active')) {
                            sidebar.classList.remove('active');
                            toggleBtn.querySelector('i').className = 'bi bi-chevron-left';
                            localStorage.setItem('sidebarCollapsed', 'false');
                        }
                    } else {
                        if (!sidebar.classList.contains('active')) {
                            sidebar.classList.add('active');
                            toggleBtn.querySelector('i').className = 'bi bi-chevron-right';
                            localStorage.setItem('sidebarCollapsed', 'true');
                        }
                    }
                }, 250);
            });

            if (window.innerWidth <= 768 && !sidebar.classList.contains('active')) {
                sidebar.classList.add('active');
                toggleBtn.querySelector('i').className = 'bi bi-chevron-right';
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            console.log('✅ Sidebar toggle functionality initialized!');
        });
    </script>

    <!-- GLOBAL MOBILE HELPERS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = () => window.innerWidth <= 768;

            function prepareTables() {
                document.querySelectorAll('table').forEach(function(table) {
                    if (table.closest('.navbar, .sidebar')) return;

                    if (!table.closest('.table-responsive')) {
                        const wrap = document.createElement('div');
                        wrap.className = 'table-responsive';
                        table.parentNode.insertBefore(wrap, table);
                        wrap.appendChild(table);
                    }

                    const headRows = table.querySelectorAll('thead tr');
                    const headCells = headRows.length ? Array.from(headRows[headRows.length - 1].children) :
                        [];
                    const simpleHead = headRows.length === 1 && headCells.every(th => th.colSpan === 1);

                    if (simpleHead && headCells.length >= 6 && !table.classList.contains('no-stack')) {
                        table.classList.add('table-stack');
                    }

                    if (simpleHead && table.classList.contains('table-stack') && !table.classList.contains(
                            'no-stack')) {
                        const heads = headCells.map(th => th.textContent.replace(/\s+/g, ' ').trim());
                        table.querySelectorAll('tbody tr').forEach(function(tr) {
                            if (tr.children.length !== heads.length) return;
                            Array.from(tr.children).forEach(function(td, i) {
                                if (td.hasAttribute('data-label') || td.colSpan > 1) return;
                                const label = heads[i];
                                const imageOnly = td.querySelector('img') && !td.textContent
                                    .trim();

                                if (/^(image|photo|picture)$/i.test(label) || imageOnly) {
                                    td.classList.add('stack-media');
                                } else if (/action/i.test(label) && td.querySelector(
                                        '.btn, button')) {
                                    td.classList.add('stack-actions');
                                }
                                if (label) td.setAttribute('data-label', label);
                            });
                        });
                    }
                });
            }
            prepareTables();

            let pending = false;
            new MutationObserver(function() {
                if (pending) return;
                pending = true;
                requestAnimationFrame(function() {
                    pending = false;
                    prepareTables();
                });
            }).observe(document.body, {
                childList: true,
                subtree: true
            });

            const sidebar = document.getElementById('sidebar');
            const collapseBtn = document.getElementById('sidebarCollapse');
            if (sidebar) {
                const overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);

                const sync = function() {
                    document.body.classList.toggle('sidebar-open', isMobile() && !sidebar.classList
                        .contains('active'));
                };
                new MutationObserver(sync).observe(sidebar, {
                    attributes: true,
                    attributeFilter: ['class']
                });
                window.addEventListener('resize', sync);

                overlay.addEventListener('click', function() {
                    if (collapseBtn && !sidebar.classList.contains('active')) collapseBtn.click();
                });

                sidebar.querySelectorAll('a.nav-link').forEach(function(a) {
                    a.addEventListener('click', function() {
                        if (isMobile() && collapseBtn && !sidebar.classList.contains('active'))
                            collapseBtn.click();
                    });
                });

                sync();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>