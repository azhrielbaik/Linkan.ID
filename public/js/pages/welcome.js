/**
 * Linkan.ID — Welcome Landing Page Script
 * Mobile-First Performance & Interaction Controller
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouchOnly = !window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // ============================================================
    // 1. REVEAL ANIMATIONS (Progressive Enhancement)
    // ============================================================
    const reveals = document.querySelectorAll('.reveal, .reveal-scale');
    if (reveals.length > 0) {
        const revealOptions = {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        };

        function handleShowcaseReveal(reveal) {
            if (reveal.classList.contains('creator-showcase-section') && !reveal.dataset.revealed) {
                reveal.dataset.revealed = 'true';
                const container = reveal.querySelector('.showcase-container');
                const tags = reveal.querySelectorAll('.anime-tag');
                if (container && tags.length > 0) {
                    const cRect = container.getBoundingClientRect();
                    const cx = cRect.width / 2;
                    const cy = cRect.height / 2;

                    tags.forEach((tag) => {
                        const ex = tag.offsetLeft + tag.offsetWidth / 2;
                        const ey = tag.offsetTop + tag.offsetHeight / 2;
                        tag.dataset.dx = cx - ex;
                        tag.dataset.dy = cy - ey;
                    });
                }
                window.dispatchEvent(new CustomEvent('showcase-revealed'));
            }
        }

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    handleShowcaseReveal(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, revealOptions);

        reveals.forEach((reveal) => revealObserver.observe(reveal));

        // Quick fallback for above-the-fold or already visible elements
        setTimeout(() => {
            reveals.forEach((reveal) => {
                const rect = reveal.getBoundingClientRect();
                if (rect.top < window.innerHeight) {
                    reveal.classList.add('active');
                    handleShowcaseReveal(reveal);
                }
            });
        }, 100);
    }

    // ============================================================
    // 2. NAVBAR SCROLL CONTROLLER (rAF Throttled, No Jank)
    // ============================================================
    const navbarWrapper = document.getElementById('navbarWrapper');
    if (navbarWrapper) {
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateNavbar() {
            const currentScrollY = window.scrollY;
            const menuOpen = document.getElementById('mobileNavOverlay')?.classList.contains('active');

            // Scrolled glass effect
            if (currentScrollY > 20) {
                navbarWrapper.classList.add('scrolled');
            } else {
                navbarWrapper.classList.remove('scrolled');
            }

            // Hide/Show logic with delta threshold
            if (!menuOpen) {
                const delta = currentScrollY - lastScrollY;
                if (delta > 8 && currentScrollY > 150) {
                    navbarWrapper.classList.add('nav-hidden');
                } else if (delta < -8 || currentScrollY <= 150) {
                    navbarWrapper.classList.remove('nav-hidden');
                }
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });
    }

    // ============================================================
    // 3. ACCESSIBLE MOBILE MENU (iOS Scroll-Lock, Focus Trap, Escape)
    // ============================================================
    const mobileNavToggle = document.getElementById('mobileNavToggle');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mainEl = document.querySelector('main');
    const footerEl = document.querySelector('footer');

    if (mobileNavToggle && mobileNavOverlay) {
        let savedScrollY = 0;
        let isMenuOpen = false;

        function lockScroll() {
            savedScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + savedScrollY + 'px';
            document.body.style.width = '100%';
            document.body.style.overflow = 'hidden';
        }

        function unlockScroll() {
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflow = '';
            window.scrollTo(0, savedScrollY);
        }

        function openMenu() {
            if (isMenuOpen) return;
            isMenuOpen = true;
            lockScroll();

            mobileNavToggle.classList.add('active');
            mobileNavOverlay.classList.add('active');

            mobileNavToggle.setAttribute('aria-expanded', 'true');
            mobileNavOverlay.setAttribute('aria-hidden', 'false');

            if (mainEl) mainEl.setAttribute('inert', '');
            if (footerEl) footerEl.setAttribute('inert', '');

            // Hide sticky CTA while menu is open
            const stickyCta = document.getElementById('mobileStickyCta');
            if (stickyCta) stickyCta.classList.remove('visible');

            // Focus on first link
            setTimeout(() => {
                const firstLink = mobileNavOverlay.querySelector('a, button');
                if (firstLink) firstLink.focus();
            }, 100);
        }

        function closeMenu() {
            if (!isMenuOpen) return;
            isMenuOpen = false;
            unlockScroll();

            mobileNavToggle.classList.remove('active');
            mobileNavOverlay.classList.remove('active');

            mobileNavToggle.setAttribute('aria-expanded', 'false');
            mobileNavOverlay.setAttribute('aria-hidden', 'true');

            if (mainEl) mainEl.removeAttribute('inert');
            if (footerEl) footerEl.removeAttribute('inert');

            // Return focus to toggle button
            mobileNavToggle.focus();
        }

        mobileNavToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isMenuOpen) closeMenu();
            else openMenu();
        });

        // Close on clicking outside the menu container
        mobileNavOverlay.addEventListener('click', (e) => {
            if (e.target === mobileNavOverlay) {
                closeMenu();
            }
        });

        // Close on all menu links & action buttons
        mobileNavOverlay.querySelectorAll('a, button').forEach((clickable) => {
            clickable.addEventListener('click', () => {
                closeMenu();
            });
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMenu();
            }
        });

        // Focus trap inside modal
        mobileNavOverlay.addEventListener('keydown', (e) => {
            if (!isMenuOpen || e.key !== 'Tab') return;

            const focusables = mobileNavOverlay.querySelectorAll(
                'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            if (focusables.length === 0) return;

            const firstEl = focusables[0];
            const lastEl = focusables[focusables.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstEl) {
                    e.preventDefault();
                    lastEl.focus();
                }
            } else {
                if (document.activeElement === lastEl) {
                    e.preventDefault();
                    firstEl.focus();
                }
            }
        });

        // Close menu if viewport expanded to desktop
        const desktopQuery = window.matchMedia('(min-width: 1024px)');
        desktopQuery.addEventListener('change', (e) => {
            if (e.matches && isMenuOpen) {
                closeMenu();
            }
        });
    }

    // ============================================================
    // 4. CLAIM INPUT TYPING ANIMATION (Focus-Aware, Pausable)
    // ============================================================
    const claimInput = document.querySelector('.claim-input');
    if (claimInput && !prefersReducedMotion) {
        const words = [
            'YourNameHere',
            'kreator',
            'bisnisanda',
            'portofolio',
            'toko-online',
            'content_creator',
        ];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingTimeout = null;
        let isUserTyping = false;
        let isFocused = false;

        function runTyping() {
            if (isUserTyping || isFocused) return;

            const currentWord = words[wordIndex];
            let delay = isDeleting ? 90 : 130;

            if (isDeleting) {
                claimInput.setAttribute('placeholder', currentWord.substring(0, charIndex));
                charIndex--;
            } else {
                claimInput.setAttribute('placeholder', currentWord.substring(0, charIndex));
                charIndex++;
            }

            if (!isDeleting && charIndex > currentWord.length) {
                isDeleting = true;
                delay = 2000; // Pause on full word
            } else if (isDeleting && charIndex < 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                charIndex = 0;
                delay = 400;
            }

            typingTimeout = setTimeout(runTyping, delay);
        }

        claimInput.addEventListener('focus', () => {
            isFocused = true;
            clearTimeout(typingTimeout);
        });

        claimInput.addEventListener('blur', () => {
            isFocused = false;
            if (!isUserTyping && claimInput.value.length === 0) {
                typingTimeout = setTimeout(runTyping, 600);
            }
        });

        claimInput.addEventListener('input', () => {
            if (claimInput.value.length > 0) {
                isUserTyping = true;
                clearTimeout(typingTimeout);
            } else {
                isUserTyping = false;
            }
        });

        typingTimeout = setTimeout(runTyping, 1200);
    }

    // ============================================================
    // 5. PRICING MOBILE CAROUSEL CONTROLLER (Option A)
    // ============================================================
    const pricingGrid = document.querySelector('.pricing-grid');
    const pricingDotsContainer = document.getElementById('pricingDots');
    const pricingSwipeHint = document.getElementById('pricingSwipeHint');

    if (pricingGrid && pricingDotsContainer) {
        const pricingCards = pricingGrid.querySelectorAll('.pricing-card');
        const dots = pricingDotsContainer.querySelectorAll('.pricing-dot');

        // Auto-center "Popular" card on mobile/tablet without vertical page shift
        function centerPopularCard() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                const popularCard = pricingGrid.querySelector('.pricing-card.popular') || pricingCards[1];
                if (popularCard) {
                    const scrollTarget = popularCard.offsetLeft - (pricingGrid.clientWidth - popularCard.clientWidth) / 2;
                    pricingGrid.scrollLeft = Math.max(0, scrollTarget);
                }
            }
        }

        // Run after initial paint
        requestAnimationFrame(() => {
            setTimeout(centerPopularCard, 100);
        });

        // IntersectionObserver to update active dot
        if (pricingCards.length > 0 && dots.length > 0) {
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const index = Array.from(pricingCards).indexOf(entry.target);
                        if (index !== -1) {
                            dots.forEach((dot, dIdx) => {
                                const isActive = dIdx === index;
                                dot.classList.toggle('active', isActive);
                                dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                            });
                        }
                    }
                });
            }, {
                root: pricingGrid,
                threshold: 0.6
            });

            pricingCards.forEach((card) => cardObserver.observe(card));

            // Clicking dots to navigate
            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    const targetIndex = parseInt(dot.dataset.index, 10);
                    const targetCard = pricingCards[targetIndex];
                    if (targetCard) {
                        const scrollTarget = targetCard.offsetLeft - (pricingGrid.clientWidth - targetCard.clientWidth) / 2;
                        pricingGrid.scrollTo({
                            left: Math.max(0, scrollTarget),
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Hide swipe hint on user scroll
            pricingGrid.addEventListener('scroll', () => {
                if (pricingSwipeHint && !pricingSwipeHint.classList.contains('hidden')) {
                    pricingSwipeHint.classList.add('hidden');
                }
            }, { passive: true, once: true });
        }
    }

    // ============================================================
    // 6. TESTIMONIALS MOBILE CAROUSEL CONTROLLER
    // ============================================================
    const testiCarousel = document.getElementById('testiMobileCarousel');
    const testiDotsContainer = document.getElementById('testiDots') || document.getElementById('testiMobileDots');

    if (testiCarousel && testiDotsContainer) {
        const testiCards = testiCarousel.querySelectorAll('.testi-card');
        const testiDots = testiDotsContainer.querySelectorAll('.testi-dot');

        if (testiCards.length > 0 && testiDots.length > 0) {
            const testiCardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const index = Array.from(testiCards).indexOf(entry.target);
                        if (index !== -1 && index < testiDots.length) {
                            testiDots.forEach((dot, dIdx) => {
                                const isActive = dIdx === index;
                                dot.classList.toggle('active', isActive);
                                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
                                dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                            });
                        }
                    }
                });
            }, {
                root: testiCarousel,
                threshold: 0.6
            });

            testiCards.forEach((card) => testiCardObserver.observe(card));

            testiDots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    const targetIndex = parseInt(dot.dataset.index, 10);
                    const targetCard = testiCards[targetIndex];
                    if (targetCard) {
                        const scrollTarget = targetCard.offsetLeft - (testiCarousel.clientWidth - targetCard.clientWidth) / 2;
                        testiCarousel.scrollTo({
                            left: Math.max(0, scrollTarget),
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
    }

    // ============================================================
    // 7. STICKY MOBILE CTA BAR OBSERVER (<= 639px)
    // ============================================================
    const mobileStickyCta = document.getElementById('mobileStickyCta');
    const heroSection = document.querySelector('.hero-section');
    const pricingSection = document.getElementById('pricing');
    const footerWrapper = document.querySelector('.footer-wrapper');

    if (mobileStickyCta && heroSection) {
        let isHeroVisible = true;
        let isPricingOrFooterVisible = false;

        function updateStickyCtaVisibility() {
            if (!window.matchMedia('(max-width: 639px)').matches) {
                mobileStickyCta.classList.remove('visible');
                document.body.classList.remove('has-sticky-cta');
                return;
            }

            const menuOpen = document.getElementById('mobileNavOverlay')?.classList.contains('active');
            if (menuOpen) {
                mobileStickyCta.classList.remove('visible');
                return;
            }

            if (!isHeroVisible && !isPricingOrFooterVisible) {
                mobileStickyCta.classList.add('visible');
                document.body.classList.add('has-sticky-cta');
            } else {
                mobileStickyCta.classList.remove('visible');
                document.body.classList.remove('has-sticky-cta');
            }
        }

        const heroObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                isHeroVisible = entry.isIntersecting;
                updateStickyCtaVisibility();
            });
        }, { threshold: 0.1 });

        heroObserver.observe(heroSection);

        const hideObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.target === pricingSection || entry.target === footerWrapper) {
                    isPricingOrFooterVisible = entries.some(e => e.isIntersecting);
                    updateStickyCtaVisibility();
                }
            });
        }, { threshold: 0.05 });

        if (pricingSection) hideObserver.observe(pricingSection);
        if (footerWrapper) hideObserver.observe(footerWrapper);
    }

    // ============================================================
    // 8. ORIGIN-AWARE INTERACTIVE BUTTONS (Desktop Fine Pointer Only)
    // ============================================================
    if (!isTouchOnly) {
        const originBtns = document.querySelectorAll('.origin-btn');
        originBtns.forEach((btn) => {
            const updateCoords = (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                btn.style.setProperty('--x', x + 'px');
                btn.style.setProperty('--y', y + 'px');
            };

            btn.addEventListener('mouseenter', updateCoords, { passive: true });
            btn.addEventListener('mousemove', updateCoords, { passive: true });
        });
    }

    // ============================================================
    // 9. SCRAMBLE TEXT EFFECT (Hover/Fine Pointer Only)
    // ============================================================
    if (!isTouchOnly && !prefersReducedMotion) {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const scrambleLinks = document.querySelectorAll('.scramble-link');

        scrambleLinks.forEach((link) => {
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
                originalText.split('').forEach((char) => {
                    const s = document.createElement('span');
                    s.style.display = 'inline-block';
                    s.style.whiteSpace = 'nowrap';
                    s.style.visibility = 'hidden';
                    s.textContent = char === ' ' ? '\u00A0' : char;
                    link.appendChild(s);
                    tempSpans.push({ span: s, char });
                });

                const charWidths = tempSpans.map(({ span, char }) => {
                    return {
                        char: char,
                        width: Math.ceil(span.getBoundingClientRect().width * 10) / 10,
                    };
                });

                link.innerHTML = '';
                const charBoxes = [];

                charWidths.forEach(({ char, width }, i) => {
                    if (char === ' ') {
                        const space = document.createElement('span');
                        space.style.display = 'inline-block';
                        space.style.whiteSpace = 'nowrap';
                        space.style.width = width + 'px';
                        space.innerHTML = '&nbsp;';
                        link.appendChild(space);
                        return;
                    }

                    const box = document.createElement('span');
                    box.className = 'scramble-char-box';
                    box.style.display = 'inline-block';
                    box.style.overflow = 'hidden';
                    box.style.whiteSpace = 'nowrap';
                    box.style.width = width + 'px';
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

                    charList.forEach((c) => {
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
                    charBoxes.push({ col: col, index: i });
                });

                void link.offsetWidth;

                charBoxes.forEach(({ col, index }) => {
                    col.style.transition = 'transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) ' + (index * 35) + 'ms';
                    col.style.transform = 'translateY(0%)';
                });

                const totalDuration = 450 + originalText.length * 35 + 50;
                timeoutId = setTimeout(() => {
                    link.innerText = originalText;
                    link.style.whiteSpace = '';
                }, totalDuration);
            });
        });
    }

    // ============================================================
    // 10. TARGETED SMOOTH SCROLL FOR IN-PAGE ANCHORS
    // ============================================================
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetEl = document.querySelector(targetId);
                if (targetEl) {
                    e.preventDefault();
                    targetEl.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'start'
                    });
                    if (history.pushState) {
                        history.pushState(null, '', targetId);
                    }
                }
            }
        });
    });
});
