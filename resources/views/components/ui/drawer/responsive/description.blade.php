@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

<template x-if="isDesktop">
    <x-ui.dialog.description
        {{ $attributes->class($class) }}>{{ $slot }}</x-ui.dialog.description>
</template>
<template x-if="!isDesktop">
    <x-ui.drawer.description
        {{ $attributes->class($class) }}>{{ $slot }}</x-ui.drawer.description>
</template>
