@blaze(fold: true)

@props([
    'asChild' => false,
    'isActive' => false,
    'href' => '#',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left text-sm outline-hidden ring-sidebar-ring transition-[width,height,padding] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 group-data-[collapsible=icon]/sidebar-wrapper:size-8! group-data-[collapsible=icon]/sidebar-wrapper:p-2! [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
    );

    if ($isActive) {
        $presetClass->add(
            'bg-sidebar-accent font-medium text-sidebar-accent-foreground',
        );
    }

    $presetAttributes = [
        'data-slot' => 'sidebar-menu-button',
        'data-sidebar' => 'menu-button',
        'data-active' => $isActive ? '' : null,
        'href' => $href,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    default-tag="a">
    {{ $slot }}
</x-ui.abstract>
