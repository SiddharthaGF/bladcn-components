@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex flex-wrap items-center gap-1.5 text-sm break-words text-muted-foreground sm:gap-2.5',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-list',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<ol {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</ol>
