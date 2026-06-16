<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Vape Expo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        
    .stat-card-modern {
    background: #ffffff;
    border-radius: 20px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    border: 1px solid #eef2f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}

.stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
    border-color: #e0e7ed;
}

.stat-icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    transition: all 0.3s ease;
}

.stat-card-modern:hover .stat-icon-wrapper {
    transform: scale(1.02);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 600;
    color: #8b9cb0;
    display: block;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .stat-card-modern {
        padding: 1rem;
        gap: 0.75rem;
    }

    .stat-icon-wrapper {
        width: 44px;
        height: 44px;
        font-size: 1.3rem;
        border-radius: 14px;
    }

    .stat-value {
        font-size: 1.4rem;
    }

    .stat-label {
        font-size: 0.65rem;
    }
}

        /* Modern Notification Styles */
        .admin-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            width: 380px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            animation: notificationSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .admin-notification-hide {
            animation: notificationSlideOut 0.3s ease forwards;
        }

        @keyframes notificationSlideIn {
            0% {
                transform: translateX(100%) scale(0.8);
                opacity: 0;
            }

            100% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        @keyframes notificationSlideOut {
            0% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }

            100% {
                transform: translateX(100%) scale(0.8);
                opacity: 0;
            }
        }

        @keyframes progressShrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .admin-notification-inner {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
        }

        .admin-notification-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .admin-notification-icon-wrapper i {
            font-size: 1.4rem;
        }

        .admin-notification-icon-wrapper.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .admin-notification-icon-wrapper.success i {
            color: #059669;
        }

        .admin-notification-icon-wrapper.error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }

        .admin-notification-icon-wrapper.error i {
            color: #dc2626;
        }

        .admin-notification-icon-wrapper.warning {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        }

        .admin-notification-icon-wrapper.warning i {
            color: #ea580c;
        }

        .admin-notification-icon-wrapper.info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .admin-notification-icon-wrapper.info i {
            color: #2563eb;
        }

        .admin-notification-content {
            flex: 1;
        }

        .admin-notification-title {
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .admin-notification.success .admin-notification-title {
            color: #059669;
        }

        .admin-notification.error .admin-notification-title {
            color: #dc2626;
        }

        .admin-notification.warning .admin-notification-title {
            color: #ea580c;
        }

        .admin-notification.info .admin-notification-title {
            color: #2563eb;
        }

        .admin-notification-message {
            font-size: 0.8rem;
            color: #475569;
            line-height: 1.4;
        }

        .admin-notification-close {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .admin-notification-close:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-notification-close i {
            font-size: 0.9rem;
        }

        .admin-notification-progress {
            height: 3px;
            width: 100%;
            animation: progressShrink 4s linear forwards;
        }

        .admin-notification-progress.success {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .admin-notification-progress.error {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }

        .admin-notification-progress.warning {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .admin-notification-progress.info {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
        }

        @media (max-width: 480px) {
            .admin-notification {
                width: auto;
                left: 16px;
                right: 16px;
            }

            .admin-notification-inner {
                padding: 12px 14px;
                gap: 10px;
            }

            .admin-notification-icon-wrapper {
                width: 34px;
                height: 34px;
            }

            .admin-notification-icon-wrapper i {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container">
            <!-- HOME BUTTON ADDED ON LEFT SIDE -->
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill me-3"
                style="border-color: rgba(255,255,255,0.3);">
                <i class="bi bi-house-door-fill me-1"></i> Home
            </a>
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                    class="d-inline-block align-text-top me-2">
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

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin-notifications.js') }}"></script>
</body>

</html>
