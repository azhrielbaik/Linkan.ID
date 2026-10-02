@props([
    'title',
    'icon' => null,
    'iconColor' => 'text-orange-500',
    'cardLink' => null,
    'linkText' => 'Lihat Semua',
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'analytics-card ' . $class]) }}>
    <div class="analytics-card-header">
        <h3 class="analytics-card-title">
            @if($icon)
                <i class="{{ $icon }} {{ $iconColor }}"></i>
            @endif
            <span>{{ $title }}</span>
        </h3>
        @if($cardLink)
            <a href="{{ $cardLink }}" class="analytics-card-link">
                {{ $linkText }} <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        @elseif(isset($headerRight))
            {{ $headerRight }}
        @endif
    </div>
    {{ $slot }}
</div>
