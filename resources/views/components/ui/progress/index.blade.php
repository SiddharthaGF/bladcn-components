@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/progress --}}

@props([
    'value' => 0,
    'max' => 100,
    'style' => null,
    'class' => null,
])

@php
    $max = max(1, (float) $max);
    $value = max(0, min((float) $value, $max));
    $percent = ($value / $max) * 100;
    $translateX = 100 - $percent;

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative h-2 w-full overflow-hidden rounded-full bg-primary/20',
    );

    $indicatorClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'h-full w-full flex-1 bg-primary transition-all',
    );

    $presetAttributes = [
        'role' => 'progressbar',
        'aria-valuemin' => '0',
        'aria-valuemax' => (string) $max,
        'aria-valuenow' => (string) $value,
        'data-slot' => 'progress',
        'data-value' => $value,
        'data-max' => $max,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <div @class($indicatorClass)
        data-slot="progress-indicator"
        style="transform: translateX(-{{ $translateX }}%)"></div>
</div>
