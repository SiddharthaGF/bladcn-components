@blaze(fold: true)

@props([
    'size' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $overlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50 backdrop-blur-md transition-opacity ease-in-out data-[state=open]:opacity-100 data-[state=open]:duration-500 data-[state=closed]:opacity-0 data-[state=closed]:duration-300',
    );

    $contentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/alert-dialog-content fixed top-[50%] left-[50%] z-[51] grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg outline-none transition ease-in-out data-[state=closed]:animate-out data-[state=closed]:duration-300 data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:duration-500 data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95 sm:max-w-lg',
        )
        ->add($size === 'sm' ? 'data-[size=sm]:max-w-xs' : '');

    $presetAttributes = [
        'data-slot' => 'alert-dialog-content',
        'data-size' => $size,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<template x-teleport="body">
    <div data-slot="alert-dialog-portal"
        x-cloak
        x-show="isPresent">
        <div @class($overlayClass)
            data-slot="alert-dialog-overlay"
            data-state="closed"
            x-bind:data-state="animationState"></div>
        <div {{ $attributes->merge($presetAttributes)->class([$contentClass, $class]) }}
            aria-modal="true"
            data-slot="alert-dialog-content"
            data-state="closed"
            role="alertdialog"
            x-bind:class="{ 'invisible': animationState === 'closed' && !isClosing }"
            x-bind:data-state="animationState"
            x-on:click.stop>
            {{ $slot }}
        </div>
    </div>
</template>
