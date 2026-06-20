@blaze(fold: true)

@aware(['open' => false])

@props([
    'asChild' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'collapsible-trigger',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }

    $alpineAttributes = [
        'x-on:click' => 'toggle()',
        'data-state' => $open ? 'open' : 'closed',
        'aria-expanded' => $open ? 'true' : 'false',
        'x-bind:data-state' => "isOpen ? 'open' : 'closed'",
        'x-bind:aria-expanded' => 'isOpen',
    ];
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->merge($alpineAttributes)->class($class) }}
    default-tag="button">
    {{ $slot }}
</x-ui.abstract>
