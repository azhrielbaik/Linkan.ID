                    {{-- ── SEKSI 3: BENTUK BLOK ELEMEN ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-vector-square"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Bentuk Blok</h3>
                                <p class="design-settings-section-desc">Atur sudut blok elemen di microsite</p>
                            </div>
                        </header>

                        @php $currentBlockShape = $appearance->block_shape ?? 'rounded'; @endphp

                        <div class="block-shape-options" role="radiogroup" aria-label="Pilih bentuk blok">

                            {{-- Shape 1: Sharp (siku-siku) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'sharp' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="sharp" class="hidden-radio" {{ $currentBlockShape === 'sharp' ? 'checked' : '' }} onchange="applyBlockShape('sharp')">
                                <div class="block-shape-preview block-shape-sharp"></div>
                                <span class="block-shape-label">Sharp</span>
                            </label>

                            {{-- Shape 2: Rounded (sudut bulat biasa) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'rounded' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="rounded" class="hidden-radio" {{ $currentBlockShape === 'rounded' ? 'checked' : '' }} onchange="applyBlockShape('rounded')">
                                <div class="block-shape-preview block-shape-rounded"></div>
                                <span class="block-shape-label">Rounded</span>
                            </label>

                            {{-- Shape 3: Pill (super rounded) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'pill' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="pill" class="hidden-radio" {{ $currentBlockShape === 'pill' ? 'checked' : '' }} onchange="applyBlockShape('pill')">
                                <div class="block-shape-preview block-shape-pill"></div>
                                <span class="block-shape-label">Pill</span>
                            </label>

                        </div>
                    </section>

