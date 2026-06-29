@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/accordion --}}

@props([
    'id' => null,
    'type' => 'single',
    'collapsible' => false,
    'defaultValue' => null,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $accordionId = filled($id)
        ? $id
        : 'accordion-' . Illuminate\Support\Str::random(8);
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $defaultOpen = match (true) {
        is_array($defaultValue) => $defaultValue,
        filled($defaultValue) => [$defaultValue],
        default => [],
    };

    $presetAttributes = [
        'id' => $accordionId,
        'data-slot' => 'accordion',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div @keydown="$store.accordion.handleKeydown(@js($accordionId), $event)"
    {{ $attributes->merge($presetAttributes)->class($class) }}
    x-data="bladcnAccordionRoot({
        accordionId: @js($accordionId),
        type: @js($type),
        collapsible: @js($collapsible),
        defaultOpen: @js($defaultOpen),
    })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnAccordionRoot', (config = {}) => ({
                accordionId: config.accordionId,
                type: config.type ?? 'single',
                collapsible: config.collapsible ?? false,
                defaultOpen: config.defaultOpen ?? [],

                init() {
                    this.$store.accordion.register(this
                        .accordionId, {
                            mode: this.type,
                            defaultOpen: this.defaultOpen,
                        });
                },

                toggle(value) {
                    const store = this.$store.accordion;

                    if (store.isOpen(this.accordionId, value)) {
                        if (this.type === 'single' && !this
                            .collapsible) {
                            return;
                        }

                        store.close(this.accordionId, value);

                        return;
                    }

                    store.open(this.accordionId, value);
                },
            }));
        });
    </script>
@endPushOnce
