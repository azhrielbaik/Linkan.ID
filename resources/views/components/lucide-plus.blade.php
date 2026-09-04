@props([
    'class' => '',
    'size' => 24,
    'strokeWidth' => 2,
])

<svg
    {{ $attributes->merge(['class' => 'lucide lucide-plus ' . $class]) }}
    xmlns="http://www.w3.org/2000/svg"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
>
    <path d="M5 12h14"/>
    <path d="M12 5v14"/>
</svg>
