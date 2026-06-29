@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/alert --}}

@props([
    'id' => null,
    'variant' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'relative grid w-full grid-cols-[0_1fr] items-start gap-y-0.5 rounded-lg border px-4 py-3 text-sm has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] has-[>svg]:gap-x-3 [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current',
        )
        ->add(
            match ($variant) {
                'destructive'
                    => 'bg-card text-destructive *:data-[slot=alert-description]:text-destructive/90 [&>svg]:text-current',
                default => 'bg-card text-card-foreground',
            },
        );

    $presetAttributes = [
        'id' => $id,
        'role' => 'alert',
        'data-slot' => 'alert',
        'data-variant' => $variant,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
