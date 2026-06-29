@blaze(fold: true)

@aware(['id', 'descriptionId', 'messageId', 'error' => false])

@props([
    'message' => null,
    'style' => null,
    'class' => null,
])

@php
    $content = $message ?? (is_string($error) ? $error : null);

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-sm font-medium text-destructive',
    );

    $presetAttributes = [
        'data-slot' => 'form-message',
        'id' => $messageId,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

@if (filled($content))
    <p
        {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
        {{ $content }}
    </p>
@endif
