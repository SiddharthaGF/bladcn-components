@blaze(fold: true)

@props([
    'inset' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'px-2 py-1.5 text-xs font-medium text-muted-foreground data-[inset]:pl-8',
    );

    $presetAttributes = [
        'data-slot' => 'context-menu-label',
        'data-inset' => $inset ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
