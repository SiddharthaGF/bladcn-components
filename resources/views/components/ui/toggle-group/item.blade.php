@blaze(fold: true)

@aware(['variant' => 'default', 'size' => 'default'])

@props([
    'value' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $resolvedVariant = $variant;
    $resolvedSize = $size;

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'inline-flex w-auto min-w-0 shrink-0 items-center justify-center gap-2 rounded-md px-3 text-sm font-medium whitespace-nowrap transition-[color,box-shadow] outline-none hover:bg-muted hover:text-muted-foreground focus-visible:z-10 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        )
        ->add(
            match ($resolvedVariant) {
                'outline'
                    => 'border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground group-data-[spacing=0]/toggle-group:rounded-none group-data-[spacing=0]/toggle-group:shadow-none group-data-[spacing=0]/toggle-group:first:rounded-l-md group-data-[spacing=0]/toggle-group:last:rounded-r-md group-data-[spacing=0]/toggle-group:data-[variant=outline]:border-l-0 group-data-[spacing=0]/toggle-group:data-[variant=outline]:first:border-l',
                default
                    => 'bg-transparent group-data-[spacing=0]/toggle-group:rounded-none group-data-[spacing=0]/toggle-group:first:rounded-l-md group-data-[spacing=0]/toggle-group:last:rounded-r-md',
            },
        )
        ->add(
            match ($resolvedSize) {
                'sm' => 'h-8 min-w-8 px-1.5',
                'lg' => 'h-10 min-w-10 px-2.5',
                default => 'h-9 min-w-9 px-2',
            },
        );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'toggle-group-item',
        'data-variant' => $resolvedVariant,
        'data-size' => $resolvedSize,
        'data-value' => $value,
        'disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:aria-pressed="isOn(@js($value))"
    x-bind:data-state="isOn(@js($value)) ? 'on' : 'off'"
    x-on:click="toggle(@js($value))">
    {{ $slot }}
</button>
