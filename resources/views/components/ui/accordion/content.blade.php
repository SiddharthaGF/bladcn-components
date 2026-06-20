@blaze(fold: true)

@aware(['value', 'defaultValue', 'transition' => true])

@props([
    'style' => null,
    'class' => null,
])

@php
    $defaultOpen = match (true) {
        is_array($defaultValue) => $defaultValue,
        filled($defaultValue) => [$defaultValue],
        default => [],
    };

    $initiallyOpen = in_array($value, $defaultOpen, true);

    $presetClass = new \AiluraCode\Bladcn\Support\ClassResolver()->add(
        'overflow-hidden text-sm',
    );

    $innerClass = new \AiluraCode\Bladcn\Support\ClassResolver()->add(
        'pt-0 pb-4',
    );

    $presetAttributes = [
        'data-slot' => 'accordion-content',
        'data-state' => $initiallyOpen ? 'open' : 'closed',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :data-state="isOpen(@js($value)) ? 'open' : 'closed'"
    {{ $attributes->merge($presetAttributes)->class([$presetClass]) }}
    @if ($transition) x-collapse.duration.200ms @endif
    @unless ($initiallyOpen)
        x-cloak
    @endunless
    x-show="isOpen(@js($value))">
    <div @class([$innerClass, $class])>
        {{ $slot }}
    </div>
</div>
