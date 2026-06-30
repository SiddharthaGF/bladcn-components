@blaze(fold: true)

@props([
    'asChild' => false,
    'class' => null,
])

<x-ui.button {{ $attributes->class(['size-7', $class]) }}
    data-sidebar="trigger"
    data-slot="sidebar-trigger"
    size="icon"
    type="button"
    variant="ghost"
    x-on:click="$store.sidebar.matchesBreakpoint ? toggleExpanded() : $store.sidebar.toggle()">
    <x-ui.icon aria-hidden="true"
        class="size-4"
        name="panel-left" />
    <span class="sr-only">Toggle Sidebar</span>
</x-ui.button>
