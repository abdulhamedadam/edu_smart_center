/**
 * ProgMaker Admin Dashboard - Sidebar Module
 * Handles sidebar toggle, navigation, and responsive behavior
 */

(function() {
    'use strict';

    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const contentWrapper = document.getElementById('content-wrapper');
    const navLinks = document.querySelectorAll('.nav-link[data-page]');

    // State
    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    let isMobileOpen = false;

    /**
     * Initialize sidebar
     */
    function init() {
        // Apply initial state
        if (isCollapsed && window.innerWidth > 991) {
            document.body.classList.add('sidebar-collapsed');
        }

        // Bind events
        bindEvents();

        // Add tooltips for collapsed sidebar
        addTooltips();
    }

    /**
     * Bind event listeners
     */
    function bindEvents() {
        // Sidebar toggle (desktop)
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        // Mobile toggle
        if (mobileToggle) {
            mobileToggle.addEventListener('click', toggleMobileSidebar);
        }

        // Overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeMobileSidebar);
        }

        // Navigation links
        navLinks.forEach(link => {
            link.addEventListener('click', handleNavClick);
        });

        // Window resize
        window.addEventListener('resize', handleResize);

        // Keyboard shortcut
        document.addEventListener('keydown', handleKeyboard);
    }

    /**
     * Toggle sidebar collapse state (desktop)
     */
    function toggleSidebar() {
        isCollapsed = !isCollapsed;
        document.body.classList.toggle('sidebar-collapsed', isCollapsed);
        localStorage.setItem('sidebarCollapsed', isCollapsed);

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('sidebarToggle', {
            detail: { collapsed: isCollapsed }
        }));

        // Refresh charts if they exist
        setTimeout(() => {
            if (window.Chart) {
                Chart.instances.forEach(chart => chart.resize());
            }
        }, 300);
    }

    /**
     * Toggle mobile sidebar
     */
    function toggleMobileSidebar() {
        isMobileOpen = !isMobileOpen;
        document.body.classList.toggle('sidebar-mobile-open', isMobileOpen);
    }

    /**
     * Close mobile sidebar
     */
    function closeMobileSidebar() {
        isMobileOpen = false;
        document.body.classList.remove('sidebar-mobile-open');
    }

    /**
     * Handle navigation link click
     */
    function handleNavClick(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        
        if (page) {
            // Update active state
            navLinks.forEach(link => link.parentElement.classList.remove('active'));
            this.parentElement.classList.add('active');

            // Show page
            showPage(page);

            // Close mobile sidebar
            if (window.innerWidth <= 991) {
                closeMobileSidebar();
            }

            // Update URL hash
            window.location.hash = page;
        }
    }

    /**
     * Show specific page
     */
    function showPage(pageName) {
        // Hide all pages
        const pages = document.querySelectorAll('.page-content');
        pages.forEach(page => {
            page.classList.remove('active');
        });

        // Show target page
        const targetPage = document.getElementById(`${pageName}-page`);
        if (targetPage) {
            targetPage.classList.add('active');
            
            // Update page title
            const pageTitle = targetPage.querySelector('.page-title');
            if (pageTitle) {
                document.title = `${pageTitle.textContent} - ProgMaker`;
            }

            // Trigger page show event
            window.dispatchEvent(new CustomEvent('pageShow', {
                detail: { page: pageName }
            }));
        }
    }

    /**
     * Handle window resize
     */
    function handleResize() {
        if (window.innerWidth > 991) {
            // Desktop: close mobile sidebar
            closeMobileSidebar();
        } else {
            // Mobile: remove collapsed state
            document.body.classList.remove('sidebar-collapsed');
        }
    }

    /**
     * Handle keyboard shortcuts
     */
    function handleKeyboard(e) {
        // Ctrl/Cmd + B to toggle sidebar
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            if (window.innerWidth > 991) {
                toggleSidebar();
            }
        }

        // Escape to close mobile sidebar
        if (e.key === 'Escape' && isMobileOpen) {
            closeMobileSidebar();
        }
    }

    /**
     * Add tooltips for collapsed sidebar
     */
    function addTooltips() {
        navLinks.forEach(link => {
            const text = link.querySelector('.nav-text');
            if (text) {
                link.setAttribute('data-tooltip', text.textContent.trim());
            }
        });
    }

    /**
     * Check URL hash on load
     */
    function checkUrlHash() {
        const hash = window.location.hash.slice(1);
        if (hash) {
            const targetLink = document.querySelector(`.nav-link[data-page="${hash}"]`);
            if (targetLink) {
                targetLink.click();
            }
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            init();
            checkUrlHash();
        });
    } else {
        init();
        checkUrlHash();
    }

    // Expose API
    window.Sidebar = {
        toggle: toggleSidebar,
        collapse: () => {
            if (!isCollapsed) toggleSidebar();
        },
        expand: () => {
            if (isCollapsed) toggleSidebar();
        },
        showPage: showPage,
        isCollapsed: () => isCollapsed
    };
})();
