@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/toggle --}}

@props([
    'variant' => 'default',
    'size' => 'default',
    'pressed' => false,
    'disabled' => false,
    'type' => 'button',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium whitespace-nowrap transition-[color,box-shadow] outline-none hover:bg-muted hover:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        )
        ->add(
            match ($variant) {
                'outline'
                    => 'border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground',
                default => 'bg-transparent',
            },
        )
        ->add(
            match ($size) {
                'sm' => 'h-8 min-w-8 px-1.5',
                'lg' => 'h-10 min-w-10 px-2.5',
                default => 'h-9 min-w-9 px-2',
            },
        );

    $presetAttributes = [
        'type' => $type,
        'data-slot' => 'toggle',
        'data-variant' => $variant,
        'data-size' => $size,
        'disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:aria-pressed="on"
    x-bind:data-state="on ? 'on' : 'off'"
    x-data="{ on: @js($pressed) }"
    x-on:click="on = !on">
    {{ $slot }}
</button>
