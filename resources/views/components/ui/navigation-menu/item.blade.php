@blaze(fold: true)

@props([
    'value' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'navigation-menu-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<li {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}>
    {{ $slot }}
</li>
