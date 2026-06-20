@blaze(fold: true)

@props([
    'variant' => 'default',
    'size' => 'default',
    'class' => null,
])

<x-ui.button :size="$size"
    :variant="$variant"
    {{ $attributes->class($class) }}
    data-slot="alert-dialog-action"
    x-on:click="close()">
    {{ $slot }}
</x-ui.button>
