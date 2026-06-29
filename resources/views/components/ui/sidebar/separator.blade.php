@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'bg-sidebar-border mx-2 h-px w-auto shrink-0',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar-separator',
        'data-sidebar' => 'separator',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
</div>
