@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex w-full min-w-0 flex-col gap-1',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar-menu',
        'data-sidebar' => 'menu',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<ul {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</ul>
