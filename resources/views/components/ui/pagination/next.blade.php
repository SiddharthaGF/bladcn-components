@blaze(fold: true)

@props([
    'href' => '#',
    'style' => null,
    'class' => null,
])

<x-ui.pagination.link :href="$href"
    {{ $attributes->class(['gap-1 px-2.5 sm:pr-2.5', $class]) }}
    aria-label="Go to next page"
    size="default">
    <span class="hidden sm:block">Next</span>
    <x-ui.icon aria-hidden="true"
        class="size-4"
        name="chevron-right" />
</x-ui.pagination.link>
