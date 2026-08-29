<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Dashboard - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .navbar-driver {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            z-index: 1050;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .btn {
            border-radius: 30px;
        }

        .badge {
            border-radius: 30px;
            padding: 0.35rem 0.75rem;
        }

        /* Minimal Home button matching the dark theme */
        .btn-home-minimal {
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.8rem;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-home-minimal:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* ================================================================ */
        /* ✅ SIDEBAR STYLES (Only used when .has-sidebar is on body) */
        /* ================================================================ */
        .app-sidebar {
            position: fixed !important;
            top: 80px !important;
            /* 20px below navbar */
            left: 0 !important;
            width: 260px !important;
            height: auto !important;
            max-height: calc(100vh - 100px) !important;
            background: #ffffff;
            border-radius: 0 0 16px 0;
            border-radius: 16px !important;
            /* ✅ Fully rounded corners */
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
            z-index: 1040 !important;
            overflow-y: auto !important;
            padding-bottom: 10px !important;
            margin-top: 0 !important;
            transform: none !important;
            transition: none !important;
        }

        .sidebar-header {
            background: #1e293b;
            padding: 18px 20px;
            text-align: center;
            color: #fff;
        }

        .sidebar-header h6 {
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .sidebar-menu {
            padding: 12px;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar-menu .menu-item i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            margin-right: 14px;
        }

        .sidebar-menu .menu-item:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar-menu .menu-item.active {
            background: #eff6ff;
            color: #2563eb;
            border-left: 3px solid #2563eb;
        }

        /* ================================================================ */
        /* ✅ CONTENT MARGINS - Only applies to pages with .has-sidebar */
        /* ================================================================ */
        body.has-sidebar .container.mt-4 {
            margin-left: 280px !important;
            max-width: calc(100% - 280px) !important;
            padding-right: 20px !important;
            padding-top: 80px !important;
        }

        /* ✅ Normal pages (No sidebar) - Centered */
        body:not(.has-sidebar) .container.mt-4 {
            padding-top: 80px !important;
            max-width: 1140px !important;
        }

        /* ================================================================ */
        /* RESPONSIVE SIDEBAR FIX (Mobile) */
        /* ================================================================ */

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 9998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (max-width: 991px) {
            .app-sidebar {
                position: fixed !important;
                top: 60px !important;
                left: 0 !important;
                height: auto !important;
                max-height: calc(100vh - 60px) !important;
                transform: translateX(-100%) !important;
                z-index: 9999 !important;
                margin-top: 0 !important;
                transition: transform 0.3s ease-in-out !important;
            }

            .app-sidebar.open {
                transform: translateX(0) !important;
            }

            /* Mobile: No sidebar margins */
            body.has-sidebar .container.mt-4 {
                margin-left: 0 !important;
                max-width: 100% !important;
                padding-right: 15px !important;
                padding-left: 15px !important;
                padding-top: 80px !important;
            }
        }

        /* ================================================================ */
    </style>
</head>

<body class="@yield('page-class')">
    <nav class="navbar navbar-driver navbar-dark">
        <div class="container-fluid px-5">
            <div class="d-flex align-items-center">
                <!-- ✅ HOME BUTTON -->
                <a href="{{ route('driver.dashboard') }}" class="btn btn-home-minimal rounded-pill me-3">
                    <i class="bi bi-house-door me-1"></i> Home
                </a>

                <!-- Logo & Brand Name -->
                <a class="navbar-brand" href="{{ route('driver.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="30"
                        class="d-inline-block align-text-top me-2">
                    Vape Expo Driver
                </a>
            </div>

            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Added: The Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Custom Modal Structure -->
    <div id="customModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div
            style="background: white; border-radius: 12px; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
            <div id="customModalContent"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global function for handling order status updates
        window.handleStatus = function(action, orderId) {
            console.log('handleStatus called with:', action, orderId);

            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';
            }

            let url = '';
            if (action === 'confirm') url = '/driver/online-orders/' + orderId + '/confirm';
            else if (action === 'processing') url = '/driver/online-orders/' + orderId + '/processing';
            else if (action === 'ready') url = '/driver/online-orders/' + orderId + '/ready';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (resultDiv) {
                            resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        }
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        if (resultDiv) {
                            resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message ||
                                'Error occurred') + '</div>';
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    if (resultDiv) {
                        resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
                    }
                });
        };

        function openDeliveryModal(deliveryId) {
            const modal = document.getElementById('customModal');
            const modalContent = document.getElementById('customModalContent');

            modalContent.innerHTML = `
                <div style="padding: 20px; text-align: center;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading delivery details...</p>
                </div>
            `;
            modal.style.display = 'flex';

            fetch(`/driver/deliveries/${deliveryId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    modalContent.innerHTML = `
                        <div style="padding: 20px;">
                            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px;">
                                Failed to load delivery details. Please try again.
                            </div>
                            <div style="text-align: center; margin-top: 15px;">
                                <button onclick="closeModal()" class="btn btn-secondary">Close</button>
                            </div>
                        </div>
                    `;
                });
        }

        function closeModal() {
            document.getElementById('customModal').style.display = 'none';
            document.getElementById('customModalContent').innerHTML = '';
        }

        // Close modal when clicking outside
        document.getElementById('customModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // ==========================================================
        // SIDEBAR TOGGLE FUNCTION (For Mobile Only)
        // ==========================================================
        function toggleSidebar() {
            const sidebar = document.querySelector('.app-sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                sidebar.classList.toggle('open');
                if (overlay) {
                    overlay.classList.toggle('active');
                }
            }
        }

        // Close sidebar when clicking outside (The Overlay)
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            toggleSidebar();
        });

        // Close sidebar if the screen is resized back to Desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                document.querySelector('.app-sidebar')?.classList.remove('open');
                document.getElementById('sidebarOverlay')?.classList.remove('active');
            }
        });
    </script>
</body>

</html>
