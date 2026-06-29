@blaze(fold: true)

@props([
    'id' => null,
    'descriptionId' => null,
    'messageId' => null,
    'error' => false,
    'style' => null,
    'class' => null,
])

@php
    $id = filled($id)
        ? $id
        : 'form-item-' . \Illuminate\Support\Str::uuid()->toString();
    $descriptionId = filled($descriptionId)
        ? $descriptionId
        : "{$id}-description";
    $messageId = filled($messageId) ? $messageId : "{$id}-message";

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'grid gap-2',
    );

    $presetAttributes = [
        'data-slot' => 'form-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
