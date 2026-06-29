@blaze(fold: true)

@props([
    'index' => 0,
    'withHandle' => false,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex w-px shrink-0 cursor-col-resize items-center justify-center bg-border after:absolute after:inset-y-0 after:left-1/2 after:w-1 after:-translate-x-1/2 focus-visible:ring-1 focus-visible:ring-ring focus-visible:ring-offset-1 focus-visible:outline-hidden group-data-[orientation=vertical]/resizable-panel-group:h-px group-data-[orientation=vertical]/resizable-panel-group:w-full group-data-[orientation=vertical]/resizable-panel-group:cursor-row-resize group-data-[orientation=vertical]/resizable-panel-group:after:left-0 group-data-[orientation=vertical]/resizable-panel-group:after:h-1 group-data-[orientation=vertical]/resizable-panel-group:after:w-full group-data-[orientation=vertical]/resizable-panel-group:after:translate-x-0 group-data-[orientation=vertical]/resizable-panel-group:after:-translate-y-1/2',
    );

    $presetAttributes = [
        'data-slot' => 'resizable-handle',
        'tabindex' => '0',
        'role' => 'separator',
        'aria-orientation' => 'vertical',
        'aria-valuenow' => '50',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-on:pointerdown="startDrag({{ $index }}, $event)">
    @if ($withHandle)
        <div
            class="rounded-xs bg-border z-10 flex h-4 w-3 items-center justify-center border group-data-[orientation=vertical]/resizable-panel-group:rotate-90">
            <x-ui.icon aria-hidden="true"
                class="size-2.5"
                name="grip-vertical" />
        </div>
    @endif
</div>
