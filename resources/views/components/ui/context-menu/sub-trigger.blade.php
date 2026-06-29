@blaze(fold: true)

@props([
    'inset' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'flex w-full cursor-default items-center gap-1.5 rounded-md px-1.5 py-1 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground hover:bg-accent hover:text-accent-foreground data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        )
        ->add('w-full');

    $presetAttributes = [
        'role' => 'menuitem',
        'data-slot' => 'context-menu-sub-trigger',
        'data-inset' => $inset ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:data-state="isSubOpen ? 'open' : 'closed'"
    x-on:click.stop="openSub($event)"
    x-on:mouseenter="openSub($event)"
    x-on:mouseleave="scheduleClose()"
    x-ref="subTrigger">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="ml-auto size-4 shrink-0 opacity-60"
        name="chevron-right" />
</div>
