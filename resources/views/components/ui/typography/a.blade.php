@blaze(fold: true)

@props([
    'id' => null,
    'href' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'font-medium text-primary underline underline-offset-4',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-a',
    ];

    if (filled($href)) {
        $presetAttributes['href'] = $href;
    }

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<a {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</a>
