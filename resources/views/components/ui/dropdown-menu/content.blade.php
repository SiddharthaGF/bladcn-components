@blaze(fold: true)

@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $anchorPlacement = match (true) {
        $side === 'bottom' && $align === 'start' => 'bottom-start',
        $side === 'bottom' && $align === 'end' => 'bottom-end',
        $side === 'bottom' && $align === 'center' => 'bottom',
        $side === 'top' && $align === 'start' => 'top-start',
        $side === 'top' && $align === 'end' => 'top-end',
        $side === 'top' && $align === 'center' => 'top',
        $side === 'left' && $align === 'start' => 'left-start',
        $side === 'left' && $align === 'end' => 'left-end',
        $side === 'left' && $align === 'center' => 'left',
        $side === 'right' && $align === 'start' => 'right-start',
        $side === 'right' && $align === 'end' => 'right-end',
        $side === 'right' && $align === 'center' => 'right',
        default => 'bottom-start',
    };

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/menu absolute z-50 flex min-w-32 flex-col overflow-x-hidden overflow-y-auto rounded-lg border bg-popover p-1 text-popover-foreground shadow-md ring-1 ring-foreground/10 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $presetAttributes = [
        'data-slot' => 'dropdown-menu-content',
        'data-align' => $align,
        'data-side' => $side,
    ];

    $mergedStyle = filled($style) ? $style : null;
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($mergedStyle !== null) style="{{ $mergedStyle }}" @endif
    x-anchor.{{ $anchorPlacement }}.offset.{{ $sideOffset }}="$refs.trigger"
    x-bind:data-keyboard-nav="keyboardNav ? '' : null"
    x-bind:data-state="panelOpen ? 'open' : 'closed'"
    x-bind="$store.menu.menuProps(id)"
    x-cloak
    x-init="$store.menu.bindMenu(id, $el)"
    x-on:pointermove="disableKeyboardNav()"
    x-show="panelOpen">
    {{ $slot }}
</div>
