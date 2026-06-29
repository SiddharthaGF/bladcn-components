@blaze(fold: true)

@props([
    'placeholder' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'line-clamp-1 flex items-center gap-2',
    );

    $presetAttributes = [
        'data-slot' => 'select-value',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<span :class="{ 'text-muted-foreground': !value }"
    :data-placeholder="value ? null : ''"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-ref="value"
    x-text="label || @js($placeholder) || ''">
    @if (!$placeholder)
        {{ $slot }}
    @endif
</span>
