@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-sm leading-normal font-normal text-muted-foreground group-has-[[data-orientation=horizontal]]/field:text-balance [&>a]:underline [&>a]:underline-offset-4 [&>a:hover]:text-primary',
    );

    $presetAttributes = [
        'data-slot' => 'field-description',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<p {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</p>
