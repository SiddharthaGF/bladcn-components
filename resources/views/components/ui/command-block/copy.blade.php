@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'command-block-copy',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.button
    {{ $attributes->merge($presetAttributes)->class(['text-muted-foreground relative inline-flex size-7 items-center justify-center', $class]) }}
    size="icon-sm"
    variant="ghost"
    x-bind:disabled="isCopied"
    x-on:click="copy">
    <span class="absolute"
        x-show="!isCopied"
        x-transition.opacity>
        <x-ui.icon class="size-3.5"
            name="copy" />
    </span>

    <span class="absolute"
        x-cloak
        x-show="isCopied"
        x-transition.opacity>
        <x-ui.icon class="size-3.5"
            name="check" />
    </span>
</x-ui.button>
