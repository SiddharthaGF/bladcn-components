@blaze(fold: true)

@props([
    'align' => 'center',
    'side' => 'bottom',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $positionClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('absolute z-50 pointer-events-none w-max max-w-none')
        ->add(
            match ($side) {
                'top' => 'bottom-full',
                'left' => match ($align) {
                    'start' => 'right-full top-0',
                    'end' => 'right-full bottom-0',
                    default => 'right-full top-1/2 -translate-y-1/2',
                },
                'right' => match ($align) {
                    'start' => 'left-full top-0',
                    'end' => 'left-full bottom-0',
                    default => 'left-full top-1/2 -translate-y-1/2',
                },
                default => 'top-full',
            },
        )
        ->add(
            in_array($side, ['top', 'bottom'], true)
                ? match ($align) {
                    'start' => 'left-0',
                    'end' => 'right-0',
                    default => 'left-1/2 -translate-x-1/2',
                }
                : '',
        );

    $surfaceClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'pointer-events-auto w-64 shrink-0 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    );

    $offsetStyle = match ($side) {
        'top' => "margin-bottom: {$sideOffset}px",
        'left' => "margin-right: {$sideOffset}px",
        'right' => "margin-left: {$sideOffset}px",
        default => "margin-top: {$sideOffset}px",
    };

    $presetAttributes = [
        'data-slot' => 'hover-card-content',
        'data-align' => $align,
        'data-side' => $side,
    ];

    $mergedStyle = trim(
        collect([$offsetStyle, $style])
            ->filter()
            ->implode('; '),
    );
@endphp

<div @if ($mergedStyle !== '') style="{{ $mergedStyle }}" @endif
    @class($positionClass)>
    <div {{ $attributes->merge($presetAttributes)->class([$surfaceClass, $class]) }}
        x-bind:data-state="isOpen ? 'open' : 'closed'"
        x-cloak
        x-show="isOpen">
        {{ $slot }}
    </div>
</div>
