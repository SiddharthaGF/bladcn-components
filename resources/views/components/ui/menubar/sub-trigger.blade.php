@blaze(fold: true)

@props([
    'inset' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground',
    );

    $presetAttributes = [
        'role' => 'menuitem',
        'data-slot' => 'menubar-sub-trigger',
        'data-inset' => $inset ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:data-state="isSubOpen ? 'open' : 'closed'"
    x-on:click.stop="toggleSub()">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="ml-auto size-4"
        name="chevron-right" />
</div>
