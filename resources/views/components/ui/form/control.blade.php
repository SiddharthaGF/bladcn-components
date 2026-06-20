@blaze(fold: true)

@aware(['id', 'descriptionId', 'messageId', 'error' => false])

@props([
    'asChild' => true,
    'style' => null,
    'class' => null,
])

@php
    $describedBy = trim(
        collect([$descriptionId, $error ? $messageId : null])
            ->filter()
            ->implode(' '),
    );

    $presetAttributes = [
        'data-slot' => 'form-control',
        'id' => $id,
        'aria-describedby' => $describedBy !== '' ? $describedBy : null,
        'aria-invalid' => $error ? 'true' : 'false',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

@if ($asChild)
    {!! \AiluraCode\Bladcn\Support\AsChildSlot::render(
        $slot->toHtml(),
        $attributes->merge($presetAttributes),
    ) !!}
@else
    <div {{ $attributes->merge($presetAttributes)->class($class) }}>
        {{ $slot }}
    </div>
@endif
