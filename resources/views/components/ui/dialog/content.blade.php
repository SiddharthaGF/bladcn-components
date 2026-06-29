@blaze(fold: true)

@aware(['transition' => true])

@props([
    'showCloseButton' => true,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $overlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-50 bg-black/50',
    );

    $contentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg outline-none sm:max-w-lg',
    );

    $closeClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'absolute top-4 right-4 rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
    );
@endphp

<template x-teleport="body">
    <div data-slot="dialog-portal">
        <div @if ($transition) x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @endif
            @class($overlayClass)
            data-slot="dialog-overlay"
            x-bind:data-state="isOpen ? 'open' : 'closed'"
            x-cloak
            x-on:click="close()"
            x-show="isOpen"></div>
        <div {{ $attributes->class([$contentClass, $class]) }}
            @if ($transition) x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @endif
            aria-modal="true"
            data-slot="dialog-content"
            role="dialog"
            x-bind:data-state="isOpen ? 'open' : 'closed'"
            x-cloak
            x-on:click.stop
            x-show="isOpen">
            {{ $slot }}
            @if ($showCloseButton)
                <button @class($closeClass)
                    data-slot="dialog-close"
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
