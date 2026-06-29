@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/spinner --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'size-4 animate-spin',
    );

    $presetAttributes = [
        'role' => 'status',
        'aria-label' => 'Loading',
        'data-slot' => 'spinner',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.icon
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    name="loader-circle" />
