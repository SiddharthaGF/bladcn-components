@blaze(fold: true)

@aware(['orientation' => 'horizontal'])

@props([
    'variant' => 'outline',
    'size' => 'icon',
    'class' => null,
])

@php
    $positionClass =
        $orientation === 'horizontal'
            ? '-right-12 top-1/2 -translate-y-1/2'
            : '-bottom-12 left-1/2 -translate-x-1/2 rotate-90';
@endphp

<x-ui.button :size="$size"
    :variant="$variant"
    {{ $attributes->class(['absolute size-8 rounded-full', $positionClass, $class]) }}
    data-slot="carousel-next"
    type="button"
    x-bind:disabled="!canScrollNext"
    x-on:click="scrollNext()">
    <x-ui.icon aria-hidden="true"
        name="chevron-right" />
    <span class="sr-only">Next slide</span>
</x-ui.button>
