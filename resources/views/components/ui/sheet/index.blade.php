@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/sheet --}}

@props([
    'open' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'sheet',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnSheet({ open: @js($open) })"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const SHEET_CLOSE_DURATION = 300;

            Alpine.data('bladcnSheet', (config = {}) => ({
                isOpen: config.open ?? false,
                isPresent: config.open ?? false,
                animationState: config.open ? 'open' : 'closed',
                isClosing: false,
                closeTimer: null,

                open() {
                    clearTimeout(this.closeTimer);
                    this.isClosing = false;
                    this.isOpen = true;
                    this.isPresent = true;
                    this.animationState = 'closed';
                    this.$store.scroll.lock();

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame
                                (() => {
                                    this.animationState =
                                        'open';
                                });
                        });
                    });
                },

                close() {
                    if (!this.isPresent) {
                        return;
                    }

                    clearTimeout(this.closeTimer);
                    this.isClosing = true;
                    this.isOpen = false;
                    this.animationState = 'closed';

                    this.closeTimer = setTimeout(() => {
                        this.isPresent = false;
                        this.isClosing = false;
                        this.$store.scroll.unlock();
                    }, SHEET_CLOSE_DURATION);
                },
            }));
        });
    </script>
@endPushOnce
