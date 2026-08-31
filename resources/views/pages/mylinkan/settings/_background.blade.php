                    {{-- ── SEKSI 1: BACKGROUND ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Background Halaman</h3>
                                <p class="design-settings-section-desc">Pilih gambar atau warna untuk latar belakang</p>
                            </div>
                        </header>

                        {{-- Sub-tab: Gambar | Warna --}}
                        <div class="background-sub-tab-switcher" role="tablist">
                            <button
                                type="button"
                                id="bg-tab-gambar"
                                class="bg-sub-tab-btn {{ ($appearance && $appearance->background_type === 'image') ? 'is-active' : '' }}"
                                onclick="switchBackgroundTab('gambar')"
                            >
                                <i class="fas fa-image"></i> Gambar
                            </button>
                            <button
                                type="button"
                                id="bg-tab-warna"
                                class="bg-sub-tab-btn {{ (!$appearance || $appearance->background_type !== 'image') ? 'is-active' : '' }}"
                                onclick="switchBackgroundTab('warna')"
                            >
                                <i class="fas fa-palette"></i> Warna
                            </button>
                        </div>

                        {{-- Sub-panel: Pilih Gambar Background --}}
                        <div id="bgPanelGambar" class="background-image-grid" style="display: {{ ($appearance && $appearance->background_type === 'image') ? 'grid' : 'none' }};">
                            @php
                                $backgroundImages = [
                                    'blue ocean.png'           => 'Blue Ocean',
                                    'city light.png'           => 'City Light',
                                    'clasic.png'               => 'Classic',
                                    'desert.png'               => 'Desert',
                                    'green flower.png'         => 'Green Flower',
                                    'library.png'              => 'Library',
                                    'mountain.png'             => 'Mountain',
                                    'news paper.png'           => 'News Paper',
                                    'pink candy.png'           => 'Pink Candy',
                                    'playstation abstract.png' => 'PS Abstract',
                                    'sunset.png'               => 'Sunset',
                                ];
                                $currentBgImage = ($appearance && $appearance->background_type === 'image')
                                    ? $appearance->background_color
                                    : null;
                            @endphp

                            {{-- Opsi: Tidak ada gambar (transparan / hanya warna) --}}
                            <label class="background-image-option {{ !$currentBgImage ? 'is-selected' : '' }}">
                                <input
                                    type="radio"
                                    name="design_background_image"
                                    value=""
                                    class="hidden-radio"
                                    {{ !$currentBgImage ? 'checked' : '' }}
                                    onchange="applyBackgroundImage('')"
                                >
                                <div class="bg-option-preview bg-option-none">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <span class="bg-option-label">Tanpa Gambar</span>
                            </label>

                            @foreach($backgroundImages as $filename => $label)
                                <label class="background-image-option {{ $currentBgImage === $filename ? 'is-selected' : '' }}">
                                    <input
                                        type="radio"
                                        name="design_background_image"
                                        value="{{ $filename }}"
                                        class="hidden-radio"
                                        {{ $currentBgImage === $filename ? 'checked' : '' }}
                                        onchange="applyBackgroundImage('{{ $filename }}')"
                                    >
                                    <div class="bg-option-preview" style="background-image: url('{{ asset('images/background/' . $filename) }}'); background-size: cover; background-position: center;">
                                    </div>
                                    <span class="bg-option-label">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Sub-panel: Pilih Warna Background --}}
                        <div id="bgPanelWarna" class="background-color-panel" style="display: {{ ($appearance && $appearance->background_type === 'image') ? 'none' : 'flex' }};">

                            <div class="bg-color-presets">
                                @php
                                    $colorPresets = [
                                        '#FFFFFF' => 'Putih',
                                        '#F8FAFC' => 'Abu Terang',
                                        '#F0FDF4' => 'Hijau Lembut',
                                        '#FFF7ED' => 'Oranye Lembut',
                                        '#EFF6FF' => 'Biru Lembut',
                                        '#FDF4FF' => 'Ungu Lembut',
                                        '#FFF1F2' => 'Merah Muda',
                                        '#FAFAF9' => 'Stone',
                                        '#1E293B' => 'Biru Gelap',
                                        '#111827' => 'Hitam',
                                    ];
                                    $currentBgColor = ($appearance && $appearance->background_type === 'color')
                                        ? ($appearance->background_color ?? '#FFFFFF')
                                        : '#FFFFFF';
                                @endphp

                                @foreach($colorPresets as $hex => $colorName)
                                    <button
                                        type="button"
                                        class="bg-color-preset-swatch {{ $currentBgColor === $hex ? 'is-selected' : '' }}"
                                        style="background-color: {{ $hex }};"
                                        title="{{ $colorName }}"
                                        onclick="applyBackgroundColor('{{ $hex }}')"
                                        data-color="{{ $hex }}"
                                    ></button>
                                @endforeach
                            </div>

                            <div class="bg-color-custom-row">
                                <label class="profile-form-label" for="bgColorCustomPicker">Warna Custom</label>
                                <div class="bg-color-picker-wrapper">
                                    <input
                                        type="color"
                                        id="bgColorCustomPicker"
                                        value="{{ $currentBgColor ?? '#FFFFFF' }}"
                                        oninput="applyBackgroundColor(this.value)"
                                        class="bg-color-custom-input"
                                    >
                                    <span id="bgColorHexDisplay" class="bg-color-hex-display">{{ $currentBgColor ?? '#FFFFFF' }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

