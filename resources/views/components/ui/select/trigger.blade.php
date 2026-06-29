@blaze(fold: true)

@props([
    'size' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'flex w-fit items-center justify-between gap-2 rounded-md border border-input bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:bg-input/30 dark:hover:bg-input/50 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2',
        )
        ->add(
            match ($size) {
                'sm' => 'h-8',
                default => 'h-9',
            },
        );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'select-trigger',
        'data-size' => $size,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button :aria-expanded="isOpen"
    :disabled="disabled"
    @click="toggle()"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-ref="trigger">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="size-4 opacity-50"
        name="chevron-down" />
</button>
