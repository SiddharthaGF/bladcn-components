@blaze(fold: true)

@props([
    'size' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'flex w-full items-center justify-between gap-2 rounded-md border border-input bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-input/30 dark:hover:bg-input/50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground',
        )
        ->add(
            match ($size) {
                'sm' => 'h-8',
                default => 'h-9',
            },
        );

    $presetAttributes = [
        'type' => 'button',
        'data-slot' => 'combobox-trigger',
        'data-size' => $size,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<button
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-bind:aria-expanded="isOpen"
    x-bind:disabled="disabled"
    x-on:click="toggle()"
    x-ref="trigger">
    {{ $slot }}
    <x-ui.icon aria-hidden="true"
        class="size-4 opacity-50"
        name="chevron-down" />
</button>
