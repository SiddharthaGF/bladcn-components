@blaze(fold: true)

@props([
    'isActive' => false,
    'size' => 'icon',
    'href' => '#',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'inline-flex shrink-0 items-center justify-center rounded-lg border border-transparent bg-clip-padding text-sm font-medium whitespace-nowrap transition-all outline-none select-none focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4',
        )
        ->add(
            $isActive
                ? 'border-border bg-background hover:bg-muted hover:text-foreground dark:border-input dark:bg-input/30 dark:hover:bg-input/50'
                : 'hover:bg-muted hover:text-foreground dark:hover:bg-muted/50',
        )
        ->add(
            match ($size) {
                'default'
                    => 'h-8 gap-1.5 px-2.5 has-data-[icon=inline-end]:pr-2 has-data-[icon=inline-start]:pl-2',
                'sm'
                    => 'h-7 gap-1 rounded-[min(var(--radius-md),12px)] px-2.5 text-[0.8rem] [&_svg:not([class*=\'size-\'])]:size-3.5',
                'lg' => 'h-9 gap-1.5 px-2.5',
                default => 'size-8',
            },
        );

    $presetAttributes = [
        'data-slot' => 'pagination-link',
        'data-active' => $isActive ? '' : null,
        'href' => $href,
        'aria-current' => $isActive ? 'page' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<a {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</a>
