@blaze(fold: true)

@props([
    'delayDuration' => 0,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'tooltip-provider',
        'data-delay-duration' => $delayDuration,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}>
    {{ $slot }}
</div>
