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
            ? '-left-12 top-1/2 -translate-y-1/2'
            : '-top-12 left-1/2 -translate-x-1/2 rotate-90';
@endphp

<x-ui.button :size="$size"
    :variant="$variant"
    {{ $attributes->class(['absolute size-8 rounded-full', $positionClass, $class]) }}
    data-slot="carousel-previous"
    type="button"
    x-bind:disabled="!canScrollPrev"
    x-on:click="scrollPrev()">
    <x-ui.icon aria-hidden="true"
        name="chevron-left" />
    <span class="sr-only">Previous slide</span>
</x-ui.button>
