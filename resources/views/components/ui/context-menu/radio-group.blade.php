@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'role' => 'group',
        'data-slot' => 'context-menu-radio-group',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['w-full', $class]) }}>
    {{ $slot }}
</div>
