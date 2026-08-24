<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Owner Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ========== ENSURE TAB CONTENT IS VISIBLE ========== */
.tab-pane {
    display: none !important;
}

.tab-pane.show.active {
    display: block !important;
}

#overview.show.active,
#analytics.show.active {
    display: block !important;
}
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f5f7fb;
        }

        /* ========== LOADING SCREEN STYLES ========== */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .loading-logo {
            background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            animation: pulseGlow 1.5s ease-in-out infinite;
            box-shadow: 0 0 30px rgba(13, 110, 253, 0.15);
            padding: 20px;
        }

        .loading-title {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            margin: 1.5rem auto 0;
            border: 3px solid rgba(13, 110, 253, 0.15);
            border-top-color: #0d6efd;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulseGlow {
            0%,
            100% {
                box-shadow: 0 0 20px rgba(13, 110, 253, 0.15);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 40px rgba(13, 110, 253, 0.3);
                transform: scale(1.03);
            }
        }

        /* ========== ENHANCED ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes gentlePulse {
            0%,
            100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        .animate-fade-left {
            animation: fadeInLeft 0.5s ease forwards;
            opacity: 0;
        }

        .animate-fade-right {
            animation: fadeInRight 0.5s ease forwards;
            opacity: 0;
        }

        .animate-scale {
            animation: scaleIn 0.4s ease forwards;
            opacity: 0;
        }

        .delay-1 {
            animation-delay: 0.05s;
        }
        .delay-2 {
            animation-delay: 0.1s;
        }
        .delay-3 {
            animation-delay: 0.15s;
        }
        .delay-4 {
            animation-delay: 0.2s;
        }
        .delay-5 {
            animation-delay: 0.25s;
        }
        .delay-6 {
            animation-delay: 0.3s;
        }
        .delay-7 {
            animation-delay: 0.35s;
        }
        .delay-8 {
            animation-delay: 0.4s;
        }
        .delay-9 {
            animation-delay: 0.45s;
        }
        .delay-10 {
            animation-delay: 0.5s;
        }
        .delay-11 {
            animation-delay: 0.55s;
        }
        .delay-12 {
            animation-delay: 0.6s;
        }
        .delay-13 {
            animation-delay: 0.65s;
        }
        .delay-14 {
            animation-delay: 0.7s;
        }
        .delay-15 {
            animation-delay: 0.75s;
        }
        .delay-16 {
            animation-delay: 0.8s;
        }

        /* Sidebar */
        .sidebar-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 56px;
            height: 56px;
            border-radius: 28px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            transform: scale(1.05);
        }

        .mobile-menu-toggle i {
            transition: transform 0.3s ease;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-card .card-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: none;
            padding: 1.25rem 1rem;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .sidebar-card .list-group-item {
            background: white;
            color: #4a5568;
            border: none;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            margin: 2px 8px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-card .list-group-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.08), transparent);
            transition: left 0.5s ease;
        }

        .sidebar-card .list-group-item:hover::before {
            left: 100%;
        }

        .sidebar-card .list-group-item:hover {
            background: #f1f8ff;
            color: #0d6efd;
            transform: translateX(5px);
        }

        .sidebar-card .list-group-item.active {
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
            border-left: 3px solid #0d6efd;
        }

        .sidebar-card .list-group-item i {
            width: 24px;
            color: #6c757d;
            transition: transform 0.2s ease;
        }

        .sidebar-card .list-group-item:hover i {
            transform: scale(1.1);
            color: #0d6efd;
        }

        .sidebar-card .list-group-item.active i,
        .sidebar-card .list-group-item:hover i {
            color: #0d6efd;
        }

        .sidebar-card .text-muted {
            color: #6c757d !important;
            font-size: 0.75rem;
            padding: 0.75rem 1.25rem 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .sidebar-card .bg-light {
            background: #f8f9fa !important;
            padding: 0.25rem 1.25rem;
            margin-top: 0.25rem;
        }

        .sidebar-card .ps-4 {
            padding-left: 2.5rem !important;
            border-left: 2px dashed #dee2e6;
            margin-left: 1rem;
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            background: white;
            overflow: hidden;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.12);
        }

        .stat-card .card-body {
            padding: 1.25rem;
        }

        .stat-card h5 {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }

        .stat-card h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d5e 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .stat-card:hover h2 {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            background-clip: text;
            -webkit-background-clip: text;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: rotate(5deg) scale(1.05);
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(231, 76, 60, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .low-stock-badge {
            animation: gentlePulse 2s infinite;
            display: inline-block;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: none;
            gap: 0.5rem;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            border: none;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-tabs .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: #0d6efd;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-tabs .nav-link:hover::before,
        .nav-tabs .nav-link.active::before {
            width: 60%;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }

        .nav-tabs .nav-link:hover {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.03);
            transform: translateY(-2px);
        }

        .tab-content {
            padding-top: 1.5rem;
        }

        /* Chart containers */
        .chart-container-small {
            max-width: 100%;
            height: 200px;
            margin: 0 auto;
        }

        .chart-container-medium {
            max-width: 100%;
            height: 250px;
            margin: 0 auto;
        }

        .chart-container-large {
            max-width: 100%;
            height: 300px;
            margin: 0 auto;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            min-height: 200px;
        }

        canvas {
            max-height: 100%;
            width: 100% !important;
            transition: all 0.3s ease;
        }

        canvas:hover {
            transform: scale(1.02);
        }

        .chart-fallback {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .analytics-card {
            height: 100%;
            transition: all 0.3s ease;
        }

        .analytics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 28px -12px rgba(0, 0, 0, 0.12);
        }

        .analytics-card .card-body {
            padding: 1rem;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 0.5rem 0.75rem;
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.03);
        }

        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 1s ease-in-out;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        /* Section Headers */
        .section-header {
            border-left: 4px solid #0d6efd;
            padding-left: 12px;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }

        .section-header h5 {
            font-weight: 700;
            color: #1e2a3e;
            margin-bottom: 0;
        }

        .section-header p {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        /* Filter controls */
        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 0;
        }

        .filter-controls select,
        .filter-controls input {
            border-radius: 10px;
            border: 1px solid #dee2e6;
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
            background: white;
            transition: all 0.2s ease;
        }

        .filter-controls select:focus,
        .filter-controls input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            outline: none;
        }

        .filter-controls label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0;
        }

        /* Auto-refresh indicator */
        .auto-refresh-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            color: #6c757d;
            padding: 0.2rem 0.6rem;
            background: rgba(13, 110, 253, 0.05);
            border-radius: 20px;
        }

        .auto-refresh-indicator .spinner-grow {
            width: 10px;
            height: 10px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .row>.col-md-3 {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                z-index: 1001;
                transition: left 0.3s ease;
                margin: 0;
                padding: 0;
            }

            .row>.col-md-3.sidebar-open {
                left: 0;
            }

            .sidebar-card {
                border-radius: 0;
                height: 100%;
                overflow-y: auto;
            }

            .sidebar-close-btn {
                display: block;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            .row>.col-md-9 {
                width: 100%;
            }

            .navbar .container {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .navbar-nav {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
            }

            .navbar-text {
                font-size: 0.8rem;
                margin-right: 0.5rem !important;
            }

            .btn-outline-light {
                padding: 0.25rem 0.75rem;
                font-size: 0.8rem;
            }

            .stat-card h2 {
                font-size: 1.2rem;
            }

            .stat-card h5 {
                font-size: 0.6rem;
            }

            .stat-icon {
                width: 35px;
                height: 35px;
            }

            .stat-icon i {
                font-size: 1rem !important;
            }

            .welcome-banner .col-md-8,
            .welcome-banner .col-md-4 {
                text-align: center !important;
            }

            .welcome-banner .text-md-end {
                text-align: center !important;
                margin-top: 0.75rem;
            }

            .welcome-banner h4 {
                font-size: 1rem;
            }

            .welcome-banner p {
                font-size: 0.8rem;
            }

            .nav-tabs {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }

            .nav-tabs .nav-link i {
                font-size: 0.8rem;
            }

            .chart-container-medium,
            .chart-container-small {
                height: 180px;
            }
            
            .chart-container-large {
                height: 220px;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table th,
            .table td {
                padding: 0.4rem;
                font-size: 0.7rem;
                white-space: nowrap;
            }
            
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-controls select,
            .filter-controls input {
                width: 100%;
            }

            .auto-refresh-indicator {
                font-size: 0.6rem;
                padding: 0.15rem 0.5rem;
            }

            .card-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.5rem;
            }
            
            /* ===== FIX FOR MOBILE OVERVIEW TAB ===== */
            .tab-pane {
                display: none !important;
            }
            
            .tab-pane.show.active {
                display: block !important;
            }
            
            #overview.show.active {
                display: block !important;
            }
            
            #analytics.show.active {
                display: block !important;
            }
            
            /* Stat cards mobile */
            .stat-card .card-body {
                padding: 0.75rem !important;
            }
            
            .stat-card h2 {
                font-size: 1.2rem !important;
            }
            
            .stat-card h5 {
                font-size: 0.55rem !important;
            }
            
            .stat-icon {
                width: 32px !important;
                height: 32px !important;
            }
            
            .stat-icon i {
                font-size: 0.9rem !important;
            }
            
            /* Fix grid spacing for overview */
            .tab-pane#overview .row.g-4 {
                margin-left: -0.25rem !important;
                margin-right: -0.25rem !important;
            }
            
            .tab-pane#overview .row.g-4>[class*="col-"] {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }
            
            .tab-pane#overview .row.g-4.mb-5 {
                margin-bottom: 1.5rem !important;
            }
            
            .tab-pane#overview .row.g-4.mb-4 {
                margin-bottom: 1rem !important;
            }
            
            /* Today's activity mobile fix */
            .tab-pane#overview .d-flex.justify-content-between.gap-2 {
                gap: 0.25rem !important;
            }
            
            .tab-pane#overview .fw-bold.fs-3 {
                font-size: 1.2rem !important;
            }
            
            .tab-pane#overview .bi.fs-5 {
                font-size: 1rem !important;
            }
            
            .tab-pane#overview small.text-muted {
                font-size: 0.55rem !important;
            }
            
            /* Business overview mobile */
            .tab-pane#overview .card-body ul {
                padding-left: 1.2rem !important;
            }
            
            .tab-pane#overview .card-body ul li {
                font-size: 0.75rem !important;
                margin-bottom: 0.25rem !important;
            }
            
            /* Section headers mobile */
            .section-header h5 {
                font-size: 0.85rem !important;
            }
            
            .section-header p {
                font-size: 0.65rem !important;
            }
        }

        @media (max-width: 480px) {
            .stat-card .card-body {
                padding: 0.5rem !important;
            }

            .stat-card h2 {
                font-size: 0.9rem !important;
            }
            
            .stat-card h5 {
                font-size: 0.45rem !important;
            }
            
            .stat-icon {
                width: 26px !important;
                height: 26px !important;
            }
            
            .stat-icon i {
                font-size: 0.7rem !important;
            }

            .welcome-banner .bg-white.bg-opacity-10 {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.7rem;
            }

            h1.h3 {
                font-size: 1.25rem;
            }

            .tab-pane#overview .fw-bold.fs-3 {
                font-size: 1rem !important;
            }
            
            .tab-pane#overview .bi.fs-5 {
                font-size: 0.8rem !important;
            }
            
            .tab-pane#overview small.text-muted {
                font-size: 0.45rem !important;
            }
            
            .tab-pane#overview .card-body ul li {
                font-size: 0.65rem !important;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }

            .sidebar-overlay {
                display: none !important;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        @media (max-width: 768px) {
            .list-group-item,
            .nav-link,
            .btn,
            .stat-card {
                cursor: pointer;
                -webkit-tap-highlight-color: rgba(13, 110, 253, 0.1);
            }
        }

        /* ========== EQUAL HEIGHT CARDS FIX ========== */
        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
        }

        .row.g-4>[class*="col-"] {
            display: flex;
            flex-direction: column;
        }

        .row.g-4 .stat-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .row.g-4 .stat-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .row.g-4 .stat-card .card-body>div:first-child {
            flex: 1;
        }

        .row.g-4 .badge {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .row.g-4 .badge {
                white-space: normal;
            }
        }

        /* Fix table truncation */
        .table-container {
            max-height: 400px;
            overflow: hidden;
        }

        .table-container table {
            margin-bottom: 0;
        }

        .card-body.p-0 .table-responsive {
            border-radius: 0 0 12px 12px;
        }
        /* ========== FIX FOR OVERVIEW TAB CONTENT ON MOBILE ========== */
