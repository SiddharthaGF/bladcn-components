@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/sidebar --}}

@props([
    'side' => 'left',
    'collapsible' => 'icon',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'hidden h-full shrink-0 flex-col border-sidebar-border bg-sidebar text-sidebar-foreground transition-[width] duration-200 ease-linear md:flex',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar',
        'data-side' => $side,
        'data-collapsible' => $collapsible,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<aside
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:class="$store.bladcnSidebar.open ? 'w-(--sidebar-width)' :
        'w-(--sidebar-width-icon)'"
    x-bind:data-state="$store.bladcnSidebar.state">
    <div class="flex h-full w-full flex-col overflow-hidden"
        data-sidebar="sidebar"
        data-slot="sidebar-inner">
        {{ $slot }}
    </div>
</aside>
