@blaze(fold: true)

@props([
    'asChild' => true,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'dropdown-menu-trigger',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }

    $wrapperAttributes = [
        'x-ref' => 'trigger',
        'x-init' => '$store.menu.bindTrigger(id, $el)',
    ];

    $triggerAttributes = [
        'x-on:click' => 'toggleMenu()',
        'x-bind:aria-expanded' => 'panelOpen',
        'aria-haspopup' => 'menu',
    ];
@endphp

<div {{ $attributes->only('class')->merge($wrapperAttributes)->class($class) }}>
    <x-ui.abstract :as-child="$asChild"
        {{ $attributes->except('class')->merge($presetAttributes)->merge($triggerAttributes) }}
        default-tag="button">
        {{ $slot }}
    </x-ui.abstract>
</div>
