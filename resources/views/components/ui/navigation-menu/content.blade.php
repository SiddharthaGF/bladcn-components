@blaze(fold: true)

@aware(['value' => null])

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'absolute top-full left-0 z-50 mt-1.5 w-auto min-w-[12rem] overflow-hidden rounded-md border bg-popover p-2 text-popover-foreground shadow data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'data-slot' => 'navigation-menu-content',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:data-state="isOpen(@js($value)) ? 'open' : 'closed'"
    x-cloak
    x-on:click.outside="close()"
    x-show="isOpen(@js($value))">
    {{ $slot }}
</div>
