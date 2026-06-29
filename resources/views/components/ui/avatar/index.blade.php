@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/avatar --}}

@props([
    'size' => 'default',
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/avatar relative flex size-8 shrink-0 overflow-hidden rounded-full select-none data-[size=lg]:size-10 data-[size=sm]:size-6',
    );

    $presetAttributes = [
        'data-slot' => 'avatar',
        'data-size' => $size,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnAvatar()">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnAvatar', () => ({
                showFallback: true,

                onImageLoad() {
                    this.showFallback = false;
                },

                onImageError() {
                    this.showFallback = true;
                },
            }));
        });
    </script>
@endPushOnce
