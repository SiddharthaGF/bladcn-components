@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'input-otp-group',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div
    {{ $attributes->merge($presetAttributes)->class(['flex items-center', $class]) }}>
    {{ $slot }}
</div>
