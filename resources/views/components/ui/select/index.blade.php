@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/select --}}

<x-ui.body-scroll-lock />

@props([
    'name' => null,
    'defaultValue' => null,
    'defaultLabel' => null,
    'disabled' => false,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'select',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div @keydown.escape.window="close()"
    {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}
    x-data="bladcnSelect({
        defaultValue: @js($defaultValue),
        defaultLabel: @js($defaultLabel),
        disabled: @js($disabled),
    })">
    @if (filled($name))
        <input :value="value ?? ''"
            name="{{ $name }}"
            type="hidden"
            x-ref="hidden" />
    @endif

    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const CONTENT_MARGIN = 8;

            const clamp = (value, [min, max]) => Math.min(Math.max(value,
                min), max);

            Alpine.data('bladcnSelect', (config = {}) => ({
                isOpen: false,
                isPositioned: false,
                value: config.defaultValue ?? null,
                label: config.defaultLabel ?? null,
                disabled: config.disabled ?? false,
                contentPosition: 'item-aligned',
                contentAlign: 'center',
                contentSideOffset: 4,
                portalStyle: '',
                canScrollUp: false,
                canScrollDown: false,
                scrollAutoTimer: null,
                shouldRepositionForScrollButtons: true,
                needsContentScroll: false,

                registerContent(options) {
                    this.contentPosition = options.position ??
                        'item-aligned';
                    this.contentAlign = options.align ??
                        'center';
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
                    window.bladcnBodyScrollLock?.lock();
                    this.isPositioned = this.contentPosition !==
                        'item-aligned';

                    if (this.contentPosition === 'popper') {
                        this.applyPopperPosition();
                        this.isOpen = true;
                        this.$nextTick(() => {
                            this.updateScrollButtons();
                            this.isPositioned = true;
                        });

                        return;
                    }

                    this.isOpen = true;
                    this.shouldRepositionForScrollButtons =
                        true;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this
                                .applyItemAlignedPosition();
                            this
                                .finishItemAlignedPositioning();
                            this.isPositioned =
                                true;
                        });
                    });
                },

                close() {
                    if (!this.isOpen) {
                        return;
                    }

                    this.stopScrollAuto();
                    this.isOpen = false;
                    this.isPositioned = false;
                    this.portalStyle = '';
                    this.canScrollUp = false;
                    this.canScrollDown = false;
                    this.needsContentScroll = false;
                    this.shouldRepositionForScrollButtons =
                        true;
                    window.bladcnBodyScrollLock?.unlock();
                },

                updateScrollButtons() {
                    const viewport = this.$refs.viewport;

                    if (!viewport) {
                        return;
                    }

                    this.canScrollUp = viewport.scrollTop > 0;

                    const maxScroll = viewport.scrollHeight -
                        viewport.clientHeight;
                    this.canScrollDown =
                        maxScroll > 0 && Math.ceil(viewport
                            .scrollTop) < maxScroll;
                },

                scrollViewportStep(direction) {
                    const viewport = this.$refs.viewport;
                    const item =
                        this.getSelectedItem() ??
                        viewport?.querySelector(
                            '[data-slot="select-item"]:not([data-disabled])',
                        );

                    if (!viewport || !item) {
                        return;
                    }

                    const delta =
                        direction === 'up' ? -item
                        .offsetHeight : item.offsetHeight;

                    viewport.scrollTop += delta;
                    this.updateScrollButtons();
                },

                startScrollAuto(direction) {
                    this.stopScrollAuto();
                    this.scrollViewportStep(direction);
                    this.scrollAutoTimer = window.setInterval(
                        () => {
                            this.scrollViewportStep(
                                direction);
                        }, 50);
                },

                stopScrollAuto() {
                    if (this.scrollAutoTimer !== null) {
                        window.clearInterval(this
                            .scrollAutoTimer);
                        this.scrollAutoTimer = null;
                    }
                },

                finishItemAlignedPositioning() {
                    const hadScrollButtons = this.canScrollUp ||
                        this.canScrollDown;

                    this.updateScrollButtons();

                    const hasScrollButtons = this.canScrollUp ||
                        this.canScrollDown;

                    if (
                        this.shouldRepositionForScrollButtons &&
                        !hadScrollButtons &&
                        hasScrollButtons
                    ) {
                        this.shouldRepositionForScrollButtons =
                            false;
                        requestAnimationFrame(() => {
                            this
                                .applyItemAlignedPosition();
                            this.updateScrollButtons();
                        });
                    }
                },

                selectFromItem(element) {
                    const value = element.dataset.value ?? null;
                    const label =
                        element
                        .querySelector(
                            '[data-slot="select-item-text"]')
                        ?.textContent?.trim() ?? '';

                    this.select(value, label);
                },

                select(value, label) {
                    this.value = value;
                    this.label = label;
                    this.close();

                    this.$dispatch('select-change', {
                        value,
                        label
                    });
                },

                isSelected(value) {
                    return this.value === value;
                },

                portalWrapperStyle() {
                    return this.portalStyle;
                },

                getSelectedItem() {
                    const viewport = this.$refs.viewport;

                    if (!viewport) {
                        return null;
                    }

                    if (this.value != null && this.value !==
                        '') {
                        const selected = viewport.querySelector(
                            `[data-slot="select-item"][data-value="${CSS.escape(String(this.value))}"]`,
                        );

                        if (selected && !selected.hasAttribute(
                                'data-disabled')) {
                            return selected;
                        }
                    }

                    return viewport.querySelector(
                        '[data-slot="select-item"]:not([data-disabled])',
                    );
                },

                applyPopperPosition() {
                    const trigger = this.$refs.trigger;

                    if (!trigger) {
                        return;
                    }

                    const rect = trigger
                        .getBoundingClientRect();
                    let left = rect.left;
                    const top = rect.bottom + this
                        .contentSideOffset;
                    const width = `width: ${rect.width}px;`;

                    if (this.contentAlign === 'center') {
                        left = rect.left + rect.width / 2;
                    }

                    if (this.contentAlign === 'end') {
                        left = rect.right;
                    }

                    const transform =
                        this.contentAlign === 'center' ?
                        'transform: translateX(-50%);' :
                        this.contentAlign === 'end' ?
                        'transform: translateX(-100%);' :
                        '';

                    const availableHeight = window.innerHeight -
                        CONTENT_MARGIN * 2;

                    this.portalStyle =
                        `left: ${left}px; top: ${top}px; max-height: ${availableHeight}px; ${width} ${transform}`;
                },

                measureScrollChromeHeight(content) {
                    let height = 0;

                    for (const slot of [
                            'select-scroll-up-button',
                            'select-scroll-down-button',
                        ]) {
                        const node = content.querySelector(
                            `[data-slot="${slot}"]`);

                        if (node && node.offsetParent !==
                            null) {
                            height += node.offsetHeight;
                        }
                    }

                    return height;
                },

                applyItemAlignedPosition() {
                    const trigger = this.$refs.trigger;
                    const valueNode = this.$refs.value ??
                        trigger;
                    const content = this.$refs.content;
                    const viewport = this.$refs.viewport;

                    if (!trigger || !content || !viewport) {
                        this.applyPopperPosition();

                        return;
                    }

                    const selectedItem = this.getSelectedItem();

                    if (!selectedItem) {
                        this.applyPopperPosition();

                        return;
                    }

                    const selectedItemText =
                        selectedItem.querySelector(
                            '[data-slot="select-item-text"]') ??
                        selectedItem;

                    viewport.scrollTop = 0;

                    const triggerRect = trigger
                        .getBoundingClientRect();
                    const contentRect = content
                        .getBoundingClientRect();
                    const valueNodeRect = valueNode
                        .getBoundingClientRect();
                    const itemTextRect = selectedItemText
                        .getBoundingClientRect();

                    const itemTextOffset = itemTextRect.left -
                        contentRect.left;
                    const left = valueNodeRect.left -
                        itemTextOffset;
                    const leftDelta = triggerRect.left - left;
                    const minContentWidth = triggerRect.width +
                        leftDelta;
                    const contentWidth = Math.max(
                        minContentWidth, contentRect.width);
                    const rightEdge = window.innerWidth -
                        CONTENT_MARGIN;
                    const clampedLeft = clamp(left, [
                        CONTENT_MARGIN,
                        Math.max(CONTENT_MARGIN,
                            rightEdge - contentWidth),
                    ]);

                    const contentStyles = window
                        .getComputedStyle(content);
                    const contentBorderTopWidth =
                        parseInt(contentStyles.borderTopWidth,
                            10) || 0;
                    const contentPaddingTop =
                        parseInt(contentStyles.paddingTop,
                            10) || 0;

                    const scrollChromeHeight = this
                        .measureScrollChromeHeight(content);
                    const itemsHeight = viewport.scrollHeight;
                    const fullContentHeight =
                        contentBorderTopWidth +
                        contentPaddingTop +
                        itemsHeight +
                        scrollChromeHeight;
                    const availableHeight = window.innerHeight -
                        CONTENT_MARGIN * 2;

                    const triggerMiddle = triggerRect.top +
                        triggerRect.height / 2;
                    const contentTopToItemMiddle =
                        contentBorderTopWidth +
                        contentPaddingTop +
                        selectedItem.offsetTop +
                        selectedItem.offsetHeight / 2;

                    let top = triggerMiddle -
                        contentTopToItemMiddle;

                    if (top < CONTENT_MARGIN) {
                        viewport.scrollTop = CONTENT_MARGIN -
                            top;
                        top = CONTENT_MARGIN;
                    }

                    this.needsContentScroll =
                        fullContentHeight > availableHeight;

                    const height = this.needsContentScroll ?
                        availableHeight :
                        fullContentHeight;

                    if (top + height > window.innerHeight -
                        CONTENT_MARGIN) {
                        top = Math.max(
                            CONTENT_MARGIN,
                            window.innerHeight -
                            CONTENT_MARGIN - height,
                        );
                    }

                    const styleParts = [
                        `left: ${clampedLeft}px`,
                        `top: ${top}px`,
                        'bottom: auto',
                        `min-width: ${minContentWidth}px`,
                        `max-height: ${availableHeight}px`,
                        `margin: ${CONTENT_MARGIN}px 0`,
                    ];

                    if (this.needsContentScroll) {
                        styleParts.push(
                            `height: ${availableHeight}px`);
                    }

                    this.portalStyle = styleParts.join('; ');

                    this.updateScrollButtons();
                },
            }));
        });
    </script>
@endPushOnce
