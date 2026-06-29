@blaze(fold: true)

@aware(['transition' => true])

@props([
    'side' => 'top',
    'align' => 'center',
    'sideOffset' => 4,
    'style' => null,
    'class' => null,
])

@php
    $transition = filter_var($transition, FILTER_VALIDATE_BOOLEAN);

    $positionClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('absolute z-50 pointer-events-none')
        ->add(
            match ($side) {
                'bottom' => 'top-full left-0 w-full',
                'left'
                    => 'right-full top-1/2 w-max max-w-none -translate-y-1/2',
                'right'
                    => 'left-full top-1/2 w-max max-w-none -translate-y-1/2',
                default => 'bottom-full left-0 w-full',
            },
        )
        ->add(
            match ($side) {
                'left' => match ($align) {
                    'start' => 'flex items-start justify-end',
                    'end' => 'flex items-end justify-end',
                    default => 'flex items-center justify-end',
                },
                'right' => match ($align) {
                    'start' => 'flex items-start justify-start',
                    'end' => 'flex items-end justify-start',
                    default => 'flex items-center justify-start',
                },
                default => match ($align) {
                    'start' => 'flex justify-start',
                    'end' => 'flex justify-end',
                    default => 'flex justify-center',
                },
            },
        );

    $enterStart = match ($side) {
        'bottom' => 'opacity-0 scale-95 -translate-y-1',
        'left' => 'opacity-0 scale-95 translate-x-2',
        'right' => 'opacity-0 scale-95 -translate-x-2',
        default => 'opacity-0 scale-95 translate-y-1',
    };

    $surfaceClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'pointer-events-auto relative w-fit max-w-sm rounded-md bg-foreground px-3 py-1.5 text-xs whitespace-nowrap text-background',
        )
        ->add(
            match ($side) {
                'bottom' => 'origin-top',
                'left' => 'origin-right',
                'right' => 'origin-left',
                default => 'origin-bottom',
            },
        );

    // Half the arrow (size-2.5) protrudes toward the trigger; add it to the offset.
    $arrowClearance = 6;

    $offsetStyle = match ($side) {
        'bottom' => 'margin-top: ' . ($sideOffset + $arrowClearance) . 'px',
        'left' => 'margin-right: ' . ($sideOffset + $arrowClearance) . 'px',
        'right' => 'margin-left: ' . ($sideOffset + $arrowClearance) . 'px',
        default => 'margin-bottom: ' . ($sideOffset + $arrowClearance) . 'px',
    };

    $arrowClass = match ($side) {
        'bottom' => 'left-1/2 top-0 -translate-x-1/2 -translate-y-1/2',
        'left' => 'right-0 top-1/2 -translate-y-1/2 translate-x-1/2',
        'right' => 'left-0 top-1/2 -translate-y-1/2 -translate-x-1/2',
        default => 'bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2',
    };

    $presetAttributes = [
        'data-slot' => 'tooltip-content',
        'role' => 'tooltip',
        'data-side' => $side,
        'data-align' => $align,
    ];

    $mergedStyle = trim(
        collect([$offsetStyle, $style])
            ->filter()
            ->implode('; '),
    );
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$positionClass, $class]) }}
    @if ($mergedStyle !== '') style="{{ $mergedStyle }}" @endif>
    <div @if ($transition) x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="{{ $enterStart }}"
            x-transition:enter-end="opacity-100 scale-100 translate-x-0 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100 translate-x-0 translate-y-0"
            x-transition:leave-end="{{ $enterStart }}" @endif
        @class([$surfaceClass])
        x-cloak
        x-show="isOpen">
        {{ $slot }}
        <span aria-hidden="true"
            class="{{ $arrowClass }} bg-foreground pointer-events-none absolute block size-2.5 rotate-45 rounded-[2px]"
            data-slot="tooltip-arrow"></span>
    </div>
</div>
