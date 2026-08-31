/**
 * LINKAN.ID — PLATFORM ADMIN THEME & DARK MODE MANAGER
 * Features: Zero-flash load, Instant live preview, LocalStorage cache, AJAX DB persistence.
 */

(function () {
    // Utility: Hex to RGB
    function hexToRgb(hex) {
        let c = hex.replace(/^#/, '');
        if (c.length === 3) {
            c = c.split('').map(x => x + x).join('');
        }
        const num = parseInt(c, 16);
        return {
            r: (num >> 16) & 255,
            g: (num >> 8) & 255,
            b: num & 255
        };
    }

    // Utility: Darken hex color for hover state
    function shadeColor(color, percent) {
        let { r, g, b } = hexToRgb(color);
        r = Math.floor(r * (100 + percent) / 100);
        g = Math.floor(g * (100 + percent) / 100);
        b = Math.floor(b * (100 + percent) / 100);
        r = r < 255 ? (r < 0 ? 0 : r) : 255;
        g = g < 255 ? (g < 0 ? 0 : g) : 255;
        b = b < 255 ? (b < 0 ? 0 : b) : 255;
        const RR = r.toString(16).padStart(2, '0');
        const GG = g.toString(16).padStart(2, '0');
        const BB = b.toString(16).padStart(2, '0');
        return `#${RR}${GG}${BB}`;
    }

    // Apply color CSS variables to document
    window.applyThemeColorLive = function (color) {
        if (!color || !/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/.test(color)) return;
        const rgb = hexToRgb(color);
        const hover = shadeColor(color, -12);

        document.documentElement.style.setProperty('--p-primary', color);
        document.documentElement.style.setProperty('--p-primary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
        document.documentElement.style.setProperty('--p-primary-hover', hover);
        document.documentElement.style.setProperty('--p-primary-subtle', `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.14)`);
        document.documentElement.style.setProperty('--p-primary-border', `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.28)`);
        document.documentElement.style.setProperty('--p-primary-glow', `0 4px 16px rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.25)`);
    };

    // Apply Theme Mode (light/dark)
    window.applyThemeMode = function (mode) {
        const isDark = mode === 'dark';
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
        if (isDark) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }

        // Update mode buttons UI
        const btnLight = document.getElementById('btnThemeLight');
        const btnDark = document.getElementById('btnThemeDark');
        if (btnLight && btnDark) {
            btnLight.classList.toggle('active', !isDark);
            btnDark.classList.toggle('active', isDark);
        }
    };

    // Update active swatch state
    window.updateActiveSwatch = function (color) {
        const swatches = document.querySelectorAll('.ptd-swatch');
        let matched = false;
        swatches.forEach(swatch => {
            const swatchColor = swatch.getAttribute('data-color');
            if (swatchColor && swatchColor.toLowerCase() === color.toLowerCase()) {
                swatch.classList.add('active');
                matched = true;
            } else {
                swatch.classList.remove('active');
            }
        });

        const customInput = document.getElementById('ptdCustomColorInput');
        if (customInput) {
            customInput.value = color;
        }
    };

    // Flash Save Indicator
    let saveTimeout = null;
    function showSaveIndicator() {
        const indicator = document.getElementById('themeSaveStatus');
        if (!indicator) return;
        indicator.classList.add('show');
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            indicator.classList.remove('show');
        }, 2000);
    }

    // Persist to Server via AJAX
    function saveThemeToServer(payload) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value;

        fetch('/platform-admin/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSaveIndicator();
            }
        })
        .catch(err => {
            console.warn('Theme preference saved locally. Server sync error:', err);
        });
    }

    // User Action: Set Mode
    window.setPlatformThemeMode = function (mode) {
        applyThemeMode(mode);
        localStorage.setItem('platform_theme_mode', mode);
        saveThemeToServer({ theme: mode });
    };

    // User Action: Set Color
    window.setPlatformThemeColor = function (color) {
        applyThemeColorLive(color);
        updateActiveSwatch(color);
        localStorage.setItem('platform_theme_color', color);
        saveThemeToServer({ theme_color: color });
    };

    // Toggle Dropdown Menu
    window.togglePlatformThemeDropdown = function (e) {
        if (e) e.stopPropagation();
        const dropdown = document.getElementById('platformThemeDropdown');
        const wrapper = document.getElementById('platformProfileDropdownWrapper');
        if (!dropdown || !wrapper) return;

        const isShowing = dropdown.classList.contains('show');
        if (isShowing) {
            dropdown.classList.remove('show');
            wrapper.classList.remove('active');
        } else {
            dropdown.classList.add('show');
            wrapper.classList.add('active');
        }
    };

    // Close on click outside
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('platformThemeDropdown');
        const wrapper = document.getElementById('platformProfileDropdownWrapper');
        if (dropdown && dropdown.classList.contains('show')) {
            if (!dropdown.contains(e.target) && !wrapper.contains(e.target)) {
                dropdown.classList.remove('show');
                wrapper.classList.remove('active');
            }
        }
    });

    // Close on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('platformThemeDropdown');
            const wrapper = document.getElementById('platformProfileDropdownWrapper');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                wrapper.classList.remove('active');
            }
        }
    });

    // Initialize on page load
    function initPlatformTheme() {
        const savedMode = localStorage.getItem('platform_theme_mode')
            || document.documentElement.getAttribute('data-theme')
            || 'light';

        const savedColor = localStorage.getItem('platform_theme_color')
            || document.documentElement.getAttribute('data-theme-color')
            || '#ed842c';

        applyThemeMode(savedMode);
        applyThemeColorLive(savedColor);
        updateActiveSwatch(savedColor);
    }

    // Execute immediately and on DOMContentLoaded
    initPlatformTheme();
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPlatformTheme);
    }
})();
