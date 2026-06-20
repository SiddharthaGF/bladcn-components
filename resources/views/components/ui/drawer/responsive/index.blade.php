@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/drawer --}}

<x-ui.body-scroll-lock />

@props([
    'open' => false,
    'breakpoint' => 768,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'drawer-responsive',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnDrawerResponsive({ open: @js($open), breakpoint: @js($breakpoint) })"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const DRAWER_CLOSE_DURATION = 300;

            Alpine.data('bladcnDrawerResponsive', (config = {}) => ({
                isOpen: config.open ?? false,
                isPresent: config.open ?? false,
                animationState: config.open ? 'open' : 'closed',
                isClosing: false,
                isDesktop: false,
                breakpoint: config.breakpoint ?? 768,
                closeTimer: null,
                mediaQuery: null,

                init() {
                    this.mediaQuery = window.matchMedia(
                        `(min-width: ${this.breakpoint}px)`,
                    );
                    this.isDesktop = this.mediaQuery.matches;

                    this.mediaQuery.addEventListener('change', (
                        event) => {
                        if (this.isOpen || this
                            .isPresent) {
                            this.close();
                        }

                        this.isDesktop = event.matches;
                    });
                },

                open() {
                    clearTimeout(this.closeTimer);
                    this.isClosing = false;
                    this.isOpen = true;
                    this.isPresent = true;
                    window.bladcnBodyScrollLock?.lock();

                    if (!this.isDesktop) {
                        this.animationState = 'closed';

                        this.$nextTick(() => {
                            requestAnimationFrame(
                                () => {
                                    requestAnimationFrame
                                        (() => {
                                            this.animationState =
                                                'open';
                                        });
                                });
                        });
                    }
                },

                close() {
                    if (!this.isOpen && !this.isPresent) {
                        return;
                    }

                    clearTimeout(this.closeTimer);

                    if (this.isDesktop) {
                        this.isOpen = false;
                        this.isPresent = false;
                        window.bladcnBodyScrollLock?.unlock();

                        return;
                    }

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
