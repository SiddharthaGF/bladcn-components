@blaze(fold: false)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/sidebar-wrapper flex min-h-0 w-full',
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
    x-init="$store.bladcnSidebar.init()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.store('bladcnSidebar', {
                open: true,
                openMobile: false,
                isMobile: false,
                mediaQuery: null,

                init() {
                    this.mediaQuery = window.matchMedia(
                        '(max-width: 767px)');
                    this.isMobile = this.mediaQuery.matches;
                    this.mediaQuery.addEventListener('change', (
                        event) => {
                        this.isMobile = event.matches;
                    });
                },

                get state() {
                    return this.open ? 'expanded' : 'collapsed';
                },

                toggle() {
                    if (this.isMobile) {
                        this.openMobile = !this.openMobile;

                        return;
                    }

                    this.open = !this.open;
                },

                setOpen(value) {
                    if (this.isMobile) {
                        this.openMobile = value;

                        return;
                    }

                    this.open = value;
                },
            });
        });
    </script>
@endPushOnce
