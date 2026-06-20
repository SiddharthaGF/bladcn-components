@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'field-separator',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div
    {{ $attributes->merge($presetAttributes)->class(['relative -my-2 h-5 text-sm group-data-[variant=outline]/field-group:-mb-2', $class]) }}>
    <x-ui.separator class="absolute inset-0 top-1/2" />
    @if (trim($slot) !== '')
        <span
            class="bg-background text-muted-foreground relative mx-auto block w-fit px-2"
            data-slot="field-separator-content">
            {{ $slot }}
        </span>
    @endif
</div>
