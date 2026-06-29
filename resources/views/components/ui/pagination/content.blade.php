@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex flex-row items-center gap-1',
    );

    $presetAttributes = [
        'data-slot' => 'pagination-content',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<ul {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</ul>
