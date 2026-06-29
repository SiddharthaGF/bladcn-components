@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/tabs --}}

@props([
    'defaultValue' => null,
    'orientation' => 'horizontal',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/tabs flex gap-2 data-[orientation=horizontal]:flex-col',
    );

    $presetAttributes = [
        'data-slot' => 'tabs',
        'data-orientation' => $orientation,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnTabs({
        defaultValue: @js($defaultValue),
        orientation: @js($orientation),
    })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnTabs', (config = {}) => ({
                activeTab: config.defaultValue ?? null,
                orientation: config.orientation ?? 'horizontal',

                activate(value) {
                    this.activeTab = value;
                    this.$dispatch('tabs-change', {
                        value
                    });
                },

                isActive(value) {
                    return this.activeTab === value;
                },
            }));
        });
    </script>
@endPushOnce
