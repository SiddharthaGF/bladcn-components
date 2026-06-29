@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-sm leading-none font-medium',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-small',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<small
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</small>
