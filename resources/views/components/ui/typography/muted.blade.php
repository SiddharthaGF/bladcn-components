@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-sm text-muted-foreground',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-muted',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<p {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</p>
