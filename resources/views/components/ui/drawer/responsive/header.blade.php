@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

<template x-if="isDesktop">
    <x-ui.dialog.header {{ $attributes->class($class) }}>
        {{ $slot }}
    </x-ui.dialog.header>
</template>
<template x-if="!isDesktop">
    <x-ui.drawer.header {{ $attributes->class(['text-left', $class]) }}>
        {{ $slot }}
    </x-ui.drawer.header>
</template>
