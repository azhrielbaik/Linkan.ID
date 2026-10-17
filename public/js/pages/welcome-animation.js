import { createTimeline, stagger, splitText } from 'https://esm.sh/animejs@4.5.0';

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // ============================================================
    // 1. ROTATING TITLE WITH DYNAMIC I18N PHRASES
    // ============================================================
    const changingTitle = document.getElementById('changing-title');
    if (changingTitle) {
        let phrases = [
            "Not just another link-in-bio",
            "Your ultimate creator toolkit",
            "Monetize your audience easily",
            "All your links in one place"
        ];

        try {
            if (changingTitle.dataset.phrases) {
                const parsed = JSON.parse(changingTitle.dataset.phrases);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    phrases = parsed;
                }
            }
        } catch (e) {
            console.warn('Could not parse changing-title data-phrases, using fallback.', e);
        }

        if (prefersReducedMotion) {
            // Display static first phrase for users with reduced motion preference
            changingTitle.textContent = phrases[0];
            changingTitle.style.opacity = '1';
        } else {
            let phraseIndex = 0;
            let isVisible = false;
            let isRunning = false;
            let rotationTimeout = null;
            let currentTimeline = null;

            function animateChangingText() {
                if (!isVisible) {
                    isRunning = false;
                    return;
                }
                isRunning = true;

                changingTitle.style.opacity = '0';
                changingTitle.innerHTML = phrases[phraseIndex];

                const split = splitText(changingTitle, {
                    lines: false,
                    words: false,
                    chars: { wrap: true }
                });

                const charElements = changingTitle.querySelectorAll('span');
                charElements.forEach((el) => {
                    el.style.display = 'inline-block';
                    if (el.textContent === ' ' || el.innerHTML === ' ') {
                        el.innerHTML = '&nbsp;';
                    }
                });

                changingTitle.style.opacity = '1';

                currentTimeline = createTimeline({
                    onComplete: () => {
                        phraseIndex = (phraseIndex + 1) % phrases.length;
                        if (isVisible) {
                            rotationTimeout = setTimeout(animateChangingText, 50);
                        } else {
                            isRunning = false;
                        }
                    }
                });

                currentTimeline.add(split.chars, {
                    y: ['25px', '0px'],
                    opacity: [0, 1],
                    duration: 800,
                    ease: 'outQuad',
                    delay: stagger(30)
                }).add(split.chars, {
                    y: ['0px', '-25px'],
                    opacity: [1, 0],
                    duration: 500,
                    ease: 'inQuad',
                    delay: stagger(20, { from: 'first' })
                }, '+=2500');
            }

            // Pause/Resume Anime.js title rotation when entering or leaving viewport
            const titleObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    const previouslyVisible = isVisible;
                    isVisible = entry.isIntersecting;

                    if (isVisible) {
                        if (!isRunning) {
                            clearTimeout(rotationTimeout);
                            rotationTimeout = setTimeout(animateChangingText, previouslyVisible ? 50 : 300);
                        } else if (currentTimeline && typeof currentTimeline.play === 'function') {
                            currentTimeline.play();
                        }
                    } else {
                        clearTimeout(rotationTimeout);
                        if (currentTimeline && typeof currentTimeline.pause === 'function') {
                            currentTimeline.pause();
                        }
                    }
                });
            }, { threshold: 0.05 });

            titleObserver.observe(changingTitle);
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
