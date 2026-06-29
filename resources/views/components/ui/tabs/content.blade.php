@blaze(fold: true)

@props([
    'value' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex-1 outline-none',
    );

    $presetAttributes = [
        'role' => 'tabpanel',
        'data-slot' => 'tabs-content',
        'data-value' => $value,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :data-state="isActive(@js($value)) ? 'active' : 'inactive'"
    :hidden="!isActive(@js($value))"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-cloak
    x-show="isActive(@js($value))">
    {{ $slot }}
</div>
