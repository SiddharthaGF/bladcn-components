@blaze(fold: true)

@aware(['transition' => true])

@props([
    'align' => 'center',
    'side' => 'bottom',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed z-50 w-72 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden',
    );

    if ($transition) {
        $presetClass->add(
            'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        );
    }

    $presetAttributes = [
        'data-slot' => 'popover-content',
        'data-align' => $align,
        'data-side' => $side,
    ];

    $mergedStyle = filled($style) ? $style : null;
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($mergedStyle !== null) style="{{ $mergedStyle }}" @endif
    role="dialog"
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-bind:style="portalStyle"
    x-cloak
    x-init="registerContent({ align: @js($align), side: @js($side), sideOffset: @js($sideOffset) })"
    x-ref="content"
    x-show="isOpen">
    {{ $slot }}
</div>
