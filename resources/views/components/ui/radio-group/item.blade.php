@blaze(fold: true)

@aware(['name'])

@props([
    'id' => null,
    'name' => null,
    'value' => null,
    'style' => null,
    'class' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $controlClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'pointer-events-none col-start-1 row-start-1 aspect-square size-4 shrink-0 rounded-full border border-input text-primary shadow-xs transition-[color,box-shadow] outline-none peer-focus-visible:border-ring peer-focus-visible:ring-[3px] peer-focus-visible:ring-ring/50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50 peer-aria-invalid:border-destructive peer-aria-invalid:ring-destructive/20 dark:bg-input/30 dark:peer-aria-invalid:ring-destructive/40 peer-checked:[&_[data-slot=radio-group-indicator]]:opacity-100',
    );

    $indicatorClass = 'relative flex items-center justify-center opacity-0';

    $inputAttributes = [
        'id' => $id,
        'name' => $name,
        'type' => 'radio',
        'value' => $value,
        'data-slot' => 'radio-group-item',
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
    <span @class([$controlClass, $class])
        aria-hidden="true">
        <span @class($indicatorClass)
            data-slot="radio-group-indicator">
            <x-ui.icon aria-hidden="true"
                class="{{ 'absolute top-1/2 left-1/2 size-2 -translate-x-1/2 -translate-y-1/2 fill-primary' }}"
                name="circle" />
        </span>
    </span>
</span>
