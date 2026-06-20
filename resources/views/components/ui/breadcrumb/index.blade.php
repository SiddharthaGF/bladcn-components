@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/breadcrumb --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'aria-label' => 'breadcrumb',
        'data-slot' => 'breadcrumb',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<nav {{ $attributes->merge($presetAttributes)->class($class) }}>
    {{ $slot }}
</nav>
