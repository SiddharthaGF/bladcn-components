@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        '[&_tr]:border-b',
    );

    $presetAttributes = [
        'data-slot' => 'table-header',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<thead
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</thead>
