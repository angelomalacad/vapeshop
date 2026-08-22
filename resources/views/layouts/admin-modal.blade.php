<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Modal View')</title>
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
        /* Modern Minimalist Modal Styles - Matches deliveries modal */
        .admin-modal-container {
            padding: 1.5rem;
            max-height: 85vh;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .admin-modal-container::-webkit-scrollbar {
            width: 6px;
        }

        .admin-modal-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .admin-modal-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Modal Header */
        .modal-header-minimal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef2f6;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0;
        }

        .modal-title i {
            color: #3b82f6;
            margin-right: 0.5rem;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #eef2f6;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .card-header-minimal {
            padding: 0.875rem 1.25rem;
            background: white;
            border-bottom: 1px solid #eef2f6;
        }

        .card-header-minimal h6 {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0;
        }

        .card-header-minimal h6 i {
            margin-right: 0.5rem;
            color: #3b82f6;
        }

        .card-body-minimal {
            padding: 1rem 1.25rem;
        }

        /* Info Rows */
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            width: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .info-value {
            flex: 1;
            font-size: 0.8rem;
            color: #1a1a2e;
            font-weight: 500;
        }

        .info-value .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.65rem;
        }

        /* Status Badges */
        .badge-delivered {
            background: #d1fae5;
            color: #059669;
        }

        .badge-in_transit {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-picked_up {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge-assigned {
            background: #f1f5f9;
            color: #475569;
        }

        /* Form Styles */
        .form-label-minimal {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .form-control-minimal,
        .form-select-minimal {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            transition: all 0.2s;
            width: 100%;
        }

        .form-control-minimal:focus,
        .form-select-minimal:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Proof Images */
        .proof-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .proof-image:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Buttons */
        .btn-update {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-secondary-minimal {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary-minimal:hover {
            background: #e2e8f0;
        }

        .btn-download {
            font-size: 0.7rem;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
        }

        /* Alert Styles */
        .alert-minimal {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
        }

        .alert-danger-minimal {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
        }

        .alert-success-minimal {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #059669;
        }

        .alert-info-minimal {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #2563eb;
        }

        /* Image Preview Modal */
        .image-preview-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .image-preview-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            overflow: hidden;
        }

        .image-preview-header {
            padding: 1rem 1.25rem;
            background: white;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .image-preview-body {
            padding: 1.5rem;
            text-align: center;
        }

        .image-preview-body img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 12px;
        }

        .image-preview-footer {
            padding: 1rem 1.25rem;
            background: #f8f9fa;
            border-top: 1px solid #eef2f6;
            text-align: right;
        }

        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-modal-container {
                padding: 1rem;
            }

            .info-label {
                width: 80px;
            }

            .card-header-minimal {
                padding: 0.75rem 1rem;
            }

            .card-body-minimal {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-modal-container">
        @yield('content')
    </div>

    <!-- Global Image Preview Modal -->
    <div id="adminImagePreviewModal" class="image-preview-modal" onclick="closeAdminImagePreview(event)">
        <div class="image-preview-content" onclick="event.stopPropagation()">
            <div class="image-preview-header">
                <h6 class="mb-0" id="adminPreviewTitle">Image Preview</h6>
                <button type="button" class="btn-close" onclick="closeAdminImagePreview()"></button>
            </div>
            <div class="image-preview-body">
                <img id="adminPreviewImage" src="">
            </div>
            <div class="image-preview-footer">
                <button type="button" class="btn-secondary-minimal me-2"
                    onclick="closeAdminImagePreview()">Close</button>
                <a id="adminDownloadLink" href="#" download class="btn-update"
                    style="width: auto; padding: 0.4rem 1rem; text-decoration: none;">Download</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ADD THIS - Global Notification Script -->
    <script src="{{ asset('js/admin-notification.js') }}"></script>
    
    <script>
        // Global function to close modal
        function closeAdminModal() {
            const modalElement = document.querySelector('.modal.show');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            }
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
            document.body.classList.remove('modal-open');
        }

        // Global image preview function for admin modals
        function showAdminImagePreview(imageUrl, title) {
            document.getElementById('adminPreviewImage').src = imageUrl;
            document.getElementById('adminPreviewTitle').textContent = title || 'Image Preview';
            document.getElementById('adminDownloadLink').href = imageUrl;
            document.getElementById('adminImagePreviewModal').style.display = 'flex';
        }

        function closeAdminImagePreview(event) {
            if (!event || event.target === document.getElementById('adminImagePreviewModal') || event.target.closest(
                    '.btn-close')) {
                document.getElementById('adminImagePreviewModal').style.display = 'none';
            }
        }
    </script>
</body>

</html>