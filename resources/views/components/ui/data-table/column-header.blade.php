@blaze(fold: true)

@props([
    'column' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap',
    );

    $presetAttributes = [
        'data-slot' => 'data-table-column-header',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

@if ($column)
    <button class="hover:text-foreground -ml-3 flex h-8 items-center gap-1 px-3"
        type="button"
        x-on:click="toggleSort(@js($column))">
        {{ $slot }}
        <span class="text-muted-foreground text-xs"
            x-text="sortIcon(@js($column))"></span>
    </button>
@else
    <div
        {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
        {{ $slot }}
    </div>
@endif
