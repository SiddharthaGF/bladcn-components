@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/dropdown-menu --}}

@props([
    'open' => false,
    'defaultRadioValue' => null,
    'checkboxes' => [],
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'dropdown-menu',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative inline-block', $class]) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnDropdownMenu({
        open: @js($open),
        radioValue: @js($defaultRadioValue),
        checkboxes: @js($checkboxes),
    })"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const VIEWPORT_MARGIN = 8;

            const clamp = (value, min, max) => Math.min(Math.max(value,
                min), max);

            Alpine.data('bladcnDropdownMenu', (config = {}) => ({
                isOpen: config.open ?? false,
                checkboxes: config.checkboxes ?? {},
                radioValue: config.radioValue ?? null,
                contentAlign: 'start',
                contentSide: 'bottom',
                contentSideOffset: 4,
                resolvedSide: 'bottom',
                portalStyle: '',

                registerContent(options) {
                    this.contentAlign = options.align ??
                        'start';
                    this.contentSide = options.side ?? 'bottom';
                    this.contentSideOffset = options
                        .sideOffset ?? 4;
                },

                toggle(event) {
                    if (this.isOpen) {
                        this.close();

                        return;
                    }

                    this.open(event);
                },

                open(event) {
                    this.$store.scroll.lock();
                    this.isOpen = true;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this.applyPosition(
                                event);
                            requestAnimationFrame
                                (() => this
                                    .applyPosition(
                                        event));
                        });
                    });
                },

                close() {
                    if (!this.isOpen) {
                        return;
                    }

                    this.isOpen = false;
                    this.portalStyle = '';
                    this.closeAllSubs();
                    this.$store.scroll.unlock();
                },

                closeIfOutside(event) {
                    if (event.target.closest(
                            '[data-slot="dropdown-menu-sub"]'
                        )) {
                        return;
                    }

                    this.close();
                },

                closeAllSubs() {
                    this.$root
                        .querySelectorAll(
                            '[data-slot="dropdown-menu-sub"]')
                        .forEach((element) => {
                            Alpine.$data(element)?.closeSub
                                ?.();
                        });
                },

                measureContent(content, contentRect) {
                    if (!content) {
                        return contentRect;
                    }

                    const height = Math.max(
                        content.scrollHeight,
                        content.offsetHeight,
                        contentRect.height,
                    );
                    const width = Math.max(
                        content.scrollWidth,
                        content.offsetWidth,
                        contentRect.width,
                    );

                    return {
                        width,
                        height
                    };
                },

                resolveContentSide(rect, contentRect, content) {
                    const offset = this.contentSideOffset;
                    const margin = VIEWPORT_MARGIN;
                    const preferred = this.contentSide;
                    const measured = this.measureContent(
                        content, contentRect);

                    if (preferred === 'bottom' || preferred ===
                        'top') {
                        if (measured.height <= 0) {
                            return preferred;
                        }

                        const spaceBelow =
                            window.innerHeight - margin - rect
                            .bottom - offset;
                        const spaceAbove = rect.top - margin -
                            offset;
                        const height = measured.height;

                        if (preferred === 'bottom') {
                            if (
                                height > spaceBelow &&
                                (height <= spaceAbove ||
                                    spaceAbove > spaceBelow)
                            ) {
                                return 'top';
                            }

                            return 'bottom';
                        }

                        if (
                            height > spaceAbove &&
                            (height <= spaceBelow ||
                                spaceBelow > spaceAbove)
                        ) {
                            return 'bottom';
                        }

                        return 'top';
                    }

                    if (preferred === 'left' || preferred ===
                        'right') {
                        if (measured.width <= 0) {
                            return preferred;
                        }

                        const spaceRight =
                            window.innerWidth - margin - rect
                            .right - offset;
                        const spaceLeft = rect.left - margin -
                            offset;
                        const width = measured.width;

                        if (preferred === 'right') {
                            if (
                                width > spaceRight &&
                                (width <= spaceLeft ||
                                    spaceLeft > spaceRight)
                            ) {
                                return 'left';
                            }

                            return 'right';
                        }

                        if (
                            width > spaceLeft &&
                            (width <= spaceRight || spaceRight >
                                spaceLeft)
                        ) {
                            return 'right';
                        }

                        return 'left';
                    }

                    return preferred;
                },

                applyPosition(event) {
                    const trigger = this.$refs.trigger ?? event
                        ?.currentTarget;
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
                    const side = this.resolveContentSide(
                        rect,
                        contentRect,
                        content,
                    );

                    this.resolvedSide = side;

                    let top = 0;
                    let left = 0;
                    let transform = '';
                    let maxHeight = availableHeight;

                    if (side === 'bottom' || side === 'top') {
                        const spaceBelow =
                            window.innerHeight - margin - rect
                            .bottom - offset;
                        const spaceAbove = rect.top - margin -
                            offset;

                        if (side === 'bottom') {
                            maxHeight = Math.min(
                                availableHeight,
                                Math.max(0, spaceBelow),
                            );
                            top = rect.bottom + offset;
                        } else {
                            maxHeight = Math.min(
                                availableHeight,
                                Math.max(0, spaceAbove),
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

                        ({
                            left,
                            transform
                        } = this.horizontalAlign(rect));
                    } else {
                        const spaceRight =
                            window.innerWidth - margin - rect
                            .right - offset;
                        const spaceLeft = rect.left - margin -
                            offset;

                        switch (side) {
                            case 'left':
                                left =
                                    rect.left -
                                    offset -
                                    Math.min(
                                        measured.width,
                                        Math.max(0, spaceLeft),
                                    );
                                break;
                            default:
                                left = rect.right + offset;
                        }

                        ({
                            top,
                            transform
                        } = this.verticalAlign(rect));

                        if (measured.width > 0) {
                            left = clamp(
                                left,
                                margin,
                                Math.max(
                                    margin,
                                    window.innerWidth -
                                    margin -
                                    measured.width,
                                ),
                            );
                        }
                    }

                    if (
                        (side === 'bottom' || side === 'top') &&
                        measured.width > 0
                    ) {
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
                            `max-height: ${maxHeight}px`,
                            transform,
                        ]
                        .filter(Boolean)
                        .join('; ');
                },

                horizontalAlign(rect) {
                    let left = rect.left;
                    let transform = '';

                    if (this.contentAlign === 'center') {
                        left = rect.left + rect.width / 2;
                        transform =
                            'transform: translateX(-50%);';
                    } else if (this.contentAlign === 'end') {
                        left = rect.right;
                        transform =
                            'transform: translateX(-100%);';
                    }

                    return {
                        left,
                        transform
                    };
                },

                verticalAlign(rect) {
                    let top = rect.top;
                    let transform = '';

                    if (this.contentAlign === 'center') {
                        top = rect.top + rect.height / 2;
                        transform =
                            'transform: translateY(-50%);';
                    } else if (this.contentAlign === 'end') {
                        top = rect.bottom;
                        transform =
                            'transform: translateY(-100%);';
                    }

                    return {
                        top,
                        transform
                    };
                },

                toggleCheckbox(key) {
                    this.checkboxes[key] = !this.checkboxes[
                        key];
                },

                isCheckboxChecked(key) {
                    return Boolean(this.checkboxes[key]);
                },

                selectRadio(value) {
                    this.radioValue = value;
                },

                isRadioSelected(value) {
                    return String(this.radioValue) === String(
                        value);
                },
            }));
        });
    </script>
@endPushOnce
