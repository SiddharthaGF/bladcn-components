@blaze(fold: true)

@props([
    'variant' => 'legend',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('mb-3 font-medium')
        ->add(
            match ($variant) {
                'label' => 'text-sm',
                default => 'text-base',
            },
        );

    $presetAttributes = [
        'data-slot' => 'field-legend',
        'data-variant' => $variant,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<legend
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</legend>
