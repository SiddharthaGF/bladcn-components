@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-lg leading-none font-semibold',
    );

    $presetAttributes = [
        'data-slot' => 'dialog-title',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<h2 {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</h2>
