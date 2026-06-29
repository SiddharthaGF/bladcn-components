@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'mt-4 text-sm text-muted-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'table-caption',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<caption
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</caption>
