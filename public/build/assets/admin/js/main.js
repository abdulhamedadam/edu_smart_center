/**
 * ProgMaker Admin Dashboard - Main Module
 * Core functionality and theme management
 */

(function() {
    'use strict';

    // Theme management
    const ThemeManager = {
        currentTheme: localStorage.getItem('theme') || 'light',

        init() {
            this.applyTheme(this.currentTheme);
            this.bindEvents();
        },

        bindEvents() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => this.toggle());
            }
        },

        toggle() {
            const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
            this.applyTheme(newTheme);
            this.currentTheme = newTheme;
            localStorage.setItem('theme', newTheme);

            // Trigger custom event
            window.dispatchEvent(new CustomEvent('themeChange', {
                detail: { theme: newTheme }
            }));
        },

        applyTheme(theme) {
            document.body.setAttribute('data-theme', theme);
            
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                if (theme === 'dark') {
                    icon.classList.remove('bi-moon');
                    icon.classList.add('bi-sun');
                } else {
                    icon.classList.remove('bi-sun');
                    icon.classList.add('bi-moon');
                }
            }

            // Update Flatpickr theme
            const flatpickrCalendars = document.querySelectorAll('.flatpickr-calendar');
            flatpickrCalendars.forEach(calendar => {
                if (theme === 'dark') {
                    calendar.classList.add('dark');
                } else {
                    calendar.classList.remove('dark');
                }
            });
        }
    };

    // Notification system
    const NotificationManager = {
        init() {
            this.bindEvents();
        },

        bindEvents() {
            // Mark all as read
            document.querySelectorAll('.mark-all-read').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.markAllAsRead();
                });
            });

            // Individual notification click
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    item.classList.remove('unread');
                    this.updateBadge();
                });
            });
        },

        markAllAsRead() {
            document.querySelectorAll('.notification-item.unread, .message-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            this.updateBadge();
        },

        updateBadge() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const badge = document.querySelector('.nav-btn .notification-badge');
            if (badge) {
                badge.textContent = unreadCount;
                badge.style.display = unreadCount > 0 ? 'flex' : 'none';
            }
        },

        show(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type} animate__animated animate__fadeInRight`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi bi-${this.getIcon(type)}"></i>
                </div>
                <div class="toast-content">
                    <p>${message}</p>
                </div>
                <button class="toast-close">
                    <i class="bi bi-x"></i>
                </button>
            `;

            document.body.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.classList.remove('animate__fadeInRight');
                toast.classList.add('animate__fadeOutRight');
                setTimeout(() => toast.remove(), 300);
            }, 5000);

            // Close button
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.remove();
            });
        },

        getIcon(type) {
            const icons = {
                success: 'check-circle-fill',
                error: 'x-circle-fill',
                warning: 'exclamation-triangle-fill',
                info: 'info-circle-fill'
            };
            return icons[type] || icons.info;
        }
    };

    // Loading overlay
    const LoadingManager = {
        show(message = 'جاري التحميل...') {
            const overlay = document.createElement('div');
            overlay.id = 'loadingOverlay';
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>${message}</p>
                </div>
            `;
            document.body.appendChild(overlay);
        },

        hide() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.remove();
            }
        }
    };

    // Smooth scroll
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    // Initialize tooltips and popovers
    function initBootstrapComponents() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize Bootstrap popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Handle form submissions
    function initFormHandlers() {
        // Generic form handler with AJAX
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الحفظ...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        NotificationManager.show('تم الحفظ بنجاح!', 'success');
                        form.reset();
                    } else {
                        throw new Error('Failed to save');
                    }
                } catch (error) {
                    NotificationManager.show('حدث خطأ أثناء الحفظ', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });
    }

    // Print functionality
    function initPrintHandlers() {
        document.querySelectorAll('[data-print]').forEach(btn => {
            btn.addEventListener('click', () => {
                window.print();
            });
        });
    }

    // Export functionality
    function initExportHandlers() {
        document.querySelectorAll('[data-export]').forEach(btn => {
            btn.addEventListener('click', function() {
                const format = this.getAttribute('data-export');
                const tableId = this.getAttribute('data-table');
                
                if (tableId && window.$) {
                    const table = $(`#${tableId}`).DataTable();
                    
                    switch(format) {
                        case 'excel':
                            table.button('.buttons-excel').trigger();
                            break;
                        case 'pdf':
                            table.button('.buttons-pdf').trigger();
                            break;
                        case 'print':
                            table.button('.buttons-print').trigger();
                            break;
                        case 'copy':
                            table.button('.buttons-copy').trigger();
                            break;
                    }
                }
            });
        });
    }

    // Confirm delete
    window.confirmDelete = function(message = 'هل أنت متأكد من الحذف؟') {
        return Swal.fire({
            title: 'تأكيد الحذف',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then((result) => result.isConfirmed);
    };

    // Initialize everything
    function init() {
        ThemeManager.init();
        NotificationManager.init();
        initSmoothScroll();
        initBootstrapComponents();
        initFormHandlers();
        initPrintHandlers();
        initExportHandlers();

        // Add loaded class to body
        document.body.classList.add('loaded');

        console.log('%c ProgMaker Admin Dashboard ', 'background: #6366f1; color: white; font-size: 20px; padding: 10px; border-radius: 5px;');
        console.log('%c Version 1.0.0 ', 'background: #10b981; color: white; font-size: 12px; padding: 5px; border-radius: 3px;');
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose global API
    window.ProgMaker = {
        theme: ThemeManager,
        notify: NotificationManager,
        loading: LoadingManager,
        confirmDelete: window.confirmDelete
    };
})();
