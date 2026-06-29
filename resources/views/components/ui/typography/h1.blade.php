@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'scroll-m-20 text-4xl font-extrabold tracking-tight text-balance',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-h1',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h1 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h1>
