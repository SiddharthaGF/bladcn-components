@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/kbd --}}

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'pointer-events-none inline-flex h-5 w-fit min-w-5 items-center justify-center gap-1 rounded-sm bg-muted px-1 font-sans text-xs font-medium text-muted-foreground select-none',
        )
        ->add(
            '[&_svg:not([class*=\'size-\'])]:size-3 [[data-slot=tooltip-content]_&]:bg-background/20 [[data-slot=tooltip-content]_&]:text-background dark:[[data-slot=tooltip-content]_&]:bg-background/10',
        );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'kbd',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<kbd {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</kbd>
