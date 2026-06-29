@blaze(fold: true)

@props([
    'direction' => 'bottom',
    'style' => null,
    'class' => null,
])

@php
    $overlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50 backdrop-blur-md transition-opacity ease-in-out data-[state=open]:opacity-100 data-[state=open]:duration-500 data-[state=closed]:opacity-0 data-[state=closed]:duration-300',
    );

    $contentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/drawer-content fixed z-[51] flex flex-col overflow-hidden bg-background transition ease-in-out data-[state=closed]:animate-out data-[state=closed]:duration-300 data-[state=open]:animate-in data-[state=open]:duration-500',
        )
        ->add(
            match ($direction) {
                'top'
                    => 'inset-x-0 top-0 h-auto max-h-[80vh] rounded-b-lg border-b data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top',
                'left'
                    => 'inset-y-0 left-0 h-full w-3/4 border-r data-[state=closed]:slide-out-to-left data-[state=open]:slide-in-from-left sm:max-w-sm',
                'right'
                    => 'inset-y-0 right-0 h-full w-3/4 border-l data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right sm:max-w-sm',
                default
                    => 'inset-x-0 bottom-0 h-auto max-h-[80vh] rounded-t-lg border-t data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom',
            },
        );
@endphp

<template x-teleport="body">
    <div data-slot="drawer-portal"
        x-cloak
        x-show="isPresent">
        <div @class($overlayClass)
            data-slot="drawer-overlay"
            data-state="closed"
            x-bind:data-state="animationState"
            x-on:click="close()"></div>
        <div {{ $attributes->class([$contentClass, $class]) }}
            aria-modal="true"
            data-slot="drawer-content"
            data-state="closed"
            data-vaul-drawer-direction="{{ $direction }}"
            role="dialog"
            x-bind:class="{ 'invisible': animationState === 'closed' && !isClosing }"
            x-bind:data-state="animationState"
            x-on:click.stop>
            <div aria-hidden="true"
                class="bg-muted mx-auto mt-4 hidden h-2 w-[100px] shrink-0 rounded-full group-data-[vaul-drawer-direction=bottom]/drawer-content:block">
            </div>
            {{ $slot }}
        </div>
    </div>
</template>
