@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'input-otp-separator',
        'role' => 'separator',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div
    {{ $attributes->merge($presetAttributes)->class(['flex items-center', $class]) }}>
    <x-ui.icon aria-hidden="true"
        class="text-muted-foreground size-4"
        name="minus" />
</div>
