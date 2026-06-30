@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/dropdown-menu --}}
{{-- @see https://github.com/ailuracode/alpinejs-toolkit/blob/master/docs/plugins/menu.md --}}

@props([
    'id' => null,
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

    $isOpen = filter_var($open, FILTER_VALIDATE_BOOLEAN);
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative inline-block', $class]) }}
    x-bind:data-state="panelOpen ? 'open' : 'closed'"
    x-data="bladcnDropdownMenu({
        id: @js(filled($id) ? $id : null),
        open: @js($isOpen),
        checkboxes: @js($checkboxes),
        radioValue: @js($defaultRadioValue),
    })"
    x-id="['dropdown-menu']"
    x-on:click.outside="close()"
    x-on:keydown.window="handleKeydown($event)">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnDropdownMenu', (config = {}) => ({
                initId: config.id ?? null,
                initialOpen: Boolean(config.open),
                panelOpen: false,
                keyboardNav: false,
                highlightedItemId: null,
                checkboxes: config.checkboxes ?? {},
                radioValue: config.radioValue ?? null,
                _onMenuOpen: null,
                _onMenuClose: null,
                _onMenuActive: null,

                get id() {
                    return this.initId ?? this.$id(
                        'dropdown-menu');
                },

                init() {
                    const id = this.id;

                    bladcnRegisterMenu(id, {
                        orientation: 'vertical',
                        onClose: () => {
                            this.$root
                                .querySelectorAll(
                                    '[data-slot=dropdown-menu-sub]'
                                )
                                .forEach((
                                    element
                                ) => {
                                    Alpine
                                        .$data(
                                            element
                                        )
                                        ?.closeSub
                                        ?.();
                                });
                        },
                    });

                    this._onMenuOpen = (event) => {
                        if (event.detail?.id === id) {
                            this.panelOpen = true;
                        }
                    };

                    this._onMenuClose = (event) => {
                        if (event.detail?.id === id) {
                            this.panelOpen = false;
                            this.keyboardNav = false;
                            this.highlightedItemId = null;
                        }
                    };

                    this._onMenuActive = (event) => {
                        if (event.detail?.id === id) {
                            this.highlightedItemId = event
                                .detail.activeItemId ??
                                null;
                        }
                    };

                    document.addEventListener(
                        'bladcn:menu-open', this._onMenuOpen
                    );
                    document.addEventListener(
                        'bladcn:menu-close', this
                        ._onMenuClose);
                    document.addEventListener(
                        'bladcn:menu-active', this
                        ._onMenuActive);

                    if (this.initialOpen) {
                        this.$store.menu.open(id);
                    }
                },

                destroy() {
                    document.removeEventListener(
                        'bladcn:menu-open', this._onMenuOpen
                    );
                    document.removeEventListener(
                        'bladcn:menu-close', this
                        ._onMenuClose);
                    document.removeEventListener(
                        'bladcn:menu-active', this
                        ._onMenuActive);
                    this.$store.menu.unregister(this.id);
                },

                toggleMenu() {
                    if (this.panelOpen) {
                        this.close();

                        return;
                    }

                    this.$store.menu.open(this.id);
                },

                close() {
                    this.$store.menu.close(this.id);
                },

                enableKeyboardNav() {
                    this.keyboardNav = true;
                },

                disableKeyboardNav() {
                    this.keyboardNav = false;
                },

                isMenuNavigationKey(key) {
                    return [
                        'ArrowUp',
                        'ArrowDown',
                        'ArrowLeft',
                        'ArrowRight',
                        'Home',
                        'End',
                        'Enter',
                        ' ',
                    ].includes(key);
                },

                getOpenSub() {
                    for (const element of this.$root
                            .querySelectorAll(
                                '[data-slot="dropdown-menu-sub"]',
                            )) {
                        const sub = Alpine.$data(element);

                        if (sub?.isSubOpen) {
                            return sub;
                        }
                    }

                    return null;
                },

                isDropdownItemHighlighted(itemId) {
                    const openSub = this.getOpenSub();

                    if (openSub) {
                        return openSub.isSubItemHighlighted(
                            itemId);
                    }

                    return this.getHighlightedItemId() ===
                        itemId;
                },

                highlightItem(itemId, element = null) {
                    this.disableKeyboardNav();

                    const subRoot = element?.closest(
                        '[data-slot="dropdown-menu-sub"]',
                    );
                    const sub = subRoot ? Alpine.$data(
                        subRoot) : null;

                    if (
                        sub?.isSubOpen &&
                        element?.closest(
                            '[data-slot="dropdown-menu-sub-content"]',
                        )
                    ) {
                        sub.subHighlightedItemId = itemId;

                        return;
                    }

                    const openSub = this.getOpenSub();

                    if (openSub) {
                        const activeTriggerId =
                            openSub.$refs.subTrigger
                            ?.dataset.menuItemId ?? null;

                        if (activeTriggerId !== itemId) {
                            openSub.closeSub();
                        }
                    }

                    this.highlightedItemId = itemId;
                    this.$store.menu.setActiveItem(
                        this.id, itemId);
                },

                getHighlightedItemId() {
                    return (
                        this.highlightedItemId ??
                        this.$store.menu.activeItem(this.id)
                    );
                },

                getHighlightedItemElement() {
                    const activeId = this
                        .getHighlightedItemId();

                    if (!activeId) {
                        return null;
                    }

                    return this.$root.querySelector(
                        `[data-menu-item-id="${activeId}"]`,
                    );
                },

                focusHighlightedItem() {
                    const activeId = this
                .getHighlightedItemId();

                    this.$nextTick(() => {
                        this.$root
                            .querySelectorAll(
                                '[data-menu-item-id]')
                            .forEach((element) => {
                                if (
                                    element.dataset
                                    .menuItemId !==
                                    activeId
                                ) {
                                    element.blur();
                                }
                            });

                        this.getHighlightedItemElement()
                            ?.focus({
                                preventScroll: true,
                            });
                    });
                },

                openActiveSubmenu(event) {
                    const activeId = this
                        .getHighlightedItemId();

                    if (!activeId) {
                        return false;
                    }

                    const subTrigger = this.$root.querySelector(
                        `[data-menu-item-id="${activeId}"][data-slot="dropdown-menu-sub-trigger"]`,
                    );

                    if (!subTrigger) {
                        return false;
                    }

                    const subRoot = subTrigger.closest(
                        '[data-slot="dropdown-menu-sub"]',
                    );
                    const sub = subRoot ? Alpine.$data(
                        subRoot) : null;

                    if (!sub?.openSub) {
                        return false;
                    }

                    event.preventDefault();

                    sub.openSub(event);

                    return true;
                },

                activateHighlightedSpecialItem(event) {
                    const element = this
                        .getHighlightedItemElement();

                    if (!element) {
                        return false;
                    }

                    const role = element.getAttribute('role');

                    if (
                        role !== 'menuitemcheckbox' &&
                        role !== 'menuitemradio'
                    ) {
                        return false;
                    }

                    event.preventDefault();
                    element.click();

                    return true;
                },

                handleKeydown(event) {
                    if (!this.panelOpen) {
                        return;
                    }

                    if (this.isMenuNavigationKey(event.key)) {
                        this.enableKeyboardNav();
                    }

                    const openSub = this.getOpenSub();

                    if (openSub?.handleKeydown(event)) {
                        return;
                    }

                    if (
                        event.key === 'Enter' ||
                        event.key === ' ' ||
                        event.key === 'ArrowRight'
                    ) {
                        if (this.openActiveSubmenu(event)) {
                            return;
                        }
                    }

                    if (
                        (event.key === 'Enter' || event.key ===
                            ' ') &&
                        this.activateHighlightedSpecialItem(
                            event)
                    ) {
                        return;
                    }

                    this.$store.menu.handleKeydown(this.id,
                        event);
                    this.highlightedItemId =
                        this.$store.menu.activeItem(this.id);
                    this.focusHighlightedItem();
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
