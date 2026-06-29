@blaze(fold: true)

@aware(['id', 'descriptionId', 'messageId', 'error' => false])

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'data-[error=true]:text-destructive',
    );

    $presetAttributes = [
        'data-slot' => 'form-label',
        'for' => $id,
        'data-error' => $error ? '' : null,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<x-ui.label
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</x-ui.label>
