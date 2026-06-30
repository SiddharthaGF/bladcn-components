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
                subHighlightedItemId: null,
                subPortalStyle: '',
                resolvedSubSide: 'right',
                closeTimer: null,

                get menuRoot() {
                    const root = this.$el.closest(
                        '[data-slot="dropdown-menu"]',
                    );

                    return root ? Alpine.$data(root) : null;
                },

                get panelOpen() {
                    return this.menuRoot?.panelOpen ??
                        false;
                },

                get keyboardNav() {
                    return this.menuRoot?.keyboardNav ??
                        false;
                },

                disableKeyboardNav() {
                    this.menuRoot?.disableKeyboardNav();
                },

                getSubItemElements() {
                    if (!this.$refs.subContent) {
                        return [];
                    }

                    return Array.from(
                        this.$refs.subContent
                        .querySelectorAll(
                            '[data-menu-item-id]',
                        ),
                    ).filter(
                        (element) =>
                        element.getAttribute(
                            'aria-disabled') !== 'true' &&
                        !element.disabled,
                    );
                },

                isSubItemHighlighted(itemId) {
                    if (!this.isSubOpen || !this.$refs
                        .subContent) {
                        return false;
                    }

                    if (!this.$refs.subContent.querySelector(
                            `[data-menu-item-id="${itemId}"]`,
                        )) {
                        return false;
                    }

                    return this.subHighlightedItemId === itemId;
                },

                focusSubItem(itemId) {
                    if (!this.$refs.subContent) {
                        return;
                    }

                    this.$refs.subContent
                        .querySelectorAll('[data-menu-item-id]')
                        .forEach((element) => {
                            if (element.dataset
                                .menuItemId !== itemId) {
                                element.blur();
                            }
                        });

                    this.$refs.subContent
                        .querySelector(
                            `[data-menu-item-id="${itemId}"]`,
                        )
                        ?.focus({
                            preventScroll: true,
                        });
                },

                activateSubItem(itemId) {
                    const element = this.$refs.subContent
                        ?.querySelector(
                            `[data-menu-item-id="${itemId}"]`,
                        );

                    element?.click();
                },

                focusParentSubTrigger() {
                    const trigger = this.$refs.subTrigger;
                    const triggerId = trigger?.dataset
                        .menuItemId;
                    const menuRoot = this.$el.closest(
                        '[data-slot="dropdown-menu"]',
                    );
                    const menu = menuRoot ? Alpine.$data(
                        menuRoot) : null;

                    if (menu && triggerId) {
                        menu.highlightedItemId = triggerId;
                        menu.$store.menu.setActiveItem(menu.id,
                            triggerId);
                    }

                    trigger?.focus({
                        preventScroll: true
                    });
                },

                selectFirstSubItem() {
                    const items = this.getSubItemElements();

                    if (items.length === 0) {
                        return;
                    }

                    this.subHighlightedItemId =
                        items[0].dataset.menuItemId ?? null;
                    this.focusSubItem(this
                        .subHighlightedItemId);
                },

                moveSubHighlight(delta) {
                    const items = this.getSubItemElements();

                    if (items.length === 0) {
                        return;
                    }

                    const ids = items.map(
                        (element) => element.dataset
                        .menuItemId,
                    );
                    let index = ids.indexOf(this
                        .subHighlightedItemId);

                    if (index === -1) {
                        index = 0;
                    } else {
                        index =
                            (index + delta + ids.length) % ids
                            .length;
                    }

                    this.subHighlightedItemId = ids[index] ??
                        null;
                    this.focusSubItem(this
                        .subHighlightedItemId);
                },

                handleKeydown(event) {
                    if (!this.isSubOpen) {
                        return false;
                    }

                    const menuRoot = this.$el.closest(
                        '[data-slot="dropdown-menu"]',
                    );
                    const menu = menuRoot ? Alpine.$data(
                        menuRoot) : null;

                    if (menu?.enableKeyboardNav) {
                        menu.enableKeyboardNav();
                    }

                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        this.moveSubHighlight(1);

                        return true;
                    }

                    if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        this.moveSubHighlight(-1);

                        return true;
                    }

                    if (event.key === 'Home') {
                        event.preventDefault();
                        const items = this.getSubItemElements();

                        if (items[0]) {
                            this.subHighlightedItemId =
                                items[0].dataset.menuItemId ??
                                null;
                            this.focusSubItem(this
                                .subHighlightedItemId);
                        }

                        return true;
                    }

                    if (event.key === 'End') {
                        event.preventDefault();
                        const items = this.getSubItemElements();
                        const last = items[items.length - 1];

                        if (last) {
                            this.subHighlightedItemId =
                                last.dataset.menuItemId ?? null;
                            this.focusSubItem(this
                                .subHighlightedItemId);
                        }

                        return true;
                    }

                    if (event.key === 'Enter' || event.key ===
                        ' ') {
                        if (!this.subHighlightedItemId) {
                            return false;
                        }

                        event.preventDefault();
                        this.activateSubItem(this
                            .subHighlightedItemId);

                        return true;
                    }

                    if (event.key === 'ArrowLeft' || event
                        .key === 'Escape') {
                        event.preventDefault();
                        this.closeSub();

                        return true;
                    }

                    return false;
                },

                openSub(event) {
                    clearTimeout(this.closeTimer);
                    this.isSubOpen = true;

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            this.applySubPosition(
                                event);
                            requestAnimationFrame
                                (() => {
                                    this.applySubPosition(
                                        event
                                    );
                                    this
                                        .selectFirstSubItem();
                                });
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
                    this.subHighlightedItemId = null;
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

                    this.focusParentSubTrigger();
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
