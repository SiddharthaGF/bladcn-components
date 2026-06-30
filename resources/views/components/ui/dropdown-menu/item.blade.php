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
        'group/dropdown-menu-item relative flex w-full cursor-default items-center gap-1.5 rounded-md px-1.5 py-1 text-sm outline-hidden select-none disabled:pointer-events-none disabled:opacity-50 data-[inset]:pl-8 data-[variant=destructive]:text-destructive [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 data-[variant=destructive]:*:[svg]:text-destructive',
    );

    $presetAttributes = [
        'type' => 'button',
        'role' => 'menuitem',
        'data-slot' => 'dropdown-menu-item',
        'data-inset' => $inset ? '' : null,
        'data-variant' => $variant,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click="
            $el.closest('[data-slot=dropdown-menu-sub-content]')
                ? $store.menu.close(id)
                : $store.menu.selectItem(id, $id('dropdown-menu-item'))
        "
    @endunless
    @disabled($disabled)
    x-bind:aria-disabled="@js($disabled)"
    x-bind:class="isDropdownItemHighlighted($id('dropdown-menu-item')) ?
        'bg-accent text-accent-foreground' : ''"
    x-bind:data-menu-item-id="$id('dropdown-menu-item')"
    x-bind:tabindex="isDropdownItemHighlighted($id('dropdown-menu-item')) ? 0 : -1"
    x-id="['dropdown-menu-item']"
    x-init="if (!$el.closest('[data-slot=dropdown-menu-sub-content]')) {
        $store.menu.registerItem(id, $id('dropdown-menu-item'), { disabled: @js($disabled) });
    }"
    x-on:mouseenter="highlightItem($id('dropdown-menu-item'), $el)">
    {{ $slot }}
</button>
