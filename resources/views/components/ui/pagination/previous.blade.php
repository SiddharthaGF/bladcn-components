@blaze(fold: true)

@props([
    'href' => '#',
    'style' => null,
    'class' => null,
])

<x-ui.pagination.link :href="$href"
    {{ $attributes->class(['gap-1 px-2.5 sm:pl-2.5', $class]) }}
    aria-label="Go to previous page"
    size="default">
    <x-ui.icon aria-hidden="true"
        class="size-4"
        name="chevron-left" />
    <span class="hidden sm:block">Previous</span>
</x-ui.pagination.link>
