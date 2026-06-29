@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        '[&_tr:last-child]:border-0',
    );

    $presetAttributes = [
        'data-slot' => 'table-body',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<tbody
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</tbody>
