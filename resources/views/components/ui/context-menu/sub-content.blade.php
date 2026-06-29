@blaze(fold: true)

@props([
    'side' => 'right',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed z-50 flex min-w-24 flex-col overflow-hidden rounded-lg border bg-popover p-1 text-popover-foreground shadow-lg ring-1 ring-foreground/10 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'data-slot' => 'context-menu-sub-content',
        'data-side' => $side,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:data-side="resolvedSubSide"
    x-bind:data-state="isSubOpen ? 'open' : 'closed'"
    x-bind:style="subPortalStyle"
    x-cloak
    x-on:click.outside.stop="closeSub()"
    x-on:mouseenter="cancelClose()"
    x-on:mouseleave="scheduleClose()"
    x-ref="subContent"
    x-show="isSubOpen">
    {{ $slot }}
</div>
