                    {{-- ── SEKSI 1: BACKGROUND ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">{{ __('microsite.bg_title') }}</h3>
                                <p class="design-settings-section-desc">{{ __('microsite.bg_desc') }}</p>
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
                                <i class="fas fa-image"></i> {{ __('microsite.bg_image') }}
                            </button>
                            <button
                                type="button"
                                id="bg-tab-warna"
                                class="bg-sub-tab-btn {{ (!$appearance || $appearance->background_type !== 'image') ? 'is-active' : '' }}"
                                onclick="switchBackgroundTab('warna')"
                            >
                                <i class="fas fa-palette"></i> {{ __('microsite.bg_color') }}
                            </button>
                        </div>

                        {{-- Sub-panel: Pilih Gambar Background --}}
                        <div id="bgPanelGambar" class="background-image-grid" style="display: {{ ($appearance && $appearance->background_type === 'image') ? 'grid' : 'none' }};">
                                @php
                                    $backgroundImages = [
                                        'blue ocean.webp'           => 'Blue Ocean',
                                        'city light.webp'           => 'City Light',
                                        'clasic.webp'               => 'Classic',
                                        'desert.webp'               => 'Desert',
                                        'green flower.webp'         => 'Green Flower',
                                        'library.webp'              => 'Library',
                                        'mountain.webp'             => 'Mountain',
                                        'news paper.webp'           => 'News Paper',
                                        'pink candy.webp'           => 'Pink Candy',
                                        'playstation abstract.webp' => 'PS Abstract',
                                        'sunset.webp'               => 'Sunset',
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
                                <span class="bg-option-label">{{ __('microsite.bg_none') }}</span>
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
                                        '#FFFFFF' => __('microsite.color_white'),
                                        '#F8FAFC' => __('microsite.color_light_gray'),
                                        '#F0FDF4' => __('microsite.color_soft_green'),
                                        '#FFF7ED' => __('microsite.color_soft_orange'),
                                        '#EFF6FF' => __('microsite.color_soft_blue'),
                                        '#FDF4FF' => __('microsite.color_soft_purple'),
                                        '#FFF1F2' => __('microsite.color_pink'),
                                        '#FAFAF9' => 'Stone',
                                        '#1E293B' => __('microsite.color_dark_blue'),
                                        '#111827' => __('microsite.color_black'),
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
                                <label class="profile-form-label" for="bgColorCustomPicker">{{ __('microsite.bg_custom_color') }}</label>
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

