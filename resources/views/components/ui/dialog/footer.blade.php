@blaze(fold: true)

@props([
    'showCloseButton' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex flex-col-reverse gap-2 sm:flex-row sm:justify-end',
    );

    $presetAttributes = [
        'data-slot' => 'dialog-footer',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
    @if ($showCloseButton)
        <x-ui.dialog.close>
            <x-ui.button variant="outline">Close</x-ui.button>
        </x-ui.dialog.close>
    @endif
</div>
