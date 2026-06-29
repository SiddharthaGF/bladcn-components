@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/input --}}

@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'style' => null,
    'class' => null,
    'disabled' => false,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'h-9 w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none selection:bg-primary selection:text-primary-foreground file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm dark:bg-input/30',
        )
        ->add(
            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40',
        );

    $presetAttributes = [
        'id' => $id,
        'name' => $name,
        'type' => $type,
        'data-slot' => 'input',
        'disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<input
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }} />
