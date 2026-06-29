@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'mt-8 scroll-m-20 text-2xl font-semibold tracking-tight',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-h3',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h3 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h3>
