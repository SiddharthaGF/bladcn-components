@blaze(fold: true)

@props([
    'value' => null,
    'label' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $itemLabel = $label ?? trim(strip_tags($slot->toHtml()));

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
    );

    $presetAttributes = [
        'role' => 'option',
        'data-slot' => 'combobox-item',
        'data-value' => $value,
        'data-disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click="select(@js($value), @js($itemLabel))"
    @endunless
    x-bind:class="{
        'bg-accent text-accent-foreground': isSelected(
            @js($value))
    }"
    x-cloak
    x-show="matchesLabel(@js($itemLabel))">
    <span class="absolute right-2 flex size-3.5 items-center justify-center"
        data-slot="combobox-item-indicator">
        <span x-show="isSelected(@js($value))">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="check" />
        </span>
    </span>
    {{ $slot }}
</div>
