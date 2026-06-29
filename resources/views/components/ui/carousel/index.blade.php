@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/carousel --}}

@props([
    'orientation' => 'horizontal',
    'opts' => [],
    'plugin' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative',
    );

    $presetAttributes = [
        'role' => 'region',
        'aria-roledescription' => 'carousel',
        'data-slot' => 'carousel',
        'data-orientation' => $orientation,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($plugin === 'autoplay') x-on:mouseenter="pauseAutoplay()"
        x-on:mouseleave="resumeAutoplay()" @endif
    x-data="bladcnCarousel({ orientation: @js($orientation), opts: @js($opts), plugin: @js($plugin) })"
    x-on:keydown.capture="onKeydown($event)">
    @php($__env->pushConsumableComponentData(['orientation' => $orientation]))
    {{ $slot }}
    @php($__env->popConsumableComponentData())
</div>
