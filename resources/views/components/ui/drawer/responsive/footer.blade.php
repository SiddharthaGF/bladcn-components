@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

<template x-if="isDesktop">
    <x-ui.dialog.footer {{ $attributes->class($class) }}>
        {{ $slot }}
    </x-ui.dialog.footer>
</template>
<template x-if="!isDesktop">
    <x-ui.drawer.footer {{ $attributes->class(['pt-2', $class]) }}>
        {{ $slot }}
    </x-ui.drawer.footer>
</template>
