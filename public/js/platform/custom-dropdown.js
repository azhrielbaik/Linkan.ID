/**
 * LINKAN.ID — MODERN CUSTOM DROPDOWN ENGINE (VANILLA JS)
 * 
 * Features:
 * 1. Trigger Button: Displays selected item label + animated chevron down, rounded-full/rounded-3xl border.
 * 2. Interaction & Animation: Vanilla JS click listener with smooth 300ms cubic-bezier transition (scale-95 opacity-0 -> scale-100 opacity-100).
 * 3. List Item: Bold item labels (font-weight: 700), hover highlights, and active checkmarks.
 * 4. Click Outside: Closes automatically on outside click or Escape key press.
 * 5. Full 2-Way Binding with Native <select> elements (supports forms, onchange handlers, and AJAX filters).
 */

(function () {
    'use strict';

    // Store active open dropdown instance
    let activeDropdown = null;

    /**
     * Close any currently open custom dropdown
     */
    function closeAllDropdowns() {
        if (activeDropdown) {
            activeDropdown.classList.remove('is-open');
            const trigger = activeDropdown.querySelector('.custom-dropdown-trigger');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            activeDropdown = null;
        }
    }

    /**
     * Initialize a single select element into a modern custom dropdown
     * @param {HTMLSelectElement} selectEl
     */
    function initCustomDropdown(selectEl) {
        if (!selectEl || selectEl.dataset.customDropdownInit === 'true') return;
        selectEl.dataset.customDropdownInit = 'true';

        // Hide original select visually but keep it accessible for form submission
        selectEl.classList.add('custom-dropdown-native-hidden');

        // Create Dropdown Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-dropdown-wrapper';
        if (selectEl.id) wrapper.dataset.forId = selectEl.id;

        // Create Trigger Button (Rounded-full with active label and chevron)
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-dropdown-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        const labelSpan = document.createElement('span');
        labelSpan.className = 'custom-dropdown-label';

        const chevronIcon = document.createElement('i');
        chevronIcon.className = 'fas fa-chevron-down custom-dropdown-chevron';

        trigger.appendChild(labelSpan);
        trigger.appendChild(chevronIcon);

        // Create Dropdown Menu Panel (scale-95 transition container)
        const menu = document.createElement('div');
        menu.className = 'custom-dropdown-menu';
        menu.setAttribute('role', 'listbox');

        /**
         * Rebuild the menu options from the select element
         */
        function buildOptions() {
            menu.innerHTML = '';
            const selectedOpt = selectEl.selectedOptions[0] || selectEl.options[0];
            labelSpan.textContent = selectedOpt ? selectedOpt.textContent.trim() : 'Pilih...';

            const children = Array.from(selectEl.children);

            children.forEach(child => {
                if (child.tagName.toLowerCase() === 'optgroup') {
                    const groupLabel = document.createElement('div');
                    groupLabel.className = 'custom-dropdown-group-label';
                    groupLabel.textContent = child.label;
                    menu.appendChild(groupLabel);

                    Array.from(child.children).forEach(option => {
                        createOptionItem(option, menu);
                    });
                } else if (child.tagName.toLowerCase() === 'option') {
                    createOptionItem(child, menu);
                }
            });
        }

        /**
         * Create an individual option item with bold label and checkmark
         */
        function createOptionItem(optionEl, menuContainer) {
            const item = document.createElement('div');
            item.className = 'custom-dropdown-item';
            item.setAttribute('role', 'option');
            item.dataset.value = optionEl.value;

            const isSelected = optionEl.selected;
            if (isSelected) {
                item.classList.add('is-selected');
                item.setAttribute('aria-selected', 'true');
            }

            // Bold text label
            const textSpan = document.createElement('span');
            textSpan.className = 'custom-dropdown-item-text';
            textSpan.textContent = optionEl.textContent.trim();

            // Checkmark icon for active item
            const checkIcon = document.createElement('i');
            checkIcon.className = 'fas fa-check custom-dropdown-check';

            item.appendChild(textSpan);
            item.appendChild(checkIcon);

            // Item click handler
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                selectOption(optionEl.value);
                closeAllDropdowns();
            });

            menuContainer.appendChild(item);
        }

        /**
         * Select an option by value and sync with native select
         */
        function selectOption(value) {
            if (selectEl.value !== value) {
                selectEl.value = value;
                // Dispatch native change & input events for any attached listeners or form submissions
                selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                selectEl.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // Update UI state
            const selectedOpt = selectEl.selectedOptions[0] || selectEl.options[0];
            labelSpan.textContent = selectedOpt ? selectedOpt.textContent.trim() : 'Pilih...';

            menu.querySelectorAll('.custom-dropdown-item').forEach(it => {
                if (it.dataset.value === value) {
                    it.classList.add('is-selected');
                    it.setAttribute('aria-selected', 'true');
                } else {
                    it.classList.remove('is-selected');
                    it.removeAttribute('aria-selected');
                }
            });
        }

        // Toggle Dropdown on Trigger Click
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = wrapper.classList.contains('is-open');

            if (isOpen) {
                closeAllDropdowns();
            } else {
                closeAllDropdowns(); // Close any other open dropdown

                // Adjust menu placement if too close to right edge
                wrapper.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
                activeDropdown = wrapper;

                const rect = menu.getBoundingClientRect();
                if (rect.right > window.innerWidth - 20) {
                    menu.classList.add('menu-right');
                } else {
                    menu.classList.remove('menu-right');
                }
            }
        });

        // Insert custom dropdown right after the original select
        selectEl.parentNode.insertBefore(wrapper, selectEl);
        wrapper.appendChild(selectEl);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        buildOptions();

        // Listen for programmatic updates to native select
        selectEl.addEventListener('change', () => {
            const selectedOpt = selectEl.selectedOptions[0] || selectEl.options[0];
            if (selectedOpt) {
                labelSpan.textContent = selectedOpt.textContent.trim();
                menu.querySelectorAll('.custom-dropdown-item').forEach(it => {
                    it.classList.toggle('is-selected', it.dataset.value === selectEl.value);
                });
            }
        });
    }

    /**
     * Auto-detect and enhance all selects with filter classes or data-custom-dropdown attribute
     */
    function enhanceAllSelects() {
        const selects = document.querySelectorAll(
            'select.filter-select, select.p-filter-select, select.custom-select, select[data-custom-dropdown]'
        );
        selects.forEach(initCustomDropdown);
    }

    // 4. Click outside listener (Logika JS Murni)
    document.addEventListener('click', (e) => {
        if (activeDropdown && !activeDropdown.contains(e.target)) {
            closeAllDropdowns();
        }
    });

    // Close on Escape key press
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && activeDropdown) {
            closeAllDropdowns();
        }
    });

    // Initialize on DOM Ready & provide global helper
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enhanceAllSelects);
    } else {
        enhanceAllSelects();
    }

    // Expose utility to window for dynamic elements if loaded via AJAX
    window.initPlatformCustomDropdowns = enhanceAllSelects;
    window.initCustomDropdown = initCustomDropdown;
    window.closeAllPlatformDropdowns = closeAllDropdowns;
})();
