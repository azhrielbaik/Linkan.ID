/**
 * ============================================================================
 * LINKAN.ID - ADMIN SELLER MAIN LAYOUT JAVASCRIPT (layout.js)
 * Extracted from resources/views/admin_seller/layouts/app.blade.php
 * ============================================================================
 */

(function () {
    'use strict';

    /**
     * ========================================================================
     * 1. CONFIG & DATA BRIDGE
     * Reads backend parameters passed via <script id="app-config">
     * ========================================================================
     */
    function initAppConfig() {
        try {
            const configElem = document.getElementById('app-config');
            if (configElem) {
                const config = JSON.parse(configElem.textContent);
                window.SellerNotifEndpoint = config.notifEndpoint || '';
                window.SellerNotifReadEndpoint = config.notifReadEndpoint || '';
                window.SellerNotifReadAllEndpoint = config.notifReadAllEndpoint || '';
                window.SellerNotifSSEEndpoint = config.notifSSEEndpoint || '';
            }
        } catch (e) {
            console.error('Error reading app-config:', e);
        }
    }

    /**
     * ========================================================================
     * 2. THEME MANAGEMENT
     * ========================================================================
     */
    function syncThemeState() {
        try {
            var savedTheme = localStorage.getItem('theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var isDark = savedTheme === 'dark' || (!savedTheme && prefersDark);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {}
    }

    function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = !html.classList.contains('dark');

        if (isDark) {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            document.cookie = "theme=dark; path=/; max-age=31536000; SameSite=Lax";
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            document.cookie = "theme=light; path=/; max-age=31536000; SameSite=Lax";
        }

        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: isDark } }));
    }

    /**
     * ========================================================================
     * 3. SIDEBAR MANAGEMENT
     * ========================================================================
     */
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
        }
    }

    function toggleMinimize() {
        document.body.classList.toggle('mini-sidebar');
        const isMini = document.body.classList.contains('mini-sidebar');
        localStorage.setItem('sidebar-mini', isMini ? 'true' : 'false');
    }

    function restoreSidebarMiniState() {
        if (localStorage.getItem('sidebar-mini') === 'true') {
            document.body.classList.add('mini-sidebar');
        }
    }

    /**
     * ========================================================================
     * 4. PROFILE DROPDOWN
     * ========================================================================
     */
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    function initProfileDropdownClose() {
        document.addEventListener('click', function (event) {
            const profile = document.querySelector('.top-profile');
            const dropdown = document.getElementById('profileDropdown');
            if (profile && dropdown && !profile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    }

    /**
     * ========================================================================
     * 5. GLOBAL LOADING OVERLAY
     * ========================================================================
     */
    function showGlobalLoader() {
        const overlay = document.getElementById('global-loading-overlay');
        if (overlay) overlay.style.display = 'flex';
    }

    function hideGlobalLoader() {
        const overlay = document.getElementById('global-loading-overlay');
        if (overlay) overlay.style.display = 'none';
    }

    function initLoadingInterceptors() {
        // 1. Standar Form Submit
        document.addEventListener('submit', function (e) {
            // Check if submission is prevented by another script
            if (!e.defaultPrevented) {
                showGlobalLoader();
            }
        });

        // 2. Turbo events (karena menggunakan Hotwired Turbo)
        document.addEventListener('turbo:submit-start', showGlobalLoader);
        document.addEventListener('turbo:submit-end', hideGlobalLoader);
        document.addEventListener('turbo:load', hideGlobalLoader);
        document.addEventListener('turbo:render', hideGlobalLoader);

        // 3. Intercept Fetch API (Untuk AJAX manual seperti update posisi, simpan produk digital)
        if (!window._networkIntercepted) {
            window._networkIntercepted = true;

            const originalFetch = window.fetch;
            window.fetch = async function (...args) {
                let isMutating = false;
                const requestUrl = typeof args[0] === 'string' ? args[0] : (args[0]?.url || '');
                const isNotificationRead = requestUrl.includes('/notifications/read');

                if (args[1] && args[1].method && !args[1].silent && !isNotificationRead) {
                    const method = args[1].method.toUpperCase();
                    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                        isMutating = true;
                        showGlobalLoader();
                    }
                }
                try {
                    return await originalFetch.apply(this, args);
                } finally {
                    if (isMutating) {
                        hideGlobalLoader();
                    }
                }
            };

            // 4. Intercept XMLHttpRequest (Untuk jQuery AJAX, Axios, dll)
            const originalXhrOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function (method, url) {
                this._requestMethod = method ? method.toUpperCase() : 'GET';
                this._requestUrl = url || '';
                return originalXhrOpen.apply(this, arguments);
            };

            const originalXhrSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.send = function () {
                const isNotif = (this._requestUrl || '').includes('/notifications/read');
                let isMutating = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(this._requestMethod) && !isNotif && !this._isSilent;
                if (isMutating) {
                    showGlobalLoader();
                    this.addEventListener('loadend', function () {
                        hideGlobalLoader();
                    });
                }
                return originalXhrSend.apply(this, arguments);
            };
        }

        // Jika halaman dipulihkan dari bfcache (tombol back)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                hideGlobalLoader();
            }
        });
    }

    /**
     * ========================================================================
     * 6. TURBO INTEGRATION
     * ========================================================================
     */
    function initTurboHooks() {
        document.addEventListener("turbo:before-render", function (event) {
            syncThemeState();

            if (document.body.classList.contains('mini-sidebar')) {
                event.detail.newBody.classList.add('mini-sidebar');
            } else {
                event.detail.newBody.classList.remove('mini-sidebar');
            }
        });

        document.addEventListener("turbo:before-cache", function () {
            syncThemeState();
            hideGlobalLoader();
        });

        document.addEventListener("turbo:render", function () {
            syncThemeState();
        });

        document.addEventListener("turbo:load", function () {
            initAppConfig();
            restoreSidebarMiniState();
        });
    }

    // Expose functions globally for onclick and external scripts
    window.toggleDarkMode = toggleDarkMode;
    window.toggleSidebar = toggleSidebar;
    window.toggleMinimize = toggleMinimize;
    window.toggleProfileDropdown = toggleProfileDropdown;
    window.showGlobalLoader = showGlobalLoader;
    window.hideGlobalLoader = hideGlobalLoader;
    window.syncThemeState = syncThemeState;

    // Auto-init immediately and on DOMContentLoaded
    initAppConfig();
    restoreSidebarMiniState();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            restoreSidebarMiniState();
            initProfileDropdownClose();
            initLoadingInterceptors();
            initTurboHooks();
        });
    } else {
        restoreSidebarMiniState();
        initProfileDropdownClose();
        initLoadingInterceptors();
        initTurboHooks();
    }

})();
