/**
 * LINKAN.ID — MODERN CAPSULE NAVBAR ENGINE (VANILLA JS)
 * 
 * Features:
 * 1. Single Active Tab: Clicking a tab activates it and closes all other tabs.
 * 2. Expanding Animation: Pure CSS transitions expand the label on .is-active.
 * 3. Click Outside: Automatically deactivates all tabs (collapses back to icons only) when clicking outside.
 */

(function () {
    'use strict';

    /**
     * Deactivate all tabs in the capsule navbar
     * @param {HTMLElement} container
     */
    function deactivateAllTabs(container) {
        if (!container) return;
        const tabs = container.querySelectorAll('.nav-capsule-tab');
        tabs.forEach(tab => {
            tab.classList.remove('is-active');
            tab.setAttribute('aria-expanded', 'false');
        });
    }

    /**
     * Initialize capsule navbar interactions
     */
    function initNavbarCapsules() {
        const capsules = document.querySelectorAll('.platform-navbar-capsule');
        if (!capsules.length) return;

        capsules.forEach(capsule => {
            const tabs = capsule.querySelectorAll('.nav-capsule-tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    const isAlreadyActive = this.classList.contains('is-active');

                    // If it's a link and already active, let it navigate normally
                    // If it's the first click, expand it and activate
                    if (!isAlreadyActive) {
                        // Deactivate all other tabs in this capsule
                        tabs.forEach(t => {
                            t.classList.remove('is-active');
                            t.setAttribute('aria-expanded', 'false');
                        });

                        // Activate clicked tab
                        this.classList.add('is-active');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });

        // 5. Click outside listener (Logika JS Murni)
        document.addEventListener('click', function (e) {
            capsules.forEach(capsule => {
                if (!capsule.contains(e.target)) {
                    deactivateAllTabs(capsule);
                }
            });
        });

        // Close on Escape key press
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                capsules.forEach(capsule => deactivateAllTabs(capsule));
            }
        });
    }

    // Initialize on DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbarCapsules);
    } else {
        initNavbarCapsules();
    }

    window.initNavbarCapsules = initNavbarCapsules;
})();
