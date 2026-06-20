@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/drawer --}}

<x-ui.body-scroll-lock />

@props([
    'open' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'drawer',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnDrawer({ open: @js($open) })"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const DRAWER_CLOSE_DURATION = 300;

            Alpine.data('bladcnDrawer', (config = {}) => ({
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
                    window.bladcnBodyScrollLock?.lock();

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
                        window.bladcnBodyScrollLock
                            ?.unlock();
                    }, DRAWER_CLOSE_DURATION);
                },
            }));
        });
    </script>
@endPushOnce
