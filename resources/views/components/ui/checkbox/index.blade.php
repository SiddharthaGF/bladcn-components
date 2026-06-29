@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/checkbox --}}

@props([
    'id' => null,
    'name' => null,
    'value' => '1',
    'style' => null,
    'class' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $boxClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'pointer-events-none col-start-1 row-start-1 size-4 shrink-0 rounded-[4px] border border-input shadow-xs transition-shadow outline-none peer-focus-visible:border-ring peer-focus-visible:ring-[3px] peer-focus-visible:ring-ring/50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50 peer-aria-invalid:border-destructive peer-aria-invalid:ring-destructive/20 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground peer-checked:[&_svg]:opacity-100 dark:bg-input/30 dark:peer-aria-invalid:ring-destructive/40 dark:peer-checked:bg-primary',
    );

    $inputAttributes = [
        'id' => $id,
        'name' => $name,
        'type' => 'checkbox',
        'value' => $value,
        'data-slot' => 'checkbox',
        'disabled' => $disabled ? '' : null,
    ];

    if ($checked) {
        $inputAttributes['checked'] = '';
    }

    if (filled($style)) {
        $inputAttributes['style'] = $style;
    }
@endphp

<span class="inline-grid size-4 shrink-0">
    <input
        {{ $attributes->except('class')->merge($inputAttributes)->class('peer col-start-1 row-start-1 m-0 size-4 cursor-pointer opacity-0 disabled:cursor-not-allowed') }} />
    <span @class([$boxClass, $class])
        aria-hidden="true">
        <span
            class="grid size-full place-content-center text-current transition-none"
            data-slot="checkbox-indicator">
            <x-ui.icon aria-hidden="true"
                class="size-3.5 opacity-0"
                name="check" />
        </span>
    </span>
</span>
