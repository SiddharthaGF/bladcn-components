@blaze(fold: true)

@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
    'searchPlaceholder' => 'Search...',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'z-50 max-h-60 min-w-[8rem] origin-top overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md',
    );

    $presetAttributes = [
        'data-slot' => 'combobox-content',
        'role' => 'listbox',
        'data-align' => $align,
        'data-side' => $side,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<template x-teleport="body">
    <div class="fixed z-50 flex flex-col outline-none"
        data-slot="combobox-portal"
        x-bind:class="{ 'invisible': isOpen && !isPositioned }"
        x-bind:data-side="resolvedSide"
        x-bind:style="portalStyle"
        x-cloak
        x-on:click.outside="close()"
        x-ref="contentWrapper"
        x-show="isOpen"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter="transition ease-out duration-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75">
        <div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
            x-init="registerContent({ align: @js($align), side: @js($side), sideOffset: @js($sideOffset) })"
            x-on:click.stop
            x-ref="content">
            <div class="flex items-center gap-2 border-b px-3"
                data-slot="combobox-search">
                <x-ui.icon aria-hidden="true"
                    class="size-4 shrink-0 opacity-50"
                    name="search" />
                <input
                    class="outline-hidden placeholder:text-muted-foreground flex h-10 w-full rounded-md bg-transparent py-2 text-sm"
                    placeholder="{{ $searchPlaceholder }}"
                    type="text"
                    x-model="search"
                    x-ref="searchInput" />
            </div>

            <div class="max-h-48 overflow-y-auto p-1">
                {{ $slot }}
            </div>
        </div>
    </div>
</template>
