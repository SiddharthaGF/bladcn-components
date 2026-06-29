@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'inline-flex items-center gap-1.5',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<li {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</li>
