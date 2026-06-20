@blaze(fold: false)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'menubar-sub',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['relative', $class]) }}
    x-data="bladcnMenubarSub()"
    x-on:click.outside="closeSub()">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnMenubarSub', () => ({
                isSubOpen: false,

                toggleSub() {
                    this.isSubOpen = !this.isSubOpen;
                },

                closeSub() {
                    this.isSubOpen = false;
                },
            }));
        });
    </script>
@endPushOnce
