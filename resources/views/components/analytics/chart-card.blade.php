@props([
    'title',
    'icon' => null,
    'iconColor' => 'text-orange-500',
    'cardLink' => null,
    'linkText' => 'Lihat Semua',
    'class' => '',
    'resetKey' => null,
])

<div {{ $attributes->merge(['class' => 'analytics-card ' . $class]) }}>
    <div class="analytics-card-header">
        <h3 class="analytics-card-title">
            @if($icon)
                <i class="{{ $icon }} {{ $iconColor }}"></i>
            @endif
            <span>{{ $title }}</span>
        </h3>
        <div class="flex items-center gap-2">
            @if($resetKey)
                <button type="button" 
                        class="analytics-chart-reset-btn" 
                        data-chart-reset="{{ $resetKey }}"
                        onclick="AnalyticsPage.charts.reset('{{ $resetKey }}', this)" 
                        title="Reset Tampilan Grafik"
                        aria-label="Reset Grafik">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            @endif
            @if($cardLink)
                <a href="{{ $cardLink }}" class="analytics-card-link">
                    {{ $linkText }} <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            @elseif(isset($headerRight))
                {{ $headerRight }}
            @endif
        </div>
    </div>
    {{ $slot }}
</div>
