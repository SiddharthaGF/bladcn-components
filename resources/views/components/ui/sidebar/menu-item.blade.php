@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'sidebar-menu-item',
        'data-sidebar' => 'menu-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<li
    {{ $attributes->merge($presetAttributes)->class(['group/menu-item relative flex w-full', $class]) }}>
    {{ $slot }}
</li>
