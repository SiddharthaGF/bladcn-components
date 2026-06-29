@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/navigation-menu --}}

@props([
    'viewport' => true,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/navigation-menu relative flex max-w-max flex-1 items-center justify-center',
    );

    $presetAttributes = [
        'data-slot' => 'navigation-menu',
        'data-viewport' => $viewport ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<nav {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnNavigationMenu()"
    x-on:click.outside="close()"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</nav>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnNavigationMenu', () => ({
                activeItem: null,

                openItem(value) {
                    this.activeItem = this.activeItem ===
                        value ? null : value;
                },

                isOpen(value) {
                    return this.activeItem === value;
                },

                close() {
                    this.activeItem = null;
                },
            }));
        });
    </script>
@endPushOnce
