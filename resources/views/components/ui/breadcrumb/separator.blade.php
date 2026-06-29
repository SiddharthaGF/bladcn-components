@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        '[&>svg]:size-3.5',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-separator',
        'role' => 'presentation',
        'aria-hidden' => 'true',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<li {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    @if (trim($slot) !== '')
        {{ $slot }}
    @else
        <x-ui.icon aria-hidden="true"
            name="chevron-right" />
    @endif
</li>
