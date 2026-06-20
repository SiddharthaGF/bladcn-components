@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/hover-card --}}

@props([
    'openDelay' => 700,
    'closeDelay' => 300,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'hover-card',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative inline-block w-fit', $class]) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnHoverCard({ openDelay: @js($openDelay), closeDelay: @js($closeDelay) })"
    x-on:mouseenter="show()"
    x-on:mouseleave="hide()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnHoverCard', (config = {}) => ({
                isOpen: false,
                openDelay: config.openDelay ?? 700,
                closeDelay: config.closeDelay ?? 300,
                openTimeout: null,
                closeTimeout: null,

                show() {
                    clearTimeout(this.closeTimeout);
                    clearTimeout(this.openTimeout);

                    this.openTimeout = setTimeout(() => {
                        this.isOpen = true;
                    }, this.openDelay);
                },

                hide() {
                    clearTimeout(this.openTimeout);

                    this.closeTimeout = setTimeout(() => {
                        this.isOpen = false;
                    }, this.closeDelay);
                },
            }));
        });
    </script>
@endPushOnce
