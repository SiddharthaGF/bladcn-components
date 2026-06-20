@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

<template x-if="isDesktop">
    <x-ui.dialog.title
        {{ $attributes->class($class) }}>{{ $slot }}</x-ui.dialog.title>
</template>
<template x-if="!isDesktop">
    <x-ui.drawer.title
        {{ $attributes->class($class) }}>{{ $slot }}</x-ui.drawer.title>
</template>
