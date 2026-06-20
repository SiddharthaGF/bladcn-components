@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'sidebar-group',
        'data-sidebar' => 'group',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div
    {{ $attributes->merge($presetAttributes)->class(['relative flex w-full min-w-0 flex-col p-2', $class]) }}>
    {{ $slot }}
</div>
