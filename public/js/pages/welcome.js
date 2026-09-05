document.addEventListener("DOMContentLoaded", function () {
    const reveals = document.querySelectorAll(".reveal, .reveal-scale");

    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px",
    };

    function handleShowcaseReveal(reveal) {
        if (
            reveal.classList.contains("creator-showcase-section") &&
            !reveal.dataset.revealed
        ) {
            reveal.dataset.revealed = "true";
            const container = reveal.querySelector(".showcase-container");
            const tags = reveal.querySelectorAll(".anime-tag");
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
            window.dispatchEvent(new CustomEvent("showcase-revealed"));
        }
    }

    const revealOnScroll = new IntersectionObserver(function (
        entries,
        observer,
    ) {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add("active");
                handleShowcaseReveal(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    reveals.forEach((reveal) => {
        revealOnScroll.observe(reveal);
    });

    // Trigger immediately for elements already in viewport on load
    setTimeout(() => {
        reveals.forEach((reveal) => {
            const rect = reveal.getBoundingClientRect();
            if (rect.top < window.innerHeight) {
                reveal.classList.add("active");
                handleShowcaseReveal(reveal);
            }
        });
    }, 100);

    // Typing animation for placeholder in claim input
    const claimInput = document.querySelector(".claim-input");
    if (claimInput) {
        const words = [
            "YourNameHere",
            "kreator",
            "bisnisanda",
            "portofolio",
            "toko-online",
            "content_creator",
        ];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingDelay = 150;
        let erasingDelay = 100;
        let newWordDelay = 2000;

        function type() {
            const currentWord = words[wordIndex];
            let currentDelay = typingDelay;

            if (isDeleting) {
                claimInput.setAttribute(
                    "placeholder",
                    currentWord.substring(0, charIndex),
                );
                charIndex--;
                currentDelay = erasingDelay;
            } else {
                claimInput.setAttribute(
                    "placeholder",
                    currentWord.substring(0, charIndex),
                );
                charIndex++;
                currentDelay = typingDelay;
            }

            if (!isDeleting && charIndex > currentWord.length) {
                isDeleting = true;
                currentDelay = newWordDelay;
            } else if (isDeleting && charIndex < 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                charIndex = 0;
                currentDelay = 500;
            }

            setTimeout(type, currentDelay);
        }

        setTimeout(type, 1000);
    }

    // Mobile menu toggle logic
    const mobileNavToggle = document.getElementById("mobileNavToggle");
    const mobileNavOverlay = document.getElementById("mobileNavOverlay");
    if (mobileNavToggle && mobileNavOverlay) {
        const mobileNavLinks =
            mobileNavOverlay.querySelectorAll(".mobile-nav-link");

        function toggleMenu() {
            const isActive = mobileNavToggle.classList.toggle("active");
            mobileNavOverlay.classList.toggle("active");
            mobileNavToggle.setAttribute("aria-expanded", isActive);
            mobileNavOverlay.setAttribute("aria-hidden", !isActive);
            if (isActive) {
                document.body.style.overflow = "hidden";
            } else {
                document.body.style.overflow = "";
            }
        }

        mobileNavToggle.addEventListener("click", toggleMenu);

        mobileNavLinks.forEach((link) => {
            link.addEventListener("click", () => {
                mobileNavToggle.classList.remove("active");
                mobileNavOverlay.classList.remove("active");
                mobileNavToggle.setAttribute("aria-expanded", "false");
                mobileNavOverlay.setAttribute("aria-hidden", "true");
                document.body.style.overflow = "";
            });
        });
    }

    // Origin-aware interactive magnetic circle buttons
    const originBtns = document.querySelectorAll(".origin-btn");
    originBtns.forEach((btn) => {
        const updateCoords = (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            btn.style.setProperty("--x", `${x}px`);
            btn.style.setProperty("--y", `${y}px`);
        };

        btn.addEventListener("mouseenter", updateCoords);
        btn.addEventListener("mousemove", updateCoords);
    });

    // Vertical Top-to-Bottom Scramble Text Effect on Navigation Links (Fixed Widths, No Layout Shift, No Drop)
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const scrambleLinks = document.querySelectorAll(".scramble-link");

    scrambleLinks.forEach((link) => {
        if (!link.dataset.value) {
            link.dataset.value = link.innerText.trim();
        }

        let timeoutId = null;

        link.addEventListener("mouseenter", () => {
            const originalText = link.dataset.value;
            if (!originalText) return;

            clearTimeout(timeoutId);

            // Ensure link never drops or wraps
            link.style.whiteSpace = "nowrap";
            link.style.display = "inline-block";

            // Measure original letter widths
            link.innerHTML = "";
            const tempSpans = [];
            originalText.split("").forEach((char) => {
                const s = document.createElement("span");
                s.style.display = "inline-block";
                s.style.whiteSpace = "nowrap";
                s.style.visibility = "hidden";
                s.textContent = char === " " ? "\u00A0" : char;
                link.appendChild(s);
                tempSpans.push({ span: s, char });
            });

            const charWidths = tempSpans.map(({ span, char }) => {
                return {
                    char,
                    width:
                        Math.ceil(span.getBoundingClientRect().width * 10) / 10,
                };
            });

            // Build character boxes with locked widths
            link.innerHTML = "";
            const charBoxes = [];

            charWidths.forEach(({ char, width }, i) => {
                if (char === " ") {
                    const space = document.createElement("span");
                    space.style.display = "inline-block";
                    space.style.whiteSpace = "nowrap";
                    space.style.width = `${width}px`;
                    space.innerHTML = "&nbsp;";
                    link.appendChild(space);
                    return;
                }

                const box = document.createElement("span");
                box.className = "scramble-char-box";
                box.style.display = "inline-block";
                box.style.overflow = "hidden";
                box.style.whiteSpace = "nowrap";
                box.style.width = `${width}px`;
                box.style.height = "1.3em";
                box.style.lineHeight = "1.3em";
                box.style.verticalAlign = "top";
                box.style.textAlign = "center";

                const col = document.createElement("span");
                col.className = "scramble-char-col";
                col.style.display = "flex";
                col.style.flexDirection = "column";
                col.style.width = "100%";
                col.style.whiteSpace = "nowrap";

                const r1 = letters[Math.floor(Math.random() * letters.length)];
                const r2 = letters[Math.floor(Math.random() * letters.length)];
                const r3 = letters[Math.floor(Math.random() * letters.length)];

                // Items from top to bottom: [Target char, Random 1, Random 2, Random 3, Start char]
                const charList = [char, r1, r2, r3, char];

                charList.forEach((c) => {
                    const item = document.createElement("span");
                    item.className = "scramble-char-item";
                    item.style.display = "block";
                    item.style.height = "1.3em";
                    item.style.lineHeight = "1.3em";
                    item.style.width = "100%";
                    item.style.textAlign = "center";
                    item.style.whiteSpace = "nowrap";
                    item.textContent = c;
                    col.appendChild(item);
                });

                col.style.transform = "translateY(-80%)";
                box.appendChild(col);
                link.appendChild(box);
                charBoxes.push({ col, index: i });
            });

            // Force browser reflow
            void link.offsetWidth;

            // Animate downward (atas ke bawah) with staggered easing
            charBoxes.forEach(({ col, index }) => {
                col.style.transition = `transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) ${index * 35}ms`;
                col.style.transform = "translateY(0%)";
            });

            const totalDuration = 450 + originalText.length * 35 + 50;
            timeoutId = setTimeout(() => {
                link.innerText = originalText;
                link.style.whiteSpace = "";
            }, totalDuration);
        });
    });
});
