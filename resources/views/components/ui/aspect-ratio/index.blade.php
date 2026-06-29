@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/aspect-ratio --}}

@props([
    'ratio' => '16/9',
    'style' => null,
    'class' => null,
])

@php
    [$ratioWidth, $ratioHeight] = array_pad(
        explode('/', (string) $ratio, 2),
        2,
        1,
    );
    $ratioWidth = max(1, (float) $ratioWidth);
    $ratioHeight = max(1, (float) $ratioHeight);

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative w-full',
    );

    $presetAttributes = [
        'data-slot' => 'aspect-ratio',
        'data-ratio' => $ratio,
    ];

    $mergedStyle = trim(
        collect(["aspect-ratio: {$ratioWidth} / {$ratioHeight}", $style])
            ->filter()
            ->implode('; '),
    );
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <div
        class="absolute inset-0 size-full [&>img]:size-full [&>img]:object-cover [&>video]:size-full [&>video]:object-cover">
        {{ $slot }}
    </div>
</div>
