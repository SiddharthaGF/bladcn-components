@blaze(fold: true)

@props([
    'variant' => 'outline',
    'size' => 'default',
    'class' => null,
])

<x-ui.button :size="$size"
    :variant="$variant"
    {{ $attributes->class($class) }}
    data-slot="alert-dialog-cancel"
    x-on:click="close()">
    {{ $slot }}
</x-ui.button>
