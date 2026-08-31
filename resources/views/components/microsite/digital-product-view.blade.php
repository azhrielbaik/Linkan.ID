@props(['product', 'media' => [], 'isPreview' => false])

@php
    // Penjadwalan Waktu (Scheduling Logic)
    $schedule = $product['schedule'] ?? ['enabled' => false];
    
    if (isset($schedule['enabled']) && $schedule['enabled'] == true) {
        $now = now();
        $start = isset($schedule['start']) && $schedule['start'] ? \Carbon\Carbon::parse($schedule['start']) : null;
        $end = isset($schedule['end']) && $schedule['end'] ? \Carbon\Carbon::parse($schedule['end']) : null;
        
        // Sembunyikan (return) jika di luar rentang waktu
        if ($start && $now->lt($start)) return;
        if ($end && $now->gt($end)) return;
    }

    // In actual implementation, $product would be an Eloquent model or array 
    // containing media URLs. We assume $product['mediaFiles'] is an array of objects
    // with 'url' and 'type' (e.g. 'image/jpeg', 'video/mp4').
    // Generate Unique ID
    $uniqueId = uniqid('dp_');
@endphp

{{-- 1. COMPACT CARD VIEW (Shown on Microsite) --}}
<div class="dp-compact-card" {!! !$isPreview ? 'onclick="openDpModal(\''.$uniqueId.'\')"' : '' !!} style="background: white; border-radius: 12px; overflow: hidden; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); cursor: pointer; border: 1px solid #f1f5f9; transition: transform 0.2s;">
    {{-- Card Image (First Media) --}}
    <div style="position: relative; aspect-ratio: 1/1; background: #f8fafc; overflow: hidden;">
        @if(count($media) > 0)
            @if(isset($media[0]['type']) && str_starts_with($media[0]['type'], 'video'))
                <video src="{{ $media[0]['url'] }}" style="width: 100%; height: 100%; object-fit: cover;" muted playsinline></video>
            @else
                <img src="{{ $media[0]['url'] }}" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
        @else
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                <i class="fas fa-image" style="font-size: 40px;"></i>
            </div>
        @endif
        
        @if(count($media) > 1)
            {{-- Dots Indicator for Multiple Images (Compact View) --}}
            <div style="position: absolute; bottom: 12px; left: 0; width: 100%; display: flex; justify-content: center; gap: 4px; z-index: 2;">
                @foreach($media as $index => $item)
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: {{ $index === 0 ? '#fff' : 'rgba(255,255,255,0.5)' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"></div>
                @endforeach
            </div>
        @endif
        
        {{-- Floating Button (Like in the design) --}}
        <div style="position: absolute; bottom: 10px; right: 10px; width: 36px; height: 36px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            <i class="fas fa-plus"></i>
        </div>
    </div>
    
    {{-- Card Info --}}
    <div style="padding: 12px 15px;">
        <div style="font-weight: 700; color: #1e293b; font-size: 15px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $product['title'] ?? 'Nama Produk' }}
        </div>
        
        @php
            $pricing = $product['pricing'] ?? ['type' => 'fixed', 'fixed' => 0, 'min' => 0, 'max' => 0];
            $quantity = $product['quantity'] ?? ['min' => 1, 'max' => ''];
        @endphp
        
        @if($pricing['type'] === 'fixed')
            <div style="color: #475569; font-size: 14px; margin-bottom: 4px;">Rp {{ number_format((float)$pricing['fixed'], 0, ',', '.') }}</div>
        @else
            <div style="color: #64748b; font-size: 14px; font-style: italic; margin-bottom: 4px;">Rp{{ number_format((float)$pricing['min'], 0, ',', '.') }}+</div>
        @endif
        
        <div style="color: #94a3b8; font-size: 12px;">
            Stock: {{ $quantity['max'] ? $quantity['max'] : 'Unlimited' }}
        </div>
    </div>
</div>

{{-- 2. MODAL VIEW (Shown when card is clicked) --}}
@if(!$isPreview)
<div id="dpModal_{{ $uniqueId }}" class="dp-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 99999; align-items: flex-end; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="dp-modal-content" style="background: white; width: 100%; max-width: 500px; max-height: 90vh; border-radius: 20px 20px 0 0; overflow-y: auto; transform: translateY(100%); transition: transform 0.3s ease; position: relative; display: flex; flex-direction: column;">
        
        {{-- Close Button --}}
        <button onclick="closeDpModal('{{ $uniqueId }}')" style="position: absolute; top: 15px; right: 15px; z-index: 10; width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: #475569;">
            <i class="fas fa-times"></i>
        </button>

        <!-- Media Slider -->
        @if(count($media) > 0)
            <div style="position: relative;">
                <div class="dp-media-slider" style="display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; background: #f8fafc; height: 300px; flex-shrink: 0;">
                    @foreach($media as $index => $item)
                        <div class="dp-media-slide" style="flex: 0 0 100%; width: 100%; scroll-snap-align: center; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #000;">
                            @if(isset($item['type']) && str_starts_with($item['type'], 'video'))
                                <video class="dp-video-player" src="{{ $item['url'] }}" style="width: 100%; height: 100%; object-fit: cover;" muted loop playsinline></video>
                            @else
                                <img src="{{ $item['url'] }}" alt="Media {{ $index + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @if(count($media) > 1)
                    {{-- Dots Indicator for Multiple Images (Modal View) --}}
                    <div class="dp-slider-dots" style="position: absolute; bottom: 12px; left: 0; width: 100%; display: flex; justify-content: center; gap: 6px; z-index: 2;">
                        @foreach($media as $index => $item)
                            <div class="dp-dot" data-index="{{ $index }}" style="width: 8px; height: 8px; border-radius: 50%; background: {{ $index === 0 ? '#fff' : 'rgba(255,255,255,0.5)' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.3); transition: background 0.2s;"></div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        
        {{-- Product Details --}}
        <div style="padding: 20px; flex-grow: 1;">
            {{-- 1. Judul & Deskripsi --}}
            <h4 style="margin: 0 0 10px 0; font-size: 20px; color: #1e293b; font-weight: 700;">{{ $product['title'] ?? 'Nama Produk' }}</h4>
            <div class="dp-description quill-content" style="font-size: 14px; color: #475569; margin-bottom: 24px; line-height: 1.6;">
                {!! $product['description'] ?? '' !!}
            </div>

            {{-- 2. Area Harga (Pricing) & 3. Kuantitas --}}
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 5px;">Harga Produk</label>
                    @if($pricing['type'] === 'fixed')
                        <div style="font-size: 20px; font-weight: 700; color: #FF9040;">
                            Rp {{ number_format((float)$pricing['fixed'], 0, ',', '.') }}
                        </div>
                    @else
                        <div class="pwyw-container">
                            <div style="display: flex; align-items: center; background: white; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; focus-within: border-color: #FF9040;">
                                <span style="padding: 8px 12px; background: #f1f5f9; color: #64748b; font-weight: 600; border-right: 1px solid #cbd5e1;">Rp</span>
                                <input type="number" id="priceInput_{{ $uniqueId }}" class="dp-pwyw-input" placeholder="Tentukan harga..." style="flex: 1; border: none; padding: 8px 12px; outline: none; width: 100%;" value="{{ $pricing['min'] }}" min="{{ $pricing['min'] }}" max="{{ $pricing['max'] ?: '' }}">
                            </div>
                            <div id="priceWarn_{{ $uniqueId }}" style="color: #ef4444; font-size: 12px; margin-top: 5px; display: none;">
                                Harga minimal adalah Rp {{ number_format((float)$pricing['min'], 0, ',', '.') }}{{ $pricing['max'] ? ' dan maksimal Rp ' . number_format((float)$pricing['max'], 0, ',', '.') : '' }}.
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 5px;">Kuantitas</label>
                    <div style="display: inline-flex; align-items: center; background: white; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
                        <button type="button" class="dp-qty-btn" onclick="updateDpQty('{{ $uniqueId }}', -1, {{ $quantity['min'] }}, {{ $quantity['max'] ?: 'null' }})" style="padding: 8px 12px; background: #f8fafc; border: none; border-right: 1px solid #cbd5e1; cursor: pointer; color: #475569; font-weight: bold;">-</button>
                        <input type="number" id="qtyInput_{{ $uniqueId }}" style="width: 50px; text-align: center; border: none; outline: none; padding: 8px 0; -moz-appearance: textfield;" value="{{ $quantity['min'] }}" readonly>
                        <button type="button" class="dp-qty-btn" onclick="updateDpQty('{{ $uniqueId }}', 1, {{ $quantity['min'] }}, {{ $quantity['max'] ?: 'null' }})" style="padding: 8px 12px; background: #f8fafc; border: none; border-left: 1px solid #cbd5e1; cursor: pointer; color: #475569; font-weight: bold;">+</button>
                    </div>
                </div>
            </div>

            @php
                $deliverable = $product['deliverable'] ?? ['type' => 'link', 'url' => '#', 'file' => ''];
                $ctaOnclick = '';
                if ($deliverable['type'] === 'upload' && !empty($deliverable['file'])) {
                    $fileUrl = asset('storage/' . $deliverable['file']);
                    $ctaOnclick = "event.preventDefault(); const a = document.createElement('a'); a.href = '{$fileUrl}'; a.download = ''; a.click();";
                } else {
                    $link = $deliverable['url'] ?? '#';
                    $ctaOnclick = "event.preventDefault(); window.open('{$link}', '_blank');";
                }
            @endphp

            <button onclick="{!! $ctaOnclick !!}" style="width: 100%; padding: 14px; border-radius: 8px; background: #FF9040; color: white; border: none; font-weight: 700; font-size: 16px; cursor: pointer; box-shadow: 0 4px 12px rgba(255, 144, 64, 0.3);">
                Akses Produk / Beli Sekarang
            </button>
        </div>
    </div>
</div>
@endif

<style>
/* CSS Scroll Snap Slider */
.dp-media-slider::-webkit-scrollbar {
    display: none;
}
.dp-media-slider {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.dp-qty-btn:hover { background: #f1f5f9 !important; }
.dp-qty-btn:active { background: #e2e8f0 !important; }
/* Quill Content Overrides for Frontend */
.dp-description ul, .dp-description ol { padding-left: 20px; margin-bottom: 10px; }
.dp-description p { margin-bottom: 10px; }
</style>

<script>
function openDpModal(id) {
    const modal = document.getElementById('dpModal_' + id);
    if (!modal) return;
    modal.style.display = 'flex';
    // Small delay to allow display: flex to apply before opacity transition
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.querySelector('.dp-modal-content').style.transform = 'translateY(0)';
    }, 10);
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeDpModal(id) {
    const modal = document.getElementById('dpModal_' + id);
    if (!modal) return;
    modal.style.opacity = '0';
    modal.querySelector('.dp-modal-content').style.transform = 'translateY(100%)';
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 300); // Wait for transition
}

function updateDpQty(id, change, min, max) {
    const input = document.getElementById('qtyInput_' + id);
    if (!input) return;
    
    let current = parseInt(input.value) || min;
    let next = current + change;
    
    if (next < min) next = min;
    if (max !== null && next > max) next = max;
    
    input.value = next;
}

document.addEventListener('input', function(e) {
    if (e.target && e.target.classList.contains('dp-pwyw-input')) {
        const input = e.target;
        const id = input.id.replace('priceInput_', '');
        const warn = document.getElementById('priceWarn_' + id);
        
        const val = parseFloat(input.value) || 0;
        const min = parseFloat(input.getAttribute('min')) || 0;
        const maxAttr = input.getAttribute('max');
        const max = maxAttr ? parseFloat(maxAttr) : null;
        
        if (val < min || (max !== null && val > max)) {
            input.style.borderColor = '#ef4444';
            input.parentElement.style.borderColor = '#ef4444';
            if(warn) warn.style.display = 'block';
        } else {
            input.style.borderColor = '';
            input.parentElement.style.borderColor = '#cbd5e1';
            if(warn) warn.style.display = 'none';
        }
    }
});
if (typeof window.dpVideoObserver === 'undefined') {
    window.dpVideoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const video = entry.target;
            if (entry.isIntersecting) {
                video.play().catch(e => console.log('Video autoplay prevented by browser:', e));
            } else {
                video.pause();
            }
        });
    }, {
        threshold: 0.6 // Play when 60% of the video is visible
    });
}

function initDpVideos() {
    document.querySelectorAll('.dp-video-player:not(.observed)').forEach(video => {
        window.dpVideoObserver.observe(video);
        video.classList.add('observed');
    });
}

function initDpSliders() {
    document.querySelectorAll('.dp-media-slider:not(.slider-initialized)').forEach(slider => {
        slider.classList.add('slider-initialized');
        
        slider.addEventListener('scroll', () => {
            const index = Math.round(slider.scrollLeft / slider.clientWidth);
            const container = slider.parentElement;
            const dots = container.querySelectorAll('.dp-dot');
            dots.forEach((dot, i) => {
                dot.style.background = (i === index) ? '#fff' : 'rgba(255,255,255,0.5)';
            });
        });
    });
}

// Run on initial load
document.addEventListener('DOMContentLoaded', () => {
    initDpVideos();
    initDpSliders();
});
// If it's a dynamic SPA or Livewire, also expose it globally
window.initDpVideos = initDpVideos;
window.initDpSliders = initDpSliders;
</script>
