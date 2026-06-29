@blaze(fold: true)

@props([
    'for' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/field-label peer/field-label flex w-fit gap-2 leading-snug group-data-[disabled=true]/field:opacity-50',
    );

    $presetAttributes = [
        'data-slot' => 'field-label',
    ];

    if (filled($for)) {
        $presetAttributes['for'] = $for;
    }

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.label
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</x-ui.label>
