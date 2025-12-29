@props(['variant' => 'primary', 'icon' => null, 'size' => ''])

@php
    $classes = "btn btn-{$variant}";
    if ($size) {
        $classes .= " btn-{$size}";
    }
@endphp

<button {{ $attributes->merge(['class' => $classes, 'type' => 'submit']) }}>
    @if ($icon)
        <i class="bx {{ $icon }} me-1"></i>
    @endif
    {{ $slot }}
</button>
