@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/typography --}}

@props([
    'id' => null,
    'class' => null,
    'style' => null,
])

@php
    $classes = ['divide-y overflow-hidden rounded-lg border', $class];
    $attrs = [
        'id' => $id,
        'style' => $style,
    ];
@endphp

<div {{ $attributes->class($classes)->merge($attrs) }}
    data-slot="highlighted-code">
    {{ $slot }}
</div>
