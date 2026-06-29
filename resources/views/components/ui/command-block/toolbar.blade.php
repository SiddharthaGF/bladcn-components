@blaze(fold: true)

@props([
    'showCopy' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex min-h-9 items-center gap-2 bg-[#17191e] px-3 py-1.5',
    );

    $presetAttributes = [
        'data-slot' => 'command-block-toolbar',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}

    @if ($showCopy)
        <x-ui.command-block.copy class="ml-auto" />
    @endif
</div>
