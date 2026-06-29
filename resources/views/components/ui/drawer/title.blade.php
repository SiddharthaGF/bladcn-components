@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'font-semibold text-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'drawer-title',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h2 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h2>
