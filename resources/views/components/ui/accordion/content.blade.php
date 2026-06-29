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

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-hidden text-sm',
    );

    $innerClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
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

<div :data-state="($store.accordion.isOpen(accordionId, @js($value)) || (
    @js($initiallyOpen) && !Object.hasOwn($store.accordion.groups[
        accordionId]?.open ?? {}, @js($value)))) ? 'open' :
'closed'"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($transition) x-collapse @endif
    @unless ($initiallyOpen)
        x-cloak
    @endunless
    x-bind="$store.accordion.panelProps(accordionId, @js($value))"
    x-show="$store.accordion.isOpen(accordionId, @js($value)) || (@js($initiallyOpen) && ! Object.hasOwn($store.accordion.groups[accordionId]?.open ?? {}, @js($value)))">
    <div @class([$innerClass, $class])>
        {{ $slot }}
    </div>
</div>
