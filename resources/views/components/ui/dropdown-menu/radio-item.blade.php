@blaze(fold: true)

@props([
    'value' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/dropdown-menu-item relative flex w-full cursor-default items-center gap-1.5 rounded-md py-1 pr-2 pl-8 text-sm outline-hidden select-none disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_[data-slot=dropdown-menu-radio-item-indicator]_[data-slot=radio-group-indicator]_svg]:size-2',
    );

    $presetAttributes = [
        'type' => 'button',
        'role' => 'menuitemradio',
        'data-slot' => 'dropdown-menu-radio-item',
        'data-value' => $value,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click.stop="selectRadio(@js($value))"
    @endunless
    @disabled($disabled)
    x-bind:aria-checked="isRadioSelected(@js($value))"
    x-bind:aria-disabled="@js($disabled)"
    x-bind:class="isDropdownItemHighlighted($id('dropdown-menu-radio-item')) ?
        'bg-accent text-accent-foreground' : ''"
    x-bind:data-menu-item-id="$id('dropdown-menu-radio-item')"
    x-bind:tabindex="isDropdownItemHighlighted($id('dropdown-menu-radio-item')) ? 0 : -1"
    x-id="['dropdown-menu-radio-item']"
    x-init="if (!$el.closest('[data-slot=dropdown-menu-sub-content]')) {
        $store.menu.registerItem(id, $id('dropdown-menu-radio-item'), { disabled: @js($disabled) });
    }"
    x-on:mouseenter="highlightItem($id('dropdown-menu-radio-item'), $el)">
    <span
        class="pointer-events-none absolute left-2 flex items-center justify-center"
        data-slot="dropdown-menu-radio-item-indicator">
        <span @class('relative flex size-4 shrink-0 items-center justify-center rounded-full border border-input text-primary shadow-xs dark:bg-input/30')
            aria-hidden="true">
            <span @class('relative flex items-center justify-center')
                data-slot="radio-group-indicator">
                <span x-show="isRadioSelected(@js($value))">
                    <x-ui.icon aria-hidden="true"
                        class="fill-primary absolute left-1/2 top-1/2 size-2 -translate-x-1/2 -translate-y-1/2"
                        name="circle" />
                </span>
            </span>
        </span>
    </span>
    {{ $slot }}
</button>
