@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex min-w-0 flex-1 flex-col bg-background',
    );

    $presetAttributes = [
        'data-slot' => 'sidebar-inset',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<main
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</main>
