import { createTimeline, stagger, splitText } from 'https://esm.sh/animejs@4.5.0';

        document.addEventListener('DOMContentLoaded', () => {
            // Navbar Hide/Show on Scroll using AnimeJS
            const navbarWrapper = document.getElementById('navbarWrapper');
            if (navbarWrapper) {
                let lastScrollY = window.scrollY;
                let isNavbarVisible = true;

                window.addEventListener('scroll', () => {
                    const currentScrollY = window.scrollY;

                    // Compact styling check
                    if (currentScrollY > 20) {
                        navbarWrapper.classList.add('scrolled');
                    } else {
                        navbarWrapper.classList.remove('scrolled');
                    }

                    // Hide when scrolling down past 150px
                    if (currentScrollY > lastScrollY && currentScrollY > 150) {
                        if (isNavbarVisible) {
                            isNavbarVisible = false;
                            createTimeline().add(navbarWrapper, {
                                y: -150,
                                opacity: 0,
                                duration: 400,
                                ease: 'outQuad'
                            });
                        }
                    }
                    // Show when scrolling up
                    else if (currentScrollY < lastScrollY) {
                        if (!isNavbarVisible) {
                            isNavbarVisible = true;
                            createTimeline().add(navbarWrapper, {
                                y: 0,
                                opacity: 1,
                                duration: 400,
                                ease: 'outQuad'
                            });
                        }
                    }
                    lastScrollY = currentScrollY;
                });
            }

            // Rotating Text Animation for "Not just another link-in-bio"
            const changingTitle = document.getElementById('changing-title');
            if (changingTitle) {
                const phrases = [
                    "Not just another link-in-bio",
                    "Your ultimate creator toolkit",
                    "Monetize your audience easily",
                    "All your links in one place"
                ];
                let phraseIndex = 0;

                function animateChangingText() {
                    changingTitle.style.opacity = '0';
                    changingTitle.innerHTML = phrases[phraseIndex];
                    const split = splitText(changingTitle, {
                        lines: false,
                        words: false,
                        chars: { wrap: true }
                    });

                    const charElements = changingTitle.querySelectorAll('span');
                    charElements.forEach(el => {
                        el.style.display = 'inline-block';
                        if (el.textContent === ' ' || el.innerHTML === ' ') {
                            el.innerHTML = '&nbsp;';
                        }
                    });

                    changingTitle.style.opacity = '1';

                    const tl = createTimeline({
                        onComplete: () => {
                            phraseIndex = (phraseIndex + 1) % phrases.length;
                            animateChangingText(); // Seamless transition to next word
                        }
                    });

                    tl.add(split.chars, {
                        y: ['25px', '0px'],
                        opacity: [0, 1],
                        duration: 800,
                        ease: 'outQuad',
                        delay: stagger(30)
                    })
                    .add(split.chars, {
                        y: ['0px', '-25px'],
                        opacity: [1, 0],
                        duration: 500,
                        ease: 'inQuad',
                        delay: stagger(20, { from: 'first' })
                    }, '+=2500'); // Delay reading time (2.5 seconds)
                }

                // Start the rotation
                setTimeout(animateChangingText, 1000);
            }


            document.fonts.ready.then(() => {
                const heroTitle = document.querySelector('.hero-title');
                if (heroTitle) {
                    const split = splitText(heroTitle, {
                        lines: false,
                        words: true,
                        chars: {
                            class: 'char-inner',
                            wrap: 'clip',
                            clone: 'bottom'
                        },
                    });

                    split.addEffect(({ chars }) => {
                        return createTimeline()
                        .add(chars, {
                            y: '-100%',
                            loop: true,
                            loopDelay: 350,
                            duration: 750,
                            ease: 'inOut(2)',
                        }, stagger(150, { from: 'center' }));
                    });
                }

                // AnimeJS event listener for showcase tags
                window.addEventListener('showcase-revealed', () => {
                    const tags = document.querySelectorAll('.anime-tag');
                    if (tags.length > 0) {
                        const isMobile = window.innerWidth <= 768;
                        const duration = isMobile ? 700 : 900;
                        const ease = isMobile ? 'outCubic' : 'outBack(1.2)';
                        const stag = isMobile ? 80 : 120;

                        createTimeline()
                        .add(tags, {
                            opacity: [0, 1],
                            scale: [0.6, 1],
                            x: (el) => isMobile ? [0, 0] : [parseFloat(el.dataset.dx || 0), 0],
                            y: (el) => isMobile ? [20, 0] : [parseFloat(el.dataset.dy || 0), 0],
                            duration: duration,
                            ease: ease
                        }, stagger(stag));

                        // Add active class after animation so hover/floating works smoothly
                        setTimeout(() => {
                            tags.forEach(tag => {
                                tag.style.transform = '';
                                tag.classList.add('active');
                            });
                        }, duration + (tags.length * stag));
                    }
                });
            });
        });
