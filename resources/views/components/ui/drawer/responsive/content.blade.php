@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $dialogOverlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50 backdrop-blur-md',
    );

    $drawerOverlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50 backdrop-blur-md transition-opacity ease-in-out data-[state=open]:opacity-100 data-[state=open]:duration-500 data-[state=closed]:opacity-0 data-[state=closed]:duration-300',
    );

    $dialogContentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed top-[50%] left-[50%] z-[51] grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg outline-none sm:max-w-lg',
    );

    $drawerContentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/drawer-content fixed inset-x-0 bottom-0 z-[51] flex h-auto max-h-[80vh] flex-col overflow-hidden rounded-t-lg border-t bg-background transition ease-in-out data-[state=closed]:animate-out data-[state=closed]:duration-300 data-[state=closed]:slide-out-to-bottom data-[state=open]:animate-in data-[state=open]:duration-500 data-[state=open]:slide-in-from-bottom',
    );
@endphp

<template x-teleport="body">
    <div data-mode="dialog"
        data-slot="drawer-responsive-portal"
        x-cloak
        x-show="isOpen && isDesktop">
        <div @class($dialogOverlayClass)
            data-slot="dialog-overlay"
            x-cloak
            x-on:click="close()"
            x-show="isOpen"
            x-transition:enter-end="opacity-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter="transition ease-out duration-200"
            x-transition:leave-end="opacity-0"
            x-transition:leave-start="opacity-100"
            x-transition:leave="transition ease-in duration-200"></div>
        <div {{ $attributes->class([$dialogContentClass, $class]) }}
            aria-modal="true"
            data-slot="dialog-content"
            role="dialog"
            x-cloak
            x-on:click.stop
            x-show="isOpen"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter="transition ease-out duration-200"
            x-transition:leave-end="opacity-0 scale-95"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200">
            {{ $slot }}
        </div>
    </div>
</template>

<template x-teleport="body">
    <div data-mode="drawer"
        data-slot="drawer-responsive-portal"
        x-cloak
        x-show="isPresent && !isDesktop">
        <div @class($drawerOverlayClass)
            data-slot="drawer-overlay"
            data-state="closed"
            x-bind:data-state="animationState"
            x-on:click="close()"></div>
        <div {{ $attributes->class([$drawerContentClass, $class]) }}
            aria-modal="true"
            data-slot="drawer-content"
            data-state="closed"
            data-vaul-drawer-direction="bottom"
            role="dialog"
            x-bind:class="{ 'invisible': animationState === 'closed' && !isClosing }"
            x-bind:data-state="animationState"
            x-on:click.stop>
            <div aria-hidden="true"
                class="bg-muted mx-auto mt-4 h-2 w-[100px] shrink-0 rounded-full">
            </div>
            {{ $slot }}
        </div>
    </div>
</template>
