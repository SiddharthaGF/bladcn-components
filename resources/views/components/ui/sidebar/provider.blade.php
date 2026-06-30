@blaze(fold: false)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/sidebar-wrapper has-data-[variant=inset]:bg-sidebar flex min-h-svh w-full overflow-x-hidden',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar-wrapper',
    ];

    $mergedStyle = trim(
        collect(['--sidebar-width: 16rem; --sidebar-width-icon: 3rem;', $style])
            ->filter()
            ->implode(' '),
    );
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @if ($mergedStyle !== '') style="{{ $mergedStyle }}" @endif
    x-data="bladcnSidebarProvider()">
    {{ $slot }}
</div>

{{-- Apply compact sidebar attributes before Alpine so the gap spacer matches main width. --}}
<script>
    (function() {
        const key = 'sidebar-expanded';

        function applyCompactSidebarDom() {
            try {
                if (localStorage.getItem(key) !== 'false') {
                    return;
                }
            } catch (e) {
                return;
            }

            if (!window.matchMedia('(min-width: 768px)').matches) {
                return;
            }

            const sidebar = document.querySelector(
                '[data-slot="sidebar-wrapper"] [data-slot="sidebar"]');

            if (!sidebar) {
                return;
            }

            sidebar.setAttribute('data-state', 'collapsed');
            sidebar.setAttribute('data-collapsible', 'icon');
        }

        applyCompactSidebarDom();
        document.addEventListener('livewire:navigated',
            applyCompactSidebarDom);
    })();
</script>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            if (window.__bladcnSidebarProviderRegistered) {
                return;
            }

            window.__bladcnSidebarProviderRegistered = true;

            const SIDEBAR_EXPANDED_KEY = 'sidebar-expanded';
            const SIDEBAR_MOBILE_CLOSE_MS = 300;
            const DESKTOP_MQ = '(min-width: 768px)';

            function readExpanded() {
                try {
                    return localStorage.getItem(SIDEBAR_EXPANDED_KEY) !==
                        'false';
                } catch (e) {
                    return true;
                }
            }

            function writeExpanded(expanded) {
                try {
                    localStorage.setItem(SIDEBAR_EXPANDED_KEY, String(
                        expanded));
                } catch (e) {
                    // Private browsing or blocked storage.
                }
            }

            function syncCollapsedDom(expanded) {
                document.documentElement.toggleAttribute(
                    'data-sidebar-collapsed', !expanded);
                window.dispatchEvent(new CustomEvent(
                    'bladcn:sidebar-layout'));
            }

            // Host apps must register @ailuracode/alpine-sidebar only — do not
            // also register bladcnSidebarProvider in app.js (scroll lock lives here).
            Alpine.data('bladcnSidebarProvider', () => ({
                expanded: readExpanded(),
                mobilePresent: false,
                mobileAnimationState: 'closed',
                mobileClosing: false,
                mobileCloseTimer: null,

                init() {
                    syncCollapsedDom(this.expanded);
                    this.syncSidebarGroupDom();

                    this.$watch('expanded', (value) => {
                        writeExpanded(value);
                        syncCollapsedDom(value);
                        this.syncSidebarGroupDom();
                    });

                    this.$watch(
                        () => this.$store.sidebar.visible,
                        (visible) => {
                            if (this.$store.sidebar
                                .matchesBreakpoint) {
                                return;
                            }

                            if (visible) {
                                this.openMobile();

                                return;
                            }

                            if (this.mobilePresent && !this
                                .mobileClosing) {
                                this.finishMobileClose();
                            }
                        },
                    );

                    this.$watch(
                        () => this.$store.sidebar
                        .matchesBreakpoint,
                        (matches) => {
                            if (!matches) {
                                return;
                            }

                            clearTimeout(this
                                .mobileCloseTimer);
                            this.mobilePresent = false;
                            this.mobileAnimationState =
                                'closed';
                            this.mobileClosing = false;

                            if (this.$store.sidebar
                                .visible) {
                                this.$store.sidebar.hide();
                            }

                            window.bladcnBodyScrollLock
                                ?.unlock();
                        },
                    );

                    this.$nextTick(() => {
                        document.documentElement
                            .setAttribute(
                                'data-alpine-initialized',
                                '');
                    });
                },

                destroy() {
                    clearTimeout(this.mobileCloseTimer);
                },

                openMobile() {
                    clearTimeout(this.mobileCloseTimer);
                    this.mobileClosing = false;
                    this.mobilePresent = true;
                    this.mobileAnimationState = 'closed';
                    window.bladcnBodyScrollLock?.lock();

                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame
                                (() => {
                                    this.mobileAnimationState =
                                        'open';
                                });
                        });
                    });
                },

                finishMobileClose({
                    hideStore = false,
                } = {}) {
                    if (!this.mobilePresent || this
                        .mobileClosing) {
                        return;
                    }

                    clearTimeout(this.mobileCloseTimer);
                    this.mobileClosing = true;
                    this.mobileAnimationState = 'closed';
                    document.documentElement.setAttribute(
                        'data-sidebar', '');

                    this.mobileCloseTimer = setTimeout(() => {
                        if (hideStore && this.$store
                            .sidebar
                            .visible) {
                            this.$store.sidebar.hide();
                        }

                        this.mobilePresent = false;
                        this.mobileClosing = false;
                        document.documentElement
                            .removeAttribute(
                                'data-sidebar');
                        window.bladcnBodyScrollLock
                            ?.unlock();
                    }, SIDEBAR_MOBILE_CLOSE_MS);
                },

                closeMobileSidebar() {
                    this.finishMobileClose({
                        hideStore: true,
                    });
                },

                handleMobileNavClick(event) {
                    if (this.$store.sidebar.matchesBreakpoint ||
                        !this.$store.sidebar.visible) {
                        return;
                    }

                    if (event.target.closest(
                            'a[href]:not([target=_blank]), button[type=submit]',
                        )) {
                        this.$store.sidebar.hide();
                    }
                },

                syncSidebarGroupDom() {
                    const sidebar = this.$el.querySelector(
                        '[data-slot="sidebar"]');

                    if (!sidebar) {
                        return;
                    }

                    const desktop = window.matchMedia(
                            DESKTOP_MQ)
                        .matches;

                    if (!this.expanded && desktop) {
                        sidebar.setAttribute('data-state',
                            'collapsed');
                        sidebar.setAttribute('data-collapsible',
                            'icon');
                    } else if (desktop) {
                        sidebar.setAttribute('data-state',
                            'expanded');
                        sidebar.removeAttribute(
                            'data-collapsible');
                    }
                },

                toggleExpanded() {
                    this.expanded = !this.expanded;
                },
            }));
        });
    </script>
@endPushOnce
