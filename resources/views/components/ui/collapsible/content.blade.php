@blaze(fold: true)

@aware(['open' => false, 'transition' => true])

@props([
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-hidden',
    );

    $presetAttributes = [
        'data-slot' => 'collapsible-content',
        'data-state' => $open ? 'open' : 'closed',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :data-state="isOpen ? 'open' : 'closed'"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($transition) x-collapse.duration.200ms @endif
    @unless ($open)
        x-cloak
    @endunless
    x-show="isOpen">
    {{ $slot }}
</div>
