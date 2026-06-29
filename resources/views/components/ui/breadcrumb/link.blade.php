@blaze(fold: true)

@props([
    'asChild' => false,
    'href' => '#',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'transition-colors hover:text-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-link',
        'href' => $href,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    default-tag="a">
    {{ $slot }}
</x-ui.abstract>
