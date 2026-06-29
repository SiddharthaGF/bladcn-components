@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'border-t bg-muted/50 font-medium [&>tr]:last:border-b-0',
    );

    $presetAttributes = [
        'data-slot' => 'table-footer',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<tfoot
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</tfoot>
