@blaze(fold: true)

@props([
    'asChild' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'drawer-trigger',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }

    $alpineAttributes = [
        'x-on:click' => 'open()',
        'x-bind:data-state' => "isOpen ? 'open' : 'closed'",
        'x-bind:aria-expanded' => 'isOpen',
    ];
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->merge($alpineAttributes)->class($class) }}
    default-tag="button">
    {{ $slot }}
</x-ui.abstract>
