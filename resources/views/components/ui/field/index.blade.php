@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/field --}}

@props([
    'orientation' => 'vertical',
    'invalid' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/field flex w-full gap-3 data-[invalid=true]:text-destructive',
        )
        ->add(
            match ($orientation) {
                'horizontal'
                    => 'flex-row items-center [&>[data-slot=field-label]]:flex-auto has-[>[data-slot=field-content]]:items-start has-[>[data-slot=field-content]]:[&>[role=checkbox],[role=radio]]:mt-px',
                'responsive'
                    => 'flex-col @md/field-group:flex-row @md/field-group:items-center [&>*]:w-full @md/field-group:[&>*]:w-auto @md/field-group:[&>[data-slot=field-label]]:flex-auto @md/field-group:has-[>[data-slot=field-content]]:items-start',
                default => 'flex-col [&>*]:w-full [&>.sr-only]:w-auto',
            },
        );

    $presetAttributes = [
        'role' => 'group',
        'data-slot' => 'field',
        'data-orientation' => $orientation,
        'data-invalid' => $invalid ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
