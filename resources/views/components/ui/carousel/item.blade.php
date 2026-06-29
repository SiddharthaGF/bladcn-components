@blaze(fold: true)

@aware(['orientation' => 'horizontal'])

@props([
    'style' => null,
    'class' => null,
])

@php
    $userClass = trim((string) ($class ?? ''));

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'min-w-0 box-border shrink-0 grow-0',
    );

    if ($orientation === 'vertical') {
        $presetClass->add('min-h-0 w-full');
    }

    if (!preg_match('/\bbasis-/', $userClass)) {
        $presetClass->add('basis-full');
    }

    $defaultSpacing = $orientation === 'horizontal' ? 'pl-4' : 'pt-4';

    if (
        !preg_match(
            '/[!]?p[trblxy]?-\d|[!]?px-\d|[!]?py-\d|[!]?p-\d/',
            $userClass,
        )
    ) {
        $presetClass->add($defaultSpacing);
    }

    $presetAttributes = [
        'role' => 'group',
        'aria-roledescription' => 'slide',
        'data-slot' => 'carousel-item',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
