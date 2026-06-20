@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/accordion --}}

@props([
    'type' => 'single',
    'collapsible' => false,
    'defaultValue' => null,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $defaultOpen = match (true) {
        is_array($defaultValue) => $defaultValue,
        filled($defaultValue) => [$defaultValue],
        default => [],
    };

    $presetAttributes = [
        'data-slot' => 'accordion',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-data="bladcnAccordion({
        type: @js($type),
        collapsible: @js($collapsible),
        defaultValue: @js($defaultOpen),
    })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnAccordion', (config = {}) => ({
                type: config.type ?? 'single',
                collapsible: config.collapsible ?? false,
                openItems: normalizeOpenItems(config
                    .defaultValue),

                toggle(value) {
                    if (this.type === 'multiple') {
                        if (this.openItems.includes(value)) {
                            this.openItems = this.openItems
                                .filter(
                                    (item) => item !== value,
                                );
                        } else {
                            this.openItems = [...this.openItems,
                                value
                            ];
                        }

                        return;
                    }

                    if (this.openItems.includes(value)) {
                        this.openItems = this.collapsible ? [] :
                            this.openItems;

                        return;
                    }

                    this.openItems = [value];
                },

                isOpen(value) {
                    return this.openItems.includes(value);
                },
            }));
        });

        function normalizeOpenItems(defaultValue) {
            if (Array.isArray(defaultValue)) {
                return defaultValue;
            }

            if (defaultValue) {
                return [defaultValue];
            }

            return [];
        }
    </script>
@endPushOnce
