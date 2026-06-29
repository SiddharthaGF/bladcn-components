@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex size-9 items-center justify-center',
    );

    $presetAttributes = [
        'data-slot' => 'breadcrumb-ellipsis',
        'role' => 'presentation',
        'aria-hidden' => 'true',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<span
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <x-ui.icon aria-hidden="true"
        class="size-4"
        name="ellipsis" />
    <span class="sr-only">More</span>
</span>
