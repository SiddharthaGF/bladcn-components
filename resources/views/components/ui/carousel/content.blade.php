@blaze(fold: true)

@aware(['orientation' => 'horizontal'])

@props([
    'style' => null,
    'class' => null,
])

@php
    $userClass = trim((string) ($class ?? ''));

    $viewportClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-hidden outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1',
    );

    $trackClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add('flex');

    if ($orientation === 'vertical') {
        $trackClass->add('flex-col');
    }

    if ($orientation === 'horizontal' && !preg_match('/-ml-/', $userClass)) {
        $trackClass->add('-ml-4');
    }

    if ($orientation === 'vertical' && !preg_match('/-mt-/', $userClass)) {
        $trackClass->add('-mt-4');
    }

    if (preg_match('/-mt-(\[[^\]]+\]|\d+)/', $userClass, $marginMatch)) {
        $viewportClass->add('pt-' . $marginMatch[1]);
    }

    if (preg_match('/-ml-(\[[^\]]+\]|\d+)/', $userClass, $marginMatch)) {
        $viewportClass->add('pl-' . $marginMatch[1]);
    }
@endphp

<div @class($viewportClass)
    data-slot="carousel-content"
    x-ref="viewport">
    <div @class([$trackClass, $userClass])
        x-ref="container">
        {{ $slot }}
    </div>
</div>
