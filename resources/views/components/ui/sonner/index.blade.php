@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/sonner --}}

@props([
    'position' => 'bottom-right',
    'richColors' => true,
    'visibleToasts' => 3,
    'offset' => '24px',
    'mobileOffset' => '16px',
    'style' => null,
    'class' => null,
])

@php
    $offsetValue = is_numeric($offset) ? "{$offset}px" : $offset;
    $mobileOffsetValue = is_numeric($mobileOffset)
        ? "{$mobileOffset}px"
        : $mobileOffset;
    $flashToast = session(\AiluraCode\Bladcn\Support\Toast::SESSION_KEY);

    $presetClass = new \AiluraCode\Bladcn\Support\ClassResolver()->add(
        'z-100 pointer-events-none fixed inset-0',
    );

    $presetAttributes = [
        'data-slot' => 'sonner',
        'style' => implode(
            '; ',
            array_filter([
                "--offset-top: {$offsetValue}",
                "--offset-right: {$offsetValue}",
                "--offset-bottom: {$offsetValue}",
                "--offset-left: {$offsetValue}",
                "--mobile-offset-top: {$mobileOffsetValue}",
                "--mobile-offset-right: {$mobileOffsetValue}",
                "--mobile-offset-bottom: {$mobileOffsetValue}",
                "--mobile-offset-left: {$mobileOffsetValue}",
                filled($style) ? $style : null,
            ]),
        ),
    ];
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnSonner({ defaultPosition: @js($position), richColors: @js($richColors), visibleToasts: @js($visibleToasts) })"
    x-on:toast.window="bladcnToast?.fromPayload?.($event.detail)">
    <template :key="position"
        x-for="position in activePositions()">
        <ol class="toaster group"
            data-sonner-toaster
            x-bind:data-x-position="xPosition(position)"
            x-bind:data-y-position="yPosition(position)"
            x-bind:style="toasterStyle(position)"
            x-on:mouseenter="setExpanded(position, true)"
            x-on:mouseleave="setExpanded(position, false)"
            x-show="toastsAt(position).length > 0">
            <template :key="toast.id"
                x-for="(toast, index) in toastsAt(position)">
                <li data-sonner-toast
                    data-styled="true"
                    role="status"
                    x-bind:data-dismissible="'true'"
                    x-bind:data-expanded="isExpanded(position) ? 'true' : 'false'"
                    x-bind:data-front="index == 0 ? 'true' : 'false'"
                    x-bind:data-mounted="toast.mounted ? 'true' : 'false'"
                    x-bind:data-removed="toast.removed ? 'true' : 'false'"
                    x-bind:data-rich-colors="richColors ? 'true' : 'false'"
                    x-bind:data-swipe-direction="toast.swipeDirection"
                    x-bind:data-swipe-out="toast.swipeOut ? 'true' : 'false'"
                    x-bind:data-swiping="swipingId === toast.id ? 'true' : 'false'"
                    x-bind:data-type="toastType(toast)"
                    x-bind:data-visible="index < visibleToasts ? 'true' : 'false'"
                    x-bind:data-x-position="xPosition(position)"
                    x-bind:data-y-position="yPosition(position)"
                    x-bind:style="toastStyle(toast, index, position)"
                    x-init="initToast($el, toast)"
                    x-on:pointercancel="endSwipe($event, toast, position)"
                    x-on:pointerdown="startSwipe($event, toast)"
                    x-on:pointermove="moveSwipe($event, toast, position)"
                    x-on:pointerup="endSwipe($event, toast, position)">
                    <button aria-label="Dismiss toast"
                        data-close-button
                        type="button"
                        x-on:click.stop="$store.bladcnToasts.dismiss(toast.id)">
                        <x-ui.icon aria-hidden="true"
                            class="size-3"
                            name="x" />
                    </button>
                    <div data-icon
                        x-show="showIcon(toast)">
                        <span class="contents"
                            x-cloak
                            x-show="toast.variant === 'success'">
                            <x-ui.icon aria-hidden="true"
                                class="size-4"
                                name="circle-check" />
                        </span>
                        <span class="contents"
                            x-cloak
                            x-show="toast.variant === 'info'">
                            <x-ui.icon aria-hidden="true"
                                class="size-4"
                                name="info" />
                        </span>
                        <span class="contents"
                            x-cloak
                            x-show="toast.variant === 'warning'">
                            <x-ui.icon aria-hidden="true"
                                class="size-4"
                                name="triangle-alert" />
                        </span>
                        <span class="contents"
                            x-cloak
                            x-show="toast.variant === 'destructive'">
                            <x-ui.icon aria-hidden="true"
                                class="size-4"
                                name="octagon-x" />
                        </span>
                        <span class="contents"
                            x-cloak
                            x-show="toast.variant === 'loading'">
                            <x-ui.icon aria-hidden="true"
                                class="size-4 animate-spin"
                                name="loader-2" />
                        </span>
                    </div>
                    <div data-content>
                        <div data-title
                            x-show="toast.title"
                            x-text="toast.title"></div>
                        <div data-description
                            x-show="toast.description"
                            x-text="toast.description"></div>
                    </div>
                    <button data-button
                        type="button"
                        x-on:click.stop="runAction(toast)"
                        x-show="toast.action"
                        x-text="toast.action?.label"></button>
                </li>
            </template>
        </ol>
    </template>
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnSonner', (config = {}) => ({
                defaultPosition: config.defaultPosition ??
                    'bottom-right',
                richColors: config.richColors ?? true,
                visibleToasts: config.visibleToasts ?? 3,
                expanded: {},
                heights: {},
                swipingId: null,
                pointerStart: null,
                swipeDirection: null,
                positions: [
                    'top-left',
                    'top-center',
                    'top-right',
                    'bottom-left',
                    'bottom-center',
                    'bottom-right',
                ],

                init() {
                    this.$store.bladcnToasts.defaultPosition =
                        this
                        .defaultPosition;
                },

                yPosition(position) {
                    return position.split('-')[0];
                },

                xPosition(position) {
                    return position.split('-')[1];
                },

                setExpanded(position, value) {
                    this.expanded = {
                        ...this.expanded,
                        [position]: value,
                    };
                },

                isExpanded(position) {
                    return Boolean(this.expanded[position]);
                },

                activePositions() {
                    const defaultPosition = this.$store
                        .bladcnToasts
                        .defaultPosition;
                    const used = new Set(
                        this.$store.bladcnToasts.items.map(
                            (toast) => toast.position ??
                            defaultPosition,
                        ),
                    );

                    used.add(defaultPosition);

                    return this.positions.filter((position) =>
                        used.has(
                            position));
                },

                swipeDirectionsFor(position) {
                    const [y, x] = position.split('-');
                    const directions = [];

                    if (y === 'top' || y === 'bottom') {
                        directions.push(y);
                    }

                    if (x === 'left' || x === 'right') {
                        directions.push(x);
                    }

                    return directions;
                },

                toastsAt(position) {
                    const defaultPosition = this.$store
                        .bladcnToasts
                        .defaultPosition;

                    return this.$store.bladcnToasts.items
                        .filter(
                            (toast) => (toast.position ??
                                defaultPosition) ===
                            position,
                        );
                },

                toasterStyle(position) {
                    const toasts = this.toastsAt(position);
                    const frontId = toasts[0]?.id;
                    const frontHeight = this.heights[frontId] ||
                        0;
                    let minHeight = frontHeight;

                    if (this.isExpanded(position)) {
                        minHeight = toasts
                            .slice(0, this.visibleToasts)
                            .reduce((total, toast, index) => {
                                return total + (this
                                        .heights[toast
                                            .id] || 0) +
                                    (index > 0 ? 14 : 0);
                            }, 0);
                    }

                    return {
                        '--width': '356px',
                        '--gap': '14px',
                        '--front-toast-height': `${frontHeight}px`,
                        minHeight: `${minHeight}px`,
                    };
                },

                toastOffset(toast, index, position) {
                    const toasts = this.toastsAt(position);
                    let before = 0;

                    for (let i = 0; i < index; i++) {
                        before += this.heights[toasts[i].id] ||
                            0;
                    }

                    return index * 14 + before;
                },

                toastStyle(toast, index, position) {
                    const toasts = this.toastsAt(position);
                    const offset = toast.removed ?
                        (toast.offsetBeforeRemove ?? this
                            .toastOffset(
                                toast, index, position)) :
                        this.toastOffset(toast, index,
                            position);

                    return {
                        '--index': index,
                        '--toasts-before': index,
                        '--z-index': toasts.length - index,
                        '--offset': `${offset}px`,
                        '--initial-height': `${this.heights[toast.id] || 0}px`,
                        '--swipe-amount-x': '0px',
                        '--swipe-amount-y': '0px',
                    };
                },

                measureToast(element, toast) {
                    const originalHeight = element.style.height;

                    element.style.height = 'auto';
                    const height = Math.round(element
                        .getBoundingClientRect()
                        .height);
                    element.style.height = originalHeight;

                    if (height > 0 && this.heights[toast.id] !==
                        height) {
                        this.heights = {
                            ...this.heights,
                            [toast.id]: height,
                        };
                        this.$store.bladcnToasts.setHeight(toast
                            .id, height);
                    }
                },

                initToast(element, toast) {
                    const measure = () => this.measureToast(
                        element, toast);

                    measure();

                    const observer = new ResizeObserver(
                        measure);
                    observer.observe(element);

                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            this.$store
                                .bladcnToasts
                                .markMounted(
                                    toast.id);
                            measure();
                        });
                    });
                },

                toastType(toast) {
                    if (toast.variant === 'destructive') {
                        return 'error';
                    }

                    if (toast.variant === 'loading') {
                        return 'loading';
                    }

                    return toast.variant ?? 'default';
                },

                showIcon(toast) {
                    return ['success', 'info', 'warning',
                            'destructive', 'loading'
                        ]
                        .includes(toast.variant);
                },

                runAction(toast) {
                    toast.action?.onClick?.();
                    this.$store.bladcnToasts.dismiss(toast.id);
                },

                startSwipe(event, toast) {
                    if (event.button === 2) {
                        return;
                    }

                    if (event.target.closest(
                            '[data-close-button],[data-button]'
                        )) {
                        return;
                    }

                    this.swipingId = toast.id;
                    this.swipeDirection = null;
                    this.pointerStart = {
                        x: event.clientX,
                        y: event.clientY,
                    };
                    event.currentTarget.setPointerCapture(event
                        .pointerId);
                },

                moveSwipe(event, toast, position) {
                    if (this.swipingId !== toast.id || !this
                        .pointerStart) {
                        return;
                    }

                    const allowed = this.swipeDirectionsFor(
                        position);
                    const xDelta = event.clientX - this
                        .pointerStart.x;
                    const yDelta = event.clientY - this
                        .pointerStart.y;

                    if (!this.swipeDirection && (Math.abs(
                                xDelta) > 1 ||
                            Math.abs(yDelta) > 1)) {
                        this.swipeDirection = Math.abs(xDelta) >
                            Math.abs(
                                yDelta) ? 'x' : 'y';
                    }

                    let swipeX = 0;
                    let swipeY = 0;

                    if (this.swipeDirection === 'x') {
                        if ((allowed.includes('right') &&
                                xDelta > 0) ||
                            (allowed.includes('left') &&
                                xDelta < 0)) {
                            swipeX = xDelta;
                        } else {
                            swipeX = xDelta * (1 / (1 + Math
                                .abs(xDelta) / 20));
                        }
                    } else if (this.swipeDirection === 'y') {
                        if ((allowed.includes('bottom') &&
                                yDelta > 0) ||
                            (allowed.includes('top') && yDelta <
                                0)) {
                            swipeY = yDelta;
                        } else {
                            swipeY = yDelta * (1 / (1 + Math
                                .abs(yDelta) / 20));
                        }
                    }

                    event.currentTarget.style.setProperty(
                        '--swipe-amount-x',
                        `${swipeX}px`);
                    event.currentTarget.style.setProperty(
                        '--swipe-amount-y',
                        `${swipeY}px`);
                },

                endSwipe(event, toast, position) {
                    if (this.swipingId !== toast.id) {
                        return;
                    }

                    const allowed = this.swipeDirectionsFor(
                        position);
                    const xDelta = event.clientX - (this
                        .pointerStart?.x ?? 0);
                    const yDelta = event.clientY - (this
                        .pointerStart?.y ?? 0);
                    const threshold = 45;

                    if (this.swipeDirection === 'x' && Math.abs(
                            xDelta) >=
                        threshold) {
                        const direction = xDelta > 0 ? 'right' :
                            'left';

                        if (allowed.includes(direction)) {
                            this.$store.bladcnToasts.dismiss(
                                toast.id, {
                                    swipe: true,
                                    swipeDirection: direction,
                                });
                        }
                    } else if (this.swipeDirection === 'y' &&
                        Math.abs(yDelta) >=
                        threshold) {
                        const direction = yDelta > 0 ?
                            'bottom' : 'top';

                        if (allowed.includes(direction)) {
                            this.$store.bladcnToasts.dismiss(
                                toast.id, {
                                    swipe: true,
                                    swipeDirection: direction,
                                });
                        }
                    } else {
                        event.currentTarget.style.setProperty(
                            '--swipe-amount-x', '0px');
                        event.currentTarget.style.setProperty(
                            '--swipe-amount-y', '0px');
                    }

                    this.swipingId = null;
                    this.pointerStart = null;
                    this.swipeDirection = null;
                },
            }));

            Alpine.store('bladcnToasts', {
                defaultPosition: 'bottom-right',
                items: [],
                heights: {},

                setHeight(id, height) {
                    this.heights[id] = height;
                },

                calculateOffset(id) {
                    const toast = this.items.find((item) => item
                        .id === id);

                    if (!toast) {
                        return 0;
                    }

                    const position = toast.position ?? this
                        .defaultPosition;
                    const atPosition = this.items.filter((item) => (
                        item
                        .position ?? this.defaultPosition
                    ) === position);
                    const index = atPosition.findIndex((item) =>
                        item.id ===
                        id);
                    let offset = 0;

                    for (let i = 0; i < index; i++) {
                        offset += 14 + (this.heights[atPosition[i]
                            .id] || 0);
                    }

                    return offset;
                },

                push(payload = {}) {
                    const id = crypto.randomUUID();
                    const toast = {
                        id,
                        title: payload.title ?? null,
                        description: payload.description ??
                            null,
                        variant: payload.variant ?? 'default',
                        position: payload.position ?? this
                            .defaultPosition,
                        duration: payload.duration ?? 4000,
                        action: payload.action ?? null,
                        mounted: false,
                        removed: false,
                        swipeOut: false,
                        swipeDirection: null,
                        offsetBeforeRemove: 0,
                    };

                    this.items.unshift(toast);

                    if (toast.duration > 0) {
                        setTimeout(() => this.dismiss(id), toast
                            .duration);
                    }

                    return id;
                },

                markMounted(id) {
                    const toast = this.items.find((item) => item
                        .id === id);

                    if (!toast || toast.mounted) {
                        return;
                    }

                    toast.mounted = true;
                    this.items = [...this.items];
                },

                update(id, payload = {}) {
                    const index = this.items.findIndex((toast) =>
                        toast
                        .id === id);

                    if (index === -1) {
                        return;
                    }

                    this.items[index] = {
                        ...this.items[index],
                        ...payload,
                    };
                    this.items = [...this.items];

                    if (payload.duration > 0) {
                        setTimeout(() => this.dismiss(id), payload
                            .duration);
                    }
                },

                dismiss(id, options = {}) {
                    const toast = this.items.find((item) => item
                        .id === id);

                    if (!toast || toast.removed) {
                        return;
                    }

                    toast.removed = true;
                    toast.offsetBeforeRemove = this.calculateOffset(
                        id);
                    toast.swipeOut = Boolean(options.swipe);
                    toast.swipeDirection = options.swipeDirection ??
                        null;
                    this.items = [...this.items];

                    setTimeout(() => {
                        this.items = this.items.filter((
                                item) => item.id !==
                            id);
                        delete this.heights[id];
                    }, 200);
                },
            });

            const pushToast = (titleOrPayload, options = {}) => {
                if (typeof titleOrPayload === 'string') {
                    return Alpine.store('bladcnToasts').push({
                        title: titleOrPayload,
                        ...options,
                    });
                }

                return Alpine.store('bladcnToasts').push(
                    titleOrPayload);
            };

            window.bladcnToast = pushToast;

            window.bladcnToast.success = (title, options = {}) =>
                pushToast({
                    title,
                    ...options,
                    variant: 'success',
                });

            window.bladcnToast.info = (title, options = {}) => pushToast({
                title,
                ...options,
                variant: 'info',
            });

            window.bladcnToast.warning = (title, options = {}) =>
                pushToast({
                    title,
                    ...options,
                    variant: 'warning',
                });

            window.bladcnToast.error = (title, options = {}) => pushToast({
                title,
                ...options,
                variant: 'destructive',
            });

            window.bladcnToast.fromPayload = (payload = {}) => {
                const {
                    title,
                    variant = 'default',
                    ...options
                } = payload;

                if (variant === 'success') {
                    return window.bladcnToast.success(title, options);
                }

                if (variant === 'info') {
                    return window.bladcnToast.info(title, options);
                }

                if (variant === 'warning') {
                    return window.bladcnToast.warning(title, options);
                }

                if (variant === 'destructive' || variant === 'error') {
                    return window.bladcnToast.error(title, options);
                }

                if (variant === 'loading') {
                    return pushToast({
                        title,
                        ...options,
                        variant: 'loading',
                    });
                }

                return pushToast(title, options);
            };

            window.bladcnToast.promise = (factory, messages = {}) => {
                const id = Alpine.store('bladcnToasts').push({
                    title: messages.loading ?? 'Loading...',
                    variant: 'loading',
                    duration: 0,
                });

                return Promise.resolve(factory())
                    .then((data) => {
                        Alpine.store('bladcnToasts').update(id, {
                            title: typeof messages
                                .success === 'function' ?
                                messages.success(data) :
                                messages
                                .success,
                            variant: 'success',
                            duration: 4000,
                        });

                        return data;
                    })
                    .catch(() => {
                        Alpine.store('bladcnToasts').update(id, {
                            title: messages.error ??
                                'Error',
                            variant: 'destructive',
                            duration: 4000,
                        });
                    });
            };

            @if (filled($flashToast))
                window.bladcnToast.fromPayload(
                    @json($flashToast));
            @endif
        });
    </script>
@endPushOnce
