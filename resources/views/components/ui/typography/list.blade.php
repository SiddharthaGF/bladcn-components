@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'my-6 ml-6 list-disc [&>li]:mt-2',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-list',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<ul {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</ul>
