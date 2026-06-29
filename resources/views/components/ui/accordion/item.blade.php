@blaze(fold: true)

@aware(['defaultValue'])

@props([
    'value' => null,
    'disabled' => false,
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
        'border-b last:border-b-0',
    );

    $presetAttributes = [
        'data-slot' => 'accordion-item',
        'data-value' => $value,
        'data-disabled' => $disabled ? '' : null,
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
    data-state="{{ $initiallyOpen ? 'open' : 'closed' }}"
    x-init="$store.accordion.registerItem(accordionId, @js($value), @js((bool) $disabled))">
    {{ $slot }}
</div>
