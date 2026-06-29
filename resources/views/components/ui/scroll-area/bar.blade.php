@blaze(fold: true)

@props([
    'orientation' => 'vertical',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('flex touch-none p-px transition-colors select-none')
        ->add(
            $orientation === 'horizontal'
                ? 'absolute right-0 bottom-0 left-0 z-20 h-2.5 flex-col border-t border-t-transparent'
                : 'absolute top-0 right-0 bottom-0 z-20 w-2.5 border-l border-l-transparent',
        );

    $thumbRef =
        $orientation === 'horizontal' ? 'horizontalThumb' : 'verticalThumb';

    $presetAttributes = [
        'data-slot' => 'scroll-area-scrollbar',
        'data-orientation' => $orientation,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <div class="bg-border relative flex-1 rounded-full"
        data-slot="scroll-area-thumb"
        x-ref="{{ $thumbRef }}"></div>
</div>
