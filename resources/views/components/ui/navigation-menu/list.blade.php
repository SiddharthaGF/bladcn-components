@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group flex flex-1 list-none items-center justify-center gap-1',
    );

    $presetAttributes = [
        'data-slot' => 'navigation-menu-list',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<ul {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</ul>