@media (max-width: 768px) {
    /* Force all stat cards to be visible */
    .tab-pane#overview .stat-card {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .tab-pane#overview .stat-card .card-body {
        display: flex !important;
        flex-direction: column !important;
        padding: 0.75rem !important;
    }
    
    .tab-pane#overview .stat-card .card-body > div {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        width: 100% !important;
    }
    
    .tab-pane#overview .stat-card h5 {
        display: block !important;
        font-size: 0.55rem !important;
        margin-bottom: 0.25rem !important;
    }
    
    .tab-pane#overview .stat-card h2 {
        display: block !important;
        font-size: 1.2rem !important;
        margin-bottom: 0.1rem !important;
    }
    
    .tab-pane#overview .stat-card .small {
        display: block !important;
        font-size: 0.5rem !important;
    }
    
    .tab-pane#overview .stat-card .stat-icon {
        display: flex !important;
        width: 32px !important;
        height: 32px !important;
        flex-shrink: 0 !important;
    }
    
    .tab-pane#overview .stat-card .stat-icon i {
        font-size: 0.9rem !important;
    }
    
    /* Fix section headers */
    .tab-pane#overview .section-header {
        display: block !important;
    }
    
    .tab-pane#overview .section-header h5 {
        display: block !important;
        font-size: 0.85rem !important;
    }
    
    .tab-pane#overview .section-header p {
        display: block !important;
        font-size: 0.65rem !important;
    }
    
    /* Fix Today's Activity */
    .tab-pane#overview .stat-card.h-100 {
        display: flex !important;
    }
    
    .tab-pane#overview .d-flex.justify-content-between.gap-2 {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .tab-pane#overview .d-flex.justify-content-between.gap-2 .text-center {
        display: block !important;
        flex: 1 !important;
        min-width: 40px !important;
    }
    
    .tab-pane#overview .fw-bold.fs-3 {
        display: block !important;
        font-size: 1.2rem !important;
    }
    
    .tab-pane#overview .bi.fs-5 {
        display: inline-block !important;
        font-size: 1rem !important;
    }
    
    .tab-pane#overview small.text-muted {
        display: block !important;
        font-size: 0.55rem !important;
    }
    
    /* Fix Business Overview */
    .tab-pane#overview .card-body {
        display: block !important;
    }
    
    .tab-pane#overview .card-body p {
        display: block !important;
        font-size: 0.8rem !important;
    }
    
    .tab-pane#overview .card-body ul {
        display: block !important;
        padding-left: 1.2rem !important;
    }
    
    .tab-pane#overview .card-body ul li {
        display: block !important;
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* Fix row and column display */
    .tab-pane#overview .row.g-4 {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-left: -0.25rem !important;
        margin-right: -0.25rem !important;
    }
    
    .tab-pane#overview .row.g-4 > [class*="col-"] {
        display: flex !important;
        flex-direction: column !important;
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .tab-pane#overview .col-md-3.col-6 {
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
}
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="loading-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" style="width: 70px; height: auto;">
            </div>
            <div class="loading-title">VAPE EXPO</div>
            <div class="loading-spinner"></div>
        </div>
    </div>

    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="navbar navbar-expand-lg animate-fade-up"
        style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                    class="d-inline-block align-text-top me-2" onerror="this.style.display='none'">
                Vape Expo - Owner Panel
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white-50 me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Owner)
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="welcome-banner animate-fade-up delay-1">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-white mb-2 fw-bold">
                        <i class="bi bi-stars me-2 text-warning"></i>Welcome back, Carlo!
                    </h4>
                    <p class="text-white-50 mb-0">Here's what's happening with your business today.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-2">
                        <i class="bi bi-calendar3 text-white me-2"></i>
                        <span class="text-white">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3" id="sidebarWrapper">
                <div class="card sidebar-card">
                    <div class="card-header">
                        <span><i class="bi bi-grid me-2"></i> Owner Menu</span>
                        <button class="sidebar-close-btn" id="sidebarCloseBtn">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <div class="text-muted">MANAGEMENT</div>
                        @if (Route::has('admin.branch-admin.index'))
                            <a href="{{ route('admin.branch-admin.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-people me-2"></i> Branch Personnel
                                @php $branchAdminCount = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count(); @endphp
                                <span class="badge bg-info float-end">{{ $branchAdminCount }}</span>
                            </a>
                        @endif
                        @if (Route::has('admin.customers.index'))
                            <a href="{{ route('admin.customers.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-people-fill me-2"></i> Customers
                                @php $customerCount = \App\Models\User::where('role', 'customer')->count(); @endphp
                                <span class="badge bg-success float-end">{{ $customerCount }}</span>
                            </a>
                        @endif
                        @if (Route::has('admin.products.index'))
                            <a href="{{ route('admin.products.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-box me-2"></i> Products
                                @php $productCount = \App\Models\Product::count(); @endphp
                                <span class="badge bg-info float-end">{{ $productCount }}</span>
                            </a>
                        @endif
                        <div class="text-muted">INVENTORY</div>
                        @if (Route::has('admin.inventory.index'))
                            <a href="{{ route('admin.inventory.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-clipboard-data me-2"></i> Inventory Overview
                                @php $totalItems = \App\Models\BranchInventory::count(); @endphp
                                <span class="badge bg-secondary float-end">{{ $totalItems }}</span>
                            </a>
                        @endif
                        <div class="bg-light"><small class="text-muted"><i class="bi bi-arrow-right-short me-1"></i>
                                QUICK LINKS</small></div>
                        @if (Route::has('admin.inventory.low-stock'))
                            <a href="{{ route('admin.inventory.low-stock') }}"
                                class="list-group-item list-group-item-action ps-4">
                                <i class="bi bi-exclamation-triangle me-2 text-warning"></i> Low Stock Alert
                                @php $lowStockCountNav = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count(); @endphp
                                @if ($lowStockCountNav > 0)
                                    <span class="badge bg-danger rounded-pill float-end">{{ $lowStockCountNav }}</span>
                                @endif
                            </a>
                        @endif
                        @if (Route::has('admin.inventory.transfers'))
                            <a href="{{ route('admin.inventory.transfers') }}"
                                class="list-group-item list-group-item-action ps-4">
                                <i class="bi bi-arrow-left-right me-2 text-info"></i> Stock Transfers
                                @php $pendingTransfersNav = \App\Models\StockTransfer::where('status', 'pending')->count(); @endphp
                                @if ($pendingTransfersNav > 0)
                                    <span
                                        class="badge bg-warning rounded-pill float-end">{{ $pendingTransfersNav }}</span>
                                @endif
                            </a>
                        @endif
                        @if (Route::has('admin.inventory.stock-history'))
                            <a href="{{ route('admin.inventory.stock-history') }}"
                                class="list-group-item list-group-item-action ps-4">
                                <i class="bi bi-clock-history me-2 text-secondary"></i> Stock History
                            </a>
                        @endif
                        <div class="text-muted">WAREHOUSE</div>
                        @if (Route::has('admin.warehouse.index'))
                            <a href="{{ route('admin.warehouse.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-house-door me-2"></i> Warehouse Stock
                            </a>
                        @endif
                        <div class="text-muted">DELIVERIES</div>
                        

                        @if (Route::has('admin.driver-shifts.index'))
                            <a href="{{ route('admin.driver-shifts.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-check me-2 text-primary"></i> Driver Shifts
                            </a>
                        @endif
                        <div class="text-muted">TRANSACTIONS</div>
                        @if (Route::has('admin.pos.history'))
                            <a href="{{ route('admin.pos.history') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-clock-history me-2 text-secondary"></i> POS Sales History
                            </a>
                        @endif
                        @if (Route::has('admin.online-orders.index'))
                            <a href="{{ route('admin.online-orders.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-cart me-2 text-primary"></i> Online Orders History
                            </a>
                        @endif
                        @if (Route::has('admin.deliveries.index'))
                            <a href="{{ route('admin.deliveries.index') }}"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-truck me-2 text-primary"></i> Delivery History 
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <h1 class="h3 fw-semibold animate-fade-right delay-1">Owner Dashboard</h1>
                    <div class="auto-refresh-indicator animate-fade-right delay-2">
                        <span class="spinner-grow spinner-grow-sm text-primary" role="status"></span>
                        <span>Auto-refresh: <span id="refreshCountdown">5</span>s</span>
                        <button id="toggleAutoRefresh" class="btn btn-sm btn-outline-secondary ms-1" style="padding: 0.1rem 0.4rem; font-size: 0.65rem;">
                            <i class="bi bi-pause-circle"></i>
                        </button>
                    </div>
                </div>

                @php
                    // ========== EXISTING VARIABLES ==========
                    $totalInventoryItems = \App\Models\BranchInventory::count();
                    $lowStockCount = \App\Models\BranchInventory::whereColumn(
                        'quantity',
                        '<=',
                        'low_stock_threshold',
                    )->count();
                    $outOfStockCount = \App\Models\BranchInventory::where('quantity', '<=', 0)->count();
                    $pendingTransfers = \App\Models\StockTransfer::where('status', 'pending')->count();
                    $totalProducts = \App\Models\Product::count();
                    $totalStaff = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count();
                    $totalCustomers = \App\Models\User::where('role', 'customer')->count();
                    $totalStockValue = \App\Models\BranchInventory::with('product')
                        ->get()
                        ->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));

                    $expiringSoon = $expiringSoon ?? collect();
                    $onlineOrderStatus = $onlineOrderStatus ?? [];
                    $repeatCustomerRate = $repeatCustomerRate ?? 0;
                    $deliveryVsPickup = $deliveryVsPickup ?? ['delivery_sales' => 0, 'pickup_sales' => 0];
                    $fastMovingProducts = $fastMovingProducts ?? collect();

                    // ========== WAREHOUSE STOCK ==========
                    $totalWarehouseStock = 0;
                    if (class_exists('\App\Models\WarehouseInventory') && \App\Models\WarehouseInventory::count() > 0) {
                        $totalWarehouseStock = \App\Models\WarehouseInventory::sum('quantity') ?? 0;
                    }

                    // ========== POS ORDERS ==========
                    $totalPosOrders = 0;
                    $posCompleted = 0;
                    $posCancelled = 0;

                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $totalPosOrders = \App\Models\Order::where('order_number', 'LIKE', 'POS-%')->count();
                        $posCompleted = \App\Models\Order::where('order_number', 'LIKE', 'POS-%')
                            ->where('status', 'completed')
                            ->count();
                        $posCancelled = \App\Models\Order::where('order_number', 'LIKE', 'POS-%')
                            ->where('status', 'cancelled')
                            ->count();
                    }

                    // ========== ONLINE ORDERS ==========
                    $totalOnlineOrders = 0;
                    $onlinePending = 0;
                    $onlineConfirmed = 0;
                    $onlineProcessing = 0;
                    $onlineReady = 0;
                    $onlineOutForDelivery = 0;
                    $onlineDelivered = 0;
                    $onlineCancelled = 0;

                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $totalOnlineOrders = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')->count();
                        $onlinePending = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'pending')
                            ->count();
                        $onlineConfirmed = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'confirmed')
                            ->count();
                        $onlineProcessing = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'processing')
                            ->count();
                        $onlineReady = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'ready')
                            ->count();
                        $onlineOutForDelivery = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'out_for_delivery')
                            ->count();
                        $onlineDelivered = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'delivered')
                            ->count();
                        $onlineCancelled = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->where('order_status', 'cancelled')
                            ->count();
                    }

                    // ========== DELIVERIES ==========
                    $totalDeliveries = 0;
                    $pendingDeliveries = 0;
                    $inTransitDeliveries = 0;
                    $outForDeliveryDeliveries = 0;
                    $deliveredDeliveries = 0;
                    $deliveredToday = 0;

                    if (class_exists('\App\Models\Delivery') && \App\Models\Delivery::count() > 0) {
                        $totalDeliveries = \App\Models\Delivery::count();
                        $pendingDeliveries = \App\Models\Delivery::where('status', 'pending')->count();
                        $inTransitDeliveries = \App\Models\Delivery::where('status', 'in_transit')->count();
                        $outForDeliveryDeliveries = \App\Models\Delivery::where('status', 'out_for_delivery')->count();
                        $deliveredDeliveries = \App\Models\Delivery::where('status', 'delivered')->count();
                        $deliveredToday = \App\Models\Delivery::whereDate('created_at', today())->count();
                    }

                    $deliverySuccessRate = 0;
                    if ($totalDeliveries > 0) {
                        $deliverySuccessRate = round(($deliveredDeliveries / $totalDeliveries) * 100);
                    }

                    $activeDeliveries = $inTransitDeliveries + $outForDeliveryDeliveries;

                    // ========== DRIVER SHIFTS ==========
                    $totalShiftsToday = 0;
                    $activeDriversToday = 0;
                    $pendingShifts = 0;
                    $completedShifts = 0;

                    if (class_exists('\App\Models\DriverShift') && \App\Models\DriverShift::count() > 0) {
                        $totalShiftsToday = \App\Models\DriverShift::whereDate('shift_date', today())->count();
                        $activeDriversToday = \App\Models\DriverShift::where('status', 'active')
                            ->whereDate('shift_date', today())
                            ->count();
                        $pendingShifts = \App\Models\DriverShift::where('status', 'pending')
                            ->whereDate('shift_date', today())
                            ->count();
                        $completedShifts = \App\Models\DriverShift::where('status', 'completed')
                            ->whereDate('shift_date', today())
                            ->count();
                    }

                    // ========== TODAY'S ACTIVITY ==========
                    $ordersToday = 0;
                    $posToday = 0;
                    $onlineToday = 0;

                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $ordersToday = \App\Models\Order::whereDate('created_at', today())->count();
                        $posToday = \App\Models\Order::where('order_number', 'LIKE', 'POS-%')
                            ->whereDate('created_at', today())
                            ->count();
                        $onlineToday = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->whereDate('created_at', today())
                            ->count();
                    }

                    $totalAllOrders = $totalPosOrders + $totalOnlineOrders;

                    // ========== ACTIVE DRIVERS LIST ==========
                    $activeDriversList = [];
                    if (class_exists('\App\Models\DriverShift') && \App\Models\DriverShift::count() > 0) {
                        $activeDriversList = \App\Models\DriverShift::with('driver')
                            ->where('status', 'active')
                            ->whereDate('shift_date', today())
                            ->get()
                            ->map(function ($shift) {
                                return $shift->driver->name ?? 'Unknown Driver';
                            })
                            ->toArray();
                    }

                    // ========== ONLINE ORDER STATUS FOR CHART ==========
                    $onlineOrderStatus = [];
                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $statusCounts = \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')
                            ->select('order_status', \DB::raw('count(*) as count'))
                            ->groupBy('order_status')
                            ->get();

                        foreach ($statusCounts as $statusCount) {
                            if ($statusCount->order_status) {
                                $onlineOrderStatus[$statusCount->order_status] = $statusCount->count;
                            }
                        }
                    }

                    // ========== DELIVERY VS POS SALES ==========
                    $deliveryVsPickup = [
                        'delivery_sales' => 0,
                        'pickup_sales' => 0,
                    ];

                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $deliveryVsPickup['delivery_sales'] =
                            \App\Models\Order::where('order_number', 'LIKE', 'ORD-%')->sum('total_amount') ?? 0;
                        $deliveryVsPickup['pickup_sales'] =
                            \App\Models\Order::where('order_number', 'LIKE', 'POS-%')->sum('total_amount') ?? 0;
                    }

                    // ========== REPEAT CUSTOMER RATE ==========
                    $repeatCustomerRate = 0;
                    if (class_exists('\App\Models\Order') && \App\Models\Order::count() > 0) {
                        $totalCustomersCount = \App\Models\User::where('role', 'customer')->count();
                        $repeatCustomers = \App\Models\Order::select('user_id')
                            ->whereNotNull('user_id')
                            ->groupBy('user_id')
                            ->havingRaw('COUNT(*) > 1')
                            ->count();

                        if ($totalCustomersCount > 0) {
                            $repeatCustomerRate = round(($repeatCustomers / $totalCustomersCount) * 100);
                        }
                    }
                @endphp

                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics"
                            type="button" role="tab">
                            <i class="bi bi-graph-up me-2"></i> Analytics
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                            type="button" role="tab">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard Overview
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- ANALYTICS TAB - NOW FIRST -->
                    <div class="tab-pane fade show active" id="analytics" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-graph-up me-2 text-primary"></i> Business Analytics
                            </h3>
                        </div>

                        <!-- ========== MONTHLY ORDERS LINE GRAPH ========== -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <div class="card analytics-card animate-fade-up delay-1">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <i class="bi bi-calendar-month me-2 text-primary"></i> Monthly Orders Trend
                                        </div>
                                        <div class="filter-controls mb-0">
                                            <label for="yearSelect" class="me-2">Year:</label>
                                            <select id="yearSelect" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                                @php
                                                    $currentYear = date('Y');
                                                    $startYear = 2020;
                                                @endphp
                                                @for ($y = $currentYear; $y >= $startYear; $y--)
                                                    <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container-large">
                                            <canvas id="monthlyOrdersChart"></canvas>
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-primary me-2">POS Orders</span>
                                            <span class="badge bg-success me-2">Online Orders</span>
                                            <span class="badge bg-warning text-dark">Total Orders</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ========== SALES COMPARISON BAR GRAPH ========== -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <div class="card analytics-card animate-fade-up delay-2">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <i class="bi bi-bar-chart me-2 text-success"></i> Sales Comparison
                                        </div>
                                        <div class="filter-controls mb-0">
                                            <label for="compareType" class="me-2">Compare by:</label>
                                            <select id="compareType" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                                <option value="brand">Brand</option>
                                                <option value="category">Category</option>
                                                <option value="product">Product</option>
                                            </select>
                                            <label for="salesRange" class="ms-2 me-2">Range:</label>
                                            <select id="salesRange" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                                <option value="today">Today</option>
                                                <option value="week" selected>This Week</option>
                                                <option value="month">This Month</option>
                                                <option value="last_month">Last Month</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                            <div id="customDateRange" style="display: none; gap: 0.5rem; align-items: center;" class="d-inline-flex ms-2">
                                                <input type="date" id="startDate" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                                <span class="text-muted">to</span>
                                                <input type="date" id="endDate" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container-large">
                                            <canvas id="compareChart"></canvas>
                                        </div>
                                        <div class="text-center mt-2 text-muted small">
                                            <i class="bi bi-info-circle"></i> Comparing sales by <span id="compareTypeLabel">Brand</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expiring Soon -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <div class="card analytics-card animate-fade-up delay-3">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-calendar-exclamation me-2 text-danger"></i> Expiring Soon (next
                                        30 days)
                                        @if ($expiringSoon->count() > 0)
                                            <span class="badge bg-danger float-end">{{ $expiringSoon->count() }}
                                                items</span>
                                        @endif
                                    </div>
                                    <div class="card-body p-0">
                                        @if ($expiringSoon->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Product</th>
                                                            <th>Expiration Date</th>
                                                            <th>Days Left</th>
                                                            <th>Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($expiringSoon as $item)
                                                            <tr>
                                                                <td>{{ $item->branch->name ?? 'N/A' }}</td>
                                                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') }}
                                                                </td>
                                                                <td>
                                                                    @php $daysLeft = \Carbon\Carbon::now()->diffInDays($item->expiration_date, false); @endphp
                                                                    <span
                                                                        class="badge {{ $daysLeft <= 7 ? 'bg-danger' : ($daysLeft <= 14 ? 'bg-warning' : 'bg-secondary') }}">
                                                                        {{ max(0, $daysLeft) }} days
                                                                    </span>
                                                                </td>
                                                                <td>{{ number_format($item->quantity) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-success">
                                                <i class="bi bi-check-circle fs-2"></i>
                                                <p class="mb-0">No products expiring soon</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row g-4 mb-4">
                            <!-- Online Order Status -->
                            <div class="col-md-5">
                                <div class="card analytics-card h-100 animate-fade-up delay-4">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-cart-check me-2 text-primary"></i> Online Order Status
                                        <span class="badge bg-secondary float-end">{{ array_sum($onlineOrderStatus) }}
                                            total</span>
                                    </div>
                                    <div class="card-body text-center">
                                        @if (count($onlineOrderStatus) > 0)
                                            <div class="chart-container-medium">
                                                <canvas id="orderStatusChart"></canvas>
                                            </div>
                                            <div class="row mt-3 text-center small">
                                                @foreach ($onlineOrderStatus as $status => $count)
                                                    <div class="col-4 col-md-3 mb-2">
                                                        @php
                                                            $badgeClass = match ($status) {
                                                                'pending' => 'warning',
                                                                'confirmed' => 'info',
                                                                'processing' => 'primary',
                                                                'ready' => 'success',
                                                                'out_for_delivery' => 'dark',
                                                                'delivered' => 'secondary',
                                                                'cancelled' => 'danger',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $badgeClass }} status-badge">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                                                        <div class="fw-bold mt-1">{{ $count }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="chart-fallback">
                                                <p class="text-muted">No online orders data yet</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery vs POS -->
                            <div class="col-md-3">
                                <div class="card analytics-card h-100 animate-fade-up delay-5">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-truck me-2 text-primary"></i> Delivery vs POS
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $hasSalesData =
                                                ($deliveryVsPickup['delivery_sales'] ?? 0) > 0 ||
                                                ($deliveryVsPickup['pickup_sales'] ?? 0) > 0;
                                        @endphp
                                        @if ($hasSalesData)
                                            <div class="chart-container-small">
                                                <canvas id="deliveryVsPickupChart"></canvas>
                                            </div>
                                            <div class="row text-center mt-3">
                                                <div class="col-6">
                                                    <span class="badge bg-primary status-badge">Delivery</span>
                                                    <h5 class="mb-0 mt-1">
                                                        ₱{{ number_format($deliveryVsPickup['delivery_sales'] ?? 0, 2) }}
                                                    </h5>
                                                </div>
                                                <div class="col-6">
                                                    <span class="badge bg-success status-badge">POS</span>
                                                    <h5 class="mb-0 mt-1">
                                                        ₱{{ number_format($deliveryVsPickup['pickup_sales'] ?? 0, 2) }}
                                                    </h5>
                                                </div>
                                            </div>
                                        @else
                                            <div class="chart-fallback">
                                                <p class="text-muted">No sales data yet</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat Customer Rate -->
                            <div class="col-md-4">
                                <div class="card analytics-card h-100 text-center animate-fade-up delay-6">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                                        <h5 class="mt-2">Repeat Customer Rate</h5>
                                        <h2 class="mb-0">{{ $repeatCustomerRate }}%</h2>
                                        <small class="text-muted">of customers ordered more than once</small>
                                        <div class="progress mt-3" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $repeatCustomerRate }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fastest Moving Products -->
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="card analytics-card animate-fade-up delay-7">
                                    <div class="card-header bg-white">
                                        <i class="bi bi-lightning-charge me-2 text-warning"></i> Fastest Moving
                                        Products
                                    </div>
                                    <div class="card-body p-0">
                                        @if ($fastMovingProducts->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Units Sold</th>
                                                            <th>Revenue</th>
                                                            <th class="text-end">Rank</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($fastMovingProducts as $index => $product)
                                                            <tr>
                                                                <td><strong>{{ $product->name }}</strong></td>
                                                                <td><span
                                                                        class="badge bg-primary">{{ number_format($product->total_sold) }}</span>
                                                                </td>
                                                                <td>₱{{ number_format($product->total_sold * ($product->price ?? 350), 2) }}
                                                                </td>
                                                                <td class="text-end"><span
                                                                        class="badge bg-secondary rounded-pill">#{{ $index + 1 }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="bi bi-box-seam fs-2"></i>
                                                <p>No product sales data yet</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF ANALYTICS TAB-PANE -->

                    <!-- OVERVIEW TAB - NOW SECOND -->
                    <div class="tab-pane fade" id="overview" role="tabpanel">

                        <!-- ==================== SECTION 1: STOCKS ==================== -->
                        <div class="section-header">
                            <h5><i class="bi bi-box-seam me-2 text-primary"></i>Stocks & Inventory</h5>
                            <p>Warehouse stock, branch inventory, low stock alerts, and stock value</p>
                        </div>
                        <div class="row g-4 mb-5">
                            <div class="col-md-3 col-6 animate-scale delay-2">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>TOTAL ITEMS (BRANCHES)</h5>
                                                <h2>{{ $totalInventoryItems }}</h2>
                                                <span class="small text-muted">Across all branches</span>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-database fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-3">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>WAREHOUSE STOCK</h5>
                                                <h2>{{ number_format($totalWarehouseStock) }}</h2>
                                                <div class="small text-muted">Total units in warehouse</div>
                                                <a href="{{ route('admin.warehouse.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">View
                                                    Stock →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-house-door fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-4">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>LOW STOCK ALERTS</h5>
                                                <h2 class="{{ $lowStockCount > 0 ? 'text-danger' : '' }}">
                                                    {{ $lowStockCount }}</h2>
                                                @if ($lowStockCount > 0)
                                                    <a href="{{ route('admin.inventory.low-stock') }}"
                                                        class="small text-danger low-stock-badge text-decoration-none">View
                                                        Alerts →</a>
                                                @else
                                                    <span class="small text-muted">All good</span>
                                                @endif
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(253,126,20,0.1); color:#fd7e14;">
                                                <i class="bi bi-exclamation-triangle fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-5">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>STOCK VALUE</h5>
                                                <h2>₱{{ number_format($totalStockValue, 0) }}</h2>
                                                <span class="small text-muted">Total inventory value</span>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(25,135,84,0.1); color:#198754;">
                                                <i class="fs-4">₱</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTION 2: ORDERS ==================== -->
                        <div class="section-header">
                            <h5><i class="bi bi-cart-check me-2 text-primary"></i>Orders & Deliveries</h5>
                            <p>POS orders, online orders, delivery tracking, and driver shifts</p>
                        </div>
                        <div class="row g-4 mb-5">
                            <div class="col-md-3 col-6 animate-scale delay-6">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>POS ORDERS</h5>
                                                <h2>{{ $totalPosOrders }}</h2>
                                                <div class="small text-muted">
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success">{{ $posCompleted }}
                                                        completed</span>
                                                </div>
                                                <a href="{{ route('admin.pos.history') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">View
                                                    POS →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(25,135,84,0.1); color:#198754;">
                                                <i class="bi bi-shop fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-7">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>ONLINE ORDERS</h5>
                                                <h2>{{ $totalOnlineOrders }}</h2>
                                                <div class="small text-muted">
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning">{{ $onlinePending }}
                                                        pending</span>
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success ms-1">{{ $onlineDelivered }}
                                                        delivered</span>
                                                </div>
                                                <a href="{{ route('admin.online-orders.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">View
                                                    Orders →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-cart fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-8">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>DELIVERIES</h5>
                                                <h2>{{ $totalDeliveries }}</h2>
                                                <div class="small text-muted">
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning">{{ $pendingDeliveries }}
                                                        pending</span>
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info ms-1">{{ $inTransitDeliveries }}
                                                        in transit</span>
                                                </div>
                                                <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $deliverySuccessRate }}%;"></div>
                                                </div>
                                                <div class="small text-muted mt-1">Success rate:
                                                    {{ $deliverySuccessRate }}%</div>
                                                <a href="{{ route('admin.deliveries.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">View
                                                    All →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-truck fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-9">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>DRIVER SHIFTS</h5>
                                                @if (count($activeDriversList) > 0)
                                                    <div class="fw-bold small">
                                                        {{ implode(', ', array_slice($activeDriversList, 0, 2)) }}
                                                        @if (count($activeDriversList) > 2)
                                                            +{{ count($activeDriversList) - 2 }} more
                                                        @endif
                                                    </div>
                                                    <div class="small text-success">active now</div>
                                                @else
                                                    <div class="small text-muted">No active drivers</div>
                                                @endif
                                                <a href="{{ route('admin.driver-shifts.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">Manage
                                                    →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(25,135,84,0.1); color:#198754;">
                                                <i class="bi bi-person-badge fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTION 3: OTHERS ==================== -->
                        <div class="section-header">
                            <h5><i class="bi bi-building me-2 text-primary"></i>Others</h5>
                            <p>Business management, personnel, customers, and stock transfers</p>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-3 col-6 animate-scale delay-10">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>BRANCH PERSONNEL</h5>
                                                <h2>{{ $totalStaff }}</h2>
                                                <a href="{{ route('admin.branch-admin.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">Manage
                                                    →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-people fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-11">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>PRODUCTS</h5>
                                                <h2>{{ $totalProducts }}</h2>
                                                <a href="{{ route('admin.products.index') }}"
                                                    class="small text-decoration-none text-info">Manage →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd;">
                                                <i class="bi bi-box-seam fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-12">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>CUSTOMERS</h5>
                                                <h2>{{ $totalCustomers }}</h2>
                                                <a href="{{ route('admin.customers.index') }}"
                                                    class="small text-decoration-none mt-2 d-inline-block text-primary">Manage
                                                    →</a>
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(25,135,84,0.1); color:#198754;">
                                                <i class="bi bi-person fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 animate-scale delay-13">
                                <div class="card stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>STOCK TRANSFERS</h5>
                                                <h2 class="{{ $pendingTransfers > 0 ? 'text-dark' : '' }}">
                                                    {{ $pendingTransfers }}</h2>
                                                @if ($pendingTransfers > 0)
                                                    <a href="{{ route('admin.inventory.transfers') }}"
                                                        class="small text-warning text-decoration-none">View Pending
                                                        →</a>
                                                @else
                                                    <span class="small text-muted">No pending transfers</span>
                                                @endif
                                            </div>
                                            <div class="stat-icon"
                                                style="background: rgba(255,193,7,0.1); color:#ffc107;">
                                                <i class="bi bi-arrow-left-right fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Activity Card -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6 animate-scale delay-14">
                                <div class="card stat-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h5>TODAY'S ACTIVITY</h5>
                                                <div class="d-flex justify-content-between gap-2 mt-3 flex-wrap">
                                                    <div class="text-center" style="flex: 1;">
                                                        <i class="bi bi-cart text-primary fs-5"></i>
                                                        <div class="fw-bold fs-3 mb-0">{{ $ordersToday }}</div>
                                                        <small class="text-muted">Orders</small>
                                                    </div>
                                                    <div class="text-center" style="flex: 1;">
                                                        <i class="bi bi-shop text-success fs-5"></i>
                                                        <div class="fw-bold fs-3 mb-0">{{ $posToday }}</div>
                                                        <small class="text-muted">POS</small>
                                                    </div>
                                                    <div class="text-center" style="flex: 1;">
                                                        <i class="bi bi-globe text-info fs-5"></i>
                                                        <div class="fw-bold fs-3 mb-0">{{ $onlineToday }}</div>
                                                        <small class="text-muted">Online</small>
                                                    </div>
                                                    <div class="text-center" style="flex: 1;">
                                                        <i class="bi bi-truck text-warning fs-5"></i>
                                                        <div class="fw-bold fs-3 mb-0">{{ $deliveredToday }}</div>
                                                        <small class="text-muted">Deliveries</small>
                                                    </div>
                                                </div>
                                                <div class="small text-muted mt-3 text-center">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    {{ now()->format('l, F j, Y') }}
                                                </div>
                                            </div>
                                            <div class="stat-icon ms-3"
                                                style="background: rgba(13,110,253,0.1); color:#0d6efd; min-width: 45px;">
                                                <i class="bi bi-calendar-check fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Overview Card -->
                        <div class="card animate-fade-up delay-16">
                            <div class="card-header bg-white"><i class="bi bi-info-circle me-2 text-primary"></i>
                                Business Overview</div>
                            <div class="card-body">
                                <p>Welcome to the Vape Expo Owner Panel, <strong>Carlo Caranto</strong>. From here you
                                    can manage:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li><strong>5 Branches</strong> across Calamba</li>
                                            <li><strong>Branch Personnel</strong> - {{ $totalStaff }} staff members
                                            </li>
                                            <li><strong>Product Catalog</strong> - {{ $totalProducts }} products</li>
                                            <li><strong>Warehouse Stock</strong> -
                                                {{ number_format($totalWarehouseStock) }} units</li>
                                            <li><strong>Total Orders</strong> - {{ $totalAllOrders }}
                                                ({{ $totalPosOrders }} POS + {{ $totalOnlineOrders }} Online)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li><strong>Active Deliveries</strong> - {{ $activeDeliveries }} in
                                                progress</li>
                                            <li><strong>Active Drivers Today</strong> - {{ $activeDriversToday }}
                                                drivers on duty</li>
                                            <li><strong>Low Stock Alerts</strong> - {{ $lowStockCount }} items need
                                                attention</li>
                                            <li><strong>Pending Transfers</strong> - {{ $pendingTransfers }} requests
                                            </li>
                                            <li><strong>Delivery Success Rate</strong> - {{ $deliverySuccessRate }}%
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF OVERVIEW TAB-PANE -->

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <script>
                        // Loading screen hide after page load
                        window.addEventListener('load', function() {
                            const loadingScreen = document.getElementById('loadingScreen');
                            if (loadingScreen) {
                                setTimeout(function() {
                                    loadingScreen.style.opacity = '0';
                                    setTimeout(function() {
                                        loadingScreen.style.display = 'none';
                                    }, 600);
                                }, 800);
                            }
                        });

                        // Mobile sidebar toggle functionality
                        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
                        const sidebarWrapper = document.getElementById('sidebarWrapper');
                        const sidebarOverlay = document.getElementById('sidebarOverlay');
                        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

                        function openSidebar() {
                            if (sidebarWrapper) {
                                sidebarWrapper.classList.add('sidebar-open');
                                sidebarOverlay.classList.add('active');
                                document.body.style.overflow = 'hidden';
                                if (mobileMenuToggle) {
                                    mobileMenuToggle.querySelector('i').classList.remove('bi-list');
                                    mobileMenuToggle.querySelector('i').classList.add('bi-x');
                                }
                            }
                        }

                        function closeSidebar() {
                            if (sidebarWrapper) {
                                sidebarWrapper.classList.remove('sidebar-open');
                                sidebarOverlay.classList.remove('active');
                                document.body.style.overflow = '';
                                if (mobileMenuToggle) {
                                    mobileMenuToggle.querySelector('i').classList.remove('bi-x');
                                    mobileMenuToggle.querySelector('i').classList.add('bi-list');
                                }
                            }
                        }

                        if (mobileMenuToggle) {
                            mobileMenuToggle.addEventListener('click', function(e) {
                                e.stopPropagation();
                                if (sidebarWrapper && sidebarWrapper.classList.contains('sidebar-open')) {
                                    closeSidebar();
                                } else {
                                    openSidebar();
                                }
                            });
                        }

                        if (sidebarCloseBtn) {
                            sidebarCloseBtn.addEventListener('click', closeSidebar);
                        }

                        if (sidebarOverlay) {
                            sidebarOverlay.addEventListener('click', closeSidebar);
                        }

                        const sidebarLinks = document.querySelectorAll('.sidebar-card .list-group-item');
                        sidebarLinks.forEach(link => {
                            link.addEventListener('click', function() {
                                if (window.innerWidth <= 768) {
                                    closeSidebar();
                                }
                            });
                        });

                        window.addEventListener('resize', function() {
                            if (window.innerWidth > 768) {
                                if (sidebarWrapper) {
                                    sidebarWrapper.classList.remove('sidebar-open');
                                }
                                if (sidebarOverlay) {
                                    sidebarOverlay.classList.remove('active');
                                }
                                document.body.style.overflow = '';
                            }
                        });

                        // ========== CHART VARIABLES ==========
                        let monthlyOrdersChart = null;
                        let compareChart = null;
                        let orderStatusChart = null;
                        let deliveryVsPickupChart = null;

                        // ========== AUTO-REFRESH CONTROLS ==========
                        let refreshInterval = null;
                        let countdownInterval = null;
                        let countdown = 5;
                        let isAutoRefreshEnabled = true;

                        function startAutoRefresh() {
                            if (refreshInterval) {
                                clearInterval(refreshInterval);
                            }
                            if (countdownInterval) {
                                clearInterval(countdownInterval);
                            }
                            
                            isAutoRefreshEnabled = true;
                            countdown = 5;
                            updateCountdownDisplay();
                            
                            countdownInterval = setInterval(function() {
                                countdown--;
                                if (countdown <= 0) {
                                    countdown = 5;
                                    refreshAllCharts();
                                }
                                updateCountdownDisplay();
                            }, 1000);
                            
                            const toggleBtn = document.getElementById('toggleAutoRefresh');
                            if (toggleBtn) {
                                toggleBtn.innerHTML = '<i class="bi bi-pause-circle"></i>';
                                toggleBtn.classList.remove('btn-outline-secondary');
                                toggleBtn.classList.add('btn-outline-danger');
                            }
                        }

                        function stopAutoRefresh() {
                            if (refreshInterval) {
                                clearInterval(refreshInterval);
                                refreshInterval = null;
                            }
                            if (countdownInterval) {
                                clearInterval(countdownInterval);
                                countdownInterval = null;
                            }
                            
                            isAutoRefreshEnabled = false;
                            
                            const toggleBtn = document.getElementById('toggleAutoRefresh');
                            if (toggleBtn) {
                                toggleBtn.innerHTML = '<i class="bi bi-play-circle"></i>';
                                toggleBtn.classList.remove('btn-outline-danger');
                                toggleBtn.classList.add('btn-outline-success');
                            }
                            
                            const countdownEl = document.getElementById('refreshCountdown');
                            if (countdownEl) {
                                countdownEl.textContent = '⏸';
                            }
                        }

                        function toggleAutoRefresh() {
                            if (isAutoRefreshEnabled) {
                                stopAutoRefresh();
                            } else {
                                startAutoRefresh();
                            }
                        }

                        function updateCountdownDisplay() {
                            const countdownEl = document.getElementById('refreshCountdown');
                            if (countdownEl) {
                                countdownEl.textContent = countdown;
                            }
                        }

                        function refreshAllCharts() {
                            const year = document.getElementById('yearSelect').value;
                            updateMonthlyChart(year);
                            updateCompareChart();
                            updateOrderStatusChart();
                            updateDeliveryVsPickupChart();
                        }

                        // ========== MONTHLY ORDERS CHART FUNCTIONS ==========
                        function fetchMonthlyOrders(year) {
                            return fetch(`/admin/analytics/monthly-orders?year=${year}`)
                                .then(response => response.json())
                                .catch(() => {
                                    return {
                                        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                        pos: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                        online: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                        total: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                                    };
                                });
                        }

                        function updateMonthlyChart(year) {
                            const ctx = document.getElementById('monthlyOrdersChart').getContext('2d');
                            
                            fetchMonthlyOrders(year).then(data => {
                                if (monthlyOrdersChart) {
                                    monthlyOrdersChart.destroy();
                                }

                                monthlyOrdersChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: data.months,
                                        datasets: [
                                            {
                                                label: 'POS Orders',
                                                data: data.pos,
                                                borderColor: '#0d6efd',
                                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                                tension: 0.4,
                                                fill: true
                                            },
                                            {
                                                label: 'Online Orders',
                                                data: data.online,
                                                borderColor: '#198754',
                                                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                                tension: 0.4,
                                                fill: true
                                            },
                                            {
                                                label: 'Total Orders',
                                                data: data.total,
                                                borderColor: '#ffc107',
                                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                                tension: 0.4,
                                                fill: true,
                                                borderDash: [5, 5]
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                                labels: {
                                                    boxWidth: 12,
                                                    font: { size: 11 }
                                                }
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(ctx) {
                                                        return ctx.dataset.label + ': ' + ctx.raw + ' orders';
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        }

                        // ========== COMPARISON CHART FUNCTIONS ==========
                        function fetchComparisonData(type, range, startDate, endDate) {
                            let url = `/admin/analytics/sales-comparison?type=${type}&range=${range}`;
                            if (range === 'custom' && startDate && endDate) {
                                url += `&start=${startDate}&end=${endDate}`;
                            }
                            return fetch(url)
                                .then(response => response.json())
                                .catch(() => {
                                    return {
                                        labels: ['Brand A', 'Brand B', 'Brand C', 'Brand D', 'Brand E'],
                                        data: [150, 230, 180, 90, 120],
                                        colors: ['#0d6efd', '#dc3545', '#198754', '#ffc107', '#6f42c1']
                                    };
                                });
                        }

                        function updateCompareChart() {
                            const type = document.getElementById('compareType').value;
                            const range = document.getElementById('salesRange').value;
                            const startDate = document.getElementById('startDate').value;
                            const endDate = document.getElementById('endDate').value;
                            
                            document.getElementById('compareTypeLabel').textContent = 
                                type.charAt(0).toUpperCase() + type.slice(1);
                            
                            const customRange = document.getElementById('customDateRange');
                            if (range === 'custom') {
                                customRange.style.display = 'inline-flex';
                            } else {
                                customRange.style.display = 'none';
                            }

                            const ctx = document.getElementById('compareChart').getContext('2d');
                            
                            fetchComparisonData(type, range, startDate, endDate).then(data => {
                                if (compareChart) {
                                    compareChart.destroy();
                                }

                                compareChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: data.labels,
                                        datasets: [{
                                            label: 'Sales (₱)',
                                            data: data.data,
                                            backgroundColor: data.colors || [
                                                '#0d6efd', '#dc3545', '#198754', '#ffc107', '#6f42c1',
                                                '#fd7e14', '#20c997', '#0dcaf0', '#d63384', '#6610f2'
                                            ],
                                            borderRadius: 6,
                                            borderColor: 'rgba(0,0,0,0.1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(ctx) {
                                                        return '₱' + ctx.raw.toFixed(2);
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return '₱' + value.toFixed(0);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        }

                        // ========== ORDER STATUS CHART ==========
                        function updateOrderStatusChart() {
                            const statusCanvas = document.getElementById('orderStatusChart');
                            if (!statusCanvas) return;
                            
                            fetch('/admin/analytics/order-status')
                                .then(response => response.json())
                                .catch(() => {
                                    return @json($onlineOrderStatus);
                                })
                                .then(data => {
                                    const labels = Object.keys(data);
                                    const values = Object.values(data);
                                    const colors = ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#6c757d', '#dc3545', '#20c997'];
                                    
                                    if (orderStatusChart) {
                                        orderStatusChart.destroy();
                                    }
                                    
                                    if (values.length > 0) {
                                        orderStatusChart = new Chart(statusCanvas, {
                                            type: 'pie',
                                            data: {
                                                labels: labels.map(l => l.replace(/_/g, ' ').toUpperCase()),
                                                datasets: [{
                                                    data: values,
                                                    backgroundColor: colors.slice(0, values.length)
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'right',
                                                        labels: {
                                                            boxWidth: 12,
                                                            font: { size: 11 }
                                                        }
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: (ctx) => `${ctx.label}: ${ctx.raw} orders`
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                });
                        }

                        // ========== DELIVERY VS PICKUP CHART ==========
                        function updateDeliveryVsPickupChart() {
                            const dpCanvas = document.getElementById('deliveryVsPickupChart');
                            if (!dpCanvas) return;
                            
                            fetch('/admin/analytics/delivery-vs-pickup')
                                .then(response => response.json())
                                .catch(() => {
                                    return {
                                        delivery: {{ $deliveryVsPickup['delivery_sales'] ?? 0 }},
                                        pickup: {{ $deliveryVsPickup['pickup_sales'] ?? 0 }}
                                    };
                                })
                                .then(data => {
                                    if (deliveryVsPickupChart) {
                                        deliveryVsPickupChart.destroy();
                                    }
                                    
                                    if (data.delivery > 0 || data.pickup > 0) {
                                        deliveryVsPickupChart = new Chart(dpCanvas, {
                                            type: 'doughnut',
                                            data: {
                                                labels: ['Delivery', 'POS (Walk-in)'],
                                                datasets: [{
                                                    data: [data.delivery, data.pickup],
                                                    backgroundColor: ['#0d6efd', '#198754']
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'bottom',
                                                        labels: {
                                                            boxWidth: 12,
                                                            font: { size: 11 }
                                                        }
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: (ctx) => `₱${ctx.raw.toFixed(2)}`
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                });
                        }

                        // ========== INITIALIZE ==========
                        document.addEventListener('DOMContentLoaded', function() {
                            const currentYear = new Date().getFullYear();
                            updateMonthlyChart(currentYear);
                            updateCompareChart();
                            updateOrderStatusChart();
                            updateDeliveryVsPickupChart();

                            document.getElementById('yearSelect').addEventListener('change', function() {
                                updateMonthlyChart(this.value);
                            });

                            document.getElementById('compareType').addEventListener('change', function() {
                                updateCompareChart();
                            });

                            document.getElementById('salesRange').addEventListener('change', function() {
                                const customRange = document.getElementById('customDateRange');
                                if (this.value === 'custom') {
                                    customRange.style.display = 'inline-flex';
                                } else {
                                    customRange.style.display = 'none';
                                    updateCompareChart();
                                }
                            });

                            document.getElementById('startDate').addEventListener('change', function() {
                                if (document.getElementById('salesRange').value === 'custom') {
                                    updateCompareChart();
                                }
                            });

                            document.getElementById('endDate').addEventListener('change', function() {
                                if (document.getElementById('salesRange').value === 'custom') {
                                    updateCompareChart();
                                }
                            });

                            const today = new Date();
                            const weekAgo = new Date(today);
                            weekAgo.setDate(today.getDate() - 7);
                            document.getElementById('startDate').value = weekAgo.toISOString().split('T')[0];
                            document.getElementById('endDate').value = today.toISOString().split('T')[0];

                            document.getElementById('toggleAutoRefresh').addEventListener('click', toggleAutoRefresh);
                            startAutoRefresh();

                            const progressBars = document.querySelectorAll('.progress-bar');
                            progressBars.forEach(bar => {
                                const width = bar.style.width;
                                bar.style.width = '0%';
                                setTimeout(() => {
                                    bar.style.width = width;
                                }, 300);
                            });

                            // ========== FIX: Show Overview tab on mobile ==========
                            // Get all tab panes
                            var allPanes = document.querySelectorAll('.tab-pane');
                            var tabButtons = document.querySelectorAll('#dashboardTabs .nav-link');
                            
                            // Function to switch tabs
                            function switchTab(targetId) {
                                // Hide all panes
                                allPanes.forEach(function(pane) {
                                    pane.classList.remove('show', 'active');
                                    pane.style.display = 'none';
                                });
                                
                                // Show target pane
                                var targetPane = document.querySelector(targetId);
                                if (targetPane) {
                                    targetPane.classList.add('show', 'active');
                                    targetPane.style.display = 'block';
                                }
                            }
                            
                            // Handle tab clicks
                            tabButtons.forEach(function(button) {
                                button.addEventListener('click', function(e) {
                                    var targetId = this.getAttribute('data-bs-target');
                                    tabButtons.forEach(function(btn) {
                                        btn.classList.remove('active');
                                    });
                                    this.classList.add('active');
                                    switchTab(targetId);
                                });
                            });
                            
                            // Show active tab
                            var activeTab = document.querySelector('#dashboardTabs .nav-link.active');
                            if (activeTab) {
                                var targetId = activeTab.getAttribute('data-bs-target');
                                setTimeout(function() {
                                    switchTab(targetId);
                                }, 50);
                            }
                        });

                        // Re-trigger animations when switching tabs
                        const tabButtons = document.querySelectorAll('#dashboardTabs .nav-link');
                        tabButtons.forEach(button => {
                            button.addEventListener('shown.bs.tab', function(e) {
                                const targetId = e.target.getAttribute('data-bs-target');
                                const targetPane = document.querySelector(targetId);

                                const animatedElements = targetPane.querySelectorAll(
                                    '.animate-scale, .animate-fade-up, .animate-fade-left, .animate-fade-right');
                                animatedElements.forEach(el => {
                                    const classes = el.className;
                                    el.classList.remove('animate-scale', 'animate-fade-up', 'animate-fade-left',
                                        'animate-fade-right');
                                    void el.offsetHeight;
                                    if (classes.includes('animate-scale')) el.classList.add('animate-scale');
                                    if (classes.includes('animate-fade-up')) el.classList.add('animate-fade-up');
                                    if (classes.includes('animate-fade-left')) el.classList.add(
                                        'animate-fade-left');
                                    if (classes.includes('animate-fade-right')) el.classList.add(
                                        'animate-fade-right');
                                });

                                if (targetId === '#analytics') {
                                    setTimeout(() => {
                                        if (monthlyOrdersChart) monthlyOrdersChart.resize();
                                        if (compareChart) compareChart.resize();
                                        if (orderStatusChart) orderStatusChart.resize();
                                        if (deliveryVsPickupChart) deliveryVsPickupChart.resize();
                                    }, 100);
                                }
                            });
                        });

                        window.addEventListener('beforeunload', function() {
                            if (refreshInterval) {
                                clearInterval(refreshInterval);
                            }
                            if (countdownInterval) {
                                clearInterval(countdownInterval);
                            }
                        });
                    </script>
</body>

</html>