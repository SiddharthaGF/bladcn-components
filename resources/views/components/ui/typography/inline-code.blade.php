@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative rounded bg-muted px-[0.3rem] py-[0.2rem] font-mono text-sm font-semibold',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-inline-code',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<code
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</code>
