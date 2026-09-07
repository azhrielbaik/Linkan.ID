                    {{-- ── SEKSI 2: LAYOUT PROFIL ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Layout Profil</h3>
                                <p class="design-settings-section-desc">Atur posisi dan tampilan bagian profil</p>
                            </div>
                        </header>

                        @php
                            $currentLayout = $appearance->profile_layout ?? 'classic';
                        @endphp

                        <div class="profile-layout-options" role="radiogroup" aria-label="Pilih layout profil">

                            {{-- Layout 1: Title-Top — judul di area banner, avatar besar di tengah --}}
                            <label class="profile-layout-card {{ $currentLayout === 'title-top' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="title-top" class="hidden-radio" {{ $currentLayout === 'title-top' ? 'checked' : '' }} onchange="applyProfileLayout('title-top')">
                                <div class="layout-preview layout-preview-title-top">
                                    {{-- Banner area dengan judul di dalamnya --}}
                                    <div class="lp-tt-header">
                                        <div class="lp-line lp-line-wide lp-line-dark"></div>
                                    </div>
                                    {{-- Avatar besar di tengah (di bawah banner, di area putih) --}}
                                    <div class="lp-tt-avatar"></div>
                                    {{-- Bio --}}
                                    <div class="lp-line lp-line-mid" style="margin-top: 4px;"></div>
                                    {{-- Blok konten --}}
                                    <div class="lp-block"></div>
                                </div>
                                <span class="layout-card-label">Title Top</span>
                            </label>

                            {{-- Layout 2: Classic — banner atas, avatar overlap di batas, judul & bio di bawah --}}
                            <label class="profile-layout-card {{ ($currentLayout === 'classic' || !$currentLayout) ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="classic" class="hidden-radio" {{ ($currentLayout === 'classic' || !$currentLayout) ? 'checked' : '' }} onchange="applyProfileLayout('classic')">
                                <div class="layout-preview layout-preview-classic">
                                    {{-- Banner atas --}}
                                    <div class="lp-cl-banner"></div>
                                    {{-- Avatar di tepi banner (overlap) --}}
                                    <div class="lp-cl-avatar"></div>
                                    {{-- Nama --}}
                                    <div class="lp-line lp-line-wide"></div>
                                    {{-- Bio --}}
                                    <div class="lp-line lp-line-mid"></div>
                                    {{-- Blok konten --}}
                                    <div class="lp-block"></div>
                                </div>
                                <span class="layout-card-label">Classic</span>
                            </label>

                            {{-- Layout 3: Side Panel — area abu portrait di kiri, avatar kiri atas, teks di kanan --}}
                            <label class="profile-layout-card {{ $currentLayout === 'side' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="side" class="hidden-radio" {{ $currentLayout === 'side' ? 'checked' : '' }} onchange="applyProfileLayout('side')">
                                <div class="layout-preview layout-preview-side">
                                    {{-- Banner atas --}}
                                    <div class="lp-side-banner"></div>
                                    {{-- Avatar rata kiri overlap --}}
                                    <div class="lp-side-avatar-wrapper">
                                        <div class="lp-side-avatar"></div>
                                    </div>
                                    {{-- Konten teks rata kiri --}}
                                    <div class="lp-side-content">
                                        <div class="lp-line lp-line-wide"></div>
                                        <div class="lp-line lp-line-mid"></div>
                                        <div class="lp-block"></div>
                                    </div>
                                </div>
                                <span class="layout-card-label">Side Panel</span>
                            </label>

                        </div>
                    </section>

