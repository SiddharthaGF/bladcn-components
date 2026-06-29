@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'py-6 text-center text-sm text-muted-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'combobox-empty',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-cloak
    x-show="search.trim() !== ''">
    {{ $slot }}
</div>
