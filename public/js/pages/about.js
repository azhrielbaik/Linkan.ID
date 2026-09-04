document.addEventListener('DOMContentLoaded', () => {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');

            if (mobileNavToggle && mobileNavOverlay) {
                const closeMenu = () => {
                    mobileNavToggle.classList.remove('active');
                    mobileNavOverlay.classList.remove('active');
                    mobileNavToggle.setAttribute('aria-expanded', 'false');
                    mobileNavOverlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                };

                mobileNavToggle.addEventListener('click', () => {
                    const isActive = mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active', isActive);
                    mobileNavToggle.setAttribute('aria-expanded', isActive);
                    mobileNavOverlay.setAttribute('aria-hidden', !isActive);
                    document.body.style.overflow = isActive ? 'hidden' : '';
                });

                mobileNavOverlay.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
            }

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

                    // Ensure link never drops or wraps
                    link.style.whiteSpace = 'nowrap';
                    link.style.display = 'inline-block';

                    // Measure original letter widths
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

                    // Build character boxes with locked widths
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
        });
