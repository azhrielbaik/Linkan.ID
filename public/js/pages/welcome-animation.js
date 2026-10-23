import { createTimeline, stagger, splitText } from 'https://esm.sh/animejs@4.5.0';

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // ============================================================
    // 1. KINETIC VERTICAL FADE IN/OUT LOOPING TEXT ANIMATION
    // ============================================================
    const motionHeader = document.getElementById('featuresMotionHeader');
    const looperStage = document.getElementById('verticalLooperStage');
    const looperItems = document.querySelectorAll('.vertical-looper-item');
    const ambientGlow = document.querySelector('.kinetic-ambient-glow');

    if (motionHeader && looperStage && looperItems.length > 0) {
        if (prefersReducedMotion) {
            looperItems.forEach((item, idx) => {
                if (idx === 0) {
                    item.classList.add('is-active');
                    item.style.opacity = '1';
                    item.style.transform = 'none';
                    item.style.filter = 'none';
                } else {
                    item.style.display = 'none';
                }
            });
        } else {
            // Initial state: Item 0 is active, others prepared below
            looperItems.forEach((item, idx) => {
                if (idx === 0) {
                    item.classList.add('is-active');
                } else {
                    item.classList.remove('is-active', 'is-exiting');
                }
            });

            let currentIndex = 0;
            let loopTimer = null;
            let isVisible = false;

            const transitionToNext = () => {
                if (!isVisible || looperItems.length < 2) return;

                const currentItem = looperItems[currentIndex];
                const nextIndex = (currentIndex + 1) % looperItems.length;
                const nextItem = looperItems[nextIndex];

                // 1. Slide and fade out current item upwards
                currentItem.classList.remove('is-active');
                currentItem.classList.add('is-exiting');
                currentItem.setAttribute('aria-hidden', 'true');

                // 2. Prepare next item from below
                nextItem.classList.remove('is-exiting');

                // 3. Slide and fade in next item to center asynchronously to prevent layout thrashing
                requestAnimationFrame(() => {
                    nextItem.classList.add('is-active');
                    nextItem.setAttribute('aria-hidden', 'false');
                });

                // 4. After transition ends, clean up the exited item so it resets to below
                setTimeout(() => {
                    currentItem.classList.remove('is-exiting');
                }, 700);

                currentIndex = nextIndex;
                loopTimer = setTimeout(transitionToNext, 3000);
            };

            const headerObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    isVisible = entry.isIntersecting;
                    if (isVisible) {
                        clearTimeout(loopTimer);
                        loopTimer = setTimeout(transitionToNext, 2600);
                    } else {
                        clearTimeout(loopTimer);
                    }
                });
            }, { threshold: 0.15 });

            headerObserver.observe(motionHeader);

            // Desktop pointer interactions: subtle ambient glow parallax
            if (isFinePointer && ambientGlow) {
                motionHeader.addEventListener('mousemove', (e) => {
                    const rect = motionHeader.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;
                    ambientGlow.style.transform = `translate(calc(-50% + ${x * 35}px), calc(-50% + ${y * 25}px)) scale(1.08)`;
                }, { passive: true });

                motionHeader.addEventListener('mouseleave', () => {
                    ambientGlow.style.transform = '';
                });
            }
        }
    }

    // ============================================================
    // 2. SPOTLIGHT EFFECT (FINE POINTER ONLY)
    // ============================================================
    const spotlightEl = document.getElementById('spotlightText');
    if (spotlightEl && isFinePointer) {
        spotlightEl.addEventListener('mousemove', (e) => {
            const rect = spotlightEl.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            spotlightEl.style.setProperty('--x', x + 'px');
            spotlightEl.style.setProperty('--y', y + 'px');
        }, { passive: true });

        spotlightEl.addEventListener('mouseenter', () => {
            spotlightEl.style.setProperty('--radius', '150px');
        });

        spotlightEl.addEventListener('mouseleave', () => {
            spotlightEl.style.setProperty('--radius', '0px');
        });
    }

    // ============================================================
    // 3. SHOWCASE TAGS ENTRANCE ANIMATION
    // ============================================================
    document.fonts.ready.then(() => {
        window.addEventListener('showcase-revealed', () => {
            const tags = document.querySelectorAll('.anime-tag');
            if (tags.length === 0) return;

            if (prefersReducedMotion) {
                tags.forEach((tag) => {
                    tag.style.opacity = '1';
                    tag.style.transform = '';
                    tag.classList.add('active');
                });
                return;
            }

            const isMobile = window.matchMedia('(max-width: 639px)').matches;
            const duration = isMobile ? 600 : 900;
            const ease = isMobile ? 'outCubic' : 'outBack(1.2)';
            const stag = isMobile ? 60 : 120;

            createTimeline()
                .add(tags, {
                    opacity: [0, 1],
                    scale: [0.7, 1],
                    x: (el) => isMobile ? [0, 0] : [parseFloat(el.dataset.dx || 0), 0],
                    y: (el) => isMobile ? [16, 0] : [parseFloat(el.dataset.dy || 0), 0],
                    duration: duration,
                    ease: ease
                }, stagger(stag));

            setTimeout(() => {
                tags.forEach((tag) => {
                    tag.style.transform = '';
                    tag.classList.add('active');
                });
            }, duration + (tags.length * stag));
        });
    });
});
