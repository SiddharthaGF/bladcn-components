@blaze(fold: true)

@props([
    'color' => 'var(--chart-1)',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex items-center gap-1.5 [&>svg]:h-3 [&>svg]:w-3 [&>svg]:text-muted-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'chart-legend-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <span class="size-2 shrink-0 rounded-[2px]"
        style="background-color: {{ $color }}"></span>
    {{ $slot }}
</div>
