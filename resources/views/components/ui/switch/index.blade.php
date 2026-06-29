@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/switch --}}

@props([
    'id' => null,
    'name' => null,
    'value' => '1',
    'size' => 'default',
    'style' => null,
    'class' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $trackClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/switch pointer-events-none col-start-1 row-start-1 inline-flex shrink-0 items-center rounded-full border border-transparent shadow-xs transition-all outline-none peer-focus-visible:border-ring peer-focus-visible:ring-[3px] peer-focus-visible:ring-ring/50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50 bg-input dark:bg-input/80 peer-checked:bg-primary dark:peer-checked:bg-primary peer-checked:[&_[data-slot=switch-thumb]]:translate-x-[calc(100%-2px)] dark:peer-checked:[&_[data-slot=switch-thumb]]:bg-primary-foreground',
        )
        ->add(
            match ($size) {
                'sm' => 'h-3.5 w-6',
                default => 'h-[1.15rem] w-8',
            },
        );

    $thumbClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'pointer-events-none block translate-x-0 rounded-full bg-background ring-0 transition-transform dark:bg-foreground',
        )
        ->add(
            match ($size) {
                'sm' => 'size-3',
                default => 'size-4',
            },
        );

    $inputSizeClass = match ($size) {
        'sm' => 'h-3.5 w-6',
        default => 'h-[1.15rem] w-8',
    };

    $inputAttributes = [
        'id' => $id,
        'name' => $name,
        'type' => 'checkbox',
        'role' => 'switch',
        'value' => $value,
        'disabled' => $disabled ? '' : null,
    ];

    if ($checked) {
        $inputAttributes['checked'] = '';
    }

    if (filled($style)) {
        $inputAttributes['style'] = $style;
    }
@endphp

<span class="inline-grid shrink-0">
    <input
        {{ $attributes->except('class')->merge($inputAttributes)->class(['peer col-start-1 row-start-1 m-0 cursor-pointer opacity-0 disabled:cursor-not-allowed', $inputSizeClass]) }} />
    <span @class([$trackClass, $class])
        aria-hidden="true"
        data-size="{{ $size }}"
        data-slot="switch">
        <span @class($thumbClass)
            data-slot="switch-thumb"></span>
    </span>
</span>
