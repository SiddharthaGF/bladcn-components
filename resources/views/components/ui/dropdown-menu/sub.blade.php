@blaze(fold: false)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'dropdown-menu-sub',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}
    x-data="bladcnDropdownMenuSub()"
    x-on:click.outside="closeSub()">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            const VIEWPORT_MARGIN = 8;

            Alpine.data('bladcnDropdownMenuSub', () => ({
                isSubOpen: false,
                subPortalStyle: '',
                resolvedSubSide: 'right',
                closeTimer: null,

                openSub(event) {
                    clearTimeout(this.closeTimer);
                    this.isSubOpen = true;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this.applySubPosition(
                                event);
                            requestAnimationFrame
                                (() => this
                                    .applySubPosition(
                                        event));
                        });
                    });
                },

                toggleSub(event) {
                    if (this.isSubOpen) {
                        this.closeSub();

                        return;
                    }

                    this.openSub(event);
                },

                scheduleClose() {
                    clearTimeout(this.closeTimer);
                    this.closeTimer = setTimeout(() => this
                        .closeSub(), 200);
                },

                cancelClose() {
                    clearTimeout(this.closeTimer);
                },

                closeSub() {
                    clearTimeout(this.closeTimer);
                    this.isSubOpen = false;
                    this.subPortalStyle = '';

                    this.$el
                        .querySelectorAll(
                            '[data-slot="dropdown-menu-sub"]')
                        .forEach((element) => {
                            if (element === this.$el) {
                                return;
                            }

                            Alpine.$data(element)?.closeSub
                                ?.();
                        });
                },

                applySubPosition(event) {
                    const trigger = this.$refs.subTrigger ??
                        event?.currentTarget;
                    const content = this.$refs.subContent;

                    if (!trigger || !content) {
                        return;
                    }

                    const rect = trigger
                        .getBoundingClientRect();
                    const contentRect = content
                        .getBoundingClientRect();
                    const width = Math.max(
                        content.offsetWidth,
                        content.scrollWidth,
                        contentRect.width,
                    );
                    const height = Math.max(
                        content.offsetHeight,
                        content.scrollHeight,
                        contentRect.height,
                    );
                    const offset = 4;

                    let viewportLeft = rect.right + offset;
                    let viewportTop = rect.top;

                    const fitsRight =
                        viewportLeft + width <=
                        window.innerWidth - VIEWPORT_MARGIN;
                    const fitsLeft =
                        rect.left - offset - width >=
                        VIEWPORT_MARGIN;

                    if (!fitsRight && fitsLeft) {
                        viewportLeft = rect.left - width -
                            offset;
                    } else if (!fitsRight && !fitsLeft) {
                        const spaceRight =
                            window.innerWidth -
                            VIEWPORT_MARGIN - rect.right;
                        const spaceLeft = rect.left -
                            VIEWPORT_MARGIN;

                        viewportLeft =
                            spaceLeft >= spaceRight ?
                            rect.left - width - offset :
                            rect.right + offset;
                    }

                    if (
                        viewportLeft + width > rect.left -
                        offset &&
                        viewportLeft < rect.right + offset
                    ) {
                        viewportLeft = rect.left - width -
                            offset;
                    }

                    if (height > 0) {
                        viewportTop = Math.min(
                            viewportTop,
                            window.innerHeight - height -
                            VIEWPORT_MARGIN,
                        );
                        viewportTop = Math.max(VIEWPORT_MARGIN,
                            viewportTop);
                    }

                    const containingBlock = this
                        .getFixedContainingBlock(content);
                    let left = viewportLeft;
                    let top = viewportTop;

                    if (containingBlock) {
                        const cbRect = containingBlock
                            .getBoundingClientRect();
                        left = viewportLeft - cbRect.left;
                        top = viewportTop - cbRect.top;
                    }

                    this.resolvedSubSide =
                        viewportLeft + width <= rect.left -
                        offset ?
                        'left' :
                        'right';
                    this.subPortalStyle =
                        `left: ${left}px; top: ${top}px;`;
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
            }));
        });
    </script>
@endPushOnce
