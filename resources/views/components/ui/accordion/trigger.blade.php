@blaze(fold: true)

@aware(['value', 'defaultValue', 'transition' => true, 'disabled' => false])

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
        'flex flex-1 items-start justify-between gap-4 rounded-md py-4 text-left text-sm font-medium transition-all outline-none hover:underline focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 [&[data-state=open]>svg]:rotate-180',
    );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'accordion-trigger',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div class="flex"
    data-slot="accordion-header">
    <button
        :data-state="($store.accordion.isOpen(accordionId, @js($value)) || (
            @js($initiallyOpen) && !Object.hasOwn($store.accordion
                .groups[accordionId]?.open ?? {},
                @js($value)))) ? 'open' : 'closed'"
        @click="toggle(@js($value))"
        {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
        @disabled($disabled)
        data-state="{{ $initiallyOpen ? 'open' : 'closed' }}"
        x-bind="$store.accordion.triggerProps(accordionId, @js($value))">
        {{ $slot }}
        <x-ui.icon @class([
            'pointer-events-none size-4 shrink-0 translate-y-0.5 text-muted-foreground',
            'transition-transform duration-200' => $transition,
        ])
            aria-hidden="true"
            name="chevron-down" />
    </button>
</div>
