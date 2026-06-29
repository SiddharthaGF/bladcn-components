@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'leading-7 [&:not(:first-child)]:mt-6',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-p',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<p {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</p>
