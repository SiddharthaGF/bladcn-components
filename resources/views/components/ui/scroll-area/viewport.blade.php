@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'size-full overflow-auto rounded-[inherit] outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden',
    );

    $presetAttributes = [
        'data-slot' => 'scroll-area-viewport',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-ref="viewport">
    {{ $slot }}
</div>
