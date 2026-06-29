@blaze(fold: true)

@aware(['value' => null])

@props([
    'asChild' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group inline-flex h-9 w-max items-center justify-center rounded-md bg-background px-4 py-2 text-sm font-medium transition-[color,box-shadow] outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 disabled:pointer-events-none disabled:opacity-50 data-[state=open]:bg-accent/50 data-[state=open]:text-accent-foreground',
    );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'navigation-menu-trigger',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }

    $alpineAttributes = [
        'x-on:click' =>
            'openItem(' . \Illuminate\Support\Js::from($value) . ')',
        'x-bind:data-state' =>
            'isOpen(' .
            \Illuminate\Support\Js::from($value) .
            ') ? \'open\' : \'closed\'',
    ];
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->merge($alpineAttributes)->class([$presetClass, $class]) }}
    default-tag="button">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="relative top-px ml-1 size-3 transition duration-300 group-data-[state=open]:rotate-180"
        name="chevron-down" />
</x-ui.abstract>
