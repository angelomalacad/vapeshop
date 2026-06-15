// Global Admin Notification System - Modern UI Design
(function () {
    // Add CSS only once
    if (!document.querySelector('#admin-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'admin-notification-styles';
        style.textContent = `
            .admin-notification-container {
                position: fixed;
                top: 24px;
                right: 24px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 12px;
                pointer-events: none;
            }
            .admin-notification {
                pointer-events: auto;
                position: relative;
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
                0% { transform: translateX(100%) scale(0.8); opacity: 0; }
                100% { transform: translateX(0) scale(1); opacity: 1; }
            }
            @keyframes notificationSlideOut {
                0% { transform: translateX(0) scale(1); opacity: 1; }
                100% { transform: translateX(100%) scale(0.8); opacity: 0; }
            }
            @keyframes progressShrink {
                from { width: 100%; }
                to { width: 0%; }
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
            .admin-notification-close {
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 4px;
                border-radius: 8px;
                color: #94a3b8;
                flex-shrink: 0;
            }
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

    // Global showNotification function
    window.showNotification = function (message, type = 'success') {
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

        const progressBar = notification.querySelector(
            '.admin-notification-progress',
        );
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

        const closeBtn = notification.querySelector(
            '.admin-notification-close',
        );
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            clearTimeout(timeoutId);
            dismissNotification(notification);
        });

        notification.addEventListener('click', (e) => {
            if (
                e.target === notification ||
                e.target.closest('.admin-notification-content')
            ) {
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

    // ========== CRUD FUNCTIONS ==========

    // CREATE (POST) - For creating new records
    window.ajaxCreate = function (
        url,
        formData,
        successMessage,
        redirectUrl = null,
    ) {
        showNotification('Creating...', 'info');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showNotification(
                        successMessage ||
                            data.message ||
                            'Created successfully',
                        'success',
                    );
                    setTimeout(() => {
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    if (data.errors) {
                        let errorMsg = '';
                        for (const [field, errors] of Object.entries(
                            data.errors,
                        )) {
                            errorMsg += errors[0] + '\n';
                        }
                        showNotification(
                            errorMsg || data.message || 'Creation failed',
                            'error',
                        );
                    } else {
                        showNotification(
                            data.message || 'Creation failed',
                            'error',
                        );
                    }
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Network error. Please try again.', 'error');
            });
    };

    // READ (GET) - For fetching data
    window.ajaxGet = function (url, callback, errorMessage) {
        showNotification('Loading...', 'info');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    if (callback) callback(data);
                } else {
                    showNotification(
                        errorMessage || data.message || 'Failed to load data',
                        'error',
                    );
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Network error. Please try again.', 'error');
            });
    };

    // UPDATE (PUT) - For updating existing records
    window.ajaxUpdate = function (
        url,
        formData,
        successMessage,
        redirectUrl = null,
    ) {
        showNotification('Updating...', 'info');

        fetch(url, {
            method: 'POST', // Using POST with _method=PUT for Laravel
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showNotification(
                        successMessage ||
                            data.message ||
                            'Updated successfully',
                        'success',
                    );
                    setTimeout(() => {
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    if (data.errors) {
                        let errorMsg = '';
                        for (const [field, errors] of Object.entries(
                            data.errors,
                        )) {
                            errorMsg += errors[0] + '\n';
                            // Highlight error field
                            const input = document.querySelector(
                                `[name="${field}"]`,
                            );
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                feedback.innerText = errors[0];
                                if (
                                    !input.nextElementSibling?.classList.contains(
                                        'invalid-feedback',
                                    )
                                ) {
                                    input.parentNode.insertBefore(
                                        feedback,
                                        input.nextSibling,
                                    );
                                }
                            }
                        }
                        showNotification(
                            errorMsg || data.message || 'Update failed',
                            'error',
                        );
                    } else {
                        showNotification(
                            data.message || 'Update failed',
                            'error',
                        );
                    }
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Network error. Please try again.', 'error');
            });
    };

    // DELETE - For deleting records
    window.ajaxDelete = function (
        url,
        successMessage,
        errorMessage,
        redirectUrl = null,
    ) {
        showNotification('Deleting...', 'info');

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showNotification(
                        successMessage ||
                            data.message ||
                            'Deleted successfully',
                        'success',
                    );
                    setTimeout(() => {
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    showNotification(
                        errorMessage || data.message || 'Delete failed',
                        'error',
                    );
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Network error. Please try again.', 'error');
            });
    };

    // ========== AUTO AJAX FORM HANDLER ==========
    window.initAjaxForm = function (form) {
        if (!form) return;

        const newForm = form.cloneNode(true);
        form.parentNode.replaceChild(newForm, form);
        form = newForm;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const method = form.getAttribute('data-method') || 'POST';
            const isUpdate = method === 'PUT';
            const formData = new FormData(form);

            if (isUpdate) {
                formData.append('_method', 'PUT');
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

            if (isUpdate) {
                window.ajaxUpdate(form.action, formData, null, null);
            } else {
                window.ajaxCreate(form.action, formData, null, null);
            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    };

    // Initialize all ajax forms on page load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ajax-form').forEach((form) => {
            window.initAjaxForm(form);
        });
    });
})();
