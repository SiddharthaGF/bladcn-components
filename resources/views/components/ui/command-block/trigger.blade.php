@blaze(fold: true)

@aware(['defaultValue'])

@props([
    'value' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'inline-flex h-7 items-center rounded-md border border-transparent px-2.5 text-xs font-medium transition-colors',
        )
        ->add(
            'data-[state=active]:border-border data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:hover:bg-muted data-[state=active]:hover:text-foreground dark:data-[state=active]:border-input ',
        )
        ->add(
            'data-[state=inactive]:text-muted-foreground data-[state=inactive]:hover:text-foreground',
        );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'command-block-trigger',
        'data-value' => $value,
        'disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    :data-state="isActive(@js($value)) ? 'active' : 'inactive'"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    @unless ($disabled)
        @click="select(@js($value))"
    @endunless>
    {{ $slot }}
</button>
