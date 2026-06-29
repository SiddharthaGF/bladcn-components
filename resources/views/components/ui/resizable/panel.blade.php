@blaze(fold: true)

@props([
    'index' => 0,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-hidden',
    );

    $presetAttributes = [
        'data-slot' => 'resizable-panel',
        'data-panel-index' => $index,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:style="panelStyle({{ $index }})">
    {{ $slot }}
</div>
