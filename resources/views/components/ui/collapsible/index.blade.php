@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/collapsible --}}

@props([
    'open' => false,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $presetAttributes = [
        'data-slot' => 'collapsible',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :data-state="isOpen ? 'open' : 'closed'"
    {{ $attributes->merge($presetAttributes)->class($class) }}
    data-state="{{ $open ? 'open' : 'closed' }}"
    x-data="bladcnCollapsible({ open: @js($open) })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnCollapsible', (config = {}) => ({
                isOpen: config.open ?? false,

                toggle() {
                    this.isOpen = !this.isOpen;
                },

                open() {
                    this.isOpen = true;
                },

                close() {
                    this.isOpen = false;
                },
            }));
        });
    </script>
@endPushOnce
