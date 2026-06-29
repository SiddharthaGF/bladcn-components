@blaze(fold: true)

@aware(['value' => null])

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed z-50 max-h-60 min-w-[12rem] origin-top overflow-x-hidden overflow-y-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'role' => 'menu',
        'data-slot' => 'menubar-content',
    ];
@endphp

<template x-teleport="body">
    <div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
        @if (filled($style)) style="{{ $style }}" @endif
        data-slot="menubar-portal"
        x-bind:data-state="isMenuOpen(@js($value)) ? 'open' : 'closed'"
        x-bind:style="`left: ${menuX}px; top: ${menuY}px`"
        x-cloak
        x-on:click.outside="closeMenus()"
        x-show="isMenuOpen(@js($value))">
        {{ $slot }}
    </div>
</template>
