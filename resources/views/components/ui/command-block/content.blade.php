@blaze(fold: true)

@aware(['defaultValue'])

@props([
    'value' => null,
    'command' => null,
    'style' => null,
    'class' => null,
])

@php
    $content = filled($command) ? trim($command) : trim($slot);

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-x-auto bg-[#0d0e12] px-3 py-2.5 font-mono text-[13px] leading-5 text-zinc-300',
    );

    $presetAttributes = [
        'data-slot' => 'command-block-content',
        'data-value' => $value,
        'data-command' => $content,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div :hidden="!isActive(@js($value))"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-show="isActive(@js($value))">
    <code>{{ $content }}</code>
</div>
