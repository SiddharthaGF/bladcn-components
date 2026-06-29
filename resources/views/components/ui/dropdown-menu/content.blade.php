@blaze(fold: true)

@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed z-50 flex min-w-32 flex-col overflow-x-hidden overflow-y-auto rounded-lg border bg-popover p-1 text-popover-foreground shadow-md ring-1 ring-foreground/10 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'role' => 'menu',
        'data-slot' => 'dropdown-menu-content',
        'data-align' => $align,
        'data-side' => $side,
    ];

    $mergedStyle = filled($style) ? $style : null;
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($mergedStyle !== null) style="{{ $mergedStyle }}" @endif
    data-slot="dropdown-menu-portal"
    x-bind:data-side="resolvedSide"
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-bind:style="portalStyle"
    x-cloak
    x-init="registerContent({ align: @js($align), side: @js($side), sideOffset: @js($sideOffset) })"
    x-on:click.outside="closeIfOutside($event)"
    x-ref="content"
    x-show="isOpen">
    {{ $slot }}
</div>
