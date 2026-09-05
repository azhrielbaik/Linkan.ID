(function () {
            // -------------------------------------------------------------
            // A. ACCORDION TOGGLE (Vanilla JS)
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            // B. TAB SWITCHER (Vanilla JS)
            // -------------------------------------------------------------
            const tabButtons = document.querySelectorAll('.faq-tab-btn');
            const categoryGroups = document.querySelectorAll('.faq-category-group');
            const searchInput = document.getElementById('kbSearchInput');
            const noResults = document.getElementById('faqNoResults');
            let currentActiveCategory = 'all';

            // Classes for Active and Inactive Tabs
            const activeTabClasses = ['active', 'bg-primary', 'text-white', 'border-2', 'border-white', 'ring-2', 'ring-primary', 'shadow-lg'];
            const inactiveTabClasses = ['border', 'border-slate-200', 'text-slate-500', 'hover:text-slate-900', 'hover:border-slate-300', 'bg-white', 'shadow-sm'];

            function applyTabCategory(targetCategory) {
                currentActiveCategory = targetCategory;

                // 1. Update tombol tab visual state
                tabButtons.forEach(btn => {
                    const btnCategory = btn.getAttribute('data-category');
                    if (btnCategory === targetCategory) {
                        btn.setAttribute('aria-selected', 'true');
                        inactiveTabClasses.forEach(cls => btn.classList.remove(cls));
                        activeTabClasses.forEach(cls => btn.classList.add(cls));
                    } else {
                        btn.setAttribute('aria-selected', 'false');
                        activeTabClasses.forEach(cls => btn.classList.remove(cls));
                        inactiveTabClasses.forEach(cls => btn.classList.add(cls));
                    }
                });

                // 2. Tampilkan atau sembunyikan grup kategori dengan animasi opacity
                categoryGroups.forEach(group => {
                    const groupCategory = group.getAttribute('data-category');
                    const shouldShow = (targetCategory === 'all' || groupCategory === targetCategory);

                    if (shouldShow) {
                        group.style.display = 'block';
                        group.style.opacity = '0';
                        group.style.transform = 'translateY(6px)';

                        // Tutup akordion yang terbuka saat berpindah tab
                        group.querySelectorAll('.faq-item.is-open').forEach(item => {
                            item.classList.remove('is-open');
                            const h = item.querySelector('.faq-accordion-header');
                            if (h) h.setAttribute('aria-expanded', 'false');
                        });

                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                group.style.opacity = '1';
                                group.style.transform = 'translateY(0)';
                            });
                        });
                    } else {
                        group.style.opacity = '0';
                        group.style.display = 'none';
                    }
                });

                // Sembunyikan notifikasi no-results jika ada
                if (noResults) noResults.classList.add('hidden');
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetCategory = btn.getAttribute('data-category');
                    // Bersihkan input search saat tab ditekan
                    if (searchInput && searchInput.value.trim() !== '') {
                        searchInput.value = '';
                        const clearBtn = document.getElementById('gooClearBtn');
                        if (clearBtn) clearBtn.classList.remove('visible');
                    }
                    applyTabCategory(targetCategory);
                });
            });

            // -------------------------------------------------------------
            // C. 2-STATE GOOEY SEARCH BAR & LIVE FILTERING
            // -------------------------------------------------------------
            const gooContainer = document.getElementById('gooSearchContainer');
            const gooSearchBar = document.getElementById('gooSearchBar');
            const clearBtn = document.getElementById('gooClearBtn');
            const searchIconBtn = document.getElementById('gooSearchIconBtn');
            const resetSearchBtn = document.getElementById('faqResetSearchBtn');
            const allFaqItems = document.querySelectorAll('.faq-item');

            if (gooSearchBar && gooContainer && searchInput) {
                // Expand from State 1 (Button) to State 2 (Input)
                const expandSearch = () => {
                    if (!gooSearchBar.classList.contains('expanded')) {
                        gooSearchBar.classList.add('expanded');
                        gooContainer.classList.add('is-expanded');
                        gooSearchBar.setAttribute('aria-expanded', 'true');
                        setTimeout(() => {
                            searchInput.focus();
                        }, 150);
                    }
                };

                // Collapse back to State 1 (Button) if empty
                const collapseSearch = () => {
                    if (searchInput.value.trim() === '') {
                        gooSearchBar.classList.remove('expanded');
                        gooContainer.classList.remove('is-expanded');
                        gooSearchBar.setAttribute('aria-expanded', 'false');
                        if (clearBtn) clearBtn.classList.remove('visible');
                        handleSearch();
                    }
                };

                // Click button to expand
                gooSearchBar.addEventListener('click', (e) => {
                    if (!gooSearchBar.classList.contains('expanded')) {
                        expandSearch();
                    }
                });

                gooSearchBar.addEventListener('keydown', (e) => {
                    if ((e.key === 'Enter' || e.key === ' ') && !gooSearchBar.classList.contains('expanded')) {
                        e.preventDefault();
                        expandSearch();
                    }
                });

                // Click outside to collapse if empty
                document.addEventListener('click', (e) => {
                    if (!gooContainer.contains(e.target)) {
                        collapseSearch();
                    }
                });

                // Escape key collapses
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && gooSearchBar.classList.contains('expanded')) {
                        searchInput.value = '';
                        collapseSearch();
                    }
                });

                // Real-time live search filter
                const handleSearch = () => {
                    const query = searchInput.value.toLowerCase().trim();

                    if (query.length > 0) {
                        if (clearBtn) clearBtn.classList.add('visible');
                    } else {
                        if (clearBtn) clearBtn.classList.remove('visible');
                        // Kembalikan ke tab kategori aktif
                        applyTabCategory(currentActiveCategory);
                        allFaqItems.forEach(item => {
                            item.style.display = '';
                        });
                        return;
                    }

                    let totalMatches = 0;

                    categoryGroups.forEach(group => {
                        const items = group.querySelectorAll('.faq-item');
                        let groupMatches = 0;

                        items.forEach(item => {
                            const questionText = item.querySelector('.faq-accordion-header span')?.textContent.toLowerCase() || '';
                            const answerText = item.querySelector('.faq-grid-content')?.textContent.toLowerCase() || '';

                            if (questionText.includes(query) || answerText.includes(query)) {
                                item.style.display = '';
                                groupMatches++;
                                totalMatches++;

                                if (query.length >= 2) {
                                    item.classList.add('is-open');
                                    item.querySelector('.faq-accordion-header')?.setAttribute('aria-expanded', 'true');
                                }
                            } else {
                                item.style.display = 'none';
                                item.classList.remove('is-open');
                                item.querySelector('.faq-accordion-header')?.setAttribute('aria-expanded', 'false');
                            }
                        });

                        if (groupMatches > 0) {
                            group.style.display = 'block';
                            group.style.opacity = '1';
                            group.style.transform = 'none';
                        } else {
                            group.style.display = 'none';
                        }
                    });

                    if (noResults) {
                        noResults.classList.toggle('hidden', totalMatches > 0);
                    }
                };

                searchInput.addEventListener('input', handleSearch);

                if (clearBtn) {
                    clearBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        searchInput.value = '';
                        handleSearch();
                        searchInput.focus();
                    });
                }

                if (searchIconBtn) {
                    searchIconBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        handleSearch();
                        const firstMatch = document.querySelector('.faq-item:not([style*="display: none"])');
                        if (firstMatch) {
                            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }

                if (resetSearchBtn) {
                    resetSearchBtn.addEventListener('click', function () {
                        searchInput.value = '';
                        collapseSearch();
                        applyTabCategory('all');
                    });
                }
            }

            // -------------------------------------------------------------
            // D. MOBILE NAVBAR MENU TOGGLE
            // -------------------------------------------------------------
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            if (mobileNavToggle && mobileNavOverlay) {
                const mobileNavLinks = mobileNavOverlay.querySelectorAll('.mobile-nav-link, .mobile-btn-signup');

                function toggleMenu() {
                    const isActive = mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active');
                    mobileNavToggle.setAttribute('aria-expanded', isActive);
                    mobileNavOverlay.setAttribute('aria-hidden', !isActive);
                    document.body.style.overflow = isActive ? 'hidden' : '';
                }

                mobileNavToggle.addEventListener('click', toggleMenu);

                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        mobileNavToggle.classList.remove('active');
                        mobileNavOverlay.classList.remove('active');
                        mobileNavToggle.setAttribute('aria-expanded', 'false');
                        mobileNavOverlay.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    });
                });
            }

            // -------------------------------------------------------------
            // E. SCRAMBLE TEXT EFFECT FOR NAVBAR LINKS
            // -------------------------------------------------------------
            const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const scrambleLinks = document.querySelectorAll('.scramble-link');

            scrambleLinks.forEach(link => {
                if (!link.dataset.value) {
                    link.dataset.value = link.innerText.trim();
                }

                let timeoutId = null;

                link.addEventListener('mouseenter', () => {
                    const originalText = link.dataset.value;
                    if (!originalText) return;

                    clearTimeout(timeoutId);

                    link.style.whiteSpace = 'nowrap';
                    link.style.display = 'inline-block';

                    link.innerHTML = '';
                    const tempSpans = [];
                    originalText.split('').forEach(char => {
                        const s = document.createElement('span');
                        s.style.display = 'inline-block';
                        s.style.whiteSpace = 'nowrap';
                        s.style.visibility = 'hidden';
                        s.textContent = char === ' ' ? '\u00A0' : char;
                        link.appendChild(s);
                        tempSpans.push({ span: s, char });
                    });

                    const charWidths = tempSpans.map(({ span, char }) => {
                        return { char, width: Math.ceil(span.getBoundingClientRect().width * 10) / 10 };
                    });

                    link.innerHTML = '';
                    const charBoxes = [];

                    charWidths.forEach(({ char, width }, i) => {
                        if (char === ' ') {
                            const space = document.createElement('span');
                            space.style.display = 'inline-block';
                            space.style.whiteSpace = 'nowrap';
                            space.style.width = `${width}px`;
                            space.innerHTML = '&nbsp;';
                            link.appendChild(space);
                            return;
                        }

                        const box = document.createElement('span');
                        box.className = 'scramble-char-box';
                        box.style.display = 'inline-block';
                        box.style.overflow = 'hidden';
                        box.style.whiteSpace = 'nowrap';
                        box.style.width = `${width}px`;
                        box.style.height = '1.3em';
                        box.style.lineHeight = '1.3em';
                        box.style.verticalAlign = 'top';
                        box.style.textAlign = 'center';

                        const col = document.createElement('span');
                        col.className = 'scramble-char-col';
                        col.style.display = 'flex';
                        col.style.flexDirection = 'column';
                        col.style.width = '100%';
                        col.style.whiteSpace = 'nowrap';

                        const r1 = letters[Math.floor(Math.random() * letters.length)];
                        const r2 = letters[Math.floor(Math.random() * letters.length)];
                        const r3 = letters[Math.floor(Math.random() * letters.length)];

                        const charList = [char, r1, r2, r3, char];

                        charList.forEach(c => {
                            const item = document.createElement('span');
                            item.className = 'scramble-char-item';
                            item.style.display = 'block';
                            item.style.height = '1.3em';
                            item.style.lineHeight = '1.3em';
                            item.style.width = '100%';
                            item.style.textAlign = 'center';
                            item.style.whiteSpace = 'nowrap';
                            item.textContent = c;
                            col.appendChild(item);
                        });

                        col.style.transform = 'translateY(-80%)';
                        box.appendChild(col);
                        link.appendChild(box);
                        charBoxes.push({ col, index: i });
                    });

                    void link.offsetWidth;

                    charBoxes.forEach(({ col, index }) => {
                        col.style.transition = `transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) ${index * 35}ms`;
                        col.style.transform = 'translateY(0%)';
                    });

                    const totalDuration = 450 + (originalText.length * 35) + 50;
                    timeoutId = setTimeout(() => {
                        link.innerText = originalText;
                        link.style.whiteSpace = '';
                    }, totalDuration);
                });
            });
})();
