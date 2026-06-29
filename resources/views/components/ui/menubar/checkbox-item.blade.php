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
        'relative flex cursor-default items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
    );

    $presetAttributes = [
        'role' => 'menuitemcheckbox',
        'data-slot' => 'menubar-checkbox-item',
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
        x-on:click.stop="toggleCheckbox(@js($value))"
    @endunless
    x-bind:aria-checked="isCheckboxChecked(@js($value))">
    <span
        class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <span x-show="isCheckboxChecked(@js($value))">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="check" />
        </span>
    </span>
    {{ $slot }}
</div>
