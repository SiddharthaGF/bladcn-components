@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/textarea --}}

@props([
    'id' => null,
    'name' => null,
    'style' => null,
    'class' => null,
    'disabled' => false,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex field-sizing-content min-h-16 w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 md:text-sm dark:bg-input/30 dark:aria-invalid:ring-destructive/40',
    );

    $presetAttributes = [
        'id' => $id,
        'name' => $name,
        'data-slot' => 'textarea',
        'disabled' => $disabled ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<textarea
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>{{ $slot }}</textarea>
