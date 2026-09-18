{{-- Platform Admin Clean & Modern Language Dropdown --}}
<div class="platform-lang-dropdown" id="platformLangDropdown">
    <button type="button" 
            class="lang-dropdown-btn" 
            id="platformLangBtn" 
            onclick="togglePlatformLangDropdown(event)" 
            aria-haspopup="true" 
            aria-expanded="false" 
            title="{{ App::getLocale() == 'id' ? 'Pengaturan Bahasa (Indonesia / English)' : 'Language Settings (Indonesian / English)' }}">
        <i class="fas fa-globe-americas lang-globe-icon"></i>
        <span class="lang-current-label">{{ strtoupper(App::getLocale()) }}</span>
        <i class="fas fa-chevron-down lang-chevron-icon"></i>
    </button>

    <div class="lang-dropdown-menu" id="platformLangMenu" onclick="event.stopPropagation()">
        <div class="lang-dropdown-header">
            <div class="lang-dropdown-title">
                <i class="fas fa-language"></i>
                <span>{{ App::getLocale() == 'id' ? 'Pilih Bahasa' : 'Select Language' }}</span>
            </div>
            <span class="lang-dropdown-current-tag">{{ strtoupper(App::getLocale()) }}</span>
        </div>

        <div class="lang-dropdown-list">
            <a href="{{ route('lang.switch', 'id') }}" 
               data-turbo="false" 
               class="lang-dropdown-item {{ App::getLocale() == 'id' ? 'is-active' : '' }}">
                <span class="lang-item-flag">🇮🇩</span>
                <div class="lang-item-meta">
                    <span class="lang-item-name">Bahasa Indonesia</span>
                    <span class="lang-item-sub">ID &bull; Indonesia</span>
                </div>
                @if(App::getLocale() == 'id')
                    <span class="lang-item-check"><i class="fas fa-check"></i></span>
                @endif
            </a>

            <a href="{{ route('lang.switch', 'en') }}" 
               data-turbo="false" 
               class="lang-dropdown-item {{ App::getLocale() == 'en' ? 'is-active' : '' }}">
                <span class="lang-item-flag">🇬🇧</span>
                <div class="lang-item-meta">
                    <span class="lang-item-name">English</span>
                    <span class="lang-item-sub">EN &bull; International</span>
                </div>
                @if(App::getLocale() == 'en')
                    <span class="lang-item-check"><i class="fas fa-check"></i></span>
                @endif
            </a>
        </div>
    </div>
</div>

<script>
    function togglePlatformLangDropdown(event) {
        if (event) {
            event.stopPropagation();
        }
        const menu = document.getElementById('platformLangMenu');
        const btn = document.getElementById('platformLangBtn');
        if (!menu || !btn) return;

        const isShowing = menu.classList.contains('show');

        // Tutup dropdown notifikasi & profil jika sedang terbuka
        const notifDropdown = document.getElementById('platformNotifDropdown');
        const notifBtn = document.getElementById('platformNotifBtn');
        if (notifDropdown) notifDropdown.classList.remove('show');
        if (notifBtn) notifBtn.classList.remove('active');

        const profileDropdown = document.getElementById('platformThemeDropdown');
        const profileWrapper = document.getElementById('platformProfileDropdownWrapper');
        if (profileDropdown) profileDropdown.classList.remove('show');
        if (profileWrapper) profileWrapper.classList.remove('active');

        if (isShowing) {
            menu.classList.remove('show');
            btn.classList.remove('active');
            btn.setAttribute('aria-expanded', 'false');
        } else {
            menu.classList.add('show');
            btn.classList.add('active');
            btn.setAttribute('aria-expanded', 'true');
        }
    }

    // Klik di luar dropdown untuk menutup
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('platformLangMenu');
        const btn = document.getElementById('platformLangBtn');
        if (menu && menu.classList.contains('show')) {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('show');
                if (btn) {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        }
    });

    // Tutup saat tekan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('platformLangMenu');
            const btn = document.getElementById('platformLangBtn');
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
                if (btn) {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        }
    });
</script>
