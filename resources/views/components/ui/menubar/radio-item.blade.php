@blaze(fold: true)

@props([
    'value' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex cursor-default items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
    );

    $presetAttributes = [
        'role' => 'menuitemradio',
        'data-slot' => 'menubar-radio-item',
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
        class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <span x-show="isRadioSelected(@js($value))">
            <x-ui.icon aria-hidden="true"
                class="size-2 fill-current"
                name="circle" />
        </span>
    </span>
    {{ $slot }}
</div>
