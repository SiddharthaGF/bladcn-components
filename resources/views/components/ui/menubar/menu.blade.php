@blaze(fold: true)

@props([
    'value' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'menubar-menu',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}>
    {{ $slot }}
</div>
