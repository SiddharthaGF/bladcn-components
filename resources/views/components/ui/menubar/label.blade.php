@blaze(fold: true)

@props([
    'inset' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'px-2 py-1.5 text-sm font-medium data-[inset]:pl-8',
    );

    $presetAttributes = [
        'data-slot' => 'menubar-label',
        'data-inset' => $inset ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
