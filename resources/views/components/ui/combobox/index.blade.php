@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/combobox --}}

@props([
    'name' => null,
    'defaultValue' => null,
    'defaultLabel' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'combobox',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}
    x-data="bladcnCombobox({
        defaultValue: @js($defaultValue),
        defaultLabel: @js($defaultLabel),
        disabled: @js($disabled),
    })"
    x-on:keydown.escape.window="close()">
    @if (filled($name))
        <input name="{{ $name }}"
            type="hidden"
            value="{{ $defaultValue }}"
            x-ref="hidden" />
    @endif

    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const VIEWPORT_MARGIN = 8;

            const clamp = (value, min, max) => Math.min(Math.max(value,
                min), max);

            Alpine.data('bladcnCombobox', (config = {}) => ({
                isOpen: false,
                isPositioned: false,
                value: config.defaultValue ?? null,
                label: config.defaultLabel ?? null,
                disabled: config.disabled ?? false,
                search: '',
                contentAlign: 'start',
                contentSide: 'bottom',
                contentSideOffset: 4,
                resolvedSide: 'bottom',
                portalStyle: '',

                registerContent(options) {
                    this.contentAlign = options.align ??
                        'start';
                    this.contentSide = options.side ??
                        'bottom';
                    this.contentSideOffset = options
                        .sideOffset ?? 4;
                },

                toggle() {
                    if (this.disabled) {
                        return;
                    }

                    if (this.isOpen) {
                        this.close();

                        return;
                    }

                    this.open();
                },

                open() {
                    if (this.disabled) {
                        return;
                    }

                    this.isOpen = true;
                    this.isPositioned = false;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this
                                .applyPosition();
                            requestAnimationFrame
                                (() => {
                                    this
                                        .applyPosition();
                                    this.isPositioned =
                                        true;
                                    this.$refs
                                        .searchInput
                                        ?.focus();
                                });
                        });
                    });
                },

                close() {
                    this.isOpen = false;
                    this.isPositioned = false;
                    this.portalStyle = '';
                    this.search = '';
                },

                measureContent(content, contentRect) {
                    if (!content) {
                        return contentRect;
                    }

                    return {
                        width: Math.max(
                            content.scrollWidth,
                            content.offsetWidth,
                            contentRect.width,
                        ),
                        height: Math.max(
                            content.scrollHeight,
                            content.offsetHeight,
                            contentRect.height,
                        ),
                    };
                },

                resolveContentSide(rect, contentRect, content) {
                    const offset = this.contentSideOffset;
                    const margin = VIEWPORT_MARGIN;
                    const preferred = this.contentSide;
                    const measured = this.measureContent(
                        content, contentRect);

                    if (measured.height <= 0) {
                        return preferred;
                    }

                    const spaceBelow =
                        window.innerHeight - margin - rect
                        .bottom - offset;
                    const spaceAbove = rect.top - margin -
                        offset;

                    if (preferred === 'bottom' &&
                        measured.height > spaceBelow &&
                        spaceAbove > spaceBelow) {
                        return 'top';
                    }

                    if (preferred === 'top' &&
                        measured.height > spaceAbove &&
                        spaceBelow > spaceAbove) {
                        return 'bottom';
                    }

                    return preferred;
                },

                applyPosition() {
                    const trigger = this.$refs.trigger;
                    const content = this.$refs.content;

                    if (!trigger) {
                        return;
                    }

                    const rect = trigger
                        .getBoundingClientRect();
                    const contentRect = content
                        ?.getBoundingClientRect() ?? {
                            width: 0,
                            height: 0,
                        };
                    const margin = VIEWPORT_MARGIN;
                    const offset = this.contentSideOffset;
                    const availableHeight = window.innerHeight -
                        margin * 2;
                    const measured = this.measureContent(
                        content, contentRect);
                    const side = this.resolveContentSide(rect,
                        contentRect, content);

                    this.resolvedSide = side;

                    let top = 0;
                    let left = rect.left;
                    let maxHeight = availableHeight;

                    if (side === 'bottom') {
                        maxHeight = Math.min(
                            availableHeight,
                            Math.max(
                                0,
                                window.innerHeight -
                                margin -
                                rect.bottom - offset,
                            ),
                        );
                        top = rect.bottom + offset;
                    } else {
                        maxHeight = Math.min(
                            availableHeight,
                            Math.max(0, rect.top - margin -
                                offset),
                        );
                        const placedHeight =
                            measured.height > 0 ?
                            Math.min(measured.height,
                                maxHeight) :
                            0;
                        top = rect.top - offset -
                            placedHeight;
                        top = Math.max(margin, top);
                    }

                    if (this.contentAlign === 'center') {
                        left = rect.left + rect.width / 2;
                    } else if (this.contentAlign === 'end') {
                        left = rect.right;
                    }

                    let transform = '';

                    if (this.contentAlign === 'center') {
                        transform =
                            'transform: translateX(-50%);';
                    } else if (this.contentAlign === 'end') {
                        transform =
                            'transform: translateX(-100%);';
                    }

                    if (measured.width > 0) {
                        left = clamp(
                            left,
                            margin,
                            Math.max(
                                margin,
                                window.innerWidth - margin -
                                measured.width,
                            ),
                        );
                    }

                    this.portalStyle = [
                            `left: ${left}px`,
                            `top: ${top}px`,
                            `width: ${rect.width}px`,
                            `max-height: ${maxHeight}px`,
                            transform,
                        ]
                        .filter(Boolean)
                        .join('; ');
                },

                select(value, label) {
                    this.value = value;
                    this.label = label;
                    this.close();

                    if (this.$refs.hidden) {
                        this.$refs.hidden.value = value;
                    }

                    this.$dispatch('combobox-change', {
                        value,
                        label
                    });
                },

                isSelected(value) {
                    return this.value === value;
                },

                matchesLabel(label) {
                    if (!this.search.trim()) {
                        return true;
                    }

                    return label.toLowerCase().includes(this
                        .search.trim().toLowerCase());
                },
            }));
        });
    </script>
@endPushOnce
