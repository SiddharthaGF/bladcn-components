@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/separator --}}

@props([
    'class' => null,
    'orientation' => 'horizontal',
    'decorative' => true,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'bg-border shrink-0 data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px',
    );

    $presetAttributes = [
        'data-slot' => 'separator',
        'data-orientation' => $orientation,
        'role' => $decorative ? 'none' : 'separator',
    ];
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
</div>
