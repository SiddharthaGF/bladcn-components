@blaze(fold: true)

@props([
    'inset' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/dropdown-menu-item flex w-full cursor-default items-center gap-1.5 rounded-md px-1.5 py-1 text-sm outline-hidden select-none data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
    );

    $presetAttributes = [
        'type' => 'button',
        'role' => 'menuitem',
        'data-slot' => 'dropdown-menu-sub-trigger',
        'data-inset' => $inset ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:class="isDropdownItemHighlighted($id('dropdown-menu-sub-trigger')) ?
        'bg-accent text-accent-foreground' : ''"
    x-bind:data-menu-item-id="$id('dropdown-menu-sub-trigger')"
    x-bind:data-state="isSubOpen ? 'open' : 'closed'"
    x-bind:tabindex="isDropdownItemHighlighted($id('dropdown-menu-sub-trigger')) ? 0 : -1"
    x-id="['dropdown-menu-sub-trigger']"
    x-init="$store.menu.registerItem(id, $id('dropdown-menu-sub-trigger'))"
    x-on:click.stop="openSub($event)"
    x-on:keydown.arrow-right.prevent="openSub($event)"
    x-on:keydown.enter.prevent="openSub($event)"
    x-on:mouseenter="highlightItem($id('dropdown-menu-sub-trigger'), $el); openSub($event)"
    x-on:mouseleave="scheduleClose()"
    x-ref="subTrigger">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="ml-auto size-4 shrink-0 opacity-60"
        name="chevron-right" />
</button>
