@blaze(fold: true)

@props([
    'heading' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-hidden p-1 text-foreground',
    );

    $headingClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-muted-foreground px-2 py-1.5 text-xs font-medium',
    );

    $presetAttributes = [
        'data-slot' => 'command-group',
        'role' => 'group',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    @if (filled($heading))
        <div class="{{ $headingClass }}"
            data-slot="command-group-heading">
            {{ $heading }}
        </div>
    @endif

    {{ $slot }}
</div>
