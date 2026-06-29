@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'scroll-m-20 text-xl font-semibold tracking-tight',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-h4',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h4 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h4>
