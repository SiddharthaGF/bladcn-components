@blaze(fold: true)

@props([
    'src' => null,
    'alt' => '',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'aspect-square size-full object-cover',
    );

    $presetAttributes = [
        'data-slot' => 'avatar-image',
        'src' => $src,
        'alt' => $alt,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<img {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-on:error="onImageError()"
    x-on:load="onImageLoad()"
    x-show="!showFallback" />
