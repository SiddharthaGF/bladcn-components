@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'alert-title',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
