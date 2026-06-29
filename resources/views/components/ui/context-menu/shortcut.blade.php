@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'ml-auto text-xs tracking-widest text-muted-foreground group-focus/dropdown-menu-item:text-accent-foreground group-hover/dropdown-menu-item:text-accent-foreground group-focus/context-menu-item:text-accent-foreground group-hover/context-menu-item:text-accent-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'context-menu-shortcut',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<span
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</span>
