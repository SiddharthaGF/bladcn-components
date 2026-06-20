@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/dialog --}}

@props([
    'open' => false,
    'transition' => true,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'dialog',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class($class) }}
    x-bind:data-state="isOpen ? 'open' : 'closed'"
    x-data="bladcnDialog({ open: @js($open) })"
    x-on:keydown.escape.window="close()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnDialog', (config = {}) => ({
                isOpen: config.open ?? false,

                open() {
                    this.isOpen = true;
                    document.body.classList.add(
                        'overflow-hidden');
                },

                close() {
                    this.isOpen = false;
                    document.body.classList.remove(
                        'overflow-hidden');
                },
            }));
        });
    </script>
@endPushOnce
