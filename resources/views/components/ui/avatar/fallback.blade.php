@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex size-full items-center justify-center rounded-full bg-muted text-sm text-muted-foreground group-data-[size=sm]/avatar:text-xs',
    );

    $presetAttributes = [
        'data-slot' => 'avatar-fallback',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-cloak
    x-show="showFallback">
    {{ $slot }}
</div>
