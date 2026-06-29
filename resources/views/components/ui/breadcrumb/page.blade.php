@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'font-normal text-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-page',
        'role' => 'link',
        'aria-disabled' => 'true',
        'aria-current' => 'page',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<span
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</span>
