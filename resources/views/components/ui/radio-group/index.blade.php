@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/radio-group --}}

@props([
    'name' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'grid gap-3',
    );

    $presetAttributes = [
        'role' => 'radiogroup',
        'data-slot' => 'radio-group',
    ];

    if (filled($name)) {
        $presetAttributes['data-name'] = $name;
    }

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
