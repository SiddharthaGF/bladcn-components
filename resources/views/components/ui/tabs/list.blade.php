@blaze(fold: true)

@props([
    'variant' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/tabs-list inline-flex w-fit items-center justify-center rounded-lg p-[3px] text-muted-foreground group-data-[orientation=horizontal]/tabs:h-9 group-data-[orientation=vertical]/tabs:h-fit group-data-[orientation=vertical]/tabs:flex-col data-[variant=line]:rounded-none',
        )
        ->add(
            match ($variant) {
                'line' => 'gap-1 bg-transparent',
                default => 'bg-muted',
            },
        );

    $presetAttributes = [
        'role' => 'tablist',
        'data-slot' => 'tabs-list',
        'data-variant' => $variant,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
