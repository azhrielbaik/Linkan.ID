/**
 * LINKAN.ID — MODERN GOOEY SEARCH BAR & ANIMATED AUTOCOMPLETE ENGINE (VANILLA JS)
 * 
 * Features:
 * 1. Automatically injects SVG filter with id="goo-effect" into DOM.
 * 2. State 1 (Compact Capsule Button "Search") -> State 2 (Smooth 500ms Expand Input + Gooey Glass Orb).
 * 3. Animated Search Suggestions Dropdown with Tailwind-like opacity-0 translate-y-4 -> opacity-100 translate-y-0.
 * 4. Automatic click-outside collapse for empty inputs and keyboard navigation.
 */

(function () {
    'use strict';

    /**
     * Inject hidden SVG filter definition into document body
     */
    function ensureGooeySVGFilter() {
        if (document.getElementById('goo-effect-svg')) return;

        const svgWrapper = document.createElement('div');
        svgWrapper.id = 'goo-effect-svg';
        svgWrapper.className = 'gooey-svg-filters';
        svgWrapper.innerHTML = `
            <svg style="position: absolute; width: 0; height: 0; pointer-events: none; overflow: hidden;" aria-hidden="true">
                <defs>
                    <filter id="goo-effect">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
                        <feComposite in="SourceGraphic" in2="goo" operator="atop" />
                    </filter>
                </defs>
            </svg>
        `;
        document.body.appendChild(svgWrapper);
    }

    /**
     * Enhance an individual search container
     * @param {HTMLElement} container 
     */
    function enhanceSearchContainer(container) {
        if (container.dataset.gooeyEnhanced === 'true') return;
        container.dataset.gooeyEnhanced = 'true';
        container.classList.add('gooey-enhanced');

        const input = container.querySelector('input[type="text"]');
        if (!input) return;

        // Determine placeholder text
        const placeholderText = input.getAttribute('placeholder') || 'Search';

        // 1. Create Placeholder Button Text (State 1)
        let placeholderSpan = container.querySelector('.gooey-search-placeholder');
        if (!placeholderSpan) {
            placeholderSpan = document.createElement('span');
            placeholderSpan.className = 'gooey-search-placeholder';
            placeholderSpan.innerHTML = `<i class="fas fa-search"></i> <span>${placeholderText.split(' ')[0] || 'Search'}</span>`;
            container.insertBefore(placeholderSpan, input);
        }

        // 2. Create Clear Button
        let clearBtn = container.querySelector('.gooey-search-clear');
        if (!clearBtn) {
            clearBtn = document.createElement('button');
            clearBtn.type = 'button';
            clearBtn.className = 'gooey-search-clear';
            clearBtn.innerHTML = '<i class="fas fa-times"></i>';
            clearBtn.setAttribute('aria-label', 'Clear search');
            container.appendChild(clearBtn);
        }

        // 3. Create Right Action Search Orb / Button (State 2 Gooey Orb)
        let searchBtn = container.querySelector('.gooey-search-btn');
        if (!searchBtn) {
            searchBtn = document.createElement('button');
            searchBtn.type = 'submit';
            searchBtn.className = 'gooey-search-btn';
            searchBtn.innerHTML = '<i class="fas fa-search"></i>';
            searchBtn.setAttribute('aria-label', 'Submit search');
            container.appendChild(searchBtn);
        }

        // 4. Create Floating Autocomplete Dropdown List
        let dropdown = container.querySelector('.gooey-autocomplete-dropdown');
        if (!dropdown) {
            dropdown = document.createElement('ul');
            dropdown.className = 'gooey-autocomplete-dropdown';
            container.appendChild(dropdown);
        }

        // Check Initial Value
        function syncState() {
            const hasValue = !!input.value.trim();
            if (hasValue) {
                container.classList.add('has-value', 'is-expanded');
            } else if (!container.contains(document.activeElement)) {
                container.classList.remove('has-value', 'is-expanded');
            } else {
                container.classList.remove('has-value');
            }
        }
        syncState();

        // Expand on Container / Placeholder Click
        container.addEventListener('click', function (e) {
            if (e.target.closest('.gooey-search-clear') || e.target.closest('.gooey-autocomplete-dropdown')) {
                return;
            }
            container.classList.add('is-expanded');
            input.focus();
        });

        // Focus & Blur Listeners
        input.addEventListener('focus', function () {
            container.classList.add('is-expanded');
        });

        input.addEventListener('input', function () {
            syncState();
            handleAutocompleteLookup(input, dropdown);
        });

        // Clear button action
        clearBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            input.value = '';
            input.focus();
            syncState();
            closeAutocompleteDropdown(dropdown);
            
            // Dispatch input event for real-time table filters
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });

        // Keyboard navigation for dropdown
        input.addEventListener('keydown', function (e) {
            if (!dropdown.classList.contains('is-open')) return;

            const items = Array.from(dropdown.querySelectorAll('.gooey-autocomplete-item'));
            const highlighted = dropdown.querySelector('.gooey-autocomplete-item.is-highlighted');
            let currentIndex = items.indexOf(highlighted);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                items.forEach(i => i.classList.remove('is-highlighted'));
                if (items[nextIndex]) {
                    items[nextIndex].classList.add('is-highlighted');
                    items[nextIndex].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                items.forEach(i => i.classList.remove('is-highlighted'));
                if (items[prevIndex]) {
                    items[prevIndex].classList.add('is-highlighted');
                    items[prevIndex].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'Enter') {
                if (highlighted) {
                    e.preventDefault();
                    selectSuggestionItem(highlighted, input, dropdown);
                }
            } else if (e.key === 'Escape') {
                closeAutocompleteDropdown(dropdown);
            }
        });
    }

    /**
     * AJAX Autocomplete Suggestions Handler
     */
    let debounceTimer;
    function handleAutocompleteLookup(input, dropdown) {
        const query = input.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 1) {
            closeAutocompleteDropdown(dropdown);
            return;
        }

        const suggestUrl = input.dataset.suggestUrl || getFallbackSuggestUrl(input);
        if (!suggestUrl) return;

        debounceTimer = setTimeout(() => {
            fetch(`${suggestUrl}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(items => {
                    renderAutocompleteItems(items, input, dropdown);
                })
                .catch(err => {
                    console.error('Gooey Search suggest error:', err);
                });
        }, 220);
    }

    function getFallbackSuggestUrl(input) {
        const type = input.dataset.autocompleteType;
        if (type === 'users') return '/platform-admin/users/suggest';
        if (type === 'activity') return '/platform-admin/logs/activity/suggest';
        if (type === 'transactions') return '/platform-admin/logs/transactions/suggest';
        return null;
    }

    /**
     * Render suggestions inside dropdown with entrance animation
     */
    function renderAutocompleteItems(items, input, dropdown) {
        dropdown.replaceChildren();

        if (!Array.isArray(items) || items.length === 0) {
            closeAutocompleteDropdown(dropdown);
            return;
        }

        items.slice(0, 6).forEach((item, index) => {
            const li = document.createElement('li');
            li.className = 'gooey-autocomplete-item';
            li.dataset.value = typeof item === 'object' ? item.value : item;

            const labelText = typeof item === 'object' ? item.label : item;
            
            // Extract badge if label has parenthesized tag e.g. "John (Buyer)"
            let cleanLabel = labelText;
            let badgeText = '';
            const match = labelText.match(/^(.*?)\s*\((.*?)\)$/);
            if (match) {
                cleanLabel = match[1];
                badgeText = match[2];
            }

            li.innerHTML = `
                <div class="gooey-autocomplete-item-left">
                    <i class="fas fa-search gooey-autocomplete-item-icon"></i>
                    <span class="gooey-autocomplete-item-label">${cleanLabel}</span>
                </div>
                ${badgeText ? `<span class="gooey-autocomplete-item-badge">${badgeText}</span>` : ''}
            `;

            li.addEventListener('click', function () {
                selectSuggestionItem(this, input, dropdown);
            });

            dropdown.appendChild(li);
        });

        // Open Dropdown with animated transition
        dropdown.classList.add('is-open');
    }

    function selectSuggestionItem(li, input, dropdown) {
        const value = li.dataset.value || li.querySelector('.gooey-autocomplete-item-label')?.textContent || '';
        input.value = value;
        closeAutocompleteDropdown(dropdown);

        // Check if inside a form, submit or trigger input event
        const form = input.closest('form');
        if (form && !input.id.includes('searchInput')) {
            form.submit();
        } else {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function closeAutocompleteDropdown(dropdown) {
        if (dropdown) {
            dropdown.classList.remove('is-open');
        }
    }

    /**
     * Global Click Outside Listener
     */
    function initGlobalClickOutside() {
        document.addEventListener('click', function (e) {
            const allSearchContainers = document.querySelectorAll('.search-box, .search-wrap, .gooey-search-container');
            
            allSearchContainers.forEach(container => {
                const input = container.querySelector('input[type="text"]');
                const dropdown = container.querySelector('.gooey-autocomplete-dropdown');

                if (!container.contains(e.target)) {
                    closeAutocompleteDropdown(dropdown);

                    // Collapse if input is empty
                    if (input && !input.value.trim()) {
                        container.classList.remove('is-expanded', 'has-value');
                    }
                }
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const openDropdowns = document.querySelectorAll('.gooey-autocomplete-dropdown.is-open');
                openDropdowns.forEach(closeAutocompleteDropdown);
            }
        });
    }

    /**
     * Main Init
     */
    function initGooeySearch() {
        ensureGooeySVGFilter();

        const containers = document.querySelectorAll('.search-box, .search-wrap, .gooey-search-container');
        containers.forEach(enhanceSearchContainer);
    }

    // Auto-init on DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initGooeySearch();
            initGlobalClickOutside();
        });
    } else {
        initGooeySearch();
        initGlobalClickOutside();
    }

    window.initGooeySearch = initGooeySearch;
})();
