{{-- Reusable Platform Admin Profile & Dynamic Theme Dropdown --}}
<div class="header-user-wrapper" id="platformProfileDropdownWrapper">
    <div class="header-user-btn" id="platformProfileBtn" onclick="togglePlatformThemeDropdown(event)" aria-haspopup="true" aria-expanded="false">
        <div class="header-avatar" id="headerUserAvatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
        </div>
        <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
        <i class="fas fa-chevron-down dropdown-chevron"></i>
    </div>

    {{-- Dropdown Card --}}
    <div class="platform-theme-dropdown" id="platformThemeDropdown" onclick="event.stopPropagation()">
        {{-- Profile Info Header --}}
        <div class="ptd-header">
            <div class="ptd-avatar" id="dropdownUserAvatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            </div>
            <div class="ptd-user-info">
                <div class="ptd-name">{{ Auth::user()->name ?? 'Admin Platform' }}</div>
                <div class="ptd-role"><i class="fas fa-shield-alt"></i> Platform Admin</div>
            </div>
        </div>

        {{-- Dropdown Controls --}}
        <div class="ptd-body">
            {{-- Mode Selection --}}
            <div>
                <div class="ptd-section-title">
                    <span><i class="fas fa-palette"></i> Mode Tampilan</span>
                    <span class="ptd-save-status" id="themeSaveStatus"><i class="fas fa-check"></i> Tersimpan</span>
                </div>
                <div class="ptd-mode-switcher">
                    <button type="button" class="ptd-mode-btn" id="btnThemeLight" onclick="setPlatformThemeMode('light')">
                        <i class="fas fa-sun"></i> Terang
                    </button>
                    <button type="button" class="ptd-mode-btn" id="btnThemeDark" onclick="setPlatformThemeMode('dark')">
                        <i class="fas fa-moon"></i> Gelap
                    </button>
                </div>
            </div>

            {{-- Color Accent Selection --}}
            <div>
                <div class="ptd-section-title">
                    <span><i class="fas fa-brush"></i> Warna Aksen</span>
                </div>
                <div class="ptd-color-swatches">
                    {{-- Default Orange --}}
                    <button type="button" class="ptd-swatch" style="background-color: #ed842c;" data-color="#ed842c" title="Sunset Orange" onclick="setPlatformThemeColor('#ed842c')"></button>
                    {{-- Emerald --}}
                    <button type="button" class="ptd-swatch" style="background-color: #10b981;" data-color="#10b981" title="Emerald Green" onclick="setPlatformThemeColor('#10b981')"></button>
                    {{-- Blue --}}
                    <button type="button" class="ptd-swatch" style="background-color: #2563eb;" data-color="#2563eb" title="Royal Blue" onclick="setPlatformThemeColor('#2563eb')"></button>
                    {{-- Purple --}}
                    <button type="button" class="ptd-swatch" style="background-color: #7c3aed;" data-color="#7c3aed" title="Purple Velvet" onclick="setPlatformThemeColor('#7c3aed')"></button>
                    {{-- Rose --}}
                    <button type="button" class="ptd-swatch" style="background-color: #e11d48;" data-color="#e11d48" title="Crimson Rose" onclick="setPlatformThemeColor('#e11d48')"></button>
                    {{-- Cyan --}}
                    <button type="button" class="ptd-swatch" style="background-color: #06b6d4;" data-color="#06b6d4" title="Cyber Cyan" onclick="setPlatformThemeColor('#06b6d4')"></button>

                    {{-- Custom Color Picker --}}
                    <div class="ptd-custom-color-wrap" title="Pilih Warna Kustom">
                        <input type="color" id="ptdCustomColorInput" value="{{ Auth::user()->theme_color ?? '#ed842c' }}" onchange="setPlatformThemeColor(this.value)" oninput="applyThemeColorLive(this.value)">
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Logout --}}
        <div class="ptd-footer">
            <button type="button" class="ptd-logout-btn" onclick="confirmPlatformLogout()">
                <i class="fas fa-sign-out-alt"></i> {{ __('sidebar.logout') }}
            </button>
        </div>
    </div>
</div>
