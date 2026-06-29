@blaze(fold: true)

@props([
    'inset' => false,
    'variant' => 'default',
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/dropdown-menu-item group/context-menu-item relative flex w-full cursor-default items-center gap-1.5 rounded-md px-1.5 py-1 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground hover:bg-accent hover:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[inset]:pl-8 data-[variant=destructive]:text-destructive data-[variant=destructive]:focus:bg-destructive/10 data-[variant=destructive]:focus:text-destructive dark:data-[variant=destructive]:focus:bg-destructive/20 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 data-[variant=destructive]:*:[svg]:text-destructive',
    );

    $presetAttributes = [
        'role' => 'menuitem',
        'data-slot' => 'dropdown-menu-item',
        'data-inset' => $inset ? '' : null,
        'data-variant' => $variant,
        'data-disabled' => $disabled ? '' : null,
        'tabindex' => '-1',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click="close()"
    @endunless>
    {{ $slot }}
</div>
