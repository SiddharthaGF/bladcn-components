@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/pagination --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'mx-auto flex w-full justify-center',
    );

    $presetAttributes = [
        'role' => 'navigation',
        'aria-label' => 'pagination',
        'data-slot' => 'pagination',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<nav {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</nav>
