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
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.25rem 0.8rem;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .btn-home-minimal:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-color: rgba(255,255,255,0.4);
        }

        /* ================================================================ */
        /* RESPONSIVE SIDEBAR FIX (Preserves your design)                   */
        /* ================================================================ */

        /* 1. Mobile Overlay (Only shows when sidebar is open on mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 9998; /* Behind sidebar, above content */
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* 2. Apply responsiveness to your existing .app-sidebar class */
        .app-sidebar {
            transition: transform 0.3s ease-in-out, margin-left 0.3s ease-in-out;
        }

        /* 3. Mobile Behavior: Hide the sidebar off-screen */
        @media (max-width: 991px) {
            .app-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                transform: translateX(-100%);
                z-index: 9999; /* On top of everything */
            }

            /* JavaScript will add this class to slide it in */
            .app-sidebar.open {
                transform: translateX(0);
            }
        }

        /* 4. Ensure the main content fits screen on mobile */
        @media (max-width: 991px) {
            .container.mt-4 {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
        }
        /* ================================================================ */
    </style>
</head>

<body>
    <nav class="navbar navbar-driver navbar-dark">
        <div class="container">
            <div class="d-flex align-items-center">
                <!-- ✅ HOME BUTTON (Placed before the logo) -->
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

    <!-- Custom Modal Structure (not using Bootstrap Modal) -->
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

            // Find result div in the modal
            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';
            }

            let url = '';
            if (action === 'confirm') url = '/driver/online-orders/' + orderId + '/confirm';
            else if (action === 'processing') url = '/driver/online-orders/' + orderId + '/processing';
            else if (action === 'ready') url = '/driver/online-orders/' + orderId + '/ready';

            console.log('Fetching URL:', url);

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
                    console.log('Response:', data);
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