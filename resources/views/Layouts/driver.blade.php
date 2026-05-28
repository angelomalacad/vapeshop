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
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .btn {
            border-radius: 30px;
        }
        .badge {
            border-radius: 30px;
            padding: 0.35rem 0.75rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-driver navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('driver.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo Driver
            </a>
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

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Custom Modal Structure (not using Bootstrap Modal) -->
    <div id="customModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 12px; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.3);">
            <div id="customModalContent"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>