@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/toggle-group --}}

@props([
    'type' => 'single',
    'variant' => 'default',
    'size' => 'default',
    'spacing' => 0,
    'defaultValue' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/toggle-group flex w-fit items-center rounded-md data-[spacing=default]:data-[variant=outline]:shadow-xs',
    );

    if ($spacing > 0) {
        $presetClass->add('gap-2');
    }

    $presetAttributes = [
        'role' => 'group',
        'data-slot' => 'toggle-group',
        'data-variant' => $variant,
        'data-size' => $size,
        'data-spacing' => $spacing,
        'data-type' => $type,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnToggleGroup({
        type: @js($type),
        defaultValue: @js($defaultValue),
    })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnToggleGroup', (config = {}) => ({
                type: config.type ?? 'single',
                value: config.type === 'multiple' ? [...(config
                    .defaultValue ?? [])] : (config
                    .defaultValue ?? null),

                toggle(itemValue) {
                    if (this.type === 'multiple') {
                        if (this.value.includes(itemValue)) {
                            this.value = this.value.filter((
                                    value) => value !==
                                itemValue);
                        } else {
                            this.value = [...this.value,
                                itemValue
                            ];
                        }

                        return;
                    }

                    this.value = this.value === itemValue ?
                        null : itemValue;
                },

                isOn(itemValue) {
                    if (this.type === 'multiple') {
                        return this.value.includes(itemValue);
                    }

                    return this.value === itemValue;
                },
            }));
        });
    </script>
@endPushOnce
