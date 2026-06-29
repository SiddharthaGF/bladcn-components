@blaze(fold: true)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'mt-6 border-l-2 pl-6 italic',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'typography-blockquote',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<blockquote
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</blockquote>
