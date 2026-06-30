@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex min-w-0 flex-col gap-2 overflow-x-hidden p-2',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar-footer',
        'data-sidebar' => 'footer',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
