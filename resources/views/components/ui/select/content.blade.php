@blaze(fold: true)

@aware(['transition' => true])

@props([
    'position' => 'item-aligned',
    'align' => 'center',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $contentClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex min-w-[8rem] flex-col overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md',
    );

    if ($transition) {
        $contentClass->add(
            'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        );
    }

    $viewportClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative overflow-x-hidden overflow-y-auto p-1 [scrollbar-width:none] [-ms-overflow-style:none] [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden',
    );

    $presetAttributes = [
        'data-slot' => 'select-content',
        'role' => 'listbox',
        'data-position' => $position,
        'data-align' => $align,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<template x-teleport="body">
    <div @if ($transition) x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @endif
        class="fixed z-50 flex flex-col outline-none"
        data-slot="select-portal"
        x-bind:class="{ 'invisible': isOpen && !isPositioned }"
        x-bind:data-state="isOpen ? 'open' : 'closed'"
        x-bind:style="portalWrapperStyle()"
        x-cloak
        x-init="registerContent({ position: @js($position), align: @js($align), sideOffset: @js($sideOffset) })"
        x-on:click.outside="close()"
        x-ref="contentWrapper"
        x-show="isOpen">
        <div {{ $attributes->merge($presetAttributes)->class([$contentClass, $class]) }}
            style="box-sizing: border-box;"
            x-bind:class="{ 'h-full min-h-0': needsContentScroll }"
            x-ref="content">
            <x-ui.select.scroll-up-button />

            <div class="{{ $viewportClass }}"
                data-slot="select-viewport"
                x-bind:class="{ 'min-h-0 flex-1': needsContentScroll }"
                x-on:scroll="updateScrollButtons()"
                x-ref="viewport">
                {{ $slot }}
            </div>

            <x-ui.select.scroll-down-button />
        </div>
    </div>
</template>
