@blaze(fold: true)

@props([
    'side' => 'right',
    'showCloseButton' => true,
    'style' => null,
    'class' => null,
])

@php
    $overlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50 transition-opacity ease-in-out data-[state=open]:opacity-100 data-[state=open]:duration-500 data-[state=closed]:opacity-0 data-[state=closed]:duration-300',
    );

    $contentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'fixed z-50 flex flex-col gap-4 bg-background shadow-lg transition ease-in-out data-[state=closed]:animate-out data-[state=closed]:duration-300 data-[state=open]:animate-in data-[state=open]:duration-500',
        )
        ->add(
            match ($side) {
                'left'
                    => 'inset-y-0 left-0 h-full w-3/4 border-r data-[state=closed]:slide-out-to-left data-[state=open]:slide-in-from-left sm:max-w-sm',
                'top'
                    => 'inset-x-0 top-0 h-auto border-b data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top',
                'bottom'
                    => 'inset-x-0 bottom-0 h-auto border-t data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom',
                default
                    => 'inset-y-0 right-0 h-full w-3/4 border-l data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right sm:max-w-sm',
            },
        );

    $closeClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'absolute top-4 right-4 rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none data-[state=open]:bg-secondary [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
    );
@endphp

<template x-teleport="body">
    <div data-slot="sheet-portal"
        x-cloak
        x-show="isPresent">
        <div @class($overlayClass)
            data-slot="sheet-overlay"
            data-state="closed"
            x-bind:data-state="animationState"
            x-on:click="close()"></div>
        <div {{ $attributes->class([$contentClass, $class]) }}
            aria-modal="true"
            data-side="{{ $side }}"
            data-slot="sheet-content"
            data-state="closed"
            role="dialog"
            x-bind:class="{ 'invisible': animationState === 'closed' && !isClosing }"
            x-bind:data-state="animationState"
            x-on:click.stop>
            {{ $slot }}
            @if ($showCloseButton)
                <button @class($closeClass)
                    data-slot="sheet-close"
                    type="button"
                    x-on:click="close()">
                    <x-ui.icon aria-hidden="true"
                        class="size-4"
                        name="x" />
                    <span class="sr-only">Close</span>
                </button>
            @endif
        </div>
    </div>
</template>
