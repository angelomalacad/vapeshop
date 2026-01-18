<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vape Shop System')</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-cloud-fog"></i> VapeHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="bi bi-shop"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('branches.index') }}">
                            <i class="bi bi-geo-alt"></i> Branches
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <!-- Notification Bell -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdown" style="min-width: 300px;">
                                <div class="p-3 border-bottom">
                                    <h6 class="mb-0">Notifications</h6>
                                </div>
                                <div id="notification-list" style="max-height: 300px; overflow-y: auto;">
                                    <!-- Notifications will be loaded here -->
                                </div>
                                <div class="p-2 border-top">
                                    <a href="{{ route('customer.notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                        View All
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isCustomer())
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                        <i class="bi bi-receipt"></i> My Orders
                                    </a></li>
                                @elseif(Auth::user()->isSuperAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Admin Dashboard
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>VapeHub</h5>
                    <p>Your trusted vape shop with multiple branches across the city.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Products</a></li>
                        <li><a href="{{ route('branches.index') }}" class="text-white-50 text-decoration-none">Branches</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="bi bi-telephone"></i> +63 912 345 6789</p>
                    <p><i class="bi bi-envelope"></i> info@vapehub.com</p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} VapeHub System. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Real-time Notification System -->
    @auth
    <script>
        // Initialize Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Subscribe to user's private channel
        const channel = pusher.subscribe('private-orders.{{ Auth::id() }}');
        
        // Listen for order updates
        channel.bind('order.updated', function(data) {
            showNotification('Order Update', data.message, 'success');
            updateNotificationCount(1);
            loadNotifications();
        });

        // Load notifications
        function loadNotifications() {
            fetch('/api/notifications?unread=true')
                .then(response => response.json())
                .then(data => {
                    updateNotificationCount(data.total);
                    updateNotificationList(data.notifications);
                });
        }

        function updateNotificationCount(count) {
            const badge = document.getElementById('notification-count');
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }

        function updateNotificationList(notifications) {
            const container = document.getElementById('notification-list');
            container.innerHTML = '';
            
            if (notifications.length === 0) {
                container.innerHTML = '<div class="p-3 text-center text-muted">No new notifications</div>';
                return;
            }
            
            notifications.forEach(notification => {
                const div = document.createElement('div');
                div.className = 'dropdown-item p-3 border-bottom';
                div.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${notification.title}</strong>
                        <small class="text-muted">${notification.time}</small>
                    </div>
                    <p class="mb-0">${notification.message}</p>
                `;
                container.appendChild(div);
            });
        }

        function showNotification(title, message, type = 'info') {
            // Using SweetAlert or custom notification
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            
            Toast.fire({
                icon: type,
                title: title,
                text: message
            });
        }

        // Load initial notifications
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>