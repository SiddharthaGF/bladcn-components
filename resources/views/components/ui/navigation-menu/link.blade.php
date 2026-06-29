@blaze(fold: true)

@props([
    'href' => '#',
    'active' => false,
    'asChild' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex flex-col gap-1 rounded-sm p-2 text-sm transition-all outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 data-[active=true]:bg-accent/50 data-[active=true]:text-accent-foreground [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'navigation-menu-link',
        'data-active' => $active ? '' : null,
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
