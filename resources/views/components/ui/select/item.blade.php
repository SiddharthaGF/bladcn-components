@blaze(fold: true)

@props([
    'value' => null,
    'label' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $itemLabel = preg_replace(
        '/\s+/u',
        ' ',
        trim(strip_tags($label ?? $slot->toHtml())),
    );

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground',
    );

    $presetAttributes = [
        'role' => 'option',
        'data-slot' => 'select-item',
        'data-value' => $value,
        'data-disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :aria-selected="isSelected(@js($value))"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        @click="selectFromItem($el)"
    @endunless>
    <span
        class="pointer-events-none absolute right-2 flex size-3.5 items-center justify-center"
        data-slot="select-item-indicator">
        <span x-show="isSelected(@js($value))">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="check" />
        </span>
    </span>
    <span data-slot="select-item-text">{{ $slot }}</span>
</div>
