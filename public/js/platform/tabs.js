/**
 * LINKAN.ID — MODERN CAPSULE TAB NAVBAR ENGINE (VANILLA JS)
 * 
 * Features:
 * 1. Automatically wraps text inside tabs into <span class="tab-label"> for smooth CSS max-width animation.
 * 2. Single active tab toggle on click.
 * 3. Click outside listener collapses all tabs back to icon-only state.
 */

(function () {
    'use strict';

    /**
     * Wrap raw text nodes inside tab elements into <span class="tab-label">
     * @param {HTMLElement} tab
     */
    function prepareTabStructure(tab) {
        if (!tab || tab.dataset.tabPrepared === 'true') return;
        tab.dataset.tabPrepared = 'true';

        // Check if tab already has .tab-label or .tab-text
        let labelSpan = tab.querySelector('.tab-label, .tab-text');

        if (!labelSpan) {
            // Find non-icon child nodes and wrap text
            const childNodes = Array.from(tab.childNodes);
            const textToWrap = [];

            childNodes.forEach(node => {
                // If it's a text node or not an icon/badge
                if (node.nodeType === Node.TEXT_NODE) {
                    const text = node.textContent.trim();
                    if (text) {
                        textToWrap.push(node);
                    }
                }
            });

            if (textToWrap.length > 0) {
                labelSpan = document.createElement('span');
                labelSpan.className = 'tab-label';
                
                textToWrap.forEach((textNode, idx) => {
                    labelSpan.textContent += (idx > 0 ? ' ' : '') + textNode.textContent.trim();
                    textNode.remove();
                });

                // Find where to insert (usually after icon)
                const icon = tab.querySelector('i, svg');
                if (icon && icon.nextSibling) {
                    tab.insertBefore(labelSpan, icon.nextSibling);
                } else {
                    tab.appendChild(labelSpan);
                }
            }
        }
    }

    /**
     * Collapse all tabs in containers to icon-only state
     */
    function collapseAllTabs() {
        const expandedTabs = document.querySelectorAll(
            '.tabs-container .is-expanded, ' +
            '.view-switcher .is-expanded, ' +
            '.p-ticket-tabs .is-expanded, ' +
            '.filter-tabs .is-expanded'
        );

        expandedTabs.forEach(tab => {
            tab.classList.remove('is-expanded');
            tab.setAttribute('aria-expanded', 'false');
        });
    }

    /**
     * Initialize all tab containers
     */
    function initPlatformTabs() {
        const containers = document.querySelectorAll('.tabs-container, .view-switcher, .p-ticket-tabs, .filter-tabs');
        if (!containers.length) return;

        containers.forEach(container => {
            const tabs = container.querySelectorAll('.tab-btn, .tab-link, .view-tab-link, .p-ticket-tab, .filter-tab');

            tabs.forEach(tab => {
                prepareTabStructure(tab);

                tab.addEventListener('click', function (e) {
                    const isAlreadyActive = this.classList.contains('active') || this.classList.contains('is-expanded');

                    // If it's a link, let the browser handle page navigation,
                    // but smoothly expand the active state instantly
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        t.classList.remove('is-expanded');
                        t.setAttribute('aria-expanded', 'false');
                    });

                    this.classList.add('active');
                    this.classList.add('is-expanded');
                    this.setAttribute('aria-expanded', 'true');
                });
            });
        });

        // 5. Click outside listener (Logika JS Murni)
        document.addEventListener('click', function (e) {
            let isInsideAnyContainer = false;
            containers.forEach(container => {
                if (container.contains(e.target)) {
                    isInsideAnyContainer = true;
                }
            });

            if (!isInsideAnyContainer) {
                collapseAllTabs();
            }
        });

        // Close on Escape key press
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                collapseAllTabs();
            }
        });
    }

    // Initialize on DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPlatformTabs);
    } else {
        initPlatformTabs();
    }

    window.initPlatformTabs = initPlatformTabs;
    window.collapseAllPlatformTabs = collapseAllTabs;
})();
