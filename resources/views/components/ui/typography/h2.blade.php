@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'mt-10 scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight transition-colors first:mt-0',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-h2',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h2 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h2>
