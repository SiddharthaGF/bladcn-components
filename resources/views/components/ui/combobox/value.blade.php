@blaze(fold: true)

@props([
    'placeholder' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'line-clamp-1 flex flex-1 items-center gap-2 text-left',
    );

    $presetAttributes = [
        'data-slot' => 'combobox-value',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<span {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:class="{ 'text-muted-foreground': !value }"
    x-text="label || @js($placeholder) || ''">
    @if (!$placeholder)
        {{ $slot }}
    @endif
</span>
