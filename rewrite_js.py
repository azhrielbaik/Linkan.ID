import re

file_path = 'resources/views/homeadminS/shortlink/create.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# We need to wrap the JS listeners in a flag
js_block = """    // Detail Panel Logic
    if (!window.shortlinkEventDelegationSet) {
        window.shortlinkEventDelegationSet = true;

        window.openPanel = function() {
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.add('is-visible');
            if(currentPanel) currentPanel.classList.add('is-open');
        }

        window.closePanel = function() {
            const viewSec = document.getElementById('panel-view-section');
            if (viewSec && viewSec.style.display === 'none') {
                toggleSection('view');
                return;
            }
            
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.remove('is-visible');
            if(currentPanel) currentPanel.classList.remove('is-open');
        }

        document.addEventListener('click', function(e) {
            // Close Panel
            if (e.target.closest('#sl-panel-close') || e.target.id === 'sl-overlay') {
                closePanel();
            }

            // Detail button
            const btn = e.target.closest('.sl-btn--detail');
            if (btn) {
                e.preventDefault();
                const card = btn.closest('.sl-card');
                if (!card) return;

                const form = document.getElementById('panel-form');
                if(form) {
                    form.action = `/admin/shortlinks/${card.dataset.id}`;
                }

                const panelInputSlug = document.getElementById('panel-input-slug');
                if(panelInputSlug) panelInputSlug.value = card.dataset.slug;

                const panelInputTitle = document.getElementById('panel-input-title');
                if(panelInputTitle) panelInputTitle.value = card.dataset.title;

                const panelInputPassword = document.getElementById('panel-input-password');
                if(panelInputPassword) panelInputPassword.value = card.dataset.password;

                const panelInputExpires = document.getElementById('panel-input-expires');
                if(panelInputExpires) panelInputExpires.value = card.dataset.expires;

                toggleSection('view');

                const panelTitle = document.getElementById('panel-title');
                if(panelTitle) panelTitle.innerText = card.dataset.title;
                
                const urlEl = document.getElementById('panel-url');
                if(urlEl) urlEl.href = card.dataset.url;

                const panelCreatedDate = document.getElementById('panel-created-date');
                if(panelCreatedDate) panelCreatedDate.innerText = card.dataset.created;

                const statusPasswordIcon = document.getElementById('status-password-icon');
                const statusPassword = document.getElementById('status-password');
                if(statusPassword) {
                    if (card.dataset.password) {
                        statusPassword.innerText = 'Password Protected';
                        if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-lock';
                    } else {
                        statusPassword.innerText = 'Public Link';
                        if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-unlock';
                    }
                }

                const statusExpires = document.getElementById('status-expires');
                if(statusExpires) {
                    if (card.dataset.expires) {
                        statusExpires.innerText = 'Expired: ' + card.dataset.expires.replace('T', ' ');
                    } else {
                        statusExpires.innerText = 'No Time Limit';
                    }
                }

                const panelSlug = document.getElementById('panel-slug-badge');
                if(panelSlug) {
                    const urlPath = new URL(card.dataset.url).pathname;
                    panelSlug.innerText = urlPath;
                }
                
                const panelDesc = document.getElementById('panel-desc');
                if(panelDesc) panelDesc.innerText = card.dataset.description || 'No description provided';
                
                const destEl = document.getElementById('panel-destination');
                if(destEl) {
                    destEl.href = card.dataset.destination;
                    destEl.innerText = card.dataset.destination;
                }

                const urlEl2 = document.getElementById('panel-url');
                if(urlEl2) {
                    urlEl2.href = card.dataset.url;
                    urlEl2.innerText = card.dataset.url;
                }

                const panelCreated = document.getElementById('panel-created');
                if(panelCreated) panelCreated.innerText = card.dataset.created;
                
                const panelUpdated = document.getElementById('panel-updated');
                if(panelUpdated) panelUpdated.innerText = card.dataset.updated;

                openPanel();
                const btnAnalytics = document.getElementById('panel-btn-analytics');
                if(btnAnalytics) {
                    const analyticsLink = card.querySelector('a[title="Analytics"]');
                    if(analyticsLink) btnAnalytics.href = analyticsLink.href;
                }
            }

            // Segmented tab
            const tab = e.target.closest('.segment-tab');
            if (tab) {
                e.preventDefault();
                const sortVal = tab.dataset.sort;
                const sortSelect = document.querySelector('select[name="sort"]');
                if (sortSelect) {
                    sortSelect.value = sortVal;
                    tab.parentElement.querySelectorAll('.segment-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    window.performAjaxSearch();
                }
            }

            // Edit direct
            const editBtn = e.target.closest('.sl-btn--edit-direct');
            if (editBtn) {
                e.preventDefault();
                const card = editBtn.closest('.sl-card');
                if (card) {
                    const detailBtn = card.querySelector('.sl-btn--detail');
                    if (detailBtn) {
                        detailBtn.click();
                        setTimeout(() => {
                            toggleSection('edit');
                        }, 50);
                    }
                }
            }

            // Clear search
            const clearLink = e.target.closest('.fa-times-circle')?.parentElement || e.target.closest('input[name="search"] ~ a');
            if (clearLink && clearLink.closest('div[style*="position: relative"]')) {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) searchInput.value = '';
                window.performAjaxSearch();
            }

            // Pagination
            const pageLink = e.target.closest('.pagination a, .pagination-container a, [style*="margin-top: 20px"] a');
            if (pageLink && pageLink.href) {
                const isShortlinkPagination = pageLink.closest('[style*="margin-top: 20px"]');
                if (isShortlinkPagination) {
                    e.preventDefault();
                    window.fetchAndUpdate(pageLink.href);
                }
            }
        });
    }

    // Attach submit event listener on turbo:load because the form element changes every load
    document.addEventListener("turbo:load", function() {
        window.toggleMobileForm = function() {
            const formCol = document.querySelector('.mobile-form-collapse');
            if (formCol) {
                formCol.classList.toggle('is-open');
                if (formCol.classList.contains('is-open')) {
                    formCol.scrollIntoView({ behavior: 'smooth' });
                }
            }
        };

        const searchInput = document.querySelector('input[name="search"]');
        const sortSelect = document.querySelector('select[name="sort"]');
        const searchForm = document.getElementById('search-filter-form');
        let debounceTimeout;

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                window.performAjaxSearch();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    window.performAjaxSearch();
                }, 300);
            });
        }

        window.performAjaxSearch = function() {
            const currentSearchInput = document.querySelector('input[name="search"]');
            const currentSortSelect = document.querySelector('select[name="sort"]');
            const searchVal = currentSearchInput ? currentSearchInput.value : '';
            const sortVal = currentSortSelect ? currentSortSelect.value : 'newest';
            
            const url = new URL(window.location.origin + window.location.pathname);
            if (searchVal.trim() !== '') {
                url.searchParams.set('search', searchVal);
            }
            if (sortVal !== 'newest') {
                url.searchParams.set('sort', sortVal);
            }
            
            window.fetchAndUpdate(url.toString());
        }

        window.fetchAndUpdate = function(url) {
            const listContainer = document.querySelector('.engagement-list');
            if (listContainer) listContainer.style.opacity = '0.5';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newList = doc.querySelector('.engagement-list');
                const currentList = document.querySelector('.engagement-list');
                if (newList && currentList) {
                    currentList.innerHTML = newList.innerHTML;
                    currentList.style.opacity = '1';
                }

                const newPagination = doc.querySelector('[style*="margin-top: 20px"]');
                const currentPagination = document.querySelector('[style*="margin-top: 20px"]');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                } else if (currentPagination) {
                    currentPagination.innerHTML = '';
                }

                const newResultsText = doc.querySelector('.card-header div[style*="font-size: 13px"]');
                const currentResultsText = document.querySelector('.card-header div[style*="font-size: 13px"]');
                if (newResultsText && currentResultsText) {
                    currentResultsText.innerHTML = newResultsText.innerHTML;
                }

                const newTotalLinksBox = doc.querySelector('.stat-box:nth-child(2) strong');
                const currentTotalLinksBox = document.querySelector('.stat-box:nth-child(2) strong');
                if (newTotalLinksBox && currentTotalLinksBox) {
                    currentTotalLinksBox.innerHTML = newTotalLinksBox.innerHTML;
                }

                const newSearchWrapper = doc.querySelector('input[name="search"]').parentElement;
                const currentSearchWrapper = document.querySelector('input[name="search"]').parentElement;
                if (newSearchWrapper && currentSearchWrapper) {
                    const clearBtn = currentSearchWrapper.querySelector('a');
                    const newClearBtn = newSearchWrapper.querySelector('a');
                    if (clearBtn && !newClearBtn) {
                        clearBtn.remove();
                    } else if (!clearBtn && newClearBtn) {
                        currentSearchWrapper.appendChild(newClearBtn);
                    }
                }

                const currentSortSelect2 = document.querySelector('select[name="sort"]');
                if (currentSortSelect2) {
                    const currentSort = currentSortSelect2.value || 'newest';
                    document.querySelectorAll('.segment-tab').forEach(t => {
                        if (t.dataset.sort === currentSort) {
                            t.classList.add('active');
                        } else {
                            t.classList.remove('active');
                        }
                    });
                }

                history.pushState(null, '', url);
            })
            .catch(err => {
                console.error('Ajax search failed:', err);
                if (listContainer) listContainer.style.opacity = '1';
            });
        }
    });"""

# Use regex to replace from "// Detail Panel Logic" to the end of script
pattern = re.compile(r'// Detail Panel Logic.*?document\.addEventListener\("turbo:load", function\(\) \{.*?</script>', re.DOTALL)
content = pattern.sub(js_block + "\n</script>", content)

# Make sure the data-turbo-track="reload" is PRESENT in the final content.
if '<link rel="stylesheet" href="{{ asset(\'css/pages/shortlink-create.css\') }}">' in content:
    content = content.replace(
        '<link rel="stylesheet" href="{{ asset(\'css/pages/shortlink-create.css\') }}">',
        '<link rel="stylesheet" href="{{ asset(\'css/pages/shortlink-create.css\') }}" data-turbo-track="reload">'
    )

if '<div class="dashboard-wrapper page-shortlink-create">' in content:
    content = content.replace(
        '<div class="dashboard-wrapper page-shortlink-create">',
        '<div class="dashboard-wrapper">'
    )

with open(file_path, 'w') as f:
    f.write(content)
print("Done rewriting JS without stripping CSS attributes")
