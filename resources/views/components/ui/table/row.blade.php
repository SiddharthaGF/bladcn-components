@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'border-b transition-colors hover:bg-muted/50 has-aria-expanded:bg-muted/50 data-[state=selected]:bg-muted',
    );

    $presetAttributes = [
        'data-slot' => 'table-row',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<tr {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</tr>
