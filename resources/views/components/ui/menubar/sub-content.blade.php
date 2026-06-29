@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'absolute top-0 left-full z-50 ml-1 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-lg data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'data-slot' => 'menubar-sub-content',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:data-state="isSubOpen ? 'open' : 'closed'"
    x-cloak
    x-on:click.outside.stop="closeSub()"
    x-show="isSubOpen">
    {{ $slot }}
</div>
