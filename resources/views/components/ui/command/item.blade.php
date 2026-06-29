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
        'group/command-item relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none in-data-[slot=dialog-content]:rounded-lg! data-[disabled=true]:pointer-events-none data-[disabled=true]:opacity-50 data-selected:bg-muted data-selected:text-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 data-selected:*:[svg]:text-foreground',
    );

    $presetAttributes = [
        'role' => 'option',
        'data-slot' => 'command-item',
        'data-value' => $value,
        'data-disabled' => $disabled ? 'true' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        x-on:click="$dispatch('command-select', { value: @js($value), label: @js($itemLabel) })"
        x-on:mouseenter="highlightedValue = @js($value)"
    @endunless
    x-bind:aria-selected="isHighlighted(@js($value))"
    x-bind:data-checked="isHighlighted(@js($value)) ? true : null"
    x-bind:data-selected="isHighlighted(@js($value)) ? '' : null"
    x-show="matchesQuery(@js($itemLabel))">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="group-has-data-[slot=command-shortcut]/command-item:hidden ml-auto opacity-0 group-data-[checked=true]/command-item:opacity-100"
        name="check" />
</div>
