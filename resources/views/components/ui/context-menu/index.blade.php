@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/context-menu --}}

@props([
    'defaultRadioValue' => null,
    'checkboxes' => [],
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'context-menu',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnContextMenu({
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

            Alpine.data('bladcnContextMenu', (config = {}) => ({
                isOpen: false,
                anchorX: 0,
                anchorY: 0,
                portalStyle: '',
                resolvedSide: 'bottom',
                checkboxes: config.checkboxes ?? {},
                radioValue: config.radioValue ?? null,

                openAt(event) {
                    event.preventDefault();
                    this.$store.scroll.lock();
                    this.anchorX = event.clientX;
                    this.anchorY = event.clientY;
                    this.isOpen = true;
                    this.portalStyle =
                        `left: ${this.anchorX}px; top: ${this.anchorY}px; visibility: hidden;`;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this
                                .applyPosition();
                            requestAnimationFrame
                                (() => this
                                    .applyPosition()
                                    );
                        });
                    });
                },

                close() {
                    if (!this.isOpen) {
                        return;
                    }

                    this.isOpen = false;
                    this.portalStyle = '';
                    this.resolvedSide = 'bottom';
                    this.closeAllSubs();
                    this.$store.scroll.unlock();
                },

                closeIfOutside(event) {
                    if (event.target.closest(
                            '[data-slot="context-menu-sub"]')) {
                        return;
                    }

                    this.close();
                },

                closeAllSubs() {
                    this.$root
                        .querySelectorAll(
                            '[data-slot="context-menu-sub"]')
                        .forEach((element) => {
                            Alpine.$data(element)?.closeSub
                                ?.();
                        });
                },

                measureContent(content) {
                    const rect = content
                        .getBoundingClientRect();

                    return {
                        width: Math.max(
                            content.scrollWidth,
                            content.offsetWidth,
                            rect.width,
                        ),
                        height: Math.max(
                            content.scrollHeight,
                            content.offsetHeight,
                            rect.height,
                        ),
                    };
                },

                getFixedContainingBlock(element) {
                    let node = element.parentElement;

                    while (node && node !== document
                        .documentElement) {
                        const style = window.getComputedStyle(
                            node);

                        if (
                            style.transform !== 'none' ||
                            style.translate !== 'none' ||
                            style.rotate !== 'none' ||
                            style.scale !== 'none' ||
                            style.perspective !== 'none' ||
                            style.filter !== 'none' ||
                            style.backdropFilter !== 'none' || [
                                'layout', 'paint', 'strict',
                                'content'
                            ].includes(
                                style.contain,
                            )
                        ) {
                            return node;
                        }

                        node = node.parentElement;
                    }

                    return null;
                },

                applyPosition() {
                    const content = this.$refs.content;

                    if (!content || !this.isOpen) {
                        return;
                    }

                    const margin = VIEWPORT_MARGIN;
                    const {
                        width,
                        height
                    } = this.measureContent(content);

                    if (width <= 0 || height <= 0) {
                        return;
                    }

                    const spaceRight = window.innerWidth -
                        margin - this.anchorX;
                    const spaceLeft = this.anchorX - margin;
                    const spaceBelow = window.innerHeight -
                        margin - this.anchorY;
                    const spaceAbove = this.anchorY - margin;

                    let left = this.anchorX;
                    let top = this.anchorY;

                    const flipX =
                        width > spaceRight &&
                        (width <= spaceLeft || spaceLeft >
                            spaceRight);
                    const flipY =
                        height > spaceBelow &&
                        (height <= spaceAbove || spaceAbove >
                            spaceBelow);

                    if (flipX) {
                        left = this.anchorX - width;
                    }

                    if (flipY) {
                        top = this.anchorY - height;
                    }

                    left = clamp(
                        left,
                        margin,
                        Math.max(margin, window.innerWidth -
                            margin - width),
                    );
                    top = clamp(
                        top,
                        margin,
                        Math.max(margin, window
                            .innerHeight - margin - height),
                    );

                    if (flipY) {
                        this.resolvedSide = 'top';
                    } else if (flipX) {
                        this.resolvedSide = 'left';
                    } else {
                        this.resolvedSide = 'bottom';
                    }

                    const maxHeight = Math.min(
                        window.innerHeight - margin * 2,
                        flipY ?
                        Math.max(0, this.anchorY - margin) :
                        Math.max(0, window.innerHeight -
                            margin - top),
                    );

                    let viewportLeft = left;
                    let viewportTop = top;
                    const containingBlock = this
                        .getFixedContainingBlock(content);

                    if (containingBlock) {
                        const cbRect = containingBlock
                            .getBoundingClientRect();
                        viewportLeft -= cbRect.left;
                        viewportTop -= cbRect.top;
                    }

                    this.portalStyle = [
                        `left: ${viewportLeft}px`,
                        `top: ${viewportTop}px`,
                        `max-height: ${maxHeight}px`,
                        'visibility: visible',
                    ].join('; ');
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
