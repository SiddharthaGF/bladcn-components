@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/scroll-area --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative',
    );

    $presetAttributes = [
        'data-slot' => 'scroll-area',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnScrollArea()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnScrollArea', () => ({
                updateThumbs() {
                    const viewport = this.$refs.viewport;

                    if (!viewport) {
                        return;
                    }

                    this.updateThumb('vertical', viewport);
                    this.updateThumb('horizontal', viewport);
                },

                updateThumb(orientation, viewport) {
                    const thumb = this.$refs[
                        `${orientation}Thumb`];

                    if (!thumb) {
                        return;
                    }

                    if (orientation === 'vertical') {
                        if (viewport.scrollHeight <= viewport
                            .clientHeight) {
                            thumb.style.height = '0px';

                            return;
                        }

                        const ratio = viewport.clientHeight /
                            viewport.scrollHeight;
                        const thumbHeight = Math.max(ratio *
                            viewport.clientHeight, 24);
                        const maxScroll = viewport
                            .scrollHeight - viewport
                            .clientHeight;

                        thumb.style.height = `${thumbHeight}px`;
                        thumb.style.width = '';
                        thumb.style.transform = `translateY(${
                    (viewport.scrollTop / maxScroll)
* (viewport.clientHeight - thumbHeight)
                }px)`;

                        return;
                    }

                    if (viewport.scrollWidth <= viewport
                        .clientWidth) {
                        thumb.style.width = '0px';

                        return;
                    }

                    const ratio = viewport.clientWidth /
                        viewport.scrollWidth;
                    const thumbWidth = Math.max(ratio * viewport
                        .clientWidth, 24);
                    const maxScroll = viewport.scrollWidth -
                        viewport.clientWidth;

                    thumb.style.width = `${thumbWidth}px`;
                    thumb.style.height = '';
                    thumb.style.transform = `translateX(${
                (viewport.scrollLeft / maxScroll) * (viewport.clientWidth - thumbWidth)
            }px)`;
                },

                init() {
                    this.$nextTick(() => this.updateThumbs());
                    this.$refs.viewport?.addEventListener(
                        'scroll', () => this.updateThumbs());
                    window.addEventListener('resize', () => this
                        .updateThumbs());
                },
            }));
        });
    </script>
@endPushOnce
