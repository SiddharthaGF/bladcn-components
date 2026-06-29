@blaze(fold: true)

@props([
    'placeholder' => 'Search...',
    'style' => null,
    'class' => null,
])

@php
    $inputGroupClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'h-8! rounded-lg! border-input/30 bg-input/30 shadow-none! *:data-[slot=input-group-addon]:pl-2!',
    );

    $inputClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'w-full text-sm outline-hidden disabled:cursor-not-allowed disabled:opacity-50',
    );

    $presetAttributes = [
        'type' => 'text',
        'data-slot' => 'command-input',
        'placeholder' => $placeholder,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div class="p-1 pb-0"
    data-slot="command-input-wrapper">
    <x-ui.input-group class="{{ $inputGroupClass }}">
        <input
            {{ $attributes->merge($presetAttributes)->class([$inputClass, $class]) }}
            x-model="search" />
        <x-ui.input-group.addon align="inline-end">
            <x-ui.icon aria-hidden="true"
                class="size-4 shrink-0 opacity-50"
                name="search" />
        </x-ui.input-group.addon>
    </x-ui.input-group>
</div>
