@blaze(fold: true)

@props([
    'value' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex w-full cursor-default items-center gap-1.5 rounded-md py-1 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground hover:bg-accent hover:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[inset]:pl-8 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_[data-slot=dropdown-menu-radio-item-indicator]_[data-slot=radio-group-indicator]_svg]:size-2 [&_[data-slot=context-menu-radio-item-indicator]_[data-slot=radio-group-indicator]_svg]:size-2',
    );

    $presetAttributes = [
        'role' => 'menuitemradio',
        'data-slot' => 'context-menu-radio-item',
        'data-value' => $value,
        'data-disabled' => $disabled ? '' : null,
        'tabindex' => '-1',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click.stop="selectRadio(@js($value))"
    @endunless
    x-bind:aria-checked="isRadioSelected(@js($value))">
    <span
        class="pointer-events-none absolute left-2 flex items-center justify-center"
        data-slot="context-menu-radio-item-indicator">
        <span @class('relative flex size-4 shrink-0 items-center justify-center rounded-full border border-input text-primary shadow-xs dark:bg-input/30')
            aria-hidden="true">
            <span @class('relative flex items-center justify-center')
                data-slot="radio-group-indicator">
                <span x-show="isRadioSelected(@js($value))">
                    <x-ui.icon aria-hidden="true"
                        class="{{ 'absolute top-1/2 left-1/2 size-2 -translate-x-1/2 -translate-y-1/2 fill-primary' }}"
                        name="circle" />
                </span>
            </span>
        </span>
    </span>
    {{ $slot }}
</div>
