@blaze(fold: true)

@props([
    'value' => null,
    'checked' => false,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/dropdown-menu-item relative flex w-full cursor-default items-center gap-1.5 rounded-md px-1.5 py-1 pr-8 text-sm outline-hidden select-none disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_[data-slot=dropdown-menu-checkbox-item-indicator]_svg]:size-4',
    );

    $presetAttributes = [
        'type' => 'button',
        'role' => 'menuitemcheckbox',
        'data-slot' => 'dropdown-menu-checkbox-item',
        'data-value' => $value,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click.stop="toggleCheckbox(@js($value))"
    @endunless
    @disabled($disabled)
    x-bind:aria-checked="isCheckboxChecked(@js($value))"
    x-bind:aria-disabled="@js($disabled)"
    x-bind:class="isDropdownItemHighlighted($id('dropdown-menu-checkbox-item')) ?
        'bg-accent text-accent-foreground' : ''"
    x-bind:data-menu-item-id="$id('dropdown-menu-checkbox-item')"
    x-bind:tabindex="isDropdownItemHighlighted($id('dropdown-menu-checkbox-item')) ? 0 : -1"
    x-id="['dropdown-menu-checkbox-item']"
    x-init="if (!$el.closest('[data-slot=dropdown-menu-sub-content]')) {
        $store.menu.registerItem(id, $id('dropdown-menu-checkbox-item'), { disabled: @js($disabled) });
    }"
    x-on:mouseenter="highlightItem($id('dropdown-menu-checkbox-item'), $el)">
    <span
        class="pointer-events-none absolute right-2 flex items-center justify-center"
        data-slot="dropdown-menu-checkbox-item-indicator">
        <span x-show="isCheckboxChecked(@js($value))">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="check" />
        </span>
    </span>
    {{ $slot }}
</button>
