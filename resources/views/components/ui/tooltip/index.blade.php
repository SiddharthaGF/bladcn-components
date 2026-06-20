@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/tooltip --}}

@aware(['delayDuration'])

@props([
    'delayDuration' => null,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $resolvedDelay = $delayDuration ?? 0;

    $presetAttributes = [
        'data-slot' => 'tooltip',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative inline-block w-fit', $class]) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnTooltip({ delayDuration: @js((int) $resolvedDelay) })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnTooltip', (config = {}) => ({
                isOpen: false,
                delayDuration: config.delayDuration ?? 0,
                timeout: null,

                show() {
                    clearTimeout(this.timeout);

                    if (this.delayDuration > 0) {
                        this.timeout = setTimeout(() => {
                            this.isOpen = true;
                        }, this.delayDuration);

                        return;
                    }

                    this.isOpen = true;
                },

                hide() {
                    clearTimeout(this.timeout);
                    this.isOpen = false;
                },
            }));
        });
    </script>
@endPushOnce
